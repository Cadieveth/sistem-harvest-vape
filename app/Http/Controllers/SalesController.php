<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Purchase;
use App\Models\Journal;
use App\Models\Detail;
use App\Rules\InventoryCheck;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sales::query();

        $start_date = request()->get('start_date') ?? '';
        $end_date = request()->get('end_date') ?? '';
        $barang = request()->get('barang') ?? '';

        if ($start_date && $end_date) {
            $query->whereBetween('date_sales', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('date_sales', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('date_sales', '<=', $end_date);
        }

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

        // Handle reset filter
        if ($request->has('reset_filter')) {
            return redirect()->route('admin.sales.index');
        }

        $totalSalesQuantity = $query->sum('jumlah_sales');
        $totalSales = $query->sum('total_sales');
        $totalSalesDiscount = $query->sum('harga_potongan');
        $totalSalesNet = $query->sum('penjualan_bersih');

        $salesData = $query->get();
        $barangKeluar = 0;
        foreach ($salesData as $sale) {
            $hargaPokok = $sale->total_sales / (1.4);
            $barangKeluar += $hargaPokok;

            \Log::info('Sales Data:', [
                'kode_sales' => $sale->kode_sales,
                'total_sales' => $sale->total_sales,
                'hargaPokok' => $hargaPokok,
                'barangKeluar (cumulative)' => $barangKeluar
            ]);
        }

        $sales = $query->orderBy('date_sales', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(7);

        return view('admin.display.sales', [
            'sales' => $sales,
            'totalSalesQuantity' => $totalSalesQuantity,
            'totalSales' => $totalSales,
            'totalSalesDiscount' => $totalSalesDiscount,
            'totalSalesNet' => $totalSalesNet,
            'barangKeluar' => $barangKeluar,
        ]);
    }

    public function create()
    {
        // Ambil data dari Purchase
        $purchaseOptions = Purchase::select(
            'purchase.kode_barang AS kode_barang',
            'purchase.batch AS batch',
            'purchase.nama_barang AS nama_barang',
            'purchase.harga_barang AS harga_barang',
            'purchase.harga_pokok AS harga_pokok',
            'purchase.tanggal_pembelian AS tanggal_pembelian',
            'purchase.jumlah_barang AS jumlah_barang'
        )
        ->leftJoin('inventory', function($join) {
            $join->on('purchase.kode_barang', '=', 'inventory.kode_barang')
                ->on('purchase.batch', '=', 'inventory.batch');
        })
        ->where('purchase.kode_purchase', 'LIKE', '1624%')
        ->distinct()
        ->get();

        // Ambil data dari Detail
        $detailOptions = Detail::select(
            'detail.kode_barang AS kode_barang',
            'detail.batch AS batch',
            'detail.nama_barang AS nama_barang',
            'detail.harga_barang AS harga_barang'
        )
            ->leftJoin('inventory', function($join) {
                $join->on('detail.kode_barang', '=', 'inventory.kode_barang')
                    ->on('detail.batch', '=', 'inventory.batch');
            })
            ->where('detail.kode_data', 'LIKE', '1621%')
            ->distinct()
            ->get();

        $kodeBarangOptions = $purchaseOptions->concat($detailOptions)->sortBy(function($item) {
            return $item->batch;
        });


        return view('admin.form.addSales', [
            'kodeBarangOptions' => $kodeBarangOptions,
        ]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
        ];

        $data = $request->validate([
            'date_sales' => 'required',
            'kode_barang' => 'required',
            'jumlah_sales' => ['required', 'numeric', 'min:1'],
            'harga_potongan' => 'nullable|numeric|gte:0',
        ], $message);

        // Set harga_potongan to 0 if it is not provided or is equal to 0
        if ($request->input('harga_potongan') === null || $request->input('harga_potongan') == 0) {
            $data['harga_potongan'] = 0;
        }

        // Memisahkan kode_barang dan batch
        $kodeBarangBatch = explode('-', $request->input('kode_barang'));
        $kodeBarang = $kodeBarangBatch[0];
        $batch = $kodeBarangBatch[1];

        // Periksa stok di Inventory
        $inventory = \App\Models\Inventory::where('kode_barang', $kodeBarang)
            ->where('batch', $batch)
            ->first();

        if (!$inventory || $inventory->jumlah_barang < $request->input('jumlah_sales')) {
            return redirect()->back()->withErrors([
                'jumlah_sales' => 'Stok tidak cukup untuk jumlah yang diminta.',
            ])->withInput();
        }

        // Update stok di Inventory
        $inventory->decrement('jumlah_barang', $request->input('jumlah_sales'));

        // Menentukan harga berdasarkan sumber barang
        $selectedOption = Purchase::where('kode_barang', $kodeBarang)
            ->where('batch', $batch)
            ->first();

        if (!$selectedOption) {
            $selectedOption = Detail::where('kode_barang', $kodeBarang)
                ->where('batch', $batch)
                ->first();
            $hargaBarang = $selectedOption->harga_barang * 1.4;
        } else {
            $hargaBarang = $selectedOption->harga_pokok * 1.4;
        }

        $data['harga_barang'] = $hargaBarang;

        // Menambahkan nilai 'nama_barang' ke dalam data
        $data['nama_barang'] = $selectedOption->nama_barang;

        // Hitung total_sales
        $total_sales = $data['jumlah_sales'] * $hargaBarang;
        $data['total_sales'] = $total_sales;

        // Hitung penjualan_bersih
        $penjualan_bersih = $total_sales - $data['harga_potongan'];
        $data['penjualan_bersih'] = $penjualan_bersih;

        Sales::create($data);

        return redirect()->route('admin.sales.index')->with('success', 'Sales added successfully');
    }

    public function edit($id)
    {
        $sales = Sales::findOrFail($id);
        $purchaseOptions = Purchase::select(
            'purchase.kode_barang AS kode_barang',
            'purchase.batch AS batch',
            'purchase.nama_barang AS nama_barang',
            'purchase.harga_barang AS harga_barang',
            'purchase.harga_pokok AS harga_pokok',
            'purchase.tanggal_pembelian AS tanggal_pembelian',
            'purchase.jumlah_barang AS jumlah_barang'
        )
        ->leftJoin('inventory', function($join) {
            $join->on('purchase.kode_barang', '=', 'inventory.kode_barang')
                ->on('purchase.batch', '=', 'inventory.batch');
        })
        ->where('purchase.kode_purchase', 'LIKE', '1624%')
        ->distinct()
        ->get();

        // Ambil data dari Detail
        $detailOptions = Detail::select(
            'detail.kode_barang AS kode_barang',
            'detail.batch AS batch',
            'detail.nama_barang AS nama_barang',
            'detail.harga_barang AS harga_barang'
        )
            ->leftJoin('inventory', function($join) {
                $join->on('detail.kode_barang', '=', 'inventory.kode_barang')
                    ->on('detail.batch', '=', 'inventory.batch');
            })
            ->where('detail.kode_data', 'LIKE', '1621%')
            ->distinct()
            ->get();

        // Gabungkan data dari Purchase dan Detail, lalu urutkan berdasarkan batch
        $kodeBarangOptions = $purchaseOptions->concat($detailOptions)->sortBy(function($item) {
            return $item->batch;
        });

        return view('admin.edit.editSales', [
            'kodeBarangOptions' => $kodeBarangOptions,
            'sales' => $sales,
        ]);
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
        ];

        $data = $request->validate([
            'date_sales' => 'sometimes|required',
            'kode_barang' => 'required',
            'jumlah_sales' => ['required', 'numeric', 'min:1'],
            'harga_potongan' => 'nullable|numeric|gte:0',
        ], $message);

        // Ambil data Sales yang akan diperbarui
        $sales = Sales::findOrFail($id);

        // Memisahkan kode_barang dan batch
        $kodeBarangBatch = explode('-', $request->input('kode_barang'));
        $kodeBarang = $kodeBarangBatch[0];
        $batch = $kodeBarangBatch[1];

        // Periksa stok di Inventory
        $inventory = \App\Models\Inventory::where('kode_barang', $kodeBarang)
            ->where('batch', $batch)
            ->first();

        if (!$inventory || ($inventory->jumlah_barang + $sales->jumlah_sales) < $request->input('jumlah_sales')) {
            return redirect()->back()->withErrors([
                'jumlah_sales' => 'Stok tidak cukup untuk jumlah yang diminta.',
            ])->withInput();
        }

        // Update stok di Inventory
        $stok_lama = $inventory->jumlah_barang + $sales->jumlah_sales;
        $stok_baru = $stok_lama - $request->input('jumlah_sales');
        $inventory->update([
            'jumlah_barang' => $stok_baru,
        ]);

        // Menentukan harga berdasarkan sumber barang
        $selectedOption = Purchase::where('kode_barang', $kodeBarang)
            ->where('batch', $batch)
            ->first();

        if (!$selectedOption) {
            $selectedOption = Detail::where('kode_barang', $kodeBarang)
                ->where('batch', $batch)
                ->first();
            $hargaBarang = $selectedOption->harga_barang * 1.4;
        } else {
            $hargaBarang = $selectedOption->harga_pokok * 1.4;
        }

        $data['harga_barang'] = $hargaBarang;
        $data['nama_barang'] = $selectedOption->nama_barang;

        // Hitung total_sales
        $total_sales = $data['jumlah_sales'] * $hargaBarang;
        $data['total_sales'] = $total_sales;

        // Hitung penjualan_bersih
        $penjualan_bersih = $total_sales - $data['harga_potongan'];
        $data['penjualan_bersih'] = $penjualan_bersih;

        // Update data Sales
        $sales->update($data);

        // Update jurnal jika diperlukan (sesuaikan logika seperti di store)
        $kode_sales = $sales->kode_sales;

        $journalSales = Journal::where('ket_jurnal', 'LIKE', 'Penjualan ' . $sales->nama_barang . ' (' . $kode_sales . ')%')->first();
        if ($journalSales) {
            $journalSales->update([
                'tanggal_jurnal' => $data['date_sales'],
                'debit_acc_id' => 3,
                'kredit_acc_id' => 10,
                'debit_jurnal' => $total_sales,
                'kredit_jurnal' => $total_sales,
                'ket_jurnal' => 'Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
            ]);
        } else {
            Journal::create([
                'tanggal_jurnal' => $data['date_sales'],
                'debit_acc_id' => 3,
                'kredit_acc_id' => 10,
                'debit_jurnal' => $total_sales,
                'kredit_jurnal' => $total_sales,
                'ket_jurnal' => 'Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
            ]);
        }

        $journalSalesCost = Journal::where('ket_jurnal', 'LIKE', 'Penjualan ' . $sales->nama_barang . ' (' . $kode_sales . ')%')->where('debit_acc_id', 24)->first();
        if ($journalSalesCost) {
            $journalSalesCost->update([
                'tanggal_jurnal' => $data['date_sales'],
                'debit_acc_id' => 24,
                'kredit_acc_id' => 7,
                'debit_jurnal' => $total_sales / 1.4,
                'kredit_jurnal' => $total_sales / 1.4,
                'ket_jurnal' => 'Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
            ]);
        } else {
            Journal::create([
                'tanggal_jurnal' => $data['date_sales'],
                'debit_acc_id' => 24,
                'kredit_acc_id' => 7,
                'debit_jurnal' => $total_sales / 1.4,
                'kredit_jurnal' => $total_sales / 1.4,
                'ket_jurnal' => 'Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
            ]);
        }

        $journalDiscount = Journal::where('ket_jurnal', 'LIKE', 'Potongan Penjualan ' . $sales->nama_barang . ' (' . $kode_sales . ')%')->first();
        if ($data['harga_potongan'] > 0) {
            if ($journalDiscount) {
                $journalDiscount->update([
                    'tanggal_jurnal' => $data['date_sales'],
                    'debit_acc_id' => 15,
                    'kredit_acc_id' => 3,
                    'debit_jurnal' => $data['harga_potongan'],
                    'kredit_jurnal' => $data['harga_potongan'],
                    'ket_jurnal' => 'Potongan Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
                ]);
            } else {
                Journal::create([
                    'tanggal_jurnal' => $data['date_sales'],
                    'debit_acc_id' => 15,
                    'kredit_acc_id' => 3,
                    'debit_jurnal' => $data['harga_potongan'],
                    'kredit_jurnal' => $data['harga_potongan'],
                    'ket_jurnal' => 'Potongan Penjualan ' . $data['nama_barang'] . ' (' . $kode_sales . ')',
                ]);
            }
        } else {
            if ($journalDiscount) {
                $journalDiscount->delete();
            }
        }

        return redirect()->route('admin.sales.index')->with('success', 'Sales updated successfully');
    }

    public function destroy($id)
    {
        $sales = Sales::findOrFail($id);
        $sales->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Sales deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected for deletion.']);
        }

        try {
            Sales::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected items have been deleted.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the items.']);
        }
    }
}

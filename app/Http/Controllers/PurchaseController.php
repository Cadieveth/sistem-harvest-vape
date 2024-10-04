<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\Vendor;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::query();

        $start_date = request()->get('start_date') ?? '';
        $end_date = request()->get('end_date') ?? '';
        $barang = request()->get('barang') ?? '';

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_pembelian', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tanggal_pembelian', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tanggal_pembelian', '<=', $end_date);
        }

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

         // Clone the query for each calculation to prevent modifying the original query
        $totalPurchaseQuantityQuery = clone $query;
        $totalPurchaseQuery = clone $query;
        $totalPurchaseFreightQuery = clone $query;
        $totalPurchaseAssetQuery = clone $query;
        $totalPurchaseCashOutQuery = clone $query;

        // Data Card
        $totalPurchaseQuantity = $totalPurchaseQuantityQuery->where('kode_purchase', 'LIKE', '1624%')->sum('jumlah_barang');
        $totalPurchase = $totalPurchaseQuery->where('kode_purchase', 'LIKE', '1624%')->sum('total_purchase');
        $totalPurchaseFreight = $totalPurchaseFreightQuery->where('kode_purchase', 'LIKE', '1624%')->sum('biaya_kirim');
        $totalPurchaseAsset = $totalPurchaseAssetQuery->where(function($query) {
            $query->where('kode_purchase', 'LIKE', '1625%')
                ->orWhere('kode_purchase', 'LIKE', '1626%');
        })->sum('total_purchase');
        $totalPurchaseCashOut = $totalPurchaseCashOutQuery->sum('total_purchase');

        // Handle reset filter
        if ($request->has('reset_filter')) {
            return redirect()->route('admin.purchases.index');
        }

        $purchases = $query->orderBy('tanggal_pembelian', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(7);

        return view('admin.display.purchase', [
            'purchases' => $purchases,
            'totalPurchaseQuantity' => $totalPurchaseQuantity,
            'totalPurchase' => $totalPurchase,
            'totalPurchaseFreight' => $totalPurchaseFreight,
            'totalPurchaseCashOut' => $totalPurchaseCashOut,
            'totalPurchaseAsset' => $totalPurchaseAsset,
        ]);
    }

    public function create()
    {

        $persediaan = $this->getLatestCode('Persediaan');
        $peralatan = $this->getLatestCode('Peralatan');
        $perlengkapan = $this->getLatestCode('Perlengkapan');
        $vendor = Vendor::all();

        return view('admin.form.addPurchase', compact('vendor', 'persediaan', 'peralatan', 'perlengkapan'));
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
        ];

        $validated = $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|numeric|min:1',
            'harga_barang' => 'required|numeric|min:1',
            'tanggal_pembelian' => 'required',
            'vendor_id' => 'required',
            'ket_purchase' => 'required',
            'biaya_kirim' => 'nullable|numeric|gte:0',
        ], $message);

        if (!isset($validated['biaya_kirim'])) {
            $validated['biaya_kirim'] = 0;
        }

        $purchase = Purchase::create($validated);

        $purchase->calculateHargaPokok();
        $purchase->calculateTotalPurchase();
        $purchase->save();

        if ($validated['biaya_kirim'] > 0) {
            Payment::create([
                'tanggal_payment' => $validated['tanggal_pembelian'],
                'ket_payment' => 'Pengiriman',
                'keterangan' => $validated['nama_barang'],
                'cost_payment' => $validated['biaya_kirim'],
            ]);
        }

        $debit_acc_id = match ($validated['ket_purchase']) {
            'Peralatan' => 13,
            'Perlengkapan' => 14,
            default => 7,
        };

        Journal::create([
            'tanggal_jurnal' => $validated['tanggal_pembelian'],
            'debit_acc_id' => $debit_acc_id,
            'kredit_acc_id' => 3,
            'debit_jurnal' => $purchase->total_purchase,
            'kredit_jurnal' => $purchase->total_purchase,
            'ket_jurnal' => 'Pembelian ' . $validated['nama_barang'] . ' (' . $validated['kode_barang'] . ')',
        ]);

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase added successfully');
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $vendor = Vendor::all();
        return view('admin.edit.editPurchase', compact('vendor', 'purchase'));
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
        ];

        $purchase = Purchase::findOrFail($id);

        $validated = $request->validate([
            'kode_barang' => 'sometimes|required' . $purchase->id,
            'tanggal_pembelian' => 'required',
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|numeric|min:1',
            'harga_barang' => 'required|numeric|min:1',
            'vendor_id' => 'required',
            'ket_purchase' => 'required',
            'biaya_kirim' => 'nullable|numeric|gte:0',
        ], $message);

        if (!$request->filled('kode_barang')) {
            $validated['kode_barang'] = $purchase->kode_barang;
        }

        if (!isset($validated['biaya_kirim'])) {
            $validated['biaya_kirim'] = 0;
        }

        $originalBiayaKirim = $purchase->biaya_kirim;
        $purchase->update($validated);

        $purchase->calculateHargaPokok();
        $purchase->calculateTotalPurchase();
        $purchase->save();

        if ($originalBiayaKirim != $validated['biaya_kirim']) {
            if ($validated['biaya_kirim'] == 0) {
                Payment::where('cost_payment', $originalBiayaKirim)->where('ket_payment', 'Pengiriman')->delete();
            } elseif ($originalBiayaKirim == 0 && $validated['biaya_kirim'] > 0) {
                Payment::create([
                    'tanggal_payment' => $purchase->tanggal_pembelian,
                    'ket_payment' => 'Pengiriman',
                    'cost_payment' => $validated['biaya_kirim'],
                ]);
            } else {
                Payment::where('cost_payment', $originalBiayaKirim)->where('ket_payment', 'Pengiriman')
                    ->update(['cost_payment' => $validated['biaya_kirim']]);
            }
        }

        $debit_acc_id = match ($validated['ket_purchase']) {
            'Peralatan' => 13,
            'Perlengkapan' => 14,
            default => 7,
        };

        // Gunakan id dan kode_barang untuk pencarian Journal
        $journal = Journal::where('ket_jurnal', 'LIKE', 'Pembelian ' . $purchase->nama_barang . ' (' . $purchase->kode_barang . ')')->first();
        if ($journal) {
            $journal->update([
                'tanggal_jurnal' => $purchase->tanggal_pembelian,
                'debit_acc_id' => $debit_acc_id,
                'debit_jurnal' => $purchase->total_purchase,
                'kredit_jurnal' => $purchase->total_purchase,
                'ket_jurnal' => 'Pembelian ' . $validated['nama_barang'] . ' (' . $validated['kode_barang'] . ')',
            ]);
        } else {
            Journal::create([
                'tanggal_jurnal' => $purchase->tanggal_pembelian,
                'debit_acc_id' => $debit_acc_id,
                'kredit_acc_id' => 3,
                'debit_jurnal' => $purchase->total_purchase,
                'kredit_jurnal' => $purchase->total_purchase,
                'ket_jurnal' => 'Pembelian ' . $validated['nama_barang'] . ' (' . $validated['kode_barang'] . ')',
            ]);
        }

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase updated successfully');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->biaya_kirim > 0) {
            Payment::where('cost_payment', $purchase->biaya_kirim)->where('ket_payment', 'Pengiriman')->delete();
        }

        $purchase->delete();

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted successfully');
    }

    private function getLatestCode($category)
    {
        $prefixes = [
            'Persediaan' => ['LQD%', 'CL', 'DVC', 'ACS', 'BTR'],
            'Peralatan' => ['124'],
            'Perlengkapan' => ['105'],
        ];

        $latestCodes = [];
        foreach ($prefixes[$category] as $prefix) {
            $latestPurchase = Purchase::where('kode_barang', 'LIKE', 'HV/%/' . $prefix)
                ->orderBy(DB::raw('CAST(SUBSTRING(kode_barang, 4, LOCATE("/", kode_barang, 4) - 4) AS UNSIGNED)'), 'desc')
                ->first();

            if ($latestPurchase) {
                $lastNumber = substr($latestPurchase->kode_barang, 3, strpos($latestPurchase->kode_barang, '/', 3) - 3);
            } else {
                $lastNumber = '000';
            }

            $latestCodes[$prefix] = $lastNumber;
        }

        return $latestCodes;
    }
}

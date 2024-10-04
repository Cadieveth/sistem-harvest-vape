<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Detail;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $query = Detail::query();

        $kode = request()->get('kode') ?? '';
        $barang = request()->get('barang') ?? '';

        if ($kode) {
            $query->where('kode_barang', 'LIKE', '%' . $kode . '%');
        }

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.details.index');
        }

        $totalPersediaanQuery = clone $query;
        $totalPeralatanQuery = clone $query;
        $totalPerlengkapanQuery = clone $query;

        $totalPersediaan = $totalPersediaanQuery->where('kode_data', 'LIKE', '1621%')
            ->sum(DB::raw('jumlah_barang * harga_barang'));

        $totalPeralatan = $totalPeralatanQuery->where('kode_data', 'LIKE', '1622%')
            ->sum(DB::raw('jumlah_barang * harga_barang'));

        $totalPerlengkapan = $totalPerlengkapanQuery->where('kode_data', 'LIKE', '1623%')
            ->sum(DB::raw('jumlah_barang * harga_barang'));

        $detail = Detail::all();

        $details = $query->paginate(10);

        return view('admin.display.detail', [
            'details' => $details,
            'detail' => $detail,
            'totalPersediaan' => $totalPersediaan,
            'totalPeralatan' => $totalPeralatan,
            'totalPerlengkapan' => $totalPerlengkapan,
        ]);
    }

    public function create()
    {
        $detail = Detail::all();

        return view('admin.form.addDetail', ['detail' => $detail]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
            'kode_barang.unique' => ':attribute telah digunakan',
        ];

        $validated = $request->validate([
            'kode_barang' => 'required|unique:detail,kode_barang',
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|numeric|min:1',
            'harga_barang' => 'required|numeric|min:1',
            'ket_barang' => 'required',
        ], $message);

        $detail = Detail::create($validated);

        return redirect()->route('admin.details.index')->with('success', 'Data added successfully');
    }

    public function edit($id)
    {
        $detail = Detail::findOrFail($id);

        return view('admin.edit.editDetail', ['detail' => $detail]);
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'gte' => ':attribute tidak bisa minus',
        ];

        $detail = Detail::findOrFail($id);

        $validated = $request->validate([
            'kode_barang' => 'sometimes|required' . $detail->$id,
            'nama_barang' => 'required',
            'jumlah_barang' => 'required|numeric|min:1',
            'harga_barang' => 'required|numeric|min:1',
            'ket_barang' => 'required',
        ], $message);

        if (!$request->filled('kode_barang')) {
            $validated['kode_barang'] = $detail->kode_barang;
        }

        $detail->update($validated);

        return redirect()->route('admin.details.index')->with('success', 'Data updated successfully');
    }

    public function destroy($id)
    {
        $detail = Detail::findOrFail($id);

        $detail->delete();

        return redirect()->route('admin.details.index')->with('success', 'Data deleted successfully');
    }
}

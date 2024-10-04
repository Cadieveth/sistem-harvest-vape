<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        $nama_vendor = $request->get('nama_vendor') ?? '';
        $kontak_vendor = $request->get('kontak_vendor') ?? '';
        $alamat_vendor = $request->get('alamat_vendor') ?? '';

        if ($nama_vendor) {
            $query->where('nama_vendor', 'LIKE', '%' . $nama_vendor . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.vendors.index');
        }

        $vendors = $query->paginate(7);

        return view('admin.display.vendor', ['vendors' => $vendors]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
        ];

        $validated = $request->validate([
            'nama_vendor' => 'required',
            'kontak_vendor' => 'required',
            'alamat_vendor' => 'required',
        ], $message);

        Vendor::create($validated);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor added successfully');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.edit.editVendor', ['vendor' => $vendor]);
    }


    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
        ];

        $validated = $request->validate([
            'nama_vendor' => 'required',
            'kontak_vendor' => 'required',
            'alamat_vendor' => 'required',
        ], $message);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($validated);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully');
    }
}

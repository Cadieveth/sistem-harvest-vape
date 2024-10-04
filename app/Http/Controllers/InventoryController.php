<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{

    public function index(Request $request)
    {
        $query = Inventory::where(function ($query) {
            $query->where('kode_purchase', 'LIKE', '1624%')
                  ->orWhere('kode_purchase', 'LIKE', '1621%');
        });

        $barang = request()->get('barang') ?? '';

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.inventories.index');
        }

        $totalInventoryQuantity = $query->sum('jumlah_barang');

        $inventories = $query->get();

        $totalInventory = 0;
        foreach ($inventories as $inventory) {
            $totalInventory += $inventory->jumlah_barang * $inventory->harga_barang;
        }

        $inventories = $query->orderBy('id', 'desc')->paginate(7);

        return view('admin.display.inventory', [
            'inventories' => $inventories,
            'totalInventoryQuantity' => $totalInventoryQuantity,
            'totalInventory' => $totalInventory,
        ]);
    }

    public function peralatan(Request $request)
    {
        $query = Inventory::where(function ($query) {
            $query->where('kode_purchase', 'LIKE', '1625%')
                  ->orWhere('kode_purchase', 'LIKE', '1622%');
        });

        $barang = request()->get('barang') ?? '';

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.inventories.index');
        }

        $inventories = $query->orderBy('id', 'desc')->paginate(7);

        return view('admin.display.asset', ['inventories' => $inventories]);
    }

    public function perlengkapan(Request $request)
    {
        $query = Inventory::where(function ($query) {
            $query->where('kode_purchase', 'LIKE', '1626%')
                  ->orWhere('kode_purchase', 'LIKE', '1623%');
        });

        $barang = request()->get('barang') ?? '';

        if ($barang) {
            $query->where('nama_barang', 'LIKE', '%' . $barang . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.inventories.index');
        }

        $inventories = $query->orderBy('id', 'desc')->paginate(7);

        return view('admin.display.assetPerlengkapan', ['inventories' => $inventories]);
    }

    // public function peralatan()
    // {
    //     $inventories = Inventory::whereHas('purchase', function ($query) {
    //         $query->where('kode_purchase', 'LIKE', '1625%');
    //     })->paginate(7);

    //     return view('admin.display.asset', ['inventories' => $inventories]);
    // }

    // public function perlengkapan()
    // {
    //     $inventories = Inventory::whereHas('purchase', function ($query) {
    //         $query->where('kode_purchase', 'LIKE', '1626%');
    //     })->paginate(7);

    //     return view('admin.display.assetPerlengkapan', ['inventories' => $inventories]);
    // }
}

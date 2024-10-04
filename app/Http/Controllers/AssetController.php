<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return view('admin.display.asset');
    }

    public function peralatan()
    {
        return view('admin.display.assetPeralatan');
    }

    public function perlengkapan()
    {
        return view('admin.display.assetPerlengkapan');
    }
}

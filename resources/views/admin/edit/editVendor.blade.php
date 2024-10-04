@extends('layouts.app')

@section('title')
    <title>Edit Vendor - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Edit Data Vendor</h5>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.vendors.update', ['id' => $vendor->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_vendor" class="form-label">Nama Vendor</label>
                        <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror"
                            id="nama_vendor" name="nama_vendor" placeholder="(Contoh: Juicenation Co.)"
                            value="{{ old('nama_vendor', $vendor->nama_vendor) }}">
                        <div class="invalid-feedback">
                            @error('nama_vendor')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kontak_vendor" class="form-label">Telepon</label>
                        <input type="number" class="form-control @error('kontak_vendor') is-invalid @enderror"
                            id="kontak_vendor" name="kontak_vendor" placeholder="(Contoh: 81234567890)"
                            value="{{ old('kontak_vendor', $vendor->kontak_vendor) }}">
                        <div class="invalid-feedback">
                            @error('kontak_vendor')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat_vendor" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat_vendor') is-invalid @enderror"
                            id="alamat_vendor" name="alamat_vendor" placeholder="Alamat vendor"
                            value="{{ old('alamat_vendor', $vendor->alamat_vendor) }}">
                        <div class="invalid-feedback">
                            @error('alamat_vendor')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

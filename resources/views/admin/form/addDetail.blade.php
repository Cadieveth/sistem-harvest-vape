@extends('layouts.app')

@section('title')
    <title>Add Detail - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold" style="width: 92%">Form Add Data Detail</h5>
                    <a href="{{ route('admin.details.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.details.store') }}">
                    @csrf
                    <div class="flex justify-between">
                        <div class="mb-3 w-1/2 mr-3">
                            <label for="kode_barang" class="form-label w-full">
                                <div class="flex justify-between">
                                    <div>Kode Barang</div>
                                    <div class="flex justify-end items-end" data-bs-toggle="tooltip" title="Help"><a a
                                            href="#" class="flex justify-end items-end" data-bs-toggle="modal"
                                            data-bs-target="#detailModal"><i class="material-icons"
                                                style="font-size: 16px;">error_outline</i></a>
                                    </div>
                                </div>
                            </label>
                            <input type="text" class="form-control @error('kode_barang') is-invalid @enderror"
                                id="kode_barang" name="kode_barang" placeholder="HV/XXX/XX..."
                                value="{{ old('kode_barang') }}">
                            <div class="invalid-feedback">
                                @error('kode_barang')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 w-1/2">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang" placeholder="RillaCake Strawberry Nic 6 60ml"
                                value="{{ old('nama_barang') }}">
                            <div class="invalid-feedback">
                                @error('nama_barang')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="flex justify-between mb-3 w-1/2 mr-3">
                            <div class="mb-3 w-1/3 mr-3">
                                <label for="jumlah_barang" class="form-label">Jumlah</label>
                                <input type="number" class="form-control @error('jumlah_barang') is-invalid @enderror"
                                    id="jumlah_barang" name="jumlah_barang" placeholder="Jumlah item"
                                    value="{{ old('jumlah_barang') }}">
                                <div class="invalid-feedback">
                                    @error('jumlah_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 w-2/3">
                                <label for="harga_barang" class="form-label">Harga per Item Barang</label>
                                <input type="number" class="form-control @error('harga_barang') is-invalid @enderror"
                                    id="harga_barang" name="harga_barang" placeholder="Rp"
                                    value="{{ old('harga_barang') }}">
                                <div class="invalid-feedback">
                                    @error('harga_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 w-1/2">
                            <label for="ket_barang" class="form-label">Keterangan</label>
                            <select class="form-select @error('ket_barang') is-invalid @enderror" id="ket_barang"
                                name="ket_barang">
                                <option value="" disabled selected>- Choose Option -</option>
                                <option value="Persediaan" {{ old('ket_barang') == 'Persediaan' ? 'selected' : '' }}>
                                    Persediaan Barang Dagangan</option>
                                <option value="Peralatan" {{ old('ket_barang') == 'Peralatan' ? 'selected' : '' }}>
                                    Peralatan</option>
                                <option value="Perlengkapan" {{ old('ket_barang') == 'Perlengkapan' ? 'selected' : '' }}>
                                    Perlengkapan</option>
                            </select>
                            <div class="invalid-feedback">
                                @error('ket_barang')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

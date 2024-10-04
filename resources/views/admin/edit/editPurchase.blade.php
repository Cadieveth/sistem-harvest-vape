@extends('layouts.app')

@section('title')
    <title>Edit Purchase - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Edit Data</h5>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>
                <p class="-mt-8 mb-5 font-semibold" style="font-size: 15px">Pembelian {{ $purchase->kode_purchase }}</p>

                <form method="POST" action="{{ route('admin.purchases.update', ['id' => $purchase->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang</label>
                                <input type="text" class="form-control @error('kode_barang') is-invalid @enderror"
                                    id="kode_barang" name="kode_barang" placeholder="XXXXXXX"
                                    value="{{ old('kode_barang', $purchase->kode_barang) }}" disabled>
                                <div class="invalid-feedback">
                                    @error('kode_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill" style="width: 50%">
                            <div class="mb-3">
                                <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror"
                                    id="tanggal_pembelian" name="tanggal_pembelian" placeholder="XXXXXXX"
                                    value="{{ old('tanggal_pembelian', $purchase->tanggal_pembelian) }}">
                                <div class="invalid-feedback">
                                    @error('tanggal_pembelian')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                    id="nama_barang" name="nama_barang"
                                    placeholder="(Contoh: RillaCake Strawberry Nic 6 60ml)"
                                    value="{{ old('nama_barang', $purchase->nama_barang) }}">
                                <div class="invalid-feedback">
                                    @error('nama_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex" style="width: 50%">
                            <div class="me-3" style="width: 20%">
                                <label for="jumlah_barang" class="form-label">Jumlah</label>
                                <input type="number" class="form-control @error('jumlah_barang') is-invalid @enderror"
                                    id="jumlah_barang" name="jumlah_barang" placeholder="Jumlah item"
                                    value="{{ old('jumlah_barang', $purchase->jumlah_barang) }}">
                                <div class="invalid-feedback">
                                    @error('jumlah_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="me-3" style="width: 40%">
                                <label for="harga_barang" class="form-label">Harga per Item Barang</label>
                                <input type="number" class="form-control @error('harga_barang') is-invalid @enderror"
                                    id="harga_barang" name="harga_barang" placeholder="Harga item"
                                    value="{{ old('harga_barang', $purchase->harga_barang) }}">
                                <div class="invalid-feedback">
                                    @error('harga_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div style="width: 40%">
                                <label for="biaya_kirim" class="form-label">Biaya Kirim</label>
                                <input type="number" class="form-control @error('biaya_kirim') is-invalid @enderror"
                                    id="biaya_kirim" name="biaya_kirim" placeholder="Rp"
                                    value="{{ old('biaya_kirim', $purchase->biaya_kirim) }}">
                                <div class="invalid-feedback">
                                    @error('biaya_kirim')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id"
                                    name="vendor_id">
                                    <option value="" disabled>- Choose Vendor -</option>
                                    @foreach ($vendor as $row)
                                        <option value="{{ $row->id }}"
                                            {{ old('vendor_id', $purchase->vendor_id) == $row->id ? 'selected' : '' }}>
                                            {{ $row->nama_vendor }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('vendor_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill" style="width: 50%">
                            <div class="mb-3">
                                <label for="ket_purchase" class="form-label">Keterangan</label>
                                <select class="form-select @error('ket_purchase') is-invalid @enderror" id="ket_purchase"
                                    name="ket_purchase">
                                    <option value="" disabled>- Choose Option -</option>
                                    <option value="Persediaan"
                                        {{ old('ket_purchase', $purchase->ket_purchase) == 'Persediaan' ? 'selected' : '' }}>
                                        Persediaan Barang Dagangan</option>
                                    <option value="Peralatan"
                                        {{ old('ket_purchase', $purchase->ket_purchase) == 'Peralatan' ? 'selected' : '' }}>
                                        Peralatan</option>
                                    <option value="Perlengkapan"
                                        {{ old('ket_purchase', $purchase->ket_purchase) == 'Perlengkapan' ? 'selected' : '' }}>
                                        Perlengkapan</option>
                                </select>
                                <div class="invalid-feedback">
                                    @error('ket_purchase')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title')
    <title>Add Purchase - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Add Data Pembelian</h5>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.purchases.store') }}">
                    @csrf
                    <div class="d-flex">
                        <div class="flex-fill me-3">
                            <div class="mb-3">
                                <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror"
                                    id="tanggal_pembelian" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}">
                                <div class="invalid-feedback">
                                    @error('tanggal_pembelian')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3">
                            <div class="mb-3">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id"
                                    name="vendor_id">
                                    <option value="" disabled selected>- Choose Vendor -</option>
                                    @foreach ($vendor as $row)
                                        <option value="{{ $row->id }}"
                                            {{ old('vendor_id') == $row->id ? 'selected' : '' }}>{{ $row->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('vendor_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-3">
                                <label for="ket_purchase" class="form-label">Keterangan</label>
                                <select class="form-select @error('ket_purchase') is-invalid @enderror" id="ket_purchase"
                                    name="ket_purchase">
                                    <option value="" disabled selected>- Choose Option -</option>
                                    <option value="Persediaan" {{ old('ket_purchase') == 'Persediaan' ? 'selected' : '' }}>
                                        Persediaan Barang Dagangan</option>
                                    <option value="Peralatan" {{ old('ket_purchase') == 'Peralatan' ? 'selected' : '' }}>
                                        Peralatan</option>
                                    <option value="Perlengkapan"
                                        {{ old('ket_purchase') == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                                </select>
                                <div class="invalid-feedback">
                                    @error('ket_purchase')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 15%">
                            <div class="mb-3">
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
                        </div>
                        <div class="flex-fill me-3" style="width: 65%">
                            <div class="mb-3">
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
                        <div class="flex-fill" style="width: 20%">
                            <div class="mb-3">
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
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
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
                        <div class="flex-fill" style="width: 50%">
                            <div class="mb-3">
                                <label for="biaya_kirim" class="form-label">Biaya Pengiriman</label>
                                <input type="number" class="form-control @error('biaya_kirim') is-invalid @enderror"
                                    id="biaya_kirim" name="biaya_kirim" placeholder="Rp"
                                    value="{{ old('biaya_kirim') }}">
                                <div class="invalid-feedback">
                                    @error('biaya_kirim')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-full">
                        <div class="flex justify-between w-full">
                            <div class="w-1/2">
                                <h5 class="modal-title font-bold text-sm" id="detailModalLabel">Informasi Kode</h5>
                            </div>
                            <div class="w-1/2 flex justify-end items-end">
                                <button type="button" data-bs-dismiss="modal">
                                    <i class="material-icons" style="font-size: 16px;">clear</i>
                                </button>
                            </div>
                        </div>
                        <div class="-mt-1 text-xs">keterangan kode yang ditampilkan adalah kode terakhir dalam data</div>
                    </div>
                </div>
                <div class="modal-body -mt-3">
                    <div class="w-full">
                        <div id="table">
                            <div class="flex justify-between mb-1">
                                <div class="flex justify-center font-bold" style="width: 33%">Persediaan
                                </div>
                                <div class="flex justify-center font-bold" style="width: 34%">Peralatan
                                </div>
                                <div class="flex justify-center font-bold" style="width: 33%">Perlengkapan
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div id="persediaan" class="flex justify-center mb-1" style="width: 33%; height:auto;">
                                    @foreach ($persediaan as $key => $value)
                                        HV/{{ $value }}/{{ $key }} <br>
                                    @endforeach
                                </div>
                                <div id="peralatan" class="flex justify-center mb-1" style="width: 34%; height:auto;">
                                    @foreach ($peralatan as $key => $value)
                                        <td>HV/{{ $value }}/{{ $key }}</td>
                                    @endforeach
                                </div>
                                <div id="perlengkapan" class="flex justify-center mb-1" style="width: 33%; height:auto;">
                                    @foreach ($perlengkapan as $key => $value)
                                        <td>HV/{{ $value }}/{{ $key }}</td>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Detail Modal --}}
@endsection

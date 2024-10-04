@extends('layouts.app')

@section('title')
    <title>Add Sales - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Add Data Penjualan</h5>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.sales.store') }}">
                    @csrf
                    <div class="d-flex" style="width: 30%">
                        <div class="flex-fill me-3" style="width: 30%">
                            <div class="mb-3">
                                <label for="date_sales" class="form-label">Tanggal Penjualan</label>
                                <input type="date" class="form-control @error('date_sales') is-invalid @enderror"
                                    id="date_sales" name="date_sales" value="{{ old('date_sales') }}">
                                <div class="invalid-feedback">
                                    @error('date_sales')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill" style="width: 50%">
                        <div class="mb-3">
                            <label for="kodeBarang" class="form-label">Kode Barang</label>
                            <select class="form-select select2 @error('kode_barang') is-invalid @enderror" id="kodeBarang"
                                name="kode_barang">
                                <option value="" disabled selected>- Choose Option -</option>
                                @foreach ($kodeBarangOptions as $option)
                                    @php
                                        $hargaBarang = $option->harga_pokok
                                            ? $option->harga_pokok * 1.4
                                            : $option->harga_barang * 1.4;
                                        $kodeNamaBarang =
                                            $option->kode_barang .
                                            '-' .
                                            str_pad($option->batch, 2, '0', STR_PAD_LEFT) .
                                            ' - ' .
                                            $option->nama_barang;

                                        if ($option->jumlah_barang === 0) {
                                            $kodeNamaBarang .= ' (STOK HABIS)';
                                        }
                                    @endphp
                                    @if (
                                        !$loop->first &&
                                            $kodeBarangOptions[$loop->index - 1]->kode_barang === $option->kode_barang &&
                                            $kodeBarangOptions[$loop->index - 1]->batch === $option->batch)
                                        @continue
                                    @endif
                                    <option
                                        value="{{ $option->kode_barang . '-' . str_pad($option->batch, 2, '0', STR_PAD_LEFT) }}"
                                        data-nama="{{ $option->nama_barang }}" data-harga="{{ $hargaBarang }}">
                                        {{ $kodeNamaBarang }}
                                    </option>
                                @endforeach

                            </select>
                            <div class="invalid-feedback">
                                @error('kode_barang')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="namaBarang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                    id="namaBarang" name="nama_barang" placeholder="Auto Generate dari Kode Barang" disabled
                                    value="{{ old('nama_barang') }}">
                                <div class="invalid-feedback">
                                    @error('nama_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3" style="width: 25%">
                            <div class="mb-3">
                                <label for="jumlahBarang" class="form-label">Jumlah</label>
                                <input type="number" class="form-control @error('jumlah_sales') is-invalid @enderror"
                                    id="jumlahBarang" name="jumlah_sales" placeholder="Jumlah item"
                                    value="{{ old('jumlah_sales') }}">
                                <div class="invalid-feedback">
                                    @error('jumlah_sales')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill" style="width: 25%">
                            <div class="mb-3">
                                <label for="hargaBarang" class="form-label">Harga per Item Barang</label>
                                <input type="number" class="form-control @error('harga_barang') is-invalid @enderror"
                                    id="hargaBarang" name="harga_barang" placeholder="Harga item" disabled
                                    value="{{ old('harga_barang') }}">
                                <div class="invalid-feedback">
                                    @error('harga_barang')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill" style="width: 25%">
                            <div class="mb-3">
                                <label for="harga_potongan" class="form-label">Potongan Harga</label>
                                <input type="number" class="form-control @error('harga_potongan') is-invalid @enderror"
                                    id="harga_potongan" name="harga_potongan" placeholder="Rp"
                                    value="{{ old('harga_potongan') }}">
                                <div class="invalid-feedback">
                                    @error('harga_potongan')
                                        {{ $message }}
                                    @enderror
                                </div>
                                <div class="form-text">
                                    Potongan harga tidak berlaku kelipatan barang yang sama. Kosongkan jika tidak ada diskon
                                    pada penjualan.
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kodeBarangSelect = $('#kodeBarang').select2({
                placeholder: '- Choose Option -',
                allowClear: true
            });

            const namaBarangInput = document.getElementById("namaBarang");
            const hargaBarangInput = document.getElementById("hargaBarang");

            kodeBarangSelect.on("change", function() {
                const selectedKode = kodeBarangSelect.val();
                const selectedOption = kodeBarangSelect.find('option:selected');
                const namaBarang = selectedOption.data("nama");
                const hargaBarang = selectedOption.data("harga");

                namaBarangInput.value = namaBarang;
                hargaBarangInput.value = hargaBarang;
            });
        });

        function updateKodeBarangOptions() {
            // ... Fetch latest data from server and update kodeBarangOptions
        }

        setInterval(updateKodeBarangOptions, 60000);
    </script>
@endsection

@extends('layouts.app')

@section('title')
    <title>Add Payment - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Add Data Pembayaran</h5>
                    <a href="{{ route('admin.payment.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.payment.store') }}">
                    @csrf
                    <div class="d-flex">
                        <div class="flex-fill me-3">
                            <div class="mb-3">
                                <label for="tanggal_payment" class="form-label">Tanggal Payment</label>
                                <input type="date" class="form-control @error('tanggal_payment') is-invalid @enderror"
                                    id="tanggal_payment" name="tanggal_payment" value="{{ old('tanggal_payment') }}">
                                <div class="invalid-feedback">
                                    @error('tanggal_payment')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3">
                            <div class="mb-3">
                                <label for="ket_payment" class="form-label">Kategori Payment</label>
                                <select class="form-select @error('ket_payment') is-invalid @enderror" id="ket_payment"
                                    name="ket_payment">
                                    <option value="" disabled selected>- Choose Option -</option>
                                    <option value="Listrik, Air, dan Telepon"
                                        {{ old('ket_payment') == 'Listrik, Air, dan Telepon' ? 'selected' : '' }}>Listrik,
                                        Air, dan Telepon</option>
                                    <option value="Gaji Karyawan"
                                        {{ old('ket_payment') == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                                    <option value="Sewa" {{ old('ket_payment') == 'Sewa' ? 'selected' : '' }}>Sewa
                                    </option>
                                    <option value="Pajak" {{ old('ket_payment') == 'Pajak' ? 'selected' : '' }}>Pajak
                                    </option>
                                    <option value="Asuransi" {{ old('ket_payment') == 'Asuransi' ? 'selected' : '' }}>
                                        Asuransi</option>
                                    <option value="Pemasaran" {{ old('ket_payment') == 'Pemasaran' ? 'selected' : '' }}>
                                        Pemasaran</option>
                                    <option value="Bunga" {{ old('ket_payment') == 'Bunga' ? 'selected' : '' }}>Bunga
                                    </option>
                                    <option value="Depresiasi" {{ old('ket_payment') == 'Depresiasi' ? 'selected' : '' }}>
                                        Depresiasi</option>
                                    {{-- <option value="Pengiriman" {{ old('ket_payment') == 'Pengiriman' ? 'selected' : '' }}>
                                        Pengiriman</option> --}}
                                </select>
                                <div class="invalid-feedback">
                                    @error('ket_payment')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <div class="mb-3">
                                <label for="cost_payment" class="form-label">Cost</label>
                                <input type="number" class="form-control @error('cost_payment') is-invalid @enderror"
                                    id="cost_payment" name="cost_payment" placeholder="Cost payment"
                                    value="{{ old('cost_payment') }}">
                                <div class="invalid-feedback">
                                    @error('cost_payment')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" placeholder="Keterangan pembayaran"
                                value="{{ old('keterangan') }}">
                            <div class="invalid-feedback">
                                @error('keterangan')
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

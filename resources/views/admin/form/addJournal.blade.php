@extends('layouts.app')

@section('title')
    <title>General Ledger - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Add Jurnal Umum</h5>
                    <a href="{{ route('admin.journals.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.journals.store') }}">
                    @csrf
                    <div class="d-flex">
                        <div class="flex-fill me-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="tanggal_jurnal" class="form-label">Tanggal Transaksi</label>
                                <input type="date" class="form-control @error('tanggal_jurnal') is-invalid @enderror"
                                    id="tanggal_jurnal" name="tanggal_jurnal" value="{{ old('tanggal_jurnal') }}">
                                <div class="invalid-feedback">
                                    @error('tanggal_jurnal ')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3 mr-3" style="width: 50%">
                            <div class="mb-3">
                                <label for="ket_jurnal" class="form-label">Keterangan</label>
                                <input type="text" class="form-control @error('ket_jurnal') is-invalid @enderror"
                                    id="ket_jurnal" name="ket_jurnal"
                                    placeholder="Pembelian RillaCake Strawberry Nic 6 60ml" value="{{ old('ket_jurnal') }}">
                                <div class="invalid-feedback">
                                    @error('ket_jurnal')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3 mr-3" style="width: 35%">
                            <div class="mb-3">
                                <label for="debit_acc_id" class="form-label">Akun Debit</label>
                                <select class="form-select @error('debit_acc_id') is-invalid @enderror" id="debit_acc_id"
                                    name="debit_acc_id">
                                    <option value="" disabled selected>- Choose Account -</option>
                                    @foreach ($account as $row)
                                        <option value="{{ $row->id }}"
                                            {{ old('debit_acc_id') == $row->id ? 'selected' : '' }}>
                                            <div class="flex justify-between border border-black">
                                                <div>{{ $row->kode_akun }}</div>
                                                <div>-</div>
                                                <div>{{ $row->nama_akun }}</div>
                                            </div>
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('debit_acc_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3" style="width: 65%">
                            <div class="mb-3">
                                <label for="debit_jurnal" class="form-label">Nominal Debit</label>
                                <input type="number" class="form-control @error('debit_jurnal') is-invalid @enderror"
                                    id="debit_jurnal" name="debit_jurnal" placeholder="Rp"
                                    value="{{ old('debit_jurnal') }}">
                                <div class="invalid-feedback">
                                    @error('debit_jurnal')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-fill me-3 mr-3" style="width: 35%">
                            <div class="mb-3">
                                <label for="kredit_acc_id" class="form-label">Akun Kredit</label>
                                <select class="form-select @error('kredit_acc_id') is-invalid @enderror" id="kredit_acc_id"
                                    name="kredit_acc_id">
                                    <option value="" disabled selected>- Choose Account -</option>
                                    @foreach ($account as $row)
                                        <option value="{{ $row->id }}"
                                            {{ old('kredit_acc_id') == $row->id ? 'selected' : '' }}>
                                            <div class="flex justify-between border border-black">
                                                <div>{{ $row->kode_akun }}</div>
                                                <div>-</div>
                                                <div>{{ $row->nama_akun }}</div>
                                            </div>
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('kredit_acc_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill me-3" style="width: 65%">
                            <div class="mb-3">
                                <label for="kredit_jurnal" class="form-label">Nominal Kredit</label>
                                <input type="number" class="form-control @error('kredit_jurnal') is-invalid @enderror"
                                    id="kredit_jurnal" name="kredit_jurnal" placeholder="Rp"
                                    value="{{ old('kredit_jurnal') }}">
                                <div class="invalid-feedback">
                                    @error('kredit_jurnal')
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
@endsection

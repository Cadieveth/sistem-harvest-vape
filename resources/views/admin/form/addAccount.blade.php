@extends('layouts.app')

@section('title')
    <title>Add Account - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold" style="width: 92%">Form Add Data Akun</h5>
                    <div class="btn btn-circle btn-outline-primary" data-bs-toggle="tooltip" title="Help"><a a
                            href="#" class="flex justify-end items-end" data-bs-toggle="modal"
                            data-bs-target="#detailModal">?</a>
                    </div>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.accounts.store') }}">
                    @csrf
                    <div class="flex justify-between">
                        <div class="mb-3 w-1/2 mr-3">
                            <label for="kode_akun" class="form-label">Kode Akun</label>
                            <input type="text" class="form-control @error('kode_akun') is-invalid @enderror"
                                id="kode_akun" name="kode_akun" placeholder="101..." value="{{ old('kode_akun') }}">
                            <div class="invalid-feedback">
                                @error('kode_akun')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 w-1/2">
                            <label for="nama_akun" class="form-label">Nama Akun</label>
                            <input type="text" class="form-control @error('nama_akun') is-invalid @enderror"
                                id="nama_akun" name="nama_akun" placeholder="Kas" value="{{ old('nama_akun') }}">
                            <div class="invalid-feedback">
                                @error('nama_akun')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Akun</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id">
                            <option value="" disabled selected>- Choose Category -</option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}" {{ old('category_id') == $row->id ? 'selected' : '' }}>
                                    {{ $row->category }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            @error('category_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog" style="width: 50%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-full">
                        <div class="flex justify-between w-full">
                            <div class="w-1/2">
                                <h5 class="modal-title font-bold text-sm" id="detailModalLabel">Informasi Kode dan Kategori
                                </h5>
                            </div>
                            <div class="w-1/2 flex justify-end items-end">
                                <button type="button" data-bs-dismiss="modal">
                                    <i class="material-icons" style="font-size: 16px;">clear</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body -mt-3">
                    <div class="w-full">
                        <div id="table">
                            <div class="flex justify-between mb-1">
                                <div class="flex justify-center font-bold" style="width: 33%">Aktiva
                                </div>
                                <div class="flex justify-center font-bold" style="width: 34%">Liabilitas
                                </div>
                                <div class="flex justify-center font-bold" style="width: 33%">Modal
                                </div>
                                <div class="flex justify-center font-bold" style="width: 34%">Pendapatan
                                </div>
                                <div class="flex justify-center font-bold" style="width: 33%">Beban
                                </div>
                            </div>
                            <div class="flex justify-between mb-1">
                                <div class="flex justify-center" style="width: 33%">100-199
                                </div>
                                <div class="flex justify-center" style="width: 34%">200-299
                                </div>
                                <div class="flex justify-center" style="width: 33%">300-399
                                </div>
                                <div class="flex justify-center" style="width: 34%">400-499
                                </div>
                                <div class="flex justify-center" style="width: 33%">500-599
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

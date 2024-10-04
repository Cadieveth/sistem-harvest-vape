@extends('layouts.app')

@section('title')
    <title>Edit Account - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Form Edit Data Akun</h5>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.accounts.update', ['id' => $account->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-between">
                        <div class="mb-3 mr-3 w-1/2">
                            <label for="kode_akun" class="form-label">Kode Akun</label>
                            <input type="text" class="form-control @error('kode_akun') is-invalid @enderror"
                                id="kode_akun" name="kode_akun" placeholder="101..."
                                value="{{ old('kode_akun', $account->kode_akun) }}">
                            <div class="invalid-feedback">
                                @error('kode_akun')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 w-1/2">
                            <label for="nama_akun" class="form-label">Nama Akun</label>
                            <input type="text" class="form-control @error('nama_akun') is-invalid @enderror"
                                id="nama_akun" name="nama_akun" placeholder="Kas"
                                value="{{ old('nama_akun', $account->nama_akun) }}">
                            <div class="invalid-feedback">
                                @error('nama_akun')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Ketegori Akun</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id">
                            <option value="" disabled>- Choose Category -</option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}"
                                    {{ old('category_id', $account->category_id) == $row->id ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

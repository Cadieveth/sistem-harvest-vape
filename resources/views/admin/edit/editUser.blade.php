@extends('layouts.app')

@section('title')
    <title>Edit User - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Edit User</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.users.update', ['id' => $user->id]) }}">
                    @csrf
                    @method('PUT')



                    <div id="container" class="mb-3">
                        <div id="first-row">
                            <div id="column-1">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama User</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="(Contoh: Christian)"
                                        value="{{ old('name', $user->name) }}" disabled>
                                    <div class="invalid-feedback">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="second-row" class="flex justify-between">
                            <div id="column-1" class="w-1/2 mr-3">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" placeholder="(Contoh: Christian2)"
                                        value="{{ old('username', $user->username) }}">
                                    <div class="invalid-feedback">
                                        @error('username')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="column-2" class="w-1/2 ml-3">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Status</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role">
                                        <option value="" disabled>- Choose Role -</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->role }}"
                                                {{ old('role', $user->role) == $role->role ? 'selected' : '' }}>
                                                {{ $role->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('role')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="role" class="form-label">Status</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" id="role"
                            name="role" placeholder="role" value="{{ old('role', $user->role) }}">
                        <div class="invalid-feedback">
                            @error('role')
                                {{ $message }}
                            @enderror
                        </div>
                    </div> --}}
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

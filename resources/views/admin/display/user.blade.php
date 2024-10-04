@extends('layouts.app')

@section('title')
    <title>User - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Data User</h5>
                {{-- button add --}}
                {{-- <a href="{{ route('admin.addVendor') }}" type="button" class="btn btn-outline-primary m-1">Add Vendor</a> --}}

                <form method="GET" action="{{ route('admin.users.index') }}">
                    {{-- filter collumn --}}
                    <div class="col-md-10 mt-5">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="nama_user" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ request()->get('name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- button filter --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Apply Filter">
                            <i class="ti ti-adjustments"></i>
                        </button>
                        <a href="{{ route('admin.users.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                            data-bs-toggle="tooltip" title="Reset">
                            <i class="ti ti-adjustments-off"></i>
                        </a>
                    </div>
                </form>
                <hr class="mt-4">
                <div class="overflow-auto table-responsive">
                    <table class="table text-sm" id="table1">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Created Date <i class="bi bi-plus-circle ml-1"></i></th>
                                <th>Verified Date <i class="bi bi-check2-circle ml-1"></i></th>
                                <th>Updated Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td style="width: 8%">{{ $user->name }}</td>
                                    <td style="width: 15%">{{ $user->username }}</td>
                                    <td style="width: 12%">
                                        <strong>{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</strong><br>
                                        <span
                                            style="font-size: 11px">{{ \Carbon\Carbon::parse($user->created_at)->format('h.i A') }}</span>
                                    </td>
                                    <td style="width: 12%">
                                        <strong>{{ \Carbon\Carbon::parse($user->email_verified_at)->format('d F Y') }}</strong><br>
                                        <span
                                            style="font-size: 11px">{{ \Carbon\Carbon::parse($user->email_verified_at)->format('h.i A') }}</span>
                                    </td>
                                    <td style="width: 12%">
                                        <strong>{{ \Carbon\Carbon::parse($user->updated_at)->format('d F Y') }}</strong><br>
                                        <span
                                            style="font-size: 11px">{{ \Carbon\Carbon::parse($user->updated_at)->format('h.i A') }}</span>
                                    </td>
                                    <td style="width: 8%">{{ $user->role }}</td>
                                    <td style="width: 7%">
                                        <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="action-icons">
                                                <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                                                    class="edit-icon" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                    title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $users->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $users->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
                                $start = max(1, $currentPage - 1);
                                $end = min($lastPage, $currentPage + 1);

                                if ($currentPage <= 3) {
                                    $start = 1;
                                    $end = min(4, $lastPage);
                                } elseif ($currentPage >= $lastPage - 2) {
                                    $start = max(1, $lastPage - 3);
                                    $end = $lastPage;
                                }

                                if ($start > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $users->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $users->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $users->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li class="page-item {{ $users->currentPage() == $users->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $users->appends(request()->except('page'))->nextPageUrl() }}"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin akan delete data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table1').on('click', '.delete-icon', function(e) {
                var form = $(this).closest("form");
                e.preventDefault();
                $('#confirmDeleteModal').modal('show');

                $('#confirm-delete').click(function() {
                    form.submit();
                });
            });

            @if (session()->has('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

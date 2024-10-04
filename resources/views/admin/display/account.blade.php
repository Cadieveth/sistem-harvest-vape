@extends('layouts.app')

@section('title')
    <title>Account - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Data Akun</h5>

                <form method="GET" action="{{ route('admin.accounts.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="flex">
                            <div class="col-md-4 mr-3">
                                <label for="nama_akun" class="form-label">Filter by Account:</label>
                                <input type="text" class="form-control" id="nama_akun" name="nama_akun"
                                    value="{{ request()->get('nama_akun') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="category_id" class="form-label">Filter by Category:</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id">
                                    <option value="" disabled selected>- Choose Category -</option>
                                    @foreach ($category as $row)
                                        <option value="{{ $row->id }}"
                                            {{ request()->get('category_id') == $row->id ? 'selected' : '' }}>
                                            {{ $row->category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" mt-4 ml-3">
                                <a href="{{ route('admin.categories.index') }}" type="button"
                                    class="btn btn-outline-primary m-1"><i class="bi bi-arrow-up-right-circle mr-1"></i> Add
                                    Category</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Apply Filter">
                            <i class="ti ti-adjustments"></i>
                        </button>
                        <a href="{{ route('admin.accounts.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                            data-bs-toggle="tooltip" title="Reset">
                            <i class="ti ti-adjustments-off"></i>
                        </a>
                    </div>
                    <div class="flex justify-end -mt-3">
                        <a href="{{ route('admin.addAccount') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Account</a>
                    </div>
                </form>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($accounts->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Akun</th>
                                    <th>Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>

                                        <td style="width: 15%">{{ $account->kode_akun }}</td>
                                        <td style="width: 40%">{{ $account->nama_akun }}</td>
                                        <td style="width: 40%">{{ $account->category->category }}</td>
                                        <td style="width: 5%">
                                            <form action="{{ route('admin.accounts.destroy', ['id' => $account->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="{{ route('admin.accounts.edit', ['id' => $account->id]) }}"
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
                    @endif
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $accounts->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $accounts->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $accounts->currentPage();
                                $lastPage = $accounts->lastPage();
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
                                        $accounts->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $accounts->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $accounts->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li
                                class="page-item {{ $accounts->currentPage() == $accounts->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $accounts->appends(request()->except('page'))->nextPageUrl() }}"
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

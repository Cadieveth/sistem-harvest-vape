@extends('layouts.app')

@section('title')
    <title>Account - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold">Data Kategori Akun</h5>
                    <div class="flex">
                        <div class="btn btn-circle btn-outline-primary" data-bs-toggle="tooltip" title="Help"><a a
                                href="#" class="flex justify-end items-end" data-bs-toggle="modal"
                                data-bs-target="#detailModal">?</a>
                        </div>
                        <div class="ml-3"><a href="{{ route('admin.accounts.index') }}"
                                class="btn btn-circle btn-outline-primary" data-bs-toggle="tooltip" title="back">
                                <i class="ti ti-chevron-left"></i>
                            </a></div>
                    </div>
                </div>

                <div class="col-md-10 mt-5">
                    <div class="flex justify-between">
                        <form id="category-form" method="POST" class="w-full"
                            action="{{ route('admin.categories.store') }}">
                            @csrf
                            <input type="hidden" id="category-id" name="category_id" value="">
                            <div class="flex justify-between">
                                <div class="col-md-4 mr-3" style="width: 35%">
                                    <label for="category" class="form-label">Kategori:</label>
                                    <input type="text" class="form-control" id="category" name="category"
                                        placeholder="Nama Kategori" value="">
                                    <div class="invalid-feedback">
                                        @error('category')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4" style="width: 13%">
                                    <button type="submit" class="btn btn-outline-primary m-1" id="category-button">
                                        Add Category</button>
                                </div>
                                <div class="mt-4 -ml-1" style="width: 54%">
                                    <button type="button" class="btn btn-outline-danger m-1" id="cancel-button"
                                        style="display: none;">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($categories->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td style="width: 15%">
                                            <form action="{{ route('admin.categories.destroy', ['id' => $category->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="#" class="edit-icon" data-id="{{ $category->id }}"
                                                        data-category="{{ $category->category }}" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                        title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                        <td style="width: 85%">{{ $category->category }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $categories->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $categories->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $categories->currentPage();
                                $lastPage = $categories->lastPage();
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
                                        $categories->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $categories->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $categories->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li
                                class="page-item {{ $categories->currentPage() == $categories->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $categories->appends(request()->except('page'))->nextPageUrl() }}"
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
                    Are you sure you want to delete this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal  --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog" style="width: 50%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-full">
                        <div class="flex justify-between w-full">
                            <div class="w-1/2">
                                <h5 class="modal-title font-bold text-sm" id="detailModalLabel">
                                    Informasi Menu Kategori Akun
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
                        <table>
                            <tr>
                                <td class="align-text-top text-start">1.</td>
                                <td>
                                    <P>Input nilai Kategori untuk mengisi data, kemudian klik Add Category menambahkan data.
                                    </P>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-text-top text-start">2.</td>
                                <td>
                                    <P>untuk mengubah data yang telah di input, klik icon edit -> <i
                                            class="bi bi-pencil-square"></i>, kemudian ubah data pada field Kategori.
                                        Setelah
                                        itu klik Edit Data
                                    </P>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-text-top text-start">3.</td>
                                <td>
                                    <P>Klik Cancel, untuk keluar dari mode edit data.
                                    </P>
                                </td>
                            </tr>
                        </table>
                    </div>
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
            // Handle delete icon click
            $('#table1').on('click', '.delete-icon', function(e) {
                var form = $(this).closest("form");
                e.preventDefault();
                $('#confirmDeleteModal').modal('show');

                $('#confirm-delete').click(function() {
                    form.submit();
                });
            });

            // Handle edit icon click
            $('#table1').on('click', '.edit-icon', function(e) {
                e.preventDefault();
                var categoryId = $(this).data('id');
                var category = $(this).data('category');

                $('#category-id').val(categoryId);
                $('#category').val(category);
                $('#category-form').attr('action', '/admin/category/' + categoryId);
                $('#category-form').append('<input type="hidden" name="_method" value="PUT">');
                $('#category-button').text('Edit Category');
                $('#cancel-button').show();
            });

            // Handle cancel button click
            $('#cancel-button').click(function() {
                $('#category-id').val('');
                $('#category').val('');
                $('#category-form').attr('action', '{{ route('admin.categories.store') }}');
                $('#category-form').find('input[name="_method"]').remove();
                $('#category-button').text('Add Category');
                $('#cancel-button').hide();
            });

            // Show success or error messages
            @if (session()->has('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection

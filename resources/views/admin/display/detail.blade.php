@extends('layouts.app')

@section('title')
    <title>Detail - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-semibold mb-4">Rincian Data Neraca Saldo Awal</h5>
                    <a href="{{ route('admin.balances.index') }}" class="btn btn-circle btn-outline-primary"
                        data-bs-toggle="tooltip" title="back">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </div>

                <div id="card-container" class="flex justify-between mb-2 mt-3">
                    <div id="card-1" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Awal Persediaan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPersediaan, 2, ',', '.') }}</div>
                                <div class="text-xs">tersedia untuk dijual dari neraca awal</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-box-seam"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-2" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Awal Peralatan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPeralatan, 2, ',', '.') }}</div>
                                <div class="text-xs">tersedia dari neraca awal</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-tools"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-3" class="border-2 border-indigo-500 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Awal Perlengkapan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPerlengkapan, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">tersedia dari neraca awal</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-tablet-landscape" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.details.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="flex">
                            <div class="col-md-4 mr-3">
                                <label for="kode" class="form-label">Filter by Code:</label>
                                <input type="text" class="form-control" id="kode" name="kode"
                                    value="{{ request()->get('kode') }}">
                            </div>
                            <div class="col-md-4 mr-3">
                                <label for="barang" class="form-label">Filter by Name:</label>
                                <input type="text" class="form-control" id="barang" name="barang"
                                    value="{{ request()->get('barang') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Apply Filter">
                            <i class="ti ti-adjustments"></i>
                        </button>
                        <a href="{{ route('admin.details.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                            data-bs-toggle="tooltip" title="Reset">
                            <i class="ti ti-adjustments-off"></i>
                        </a>
                    </div>
                    <div class="flex justify-end -mt-3">
                        <a href="{{ route('admin.addDetail') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Data</a>
                    </div>
                </form>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($details->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Kode Data</th>
                                    <th style="width: 15%">Kode Barang</th>
                                    <th style="width: 35%">Nama Barang</th>
                                    <th style="width: 10%">Jumlah</th>
                                    <th style="width: 15%">Harga</th>
                                    <th style="width: 10%">Kategori</th>
                                    <th style="width: 5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $data)
                                    <tr>
                                        <td>{{ $data->kode_data }}</td>
                                        <td>{{ $data->kode_barang }}</td>
                                        <td>{{ $data->nama_barang }}</td>
                                        <td>{{ $data->jumlah_barang }}</td>
                                        <td>Rp{{ number_format($data->harga_barang, 2, ',', '.') }}</td>
                                        <td>{{ $data->ket_barang }}</td>
                                        <td>
                                            <form action="{{ route('admin.details.destroy', ['id' => $data->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="{{ route('admin.details.edit', ['id' => $data->id]) }}"
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
                            <li class="page-item {{ $details->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $details->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $details->currentPage();
                                $lastPage = $details->lastPage();
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
                                        $details->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $details->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $details->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li class="page-item {{ $details->currentPage() == $details->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $details->appends(request()->except('page'))->nextPageUrl() }}"
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
    {{-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
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
    </div> --}}
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

{{-- @section('js')
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
@endsection --}}

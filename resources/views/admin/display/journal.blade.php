@extends('layouts.app')

@section('title')
    <title>General Ledger - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @php
        use Carbon\Carbon;
    @endphp

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold">Jurnal Umum</h5>

                <div id="card-container" class="flex justify-start mb-2">
                    <div id="card-3" class="border-2 border-indigo-500 mr-3 w-1/4 rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div>
                                <div class="font-bold text-base">Total Debit</div>
                                <div class="font-bold text-2xl">{{ number_format($totalDebit, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">...</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-arrow-down-left-square" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-4" class="border-2 border-indigo-500 w-1/4 rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div>
                                <div class="font-bold text-base">Total Kredit</div>
                                <div class="font-bold text-2xl">{{ number_format($totalKredit, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">...</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-arrow-up-right-square" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.journals.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="keterangan" class="form-label">Filter by Ket:</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ !empty($_GET['keterangan']) ? $_GET['keterangan'] : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Filter by Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ !empty($_GET['start_date']) ? $_GET['start_date'] : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">Filter by End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ !empty($_GET['end_date']) ? $_GET['end_date'] : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip"
                                title="Apply Filter"><i class="ti ti-adjustments"></i></button>
                            <a href="{{ route('admin.journals.index', ['reset_filter' => 1]) }}"
                                class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Reset"><i
                                    class="ti ti-adjustments-off"></i></a>
                        </div>
                    </div>
                    <div class="flex justify-end -mt-3">
                        <a href="{{ route('admin.addJournal') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Jurnal</a>
                    </div>
                </form>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($journals->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Akun</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($journals as $data)
                                    @php
                                        $disabled = in_array(
                                            [$data->debit_acc_id, $data->kredit_acc_id],
                                            [
                                                [3, 10],
                                                [15, 3],
                                                [24, 7],
                                                [7, 3],
                                                [13, 3],
                                                [14, 3],
                                                [16, 3],
                                                [18, 3],
                                                [22, 3],
                                                [20, 3],
                                                [21, 3],
                                                [17, 3],
                                                [23, 3],
                                            ],
                                        );
                                    @endphp
                                    <tr>
                                        <td style="width: 13%">{{ Carbon::parse($data->tanggal_jurnal)->format('d M Y') }}
                                        </td>
                                        <td style="width: 30%">{{ $data->ket_jurnal }}</td>
                                        <td style="width: 22%">
                                            <div>
                                                <div>{{ $data->debitAccount->nama_akun }}</div>
                                                <div>{{ $data->kreditAccount->nama_akun }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 15%">Rp{{ number_format($data->debit_jurnal, 2, ',', '.') }}</td>
                                        <td style="width: 15%">
                                            <div>
                                                <div class="invisible">Rp</div>
                                                <div>Rp{{ number_format($data->kredit_jurnal, 2, ',', '.') }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 5%">
                                            <form action="{{ route('admin.journals.destroy', ['id' => $data->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="{{ route('admin.journals.edit', ['id' => $data->id]) }}"
                                                        class="edit-icon" data-bs-toggle="tooltip" title="Edit"
                                                        @if ($disabled) style="pointer-events: none; opacity: 0.5;" @endif>
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                        title="Delete"
                                                        @if ($disabled) style="pointer-events: none; opacity: 0.5;" @endif>
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
                {{-- <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div> --}}
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $journals->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $journals->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $journals->currentPage();
                                $lastPage = $journals->lastPage();
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
                                        $journals->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $journals->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $journals->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li
                                class="page-item {{ $journals->currentPage() == $journals->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $journals->appends(request()->except('page'))->nextPageUrl() }}"
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
        });
    </script>
@endsection

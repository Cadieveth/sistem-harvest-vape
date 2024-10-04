@extends('layouts.app')

@section('title')
    <title>Payment - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @php
        use Carbon\Carbon;
    @endphp

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Data Pembayaran</h4>

                <div id="card-container" class="flex justify-between mb-2">
                    <div id="card-1" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Biaya Pengiriman</div>
                                <div class="font-bold text-2xl">{{ number_format($totalShippingCost, 2, ',', '.') }}</div>
                                <div class="text-xs">Beban Angkut Pembelian</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-truck"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-2" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Biaya Operasional</div>
                                <div class="font-bold text-2xl">{{ number_format($totalOperationCost, 2, ',', '.') }}</div>
                                <div class="text-xs">Beban Operasi</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-cash-coin"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-3" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Biaya Non-Operasional</div>
                                <div class="font-bold text-2xl">{{ number_format($totalTaxNInterestCost, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">Beban Pajak dan Bunga</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-bank"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-4" class="border-2 border-indigo-500 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Biaya</div>
                                <div class="font-bold text-2xl">{{ number_format($totalCost, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">dari Transaksi Pembayaran</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-currency-dollar" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.payment.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="ket" class="form-label">Kategori Payment:</label>
                                <input type="text" class="form-control" id="ket" name="ket"
                                    value="{{ !empty($_GET['ket']) ? $_GET['ket'] : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ !empty($_GET['start_date']) ? $_GET['start_date'] : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ !empty($_GET['end_date']) ? $_GET['end_date'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Apply Filter"><i
                                class="ti ti-adjustments"></i></button>
                        <a href="{{ route('admin.payment.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                            data-bs-toggle="tooltip" title="Reset"><i class="ti ti-adjustments-off"></i></a>
                    </div>
                    <div class="flex justify-end -mt-3">
                        <a href="{{ route('admin.addPayment') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Pembayaran</a>
                    </div>
                </form>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($payment->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Cost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment as $row)
                                    <tr>
                                        <td style="width: 10%">{{ $row->kode_payment }}</td>
                                        {{-- <td class="text-nowrap">{{ $row->tanggal_payment }}</td> --}}
                                        <td class="text-nowrap" style="width: 10%">
                                            {{ Carbon::parse($row->tanggal_payment)->format('d M Y') }}</td>
                                        <td style="width: 22%">{{ $row->ket_payment }}</td>
                                        <td style="width: 35%">{{ $row->keterangan }}</td>
                                        <td class="text-nowrap" style="width: 18%">Rp
                                            {{ number_format($row->cost_payment, 2, ',', '.') }}
                                        </td>
                                        <td style="width: 5%">
                                            <form action="{{ route('admin.payment.destroy', ['id' => $row->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="{{ route('admin.payment.edit', ['id' => $row->id]) }}"
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
                            <li class="page-item {{ $payment->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $payment->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $payment->currentPage();
                                $lastPage = $payment->lastPage();
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
                                        $payment->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $payment->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $payment->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li class="page-item {{ $payment->currentPage() == $payment->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $payment->appends(request()->except('page'))->nextPageUrl() }}"
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

            var start_date = $('#start_date'); // Mendapatkan elemen dengan ID 'start_date'
            var end_date = $('#end_date'); // Mendapatkan elemen dengan ID 'end_date'

            function validateFilterDate(start, end) {
                var start = new Date(start); // Mengonversi tanggal awal ke objek Date
                var end = new Date(end); // Mengonversi tanggal akhir ke objek Date

                if (start <= end) { // Memeriksa apakah tanggal awal kurang dari atau sama dengan tanggal akhir
                    return true; // Jika valid, kembalikan true
                } else {
                    return false; // Jika tidak valid, kembalikan false
                }
            }

            start_date.on('change', () => {
                end_date.attr('min', start_date.val()); // Menetapkan tanggal minimum pada 'end_date'
                if (!validateFilterDate(start_date.val(), end_date.val())) {
                    end_date.val(''); // Menghapus nilai 'end_date' jika rentang tanggal tidak valid
                }
            });
        });
    </script>
@endsection

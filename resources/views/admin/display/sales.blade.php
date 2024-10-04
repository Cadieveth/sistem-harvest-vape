@extends('layouts.app')

@section('title')
    <title>Sales - Harvest Vape</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    @php
        use Carbon\Carbon;
    @endphp

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Data Penjualan</h5>

                <div id="card-container" class="flex justify-between mb-2">
                    <div id="card-1" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Persediaan Keluar</div>
                                <div class="font-bold text-2xl">{{ number_format($barangKeluar, 2, ',', '.') }}</div>
                                <div class="text-xs"><b>{{ $totalSalesQuantity }}</b> pcs Penjualan Persediaan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-box-seam"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-2" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Penjualan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalSales, 2, ',', '.') }}</div>
                                <div class="text-xs">dari Transaksi Penjualan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-coin"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-3" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Discount</div>
                                <div class="font-bold text-2xl">{{ number_format($totalSalesDiscount, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">tercatat pada Penjualan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-scissors"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-4" class="border-2 border-indigo-500 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Penjualan Bersih</div>
                                <div class="font-bold text-2xl">{{ number_format($totalSalesNet, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">dari Transaksi Penjualan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-currency-dollar" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.sales.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="barang" class="form-label">Filter by Name:</label>
                                <input type="text" class="form-control" id="barang" name="barang"
                                    value="{{ !empty($_GET['barang']) ? $_GET['barang'] : '' }}">
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
                            <a href="{{ route('admin.sales.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                                data-bs-toggle="tooltip" title="Reset"><i class="ti ti-adjustments-off"></i></a>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        @if (Auth::user()->role !== 'Staff')
                            <a href="#" type="button" class="btn btn-outline-danger m-1" id="delete-selected"
                                title="Delete Sales">
                                <i class="ti ti-trash" style="font-size: 20px;"></i>
                            </a>
                        @endif
                        <a href="#" type="button" class="btn btn-outline-primary m-1 create-invoice-button"
                            data-bs-toggle="modal" data-bs-target="#invoiceModal" title="Create Invoice">
                            <i class="ti ti-file-invoice" style="font-size: 20px;"></i>
                        </a>
                        <a href="{{ route('admin.addSales') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Penjualan</a>
                    </div>
                </form>

                <hr class="mt-4">
                <div class="overflow-auto table-responsive">
                    @if ($sales->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Penjualan</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <!-- Start checkBox -->
                                        <td style="width: 3%">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="checkbox{{ $sale->id }}" data-id="{{ $sale->id }}"
                                                data-kode="{{ $sale->kode_sales }}"
                                                data-tanggal="{{ $sale->date_sales }}"
                                                data-kode-barang="{{ $sale->kode_barang }}"
                                                data-nama-barang="{{ $sale->nama_barang }}"
                                                data-harga-barang="{{ $sale->harga_barang }}"
                                                data-jumlah-barang="{{ $sale->jumlah_sales }}"
                                                data-penjualan-bersih="{{ $sale->penjualan_bersih }}"
                                                data-harga-potongan="{{ $sale->harga_potongan }}"
                                                data-total-sales="{{ $sale->total_sales }}">
                                        </td>
                                        <!-- End checkBox -->
                                        {{-- <td class="text-nowrap">{{ $sale->date_sales }}</td> --}}
                                        <td class="text-nowrap">
                                            {{ Carbon::parse($sale->date_sales)->format('d M Y') }}</td>
                                        <td style="width: 10%">{{ $sale->kode_sales }}</td>
                                        <td style="width: 13%">{{ $sale->kode_barang }}</td>
                                        <td style="width: 26%">{{ $sale->nama_barang }}</td>
                                        <td style="width: 5%">{{ $sale->jumlah_sales }}</td>
                                        <td class="text-nowrap">Rp
                                            {{ number_format($sale->harga_potongan, 2, ',', '.') }}</td>
                                        <td class="text-nowrap">Rp
                                            {{ number_format($sale->penjualan_bersih, 2, ',', '.') }}</td>
                                        <td style="width: 10%">
                                            <form action="{{ route('admin.sales.destroy', ['id' => $sale->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a a href="#" class="edit-icon" data-bs-toggle="modal"
                                                        data-bs-target="#detailModal"
                                                        data-tanggal="{{ $sale->date_sales }}"
                                                        data-kode-barang="{{ $sale->kode_barang }}"
                                                        data-id-sales="{{ $sale->id }}"
                                                        data-kode-sales="{{ $sale->kode_sales }}"
                                                        data-nama-barang="{{ $sale->nama_barang }}"
                                                        data-harga-barang="{{ $sale->harga_barang }}"
                                                        data-jumlah-barang="{{ $sale->jumlah_sales }}"
                                                        data-penjualan-bersih="{{ $sale->penjualan_bersih }}"
                                                        data-harga-potongan="{{ $sale->harga_potongan }}"
                                                        data-total-sales="{{ $sale->total_sales }}" title="Detail">
                                                        <i class="ti ti-zoom-in"></i>
                                                    </a>
                                                    <a href="{{ route('admin.sales.edit', ['id' => $sale->id]) }}"
                                                        class="edit-icon" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    @if (Auth::user()->role !== 'Staff')
                                                        <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                            title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                    @endif
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
                            <li class="page-item {{ $sales->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $sales->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $sales->currentPage();
                                $lastPage = $sales->lastPage();
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
                                        $sales->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $sales->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $sales->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li class="page-item {{ $sales->currentPage() == $sales->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $sales->appends(request()->except('page'))->nextPageUrl() }}"
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
    <div class="modal fade" id="confirmDeleteModal2" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
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
                    <button type="button" class="btn btn-danger" id="confirm-delete2">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Dialog for Invoice -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="container-fluid" style="padding-top: 10px">
                    <a href="#" id="downloadButton" type="button" class="btn btn-outline-primary m-1"
                        data-bs-toggle="tooltip" title="Download">
                        <i class="ti ti-file-invoice"></i>
                    </a>
                    <div class="card">
                        <div class="card-body p-4">
                            <table class="table">
                                <div class="invoice-header">
                                    <div class="invoice-logo">
                                        <img src="{{ asset('backend/asset/img/logos/Logo HV.png') }}" alt="Logo"
                                            width="120px" style="margin-bottom: 10px">
                                    </div>
                                    <div class="invoice-address" style="margin-left: 40px">
                                        <p><strong>HARVEST VAPE</strong></p>
                                        <p>Jl. Brigjen. Slamet Riyadi No. 36 Kepatihan</p>
                                        <p>Kranggan, Kec. Ambarawa, Kab. Semarang</p>
                                        <p>Jawa Tengah 50613</p>
                                    </div>
                                </div>

                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <table style="width: 100%">
                                                <tr>
                                                    <td colspan="4">
                                                        <h2 class="invoice-title">INVOICE</h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 15%">Tanggal</td>
                                                    <td>: {{ date('Y-m-d') }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 15%">Telepon</td>
                                                    <td>: +62 821-2455-2000</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <table class="invoice-table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 35%">Nama Produk</th>
                                                                    <th style="width: 5%">Jumlah</th>
                                                                    <th style="width: 15%">Harga Satuan</th>
                                                                    <th style="width: 15%">Total</th>
                                                                    <th style="width: 15%">Discount</th>
                                                                    <th style="width: 15%">Total Bersih</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="invoiceItems">
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="invoice-subtotal">
                                                            <span>Sub Total</span>
                                                            <span style="margin-right: 0px"><span id="totalHarga"></span>
                                                            </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="invoice-status">
                                                            <p>{{ date('d-M-Y') }}</p>
                                                            <p>Tanda terima,</p>
                                                            <h3 style="margin-top: 50px">[LUNAS]</h3>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="invoice-footer">
                                            INVOICE: Harvest Vape
                                        </div>
                                    </div>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Dialog for Invoice -->

    <!-- Start Dialog -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr style="border-bottom: none; border-top: none;">
                            <td>Tanggal</td>
                            <td>: <span id="detailTanggal"></span></td>
                        </tr>
                        <tr style="border-bottom: none; border-top: none;">
                            <td width="30%">Kode Penjualan</td>
                            <td>: <span id="detailIdSales"></span></td>
                        </tr>
                    </table>
                    {{-- <hr> --}}
                    <table class="table">
                        <tr style="border-bottom: none; border-top: none;">
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                        <tr style="border-bottom: none; border-top: none;">
                            <td><span id="detailKodeBarang"></span></td>
                            <td><span id="detailNamaBarang"></span></td>
                            <td><span id="detailJumlahBarang"></span></td>
                            <td><span id="detailHargaBarang"></span></td>
                        </tr>
                        <tr style="border-bottom: none; border-top: none;">
                            <td>Potongan Harga
                            </td>
                            <td colspan="2">
                            </td>
                            <td><span id="detailHargaPotongan"></span>
                            </td>
                        </tr>
                        <tr style="border-bottom: none; border-top: none;">
                            <td style="width: 25%">Total</td>
                            <td colspan="2"></td>
                            <td style="width: 25%"><span id="detailPenjualanBersih"></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    Anda yakin akan delete data penjualan terpilih?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertModalBody">
                    <!-- Alert message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .invoice-header {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #557ada;
        }


        .invoice-logo {
            margin-right: 20px;
            max-width: 100px;
        }

        .invoice-title {
            text-align: center;
            font-size: 24px;
            margin-top: 30px;
        }

        .invoice-info {
            margin-top: 30px;
        }

        .invoice-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .invoice-table th {
            border: 1px solid #557ada;
            padding: 10px;
            text-align: left;
        }


        .invoice-table td {
            border: 0px;
            padding: 10px;
            text-align: left;
        }

        .invoice-subtotal {
            border-top: 1px solid #557ada;
            border-bottom: 1px solid #557ada;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-status {
            padding: 10px;
            text-align: right;
            margin-top: 50px;

        }

        .invoice-footer {
            background-color: #557ada;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        .btn-primary {
            background-color: #557ada;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #557ada;
            opacity: 0.8;
        }
    </style>
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table1').on('click', '.delete-icon', function(e) {
                var form = $(this).closest("form");
                e.preventDefault();
                $('#confirmDeleteModal2').modal('show');

                $('#confirm-delete2').click(function() {
                    form.submit();
                });
            });

            @if (session()->has('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif

            // Set up AJAX to include the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Store selected IDs globally
            var selectedIds = [];

            $('#delete-selected').on('click', function() {
                selectedIds = [];

                // Get all checked checkboxes
                $('input.form-check-input:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });

                if (selectedIds.length === 0) {
                    showAlert('Pilih minimal satu data untuk delete');
                    return;
                }

                // Show the confirm delete modal
                $('#confirmDeleteModal').modal('show');
            });

            // Handle the confirmation of the delete action
            $('#confirm-delete').on('click', function() {
                $('#confirmDeleteModal').modal('hide');

                // Send AJAX request to delete selected items
                $.ajax({
                    url: '{{ route('admin.sales.bulk-delete') }}',
                    type: 'POST',
                    data: {
                        ids: selectedIds
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the deleted rows from the table
                            $('input.form-check-input:checked').closest('tr').remove();
                            showAlert('Selected item deleted succesfully');
                        } else {
                            showAlert('An error occurred while deleting the items.');
                        }
                    },
                    error: function(xhr) {
                        showAlert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            function showAlert(message) {
                $('#alertModalBody').text(message);
                $('#alertModal').modal('show');
            }
        });

        function formatCurrency(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const downloadButton = document.getElementById("downloadButton");
            const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            const detailTanggal = document.getElementById('detailTanggal');
            const detailKodeBarang = document.getElementById('detailKodeBarang');
            const detailIdSales = document.getElementById('detailIdSales');
            const detailNamaBarang = document.getElementById('detailNamaBarang');
            const detailHargaBarang = document.getElementById('detailHargaBarang');
            const detailJumlahBarang = document.getElementById('detailJumlahBarang');
            const detailPenjualanBersih = document.getElementById('detailPenjualanBersih');
            const detailHargaPotongan = document.getElementById('detailHargaPotongan');
            const detailTotalSales = document.getElementById('detailTotalSales');
            // ... Tambahan variabel lain sesuai kebutuhan ...

            const detailButtons = document.querySelectorAll('.edit-icon[data-bs-toggle="modal"]');
            detailButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const tanggal = this.getAttribute('data-tanggal');
                    const kodeBarang = this.getAttribute('data-kode-barang');
                    const idSales = this.getAttribute('data-kode-sales');
                    const namaBarang = this.getAttribute('data-nama-barang');
                    const hargaBarang = this.getAttribute('data-harga-barang');
                    const jumlahBarang = this.getAttribute('data-jumlah-barang');
                    const penjualanBersih = this.getAttribute('data-penjualan-bersih');
                    const hargaPotongan = this.getAttribute('data-harga-potongan');
                    const totalSales = this.getAttribute('data-total-sales');
                    // ... Ambil data lain sesuai kebutuhan ...

                    detailTanggal.textContent = tanggal;
                    detailKodeBarang.textContent = kodeBarang;
                    detailIdSales.textContent = idSales;
                    detailNamaBarang.textContent = namaBarang;
                    detailHargaBarang.textContent = formatCurrency(hargaBarang);
                    detailJumlahBarang.textContent = jumlahBarang;
                    detailPenjualanBersih.textContent = formatCurrency(penjualanBersih);
                    detailHargaPotongan.textContent = formatCurrency(hargaPotongan);
                    detailTotalSales.textContent = formatCurrency(totalSales);
                    // ... Isi dengan data lain sesuai kebutuhan ...

                    detailModal.show();
                });
            });

            const createInvoiceButton = document.querySelector('.create-invoice-button');
            createInvoiceButton.addEventListener('click', function(event) {
                // Temukan semua checkbox yang terpilih
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');

                // Temukan tbody di modal invoice untuk menampilkan data dari checkbox
                const invoiceItems = document.getElementById('invoiceItems');

                // Kosongkan isi tbody invoiceItems
                invoiceItems.innerHTML = '';

                // Hitung total harga
                let totalHarga = 0;

                // Iterasi melalui checkbox yang terpilih
                checkboxes.forEach(checkbox => {
                    // Ambil data dari atribut data pada checkbox
                    const namaBarang = checkbox.getAttribute('data-nama-barang');
                    const hargaBarang = checkbox.getAttribute('data-harga-barang');
                    const jumlahBarang = checkbox.getAttribute('data-jumlah-barang');
                    const penjualanBersih = checkbox.getAttribute('data-penjualan-bersih');
                    const hargaPotongan = checkbox.getAttribute('data-harga-potongan');
                    const totalSales = checkbox.getAttribute('data-total-sales');

                    // Tambahkan data dari checkbox ke dalam tbody invoiceItems
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                <td>${namaBarang}</td>
                <td class="text-center">${jumlahBarang}</td>
                <td>${formatCurrency(hargaBarang)}</td>
                <td>${formatCurrency(totalSales)}</td>
                <td>${formatCurrency(hargaPotongan)}</td>
                <td>${formatCurrency(penjualanBersih)}</td>
                `;

                    invoiceItems.appendChild(newRow);

                    // Hitung total harga
                    const hargaBarangFloat = parseFloat(penjualanBersih);
                    const jumlahBarangInt = parseInt(jumlahBarang);
                    totalHarga += hargaBarangFloat
                });

                // Temukan elemen span dengan id "totalHarga" dan perbarui teksnya
                const totalHargaElem = document.getElementById('totalHarga');
                totalHargaElem.textContent = formatCurrency(totalHarga);

                // Tampilkan modal ketika tombol diklik
                invoiceModal.show();
            });

            downloadButton.addEventListener("click", function(event) {
                event.preventDefault();

                var element = document.querySelector(
                    '.modal .card'); // Seleksi elemen yang ingin dikonversi ke PDF
                var opt = {
                    margin: 0.5,
                    filename: 'invoice.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'portrait'
                    }
                };

                // Menggunakan html2pdf untuk mengonversi elemen ke PDF
                html2pdf().set(opt).from(element).save();
            });
        });

        const closeModalButton = document.querySelector('.modal-footer .btn-secondary');
        closeModalButton.addEventListener('click', function() {
            detailModal.hide();
        });
    </script>
@endsection

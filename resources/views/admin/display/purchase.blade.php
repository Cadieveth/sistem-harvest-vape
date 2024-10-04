@extends('layouts.app')

@section('title')
    <title>Purchase - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @php
        use Carbon\Carbon;
    @endphp

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Data Pembelian</h5>

                <div id="card-container" class="flex justify-between mb-2">
                    <div id="card-1" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Kuantitas</div>
                                <div class="font-bold text-2xl">{{ $totalPurchaseQuantity }}</div>
                                <div class="text-xs">pcs Persediaan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-box-seam"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-2" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Persediaan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPurchase, 2, ',', '.') }}</div>
                                <div class="text-xs">dari Total Pembelian</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-coin"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-3" class="border-2 border-indigo-500 mr-3 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Saldo Aset</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPurchaseAsset, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">Peralatan dan Perlengkapan</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-tools"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-4" class="border-2 border-indigo-500 w-full rounded-lg p-3">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Kas Keluar</div>
                                <div class="font-bold text-2xl">{{ number_format($totalPurchaseCashOut, 2, ',', '.') }}
                                </div>
                                <div class="text-xs">dari Transaksi Pembelian</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i
                                    class="bi bi-currency-dollar" style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.purchases.index') }}">
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
                            <a href="{{ route('admin.purchases.index', ['reset_filter' => 1]) }}"
                                class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Reset"><i
                                    class="ti ti-adjustments-off"></i></a>
                        </div>
                    </div>
                    <div class="flex justify-end -mt-3">
                        <a href="{{ route('admin.addPurchase') }}" type="button" class="btn btn-outline-primary m-1"><i
                                class="bi bi-plus-lg mr-1"></i> Add
                            Purchase</a>
                    </div>
                </form>

                <hr class="mt-2">

                <div class="overflow-auto table-responsive">
                    @if ($purchases->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm" id="table1">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Purchase</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Biaya Pengiriman</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td class="text-nowrap">
                                            {{ Carbon::parse($purchase->tanggal_pembelian)->format('d M Y') }}</td>
                                        <td style="width: 10%">{{ $purchase->kode_purchase }}</td>
                                        <td style="width: 30%">{{ $purchase->nama_barang }}</td>
                                        <td style="width: 5%">{{ $purchase->jumlah_barang }}</td>
                                        <td style="width: 17%" class="text-nowrap">Rp
                                            {{ number_format($purchase->harga_barang, 2, ',', '.') }}</td>
                                        <td style="width: 17%" class="text-nowrap">Rp
                                            {{ number_format($purchase->total_purchase, 2, ',', '.') }}</td>
                                        <td style="width: 17%" class="text-nowrap">Rp
                                            {{ number_format($purchase->biaya_kirim, 2, ',', '.') }}</td>
                                        <td style="width: 11%">{{ $purchase->ket_purchase }}</td>
                                        <td style="width: 7%">
                                            <form action="{{ route('admin.purchases.destroy', ['id' => $purchase->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="action-icons">
                                                    <a href="{{ route('admin.purchases.edit', ['id' => $purchase->id]) }}"
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
                            <li class="page-item {{ $purchases->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $purchases->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $purchases->currentPage();
                                $lastPage = $purchases->lastPage();
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
                                        $purchases->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $purchases->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $purchases->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li
                                class="page-item {{ $purchases->currentPage() == $purchases->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $purchases->appends(request()->except('page'))->nextPageUrl() }}"
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

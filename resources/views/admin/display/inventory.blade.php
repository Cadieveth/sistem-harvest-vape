@extends('layouts.app')

@section('title')
    <title>Inventory - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Data Persediaan</h5>

                <div id="card-container" class="flex justify-start mb-2">
                    <div id="card-1" class="border-2 border-indigo-500 mr-3 rounded-lg p-3" style="width: 25%">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Total Kuantitas</div>
                                <div class="font-bold text-2xl">{{ $totalInventoryQuantity }}</div>
                                <div class="text-xs">Persediaan Akhir</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-box-seam"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                    <div id="card-2" class="border-2 border-indigo-500 mr-3 rounded-lg p-3" style="width: 25%">
                        <div id="card-header" class="flex justify-between">
                            <div style="width: 80%">
                                <div class="font-bold text-base">Saldo Persediaan</div>
                                <div class="font-bold text-2xl">{{ number_format($totalInventory, 2, ',', '.') }}</div>
                                <div class="text-xs">Barang tersedia untuk dijual</div>
                            </div>
                            <div class="flex justify-center items-center mr-2" style="width: 20%"><i class="bi bi-coin"
                                    style="font-size: 60px; color: #5d87ff;"></i></div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.inventories.index') }}">
                    <div class="col-md-10 mt-5">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="barang" class="form-label">Filter by Name:</label>
                                <input type="text" class="form-control" id="barang" name="barang"
                                    value="{{ !empty($_GET['barang']) ? $_GET['barang'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="tooltip" title="Apply Filter"><i
                                class="ti ti-adjustments"></i></button>
                        <a href="{{ route('admin.inventories.index', ['reset_filter' => 1]) }}" class="btn btn-primary mt-4"
                            data-bs-toggle="tooltip" title="Reset"><i class="ti ti-adjustments-off"></i></a>
                    </div>
                </form>

                <hr class="mt-4">

                <div class="overflow-auto table-responsive">
                    @if ($inventories->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <table class="table text-sm">
                            <thead>
                                <tr>
                                    <th>Kode Data</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $inventory)
                                    <tr class="{{ $inventory->jumlah_barang == 0 ? 'alert alert-danger' : '' }}">
                                        <td style="width: 10%">{{ $inventory->kode_purchase }}</td>
                                        <td style="width: 10%">
                                            <span>{{ $inventory->kode_barang }}</span><br>
                                            <strong style="font-size: 11px">Batch {{ $inventory->batch }}</strong>
                                        </td>
                                        <td style="width: 40%">{{ $inventory->nama_barang }}</td>
                                        <td style="width: 10%">{{ $inventory->jumlah_barang }}</td>
                                        <td style="width: 20%">Rp
                                            {{ number_format($inventory->harga_barang, 2, ',', '.') }}
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
                            <li class="page-item {{ $inventories->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $inventories->appends(request()->except('page'))->previousPageUrl() }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only"></span>
                                </a>
                            </li>

                            @php
                                $currentPage = $inventories->currentPage();
                                $lastPage = $inventories->lastPage();
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
                                        $inventories->appends(request()->except('page'))->url(1) .
                                        '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' .
                                        ($i == $currentPage ? 'active' : '') .
                                        '"><a class="page-link" href="' .
                                        $inventories->appends(request()->except('page'))->url($i) .
                                        '">' .
                                        $i .
                                        '</a></li>';
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="' .
                                        $inventories->appends(request()->except('page'))->url($lastPage) .
                                        '">' .
                                        $lastPage .
                                        '</a></li>';
                                }
                            @endphp

                            <li
                                class="page-item {{ $inventories->currentPage() == $inventories->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $inventories->appends(request()->except('page'))->nextPageUrl() }}"
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
@endsection

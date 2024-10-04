@extends('layouts.app')

@section('title')
    <title>Asset - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h5 class="card-title fw-semibold mb-4">Asset</h5>
                <hr>
                @component('components.tabMenu')
                @endcomponent
                <div id="tab-content">
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
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventories as $inventory)
                                        <tr>
                                            <td style="width: 10%">{{ $inventory->kode_barang }}</td>
                                            <td style="width: 40%">{{ $inventory->nama_barang }}</td>
                                            <td style="width: 10%">{{ $inventory->jumlah_barang }}</td>
                                            <td style="width: 20%">Rp
                                                {{ number_format($inventory->harga_barang, 2, ',', '.') }}</td>
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
    </div>
@endsection

{{-- @section('js')
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');

            // Menggunakan AJAX atau cara lain untuk memuat konten halaman di sini
            // Contoh:
            fetch('/admin/display/' + tabId)
                .then(response => response.text())
                .then(content => {
                    document.getElementById('tab-content').innerHTML = content;
                });
        }
    </script>
@endsection --}}

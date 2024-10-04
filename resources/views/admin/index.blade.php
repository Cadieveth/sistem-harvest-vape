@extends('layouts.app')

@section('title')
    <title>Dashboard - Harvest Vape</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <!--  Row 1 -->
        <div class="py-14"></div>
        <div>
            <h3>Hello, {{ Auth::user()->name }}!</h3>
            <p>Status: <u>{{ Auth::user()->role }}</u></p>
        </div>
        <div class="row">
            {{-- Chart --}}
            <div class="col-lg-8 d-flex align-items-strech">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                            <div class="mb-3 mb-sm-0">
                                <h5 class="card-title fw-semibold">Sales Overview</h5>
                            </div>
                            <div>
                                <form method="GET" action="{{ route('dashboard') }}" id="monthYearForm">
                                    <select class="form-select" name="bulan" id="monthYearSelect">
                                        @foreach ($datasales ?? [] as $row)
                                            <option value="{{ $row->formatted_month ?? '' }}"
                                                {{ $bulan == ($row->formatted_month ?? '') ? 'selected' : '' }}>
                                                {{ isset($row->formatted_month) ? \Carbon\Carbon::createFromFormat('m-Y', $row->formatted_month)->format('F Y') : '0' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div id="chart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Yearly Breakup -->
                        <div class="card overflow-hidden">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-9 fw-semibold">Yearly Breakup</h5>
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        @if (!empty($salesData) && isset($salesData[0]))
                                            <h4 class="fw-semibold mb-3">Rp {{ number_format($salesData[0]) }}</h4>
                                            <div class="d-flex align-items-center mb-3">
                                                <span
                                                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                                    @if ($percentageChangeBreakup > 0)
                                                        <i class="ti ti-arrow-up-left text-success"></i>
                                                    @else
                                                        <i class="ti ti-arrow-down-left text-danger"></i>
                                                    @endif
                                                </span>
                                                <p class="text-dark me-1 fs-3 mb-0">{{ $percentageChangeBreakup }}%</p>
                                                <p class="fs-3 mb-0">last year</p>
                                            </div>
                                        @else
                                            <h4 class="fw-semibold mb-3">Rp 0</h4>
                                            <div class="d-flex align-items-center mb-3">
                                                <span
                                                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-arrow-up-left text-success"></i>
                                                </span>
                                                <p class="text-dark me-1 fs-3 mb-0">0%</p>
                                                <p class="fs-3 mb-0">last year</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-center">
                                            <div id="breakup"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <!-- Monthly Earnings -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-8">
                                        <h5 class="card-title mb-9 fw-semibold"> Monthly Earnings </h5>
                                        @if (isset($currentMonthEarnings))
                                            <h4 class="fw-semibold mb-3">Rp {{ number_format($currentMonthEarnings) }}</h4>
                                            <div class="d-flex align-items-center pb-1">
                                                <span
                                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                                    @if (isset($percentageChangeFormatted) && $percentageChangeFormatted > 0)
                                                        <i class="ti ti-arrow-up-left text-success"></i>
                                                    @else
                                                        <i class="ti ti-arrow-down-left text-danger"></i>
                                                    @endif
                                                </span>
                                                <p class="text-dark me-1 fs-3 mb-0">
                                                    {{ isset($percentageChangeFormatted) ? $percentageChangeFormatted : 0 }}%
                                                </p>
                                                <p class="fs-3 mb-0">last year</p>
                                            </div>
                                        @else
                                            <h4 class="fw-semibold mb-3">Rp 0</h4>
                                            <div class="d-flex align-items-center pb-1">
                                                <span
                                                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-arrow-down-left text-danger"></i>
                                                </span>
                                                <p class="text-dark me-1 fs-3 mb-0">0%</p>
                                                <p class="fs-3 mb-0">last year</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-end">
                                            <div
                                                class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-currency-dollar fs-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="earning"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 d-flex align-items-stretch">
                {{-- Best Seller  --}}
                <div class="card w-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-4">Best Seller</h5>
                        @if ($bestSellers->isEmpty())
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">No</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Kode Barang</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Nama Barang</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Total Sales</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bestSellers as $index => $row)
                                            <tr>
                                                <td class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->kode_barang }}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->nama_barang }}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->total_sales_sum }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex align-items-stretch">
                {{-- Top Vendor  --}}
                <div class="card w-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-semibold mb-4">Top Vendor</h5>
                        @if ($topVendors->isEmpty())
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">No</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Kode Vendor</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Nama Vendor</h6>
                                            </th>
                                            <th class="border-bottom-0">
                                                <h6 class="fw-semibold mb-0">Total Purchase</h6>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topVendors as $index => $row)
                                            <tr>
                                                <td class="border-bottom-0">
                                                    <h6 class="fw-semibold mb-0">{{ $index + 1 }}</h6>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->vendor->kode_vendor }}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->vendor->nama_vendor }}</p>
                                                </td>
                                                <td class="border-bottom-0">
                                                    <p class="mb-0 fw-normal">{{ $row->total_transactions }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            {{-- Last Stock --}}
            @if ($inventory->isEmpty())
                <p class="flex justify-center items-center font-bold text-center alert alert-info">No Stock Data Available
                </p>
            @else
                <div class="row">
                    @foreach ($inventory as $row)
                        <div class="col-sm-6 col-xl-3 d-flex">
                            <div class="card overflow-hidden rounded-2 flex-fill">
                                <div class="card-body pt-3 p-4">
                                    <h6 class="fw-semibold fs-4 mb-4">{{ $row->nama_barang }}</h6>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="text-muted fs-4 mb-0 flex justify-end items-start">Sisa stok:
                                            {{ $row->jumlah_barang }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection

@section('js')
    <script>
        $(function() {
            $('#monthYearSelect').on('change', function() {
                $('#monthYearForm').submit();
            });

            var chart = {
                series: [{
                    name: "Sales:",
                    data: @json($data),
                }, ],

                chart: {
                    type: "bar",
                    height: 345,
                    offsetX: -15,
                    toolbar: {
                        show: true
                    },
                    foreColor: "#adb0bb",
                    fontFamily: "inherit",
                    sparkline: {
                        enabled: false
                    },
                },

                colors: ["#5D87FF"],

                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "35%",
                        borderRadius: [6],
                        borderRadiusApplication: "end",
                        borderRadiusWhenStacked: "all",
                    },
                },
                markers: {
                    size: 0
                },

                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },

                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },

                xaxis: {
                    type: "category",
                    categories: @json($categories),
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color"
                        },
                    },
                },

                yaxis: {
                    show: true,
                    min: 0,
                    tickAmount: 4,
                    labels: {
                        style: {
                            cssClass: "grey--text lighten-2--text fill-color",
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 3,
                    lineCap: "butt",
                    colors: ["transparent"],
                },

                tooltip: {
                    theme: "light"
                },

                responsive: [{
                    breakpoint: 600,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 3,
                            },
                        },
                    },
                }, ],
            };

            var chart = new ApexCharts(document.querySelector("#chart"), chart);
            chart.render();

            // =====================================
            // Breakup
            // =====================================
            var breakup = {
                color: "#adb5bd",
                series: {!! json_encode($salesData) !!},
                labels: {!! json_encode($salesByYear->pluck('tahun')) !!},
                chart: {
                    width: 180,
                    type: "donut",
                    fontFamily: "Plus Jakarta Sans', sans-serif",
                    foreColor: "#adb0bb",
                },
                plotOptions: {
                    pie: {
                        startAngle: 0,
                        endAngle: 360,
                        donut: {
                            size: "75%",
                        },
                    },
                },
                stroke: {
                    show: false,
                },

                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },
                colors: ["#5D87FF", "#ecf2ff", "#F9F9FD"],

                responsive: [{
                    breakpoint: 991,
                    options: {
                        chart: {
                            width: 150,
                        },
                    },
                }, ],
                tooltip: {
                    theme: "dark",
                    fillSeriesColor: false,
                },
            };

            var chart = new ApexCharts(document.querySelector("#breakup"), breakup);
            chart.render();

            // =====================================
            // Earning
            // =====================================
            var earning = {
                chart: {
                    id: "sparkline3",
                    type: "area",
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                    group: "sparklines",
                    fontFamily: "Plus Jakarta Sans', sans-serif",
                    foreColor: "#adb0bb",
                },
                series: [{
                    name: "Earnings",
                    color: "#49BEFF",
                    data: {!! json_encode($monthlyEarnings) !!},
                }],
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                fill: {
                    colors: ["#f3feff"],
                    type: "solid",
                    opacity: 0.05,
                },
                markers: {
                    size: 0,
                },
                tooltip: {
                    theme: "dark",
                    fixed: {
                        enabled: true,
                        position: "right",
                    },
                    x: {
                        show: false,
                    },
                },
            };

            new ApexCharts(document.querySelector("#earning"), earning).render();
        });
    </script>
@endsection

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

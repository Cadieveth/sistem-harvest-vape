@extends('layouts.app')

@section('title')
    <title>Laporan Laba/Rugi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Laporan Laba/Rugi</h4>
                <hr>

                <form method="GET" action="{{ route('admin.labaRugi') }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="dari">Start Date</label>
                                <input type="date" name="dari" class="form-control" required
                                    value="{{ old('dari', $dari) }}">
                            </div>
                            <div class="col-md-2">
                                <label for="dari">End Date</label>
                                <input type="date" name="sampai" class="form-control" required
                                    value="{{ old('sampai', $sampai) }}">
                            </div>
                            <div class="col-md-5 align-self-end">
                                <button type="submit" class="btn btn-primary text-white me-2">Show</button>
                                @if ($dari && $sampai)
                                    <a href="{{ route('admin.labaRugi.cetak', ['dari' => $dari, 'sampai' => $sampai]) }}"
                                        class="btn btn-success text-white" target="_blank">Print</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <hr class="mt-4">

                @if ($dari && $sampai)
                    <div class="table-responsive">
                        <div id="header-sheet" class="text-center">
                            <p><b>Harvest Vape</b></p>
                            <p><b>Laporan Laba/Rugi</b></p>
                            <p><b>Per {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</b></p>

                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th colspan="4">Pendapatan</th>
                                </tr>
                                <tr>
                                    <td>Penjualan</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp {{ number_format($totalSales, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Potongan Penjualan</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp {{ number_format($totalDiscount, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Penjualan Bersih</th>
                                    <td></td>
                                    <td></td>
                                    <th class="text-nowrap text-end">Rp {{ number_format($netSales, 2, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="4">Harga Pokok Penjualan (Cost of Good Sold)</th>
                                </tr>
                                <tr>
                                    <td>Pembelian</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp {{ number_format($totalPurchase, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                {{-- <tr>
                                    <td>Beban Angkut Pembelian</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp
                                        {{ number_format($totalBebanPengiriman, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr> --}}
                                <tr>
                                    <th>Harga Pokok Penjualan</th>
                                    <td></td>
                                    <td></td>
                                    <th class="text-nowrap text-end">Rp
                                        {{ number_format($totalHPP, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4">Beban Operasional</th>
                                </tr>
                                @forelse ($groupedPayments as $payment)
                                    <tr>
                                        <td>Beban {{ $payment['ket_payment'] }}</td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($payment['cost_payment'], 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Beban</td>
                                        <td class="text-nowrap text-end">Rp {{ number_format(0, 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td>Total Beban Operasional</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp
                                        {{ number_format($totalPayment, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th colspan="4">Beban Bunga dan Pajak</th>
                                </tr>
                                @forelse ($groupedTaxPayments as $taxPayment)
                                    <tr>
                                        <td>Beban {{ $taxPayment['ket_payment'] }}</td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($taxPayment['cost_payment'], 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Beban</td>
                                        <td class="text-nowrap text-end">Rp {{ number_format(0, 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td>Total Beban Non-Operasional</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp
                                        {{ number_format($totalTaxPayment, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Total Beban</th>
                                    <td></td>
                                    <td></td>
                                    <th class="text-nowrap text-end">Rp
                                        {{ number_format($totalBeban, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($laba < 0)
                                            Rugi
                                        @else
                                            Laba Bersih
                                        @endif
                                    </th>
                                    <td></td>
                                    <td></td>
                                    <th class="text-nowrap text-end">
                                        @if ($laba < 0)
                                            (Rp {{ number_format(abs($laba), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format($laba, 2, ',', '.') }}
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info flex justify-center"><b>Select Period</b></div>
                @endif
            </div>
        </div>
    </div>
@endsection

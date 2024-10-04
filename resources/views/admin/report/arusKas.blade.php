@extends('layouts.app')

@section('title')
    <title>Laporan Perubahan Arus Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Laporan Perubahan Arus Kas</h4>
                <hr>

                <form method="GET" action="{{ route('admin.arusKas') }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="sampai">End Date</label>
                                <input type="date" name="sampai" class="form-control" required
                                    value="{{ old('sampai', $sampai) }}">
                            </div>
                            <div class="col-md-5 align-self-end">
                                <button type="submit" class="btn btn-primary text-white me-2">Show</button>
                                @if ($sampai)
                                    <a href="{{ route('admin.arusKas.cetak', ['sampai' => $sampai]) }}"
                                        class="btn btn-success text-white" target="_blank">Print</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <hr class="mt-4">

                @if ($sampai)
                    <div class="table-responsive">
                        <div id="header-sheet" class="text-center">
                            <p><b>Harvest Vape</b></p>
                            <p><b>Laporan Arus Kas</b></p>
                            <p><b>Per {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</b></p>

                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th colspan="4">Saldo Awal Kas</th>
                                    <th class="text-nowrap text-end">Rp {{ number_format($saldoAwalKas, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <td>Penjualan</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-nowrap text-end">Rp
                                        {{ number_format($sales, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th colspan="4">Penerimaan kas dari sales</th>
                                    <th class="text-nowrap text-end">Rp {{ number_format($netSales, 2, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="5">Arus Kas dari Aktivitas Operasional</th>
                                </tr>
                                @foreach ($purchase2 as $row)
                                    <tr>
                                        <td>Pembelian {{ $row->ket_purchase }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($row->total_purchase_sum, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                @forelse ($groupedPayments as $row)
                                    <tr>
                                        <td>Beban {{ $row['ket_payment'] }}</td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($row['cost_payment'], 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Beban</td>
                                        <td class="text-nowrap text-end">Rp {{ number_format(0, 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td>Total Beban Operasional</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">
                                        @if ($totalOperasional < 0)
                                            (Rp {{ number_format(abs($totalPayment), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($totalPayment), 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Kas dari aktivitas operasional</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-nowrap text-end">
                                        @if ($totalOperasional < 0)
                                            (Rp {{ number_format(abs($totalOperasional), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($totalOperasional), 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($groupedPayments2 as $row)
                                    <tr>
                                        <td>Pembayaran {{ $row['ket_payment'] }}</td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($row['cost_payment'], 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total Beban Non-Operasional</td>
                                    <td></td>
                                    <td class="text-nowrap text-end">
                                        @if ($totalOperasional < 0)
                                            (Rp {{ number_format(abs($totalPayment2), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($totalPayment2), 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Kas dari aktivitas operasional</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-nowrap text-end">
                                        @if ($totalOperasional2 < 0)
                                            (Rp {{ number_format(abs($totalOperasional2), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($totalOperasional2), 2, ',', '.') }}
                                        @endif
                                    <td></td>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4">Kas bersih dari aktivitas operasional</th>
                                    <th class="text-nowrap text-end">
                                        @if ($kasOperasional < 0)
                                            (Rp {{ number_format(abs($kasOperasional), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($kasOperasional), 2, ',', '.') }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="5">Arus Kas dari Aktivitas Investasi</th>
                                </tr>
                                @foreach ($purchase as $row)
                                    <tr>
                                        <td>Pembelian {{ $row->ket_purchase }}</td>
                                        <td></td>
                                        <td class="text-nowrap text-end">Rp
                                            {{ number_format($row->total_purchase_sum, 2, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>kas dari aktivitas investasi</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-nowrap text-end">
                                        @if (count($purchase) > 0)
                                            Rp {{ number_format(abs($totalPurchase), 2, ',', '.') }}
                                        @else
                                            Rp {{ number_format(0, 2, ',', '.') }}
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th colspan="4">Kas akhir periode</th>
                                    <th class="text-nowrap text-end">
                                        @if ($totalInvestasi < 0)
                                            (Rp {{ number_format(abs($totalInvestasi), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format(abs($totalInvestasi), 2, ',', '.') }}
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

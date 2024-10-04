<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Perubahan Arus Kas</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        th {
            height: 25px;
            font-weight: bold;
            text-align: center;
        }

        table,
        th,
        td {
            border: none;
        }

        th,
        td {
            padding: 4px;
        }

        td {
            vertical-align: top;
        }

        thead {
            background: lightgray;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .table-no-border,
        .table-no-border th,
        .table-no-border td {
            border: none;
        }

        .mt-1 {
            margin-top: 20px;
        }

        .mt-2 {
            margin-top: 40px;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-0 {
            margin-top: 0;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100vh;
            text-align: center;
        }

        .header-content {
            display: inline-block;
            margin-top: 10px;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .no-margin {
            margin-bottom: 0;
            margin-top: 0;
        }

        .text-nowrap {
            white-space: nowrap;
        }
    </style>
</head>

@php
    use Carbon\Carbon;
@endphp

<body>
    <div class="container">
        <img class="logo" width="80" src="{{ $base64Image }}" alt="Logo">
        <div class="header-content">
            <h2 class="no-margin">
                HARVEST VAPE
            </h2>
            <p class="no-margin">
                Jl. Brigjen. Slamet Riyadi No. 36 Kepatihan
                <br>
                Kranggan, Kec. Ambarawa, Kab. Semarang
                <br>
                Jawa Tengah 50613
            </p>
        </div>
    </div>
    <hr>

    <h3 class="center" style="font-size: 15px">
        Laporan Perubahan Arus Kas
    </h3>

    <h3 class="center" style="font-size: 15px">
        Periode {{ Carbon::parse($sampai)->translatedFormat('d F Y') }}
    </h3>

    <table class="table mt-1">
        <tbody>
            <tr>
                <td colspan="4"><b>Saldo Awal Kas</b></td>
                <td class="text-nowrap text-end"><b>Rp {{ number_format($saldoAwalKas, 2, ',', '.') }}</b></td>
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
                <td colspan="4"><b>Penerimaan kas dari sales</b></td>
                <td class="text-nowrap text-end"><b>Rp {{ number_format($netSales, 2, ',', '.') }}</b></td>
            </tr>
            <tr>
                <td colspan="5"><b>Arus Kas dari Aktivitas Operasional</b></td>
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
                <td colspan="4"><b>Kas bersih dari aktivitas operasional</b></td>
                <td class="text-nowrap text-end"><b>
                        @if ($kasOperasional < 0)
                            (Rp {{ number_format(abs($kasOperasional), 2, ',', '.') }})
                        @else
                            Rp {{ number_format(abs($kasOperasional), 2, ',', '.') }}
                        @endif
                    </b></td>
            </tr>
            <tr>
                <td colspan="5"><b>Arus Kas dari Aktivitas Investasi</b></td>
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
                <td colspan="4"><b>Kas akhir periode</b></td>
                <td class="text-nowrap text-end"><b>
                        @if ($totalInvestasi < 0)
                            (Rp {{ number_format(abs($totalInvestasi), 2, ',', '.') }})
                        @else
                            Rp {{ number_format(abs($totalInvestasi), 2, ',', '.') }}
                        @endif
                    </b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>

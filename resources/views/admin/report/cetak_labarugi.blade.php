<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Laba/Rugi</title>
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

    <h4 class="center">
        Laporan Laba/Rugi
    </h4>

    <table class="table-no-border">
        <tr>
            <td width="40">Periode</td>
            <td>
                : {{ date('d-m-Y', strtotime($dari)) }} s/d {{ date('d-m-Y', strtotime($sampai)) }}
            </td>
        </tr>
    </table>

    <table class="table mt-1">
        <tbody>
            <tr>
                <td colspan="4"><b>Pendapatan</b></td>
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
                <td><b>Penjualan Bersih</b></td>
                <td></td>
                <td></td>
                <td class="text-nowrap text-end"><b>
                        Rp {{ number_format($netSales, 2, ',', '.') }}
                    </b></td>
            </tr>

            <tr>
                <td colspan="4"><b>Harga Pokok Penjualan (Cost of Good Sold)</b></td>
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
                <td><b>Harga Pokok Penjualan</b></td>
                <td></td>
                <td></td>
                <td class="text-nowrap text-end"><b>
                        Rp {{ number_format($totalHPP, 2, ',', '.') }}
                    </b></td>
            </tr>

            <tr>
                <td colspan="4"><b>Beban Operasional</b></td>
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
                <td class="text-nowrap text-end">
                    Rp {{ number_format($totalPayment, 2, ',', '.') }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"><b>Beban Bunga dan Pajak</b></td>
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
                <td class="text-nowrap text-end">
                    Rp {{ number_format($totalTaxPayment, 2, ',', '.') }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td><b>Total Beban</b></td>
                <td></td>
                <td></td>
                <td class="text-nowrap text-end"><b>
                        Rp {{ number_format($totalBeban, 2, ',', '.') }}
                    </b></td>
            </tr>

            <tr>
                <td><b>
                        @if ($laba < 0)
                            Rugi
                        @else
                            Laba Bersih
                        @endif
                    </b></td>
                <td></td>
                <td></td>
                <td class="text-nowrap text-end"><b>
                        @if ($laba < 0)
                            (Rp {{ number_format(abs($laba), 2, ',', '.') }})
                        @else
                            Rp {{ number_format($laba, 2, ',', '.') }}
                        @endif
                    </b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Neraca</title>
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

        .text-end {
            text-align: right;
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
        Laporan Neraca
    </h3>

    <h3 class="center" style="font-size: 15px">
        Periode {{ Carbon::parse($sampai)->translatedFormat('d F Y') }}
    </h3>

    <table class="table mt-1">
        <thead>
            <tr>
                <td><b>Akun</b></td>
                <td class="text-nowrap text-end"><b>Aktiva</b></td>
                <td class="text-nowrap text-end"><b>Kewajiban dan Ekuitas</b></td>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebit = 0;
                $totalKredit = 0;
            @endphp
            @foreach ($accountBalances as $balance)
                @php
                    $kodeAkun = $balance['account']->kode_akun;
                    $saldoAkhir = $balance['saldoAkhir'];
                @endphp
                <tr>
                    <td>{{ $balance['account']->nama_akun }}</td>
                    @if (str_starts_with($kodeAkun, '1'))
                        <td class="text-nowrap text-end">{{ number_format($saldoAkhir, 2, ',', '.') }}</td>
                        <td class="text-nowrap text-end"></td>
                        @php $totalDebit += $saldoAkhir; @endphp
                    @elseif (str_starts_with($kodeAkun, '2') || str_starts_with($kodeAkun, '3'))
                        <td class="text-nowrap text-end"></td>
                        <td class="text-nowrap text-end">{{ number_format(abs($saldoAkhir), 2, ',', '.') }}</td>
                        @php $totalKredit += abs($saldoAkhir); @endphp
                    @endif
                </tr>
            @endforeach
            {{-- <tr>
                <td>Total Saldo Akun</td>
                <td class="text-nowrap text-end">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                <td class="text-nowrap text-end">{{ number_format($totalKredit, 2, ',', '.') }}</td>
            </tr> --}}
            <tr>
                <td>{{ $laba < 0 ? 'rugi' : 'laba' }}</td>
                <td></td>
                <td class="text-nowrap text-end">
                    {{ $laba < 0 ? '(' . number_format(abs($laba), 2, ',', '.') . ')' : number_format($laba, 2, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td>Modal Awal Harvest Vape</td>
                <td></td>
                <td class="text-nowrap text-end">{{ number_format($totalModal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td class="text-nowrap text-end"><b>{{ number_format($totalDebit, 2, ',', '.') }}</b></td>
                <td class="text-nowrap text-end"><b>
                        {{ number_format($totalKredit + $totalModal + $laba, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>

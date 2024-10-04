<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Perubahan Ekuitas</title>
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
        Laporan Perubahan Ekuitas
    </h3>

    <h3 class="center" style="font-size: 15px">
        Periode {{ Carbon::parse($sampai)->translatedFormat('d F Y') }}
    </h3>

    <table class="table mt-1" style="width: 100%">
        <tbody>
            <tr>
                <td><b>Modal Awal</b></td>
                <td class="text-nowrap text-end"><b>Rp {{ number_format($totalModalAwal, 2, ',', '.') }}
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
                <td class="text-nowrap text-end"><b>
                        @if ($laba < 0)
                            (Rp {{ number_format(abs($laba), 2, ',', '.') }})
                        @else
                            Rp {{ number_format($laba, 2, ',', '.') }}
                        @endif
                    </b></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-nowrap text-end">
                    @if ($total < 0)
                        (Rp {{ number_format(abs($total), 2, ',', '.') }})
                    @else
                        Rp {{ number_format($total, 2, ',', '.') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td><b>Modal Akhir ({{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }})</b></td>
                <td class="text-nowrap text-end"><b>
                        @if ($total < 0)
                            (Rp {{ number_format(abs($total), 2, ',', '.') }})
                        @else
                            Rp {{ number_format($total, 2, ',', '.') }}
                        @endif
                    </b></td>
            </tr>
        </tbody>
    </table>

</body>

</html>

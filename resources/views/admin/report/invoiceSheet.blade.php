@extends('layouts.app')
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-header {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #557ada;
        }


        .invoice-logo {
            margin-right: 20px;
            max-width: 100px;
        }

        .invoice-title {
            text-align: center;
            font-size: 24px;
            margin-top: 30px;
        }

        .invoice-info {
            margin-top: 30px;
        }

        .invoice-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .invoice-table th {
            border: 1px solid #557ada;
            padding: 10px;
            text-align: left;
        }


        .invoice-table td {
            border: 0px;
            padding: 10px;
            text-align: left;
        }

        .invoice-subtotal {
            border-top: 1px solid #557ada;
            border-bottom: 1px solid #557ada;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-status {
            padding: 10px;
            text-align: right;
            margin-top: 50px;

        }

        .invoice-footer {
            background-color: #557ada;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        .btn-primary {
            background-color: #557ada;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #557ada;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    @section('content')
        <!-- Start Main -->
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold mb-4">Invoice Template</h5>
                            <div>
                                <button class="btn btn-outline-primary m-1"><i class="ti ti-download"></i></button>
                                <button class="btn btn-outline-primary m-1"><i class="ti ti-share"></i></button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-4">
                                <table class="table">
                                    <div class="invoice-header">
                                        <div class="invoice-logo">
                                            <img src="{{ asset('backend/asset/img/logos/Logo HV.png') }}" alt="Logo"
                                                width="120px" style="margin-bottom: 10px">
                                        </div>
                                        <div class="invoice-address" style="margin-left: 40px">
                                            <p><strong>HARVEST VAPE</strong></p>
                                            <p>Jl. Brigjen. Slamet Riyadi No. 36 Kepatihan</p>
                                            <p>Kranggan, Kec. Ambarawa, Kab. Semarang</p>
                                            <p>Jawa Tengah 50613</p>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h2 class="invoice-title">INVOICE</h2>
                                                <div class="invoice-info">
                                                    <p>Tanggal: {{ date('Y-m-d') }}</p>
                                                    <p>Tlpn: 0899-9410-100</p>
                                                </div>
                                                <table class="invoice-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%">Jumlah</th>
                                                            <th style="width: 55%">Nama Produk</th>
                                                            <th style="width: 20%">Harga Satuan</th>
                                                            <th style="width: 20%">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center"> 2 </td>
                                                            <td> English Breakfast V3 </td>
                                                            <td> Rp 125.000,00 </td>
                                                            <td> Rp 250.000,00 </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center"> 1 </td>
                                                            <td> Oat Drips V1 </td>
                                                            <td> Rp 140.000,00 </td>
                                                            <td> Rp 140.000,00 </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="invoice-subtotal">
                                                    <span>Sub Total</span>
                                                    <span style="margin-right: 75px">Rp 390.000,00</span>
                                                </div>
                                                <div class="invoice-status">
                                                    <p>{{ date('d-M-Y') }}</p>
                                                    <p>Tanda terima,</p>
                                                    <h3 style="margin-top: 50px">[LUNAS]</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="invoice-footer">
                                        INVOICE: Harvest Vape
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main -->
    @endsection
</body>

</html>

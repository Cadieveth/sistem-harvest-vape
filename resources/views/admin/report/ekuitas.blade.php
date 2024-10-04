@extends('layouts.app')

@section('title')
    <title>Laporan Perubahan Ekuitas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Laporan Perubahan Ekuitas</h4>
                <hr>

                <form method="GET" action="{{ route('admin.ekuitas') }}">
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
                                    <a href="{{ route('admin.ekuitas.cetak', ['sampai' => $sampai]) }}"
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
                            <p><b>Laporan Perubahan Modal</b></p>
                            <p><b>Per {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</b></p>

                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Modal Awal</th>
                                    <th class="text-nowrap text-end">Rp {{ number_format($totalModalAwal, 2, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($laba < 0)
                                            Rugi
                                        @else
                                            Laba Bersih
                                        @endif
                                    </th>
                                    <th class="text-nowrap text-end">
                                        @if ($laba < 0)
                                            (Rp {{ number_format(abs($laba), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format($laba, 2, ',', '.') }}
                                        @endif
                                    </th>
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
                                    <th>Modal Akhir ({{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }})</th>
                                    <th class="text-nowrap text-end">
                                        @if ($total < 0)
                                            (Rp {{ number_format(abs($total), 2, ',', '.') }})
                                        @else
                                            Rp {{ number_format($total, 2, ',', '.') }}
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

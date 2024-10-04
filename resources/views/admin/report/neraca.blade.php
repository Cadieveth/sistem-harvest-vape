@extends('layouts.app')

@section('title')
    <title>Laporan Neraca</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Laporan Neraca</h4>
                <hr>

                <form method="GET" action="{{ route('admin.neraca') }}">
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
                                    <a href="{{ route('admin.neraca.cetak', ['sampai' => $sampai]) }}"
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
                            <p><b>Laporan Neraca</b></p>
                            <p><b>Per {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</b></p>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Aktiva</th>
                                    <th class="text-end">Kewajiban dan Ekuitas</th>
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
                                            <td class="text-end">{{ number_format($saldoAkhir, 2, ',', '.') }}</td>
                                            <td class="text-end"></td>
                                            @php $totalDebit += $saldoAkhir; @endphp
                                        @elseif (str_starts_with($kodeAkun, '2') || str_starts_with($kodeAkun, '3'))
                                            <td class="text-end"></td>
                                            <td class="text-end">{{ number_format(abs($saldoAkhir), 2, ',', '.') }}</td>
                                            @php $totalKredit += abs($saldoAkhir); @endphp
                                        @endif
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td>Total Saldo Akun</td>
                                    <td class="text-end">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($totalKredit, 2, ',', '.') }}</td>
                                </tr> --}}
                                <tr>
                                    <td>{{ $laba < 0 ? 'rugi' : 'laba' }}</td>
                                    <td></td>
                                    <td class="text-end">
                                        {{ $laba < 0 ? '(' . number_format(abs($laba), 2, ',', '.') . ')' : number_format($laba, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Modal Awal Harvest Vape</td>
                                    <td></td>
                                    <td class="text-end">{{ number_format($totalModal, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">{{ number_format($totalDebit, 2, ',', '.') }}</th>
                                    <th class="text-end">
                                        {{ number_format($totalKredit + $totalModal + $laba, 2, ',', '.') }}</th>
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

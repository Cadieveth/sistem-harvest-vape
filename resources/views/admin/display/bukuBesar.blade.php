@extends('layouts.app')

@section('title')
    <title>Buku Besar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <h4 class="card-title fw-semibold mb-4">Buku Besar</h4>
                <hr>

                <form method="GET" action="{{ route('admin.ledgers.index') }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="debit_acc_id" class="form-label">Account:</label>
                                <select class="form-select @error('debit_acc_id') is-invalid @enderror" id="debit_acc_id"
                                    name="debit_acc_id">
                                    <option value="" disabled selected>- Choose Account -</option>
                                    @foreach ($account as $row)
                                        <option value="{{ $row->id }}"
                                            {{ old('debit_acc_id', request('debit_acc_id')) == $row->id ? 'selected' : '' }}>
                                            {{ $row->kode_akun }} - {{ $row->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('debit_acc_id')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="sampai" class="form-label">End Date</label>
                                <input type="date" name="sampai" class="form-control" required
                                    value="{{ old('sampai', request('sampai')) }}">
                            </div>
                            <div class="col-md-5 align-self-end">
                                <button type="submit" class="btn btn-primary text-white me-2">Show</button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr class="mt-4">

                @if (!$selectedAccount)
                    <div class="alert alert-info text-center"><b>Select Account and Period</b></div>
                @else
                    <div class="table-responsive">
                        <div id="header-sheet" class="text-center">
                            <p><b>Harvest Vape</b></p>
                            <p><b>Buku Besar {{ $selectedAccount->nama_akun }}</b></p>
                            <p><b>Periode {{ \Carbon\Carbon::parse($sampai)->format('d F Y') }}</b></p>
                        </div>
                        <div class="text-end mb-3">Kode Akun: {{ $selectedAccount->kode_akun }}</div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 35%">Tanggal</th>
                                    <th style="width: 5%">ref</th>
                                    <th class="text-end" style="width: 30%">Debit (Rp)</th>
                                    <th class="text-end" style="width: 30%">Kredit (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($saldoAwal)
                                    <tr id="saldo_awal">
                                        <th colspan="2">Saldo Awal {{ $selectedAccount->nama_akun }}</th>
                                        @if (in_array(substr($selectedAccount->kode_akun, 0, 1), ['1', '5']) || $selectedAccount->kode_akun == '403')
                                            <th class="text-end">{{ number_format($saldoAwal->nominal, 2, ',', '.') }}</th>
                                            <th class="text-end"></th>
                                        @else
                                            <th class="text-end"></th>
                                            <th class="text-end">{{ number_format($saldoAwal->nominal, 2, ',', '.') }}</th>
                                        @endif
                                    </tr>
                                @endif
                                @foreach ($journals as $journal)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($journal->tanggal_jurnal)->format('d/m/Y') }}</td>
                                        <td></td>
                                        @if ($journal->debit_acc_id == $selectedAccount->id)
                                            <td class="text-end">{{ number_format($journal->debit_jurnal, 2, ',', '.') }}
                                            </td>
                                            <td class="text-end"></td>
                                        @elseif ($journal->kredit_acc_id == $selectedAccount->id)
                                            <td class="text-end"></td>
                                            <td class="text-end">{{ number_format($journal->kredit_jurnal, 2, ',', '.') }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr id="saldo_akhir">
                                    <th colspan="2">Saldo Akhir {{ $selectedAccount->nama_akun }}</th>
                                    <th class="text-end">
                                        @if (substr($selectedAccount->kode_akun, 0, 1) == '1' || substr($selectedAccount->kode_akun, 0, 1) == '5')
                                            @if ($saldoAkhir >= 0)
                                                {{ number_format($saldoAkhir, 2, ',', '.') }}
                                            @endif
                                        @elseif (substr($selectedAccount->kode_akun, 0, 1) == '2' ||
                                                substr($selectedAccount->kode_akun, 0, 1) == '3' ||
                                                substr($selectedAccount->kode_akun, 0, 1) == '4')
                                            @if ($saldoAkhir >= 0)
                                                ({{ number_format($saldoAkhir, 2, ',', '.') }})
                                            @endif
                                        @endif
                                    </th>
                                    <th class="text-end">
                                        @if (substr($selectedAccount->kode_akun, 0, 1) == '1' || substr($selectedAccount->kode_akun, 0, 1) == '5')
                                            @if ($saldoAkhir < 0)
                                                ({{ number_format(abs($saldoAkhir), 2, ',', '.') }})
                                            @endif
                                        @elseif (substr($selectedAccount->kode_akun, 0, 1) == '2' ||
                                                substr($selectedAccount->kode_akun, 0, 1) == '3' ||
                                                substr($selectedAccount->kode_akun, 0, 1) == '4')
                                            @if ($saldoAkhir < 0)
                                                {{ number_format(abs($saldoAkhir), 2, ',', '.') }}
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use App\Models\BalanceSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $account = Account::all();
        $selectedAccount = null;
        $journals = collect();
        $saldoAkhir = 0;
        $saldoAwal = null;
        $sampai = $request->input('sampai');

        if ($request->has('debit_acc_id') && $request->has('sampai')) {
            $selectedAccount = Account::find($request->debit_acc_id);

            if ($selectedAccount) {
                $journals = Journal::where(function ($query) use ($selectedAccount) {
                        $query->where('debit_acc_id', $selectedAccount->id)
                            ->orWhere('kredit_acc_id', $selectedAccount->id);
                    })
                    ->whereDate('tanggal_jurnal', '<=', $sampai)
                    ->orderBy('tanggal_jurnal', 'asc')
                    ->get();

                $totalDebit = $journals->where('debit_acc_id', $selectedAccount->id)->sum('debit_jurnal');
                $totalKredit = $journals->where('kredit_acc_id', $selectedAccount->id)->sum('kredit_jurnal');

                $saldoAwal = BalanceSheet::where('id_akun', $selectedAccount->id)->first();

                $saldoAkhir = ($saldoAwal ? $saldoAwal->nominal : 0) + ($totalDebit - $totalKredit);
            }
        }

        return view('admin.display.bukuBesar', [
            'account' => $account,
            'selectedAccount' => $selectedAccount,
            'journals' => $journals,
            'saldoAkhir' => $saldoAkhir,
            'saldoAwal' => $saldoAwal,
            'sampai' => $sampai,
        ]);
    }
}

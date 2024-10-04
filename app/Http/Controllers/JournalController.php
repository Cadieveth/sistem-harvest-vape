<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\Account;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::query();

        $start_date = request()->get('start_date') ?? '';
        $end_date = request()->get('end_date') ?? '';
        $keterangan = request()->get('keterangan') ?? '';

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_jurnal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tanggal_jurnal', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tanggal_jurnal', '<=', $end_date);
        }

        if ($keterangan) {
            $query->where('ket_jurnal', 'LIKE', '%' . $keterangan . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.journals.index');
        }

        $account = Account::all();

        // Data Card
        $cloneQuery = clone $query;
        $totalDebit = $cloneQuery->sum('debit_jurnal');
        $totalKredit = $cloneQuery->sum('kredit_jurnal');

        $query->orderBy('tanggal_jurnal', 'desc')
        ->orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(ket_jurnal, ' ', -2), '(', 1) ASC")
        ->orderByRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(ket_jurnal, '(', -1), ')', 1) ASC")
        ->orderBy('id');


        $journals = $query->paginate(10);

        return view('admin.display.journal', [
            'journals' => $journals,
            'account' => $account,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
        ]);
    }

    public function create()
        {
            $account = Account::all();

            return view('admin.form.addJournal', ['account' => $account]);
        }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
        ];

        $validated = $request->validate([
            'tanggal_jurnal' => 'required',
            'debit_acc_id' => 'required',
            'kredit_acc_id' => 'required',
            'debit_jurnal' => 'required|numeric|min:1',
            'kredit_jurnal' => 'required|numeric|min:1',
            'ket_jurnal' => 'required',
        ], $message);

        Journal::create($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal added successfully');
    }

    public function edit($id)
    {
        $journal = Journal::findOrFail($id);
        $account = Account::all();
        return view('admin.edit.editJournal', [
            'journal' => $journal,
            'account' => $account,
        ]);
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
        ];

        $validated = $request->validate([
            'tanggal_jurnal' => 'required',
            'debit_acc_id' => 'required',
            'kredit_acc_id' => 'required',
            'debit_jurnal' => 'required|numeric|min:1',
            'kredit_jurnal' => 'required|numeric|min:1',
            'ket_jurnal' => 'required',
        ], $message);

        $journal = Journal::findOrFail($id);
        $journal->update($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal updated successfully');
    }

    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
        $journal->delete();

        return redirect()->route('admin.journals.index')->with('success', 'Journal deleted successfully');
    }
}

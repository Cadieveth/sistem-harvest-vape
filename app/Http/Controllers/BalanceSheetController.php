<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceSheet;
use App\Models\Account;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $query = BalanceSheet::query();

        // Filter akun dengan kode 1**, 2**, dan 5**
        $account = Account::where(function ($query) {
            $query->where('kode_akun', 'like', '1%')
                ->orWhere('kode_akun', 'like', '2%')
                ->orWhere('kode_akun', 'like', '3%');
        })->get();

        $neracaAwal = $query->join('account', 'neraca_awal.id_akun', '=', 'account.id')
                        ->orderBy('account.kode_akun', 'asc')
                        ->select('neraca_awal.*')
                        ->paginate(7);

        // Filter kode_akun 1** dan 5**
        $aktiva = $neracaAwal->filter(function ($item) {
            return preg_match('/^1\d{2}$|^5\d{2}$/', $item->account->kode_akun);
        });

        // Filter kode_akun 2**, 3**, dan 4**
        $liabilitasEkuitas = $neracaAwal->filter(function ($item) {
            return preg_match('/^2\d{2}$|^3\d{2}$|^4\d{2}$/', $item->account->kode_akun);
        });

        $modal = $liabilitasEkuitas->sum('nominal');
        $totalNominal = $neracaAwal->sum('nominal');
        $totalModal = $totalNominal - $modal;

        $totalKiri = $neracaAwal->sum('nominal');
        $totalKanan = $totalModal + $modal;

        return view('admin.display.neracaAwal', [
            'neracaAwal' => $neracaAwal,
            'account' => $account,
            'totalModal' => $totalModal,
            'totalNominal' => $totalNominal,
            'aktiva' => $aktiva,
            'liabilitasEkuitas' => $liabilitasEkuitas,
            'totalKiri' => $totalKiri,
            'totalKanan' => $totalKanan,
        ]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'id_akun.unique' => 'Akun telah digunakan',
        ];

        $validated = $request->validate([
            'id_akun' => 'required|unique:neraca_awal,id_akun',
            'nominal' => 'required|numeric|min:1',
        ], $message);

        $validated['modal'] = $validated['nominal'];

        BalanceSheet::create($validated);

        return redirect()->route('admin.balances.index')->with('success', 'Data added successfully');
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
            'id_akun.unique' => 'Akun telah digunakan',
        ];

        $dataEdit = BalanceSheet::findOrFail($id);

        $validated = $request->validate([
            'id_akun' => 'required|unique:neraca_awal,id_akun,' . $dataEdit->id,
            'nominal' => 'required|numeric|min:1',
        ], $message);

        $validated['modal'] = $validated['nominal'];

        $dataEdit->update($validated);

        return redirect()->route('admin.balances.index')->with('success', 'Data updated successfully');
    }

    public function destroy($id)
    {
        $dataDelete = BalanceSheet::findOrFail($id);
        $dataDelete->delete();

        return redirect()->route('admin.balances.index')->with('success', 'Data deleted successfully');
    }
}

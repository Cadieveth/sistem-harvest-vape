<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\AccountCategory;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::with('category');

        $nama_akun = $request->get('nama_akun') ?? '';
        $category_id = $request->get('category_id') ?? '';

        if ($nama_akun) {
            $query->where('nama_akun', 'LIKE', '%' . $nama_akun . '%');
        }

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.accounts.index');
        }

        $category = AccountCategory::all();

        $accounts = $query->orderBy('kode_akun')->paginate(7);

        return view('admin.display.account', [
            'accounts' => $accounts,
            'category' => $category,
        ]);
    }

    public function create()
    {
        $category = AccountCategory::all();

        return view('admin.form.addAccount', ['category' => $category]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'kode_akun.unique' => ':attribute telah digunakan',
        ];

        $validated = $request->validate([
            'kode_akun' => 'required|unique:account,kode_akun',
            'nama_akun' => 'required',
            'category_id' => 'required',
        ], $message);

        Account::create($validated);

        return redirect()->route('admin.accounts.index')->with('success', 'Account added successfully');
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $category = AccountCategory::all();
        return view('admin.edit.editAccount', [
            'account' => $account,
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
        ];

        $validated = $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'category_id' => 'required',
        ], $message);

        $account = Account::findOrFail($id);
        $account->update($validated);

        return redirect()->route('admin.accounts.index')->with('success', 'Account updated successfully');
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Account deleted successfully');
    }
}

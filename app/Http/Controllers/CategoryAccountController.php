<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AccountCategory;

class CategoryAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountCategory::query();

        $categories = $query->paginate(7);

        return view('admin.display.accountcategory', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
        ];

        $validated = $request->validate([
            'category' => 'required',
        ], $message);

        AccountCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category added successfully');
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
        ];

        $validated = $request->validate([
            'category' => 'required',
        ], $message);

        $category = AccountCategory::findOrFail($id);
        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = AccountCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Account deleted successfully');
    }
}

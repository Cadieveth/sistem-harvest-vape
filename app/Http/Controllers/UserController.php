<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        $name = $request->get('name') ?? '';
        $username = $request->get('username') ?? '';
        $created = $request->get('created_at') ?? '';
        $verified = $request->get('updated_at') ?? '';
        $role = $request->get('role') ?? '';

        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($request->has('reset_filter')) {
            return redirect()->route('admin.users.index');
        }

        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(7);

        return view('admin.display.user', ['users' => $users]);
    }

    // public function edit($id)
    // {
    //     $user = User::findOrFail($id);
    //     return view('admin.edit.editUser', ['user' => $user]);
    // }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = \DB::table('role')->get();
        return view('admin.edit.editUser', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah digunakan',
        ];

        $validated = $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'role' => 'required',
        ], $message);

        $user = User::findOrFail($id);
        $validated['name'] = $request->input('name', $user->name);
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}

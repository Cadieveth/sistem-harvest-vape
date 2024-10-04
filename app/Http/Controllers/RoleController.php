<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::query();

        $role = $request->get('role') ?? '';

        if ($role) {
            $query->where('role', 'LIKE', '%' . $role . '%');
        }

        $roles = $query->get();

        return view('admin.display.role', ['roles' => $roles]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $role = (string) $request->get('role', '');
        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role !== '', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'role'));
    }

    public function edit($locale, $user)
    {
        $user = User::findOrFail($user);
        return view('admin.users.edit', compact('user'));
    }

    public function update($locale, $user, Request $request)
    {
        $user = User::findOrFail($user);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:student,instructor,admin'],
            'is_blocked' => ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->is_blocked = (bool) ($data['is_blocked'] ?? false);
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }
        $user->save();

        return back()->with('success', 'User updated');
    }
}



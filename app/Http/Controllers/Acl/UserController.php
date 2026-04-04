<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\UserStoreRequest;
use App\Http\Requests\Acl\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $search = request('name');

        $users = User::query()
            ->with('roles:id,name')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name,
            ]);

        return Inertia::render('ACL/Users/Index', [
            'users' => $users,
            'filters' => ['name' => $search],
        ]);
    }

    public function create()
    {
        return Inertia::render('ACL/Users/Create', [
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => $request->string('password'),
            ]);

            if ($request->filled('role_id')) {
                $role = Role::findOrFail($request->integer('role_id'));
                $user->syncRoles([$role->name]);
            }
        });

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $user->load('roles:id,name');

        return Inertia::render('ACL/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->roles->first()?->id,
            ],
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $payload = [
                'name' => $request->string('name'),
                'email' => $request->string('email'),
            ];

            if ($request->filled('password')) {
                $payload['password'] = $request->string('password');
            }

            $user->update($payload);

            if ($request->filled('role_id')) {
                $role = Role::findOrFail($request->integer('role_id'));
                $user->syncRoles([$role->name]);
            } else {
                $user->syncRoles([]);
            }
        });

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}

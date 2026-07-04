<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * CRUD User — khusus ketua (PRD F-12).
     */
    public function index(): View
    {
        $users = User::withCount('articles')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', ['user' => new User(['role' => 'admin'])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'role' => $request->string('role'),
            'password' => Hash::make($request->string('password')),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->fill([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'role' => $request->string('role'),
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->string('password'));
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna dihapus.');
    }
}

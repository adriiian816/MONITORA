<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar akun OPD.
     */
    public function index()
    {
        $opdUsers = User::where('role', 'opd')->latest()->get();
        return view('admin.users.index', compact('opdUsers'));
    }

    /**
     * Menampilkan form tambah akun OPD.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan data akun OPD baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'opd', // Pastikan rolenya diset sebagai opd
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun OPD berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit akun OPD.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data akun OPD.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun OPD berhasil diperbarui!');
    }

    /**
     * Menghapus akun OPD.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun OPD berhasil dihapus!');
    }

    /**
     * Mengubah password akun OPD secara langsung oleh Admin.
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password OPD ' . $user->name . ' berhasil diubah!');
    }

    /**
     * Menampilkan riwayat masuk (login) dan keluar (logout) akun OPD.
     */
    public function loginHistories()
    {
        $histories = LoginHistory::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'opd');
            })
            ->latest('logged_at')
            ->paginate(15);

        return view('admin.login-histories', compact('histories'));
    }
}
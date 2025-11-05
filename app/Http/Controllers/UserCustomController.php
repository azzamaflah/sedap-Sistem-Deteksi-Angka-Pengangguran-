<?php

namespace App\Http\Controllers;

use App\Models\User;  // ✅ GANTI dari UserCustom ke User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserCustomController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(20);
        return view('pengguna.pengguna', compact('data'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,user',  // ✅ TAMBAHKAN
        ]);

        // Jika email kosong, buat email dummy
        if (empty($validated['email'])) {
            $validated['email'] = $validated['username'] . '@bantulwe.com';
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pengguna = User::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $id)
    {
        $pengguna = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $pengguna->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $pengguna->id,
            'password' => 'nullable|string|min:4',
            'role' => 'required|in:admin,user',  // ✅ TAMBAHKAN
        ]);

        // Jika email kosong, buat email dummy
        if (empty($validated['email'])) {
            $validated['email'] = $validated['username'] . '@bantulwe.com';
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}

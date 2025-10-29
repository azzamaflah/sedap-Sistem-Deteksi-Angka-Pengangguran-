<?php

namespace App\Http\Controllers;

use App\Models\UserCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserCustomController extends Controller
{
    public function index(Request $request)
    {
        $query = UserCustom::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id_user', 'like', "%{$search}%");
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
            'id_user' => 'required|string|max:50|unique:user,id_user',
            'nama' => 'required|string|max:50',
            'user_name' => 'required|string|max:50',
            'password' => 'required|string|min:4',
            'email' => 'nullable|email|max:50',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        UserCustom::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($no)
    {
        $pengguna = UserCustom::where('no', $no)->firstOrFail();
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $no)
    {
        $pengguna = UserCustom::where('no', $no)->firstOrFail();

        $validated = $request->validate([
            'id_user' => 'required|string|max:50|unique:user,id_user,' . $pengguna->no . ',no',
            'nama' => 'required|string|max:50',
            'user_name' => 'required|string|max:50',
            'password' => 'nullable|string|min:4',
            'email' => 'nullable|email|max:50',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diupdate');
    }

    public function destroy($no)
    {
        $pengguna = UserCustom::where('no', $no)->firstOrFail();
        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}

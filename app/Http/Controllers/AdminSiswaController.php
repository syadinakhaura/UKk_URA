<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminSiswaController extends Controller
{
    public function index()
    {
        $siswas = User::where('role', 'siswa')->orderBy('name')->get();

        return view('admin.siswa.index', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:255', 'unique:users,nisn'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'nisn' => $validated['nisn'],
            'email' => $validated['email'],
            'role' => 'siswa',
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Akun siswa berhasil ditambahkan.');
    }
}

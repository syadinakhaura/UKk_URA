<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $aspirasis = Aspirasi::where('user_id', Auth::id())
            ->with(['kategori', 'umpanBalik'])
            ->latest()
            ->get();

        return view('siswa.dashboard', compact('kategoris', 'aspirasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('aspirasi', 'public');
        }

        $aspirasi = Aspirasi::create([
            'user_id' => Auth::id(),
            'kategori_id' => $validated['kategori_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'foto_path' => $fotoPath,
            'status' => 'Menunggu',
        ]);

        $aspirasi->logs()->create([
            'actor_user_id' => Auth::id(),
            'actor_role' => Auth::user()->role,
            'action' => 'pengajuan_dibuat',
            'description' => 'Pengajuan aspirasi dibuat oleh siswa.',
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Aspirasi berhasil dikirim!');
    }

    public function show(Aspirasi $aspirasi)
    {
        if ($aspirasi->user_id !== Auth::id()) {
            abort(403);
        }

        $aspirasi->load(['kategori', 'umpanBalik.admin', 'validatedByYayasan', 'logs.actor']);

        return view('siswa.detail', compact('aspirasi'));
    }
}

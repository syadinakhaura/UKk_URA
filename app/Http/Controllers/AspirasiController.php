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
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Aspirasi::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Aspirasi berhasil dikirim!');
    }

    public function show(Aspirasi $aspirasi)
    {
        if ($aspirasi->user_id !== Auth::id()) {
            abort(403);
        }

        $aspirasi->load(['kategori', 'umpanBalik.admin', 'validatedByYayasan']);
        return view('siswa.detail', compact('aspirasi'));
    }
}

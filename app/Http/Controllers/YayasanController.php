<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YayasanController extends Controller
{
    public function index()
    {
        $stats = [
            'diajukan' => Aspirasi::where('status', 'Diajukan')->count(),
            'disetujui' => Aspirasi::where('status', 'Disetujui')->count(),
            'ditolak' => Aspirasi::where('status', 'Ditolak')->count(),
            'selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        $latest_diajukan = Aspirasi::with(['user', 'kategori', 'submittedByAdmin'])
            ->where('status', 'Diajukan')
            ->latest()
            ->take(5)
            ->get();

        return view('yayasan.dashboard', compact('stats', 'latest_diajukan'));
    }

    public function aspirasiIndex(Request $request)
    {
        $aspirasis = Aspirasi::with(['user', 'kategori', 'submittedByAdmin', 'validatedByYayasan'])
            ->where('status', '!=', 'Menunggu')
            ->filter($request->only(['status', 'kategori', 'tanggal', 'tanggal_awal', 'tanggal_akhir']))
            ->latest()
            ->get();

        $kategoris = Kategori::all();

        return view('yayasan.aspirasi.index', compact('aspirasis', 'kategoris'));
    }

    public function show(Aspirasi $aspirasi)
    {
        if ($aspirasi->status === 'Menunggu') {
            abort(404);
        }

        $aspirasi->load(['user', 'kategori', 'umpanBalik.admin', 'submittedByAdmin', 'validatedByYayasan', 'logs.actor']);

        return view('yayasan.aspirasi.show', compact('aspirasi'));
    }

    public function validateAspirasi(Request $request, Aspirasi $aspirasi)
    {
        if ($aspirasi->status !== 'Diajukan') {
            return back()->withErrors([
                'status' => 'Aspirasi ini sudah divalidasi atau belum diajukan.',
            ]);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:Disetujui,Ditolak'],
            'catatan_yayasan' => ['nullable', 'string'],
        ]);

        $aspirasi->update([
            'status' => $validated['status'],
            'validated_by_yayasan_id' => Auth::id(),
            'validated_at' => now(),
            'catatan_yayasan' => $validated['catatan_yayasan'] ?? null,
        ]);

        $aspirasi->logs()->create([
            'actor_user_id' => Auth::id(),
            'actor_role' => Auth::user()->role,
            'action' => $validated['status'] === 'Disetujui' ? 'disetujui_yayasan' : 'ditolak_yayasan',
            'description' => $validated['status'] === 'Disetujui'
                ? 'Aspirasi disetujui oleh yayasan.'
                : 'Aspirasi ditolak oleh yayasan.',
            'meta' => [
                'catatan_yayasan' => $validated['catatan_yayasan'] ?? null,
            ],
        ]);

        return back()->with('success', 'Validasi aspirasi berhasil disimpan.');
    }

    public function laporan(Request $request)
    {
        $aspirasis = Aspirasi::with(['user', 'kategori', 'submittedByAdmin', 'validatedByYayasan'])
            ->where('status', '!=', 'Menunggu')
            ->filter($request->only(['status', 'kategori', 'tanggal', 'tanggal_awal', 'tanggal_akhir']))
            ->latest()
            ->get();

        $kategoris = Kategori::all();

        return view('yayasan.laporan.index', compact('aspirasis', 'kategoris'));
    }

    public function cetakLaporan(Request $request)
    {
        $aspirasis = Aspirasi::with(['user', 'kategori', 'submittedByAdmin', 'validatedByYayasan'])
            ->where('status', '!=', 'Menunggu')
            ->filter($request->only(['status', 'kategori', 'tanggal', 'tanggal_awal', 'tanggal_akhir']))
            ->latest()
            ->get();

        return view('yayasan.laporan.cetak', compact('aspirasis'));
    }
}

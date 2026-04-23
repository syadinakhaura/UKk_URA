<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\UmpanBalik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', 'Menunggu')->count(),
            'diajukan' => Aspirasi::where('status', 'Diajukan')->count(),
            'disetujui' => Aspirasi::where('status', 'Disetujui')->count(),
            'ditolak' => Aspirasi::where('status', 'Ditolak')->count(),
            'selesai' => Aspirasi::where('status', 'Selesai')->count(),
        ];

        $latest_aspirasi = Aspirasi::with(['user', 'kategori'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latest_aspirasi'));
    }

    public function aspirasiList(Request $request)
    {
        $aspirasis = Aspirasi::with(['user', 'kategori'])
            ->filter($request->only(['status', 'kategori', 'tanggal', 'tanggal_awal', 'tanggal_akhir']))
            ->latest()
            ->get();

        $kategoris = Kategori::all();

        return view('admin.aspirasi.index', compact('aspirasis', 'kategoris'));
    }

    public function show(Aspirasi $aspirasi)
    {
        $aspirasi->load(['user', 'kategori', 'umpanBalik.admin', 'submittedByAdmin', 'validatedByYayasan', 'logs.actor']);

        return view('admin.aspirasi.show', compact('aspirasi'));
    }

    public function storeFeedback(Request $request, Aspirasi $aspirasi)
    {
        if (! in_array($aspirasi->status, ['Disetujui', 'Selesai'], true)) {
            return back()->withErrors([
                'isi_umpan_balik' => 'Tanggapan dan progres hanya bisa diisi setelah aspirasi disetujui yayasan.',
            ]);
        }

        $validated = $request->validate([
            'isi_umpan_balik' => ['required', 'string'],
            'progres' => ['nullable', 'string', 'max:255'],
        ]);

        $hasFeedback = $aspirasi->umpanBalik()->exists();

        UmpanBalik::updateOrCreate(
            ['aspirasi_id' => $aspirasi->id],
            [
                'admin_id' => Auth::id(),
                'isi_umpan_balik' => $validated['isi_umpan_balik'],
                'progres' => $validated['progres'] ?? null,
            ]
        );

        $aspirasi->logs()->create([
            'actor_user_id' => Auth::id(),
            'actor_role' => Auth::user()->role,
            'action' => $hasFeedback ? 'umpan_balik_diupdate' : 'umpan_balik_dibuat',
            'description' => $hasFeedback ? 'Tanggapan admin diperbarui.' : 'Tanggapan admin dibuat.',
            'meta' => [
                'progres' => $validated['progres'] ?? null,
            ],
        ]);

        return back()->with('success', 'Umpan balik berhasil disimpan!');
    }

    public function submitToYayasan(Request $request, Aspirasi $aspirasi)
    {
        if ($aspirasi->status !== 'Menunggu') {
            return back()->withErrors([
                'status' => 'Aspirasi ini tidak bisa diajukan ke yayasan.',
            ]);
        }

        $aspirasi->update([
            'status' => 'Diajukan',
            'submitted_by_admin_id' => Auth::id(),
            'submitted_to_yayasan_at' => now(),
        ]);

        $aspirasi->logs()->create([
            'actor_user_id' => Auth::id(),
            'actor_role' => Auth::user()->role,
            'action' => 'diajukan_ke_yayasan',
            'description' => 'Aspirasi diajukan ke yayasan oleh admin.',
        ]);

        return back()->with('success', 'Aspirasi berhasil diajukan ke yayasan.');
    }

    public function updateStatus(Request $request, Aspirasi $aspirasi)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Selesai'],
        ]);

        if ($aspirasi->status !== 'Disetujui') {
            return back()->withErrors([
                'status' => 'Status Selesai hanya bisa diset setelah aspirasi disetujui yayasan.',
            ]);
        }

        $aspirasi->update(['status' => $validated['status']]);

        $aspirasi->logs()->create([
            'actor_user_id' => Auth::id(),
            'actor_role' => Auth::user()->role,
            'action' => 'ditandai_selesai',
            'description' => 'Aspirasi ditandai selesai oleh admin.',
        ]);

        return back()->with('success', 'Status aspirasi berhasil diperbarui!');
    }
}

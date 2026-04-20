@extends('layouts.app')

@section('title', 'Yayasan Control Panel')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Yayasan</h1>
            <p class="text-slate-500 text-sm">Validasi pengaduan dan pantau laporan sarana sekolah.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('yayasan.admin.index') }}" class="bg-white text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm border border-slate-200 hover:bg-slate-50 transition-all flex items-center shadow-sm">
                <i class="fas fa-user-shield mr-2 text-purple-500"></i>
                Kelola Admin
            </a>
            <a href="{{ route('yayasan.laporan.index') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-100">
                <i class="fas fa-file-export mr-2"></i>
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card label="Diajukan" value="{{ $stats['diajukan'] }}" icon="fas fa-paper-plane" color="purple" />
        <x-stat-card label="Disetujui" value="{{ $stats['disetujui'] }}" icon="fas fa-check-circle" color="emerald" />
        <x-stat-card label="Ditolak" value="{{ $stats['ditolak'] }}" icon="fas fa-times-circle" color="rose" />
        <x-stat-card label="Selesai" value="{{ $stats['selesai'] }}" icon="fas fa-flag-checkered" color="blue" />
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800">Menunggu Validasi</h2>
            <a href="{{ route('yayasan.aspirasi.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Diajukan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latest_diajukan as $a)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 font-bold border border-purple-100 text-xs">
                                    {{ strtoupper(substr($a->user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-slate-800">{{ $a->user->name }}</div>
                                    <div class="text-xs text-slate-400">NISN: {{ $a->user->nisn }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-800">{{ Str::limit($a->judul, 40) }}</div>
                            <div class="text-xs text-slate-400">ID: #ASP-{{ $a->id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $a->kategori->nama_kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600">
                                {{ $a->submitted_to_yayasan_at ? $a->submitted_to_yayasan_at->format('d M Y') : '-' }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ $a->submitted_to_yayasan_at ? $a->submitted_to_yayasan_at->format('H:i') : '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('yayasan.aspirasi.show', $a->id) }}" class="inline-flex items-center bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-all">
                                <i class="fas fa-check-circle mr-2"></i>
                                Validasi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada aspirasi yang diajukan untuk divalidasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


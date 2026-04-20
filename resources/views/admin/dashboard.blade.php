@extends('layouts.app')

@section('title', 'Admin Overview')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Administrator</h1>
            <p class="text-slate-500 text-sm">Ringkasan data aspirasi dan pengaduan sarana sekolah hari ini.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.siswa.index') }}" class="bg-white text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm border border-slate-200 hover:bg-slate-50 transition-all flex items-center shadow-sm">
                <i class="fas fa-user-plus mr-2 text-indigo-500"></i>
                Tambah Siswa
            </a>
            <a href="{{ route('admin.aspirasi.index') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-100">
                <i class="fas fa-tasks mr-2"></i>
                Kelola Aspirasi
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <x-stat-card label="Total" value="{{ $stats['total'] }}" icon="fas fa-layer-group" color="indigo" />
        <x-stat-card label="Menunggu" value="{{ $stats['menunggu'] }}" icon="fas fa-clock" color="amber" />
        <x-stat-card label="Diajukan" value="{{ $stats['diajukan'] }}" icon="fas fa-paper-plane" color="purple" />
        <x-stat-card label="Disetujui" value="{{ $stats['disetujui'] }}" icon="fas fa-check-circle" color="emerald" />
        <x-stat-card label="Ditolak" value="{{ $stats['ditolak'] }}" icon="fas fa-times-circle" color="rose" />
        <x-stat-card label="Selesai" value="{{ $stats['selesai'] }}" icon="fas fa-flag-checkered" color="blue" />
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800">Aspirasi Terbaru</h2>
            <a href="{{ route('admin.aspirasi.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latest_aspirasi as $a)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200 text-xs">
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
                            <div class="text-xs text-slate-400">{{ $a->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $a->kategori->nama_kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusStyles = [
                                    'Menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'Diajukan' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'Selesai' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                ];
                                $style = $statusStyles[$a->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $style }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.aspirasi.show', $a->id) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                <i class="fas fa-external-link-alt text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada aspirasi masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Aspirasi Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">Rincian Pengaduan</h2>
            @php
                $statusStyles = [
                    'Menunggu' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'Diajukan' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                    'Selesai' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                ];
                $style = $statusStyles[$aspirasi->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
            @endphp
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black border uppercase tracking-widest {{ $style }}">
                {{ $aspirasi->status }}
            </span>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-100">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Judul Aspirasi</p>
                        <p class="font-black text-slate-800 text-xl">{{ $aspirasi->judul }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-sm border border-slate-200">
                            {{ $aspirasi->kategori->nama_kategori }}
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Pengiriman</p>
                        <p class="font-bold text-slate-800">{{ $aspirasi->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ID Pengaduan</p>
                        <p class="font-mono font-bold text-indigo-600">#ASP-{{ str_pad($aspirasi->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Isi Keluhan/Aspirasi</p>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-slate-700 leading-relaxed italic">
                    "{{ $aspirasi->deskripsi }}"
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Admin Response Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center">
                <i class="fas fa-comment-dots text-indigo-500 mr-2"></i>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tanggapan Admin</h2>
            </div>
            <div class="p-6">
                @if($aspirasi->umpanBalik)
                    <div class="space-y-4">
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 text-slate-700 text-sm leading-relaxed">
                            {{ $aspirasi->umpanBalik->isi_umpan_balik }}
                        </div>
                        @if($aspirasi->umpanBalik->progres)
                        <div class="flex items-center space-x-2 text-indigo-600">
                            <i class="fas fa-spinner fa-spin text-xs"></i>
                            <p class="text-xs font-black uppercase tracking-tight">Progres: {{ $aspirasi->umpanBalik->progres }}</p>
                        </div>
                        @endif
                        <p class="text-[10px] text-slate-400 italic">Ditanggapi oleh {{ $aspirasi->umpanBalik->admin->name }}</p>
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-slate-400 text-sm italic">Belum ada tanggapan dari admin.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Yayasan Log Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center">
                <i class="fas fa-shield-alt text-purple-500 mr-2"></i>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Status Yayasan</h2>
            </div>
            <div class="p-6">
                @if($aspirasi->validated_at || $aspirasi->submitted_to_yayasan_at)
                    <div class="space-y-4">
                        @if($aspirasi->submitted_to_yayasan_at)
                        <div class="flex items-start">
                            <div class="mt-1 h-2 w-2 rounded-full bg-purple-400 mr-3"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Telah diajukan ke Yayasan</p>
                                <p class="text-[10px] text-slate-400">{{ $aspirasi->submitted_to_yayasan_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif

                        @if($aspirasi->validated_at)
                        <div class="flex items-start">
                            <div class="mt-1 h-2 w-2 rounded-full {{ $aspirasi->status === 'Ditolak' ? 'bg-rose-400' : 'bg-emerald-400' }} mr-3"></div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Keputusan: {{ $aspirasi->status }}</p>
                                @if($aspirasi->catatan_yayasan)
                                    <p class="text-xs text-slate-500 mt-1 bg-slate-50 p-2 rounded border border-slate-100 italic">"{{ $aspirasi->catatan_yayasan }}"</p>
                                @endif
                                <p class="text-[10px] text-slate-400 mt-1">{{ $aspirasi->validated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-slate-400 text-sm italic">Menunggu antrean validasi Yayasan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

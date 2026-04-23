@extends('layouts.app')

@section('title', 'Validasi Aspirasi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('yayasan.aspirasi.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Aspirasi Detail -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Detail Aspirasi</h2>
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
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Identitas Siswa</p>
                                <p class="font-bold text-slate-800 text-lg">{{ $aspirasi->user->name }}</p>
                                <p class="text-xs text-slate-500">NISN: {{ $aspirasi->user->nisn }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori Sarana</p>
                                <p class="font-bold text-slate-800">{{ $aspirasi->kategori->nama_kategori }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Masuk</p>
                                <p class="font-bold text-slate-800">{{ $aspirasi->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Diajukan Oleh Admin</p>
                                <p class="font-bold text-slate-800">{{ $aspirasi->submittedByAdmin->name ?? '-' }}</p>
                                @if($aspirasi->submitted_to_yayasan_at)
                                    <p class="text-xs text-slate-500">{{ $aspirasi->submitted_to_yayasan_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-slate-800 leading-tight">{{ $aspirasi->judul }}</h3>
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-slate-700 leading-relaxed italic">
                            "{{ $aspirasi->deskripsi }}"
                        </div>
                    </div>

                    @if($aspirasi->foto_path)
                    <div class="mt-8 space-y-3">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Foto Lampiran</p>
                        <a href="{{ asset('storage/' . $aspirasi->foto_path) }}" target="_blank" class="block">
                            <img src="{{ asset('storage/' . $aspirasi->foto_path) }}" alt="Foto Aspirasi" class="w-full rounded-2xl border border-slate-200 shadow-sm">
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Response (Read Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tanggapan Awal Admin</h2>
                </div>
                <div class="p-8">
                    @if($aspirasi->umpanBalik)
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Analisis & Solusi Admin</p>
                                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 text-slate-700 text-sm leading-relaxed">
                                    {{ $aspirasi->umpanBalik->isi_umpan_balik }}
                                </div>
                            </div>
                            @if($aspirasi->umpanBalik->progres)
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Status Progres</p>
                                <p class="text-indigo-600 font-bold text-sm italic">"{{ $aspirasi->umpanBalik->progres }}"</p>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center py-4 text-slate-400 italic">
                            <i class="fas fa-comment-slash text-2xl mb-2"></i>
                            <p class="text-sm font-medium">Belum ada tanggapan teknis dari admin.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Log Aktivitas</h2>
                </div>
                <div class="p-8">
                    @php
                        $logs = $aspirasi->logs->sortBy('created_at');
                    @endphp

                    @if($logs->count())
                        <div class="relative pl-8 space-y-5 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                            @foreach($logs as $log)
                                <div class="relative">
                                    <div class="absolute -left-8 mt-1 h-6 w-6 rounded-full bg-slate-100 border-4 border-white shadow-sm flex items-center justify-center">
                                        <i class="fas fa-circle text-[6px] text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">{{ $log->description ?? $log->action }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                        @if($log->actor)
                                            · {{ $log->actor->name }} ({{ $log->actor_role }})
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center py-4 text-slate-400 italic">
                            <p class="text-sm font-medium">Belum ada log aktivitas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Validation Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-800">Panel Validasi</h2>
                </div>
                <div class="p-6">
                    @if($aspirasi->status === 'Diajukan')
                        <form action="{{ route('yayasan.aspirasi.validate', $aspirasi->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">Keputusan Yayasan</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-emerald-50 border-slate-100 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 group">
                                        <input type="radio" name="status" value="Disetujui" class="sr-only" required>
                                        <i class="fas fa-check-circle text-2xl mb-2 text-slate-300 group-has-[:checked]:text-emerald-500"></i>
                                        <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-emerald-700">SETUJUI</span>
                                    </label>
                                    <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-rose-50 border-slate-100 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 group">
                                        <input type="radio" name="status" value="Ditolak" class="sr-only" required>
                                        <i class="fas fa-times-circle text-2xl mb-2 text-slate-300 group-has-[:checked]:text-rose-500"></i>
                                        <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-rose-700">TOLAK</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Validasi (Opsional)</label>
                                <textarea name="catatan_yayasan" rows="4" 
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none" 
                                    placeholder="Tulis alasan penyetujuan atau penolakan...">{{ old('catatan_yayasan') }}</textarea>
                            </div>
                            <button type="submit" 
                                class="w-full bg-indigo-600 text-white font-bold py-3.5 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Validasi
                            </button>
                        </form>
                    @else
                        <div class="space-y-6">
                            <div class="p-4 rounded-xl border {{ $aspirasi->status === 'Ditolak' ? 'bg-rose-50 border-rose-100' : 'bg-emerald-50 border-emerald-100' }}">
                                <p class="text-[10px] font-black uppercase tracking-widest {{ $aspirasi->status === 'Ditolak' ? 'text-rose-500' : 'text-emerald-500' }} mb-1">Status Validasi</p>
                                <p class="text-xl font-black {{ $aspirasi->status === 'Ditolak' ? 'text-rose-700' : 'text-emerald-700' }}">{{ strtoupper($aspirasi->status) }}</p>
                            </div>
                            
                            @if($aspirasi->validated_at)
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Divalidasi Oleh</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $aspirasi->validatedByYayasan->name ?? 'Yayasan' }}</p>
                                    <p class="text-[10px] text-slate-500 uppercase tracking-tight">{{ $aspirasi->validated_at->format('d F Y, H:i') }} WIB</p>
                                </div>
                                
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan Validasi</p>
                                    @if($aspirasi->catatan_yayasan)
                                        <div class="mt-2 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm text-slate-700 italic">
                                            "{{ $aspirasi->catatan_yayasan }}"
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Tidak ada catatan khusus.</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

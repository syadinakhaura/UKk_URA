@extends('layouts.app')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('admin.aspirasi.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-bold text-sm transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
        <div class="flex items-center space-x-2">
            @if($aspirasi->status === 'Menunggu')
                <form action="{{ route('admin.aspirasi.submit', $aspirasi->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-purple-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-purple-700 shadow-lg shadow-purple-100 transition-all flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan ke Yayasan
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.aspirasi.status', $aspirasi->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="Selesai">
                <button type="submit" 
                    class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all flex items-center shadow-lg shadow-emerald-100 {{ $aspirasi->status !== 'Disetujui' || $aspirasi->status === 'Selesai' ? 'opacity-50 cursor-not-allowed' : '' }}" 
                    {{ $aspirasi->status !== 'Disetujui' || $aspirasi->status === 'Selesai' ? 'disabled' : '' }}>
                    <i class="fas fa-check-double mr-2"></i>
                    Tandai Selesai
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detail Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Informasi Aspirasi</h2>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pb-8 border-b border-slate-100">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pengirim</p>
                            <p class="font-bold text-slate-800">{{ $aspirasi->user->name }}</p>
                            <p class="text-xs text-slate-500">NISN: {{ $aspirasi->user->nisn }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori</p>
                            <p class="font-bold text-slate-800">{{ $aspirasi->kategori->nama_kategori }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Diterima</p>
                            <p class="font-bold text-slate-800">{{ $aspirasi->created_at->format('d F Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $aspirasi->created_at->format('H:i') }} WIB</p>
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

            @if($aspirasi->submitted_to_yayasan_at || $aspirasi->validated_at)
            <!-- Workflow Timeline Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Log Alur Validasi Yayasan</h2>
                </div>
                <div class="p-6">
                    <div class="relative pl-8 space-y-6 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                        @if($aspirasi->submitted_to_yayasan_at)
                        <div class="relative">
                            <div class="absolute -left-8 mt-1 h-6 w-6 rounded-full bg-purple-100 border-4 border-white shadow-sm flex items-center justify-center">
                                <i class="fas fa-paper-plane text-[10px] text-purple-600"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-800">Diajukan ke Yayasan</p>
                            <p class="text-xs text-slate-500">{{ $aspirasi->submitted_to_yayasan_at->format('d M Y, H:i') }} oleh {{ $aspirasi->submittedByAdmin->name ?? 'System' }}</p>
                        </div>
                        @endif

                        @if($aspirasi->validated_at)
                        <div class="relative">
                            <div class="absolute -left-8 mt-1 h-6 w-6 rounded-full {{ $aspirasi->status === 'Ditolak' ? 'bg-rose-100' : 'bg-emerald-100' }} border-4 border-white shadow-sm flex items-center justify-center">
                                <i class="fas {{ $aspirasi->status === 'Ditolak' ? 'fa-times' : 'fa-check' }} text-[10px] {{ $aspirasi->status === 'Ditolak' ? 'text-rose-600' : 'text-emerald-600' }}"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-800">Hasil Validasi: {{ $aspirasi->status }}</p>
                            <p class="text-xs text-slate-500">{{ $aspirasi->validated_at->format('d M Y, H:i') }} oleh {{ $aspirasi->validatedByYayasan->name ?? 'Yayasan' }}</p>
                            @if($aspirasi->catatan_yayasan)
                                <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                                    Catatan: {{ $aspirasi->catatan_yayasan }}
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Log Aktivitas</h2>
                </div>
                <div class="p-6">
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
                        <div class="text-center py-6">
                            <p class="text-slate-400 text-sm italic">Belum ada log aktivitas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Response Column -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-800">Tanggapan Admin</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.aspirasi.feedback', $aspirasi->id) }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan Tanggapan</label>
                            <textarea name="isi_umpan_balik" rows="5" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none" 
                                placeholder="Berikan penjelasan atau solusi..." required>{{ $aspirasi->umpanBalik->isi_umpan_balik ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status Progres</label>
                            <input type="text" name="progres" value="{{ $aspirasi->umpanBalik->progres ?? '' }}" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                placeholder="Contoh: Sedang diproses teknisi">
                        </div>
                        <button type="submit" 
                            class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            {{ $aspirasi->umpanBalik ? 'Update Tanggapan' : 'Simpan Tanggapan' }}
                        </button>
                    </form>

                    @if($aspirasi->umpanBalik)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex items-center text-xs text-slate-400 italic">
                            <i class="fas fa-history mr-2"></i>
                            Terakhir diupdate oleh {{ $aspirasi->umpanBalik->admin->name }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

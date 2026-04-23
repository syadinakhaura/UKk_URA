@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Aspirasi Sekolah</h1>
            <p class="text-sm text-slate-500 font-medium">Rekapitulasi seluruh data aspirasi dan status penanganannya.</p>
        </div>
        <a href="{{ route('yayasan.laporan.cetak', request()->query()) }}" target="_blank" 
            class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all flex items-center">
            <i class="fas fa-print mr-2"></i>
            Cetak Laporan
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('yayasan.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Sarana</label>
                <select name="kategori" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center">
                    <i class="fas fa-filter mr-2 text-xs"></i>
                    Terapkan
                </button>
                <a href="{{ route('yayasan.laporan.index') }}" class="bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all flex items-center justify-center">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aspirasis as $a)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="text-sm font-bold text-slate-800">{{ $a->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $a->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-bold text-slate-800">{{ $a->user->name }}</div>
                            <div class="text-[10px] text-slate-500 font-medium">NISN: {{ $a->user->nisn }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-bold text-slate-800">{{ $a->judul }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $a->kategori->nama_kategori }}</div>
                        </td>
                        <td class="px-6 py-5 text-center">
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest {{ $style }}">
                                {{ $a->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($a->validated_at)
                                <div class="text-xs font-bold text-slate-700">{{ $a->validated_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-400 italic">Oleh {{ $a->validatedByYayasan->name ?? 'Yayasan' }}</div>
                            @else
                                <span class="text-xs text-slate-300 italic">Belum divalidasi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 border border-slate-100">
                                    <i class="fas fa-file-invoice text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium italic">Data tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Validasi Aspirasi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Antrean Validasi</h1>
        <div class="text-sm text-slate-500 font-medium italic">
            Menampilkan data yang memerlukan tindakan Yayasan
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('yayasan.aspirasi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 text-center md:text-left">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 text-center md:text-left">Kategori</label>
                <select name="kategori" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all appearance-none bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 text-center md:text-left">Tanggal Diajukan</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
                <a href="{{ route('yayasan.aspirasi.index') }}" class="bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all flex items-center justify-center">
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
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Diajukan Pada</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aspirasis as $a)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100 shadow-sm">
                                    {{ strtoupper(substr($a->user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-slate-800">{{ $a->user->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">NISN: {{ $a->user->nisn }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-bold text-slate-800">{{ $a->judul }}</div>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-500">
                                {{ $a->kategori->nama_kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-medium text-slate-600">
                                {{ $a->submitted_to_yayasan_at ? $a->submitted_to_yayasan_at->format('d M Y') : '-' }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ $a->submitted_to_yayasan_at ? $a->submitted_to_yayasan_at->format('H:i') . ' WIB' : '' }}
                            </div>
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
                            <a href="{{ route('yayasan.aspirasi.show', $a->id) }}" 
                                class="inline-flex items-center bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all group">
                                <i class="fas {{ $a->status === 'Diajukan' ? 'fa-check-circle' : 'fa-eye' }} mr-2"></i>
                                {{ $a->status === 'Diajukan' ? 'Validasi' : 'Detail' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 border border-slate-100">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium italic">Data aspirasi tidak ditemukan.</p>
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


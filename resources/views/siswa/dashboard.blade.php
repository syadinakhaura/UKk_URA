@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-8">
    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-8 text-white shadow-lg shadow-indigo-100">
        <h1 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-indigo-100 max-w-2xl">Punya keluhan atau aspirasi mengenai sarana sekolah? Kirimkan aspirasi Anda di bawah ini dan pantau perkembangannya secara real-time.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Aspirasi -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-paper-plane mr-2 text-indigo-500"></i>
                        Kirim Aspirasi Baru
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('siswa.aspirasi.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Aspirasi</label>
                            <input type="text" name="judul" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none" 
                                placeholder="Misal: AC Kelas XI RPL 2 Mati" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                            <select name="kategori_id" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none appearance-none bg-white" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Detail</label>
                            <textarea name="deskripsi" rows="4" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none" 
                                placeholder="Jelaskan secara detail keluhan Anda..." required></textarea>
                        </div>
                        <button type="submit" 
                            class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Kirim Aspirasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Histori Aspirasi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">
                        <i class="fas fa-history mr-2 text-indigo-500"></i>
                        Histori Aspirasi
                    </h2>
                    <span class="text-xs font-medium text-slate-500 bg-slate-200 px-2 py-1 rounded-full">{{ count($aspirasis) }} Total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aspirasi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($aspirasis as $a)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800">{{ $a->judul }}</div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $a->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $a->kategori->nama_kategori }}
                                    </span>
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $style }}">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('siswa.aspirasi.show', $a->id) }}" 
                                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-bold text-sm transition-colors">
                                        Detail
                                        <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-folder-open text-slate-400 text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada aspirasi yang dikirim.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

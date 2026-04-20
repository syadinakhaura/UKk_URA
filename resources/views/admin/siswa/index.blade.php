@extends('layouts.app')

@section('title', 'Manajemen Akun Siswa')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Database Siswa</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola akses akun siswa untuk sistem aspirasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-indigo-500"></i>
                        Registrasi Siswa
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                placeholder="Masukkan nama lengkap..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">NISN</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                placeholder="Masukkan NISN siswa..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Email Sekolah</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                placeholder="email@sekolah.sch.id" required>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Password</label>
                                <input type="password" name="password" 
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                    placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" 
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" 
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" 
                            class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Siswa Terdaftar</h2>
                    <span class="text-xs font-bold text-slate-400 bg-slate-200 px-2 py-1 rounded-lg">{{ $siswas->count() }} Akun</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Identitas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NISN</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($siswas as $s)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-black border border-slate-200">
                                            {{ strtoupper(substr($s->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-slate-800">{{ $s->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Role: Siswa</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                        {{ $s->nisn }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 font-medium">{{ $s->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-xs text-slate-500 font-medium">
                                        {{ $s->created_at->format('d M Y') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 border border-slate-100">
                                            <i class="fas fa-user-slash text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium italic">Belum ada akun siswa terdaftar.</p>
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


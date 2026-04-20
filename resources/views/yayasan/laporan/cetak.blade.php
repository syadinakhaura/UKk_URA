<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Aspirasi - AURA UKK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
            table { border-collapse: collapse !important; }
        }
    </style>
</head>
<body class="bg-white font-sans text-slate-900">
    <div class="max-w-6xl mx-auto p-10">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-4 border-slate-900 pb-8 mb-10">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-slate-900 rounded-2xl text-white">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tighter text-slate-900">AURA-UKK</h1>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Sistem Aspirasi & Pengaduan Sarana Sekolah</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black text-slate-800 uppercase">Laporan Rekapitulasi</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- Controls -->
        <div class="no-print mb-8 flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div class="flex items-center text-slate-500 text-sm italic">
                <i class="fas fa-info-circle mr-2"></i>
                Gunakan pengaturan browser untuk menyimpan sebagai PDF.
            </div>
            <div class="flex gap-3">
                <a href="{{ url()->previous() }}" class="px-6 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-100 transition-all">
                    Kembali
                </a>
                <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Sekarang
                </button>
            </div>
        </div>

        <!-- Content Table -->
        <div class="overflow-hidden border border-slate-200 rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest">No</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest">Tanggal</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest">Identitas Siswa</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest">Detail Aspirasi</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest">Kategori</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-center">Status</th>
                        <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-right">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($aspirasis as $index => $a)
                        <tr class="text-sm">
                            <td class="py-4 px-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-800">{{ $a->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-800 leading-tight">{{ $a->user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold tracking-tight">NISN: {{ $a->user->nisn }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-800 mb-1">{{ $a->judul }}</div>
                                <div class="text-[10px] text-slate-500 italic leading-tight max-w-[200px]">"{{ Str::limit($a->deskripsi, 60) }}"</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs font-bold text-slate-600 uppercase">{{ $a->kategori->nama_kategori }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-2 py-1 rounded text-[10px] font-black uppercase border border-slate-200">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($a->validated_at)
                                    <div class="font-bold text-slate-800 text-xs">{{ $a->validated_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-400 italic">By {{ $a->validatedByYayasan->name ?? 'Yayasan' }}</div>
                                @else
                                    <span class="text-xs text-slate-300 italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <p class="text-slate-400 font-bold italic uppercase tracking-widest">Data tidak ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer for Print -->
        <div class="mt-16 grid grid-cols-2 gap-20">
            <div class="text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-20">Dicetak Oleh,</p>
                <div class="w-48 mx-auto border-b-2 border-slate-900"></div>
                <p class="text-sm font-black text-slate-900 mt-2 uppercase">{{ Auth::user()->name }}</p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Staff Yayasan</p>
            </div>
            <div class="text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-20">Mengetahui, Ketua Yayasan</p>
                <div class="w-48 mx-auto border-b-2 border-slate-900"></div>
                <p class="text-sm font-black text-slate-900 mt-2 uppercase">_______________________</p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">NIP. .......................</p>
            </div>
        </div>

        <div class="mt-20 text-center border-t border-slate-100 pt-8">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Dokumen ini dihasilkan secara otomatis oleh Sistem AURA-UKK &copy; 2026</p>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>


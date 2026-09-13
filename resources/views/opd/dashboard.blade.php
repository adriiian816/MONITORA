<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard OPD - MONITORA') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Banner Selamat Datang -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 skew-x-12 transform origin-bottom-right"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}</h3>
                    <p class="text-slate-300 text-sm max-w-2xl">
                        Gunakan sistem MONITORA untuk memantau batas waktu pengumpulan dokumen/tugas serta mengunggah berkas penyerahan tepat waktu.
                    </p>
                    
                    <div class="mt-6">
                        <a href="{{ route('opd.submissions.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm inline-block">
                            Lihat & Submit Tugas Saya
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3 KOTAK STATISTIK MENYAMPING (Inline-Flex / Grid Fix) -->
            <div style="display: flex; gap: 1.25rem; width: 100%; flex-wrap: nowrap;">
                
                <!-- 1. TOTAL TUGAS -->
                <a href="{{ route('opd.submissions.index') }}" 
                   style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;"
                   class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Total Tugas</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all shrink-0">
                            <i class="fa-solid fa-list-check text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                            {{ $totalTugas ?? 0 }}
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-1">Penugasan BAPPEDA</p>
                    </div>
                </a>

                <!-- 2. SUDAH DIKUMPULKAN -->
                <a href="{{ route('opd.submissions.index', ['status' => 'submitted']) }}" 
                   style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;"
                   class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Dikumpulkan</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all shrink-0">
                            <i class="fa-solid fa-circle-check text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-emerald-600">
                            {{ $totalDikumpulkan ?? 0 }}
                        </h3>
                        <p class="text-[11px] text-emerald-600 font-medium mt-1">Laporan Selesai</p>
                    </div>
                </a>

                <!-- 3. BELUM DIKUMPULKAN -->
                <a href="{{ route('opd.submissions.index', ['status' => 'pending']) }}" 
                   style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;"
                   class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Belum Kirim</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-amber-600">
                            {{ $totalBelumDikumpulkan ?? 0 }}
                        </h3>
                        <p class="text-[11px] text-amber-600 font-medium mt-1">Menunggu Penyerahan</p>
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
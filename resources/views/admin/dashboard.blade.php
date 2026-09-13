<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Dashboard Admin BAPPEDA
            </h2>
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full font-semibold">
                Overview System
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Banner Selamat Datang -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 skew-x-12 transform origin-bottom-right"></div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-1">Selamat Datang Kembali, Admin BAPPEDA 👋</h3>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Pantau pengumpulan dokumen, kelola akun OPD, dan pantau riwayat notifikasi pengingat WhatsApp secara real-time dari panel kontrol ini.
                </p>
            </div>
        </div>

        <!-- 4 Stat Cards Grid -->
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <!-- 1. TOTAL TUGAS -->
    <a href="{{ route('admin.tasks.index') }}" 
       style="display: block;"
       class="w-full bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Tugas</span>
                <h3 class="text-3xl font-bold text-slate-800 mt-1 group-hover:text-indigo-600 transition-colors">
                    {{ $totalTugas ?? 0 }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all shrink-0">
                <i class="fa-solid fa-list-check text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
            <span>Tugas aktif dalam sistem</span>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
    </a>

    <!-- 2. TOTAL OPD -->
    <a href="{{ route('admin.users.index') }}" 
       style="display: block;"
       class="w-full bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total OPD</span>
                <h3 class="text-3xl font-bold text-slate-800 mt-1 group-hover:text-indigo-600 transition-colors">
                    {{ $totalOpd ?? 0 }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shrink-0">
                <i class="fa-solid fa-building-user text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
            <span>Akun OPD terdaftar</span>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
    </a>

    <!-- 3. DIKUMPULKAN -->
    <a href="{{ route('admin.tasks.index', ['status' => 'submitted']) }}" 
       style="display: block;"
       class="w-full bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Dikumpulkan</span>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">
                    {{ $totalDikumpulkan ?? 0 }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all shrink-0">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-emerald-600 font-medium flex items-center justify-between">
            <span>Laporan Selesai</span>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
    </a>

    <!-- 4. BELUM DIKUMPULKAN -->
    <a href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" 
       style="display: block;"
       class="w-full bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Belum Dikumpulkan</span>
                <h3 class="text-3xl font-bold text-amber-600 mt-1">
                    {{ $totalBelumDikumpulkan ?? 0 }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-amber-600 font-medium flex items-center justify-between">
            <span>Menunggu Penyerahan</span>
            <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
    </a>
</div>

        <!-- Tabel Riwayat Notifikasi Pengingat WhatsApp -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Riwayat Notifikasi Pengingat WhatsApp</h3>
                        <p class="text-xs text-slate-500">Daftar pesan pengingat otomatis yang dikirimkan ke kontak WhatsApp OPD</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] uppercase tracking-wider font-semibold text-slate-600">
                            <th class="py-3.5 px-5">Waktu Kirim</th>
                            <th class="py-3.5 px-5">Nama OPD</th>
                            <th class="py-3.5 px-5">Tugas</th>
                            <th class="py-3.5 px-5">Jenis Remind</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($logs ?? [] as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-5 whitespace-nowrap text-slate-500">
                                    <i class="fa-regular fa-calendar-alt mr-1.5 text-slate-400"></i>
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-slate-800">
                                    {{ $log->user->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5">
                                    {{ $log->task->title ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-medium border border-slate-200">
                                        {{ $log->type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    @if($log->status === 'success')
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] px-2.5 py-1 rounded-full font-medium">
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i> Terkirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] px-2.5 py-1 rounded-full font-medium">
                                            <i class="fa-solid fa-circle-xmark text-rose-500"></i> Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 bg-slate-50/30">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-folder-open text-3xl text-slate-300 mb-1"></i>
                                        <p class="font-medium text-slate-500">Belum ada riwayat notifikasi pengingat WhatsApp.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
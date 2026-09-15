<x-app-layout>
    <!-- Header bawaan dikosongkan agar tidak bentrok dengan layout Breeze -->
    <x-slot name="header">
        <div class="pt-4"></div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alert Notifikasi dengan Auto-Hide -->
            @if (session('success') || session('status'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 3000)" 
                     x-show="show" 
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="p-4 text-sm text-emerald-800 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center justify-between shadow-sm"
                     role="alert">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span class="font-medium">{{ session('success') ?? session('status') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <!-- Page Title & Action Button Bar -->
                <div class="p-6 sm:px-8 pt-8 pb-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2.5">
                            <i class="fa-solid fa-clipboard-list text-indigo-600"></i> {{ __('Kelola Tugas') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Daftar tugas atau monitoring yang diberikan kepada instansi/OPD.</p>
                    </div>
                    <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4.5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                        <i class="fa-solid fa-plus"></i> Buat Tugas Baru
                    </a>
                </div>

                <!-- Card Header / Info Bar & Search -->
                <div class="px-6 sm:px-8 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div class="text-sm font-medium text-gray-600">
                        Total Tugas: <span class="font-bold text-gray-900 bg-gray-200/60 px-2.5 py-1 rounded-full">{{ isset($tasks) ? $tasks->count() : 0 }} Tugas</span>
                    </div>
                    <div class="w-full sm:w-72">
                        <input type="text" placeholder="Cari judul tugas..." class="w-full text-sm border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5 text-left">Judul Tugas</th>
                                <th class="px-6 py-3.5 text-left">Deadline</th>
                                <th class="px-6 py-3.5 text-left">Tanggal Dibuat</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse ($tasks as $index => $task)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shadow-inner">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                                <div class="text-xs text-gray-400">Monitoring BAPPEDA</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg text-xs font-medium">
                                            <i class="fa-regular fa-clock text-amber-600"></i> {{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                        {{ $task->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Detail -->
                                            <a href="{{ route('admin.tasks.show', $task) }}" title="Detail Tugas" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.tasks.edit', $task) }}" title="Edit Tugas" class="p-2 bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Tugas" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                            <p class="text-sm font-medium">Belum ada tugas yang dibuat.</p>
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
</x-app-layout>
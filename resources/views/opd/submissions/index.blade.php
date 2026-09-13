<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Tugas OPD Saya') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openHistory: false, currentHistories: [], openPreview: false, pdfUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Berkas & Riwayat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $sub)
                            <tr>
                                <!-- Judul Tugas -->
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $sub->task->title }}
                                </td>

                                <!-- Deadline -->
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <div>{{ $sub->task->deadline->format('d M Y H:i') }}</div>
                                    @if($sub->is_late)
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded">Terlambat</span>
                                    @endif
                                </td>

                                <!-- Status Verifikasi (Approved, Revision, Pending) -->
                                <td class="px-6 py-4 text-sm font-semibold text-center whitespace-nowrap">
                                    @if($sub->status === 'approved')
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs">Disetujui (ACC)</span>
                                    @elseif($sub->status === 'revision')
                                        <div class="space-y-1">
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Perlu Revisi</span>
                                            @if($sub->notes)
                                                <p class="text-xs text-yellow-700 italic max-w-xs mx-auto">"{{ $sub->notes }}"</p>
                                            @endif
                                        </div>
                                    @elseif($sub->file_path)
                                        <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Menunggu Verifikasi</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Belum Dikirim</span>
                                    @endif
                                </td>

                                <!-- Preview Berkas & Tombol Riwayat Versi -->
                                <td class="px-6 py-4 text-sm text-center whitespace-nowrap">
                                    @if($sub->file_path)
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" 
                                                    @click="openPreview = true; pdfUrl = '{{ route('submissions.preview', $sub->id) }}'"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-bold underline">
                                                Lihat Berkas
                                            </button>

                                            @if($sub->histories && $sub->histories->count() > 0)
                                                <button type="button" 
                                                        @click="openHistory = true; currentHistories = {{ json_encode($sub->histories) }}"
                                                        class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold">
                                                    📜 Log Versi ({{ $sub->histories->count() }})
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi Upload/Edit -->
                                <td class="px-6 py-4 text-sm text-center">
                                    <a href="{{ route('opd.submissions.edit', $sub->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg inline-block">
                                        {{ $sub->file_path ? 'Revisi / Edit Berkas' : 'Kirim Berkas' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada tugas yang diberikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL RIWAYAT VERSI -->
        <div x-show="openHistory" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center border-b pb-3">
                    <h4 class="font-bold text-gray-800">Riwayat Berkas Sebelum Revisi</h4>
                    <button type="button" @click="openHistory = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
                </div>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    <template x-for="(hist, index) in currentHistories" :key="hist.id">
                        <div class="p-3 bg-gray-50 rounded-lg border text-xs flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-700" x-text="hist.file_name || 'Berkas Versi Lama'"></p>
                                <p class="text-[10px] text-gray-400" x-text="new Date(hist.created_at).toLocaleString('id-ID')"></p>
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 bg-gray-200 px-2 py-0.5 rounded">Arsip</span>
                        </div>
                    </template>
                </div>
                <button type="button" @click="openHistory = false" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-xs">Tutup</button>
            </div>
        </div>

        <!-- MODAL PREVIEW DOKUMEN -->
        <div x-show="openPreview" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4">
            <div class="bg-white rounded-xl w-full max-w-4xl h-[85vh] overflow-hidden shadow-xl flex flex-col">
                <div class="p-4 bg-gray-100 border-b flex justify-between items-center">
                    <h4 class="font-bold text-gray-700">Preview Dokumen Laporan</h4>
                    <button type="button" @click="openPreview = false" class="text-gray-500 font-bold">&times; Tutup</button>
                </div>
                <div class="flex-1 bg-gray-200">
                    <iframe :src="pdfUrl" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
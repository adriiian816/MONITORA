<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengumpulan Tugas: ') }} {{ $submission->task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-bold text-gray-700">Petunjuk Tugas:</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $submission->task->description ?? 'Tidak ada deskripsi tambahan.' }}</p>
                    <p class="text-xs text-red-600 font-semibold mt-2">Deadline: {{ $submission->task->deadline->format('d M Y H:i') }} WIT</p>
                </div>

                <form action="{{ route('opd.submissions.update', $submission->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if(in_array($submission->task->submission_format, ['file', 'both']))
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Upload Berkas (PDF/Doc/Zip)</label>
                            <input type="file" name="file_path" class="w-full border-gray-300 rounded-lg shadow-sm">
                            @if($submission->file_path)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-600 underline">Lihat File</a></p>
                            @endif
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Catatan Pengumpulan / Keterangan</label>
                        <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Tambahkan catatan jika diperlukan (misal: penyerahan dokumen fisik telah diserahkan ke Meja Admin Bappeda)">{{ old('notes', $submission->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('opd.submissions.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">
                            Simpan & Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
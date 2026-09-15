<x-app-layout>
    <!-- Header bawaan dikosongkan/diperkecil agar tidak bentrok -->
    <x-slot name="header">
        <div class="pt-4"></div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alert Notifikasi -->
            @if (session('success'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 3000)" 
                     x-show="show" 
                     x-transition:leave="transition ease-in duration-300"
                     class="p-4 text-sm text-emerald-800 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center justify-between shadow-sm"
                     role="alert">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                <!-- Page Title & Action Button Bar (Dipindah ke dalam card agar posisinya pas & rapi) -->
                <div class="p-6 sm:px-8 pt-8 pb-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2.5">
                            <i class="fa-solid fa-building-user text-indigo-600"></i> {{ __('Kelola Akun OPD') }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Daftar instansi/OPD yang terdaftar di sistem monitoring BAPPEDA.</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4.5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                        <i class="fa-solid fa-plus"></i> Tambah Akun OPD
                    </a>
                </div>

                <!-- Card Header / Info Bar & Search -->
                <div class="px-6 sm:px-8 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div class="text-sm font-medium text-gray-600">
                        Total Instansi: <span class="font-bold text-gray-900 bg-gray-200/60 px-2.5 py-1 rounded-full">{{ isset($opdUsers) ? $opdUsers->count() : 0 }} OPD</span>
                    </div>
                    <div class="w-full sm:w-72">
                        <input type="text" placeholder="Cari nama OPD atau email..." class="w-full text-sm border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 text-center w-16">No</th>
                                <th class="px-6 py-3.5 text-left">Nama OPD / Instansi</th>
                                <th class="px-6 py-3.5 text-left">Email</th>
                                <th class="px-6 py-3.5 text-left">Nomor WhatsApp</th>
                                <th class="px-6 py-3.5 text-left">Tanggal Dibuat</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse ($opdUsers as $index => $user)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shadow-inner">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-400">Instansi Pemerintah</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-regular fa-envelope text-gray-400 text-xs"></i>
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        @if($user->phone)
                                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg text-xs font-medium">
                                                <i class="fa-brands fa-whatsapp text-emerald-600"></i> {{ $user->phone }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-xs">
                                        {{ $user->created_at->format('d-m-Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.users.edit', $user) }}" title="Edit Akun" class="p-2 bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <!-- Tombol Reset Password -->
                                            <button type="button" 
                                                onclick="openPasswordModal('{{ $user->id }}', '{{ $user->name }}')"
                                                title="Reset Password" 
                                                class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                <i class="fa-solid fa-key text-xs"></i>
                                            </button>
                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun OPD ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Akun" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg transition-all shadow-sm">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                            <p class="text-sm font-medium">Belum ada akun OPD yang dibuat.</p>
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

    <!-- Modal Ubah Password OPD -->
    <div id="passwordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-slate-800">Ubah Password OPD</h3>
                <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form id="formUpdatePassword" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama OPD</label>
                    <input type="text" id="modalUserName" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-600">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Password Baru</label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required minlength="8" placeholder="Ulangi password baru"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closePasswordModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition-all">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript untuk Modal -->
    <script>
        function openPasswordModal(userId, userName) {
            const modal = document.getElementById('passwordModal');
            const form = document.getElementById('formUpdatePassword');
            const nameInput = document.getElementById('modalUserName');

            form.action = `/admin/users/${userId}/password`;
            nameInput.value = userName;

            modal.classList.remove('hidden');
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
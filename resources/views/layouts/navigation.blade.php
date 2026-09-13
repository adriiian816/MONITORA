<!-- Tambahkan menu ini di bagian navigasi/sidebar Admin -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('admin.login-histories')" :active="request()->routeIs('admin.login-histories')">
        {{ __('Riwayat Login OPD') }}
    </x-nav-link>
</div>
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\LoginHistory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Catat saat user LOGIN
        Event::listen(Login::class, function ($event) {
            LoginHistory::create([
                'user_id' => $event->user->id,
                'activity' => 'LOGIN',
                'ip_address' => request()->ip(),
                'logged_at' => now(),
            ]);
        });

        // Catat saat user LOGOUT
        Event::listen(Logout::class, function ($event) {
            if ($event->user) {
                LoginHistory::create([
                    'user_id' => $event->user->id,
                    'activity' => 'LOGOUT',
                    'ip_address' => request()->ip(),
                    'logged_at' => now(),
                ]);
            }
        });
    }
}
<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Only run if settings table exists
        if (Schema::hasTable('settings')) {
            $this->registerDefaultSettings();
        }
        $settings = Setting::first();
        View::share('setting', $settings);
    }

    protected function registerDefaultSettings()
    {
        $defaults = [
            'name' => 'Store',
            'email' => 'store@email.com',
            'phone' => '+96123456789',
            'address' => 'Beirut, lebanon',
            'facebook' => 'facebook.com',
            'tiktok' => 'tiktok.com',
            'instagram' => 'instagram.com',

        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate([
                'name' => 'Store',
                'email' => 'store@email.com',
                'phone' => '+96123456789',
                'address' => 'Beirut, lebanon',
                'facebook' => 'facebook.com',
                'tiktok' => 'tiktok.com',
                'instagram' => 'instagram.com',
            ]);
        }
    }
}

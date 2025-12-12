<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Api\ProfileApi;

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
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $userData = session('user_data', []);
            $view->with('currentUser', $userData);
            $profileData = [];
            $sellerPic = null;
            $token = session('auth_token');
            if ($token) {
                try {
                    $api = new ProfileApi();
                    $resp = $api->getProfile();
                    $profileData = $resp['data'] ?? [];
                    $sellerPic = $profileData['seller']['pic_photo_path'] ?? null;
                } catch (\Exception $e) {
                }
            }
            $view->with('currentProfile', $profileData);
            $view->with('currentSellerPic', $sellerPic);
        });
    }
}

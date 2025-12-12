<?php

namespace App\Http\Controllers;

use App\Api\ProfileApi;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $token = session('auth_token');
            if (!$token) {
                return redirect()->route('login.loginIndex');
            }

            $api = new ProfileApi();
            $response = $api->getProfile();
            $data = $response['data'] ?? [];
            $user = $data['user'] ?? [];
            $seller = $data['seller'] ?? [];

            return view('Page.Profile.Index', compact('user', 'seller'));
        } catch (\Exception $e) {
            Log::error('ProfileController index error: '.$e->getMessage());
            $user = [];
            $seller = [];
            return view('Page.Profile.Index', compact('user', 'seller'));
        }
    }
}

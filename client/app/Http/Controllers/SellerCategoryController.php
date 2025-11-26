<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Api\CategoryApi;

class SellerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new CategoryApi();
        $resp = $api->listWithCount($token);
        $categories = [];
        if ($resp->status() === 200) {
            $categories = $resp->json()['data'] ?? [];
        }
        return view('Page.DashboardSeller.Kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100',
        ]);
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new CategoryApi();
        $resp = $api->create(['name' => $request->input('name')], $token);
        if (in_array($resp->status(), [200,201])) {
            return redirect()->route('dashboard-seller.kategori')->with('success', 'Kategori berhasil ditambahkan.');
        }
        $msg = $resp->json()['message'] ?? 'Gagal menambahkan kategori.';
        return back()->withInput()->with('error', $msg);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\CategoryApi;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new CategoryApi();
        $resp = $api->list($token);
        $categories = [];
        if ($resp->status() === 200) {
            $categories = $resp->json()['data'] ?? [];
        }
        return view('Page.DashboardAdmin.Kategori', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.min' => 'Nama kategori minimal 2 karakter',
            'name.max' => 'Nama kategori maksimal 100 karakter',
        ]);
        
        // Check authentication
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Call API
        $api = new CategoryApi();
        $resp = $api->create(['name' => trim($validated['name'])], $token);
        
        // Handle response
        if ($resp->status() === 201) {
            return redirect()->route('dashboard-admin.kategori')->with('success', 'Kategori berhasil ditambahkan!');
        }
        
        if ($resp->status() === 200) {
            $data = $resp->json();
            if (isset($data['message']) && str_contains($data['message'], 'already exists')) {
                return back()->withInput()->with('error', 'Kategori dengan nama "' . $validated['name'] . '" sudah ada.');
            }
        }
        
        if ($resp->status() === 400) {
            $msg = $resp->json()['message'] ?? 'Data tidak valid.';
            return back()->withInput()->with('error', $msg);
        }
        
        if ($resp->status() === 401 || $resp->status() === 403) {
            return redirect()->route('login.loginIndex')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }
        
        // Default error
        $msg = $resp->json()['message'] ?? 'Terjadi kesalahan saat menambahkan kategori.';
        return back()->withInput()->with('error', $msg);
    }

    public function createView(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new \App\Api\CategoryApi();
        $resp = $api->list($token);
        $categories = [];
        if ($resp->status() === 200) {
            $categories = $resp->json()['data'] ?? [];
        }
        return view('Page.DashboardAdmin.TambahKategori', compact('categories'));
    }
}

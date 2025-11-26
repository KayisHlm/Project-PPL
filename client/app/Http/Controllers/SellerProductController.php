<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Api\ProductApi;

class SellerProductController extends Controller
{
    public function index(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new ProductApi();
        $resp = $api->list($token);
        $products = [];
        if ($resp->status() === 200) {
            $json = $resp->json();
            $products = $json['data'] ?? [];
        }
        return view('Page.DashboardSeller.Produk', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'condition' => 'required|in:Baru,Bekas',
            'price' => 'required|integer|min:1',
            'weight' => 'required|integer|min:1',
            'minOrder' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'warranty' => 'required|string|max:50',
            'year' => 'required|integer|min:2000',
            'claim' => 'required|string|min:5',
            'description' => 'required|string|min:10',
        ]);

        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cover = null;
        try {
            $files = $request->file('images');
            if (is_array($files) && count($files) > 0 && $files[0]) {
                $dir = public_path('uploads/products');
                if (!File::exists($dir)) { File::makeDirectory($dir, 0755, true); }
                $f = $files[0];
                $safeName = time().'_'.uniqid().'_'.preg_replace('/[^A-Za-z0-9_.-]/','_', $f->getClientOriginalName());
                $f->move($dir, $safeName);
                $cover = '/uploads/products/'.$safeName;
            }
        } catch (\Throwable $e) {
            Log::error('[SellerProductController] upload image error: '.$e->getMessage());
        }

        $payload = [
            'name' => $request->input('name'),
            'condition' => $request->input('condition'),
            'price' => (int) $request->input('price'),
            'weight' => (int) $request->input('weight'),
            'minOrder' => (int) $request->input('minOrder'),
            'category' => $request->input('category'),
            'warranty' => $request->input('warranty'),
            'year' => (int) $request->input('year'),
            'claim' => $request->input('claim'),
            'description' => $request->input('description'),
            'cover_image' => $cover,
        ];

        $api = new ProductApi();
        $resp = $api->create($payload, $token);

        if ($resp->status() === 201) {
            return redirect()->route('dashboard-seller.produk')->with('success', 'Produk berhasil ditambahkan.');
        }
        $msg = $resp->json()['message'] ?? 'Gagal menambahkan produk.';
        return back()->withInput()->with('error', $msg);
    }

    public function categories(Request $request)
    {
        $token = session('auth_token');
        if (!$token) {
            return redirect()->route('login.loginIndex')->with('error', 'Silakan login terlebih dahulu.');
        }
        $api = new ProductApi();
        $resp = $api->categories($token);
        $categories = [];
        if ($resp->status() === 200) {
            $json = $resp->json();
            $categories = $json['data'] ?? [];
        }
        return view('Page.DashboardSeller.Kategori', compact('categories'));
    }
}

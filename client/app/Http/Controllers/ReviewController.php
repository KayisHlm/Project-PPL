<?php

namespace App\Http\Controllers;

use App\Api\ReviewApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    protected $reviewApi;

    public function __construct()
    {
        $this->reviewApi = new ReviewApi();
    }

    public function store(Request $request, $productId)
    {
        try {
            Log::info('Review submission started', ['productId' => $productId, 'data' => $request->all()]);
            
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|max:255',
                'no_telp' => 'nullable|string|max:20',
                'province' => 'required|string|max:255',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string'
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            $response = $this->reviewApi->create($productId, $validated);
            
            Log::info('API response received', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review berhasil ditambahkan',
                    'data' => $response->json()['data'] ?? null
                ]);
            }

            $errorData = $response->json();
            return response()->json([
                'success' => false,
                'message' => $errorData['message'] ?? 'Gagal menambahkan review'
            ], $response->status());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception creating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan review'
            ], 500);
        }
    }
}

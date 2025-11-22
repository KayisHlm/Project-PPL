<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\AdminApi;

class AdminController extends Controller
{
    protected $adminApi;

    public function __construct()
    {
        $this->adminApi = new AdminApi();
        
        // Middleware untuk cek apakah user adalah admin
        $this->middleware(function ($request, $next) {
            if (!session()->has('access_token') || session('user_role') !== 'admin') {
                return redirect()->route('login.loginIndex')
                    ->withErrors(['access' => 'Unauthorized access. Admin only.']);
            }
            return $next($request);
        });
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        try {
            // Fetch statistics untuk dashboard
            $response = $this->adminApi->getPendingSellers();
            
            $pendingCount = 0;
            if ($response->successful()) {
                $pendingCount = $response->json()['data']['total'] ?? 0;
            }

            return view('Page.DashboardAdmin.Dashboard', compact('pendingCount'));

        } catch (\Exception $e) {
            return view('Page.DashboardAdmin.Dashboard')
                ->with('pendingCount', 0);
        }
    }

    /**
     * Show pending sellers page
     */
    public function pendingSellers()
    {
        try {
            $response = $this->adminApi->getPendingSellers();

            if ($response->successful()) {
                $data = $response->json()['data'];
                $sellers = $data['sellers'];
                $total = $data['total'];

                return view('Page.DashboardAdmin.PendingSellers', compact('sellers', 'total'));
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to fetch pending sellers';
                return view('Page.DashboardAdmin.PendingSellers')
                    ->withErrors(['fetch' => $errorMessage])
                    ->with('sellers', [])
                    ->with('total', 0);
            }

        } catch (\Exception $e) {
            \Log::error('Pending Sellers Error: ' . $e->getMessage());
            return view('Page.DashboardAdmin.PendingSellers')
                ->withErrors(['fetch' => 'Connection error: ' . $e->getMessage()])
                ->with('sellers', [])
                ->with('total', 0);
        }
    }

    /**
     * Approve seller
     */
    public function approveSeller($sellerId)
    {
        try {
            $response = $this->adminApi->approveSeller($sellerId);

            if ($response->successful()) {
                return redirect()->route('admin.pending-sellers')
                    ->with('success', 'Seller approved successfully');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to approve seller';
                return back()->withErrors(['approve' => $errorMessage]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['approve' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject seller
     */
    public function rejectSeller($sellerId)
    {
        try {
            $response = $this->adminApi->rejectSeller($sellerId);

            if ($response->successful()) {
                return redirect()->route('admin.pending-sellers')
                    ->with('success', 'Seller rejected successfully');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to reject seller';
                return back()->withErrors(['reject' => $errorMessage]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['reject' => 'Connection error: ' . $e->getMessage()]);
        }
    }
}
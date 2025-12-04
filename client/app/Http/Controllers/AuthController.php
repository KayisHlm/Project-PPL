<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Api\AuthApi;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    protected $authApi;


    public function __construct()
    {
        $this->authApi = new AuthApi;
    }

    public function registerIndex () {
        return view('Page.Auth.Register');
    }

    public function loginIndex () {
        return view('Page.Auth.Login');
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|max:254',
            'password' => 'required|string|min:8|max:255',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        try {
            Log::info('[AuthController] Login attempt for: ' . $request->email);
            
            // Prepare data untuk API
            $loginData = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            // Call API login via AuthApi
            $apiResponse = $this->authApi->login($loginData);

            // Check API response
            if ($apiResponse === null) {
                Log::error('[AuthController] API returned null');
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Server error. Please try again later.');
            }

            if ($apiResponse === "timeout") {
                Log::error('[AuthController] API timeout');
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Connection timeout. Please check your internet connection.');
            }

            // Parse API response
            $statusCode = $apiResponse->status();
            $responseData = $apiResponse->json();

            Log::info('[AuthController] API Response:', [
                'status' => $statusCode,
                'data' => $responseData
            ]);

            // Handle response berdasarkan status
            if ($statusCode === 200 && isset($responseData['token'])) {
                Log::info('[AuthController] Login successful for: ' . $request->email);
                session([
                    'auth_token' => $responseData['token'],
                    'user_data' => $responseData['user']
                ]);
                $role = $responseData['user']['role'] ?? null;
                if ($role === 'seller') {
                    return redirect()->route('dashboard-seller.dashboard')
                        ->with('success', 'Login successful! Welcome back.');
                }
                return redirect()->route('dashboard-admin.dashboard')
                    ->with('success', 'Login successful! Welcome back.');
            } elseif ($statusCode === 404) {
                // Email tidak ditemukan
                Log::warning('[AuthController] Email not found: ' . $request->email);
                
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Email not found. Please check your email or sign up.');
                
            } elseif ($statusCode === 401) {
                // Password salah
                Log::warning('[AuthController] Incorrect password for: ' . $request->email);
                
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Incorrect password. Please try again.');
                
            } elseif ($statusCode === 400) {
                // Bad request
                Log::warning('[AuthController] Bad request: ' . json_encode($responseData));
                
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', $responseData['message'] ?? 'Invalid input. Please check your data.');
                
            } else {
                // Unexpected error
                Log::error('[AuthController] Unexpected API response:', [
                    'status' => $statusCode,
                    'data' => $responseData
                ]);
                
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', $responseData['message'] ?? 'Login failed. Please try again.');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('[AuthController] Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function register (Request $request) {
        // dd($request);
        $request->validate([
            'email' => 'required|email|max:254|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'password_confirmation' => 'required|string|min:8|max:255|same:password',
            'shopName' => 'required|string|max:100',
            'shopDescription' => 'required|string|max:300',
            'picName' => 'required|string|max:100',
            'picPhoneNumber' => [
                'required',
                'string',
                'max:16',
                'regex:/^(\+62|0)\d{9,13}$/',
            ],
            'picEmail' => 'required|email|max:254',
            'picAddress' => 'required|string|max:200',
            'picRt' => 'required|string|max:3|regex:/^\d{1,3}$/',
            'picRw' => 'required|string|max:3|regex:/^\d{1,3}$/',
            'picProvince' => 'required|string|max:100',
            'picCity' => 'required|string|max:100',
            'picDistrict' => 'required|string|max:100',
            'picVillage' => 'required|string|max:100',
            'picKtpNumber' => 'required|string|size:16|regex:/^\d{16}$/',
            'picPhotoPath' => 'required|string',
            'picKtpPath' => 'required|string',
            'agree_final' => 'required|accepted',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
            'shopName.required' => 'Nama toko wajib diisi.',
            'shopDescription.required' => 'Deskripsi toko wajib diisi.',
            'picName.required' => 'Nama PIC wajib diisi.',
            'picPhoneNumber.required' => 'No handphone PIC wajib diisi.',
            'picPhoneNumber.regex' => 'Format no handphone tidak valid.',
            'picEmail.required' => 'Email PIC wajib diisi.',
            'picEmail.email' => 'Format email PIC tidak valid.',
            'picAddress.required' => 'Alamat PIC wajib diisi.',
            'picRt.required' => 'RT wajib diisi.',
            'picRt.regex' => 'RT harus berupa angka.',
            'picRw.required' => 'RW wajib diisi.',
            'picRw.regex' => 'RW harus berupa angka.',
            'picProvince.required' => 'Provinsi wajib diisi.',
            'picCity.required' => 'Kota/Kabupaten wajib diisi.',
            'picDistrict.required' => 'Kecamatan wajib diisi.',
            'picVillage.required' => 'Kelurahan wajib diisi.',
            'picKtpNumber.required' => 'No KTP PIC wajib diisi.',
            'picKtpNumber.size' => 'No KTP harus 16 digit.',
            'picKtpNumber.regex' => 'No KTP harus berupa angka.',
            'picPhotoPath.required' => 'Foto PIC wajib diupload.',
            'picKtpPath.required' => 'File KTP wajib diupload.',
            'agree_final.required' => 'Anda harus menyetujui pernyataan akhir.',
            'agree_final.accepted' => 'Anda harus mencentang persetujuan.',
        ]);

        DB::beginTransaction();

        try {
            Log::info('Entering try block for register save');
            
            $addData = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'shopName' => $request->input('shopName'),
                'shopDescription' => $request->input('shopDescription'),
                'picName' => $request->input('picName'),
                'picPhoneNumber' => $request->input('picPhoneNumber'),
                'picEmail' => $request->input('picEmail'),
                'picAddress' => $request->input('picAddress'),
                'picRt' => $request->input('picRt'),
                'picRw' => $request->input('picRw'),
                'picProvince' => $request->input('picProvince'),
                'picCity' => $request->input('picCity'),
                'picDistrict' => $request->input('picDistrict'),
                'picVillage' => $request->input('picVillage'),
                'picKtpNumber' => $request->input('picKtpNumber'),
                'picPhotoPath' => $request->input('picPhotoPath'),
                'picKtpPath' => $request->input('picKtpPath'),
            ];

            Log::info('Data to be sent to API:', $addData);
            // dd($addData);
            
            $apiResponse = $this->authApi->save($addData);

            // Debug: Check API response details
            Log::info('API Response Details:', [
                'response_type' => gettype($apiResponse),
                'is_null' => $apiResponse === null,
                'is_timeout' => $apiResponse === "timeout",
                'response_class' => $apiResponse ? get_class($apiResponse) : 'null',
                'status_code' => $apiResponse ? $apiResponse->status() : 'N/A',
                'successful' => $apiResponse ? $apiResponse->successful() : false,
                'response_body' => $apiResponse ? $apiResponse->body() : 'N/A'
            ]);

            if ($apiResponse === null) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Data tidak ditemukan!');
            } elseif ($apiResponse === "timeout"){
                DB::rollBack();
                return back()->withInput()->with('error', 'Koneksi API timeout, silakan coba lagi!');
            }

            if ($apiResponse->successful()) {
                DB::commit();
                
                return redirect()->route('login.loginIndex')
                    ->with('success', 'Registrasi berhasil! Cek email Anda secara berkala untuk status verifikasi akun Anda.');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Exception error: {$e->getMessage()}");
            Log::error("Exception trace: {$e->getTraceAsString()}");
            Log::error("Exception file: {$e->getFile()}:{$e->getLine()}");
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem!');
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authApi->logout();
        } catch (\Throwable $e) {
        }
        $request->session()->forget(['auth_token','user_data']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.loginIndex')->with('success', 'Anda telah logout.');
    }
}

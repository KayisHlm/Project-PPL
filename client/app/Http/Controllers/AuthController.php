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
                return redirect()->route('login.loginIndex');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Exception error: {$e->getMessage()}");
            Log::error("Exception trace: {$e->getTraceAsString()}");
            Log::error("Exception file: {$e->getFile()}:{$e->getLine()}");
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem!');
        }
    }
}
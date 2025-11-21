<?php

namespace App\Http\Controllers;

use App\Api\WilayahApi;
use Illuminate\Http\JsonResponse;

class WilayahController extends Controller
{
    protected $api;

    public function __construct(WilayahApi $api)
    {
        $this->api = $api;
    }

    public function provinces(): JsonResponse
    {
        $response = $this->api->provinces();
        return response()->json($response->json());
    }

    public function regencies($provinceCode): JsonResponse
    {
        $response = $this->api->regencies($provinceCode);
        return response()->json($response->json());
    }

    public function districts($regencyCode): JsonResponse
    {
        $response = $this->api->districts($regencyCode);
        return response()->json($response->json());
    }

    public function villages($districtCode): JsonResponse
    {
        $response = $this->api->villages($districtCode);
        return response()->json($response->json());
    }
}

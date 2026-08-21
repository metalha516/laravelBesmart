<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\DiscountWheelService;
use Illuminate\Http\Request;

class WheelController extends Controller
{
    public function config(DiscountWheelService $wheelService)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'segments' => $wheelService->getSegments(),
            ]
        ]);
    }

    public function spin(Request $request, DiscountWheelService $wheelService)
    {
        $userId = $request->user()?->id;
        $ip = $request->ip();

        $result = $wheelService->spin($userId, $ip);

        return response()->json($result);
    }
}

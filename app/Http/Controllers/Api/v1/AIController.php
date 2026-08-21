<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use App\Services\DiscountWheelService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function chat(Request $request, AIService $aiService)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $isB2B = $request->user() && $request->user()->isB2B();
        $result = $aiService->searchProducts($validated['prompt'], $isB2B);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}

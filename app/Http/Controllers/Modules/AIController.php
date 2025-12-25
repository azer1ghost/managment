<?php

declare(strict_types=1);

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AIController extends Controller
{
    private AIService $aiService;

    // Allowed user IDs - ONLY these users can access AI assistant
    private const ALLOWED_USER_IDS = [1, 2, 15, 78, 123];

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle AI question request
     * READ-ONLY endpoint - no database writes allowed
     * 
     * SECURITY: Only allows access to users with IDs: 15, 78, 123
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ask(Request $request): JsonResponse
    {
        // Authorization check - ONLY allow specific user IDs
        if (!Auth::check() || !in_array(Auth::id(), self::ALLOWED_USER_IDS, true)) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'Bu funksiyaya giriş imkanınız yoxdur.'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $question = $request->input('question');
            
            // Get AI response with metadata (READ-ONLY operation)
            $result = $this->aiService->answerQuestion($question);

            return response()->json([
                'success' => true,
                'question' => $question,
                'answer' => $result['answer'],
                'intent' => $result['intent'],
                'confidence' => $result['confidence'],
                'period' => $result['period'],
            ], 200);

        } catch (\Exception $e) {
            // Sanitize error message to prevent JS syntax errors
            $errorMessage = str_replace(['"', "'", '`'], '', $e->getMessage());
            $errorMessage = mb_substr($errorMessage, 0, 200); // Limit length
            
            return response()->json([
                'success' => false,
                'error' => 'AI service error',
                'message' => $errorMessage,
                'answer' => 'Xəta baş verdi: ' . $errorMessage,
                'intent' => null,
                'confidence' => 'low',
                'period' => null,
            ], 500);
        }
    }
}

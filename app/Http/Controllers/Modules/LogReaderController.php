<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Services\LogReaderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogReaderController extends Controller
{
    public function __construct(
        protected LogReaderService $logReader
    ) {}

    public function getIndex(): View
    {
        return view('LaravelLogReader::index');
    }

    public function getLogs(Request $request): JsonResponse
    {
        $date = $request->has('date') ? $request->get('date') : null;
        $result = $this->logReader->getLogs($date);

        if ($result['success']) {
            return response()->json(
                ['success' => true, 'data' => $result['data']],
                200,
                [],
                JSON_INVALID_UTF8_SUBSTITUTE
            );
        }

        $fallback = [
            'available_log_dates' => [],
            'date' => '',
            'filename' => '',
            'logs' => [],
        ];
        return response()->json(
            [
                'success' => false,
                'message' => $result['message'],
                'data' => $fallback,
            ],
            200,
            [],
            JSON_INVALID_UTF8_SUBSTITUTE
        );
    }

    public function postDelete(Request $request): JsonResponse
    {
        if ($request->has('filename')) {
            $ok = $this->logReader->deleteFile($request->get('filename'));
            $payload = $ok
                ? ['success' => true, 'message' => 'Successfully deleted']
                : ['success' => false, 'message' => 'File not found or invalid'];

            return response()->json($payload, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
        }
        if ($request->has('clear') && $request->get('clear') === true) {
            $this->logReader->clearAll();
            return response()->json(
                ['success' => true, 'message' => 'All Successfully deleted'],
                200,
                [],
                JSON_INVALID_UTF8_SUBSTITUTE
            );
        }

        return response()->json(
            ['success' => false, 'message' => 'Invalid request'],
            200,
            [],
            JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}

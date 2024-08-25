<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function sendSuccess($result = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $result ?? null,
        ], $code);
    }

    public function sendError($error, array $errorMessages = [], $code = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages; // 'data' yerine 'errors' kullanımı daha uygun olabilir.
        }

        return response()->json($response, $code);
    }
}

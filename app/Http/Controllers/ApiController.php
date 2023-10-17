<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sendSuccessResponse($data, $message = '', $statusCode = 200)
    {
        $response = [
            'success' => 1,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    public function sendErrorResponse($message, $error = null, $statusCode = 400)
    {
        if ($error == null) {
            $response = [
                'success' => 0,
                'message' => $message,
                'error' => $error,
            ];
        }else{
            $response = [
                'success' => 0,
                'message' => $message,
            ];
        }


        return response()->json($response, $statusCode);
    }
}

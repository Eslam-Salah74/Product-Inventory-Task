<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    public function apiResponse($data = null, $message = null, $status = 200)
    {
        $response = [
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'status'  => $status,
        ];

        if ($data instanceof LengthAwarePaginator) {
            $response['data'] = $data->items();
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ];
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    public function errorResponse($message = 'Error occurred', $status = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status'  => $status,
            'errors'  => $errors,
        ], $status);
    }
}

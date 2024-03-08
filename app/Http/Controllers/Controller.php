<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected function josnResponse($result = true, $message = "", $code = 200, $data = null, $error = null)
    {
        $reponse = [
            'result' => $result,
            'status' => $code,
            'message' => $message,
        ];

        if ($data !== null || is_array($data)) {
            $reponse['data'] = $data;
        }

        if ($error) {
            $reponse['errors'] = $error;
        }

        return response()->json($reponse, $code);
    }
}

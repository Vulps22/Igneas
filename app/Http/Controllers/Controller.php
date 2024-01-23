<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function success($data = '', $code = 200)
    {
        return json_encode([
            'response' => "success",
            'data' => $data,
            'code' => $code
        ]);
    }

    function error($data = '', $code = 500)
    {
        return json_encode([
            'response' => "error",
            'data' => $data,
            'code' => $code
        ]);
    }

    /**
     * Ensure each $fields is in $data and is not null
     *
     * @param array $data
     * @param array $fields
     * @return bool
     */
    function ensure($data, $fields)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === null) {
                return false;
            }
        }

        return true;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthenticationController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public $auth;

    function __construct(AuthenticationController $authController){
        $this->auth = $authController;
    }

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
            if (!isset($data[$field]) || !$data[$field]) {
                 echo $field;
                return false;
            }
        }

        return true;
    }

    function fill($array, $data)
    {
        foreach ($data as $field => $defaultValue) {
            // Check if the field exists in $data and is not null
            if (isset($array[$field]) && $array[$field] == null) {
                // Update the value in $array with the value from $data
                $array[$field] = $data[$field];
            } elseif(!isset($array[$field])) {
                
                // Use the default value if the field is null or non-existent in $data
                $array[$field] = $defaultValue;
            }
        }

        return $array;
    }

    function verify(Request $request) {
        if(!$this->ensure($request->all(), ['token', 'user_id'])) return false; //make sure the Authentication data is in the request

        return $this->auth->check($request->token, $request->user_id); //check the token is valid and "login" the user for this request
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UserAccessToken;
use Illuminate\Http\Request;

class UserAuthenticationController extends Controller
{

    /**
     * Authenticate a user request using a bearer token.
     *
     * This function verifies the validity of the provided bearer token by checking if it exists in the database
     * and validating it against the associated user access token. If the token is valid, a success response is returned.
     * If the token does not exist or is not valid, an error response with the appropriate HTTP status code is returned.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the bearer token in the authorization header.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the authentication status:
     * - If the token is valid, a success response with HTTP status code 200.
     * - If the token does not exist, an error response with HTTP status code 401 (Unauthorized), indicating "Access Denied: Token does not exist".
     * - If the token is not valid, an error response with HTTP status code 401 (Unauthorized), indicating "Access Denied: Token not valid".
     */
    public function authenticate(Request $request)
    {
        $token = $request->bearerToken();
        $accessToken = UserAccessToken::find($token);

        if (!$accessToken) return $this->error('Access Denied: Token does not exist', 401);

        if ($accessToken->validate(true)) return $this->success(['id' => $accessToken->user_id]);

        return $this->error('Access Denied: Token not valid', 401);
    }

    public function deauthenticate(Request $request)
    {
        $token = $request->bearerToken();
        $accessToken = UserAccessToken::find($token);
        if (!$accessToken) return $this->success('Token Destroyed');
        
        $accessToken->delete();

        $accessToken = UserAccessToken::find($token);
        if($accessToken) return $this->error('Unable to destroy Access Token. This is a serious Error. Please Report it');
        
        return $this->success('Token Destroyed');

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\UserAccessToken;
use Illuminate\Http\Request;

class UserAuthenticationController extends Controller
{
    public function authenticate(Request $request)
    {
        $token = $request->bearerToken();
        $accessToken = UserAccessToken::find($token);

        if (!$accessToken) return $this->error('Access Denied: Token does not exist', 401);

        if ($accessToken->validate(true)) return $this->success();

        return $this->error('Access Denied: Token not valid', 401);
    }
}

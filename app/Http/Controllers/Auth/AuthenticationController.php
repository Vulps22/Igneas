<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;


class AuthenticationController extends BaseController
{
    public User $user;

    public function check($token)
    {
        $accessToken = UserAccessToken::find($token);
        if (!$accessToken || !$accessToken->validate(true)) {
            $this->delete($accessToken);
            return false;
        }

        $user = $accessToken->user;
        //var_dump($user);
        if (!$user) {
            Log::error("Attempt to validate token with no assigned User: $token");
            $this->delete($accessToken);
            return false;
        }

        $this->user = $user;

        return true;
    }

    public function destroy($token)
    {
        if (!$this->user) {
            Log::error('Attempt to destroy token without an authenticated user.');
            return false;
        }

        if (!$token) {
            Log::error('Attempt to destroy token without providing a token.');
            return false;
        }

        if (!$this->check($token, $this->user->id)) {
            Log::error('Failed to validate token during destruction.');
            return false;
        }

        $userToken = UserAccessToken::find($token);
        $this->delete($userToken);
        return true;
    }

    private function delete($userToken)
    {
        if ($userToken && !$userToken->immortal) {
            $userToken->delete();
        }
    }
}

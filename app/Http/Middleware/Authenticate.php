<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\AuthenticationController;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {

        $token = $request->bearerToken();
        //var_dump($token);
        if(!$token) return response('Authentication data missing', 403);

        $authentication = new AuthenticationController();

        if ($authentication->check($token)) {
            $request->attributes->add(['auth' => $authentication]);

            return $next($request);
        }

        return response('Unauthenticated', 401);
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
}

<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
            
            if(in_array($token->role, $role))
            {
                return $next($request);
            }
            
            /**if($token->role == $role)
            {
                 return $next($request);
            }*/
           return response()->json([ 'status' => 403, 'error' => 'Unauthorized'], 403);
            

        } catch (JWTException $e) {


            // If the token creation has an error.
            return response()->json([ 'status' => 401, 'error' => 'Token Invalid'], 401);


        }


        
    }
}

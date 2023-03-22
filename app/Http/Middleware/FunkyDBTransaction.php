<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class FunkyDBTransaction
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        DB::beginTransaction();

        $response = $next($request);
        
        if($response->original['errorCode'] !== 0){
            DB::rollBack();
        }

        DB::commit();

        return $response;
    }
}

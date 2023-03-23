<?php

namespace App\Http\Middleware;

use App\Responses\FunkyResponse;
use Closure;

class FunkyAuthenticationToken
{
    private $response;

    public function __construct(FunkyResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Authentication') ||
            $request->header('Authentication') != config('zircon.FUNKY_ZIRCON_TOKEN')) {
            return $this->response->invalidInput('Invalid Authentication Token');
        }

        return $next($request);
    }
}

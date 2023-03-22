<?php
namespace App\Libraries;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class LaravelHttp
{
    public function post(string $url, array $request, array $headers = []): Response
    {
        return Http::withHeaders($headers)
            ->post($url, $request);
    }
}
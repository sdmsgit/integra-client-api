<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\Token as TokenLibrary;
use App\Http\Library\ClientApiKey as ClientApiKeyLibrary;

class TokenValidator
{
    protected $responseData = array(
        "data" => array(),
        "messages" => array(),
        "code" => 200
    );

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = array(
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS'
        );

        $token = $request->input('token');
        if (!$token) {
            $this->responseData["messages"][] = HelperLibrary::getErrorUnauthorized();
            $this->responseData["code"] = HelperLibrary::$responseCode["UNAUTHORIZED"];
            return response()->json($this->responseData, $this->responseData["code"])->withHeaders($header);
        }

        $tokenLibrary = new TokenLibrary();
        $apiKeyLibrary = new ClientApiKeyLibrary();

        $tokenData = $apiKeyLibrary->decodeToken($request->bearerToken());
        $validToken = $tokenLibrary->validateToken($tokenData->information->xtrsd,  $token);
        if (!$validToken) {
            $this->responseData["messages"][] = HelperLibrary::getErrorUnauthorized();
            $this->responseData["code"] = HelperLibrary::$responseCode["UNAUTHORIZED"];
            return response()->json($this->responseData, $this->responseData["code"])->withHeaders($header);
        }
        return $next($request);
    }
}

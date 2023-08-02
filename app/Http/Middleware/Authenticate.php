<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\ClientApiKey as ClientApiKeyLibrary;
use Exception;

class Authenticate
{
    protected $path;
    protected $ip;
    protected $bearerToken;
    protected $actionDate;
    protected $data;
    protected $bridgeData;

    protected $responseData = array(
        "data" => array(),
        "messages" => array(),
        "code" => 200
    );

    public function __construct(Request $request)
    {
        $this->path = $request->path();
        $this->ip = HelperLibrary::getIp();
        $this->bearerToken = $request->bearerToken();
        $this->actionDate = new \DateTime;
        $this->data = $request->all();
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
        $header = array(
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS'
        );
        //block all request except post or OPTIONS method
        if (!$this->bearerToken) {
            $this->responseData["messages"][] = HelperLibrary::getErrorUnauthorized();
            $this->responseData["code"] = HelperLibrary::$responseCode["UNAUTHORIZED"];
            return response()->json($this->responseData, $this->responseData["code"])->withHeaders($header);
        }
        if ($request->isMethod('OPTIONS')) {
            $this->responseData["messages"] = [];
            $this->responseData["code"] = HelperLibrary::$responseCode["OK"];

            return response()->json($this->responseData, $this->responseData["code"])->withHeaders($header);
        }

        $apiKeyLibrary = new ClientApiKeyLibrary();
        try {
            $tokenData = $apiKeyLibrary->decodeToken($this->bearerToken);

            $appIdData = $apiKeyLibrary->validateAppId($this->bearerToken, $tokenData->information->xtrsd);

            app()->instance("DatabaseInstance", $tokenData->information->xtrsd);
            app()->instance("AppIdData", $appIdData);

            app()->instance("ResponseData", $this->responseData);

            app()->instance("RequestData", $this->data);

            app()->instance("IP", $this->ip);
            app()->instance("BearerToken", $this->bearerToken);
            app()->instance("ActionDate", $this->actionDate);

            return $next($request);
        } catch (Exception $e) {
            $this->responseData["messages"][] = HelperLibrary::getErrorUnauthorized();
            $this->responseData["code"] = HelperLibrary::$responseCode["UNAUTHORIZED"];
            return response()->json($this->responseData, $this->responseData["code"])->withHeaders($header);
        }
    }
}

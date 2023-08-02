<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $app;
    protected $ip = false;
    protected $responseData = false;
    protected $bridgeData = false;
    protected $bearerToken = false;
    protected $actionDate = false;
    protected $requestData = false;
    protected $force = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = app();
        $this->ip = $this->app->make('IP');
        $this->requestData = $this->app->make('RequestData');
        $this->responseData = $this->app->make('ResponseData');
        $this->bearerToken = $this->app->make('BearerToken');
        $this->actionDate = $this->app->make('ActionDate');
    }


    protected function sendResponse($responseData)
    {
        $response = response()->json($responseData, $responseData["code"]);
        // Adds headers to the response
        $response->header("Access-Control-Allow-Origin", "*");
        $response->header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE");
        $response->header('Content-Type', 'application/json');

        return $response;
    }

    protected function validateRequest($array)
    {
        foreach ($array as $value) {
            if (!isset($this->requestData[$value]) || empty($this->requestData[$value])) {
                return false;
            }
        }

        return true;
    }
}

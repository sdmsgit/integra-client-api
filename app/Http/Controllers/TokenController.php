<?php

namespace App\Http\Controllers;

use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\Token;
use Exception;

class TokenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function generate()
    {
        try {
            $tokenLibrary = new Token();
            $tokenData = $tokenLibrary->getToken($this->databaseInstance, $this->appIdData->id);

            $this->responseData["data"] = $tokenData;
            $this->responseData["messages"] = array();
            $this->responseData["code"] = HelperLibrary::$responseCode["OK"];
            return $this->sendResponse($this->responseData);
        } catch (Exception $e) {
            $this->responseData["messages"][] = HelperLibrary::gerErrorCustomMessage($e->getMessage());
            $this->responseData["code"] = HelperLibrary::$responseCode["BAD_REQUEST"];

            return $this->sendResponse($this->responseData);
        }
    }
}

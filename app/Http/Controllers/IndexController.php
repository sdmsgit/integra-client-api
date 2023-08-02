<?php

namespace App\Http\Controllers;

use App\Http\Library\Helper as HelperLibrary;

class IndexController extends Controller
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

    public function index()
    {
        $this->responseData["messages"][] = HelperLibrary::getErrorBadRequest();
        $this->responseData["code"] = HelperLibrary::$responseCode["BAD_REQUEST"];

        return $this->sendResponse($this->responseData);
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Library\Uom;
use Illuminate\Http\Client\Request;
use App\Http\Library\Helper as HelperLibrary;

class UomController extends Controller
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
        try {

            $id = null;
            $name = null;

            if (isset($this->requestData['name']) && $this->requestData['name'] != "") {
                $name = $this->requestData['name'];
            }
            if (isset($this->requestData['id']) && $this->requestData['id'] != "") {
                $id = $this->requestData['id'];
            }

            $uomLibrary = new Uom();

            $result = $uomLibrary->getUom($this->databaseInstance, $id, $name);
            $this->responseData["data"] = $result;
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

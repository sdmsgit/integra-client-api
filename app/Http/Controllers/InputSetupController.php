<?php

namespace App\Http\Controllers;

use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\InputSetup;
use Exception;

class InputSetupController extends Controller
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
            $year = null;
            $inputSetupLibrary = new InputSetup();
            if (isset($this->requestData['year']) && $this->requestData['year'] != "") {
                $year = (int) $this->requestData['year'];
            }
            if (isset($this->requestData['id']) && $this->requestData['id'] != "") {
                $id = $this->requestData['id'];
            }

            $inputSetupData = $inputSetupLibrary->getInputSetup($this->databaseInstance, $id, $year);
            $this->responseData["data"] = $inputSetupData;
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

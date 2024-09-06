<?php

namespace App\Http\Controllers;

use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\DataSet;
use Exception;

class DataSetController extends Controller
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
            $dataSetLibrary = new DataSet();
            if (isset($this->requestData['id']) && $this->requestData['id'] != "") {
                $id = $this->requestData['id'];
            }

            $dataSet = $dataSetLibrary->getDataSet($this->databaseInstance, $id);
            $this->responseData["data"] = $dataSet;
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

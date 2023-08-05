<?php

namespace App\Http\Controllers;

use App\Http\Library\Entity;
use App\Http\Library\Helper as HelperLibrary;
use Exception;

class EntityController extends Controller
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
            $code = null;

            if (isset($this->requestData['code']) && $this->requestData['code'] != "") {
                $code = (int) $this->requestData['code'];
            }
            if (isset($this->requestData['id']) && $this->requestData['id'] != "") {
                $id = $this->requestData['id'];
            }

            $entityLibrary = new Entity();

            $entityData = $entityLibrary->getEntity($this->databaseInstance, $id, $code);
            $this->responseData["data"] = $entityData;
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

<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Library\Indikator;
use Illuminate\Http\Request;
use App\Http\Library\Helper as HelperLibrary;

class IndikatorController extends Controller
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

    public function index(Request $request)
    {
        try {
            $this->validate($request, [
                'data_set' => 'required',
                'input_setup' => 'required',
            ]);

            $id = null;
            $code = null;

            if (isset($this->requestData['code']) && $this->requestData['code'] != "") {
                $code = $this->requestData['code'];
            }
            if (isset($this->requestData['id']) && $this->requestData['id'] != "") {
                $id = $this->requestData['id'];
            }

            $indikatorLibrary = new Indikator();

            $result = $indikatorLibrary->getIndikator($this->databaseInstance,$this->requestData['data_set'], $this->requestData['input_setup'], $id, $code);
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

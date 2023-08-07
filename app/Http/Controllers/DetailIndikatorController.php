<?php

namespace App\Http\Controllers;

use App\Http\Library\DetailIndikator;
use Exception;
use Illuminate\Http\Request;
use App\Http\Library\Helper as HelperLibrary;

class DetailIndikatorController extends Controller
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
                'indikator_id' => 'required',
            ]);

            $detailIndikatorLibrary = new DetailIndikator();
            $result = $detailIndikatorLibrary->getDetailIndikator($this->databaseInstance,$this->requestData['indikator_id']);
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

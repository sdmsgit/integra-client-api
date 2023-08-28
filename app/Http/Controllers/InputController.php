<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Library\Helper as HelperLibrary;
use App\Http\Library\InputSetup as InputSetupLibrary;
use App\Http\Library\Entity as EntityLibrary;
use App\Http\Library\Indikator as IndikatorLibrary;

class InputController extends Controller
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

    public function store()
    {
        try {
            if (!isset($this->requestData['indicator_id']) || !isset($this->requestData['entity_id']) || !isset($this->requestData['input_setup_id']) || !isset($this->requestData['year'])) {
                throw new Exception(__('invalid_format'));
            }

            $inputSetupId = (int) $this->requestData['input_setup_id'];
            $inputSetupYear = (int)$this->requestData['year'];

            $inputSetupLibrary = new InputSetupLibrary();
            $inputSetupData = $inputSetupLibrary->getInputSetup($this->databaseInstance, $inputSetupId, $inputSetupYear);

            if (count($inputSetupData) <= 0) {
                throw new Exception("Input Setup ID/Input Setup Year Invalid.");
            }

            $entityLibrary = new EntityLibrary();
            $entityData = $entityLibrary->getEntityDetail($this->databaseInstance, (int)$this->requestData['entity_id']);
            if (!$entityData) {
                throw new Exception("Entity Invalid.");
            }

            $indikatorLibrary = new IndikatorLibrary();
            $indikatorData = $indikatorLibrary->getIndikatorDetail($this->databaseInstance, $this->requestData['indicator_id']);
            if (!$indikatorData) {
                throw new Exception("Indikator Invalid.");
            }

            dd($indikatorData);
        } catch (Exception $e) {
            $this->responseData["messages"][] = HelperLibrary::gerErrorCustomMessage($e->getMessage());
            $this->responseData["code"] = HelperLibrary::$responseCode["BAD_REQUEST"];

            return $this->sendResponse($this->responseData);
        }
    }
}

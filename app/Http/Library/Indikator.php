<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class Indikator
{

    public function getIndikatorDetail($database, $indicatorId)
    {
        $indikator = DB::connection($database)->table('history_m_indicators')->where('statusenabled', true)->where('id', $indicatorId)->first();
        if (!$indikator) {
            return false;
        }
        return $indikator;
    }


    public function getIndikator($database, $inputSetupId, $history_sdms,  $id = null, $code = null)
    {
        $inputSetupLibrary = new InputSetup();

        $inputSetupData = $inputSetupLibrary->getInputSetup($database, $inputSetupId, null);
        if (count($inputSetupData) <= 0) {
            return array();
        }
        $inputSetupData = $inputSetupData[0];

        $data = DB::connection($database)->table('history_m_indicators as hmi')
            ->join('history_m_disclousure as hmd', 'hmd.id', 'hmi.history_m_disclousure_id')
            ->join('m_periode as mp', 'mp.id', 'hmd.m_periode')
            ->select('hmi.id', 'hmi.code', 'hmi.name', 'mp.nama', 'mp.id as id_periode', 'hmi.status_data_setup')
            ->where('hmi.statusenabled', true)
            ->where('hmi.id_data_set', $history_sdms)
            ->where('hmi.history_master_sdms_id', $inputSetupId);

        if (isset($code) && $code != "" && $code != "undefined") {
            $data = $data->where('hmi.code', '=', $code);
        }
        if (isset($id) && $id != "" && $id != "undefined") {
            $data = $data->where('hmi.id', '=', $id);
        }
        $data = $data->get();

        //dd($inputSetupId . " " . $history_sdms);
        //return $data;
        if (count($data) > 0) {
            $listIdPeriode = array();
            foreach ($data as $dt) {
                array_push($listIdPeriode, $dt->id_periode);
            }

            $periode = DB::connection($database)->table('m_periode_detail as mpd')->select('id_m_periode', 'duedate', 'periode_order')->where('statusenabled', true)->whereIn('id_m_periode', $listIdPeriode)->orderBy('periode_order', 'asc')->get();

            $arrayPeriode = array();
            if (count($periode) > 0) {
                foreach ($periode as $periodeData) {
                    $periodeData->duedate = $inputSetupData->year . '-' . $periodeData->duedate;
                    $arrayPeriode[$periodeData->id_m_periode] = $periodeData;
                }
            }
        }

        $results = array();
        foreach ($data as $dt) {
            $resultInd = array(
                'id' => $dt->id,
                'code' => $dt->code,
                'name' => $dt->name,
                'status_data_setup' => $dt->status_data_setup,
                'periode' => trim($dt->nama),
            );
            if (isset($arrayPeriode[$dt->id_periode])) {
                $resultInd['periode_detail'] = $arrayPeriode[$dt->id_periode];
            }

            $results[] = $resultInd;
        }

        return $results;
    }
}

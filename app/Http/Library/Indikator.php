<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class Indikator
{

    public function getIndikator($database, $id_data_set, $history_sdms,  $id = null, $code = null)
    {

        $tahun = DB::connection($database)->table('input_setup as is')
        ->join('history_master_sdms as hms', 'hms.input_setup_id', 'is.id')
        ->select('hms.id', 'is.name', 'is.year', 'is.id_data_set')
        ->where('is.statusenabled', true)
        ->where('hms.id', $history_sdms)
        ->where('is.id_data_set', $id_data_set)
        ->first();

        $data = DB::connection($database)->table('history_m_indicators as hmi')
            ->join('history_m_disclousure as hmd', 'hmd.id', 'hmi.history_m_disclousure_id')
            ->join('m_periode as mp', 'mp.id', 'hmd.m_periode')
            ->select('hmi.id', 'hmi.code', 'hmi.name', 'mp.nama', 'mp.id as id_periode', 'hmi.status_data_setup')
            ->where('hmi.statusenabled', true)
            ->where('hmi.id_data_set', $id_data_set)
            ->where('hmi.history_master_sdms_id', $history_sdms);

        if (isset($code) && $code != "" && $code != "undefined") {
            $data = $data->where('hmi.code', '=', $code);
        }
        if (isset($id) && $id != "" && $id != "undefined") {
            $data = $data->where('hmi.id', '=', $id);
        }
        $data = $data->get();

        $terms = [];
        foreach ($data as $dt) {

            $periode = DB::connection($database)->table('m_periode_detail as mpd')->select('id_m_periode', 'duedate', 'periode_order')->where('statusenabled', true)->where('id_m_periode', $dt->id_periode)->orderBy('periode_order', 'asc')->get();

            unset($periodarr);
            $periodarr = [];

            foreach ($periode as $per) {
                $di_periode = $per->id_m_periode;
                $periodarr[] = array(
                    'id' =>  $di_periode,
                    'duedate' => $tahun->year . '-' . $per->duedate,
                    'periode_order' => $per->periode_order,
                );
            }

            $terms[] = array(
                'id' => $dt->id,
                'code' => $dt->code,
                'name' => $dt->name,
                'status_data_setup' => $dt->status_data_setup,
                'periode' => trim($dt->nama),
                'periode_detail' => $periodarr,
            );
        }

        return $terms;
    }
}

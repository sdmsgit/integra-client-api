<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class DetailIndikator
{

    public function getDetailIndikator($database, $indicator_id)
    {
        $data =  DB::connection($database)->table('history_m_indicator_details as mid')
            ->leftJoin('m_uom as mu', 'mu.id', 'mid.body_uom_2')
            ->select('mid.id', 'mid.code', 'mid.name', 'mid.mandatory', 'mid.header_1', 'mid.header_2', 'mid.header_3', 'mid.body_type_1', 'mu.name as uom_name', 'mid.body_type_3', 'mid.locked', 'mid.only_once', 'mid.sum', 'mid.formula', 'mid.editable', 'mid.status_data_setup')
            ->where('mid.history_m_indicators_id', $indicator_id)
            ->where('mid.statusenabled', true)
            ->get();
        $terms = [];
        foreach ($data as $dt) {
            // 
            $id = $dt->id;
            if (isset($dt->body_uom_2)) {
                $uom = $dt->body_uom_2;
            }
            $formula = [];
            $formula =  DB::connection($database)->table('history_m_indicator_details_formula as mdet')
                ->leftjoin('m_uom as mu', 'mu.id', '=', 'mdet.uom_id')
                ->leftjoin('history_m_indicator_details as mid', 'mid.id', '=', 'mdet.history_m_indicator_details')
                ->leftjoin('history_m_indicator_details as mid2', 'mid2.id', '=', 'mdet.indikator_detail_id_formula')
                ->select(
                    'mdet.id',
                    'mdet.indikator_type',
                    'mdet.indikator_detail_id',
                    'mid.name as indikator_detail_name',
                    'mid.code as indikator_detail_code',
                    'mdet.uom_id',
                    'mu.name as uom_name',
                    'mdet.rumus',
                    'mdet.calculation',
                    'mdet.indikator_detail_id_formula',
                    'mid2.name as indikator_detail_name_formula',
                    'mid2.code as indikator_detail_code_formula'

                )
                ->where('mdet.statusenabled', true)
                ->where('mdet.history_m_indicator_details', $dt->id)
                ->orderBy('mdet.id', 'asc')
                ->get();

            $question = [];
            $question =  DB::connection($database)->table('history_indicator_details_custom as midc')
                ->where('midc.statusenabled', true)
                ->where('midc.history_m_indicator_details', $dt->id)
                ->orderBy('midc.id', 'asc')
                ->get();

            $terms[] = array(
                'id' => $id,
                'code' => $dt->code,
                'name' => $dt->name,
                'mandatory' => $dt->mandatory,
                'header_1' => $dt->header_1,
                'header_2' => $dt->header_2,
                'header_3' => $dt->header_3,
                'body_type_1' => $dt->body_type_1,
                'body_uom_2' => $dt->uom_name,
                'body_type_3' => $dt->body_type_3,
                'formula' =>  $formula,
                'question_custom' =>   $question,
                'locked' => $dt->locked,
                'only_once' => $dt->only_once,
                'sum' => $dt->sum,
                'formulas' => $dt->formula,
                'editable' => $dt->editable,
                'status_data_setup' => $dt->status_data_setup,
            );
        }

        return $terms;
        
    }
}

<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class Entity
{

    public function getEntityDetail($database, $entityId)
    {
        $entity = DB::connection($database)->table('m_entitys')->where('statusenabled', true)->where('id', $entityId)->first();
        if (!$entity) {
            return false;
        }
        return $entity;
    }

    public function getEntity($database, $id = null, $code = null)
    {
        $data = DB::connection($database)->table('m_entitys as me')
            ->leftJoin('m_hirarkis as mh', 'mh.id', '=', 'me.hirarki_id')
            ->leftJoin('m_entitys as me2', 'me2.id', '=', 'me.parent_id')
            ->select('me.id', 'me.level', 'me.name', 'me.code', 'me.hirarki_id', 'mh.name as name_hirarky', 'mh.level as level_hirarki', 'me.status',  'me.log_create_at', 'me.log_update_at', 'me.log_delete_at', 'me.log_create_by', 'me.log_update_by', 'me.log_delete_by', 'me.parent_id', 'me2.name as upline_name')
            ->where('me.statusenabled', true)
            ->where('mh.statusenabled', true);
        if ($code) {
            $data = $data->where('me.code', '=', $code);
        }
        if ($id) {
            $data = $data->where('me.id', '=', $id);
        }
        $data = $data->orderBy('me.level', 'asc')->orderBy('me.id', 'asc')->distinct()->get();
        if (count($data) > 0) {
            foreach ($data as $key => $dt) {
                $detail = DB::connection($database)->table('m_entitys_detail as med')
                    ->join('m_divisions as md', 'md.id', '=', 'med.m_divisions_id')
                    ->select('med.id', 'med.m_divisions_id', 'md.name')
                    ->where('med.statusenabled', 't')
                    ->where('med.m_entitys_id', $dt->id)
                    ->orderBy('med.id', 'desc')
                    ->distinct()
                    ->get();

                $data[$key]->detail = $detail;
            }
        }
        return $data;
    }
}

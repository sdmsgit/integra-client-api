<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class DataSet
{

    public function getDataSet($database, $id = null)
    {
        $data = DB::connection($database)->table('m_data_set')
        ->select('id', 'code', 'name')
        ->where('statusenabled', true);
        if ($id) {
            $data = $data->where('id', '=', $id);
        }
        $data = $data->orderBy('code', 'asc');
        $data = $data->distinct();
        $data = $data->get();


        return $data;
    }
}

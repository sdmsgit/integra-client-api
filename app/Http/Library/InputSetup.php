<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class InputSetup
{

    public function getInputSetup($database, $id = null, $year = null)
    {
        $data = DB::connection($database)->table('input_setup as is')
            ->join('history_master_sdms as hms', 'hms.input_setup_id', 'is.id')
            ->select('hms.id', 'is.name', 'is.year', 'is.id_data_set')
            ->where('is.statusenabled', true);
        if ($year) {
            $data = $data->where('is.year', '=', $year);
        }
        if ($id) {
            $data = $data->where('is.id', '=', $id);
        }
        $data = $data->orderBy('is.year', 'desc')->distinct()->get();


        return $data;
    }
}

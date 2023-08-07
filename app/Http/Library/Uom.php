<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class Uom
{

    public function getUom($database, $id = null, $name = null)
    {

        $data = DB::connection($database)->table('m_uom as me')
            ->select('name','code','abbreviation','id')        
            ->where('statusenabled', true);
        if (isset($id) && $id != "" && $id != "undefined") {
            $data = $data->where('me.id', '=', $id);
        }
        if (isset($name) && $name != "" && $name != "undefined") {
            $data = $data->where('me.name', 'ilike', '%' . $name . '%');
        }
        $data = $data->get();

        return $data;
    }
}

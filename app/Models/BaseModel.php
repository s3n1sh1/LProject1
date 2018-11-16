<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class BaseModel extends Model
{
   public $timestamps = false;

    public function scopeNoLock($query)
    {

        $driver = DB::connection()->getConfig("driver");

        if ($driver=="sqlsrv") {
	        return $query->from(DB::raw(self::getTable() . ' with (nolock)'));
    	} else if ($driver=="mysql") { 
	        return $query->from(DB::raw(self::getTable() ));
    	} 
    }
   
}

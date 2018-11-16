<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App;

class cUPLOAD extends BaseController {

    public function UploadFile(Request $request) {

        $Hasil = [];

        $a = $request->file();
        $i = 0;
        foreach($a as $key => $value) {

            $i++;

            $file = $request->file($key);
            
            // $file = $request->file()[$i];
            // $name  = time().'-'.basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).'.'.$file->getClientOriginalExtension();
            
            // $ext = $file->getClientOriginalExtension();
            // $size = $file->getClientSize();
            // $mimetype = $file->getMimeType();

            // $type = $this->getType($ext);
            // Storage::disk('public')->put($name, $file);

            $name = date('Ymd_His_U')."(".$i.")";
            $path = $file->storeAs('public/images', $name);

            $Hasil[$key] = array("Code"=>$key, "Name"=>$name, "Path"=>$path);
        }

        return $Hasil;
    }    

}


/*
Note : 

====================================================
NGINX
    conf
        nginx.conf

menambah sintax ini        
client_max_body_size 100M;
====================================================


====================================================
PHP
    php.ini

aktifin sintax ini
extension=php_fileinfo.dll
====================================================


*/
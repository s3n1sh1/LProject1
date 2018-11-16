<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use DB;

    class A {

        public static $B = '1'; # Static class variable.

        const B = '2'; # Class constant.
        
        public static function B() { # Static class function.
            return '3';
        }

        public function C() { # Static class function.
            return '4';
        }        
    }

class cTEST extends BaseController {

    public function cobaget(Request $request) {

        // dd($request->path());
        dd($request->isMethod('get'));

        $Hasil = $_COOKIE;
        return response()->jSon($Hasil);        

    }

    public function cobapost(Request $request) {

        
        dd($request->method);

        $Hasil = $_COOKIE;
        return response()->jSon($Hasil);        

    }





    public function CobaCoba(Request $request) {
        // http://localhost:8099/myQua/lar/api/cobacoba

// echo A::$B . A::B . A::B(); # Outputs: 123
echo A::B()->C(); # Outputs: 123
        dd('Stop');   

    }



}

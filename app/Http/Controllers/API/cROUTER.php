<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App;

class cROUTER extends BaseController {

    
    public function Call2(Request $request) {
        /*
            http://localhost:8099/laravelwili/index.php/getData2?Controller=LoginController&Method=Show&TUUSER=Admin
        */

        $DataJSon = $this->fnDecrypt($request->Data, "");
        foreach($DataJSon as $row => $value) {  // Begin Looping DataJSon
            $request->request->add(array($row => $value));
        }  // End Looping DataJSon

		$RoutePath = $request->Controller."@".$request->Method;
        $Hasil = App::call('\App\Http\Controllers\API\\'.$RoutePath);
        return $Hasil;
    }    

    public function Call(Request $request) {
        /*
            ini buat panggil semua controllers di "laravel\app\Http\Controllers"
        */
        /*
            cara testing Pake Call2
        */
        // dd($request);
        // echo $request->Data; return;
        $DataJSon = $this->fnDecrypt($request->Data, "");
        // $DataJSon = $request->Data;
        // echo $DataJSon; return;
        // var_dump( $DataJSon ); return;

        foreach($DataJSon as $row => $value) {  // Begin Looping DataJSon
            // echo "<br>".$row." --> ".$value."<br>"; 
            $request->request->add(array($row => $value));
        }  // End Looping DataJSon
        // return;

		$RoutePath = $request->Controller."@".$request->Method;
        // echo "ini (".$RoutePath.")"; return;

		// return App::call('\App\Http\Controllers\\'.$RoutePath);
        $Hasil = App::call('\App\Http\Controllers\API\\'.$RoutePath);
        // echo "ini ".$Hasil; return;
        /*
        Jika ada error di "$Hasil"
        itu artinya di file controller ada yang gak beres..... contoh c_User
        */

        $Hasil = $this->fnEncrypt($Hasil, "");
        return $Hasil;

    }    

    public function Post(Request $request) {
        /*
            ini buat panggil semua controllers di "laravel\app\Http\Controllers"
        */
        /*
            cara testing Pake Call2
        */
        $DataJSon = $this->fnDecrypt($request->params['Data'], "");
        // $DataJSon = $request->Data;
        // echo $DataJSon; return;
        // var_dump( $DataJSon ); return;

        foreach($DataJSon as $row => $value) {  // Begin Looping DataJSon
            // echo "<br>".$row." --> ".$value."<br>"; 
            $request->request->add(array($row => $value));
        }  // End Looping DataJSon
        // return;

        $RoutePath = $request->Controller."@".$request->Method;
        // echo "ini (".$RoutePath.")"; return;

        // return App::call('\App\Http\Controllers\\'.$RoutePath);
        $Hasil = App::call('\App\Http\Controllers\API\\'.$RoutePath);
        // dd($Hasil);
        // echo "ini ".$Hasil; return;
        /*
        Jika ada error di "$Hasil"
        itu artinya di file controller ada yang gak beres..... contoh c_User
        */

        $Hasil = $this->fnEncrypt($Hasil, "");
        return $Hasil;

    }        
}

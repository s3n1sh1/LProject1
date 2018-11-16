<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\BaseController;
use Closure;

class CheckForToken extends BaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->isMethod('get')) {
            $DataJSon = $this->fnDecrypt($request->Data, "");
        } else if ($request->isMethod('post')) {
            // $DataJSon = $this->fnDecrypt($request->params['Data'], "");
            return $next($request);
        }

        $cookiesCode = \DB::connection()->getConfig("host").\DB::getDatabaseName().$DataJSon->AppName.$DataJSon->AppDateInfo;

        $cookiesTokenCode = $this->fnEncryptPassword("token".$cookiesCode);
        $cookiesNameCode = $this->fnEncryptPassword("name".$cookiesCode);
        $cookiesDateCode = $this->fnEncryptPassword("dateInfo".$cookiesCode);

        if($request->isMethod('get')) {
            $token = $request->cookie($cookiesTokenCode);
            $name = $request->cookie($cookiesNameCode);
            $dateInfo = $request->cookie($cookiesDateCode);
        } else if ($request->isMethod('post')) {
            $token = $_COOKIE[$cookiesTokenCode];
            $name = $_COOKIE[$cookiesNameCode];
            $dateInfo = $_COOKIE[$cookiesDateCode];
        }

        $cookiesToken = $this->fnEncryptPassword("ADRWili".$DataJSon->AppToken);
        $cookiesName = $this->fnEncryptPassword($DataJSon->AppUserName.$DataJSon->AppDateInfo);
        $cookiesDate = $this->fnEncryptPassword($DataJSon->AppDateInfo);

        // file_put_contents("token.txt","token: ".$token.", cookiesToken: ".$cookiesToken.", cookiesCode: ".$cookiesCode);

        if ( $token!=$cookiesToken || $name!=$cookiesName || $dateInfo!=$cookiesDate ) {

            $Data = ['success'=>false,
                     'message'=>'token expired! Please Refresh Your Page!',
                     // 'message'=>"  --- token : ". $token." --- ".$DataJSon->AppToken.
                     //            "  --- name : ". $name." --- ".$cookiesName.
                     //            "  --- dateInfo : ".$dateInfo." --- ".$cookiesDate,
                     'dateInfo'=>''];
            $Hasil = json_encode($Data);
            $Hasil = strrev($Hasil);
            $Hasil = base64_encode($Hasil);

            return response()->json($Hasil);
        }

        return $next($request)
        ->withCookie(cookie($cookiesTokenCode, $token, 20))
        ->withCookie(cookie($cookiesNameCode, $name, 20))
        ->withCookie(cookie($cookiesDateCode, $dateInfo, 20));
    }
}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Tblusr;
use App\Models\Tbldsc;
use DB;
use Illuminate\Support\Facades\Storage as Storage;

class cTBLUSR extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TUUSERIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUUSER', 'Username', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUNAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUEMID', 'NIP', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TUREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TU");

        $Filter = [];
        $Sort = [];
        $ColumnGrid = [];

        $TBLUSR = TBLUSR::noLock()
                ->where([
                    ['TUDLFG', '=', '0'],
                  ]);
        $TBLUSR = $this->fnQuerySearchAndPaginate($request, $TBLUSR, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TBLUSR,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TUUSERIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $TBLUSR = TBLUSR::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request)) )
                ->where([
                    ['TUUSERIY', '=', $request->TUUSERIY],
                  ])->get();
        
        $Hasil = $this->fnFillForm(true, $TBLUSR, "");
        return response()->jSon($Hasil);

    }

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TUUSERIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "TUUSER", "Username", "", true, 0, 20);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "TUNAME", "Full Name", "", true, 0, 100);
        $this->fnCrtObjPwd($Obj, true, "2", "Panel1", "TUPSWD", "Password", "", true, 6, 0);
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "TUEMID", "NIP", "", true, 8, 8);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "TUDPFG", "Status", "", "1", "Radio", "DSPLY");        
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "TUREMK", "Remark", "", false, 100);

        // $this->fnCrtObjFle($Obj, true, "0", "Panel2", "TUFOTO", "Profile Picture", "", false, false, ".jpg, .png");

        $this->fnCrtObjDefault($Obj,"TU");

        return response()->jSon($Obj);   
    }



    public function StpTBLUSR ($request) {

        $TBLUSR = json_encode($request->frmTBLUSR);
        $TBLUSR = json_decode($TBLUSR, true);

        $fTBLUAM = json_encode($request->frmTBLUAM);
        $fTBLUAM = json_decode($fTBLUAM, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLUSR", 
                                  "Key"=>['TUUSERIY'], 
                                  "Data"=>$TBLUSR, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $TBLUSR['TUUSERIY'] = $this->fnTBLNOR ($UserName, "TBLUSR");
                DB::table('TBLUSR')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $TBLUSR, $UserName, '1', 
                            ['TUUSERIY','TUUSER','TUNAME','TUPSWD','TUEMID','TUDPFG','TUREMK'], 
                            $UnikNo )
                    );


                $i=0;
                if (is_array($fTBLUAM['TBLUAM'])) {
                    foreach($fTBLUAM['TBLUAM'] as $TBLUAM) {
                        $i++;
                        $TBLUAM['TAUSERIY'] = $TBLUSR['TUUSERIY'];
                        $TBLUAM['TAMENUIY'] = $TBLUAM['TMMENUIY'];
                        $TBLUAM['TAACES'] = implode("",$TBLUAM['HAKAKSES']);
                        DB::table('TBLUAM')
                            ->insert(
                                $this->fnGetSintaxCRUD ( $TBLUAM, $UserName, '1', 
                                    ['TAUSERIY','TAMENUIY','TAACES'], 
                                    $UnikNo )
                            );
                    }                    
                }

                break;
            case "2":
                DB::table('TBLUSR')
                    ->where('TUUSERIY','=',$TBLUSR['TUUSERIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLUSR, $UserName, '2',  
                            ['TUEMID','TUDPFG','TUREMK'], 
                            $UnikNo )
                    );

                DB::table('TBLUAM')
                    ->where('TAUSERIY','=',$TBLUSR['TUUSERIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLUSR, $UserName, '3',  
                            ['TAUSERIY'], 
                            $UnikNo )
                    );

                $i = 0;
                if (is_array($fTBLUAM['TBLUAM'])) {
                    foreach($fTBLUAM['TBLUAM'] as $TBLUAM) {
                        $i++;
                        $TBLUAM['TAUSERIY'] = $TBLUSR['TUUSERIY'];

                        if ($TBLUAM['TANOMRIY']=="") {
                            $TBLUAM['TAUSERIY'] = $TBLUSR['TUUSERIY'];
                            $TBLUAM['TAMENUIY'] = $TBLUAM['TMMENUIY'];
                            $TBLUAM['TAACES'] = implode("",$TBLUAM['HAKAKSES']);
                            DB::table('TBLUAM')
                                ->insert(
                                    $this->fnGetSintaxCRUD ( $TBLUAM, $UserName, '1', 
                                        ['TAUSERIY','TAMENUIY','TAACES'], 
                                        $UnikNo )
                                );
                        } else {
                            $TBLUAM['TAACES'] = implode("",$TBLUAM['HAKAKSES']);
                            DB::table('TBLUAM')
                                ->where('TANOMRIY','=',$TBLUAM['TANOMRIY'])
                                ->update(
                                    $this->fnGetSintaxCRUD ( $TBLUAM, $UserName, '2', 
                                        ['TAACES'], 
                                        $UnikNo )
                                );
                        }
                    }                    
                }

                break;
            case "3":
                DB::table('TBLUSR')
                    ->where('TUUSERIY','=',$TBLUSR['TUUSERIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLUSR, $UserName, '3',  
                            ['TUUSERIY'], 
                            $UnikNo )
                    );

                DB::table('TBLUAM')
                    ->where('TAUSERIY','=',$TBLUSR['TUUSERIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLUSR, $UserName, '3',  
                            ['TAUSERIY'], 
                            $UnikNo )
                    );

                break;

            default:
                return array("success"=> false, "message"=> " No Permision fo this Action!!!");            
                break;
        }
        // return array("success"=> false, "message"=> "coba ssss disini");

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpTBLUSR($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }


    public function SaveDataXXXX(Request $request) {

        $fTBLUSR = json_encode($request->frmTBLUSR);
        $fTBLUSR = json_decode($fTBLUSR, true);

        $fTBLUAM = json_encode($request->frmTBLUAM);
        $fTBLUAM = json_decode($fTBLUAM, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLUSR", 
                                  "Key"=>"TUUSERIY", 
                                  "Data"=>$fTBLUSR, 
                                  "Mode"=>$request->Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        $SqlStm = [];
        switch ($request->Mode) {
            case "1":
                $fTBLUSR['TUPSWD'] = $this->fnEncryptPassword($fTBLUSR['TUPSWD']);

                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"I",
                                        "Data"=>$fTBLUSR,
                                        "Table"=>"TBLUSR",
                                        "Field"=>['TUUSERIY','TUUSER','TUNAME','TUPSWD','TUEMID','TUDPFG','TUREMK'],
                                        "Where"=>[],
                                        "Iy"=>"TUUSERIY"
                                    ));

                foreach($fTBLUAM['TBLUAM'] as $contain) {
                    $grid2 = [];
                    $grid2['TAMENUIY'] = $contain['TMMENUIY'];
                    $grid2['TAACES'] = implode("",$contain['HAKAKSES']);

                    array_push($SqlStm, array(
                                            "UnikNo"=>$UnikNo,
                                            "Mode"=>"I",
                                            "Data"=>$grid2,
                                            "Table"=>"TBLUAM",
                                            "Field"=>['TAUSERIY','TAMENUIY','TAACES'],
                                            "Where"=>[],
                                            "Iy"=>"TANOMRIY",
                                            "IyReff"=>array("TAUSERIY"=>"TUUSERIY")
                                        ));
                }

                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fTBLUSR,
                                        "Table"=>"TBLUSR",
                                        "Field"=>['TUEMID','TUDPFG','TUREMK'],
                                        "Where"=>[['TUUSERIY','=',$fTBLUSR['TUUSERIY']]]
                                    ));

                foreach($fTBLUAM['TBLUAM'] as $contain) {
                    $grid2 = [];
                    $grid2['TAUSERIY'] = $fTBLUSR['TUUSERIY'];
                    $grid2['TAMENUIY'] = $contain['TMMENUIY'];
                    $grid2['TAACES'] = implode("",$contain['HAKAKSES']);

                    if (is_null($contain['TANOMRIY'])) {
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"I",
                                                "Data"=>$grid2,
                                                "Table"=>"TBLUAM",
                                                "Field"=>['TAUSERIY','TAMENUIY','TAACES'],
                                                "Where"=>[],
                                                "Iy"=>"TANOMRIY"
                                            ));
                    } else {
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"U",
                                                "Data"=>$grid2,
                                                "Table"=>"TBLUAM",
                                                "Field"=>['TAACES'],
                                                "Where"=>[['TAMENUIY','=',$grid2['TAMENUIY']],
                                                          ['TAUSERIY','=',$grid2['TAUSERIY']]]
                        ));
                    }

                }

                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>[],
                                        "Table"=>"TBLUAM",
                                        "Field"=>['TAUSERIY'],
                                        "Where"=>[['TAUSERIY','=',$fTBLUSR['TUUSERIY']]],
                                    ));
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>[],
                                        "Table"=>"TBLUSR",
                                        "Field"=>['TUUSERIY'],
                                        "Where"=>[['TUUSERIY','=',$fTBLUSR['TUUSERIY']]],
                                    ));

                break;
        }

        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);
        return response()->jSon($Hasil);
        
    }

    public function getQuery(Request $request) {

        $USERIY = $request->TUUSERIY;
        if(!$USERIY){
            $QUERY = DB::select( 
            DB::raw("
                Select TMMENUIY, '' TANOMRIY, RTRIM(TMMENU) TMMENU, RTRIM(TMACES) TMACES, '' HAKAKSES
                From TBLMNU
                Order By TMNOMR Asc
            ") );
        } else {
            $QUERY = DB::select( 
            DB::raw("
                Select TMMENUIY, TANOMRIY, RTRIM(TMMENU) TMMENU, RTRIM(TMACES) TMACES, IfNull(TAACES,'') HAKAKSES
                From TBLMNU
                Left Join TBLUAM On TAMENUIY = TMMENUIY And TAUSERIY = '".$USERIY."'
                Order By TMNOMR Asc
            ") );
        }

        $TBLSYS = DB::select( 
        DB::raw("
            Select RTRIM(TSSYCD) value, RTRIM(TSSYNM) label From TBLSYS
            Where TSDSCD = 'MODE'
        ") );

        $menuaccess = [];
        foreach($QUERY as $row) {
            $a = str_split($row->TMACES);
            $b = array_filter($TBLSYS, function($c) use($a) {
                foreach($a as $row2) {
                    if($c->value == $row2) {
                        return true;
                    }
                }
            });

            $HakAkses = array();
            if($USERIY){
                $HakAkses = str_split(rtrim($row->HAKAKSES));
            }

            $menuaccess[] = array("TMMENUIY"=>$row->TMMENUIY,
                                  "TANOMRIY"=>$row->TANOMRIY,
                                  "TMMENU"=>$row->TMMENU,
                                  "TMACES"=>array_values($b),
                                  "HAKAKSES"=>$HakAkses
            );
        }

        return response()->jSon($menuaccess);
    }

}

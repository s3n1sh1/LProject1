<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Trfind;
use App\Models\Mmbagn;
use DB;

class cTRFIND_A extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '101', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TFFINDIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFFINO', 'Finding No', 100);
        $this->fnCrtColGrid($Obj, "dtp", 1, 1, '', 'TFDATE', 'Date', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFSUBJ', 'Subject', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'MANAME', 'Area', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'MBNAME', 'Bagian', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TFDESC', 'Description', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TFSOLU', 'Solution', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TF");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'TFDATE','direction'=>'desc');
        $Sort[] = array('name'=>'TFFINO','direction'=>'desc');
        $ColumnGrid = [];

        $TRFIND = TRFIND::noLock()
                ->leftJoin($this->fnDBRaw("Table","MMAREA"), 'MAAREAIY', '=', 'TFAREAIY')    
                ->leftJoin($this->fnDBRaw("Table","MMBAGN"), 'MBBAGNIY', '=', 'TFBAGNIY')  
                ->where([
                    ['TFDLFG', '=', '0'],
                  ])
                ->whereNull('TFACDT');
        $TRFIND = $this->fnQuerySearchAndPaginate($request, $TRFIND, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TRFIND,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TFFINDIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        if($request->Mode != "6") {
            $a = $this->fnGetRec("TRFIND","TFFINO,TFACDT","TFFINDIY",$request->TFFINDIY, "");
            if(!is_null($a->TFACDT)) {
                $Hasil = $this->fnFillForm(false, "", "Finding No. ".$a->TFFINO." already accepted");
                return response()->jSon($Hasil);        
            }
        }

        $BAGN = MMBAGN::noLock()
                           ->select(
                                DB::raw('MBBAGNIY as TFBAGNIY_OLD'),
                                DB::raw('MBBAGN as MBBAGN_OLD'),
                                DB::raw('MBNAME as MBNAME_OLD'))
                           ->where('MBDLFG', '=', '0');

        $TRFIND = TRFIND::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request), "", "TFTIPE,TRFIN1") )
                ->leftJoin($this->fnDBRaw("Table","MMAREA"), 'MAAREAIY', '=', 'TFAREAIY')    
                ->leftJoin($this->fnDBRaw("Table","MMBAGN"), 'MBBAGNIY', '=', 'TFBAGNIY')  
                ->leftJoin($this->fnDBRaw("Table","MMSTAF"), 'MCSTAFIY', '=', 'TFSTAFIY')  
                ->leftJoinSub($BAGN, 'BAGN', 'TFBAGNIY_OLD', '=', 'TFBAGNIY')     
                ->where([
                    ['TFFINDIY', '=', $request->TFFINDIY],
                  ])
                ->get();

        $TRFIN1 = DB::table($this->fnDBRaw("Table","TRFIN1"))
                ->select( 'T1FILE' )
                ->where([
                    ['T1FINDIY', '=', $request->TFFINDIY],
                    ['T1DLFG', '=', '0'],
                  ])
                ->get();

        $i = 0;
        $TRFIN1_HASIL = [];
        foreach($TRFIN1 as $key => $field) {
            array_push($TRFIN1_HASIL, $this->fnGenDataFile($field->T1FILE));

            $i++;
        }
        $TRFIND[0]['TRFIN1'] = $TRFIN1_HASIL;
        $TRFIND[0]['TFTIPE'] = "A";

        $Hasil = $this->fnFillForm(true, $TRFIND, "");
        return response()->jSon($Hasil);        

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TFFINDIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "Panel1", "TFFINO", "Finding No", "", false, 0, 0);
        $this->fnCrtObjDtp($Obj, true, "3", "Panel1", "TFDATE", "Date", "", true);        
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFAREAIY", "MAAREA", "MANAME", "Area", "", true, "MMAREA");
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFBAGNIY_OLD", "MBBAGN_OLD", "MBNAME_OLD", "Bagian", "", true, "MMBAGN");

        $this->fnCrtObjTxt($Obj, true, "3", "Panel3", "TFSUBJ", "Subject", "", true);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFDESC", "Description", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFSOLU", "Solution", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFREMK", "Remark", "", false, 100);
        $this->fnCrtObjFle($Obj, true, "3", "Panel3", "TRFIN1", "Document", "", false, true, ".jpg, .png");

        $this->fnCrtObjRad($Obj, true, "0", "Panel41", "TFTIPE", "Type Accept", "", "A", "Radio", "TPAC");  
        $this->fnCrtObjDtp($Obj, true, "0", "Panel42", "TFACDT", "Acceptance Date", "", true);         
        $this->fnCrtObjPop($Obj, true, "0", "Panel42", "TFSTAFIY", "MCSTAF", "MCNAME", "Account Officer", "", false, "MMSTAF");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel42", "TFRELO", "Responsible Officer", "", false, 100);   
        $this->fnCrtObjRmk($Obj, true, "0", "Panel42", "TFACPL", "Planning Action", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel42", "TFACRM", "Acceptance Remark", "", false, 100); 
        $this->fnCrtObjPop($Obj, true, "0", "Panel43", "TFBAGNIY", "MBBAGN", "MBNAME", "Bagian", "", true, "MMBAGN"); 
        $this->fnCrtObjRmk($Obj, true, "0", "Panel43", "TFRDRM", "Redirect Remark", "", false, 100);     

        $this->fnCrtObjDefault($Obj,"TF");

        return response()->jSon($Obj);
    }


    public function StpTRFIND ($request) {

        $TRFIND = json_encode($request->frmTRFIND_A);
        $TRFIND = json_decode($TRFIND, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TRFIND", 
                                  "Key"=>['TFFINDIY'], 
                                  "Data"=>$TRFIND, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"TRFIND_A", 
                                  "FieldTransDate"=>"TFDATE"));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "2":
                /*
                    Note : tidak perlu check sudah solution atau belum
                           karena akan kena BFCS
                */
                if ($TRFIND["TFTIPE"] === "A") {
                    $FinalField = $this->fnGetSintaxCRUD ($TRFIND, $UserName, '2',  
                                        ['TFACDT','TFSTAFIY','TFRELO','TFACPL','TFACRM'], 
                                        $UnikNo );
                } else {
                    $FinalField = $this->fnGetSintaxCRUD ($TRFIND, $UserName, '2',  
                                        ['TFBAGNIY','TFRDRM'], 
                                        $UnikNo );
                }

                DB::table('TRFIND')
                    ->where('TFFINDIY','=',$TRFIND['TFFINDIY'])
                    ->update($FinalField);

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
                        return $this->StpTRFIND($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

    // public function SaveDataxxxxxx(Request $request) {

    //     $fTRFIND = json_encode($request->frmTRFIND_A);
    //     $fTRFIND = json_decode($fTRFIND, true);

    //     $Delimiter = "";
    //     $UnikNo = $this->fnGenUnikNo($Delimiter);

    //     $HasilCheckBFCS = $this->fnCheckBFCS (
    //                         array("Table"=>"TRFIND", 
    //                               "Key"=>"TFFINDIY", 
    //                               "Data"=>$fTRFIND, 
    //                               "Mode"=>$request->Mode,
    //                               "Menu"=>"", 
    //                               "FieldTransDate"=>""));
    //     if (!$HasilCheckBFCS["success"]) {
    //         return $HasilCheckBFCS;
    //     }        

    //     $SqlStm = [];
    //     switch ($request->Mode) {
    //         case "1":
    //             $Hasil = array("success"=> false, "message"=> " No Permision fo this Action!!!");
    //             return response()->jSon($Hasil);
    //             break;
    //         case "2":
    //             // $fTRFIND['TMACES'] = implode("",$fTRFIND['TMACES']);
    //             if ($fTRFIND["TFTIPE"] === "A") {
    //                 array_push($SqlStm, array(
    //                                         "UnikNo"=>$UnikNo,
    //                                         "Mode"=>"U",
    //                                         "Data"=>$fTRFIND,
    //                                         "Table"=>"TRFIND",
    //                                         "Field"=>['TFACDT','TFSTAFIY','TFRELO','TFACPL','TFACRM'],
    //                                         "Where"=>[['TFFINDIY','=',$fTRFIND['TFFINDIY']]],
    //                                     ));
    //             } else {
    //                 array_push($SqlStm, array(
    //                                             "UnikNo"=>$UnikNo,
    //                                             "Mode"=>"U",
    //                                             "Data"=>$fTRFIND,
    //                                             "Table"=>"TRFIND",
    //                                             "Field"=>['TFBAGNIY','TFRDRM'],
    //                                             "Where"=>[['TFFINDIY','=',$fTRFIND['TFFINDIY']]],
    //                                         ));
    //             }
    //             break;
    //         case "3":                
    //             $Hasil = array("success"=> false, "message"=> " No Permision fo this Action!!!");
    //             return response()->jSon($Hasil);
    //             break;
    //     }


    //     $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);
    //     // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
    //     return response()->jSon($Hasil);


    // }


}

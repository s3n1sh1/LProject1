<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Trfind;
use DB;

class cTRFIND_S extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '101', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TFFINDIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFFINO', 'Finding No', 100);
        $this->fnCrtColGrid($Obj, "dtp", 1, 1, '', 'TFDATE', 'Date', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFSUBJ', 'Subject', 100);
        $this->fnCrtColGrid($Obj, "dtp", 1, 1, '', 'TFACDT', 'Acceptance Date', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'MANAME', 'Area', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'MBNAME', 'Bagian', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'MCNAME', 'Account Officer', 100);
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
                ->leftJoin($this->fnDBRaw("Table","MMSTAF"), 'MCSTAFIY', '=', 'TFSTAFIY')
                ->where([
                    ['TFDLFG', '=', '0'],
                  ])
                ->whereNull('TFSLDT')  
                ->whereNotNull('TFACDT');                
        $TRFIND = $this->fnQuerySearchAndPaginate($request, $TRFIND, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TRFIND,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TFFINDIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $TRFIND = TRFIND::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request), "", "TRFIN1, TRFIN2") )
                ->leftJoin($this->fnDBRaw("Table","MMAREA"), 'MAAREAIY', '=', 'TFAREAIY')    
                ->leftJoin($this->fnDBRaw("Table","MMBAGN"), 'MBBAGNIY', '=', 'TFBAGNIY')    
                ->leftJoin($this->fnDBRaw("Table","MMSTAF"), 'MCSTAFIY', '=', 'TFSTAFIY')
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

        $TRFIN2 = DB::table($this->fnDBRaw("Table","TRFIN2"))
                ->select( 'T2FILE' )
                ->where([
                    ['T2FINDIY', '=', $request->TFFINDIY],
                    ['T2DLFG', '=', '0'],
                  ])
                ->get();

        $i = 0;
        $TRFIN2_HASIL = [];
        foreach($TRFIN2 as $key => $field) {
            array_push($TRFIN2_HASIL, $this->fnGenDataFile($field->T2FILE));

            $i++;
        }
        $TRFIND[0]['TRFIN2'] = $TRFIN2_HASIL;

        $Hasil = $this->fnFillForm(true, $TRFIND, "");
        return response()->jSon($Hasil);        

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TFFINDIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "Panel1", "TFFINO", "Finding No", "", false, 0, 0);
        $this->fnCrtObjDtp($Obj, true, "3", "Panel1", "TFDATE", "Date", "", true);        
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFAREAIY", "MAAREA", "MANAME", "Area", "", true, "MMAREA");
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFBAGNIY", "MBBAGN", "MBNAME", "Bagian", "", true, "MMBAGN");

        $this->fnCrtObjTxt($Obj, true, "3", "Panel3", "TFSUBJ", "Subject", "", true);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFDESC", "Description", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFSOLU", "Solution", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFREMK", "Remark", "", false, 100);


        $this->fnCrtObjDtp($Obj, true, "3", "Panel41", "TFACDT", "Acceptance Date", "", true);         
        $this->fnCrtObjPop($Obj, true, "3", "Panel41", "TFSTAFIY", "MCSTAF", "MCNAME", "Account Officer", "", false, "MMSTAF");
        // $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFRELO", "Responsible Officer", "", false, 100);   
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFACPL", "Planning Action", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFACRM", "Acceptance Remark", "", false, 100); 
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFRDRM", "Redirect Remark", "", false, 100);  
        $this->fnCrtObjFle($Obj, true, "3", "Panel41", "TRFIN1", "Document", "", false, true, ".jpg, .png");   

        $this->fnCrtObjDtp($Obj, true, "0", "Panel5", "TFSLDT", "Solution Date", "", true);   
        $this->fnCrtObjRmk($Obj, true, "0", "Panel5", "TFRELO", "Responsible Officer", "", true, 100);   
        $this->fnCrtObjRmk($Obj, true, "0", "Panel5", "TFSLRM", "Solution Remark", "", false, 100);  
        $this->fnCrtObjRmk($Obj, true, "0", "Panel6", "TFACTN", "Action", "", true, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel6", "TFRSLT", "Result", "", true, 100); 
        $this->fnCrtObjFle($Obj, true, "0", "Panel6", "TRFIN2", "Document", "", false, true, ".jpg, .png");        

        $this->fnCrtObjDefault($Obj,"TF");

        return response()->jSon($Obj);
    }


    public function StpTRFIND ($request) {

        $TRFIND = json_encode($request->frmTRFIND_S);
        $TRFIND = json_decode($TRFIND, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = $request->AppUserName;
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TRFIND", 
                                  "Key"=>['TFFINDIY'], 
                                  "Data"=>$TRFIND, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"TRFIND_S", 
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
                DB::table('TRFIND')
                    ->where('TFFINDIY','=',$TRFIND['TFFINDIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TRFIND, $UserName, '2',  
                            ['TFSLDT','TFRELO','TFSLRM','TFACTN','TFRSLT'], 
                            $UnikNo )
                    );
                $i=0;
                // var_dump($fTRFIND['TRFIN1']);
                if (is_array($TRFIND['TRFIN2'])) {
                    foreach($TRFIND['TRFIN2'] as $TRFIN2) {
                        $i++;
                        $TRFIN2['T2FINDIY'] = $TRFIND['TFFINDIY'];
                        $TRFIN2['T2NOMRIY'] = $this->fnTBLNOR ($UserName, "TRFIN2");
                        $TRFIN2['T2FILE'] = $this->fnGenBinaryFile($request->File, 'TRFIN2_'.$i);
                        DB::table('TRFIN2')
                            ->insert(
                                $this->fnGetSintaxCRUD ( $TRFIN2, $UserName, '1', 
                                    ['T2NOMRIY','T2FINDIY','T2FILE'], 
                                    $UnikNo )
                            );
                    }                    
                }
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
                 , $request->AppUserName);
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

    // public function SaveDataXXXX(Request $request) {

    //     $fTRFIND = json_encode($request->frmTRFIND_S);
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
    //             array_push($SqlStm, array(
    //                                     "UnikNo"=>$UnikNo,
    //                                     "Mode"=>"U",
    //                                     "Data"=>$fTRFIND,
    //                                     "Table"=>"TRFIND",
    //                                     "Field"=>['TFSLDT','TFRELO','TFSLRM','TFACTN','TFRSLT'],
    //                                     "Where"=>[['TFFINDIY','=',$fTRFIND['TFFINDIY']]],
    //                                 ));


    //             if (is_array($fTRFIND['TRFIN2'])) {
    //                 array_push($SqlStm, array(
    //                                         "UnikNo"=>$UnikNo,
    //                                         "Mode"=>"D",
    //                                         "Data"=>[],
    //                                         "Table"=>"TRFIN1",
    //                                         "Field"=>['T1FINDIY'],
    //                                         "Where"=>[['T1FINDIY','=',$fTRFIND['TFFINDIY']]],
    //                                     ));

    //                 $i=0;
    //                 foreach($fTRFIND['TRFIN2'] as $fTRFIN2) {
    //                     $i++;
    //                     $fTRFIN2['T2FILE'] = $this->fnGenBinaryFile($request->File, 'TRFIN2_'.$i);
    //                     $fTRFIN2['T2FINDIY'] = $fTRFIND['TFFINDIY'];
    //                     array_push($SqlStm, array(
    //                                             "UnikNo"=>$UnikNo,
    //                                             "Mode"=>"I",
    //                                             "Data"=>$fTRFIN2,
    //                                             "Table"=>"TRFIN2",
    //                                             "Field"=>['T2NOMRIY','T2FINDIY','T2FILE'],
    //                                             "Where"=>[],
    //                                             "Iy"=>"T2NOMRIY"
    //                                         ));

    //                 }
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

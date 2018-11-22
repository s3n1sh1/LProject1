<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Trfind;
use DB;

class cTRFIND_C extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
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
                  ]);
        $TRFIND = $this->fnQuerySearchAndPaginate($request, $TRFIND, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TRFIND,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TFFINDIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {
        
        // dd($request);

        if($request->Mode != "6") {
            $a = $this->fnGetRec("TRFIND","TFFINO,TFACDT","TFFINDIY",$request->TFFINDIY, "");
            if(!is_null($a->TFACDT)) {
                $Hasil = $this->fnFillForm(false, "", "Finding No. ".$a->TFFINO." already accepted");
                return response()->jSon($Hasil);        
            }
        }

        $TRFIND = TRFIND::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request), "", "TRFIN1") )
                ->leftJoin($this->fnDBRaw("Table","MMAREA"), 'MAAREAIY', '=', 'TFAREAIY')    
                ->leftJoin($this->fnDBRaw("Table","MMBAGN"), 'MBBAGNIY', '=', 'TFBAGNIY')    
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

        $Hasil = $this->fnFillForm(true, $TRFIND, "");
        return response()->jSon($Hasil);        

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TFFINDIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "Panel1", "TFFINO", "Finding No", "", false, 0, 0);
        $this->fnCrtObjDtp($Obj, true, "2", "Panel1", "TFDATE", "Date", "", true);
        $this->fnCrtObjPop($Obj, true, "0", "Panel2", "TFAREAIY", "MAAREA", "MANAME", "Area", "", true, "MMAREA");
        $this->fnCrtObjPop($Obj, true, "0", "Panel2", "TFBAGNIY", "MBBAGN", "MBNAME", "Bagian", "", true, "MMBAGN");

        $this->fnCrtObjRmk($Obj, true, "0", "Panel3", "TFSUBJ", "Subject", "", true, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel3", "TFDESC", "Description", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel3", "TFSOLU", "Solution", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel3", "TFREMK", "Remark", "", false, 100);
        $this->fnCrtObjFle($Obj, true, "0", "Panel4", "TRFIN1", "Document", "", false, true, ".jpg, .png");

        $this->fnCrtObjDefault($Obj,"TF");

        return response()->jSon($Obj);
    }



    public function SaveData(Request $request) {

        $fTRFIND = json_encode($request->frmTRFIND_C);
        $fTRFIND = json_decode($fTRFIND, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TRFIND", 
                                  "Key"=>"TFFINDIY", 
                                  "Data"=>$fTRFIND, 
                                  "Mode"=>$request->Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }        

        $SqlStm = [];
        switch ($request->Mode) {
            case "1":
                $NoUrut = $this->fnGetRec("TBLNOR", "TNNOUR", "TNTABL", "TRFIND", "");                
                $No = isset($NoUrut->TNNOUR) ? $NoUrut->TNNOUR+1 : 1;
                $fTRFIND['TFFINO'] = "5S-".substr("0000000".$No,-5);

                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"I",
                                        "Data"=>$fTRFIND,
                                        "Table"=>"TRFIND",
                                        "Field"=>['TFFINDIY','TFFINO','TFDATE','TFAREAIY','TFBAGNIY','TFSUBJ','TFDESC','TFSOLU','TFREMK'],
                                        "Where"=>[],
                                        "Iy"=>"TFFINDIY"
                                    ));
                $i=0;
                // var_dump($fTRFIND['TRFIN1']);
                if (is_array($fTRFIND['TRFIN1'])) {
                    foreach($fTRFIND['TRFIN1'] as $fTRFIN1) {
                        $i++;
                        $fTRFIN1['T1FILE'] = $this->fnGenBinaryFile($request->File, 'TRFIN1_'.$i);
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"I",
                                                "Data"=>$fTRFIN1,
                                                "Table"=>"TRFIN1",
                                                "Field"=>['T1NOMRIY','T1FINDIY','T1FILE'],
                                                "Where"=>[],
                                                "Iy"=>"T1NOMRIY",
                                                "IyReff"=>array("T1FINDIY"=>"TFFINDIY")
                                            ));

                    }                    
                }


                break;
            case "2":
                // $fTRFIND['TMACES'] = implode("",$fTRFIND['TMACES']);
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fTRFIND,
                                        "Table"=>"TRFIND",
                                        "Field"=>['TFAREAIY','TFBAGNIY','TFSUBJ','TFDESC','TFSOLU','TFREMK'],
                                        "Where"=>[['TFFINDIY','=',$fTRFIND['TFFINDIY']]],
                                    ));

                if (is_array($fTRFIND['TRFIN1'])) {
                    array_push($SqlStm, array(
                                            "UnikNo"=>$UnikNo,
                                            "Mode"=>"D",
                                            "Data"=>[],
                                            "Table"=>"TRFIN1",
                                            "Field"=>['T1FINDIY'],
                                            "Where"=>[['T1FINDIY','=',$fTRFIND['TFFINDIY']]],
                                        ));

                    $i=0;
                    foreach($fTRFIND['TRFIN1'] as $fTRFIN1) {
                        $i++;
                        $fTRFIN1['T1FILE'] = $this->fnGenBinaryFile($request->File, 'TRFIN1_'.$i);
                        $fTRFIN1['T1FINDIY'] = $fTRFIND['TFFINDIY'];
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"I",
                                                "Data"=>$fTRFIN1,
                                                "Table"=>"TRFIN1",
                                                "Field"=>['T1NOMRIY','T1FINDIY','T1FILE'],
                                                "Where"=>[],
                                                "Iy"=>"T1NOMRIY"
                                            ));

                    }
                }
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"DD",
                                        "Data"=>[],
                                        "Table"=>"TRFIND",
                                        "Field"=>['TFFINDIY'],
                                        "Where"=>[['TFFINDIY','=',$fTRFIND['TFFINDIY']]],
                                    ));
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"DD",
                                        "Data"=>[],
                                        "Table"=>"TRFIN1",
                                        "Field"=>['T1FINDIY'],
                                        "Where"=>[['T1FINDIY','=',$fTRFIND['TFFINDIY']]],
                                    ));

                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);


    }

//     public function SaveData(Request $request) {

//         // BEGIN HEADER
//         $arr = json_encode($request->Form);
//         $arr = json_decode($arr, true);
//         $Delimiter = "";
//         $UnikNo = $this->fnGenUnikNo($Delimiter);
//         // $s = $this->fnGetSyntax($arr, 'StpTRFIND_C', $request->Mode, $UnikNo);        
//         $s = $this->fnGetSyntax($request->Source, $request->Username, $request->Mode, 'StpTRFIND_C', $arr, $UnikNo);  

//         $SQLSTM = $s['all'];
//         // END HEADER
// // file_put_contents("abc.txt", $SQLSTM);
//         // BEGIN FILE
//         if($request->Mode != '3') {
//             $arr2 = json_encode($request->File);
//             $arr2 = json_decode($arr2, true);
//             $i=0;

//             if(is_array($arr2)){
//                 $grid2 = [];
//                 foreach($arr2 as $contain) {
//                     $grid2 = [];

//                     $grid2['T1FINDIY'] = array('Value'=>$arr['TFFINDIY']['Value'],'Tipe'=>'txt');
//                     $grid2['T1FILE'] = array('Value'=>$this->fnGenBinaryFile($request->File, 'TRFIN1_'.$i),'Tipe'=>'txt');

//                     // $s = $this->fnGetSyntax($grid2, 'StpTRFIN1', $request->Mode, $UnikNo);
//                     $s = $this->fnGetSyntax($request->Source, $request->Username, '1', 'StpTRFIN1', $grid2, $UnikNo);  
//                     $SQLSTM .= $Delimiter;
//                     $SQLSTM .= $s['all'];

//                     $i++;
//                 }
//             }
//         }
//         // END FILE
// // file_put_contents("def.txt", $SQLSTM);
//         $HASIL = $this->fnSetExecuteQuery($SQLSTM,$Delimiter);

//         return response()->jSon($HASIL);
  
//     }

}

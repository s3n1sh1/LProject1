<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmprof;
use DB;

class cMMPROF extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MFPCNOIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFPCNO', 'Profit Center Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFNAME', 'Profit Center Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFDIVI', 'Divisi Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYNM', 'Divisi Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MF");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MFPCNO','direction'=>'asc');
        $ColumnGrid = [];

        $MMPROF = MMPROF::noLock()
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MFDIVI')  
                ->where([
                    ['MFDLFG', '=', '0'],
                  ]);
        $MMPROF = $this->fnQuerySearchAndPaginate($request, $MMPROF, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMPROF,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MFPCNOIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMPROF = MMPROF::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MFDIVI')  
                ->where([
                    ['MFPCNOIY', '=', $request->MFPCNOIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMPROF, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MFPCNOIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MFPCNO", "Profit Center Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MFNAME", "Profit Center Name", "", true, 0, 0);        
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "MFDIVI", "TSSYCD", "TSSYNM", "Divisi", "", true, "TBLSYS_DIVI", false, 1);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MFDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MFREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MF");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMPROF = json_encode($request->frmMMPROF);
        $fMMPROF = json_decode($fMMPROF, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMPROF", 
                                  "Key"=>['MFPCNOIY'], 
                                  "Data"=>$fMMPROF, 
                                  "Mode"=>$request->Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return response()->jSon($HasilCheckBFCS);
        }


        $SqlStm = [];
        switch ($request->Mode) {
            case "1":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"I",
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFPCNOIY','MFPCNO','MFNAME','MFDIVI','MFDPFG','MFREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MFPCNOIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFNAME','MFDPFG','MFREMK'],
                                        "Where"=> [['MFPCNOIY','=',$fMMPROF['MFPCNOIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFPCNOIY'],
                                        "Where"=>[['MFPCNOIY','=',$fMMPROF['MFPCNOIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

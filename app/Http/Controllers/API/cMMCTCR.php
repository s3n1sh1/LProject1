<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmctcr;
use DB;

class cMMCTCR extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MCCCNOIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCCCNO', 'Cost Center Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCNAME', 'Cost Center Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFNAME', 'Profit Center', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCDEPT', 'Department Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYNM', 'Department Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MC");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MCCCNO','direction'=>'asc');
        $ColumnGrid = [];

        $MMCTCR = MMCTCR::noLock()
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MCDEPT')
                ->leftJoin($this->fnDBRaw("Table","MMPROF"), 'MFPCNOIY', '=', 'MCPCNOIY')
                ->where([
                    ['MFDLFG', '=', '0'],
                  ]);
        $MMCTCR = $this->fnQuerySearchAndPaginate($request, $MMCTCR, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMCTCR,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MCCCNOIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMCTCR = MMCTCR::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MCDEPT')
                ->leftJoin($this->fnDBRaw("Table","MMPROF"), 'MFPCNOIY', '=', 'MCPCNOIY')
                ->where([
                    ['MCCCNOIY', '=', $request->MCCCNOIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMCTCR, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MCCCNOIY", "IY", "", false);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "MCPCNOIY", "MFPCNO", "MFNAME", "Profit Center", "", true, "MMPROF", true, 1);   
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MCCCNO", "Cost Center Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MCNAME", "Cost Center Name", "", true, 0, 0);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "MCDEPT", "TSSYCD", "TSSYNM", "Department", "", true, "TBLSYS_DEPT", false, 1);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MCDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MCREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MC");

        return response()->jSon($Obj);   
    }



    public function SaveData(Request $request) {


        $fMMCTCR = json_encode($request->frmMMCTCR);
        $fMMCTCR = json_decode($fMMCTCR, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMCTCR", 
                                  "Key"=>['MCCCNOIY'], 
                                  "Data"=>$fMMCTCR, 
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
                                        "Data"=>$fMMCTCR,
                                        "Table"=>"MMCTCR",
                                        "Field"=>['MCCCNOIY','MCCCNO','MCNAME','MCPCNOIY','MCDEPT','MCDPFG','MCREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MCCCNOIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMCTCR,
                                        "Table"=>"MMCTCR",
                                        "Field"=>['MCNAME','MCDPFG','MCREMK'],
                                        "Where"=> [['MCCCNOIY','=',$fMMCTCR['MCCCNOIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMCTCR,
                                        "Table"=>"MMCTCR",
                                        "Field"=>['MCCCNOIY'],
                                        "Where"=>[['MCCCNOIY','=',$fMMCTCR['MCCCNOIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

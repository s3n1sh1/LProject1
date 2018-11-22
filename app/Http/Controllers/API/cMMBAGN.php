<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmbagn;
use DB;

class cMMBAGN extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MBBAGNIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBBAGN', 'Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBNAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MB");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MBBAGN','direction'=>'asc');
        $ColumnGrid = [];

        $MMBAGN = MMBAGN::noLock()
                ->where([
                    ['MBDLFG', '=', '0'],
                  ]);
        $MMBAGN = $this->fnQuerySearchAndPaginate($request, $MMBAGN, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMBAGN,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MBBAGNIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMBAGN = MMBAGN::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->where([
                    ['MBBAGNIY', '=', $request->MBBAGNIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMBAGN, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MBBAGNIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MBBAGN", "Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MBNAME", "Name", "", true, 0, 100);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MBDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MBREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MB");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMBAGN = json_encode($request->frmMMBAGN);
        $fMMBAGN = json_decode($fMMBAGN, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMBAGN", 
                                  "Key"=>['MBBAGNIY'], 
                                  "Data"=>$fMMBAGN, 
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
                                        "Data"=>$fMMBAGN,
                                        "Table"=>"MMBAGN",
                                        "Field"=>['MBBAGNIY','MBBAGN','MBNAME','MBDPFG','MBREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MBBAGNIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMBAGN,
                                        "Table"=>"MMBAGN",
                                        "Field"=>['MBNAME','MBDPFG','MBREMK'],
                                        "Where"=> [['MBBAGNIY','=',$fMMBAGN['MBBAGNIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMBAGN,
                                        "Table"=>"MMBAGN",
                                        "Field"=>['MBBAGNIY'],
                                        "Where"=>[['MBBAGNIY','=',$fMMBAGN['MBBAGNIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

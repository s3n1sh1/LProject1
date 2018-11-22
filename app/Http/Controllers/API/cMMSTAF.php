<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmstaf;
use DB;

class cMMSTAF extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MCSTAFIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCSTAF', 'N I P', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCNAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCTITL', 'Title', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MC");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MCSTAF','direction'=>'asc');
        $ColumnGrid = [];

        $MMSTAF = MMSTAF::noLock()
                ->where([
                    ['MCDLFG', '=', '0'],
                  ]);
        $MMSTAF = $this->fnQuerySearchAndPaginate($request, $MMSTAF, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMSTAF,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MCSTAFIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMSTAF = MMSTAF::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->where([
                    ['MCSTAFIY', '=', $request->MCSTAFIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMSTAF, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MCSTAFIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MCSTAF", "N I P", "", true, 0, 12, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MCNAME", "Name", "", true, 0, 100);
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MCTITL", "Title", "", true, 0, 100);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MCDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MCREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MC");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMSTAF = json_encode($request->frmMMSTAF);
        $fMMSTAF = json_decode($fMMSTAF, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMSTAF", 
                                  "Key"=>['MCSTAFIY'], 
                                  "Data"=>$fMMSTAF, 
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
                                        "Data"=>$fMMSTAF,
                                        "Table"=>"MMSTAF",
                                        "Field"=>['MCSTAFIY','MCSTAF','MCNAME','MCTITL','MCDPFG','MCREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MCSTAFIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMSTAF,
                                        "Table"=>"MMSTAF",
                                        "Field"=>['MCNAME','MCTITL','MCDPFG','MCREMK'],
                                        "Where"=> [['MCSTAFIY','=',$fMMSTAF['MCSTAFIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMSTAF,
                                        "Table"=>"MMSTAF",
                                        "Field"=>['MCSTAFIY'],
                                        "Where"=>[['MCSTAFIY','=',$fMMSTAF['MCSTAFIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmarea;
use DB;

class cMMAREA extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MAAREAIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MAAREA', 'Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MANAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MAREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MA");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MAAREA','direction'=>'asc');
        $ColumnGrid = [];

        $MMAREA = MMAREA::noLock()
                ->where([
                    ['MADLFG', '=', '0'],
                  ]);
        $MMAREA = $this->fnQuerySearchAndPaginate($request, $MMAREA, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMAREA,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MAAREAIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMAREA = MMAREA::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->where([
                    ['MAAREAIY', '=', $request->MAAREAIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMAREA, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MAAREAIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MAAREA", "Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MANAME", "Name", "", true, 0, 0);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MADPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MAREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MA");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMAREA = json_encode($request->frmMMAREA);
        $fMMAREA = json_decode($fMMAREA, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMAREA", 
                                  "Key"=>['MAAREAIY'], 
                                  "Data"=>$fMMAREA, 
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
                                        "Data"=>$fMMAREA,
                                        "Table"=>"MMAREA",
                                        "Field"=>['MAAREAIY','MAAREA','MANAME','MADPFG','MAREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MAAREAIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMAREA,
                                        "Table"=>"MMAREA",
                                        "Field"=>['MANAME','MADPFG','MAREMK'],
                                        "Where"=> [['MAAREAIY','=',$fMMAREA['MAAREAIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMAREA,
                                        "Table"=>"MMAREA",
                                        "Field"=>['MAAREAIY'],
                                        "Where"=>[['MAAREAIY','=',$fMMAREA['MAAREAIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

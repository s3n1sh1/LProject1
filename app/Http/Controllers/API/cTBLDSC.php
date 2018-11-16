<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Tbldsc;
use DB;

class cTBLDSC extends BaseController {


    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TDDSCD', 'Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TDDSNM', 'Description', 100);
        $this->fnCrtColGrid($Obj, "num", 1, 1, '', 'TDLGTH', 'Panjang Karakter', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TDREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TD");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'TDDSCD','direction'=>'asc');
        $ColumnGrid = [];

        $TBLDSC = TBLDSC::noLock()
                ->where([
                    ['TDDLFG', '=', '0'],
                  ]);
        $TBLDSC = $this->fnQuerySearchAndPaginate($request, $TBLDSC, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TBLDSC,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TDDSCD');

        return response()->jSon($Hasil);        

    }    


    public function FillForm(Request $request) {

        $TBLDSC = TBLDSC::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request)) )
                ->where([
                    ['TDDSCD', '=', $request->TDDSCD],
                  ])->get();

        $Hasil = $this->fnFillForm(true, $TBLDSC, "");
        return response()->jSon($Hasil);        

    }   

    public function ObjectData(Request $request) {
        $Obj = [];
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "TDDSCD", "Code", "", true, 0, 0, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "TDDSNM", "Description", "", true, 0, 0, "", "Awal", "Akhir");
        $this->fnCrtObjNum($Obj, true, "0", "Panel1", "TDLGTH", "Panjang Karakter", "", false, 2, "$","IDR", 1, 1, 99);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "TDDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "TDREMK", "Remark", "Everything You Want", false, 100);
            $this->fnUpdObj($Obj, "TDREMK", array("Helper"=>'Terserah anda mau isi apa?'));

        $this->fnCrtObjDefault($Obj,"TD");     

        return response()->jSon($Obj);   
    }

    public function SaveData(Request $request) {


        $fTBLDSC = json_encode($request->frmTBLDSC);
        $fTBLDSC = json_decode($fTBLDSC, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLDSC", 
                                  "Key"=>"TDDSCD", 
                                  "Data"=>$fTBLDSC, 
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
                                        "Data"=>$fTBLDSC,
                                        "Table"=>"TBLDSC",
                                        "Field"=>['TDDSCD','TDDSNM','TDLGTH','TDDPFG','TDREMK'],
                                        "Where"=>[],
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fTBLDSC,
                                        "Table"=>"TBLDSC",
                                        "Field"=>['TDDSNM','TDLGTH','TDDPFG','TDREMK'],
                                        "Where"=>[['TDDSCD','=',$fTBLDSC['TDDSCD']]],
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fTBLDSC,
                                        "Table"=>"TBLDSC",
                                        "Field"=>['TDDSCD'],
                                        "Where"=>[['TDDSCD','=',$fTBLDSC['TDDSCD']]],
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}


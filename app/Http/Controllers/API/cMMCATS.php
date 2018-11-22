<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmcats;
use DB;

class cMMCATS extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'C2C2CDIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'C1NAME', 'Category', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C2C2CD', 'Sub Category Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C2NAME', 'Sub Category Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C2REMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "C2");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'C2C2CD','direction'=>'asc');
        $ColumnGrid = [];

        $MMCATS = MMCATS::noLock()
                ->leftJoin($this->fnDBRaw("Table","MMCATG"), 'C1C1CDIY', '=', 'C2C1CDIY')
                ->where([
                    ['C2DLFG', '=', '0'],
                  ]);
        $MMCATS = $this->fnQuerySearchAndPaginate($request, $MMCATS, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMCATS,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'C2C2CDIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMCATS = MMCATS::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->leftJoin($this->fnDBRaw("Table","MMCATG"), 'C1C1CDIY', '=', 'C2C1CDIY') 
                ->where([
                    ['C2C2CDIY', '=', $request->C2C2CDIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMCATS, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "C2C2CDIY", "IY", "", false);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "C2C1CDIY", "C1C1CD", "C1NAME", "Category", "", true, "MMCATG");
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "C2C2CD", "Sub Category Code", "", true, 0, 8, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "C2NAME", "Sub Category Name", "", true, 0, 0);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "C2DPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "C2REMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"C2");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMCATS = json_encode($request->frmMMCATS);
        $fMMCATS = json_decode($fMMCATS, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMCATS", 
                                  "Key"=>['C2C2CDIY'], 
                                  "Data"=>$fMMCATS, 
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
                                        "Data"=>$fMMCATS,
                                        "Table"=>"MMCATS",
                                        "Field"=>['C2C2CDIY','C2C1CDIY','C2C2CD','C2NAME','C2DPFG','C2REMK'],
                                        "Where"=>[],
                                        "Iy"=>"C2C2CDIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMCATS,
                                        "Table"=>"MMCATS",
                                        "Field"=>['C2NAME','C2DPFG','C2REMK'],
                                        "Where"=> [['C2C2CDIY','=',$fMMCATS['C2C2CDIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMCATS,
                                        "Table"=>"MMCATS",
                                        "Field"=>['C2C2CDIY'],
                                        "Where"=>[['C2C2CDIY','=',$fMMCATS['C2C2CDIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

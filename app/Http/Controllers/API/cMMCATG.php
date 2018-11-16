<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmcatg;
use DB;

class cMMCATG extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'C1C1CDIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C1C1CD', 'Category Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C1NAME', 'Category Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C1REMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "C1");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'C1C1CD','direction'=>'asc');
        $ColumnGrid = [];

        $MMCATG = MMCATG::noLock()
                ->where([
                    ['C1DLFG', '=', '0'],
                  ]);
        $MMCATG = $this->fnQuerySearchAndPaginate($request, $MMCATG, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMCATG,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'C1C1CDIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMCATG = MMCATG::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->where([
                    ['C1C1CDIY', '=', $request->C1C1CDIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMCATG, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "C1C1CDIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "C1C1CD", "Category Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "C1NAME", "Category Name", "", true, 0, 0);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "C1DPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "C1REMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"C1");

        return response()->jSon($Obj);   
    }


    public function SaveData(Request $request) {


        $fMMCATG = json_encode($request->frmMMCATG);
        $fMMCATG = json_decode($fMMCATG, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMCATG", 
                                  "Key"=>['C1C1CDIY'], 
                                  "Data"=>$fMMCATG, 
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
                                        "Data"=>$fMMCATG,
                                        "Table"=>"MMCATG",
                                        "Field"=>['C1C1CDIY','C1C1CD','C1NAME','C1DPFG','C1REMK'],
                                        "Where"=>[],
                                        "Iy"=>"C1C1CDIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMCATG,
                                        "Table"=>"MMCATG",
                                        "Field"=>['C1NAME','C1DPFG','C1REMK'],
                                        "Where"=> [['C1C1CDIY','=',$fMMCATG['C1C1CDIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMCATG,
                                        "Table"=>"MMCATG",
                                        "Field"=>['C1C1CDIY'],
                                        "Where"=>[['C1C1CDIY','=',$fMMCATG['C1C1CDIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

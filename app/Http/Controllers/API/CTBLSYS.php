<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Tblsys;
use DB;

class cTBLSYS extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSDSCD', 'Desc Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYCD', 'System Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYNM', 'System Name', 100);
        $this->fnCrtColGrid($Obj, "num", 0, 0, '', 'TSSYV1', 'Value 1', 100);
        $this->fnCrtColGrid($Obj, "num", 0, 0, '', 'TSSYV2', 'Value 2', 100);
        $this->fnCrtColGrid($Obj, "num", 0, 0, '', 'TSSYV3', 'Value 3', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSSYT1', 'Text 1', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSSYT2', 'Text 2', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSSYT3', 'Text 3', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLSV1', 'Label Value 1', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLSV2', 'Label Value 2', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLSV3', 'Label Value 3', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLST1', 'Label Text 1', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLST2', 'Label Text 2', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TSLST3', 'Label Text 3', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TSREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TS");

        $Filter = [];
        $Sort = [];
        // $Sort[] = array('name'=>'TDDSCD','direction'=>'asc');
        $ColumnGrid = [];

        $TBLSYS = TBLSYS::noLock()
                ->where([
                    ['TSDLFG', '=', '0'],
                  ]);
        $TBLSYS = $this->fnQuerySearchAndPaginate($request, $TBLSYS, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TBLSYS,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'RowId');

        return response()->jSon($Hasil);       

    }    

    public function FillForm(Request $request) {

        $TBLSYS = TBLSYS::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request)) )
                ->leftJoin($this->fnDBRaw("Table","TBLDSC"), 'TDDSCD', '=', 'TSDSCD')        
                ->where([
                    ['TSDSCD', '=', $request->TSDSCD],
                    ['TSSYCD', '=', $request->TSSYCD]
                  ])
                ->get();

        // $TBLSYS = DB::select(DB::raw("
        //             select TSDSCD, TDDSCD, TDDSNM, TSSYCD, TSSYNM, TSDPFG, TSREMK, TSSYT1, TSSYT2, TSSYT3, TSSYV1, TSSYV2, TSSYV3, TSLST1, TSLST2, TSLST3, TSLSV1, TSLSV2, TSLSV3, TSRGID, TSRGDT, TSCHID, TSCHDT, TSCHNO, TSCSID, TSCSDT 
        //             from tblsys with (nolock) 
        //             left join TBLDSC with (nolock) on TDDSCD = TSDSCD 
        //             where TSDSCD = '".$request->TSDSCD."'
        //             and TSSYCD = '".$request->TSSYCD."'
        //             "));
        // $a = $TBLSYS->toSql();
        // echo $a;

        $Hasil = $this->fnFillForm(true, $TBLSYS, "");
        return response()->jSon($Hasil);     

    } 

    public function ObjectData(Request $request) {
        $Obj = [];
       
        $this->fnCrtObjPop($Obj, true, "2", "Panel01", "TSDSCD", "TDDSCD", "TDDSNM", "Description Code", "", true, "TBLDSC", true, 1);
        // $this->fnCrtObjPop($Obj, true, "0", "Panel1", "TSITNOIY", "MMITNO", "MMITDS", "Item Code", "", true, "MITMAS");
        // $this->fnCrtObjPop($Obj, true, "0", "Panel1", "TSMENUIY", "TMNOMR", "TMMENU", "Item Code 2", "", true, "TBLMNU");
        // $this->fnUpdObj($Obj, "TSDSCD", array("Helper"=>'Test'));

        $this->fnCrtObjNum($Obj, false, "3", "Panel01", "TDLGTH", "Panjang", "", false);
        $this->fnCrtObjRad($Obj, true, "0", "Panel01", "TSDPFG", "Status", "", "1", "Radio", "DSPLY");

        $this->fnCrtObjTxt($Obj, true, "2", "Panel03", "TSSYCD", "Code", "", true, 6, 6);        
        $this->fnCrtObjTxt($Obj, true, "0", "Panel03", "TSSYNM", "Description", "", true);        

        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSSYT1", "Text 1", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLST1", "Label Text 1", "", false);

        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSSYT2", "Text 2", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLST2", "Label Text 2", "", false);

        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSSYT3", "Text 3", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLST3", "Label Text 3", "", false);

        $this->fnCrtObjNum($Obj, true, "0", "Panel05", "TSSYV1", "Value 1", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLSV1", "Label Value 1", "", false);

        $this->fnCrtObjNum($Obj, true, "0", "Panel05", "TSSYV2", "Value 2", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLSV2", "Label Value 2", "", false);

        $this->fnCrtObjNum($Obj, true, "0", "Panel05", "TSSYV3", "Value 3", "", false); 
        $this->fnCrtObjTxt($Obj, true, "0", "Panel05", "TSLSV3", "Label Value 3", "", false);

        $this->fnCrtObjRmk($Obj, true, "0", "Panel10", "TSREMK", "Remark", "", false, 200);        

        $this->fnCrtObjDefault($Obj,"TS");

        return response()->jSon($Obj);
    }

    public function SaveData(Request $request) {


        $fTBLSYS = json_encode($request->frmTBLSYS);
        $fTBLSYS = json_decode($fTBLSYS, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLSYS", 
                                  "Key"=>['TSDSCD','TSSYCD'], 
                                  "Data"=>$fTBLSYS, 
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
                                        "Data"=>$fTBLSYS,
                                        "Table"=>"TBLSYS",
                                        "Field"=>['TSDSCD','TSSYCD','TSSYNM','TSDPFG','TSREMK',
                                                  'TSSYT1','TSLST1','TSSYT2','TSLST2','TSSYT3','TSLST3',
                                                  'TSSYV1','TSLSV1','TSSYV2','TSLSV2','TSSYV3','TSLSV3'],
                                        "Where"=>[],
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fTBLSYS,
                                        "Table"=>"TBLSYS",
                                        "Field"=>['TSDSCD','TSSYCD','TSSYNM','TSDPFG','TSREMK',
                                                  'TSSYT1','TSLST1','TSSYT2','TSLST2','TSSYT3','TSLST3',
                                                  'TSSYV1','TSLSV1','TSSYV2','TSLSV2','TSSYV3','TSLSV3'],
                                        "Where"=> ['TSDSCD','=',$fTBLSYS['TSDSCD']],
                                                  ['TSSYCD','=',$fTBLSYS['TSSYCD']]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fTBLSYS,
                                        "Table"=>"TBLSYS",
                                        "Field"=>['TSDSCD','TSSYCD'],
                                        "Where"=>['TSDSCD','=',$fTBLSYS['TSDSCD']],
                                                 ['TSSYCD','=',$fTBLSYS['TSSYCD']]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

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


    public function StpTBLSYS ($request) {

        $TBLSYS = json_encode($request->frmTBLSYS);
        $TBLSYS = json_decode($TBLSYS, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = $request->AppUserName;
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLSYS", 
                                  "Key"=>['TSDSCD','TSSYCD'], 
                                  "Data"=>$TBLSYS, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $TBLSYS['TDDSCD'] = $this->fnTBLNOR ($UserName, "TBLSYS");
                DB::table('TBLSYS')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $TBLSYS, $UserName, '1', 
                                    ['TSDSCD','TSSYCD','TSSYNM','TSDPFG','TSREMK',
                                     'TSSYT1','TSLST1','TSSYT2','TSLST2','TSSYT3','TSLST3',
                                     'TSSYV1','TSLSV1','TSSYV2','TSLSV2','TSSYV3','TSLSV3'], 
                                    $UnikNo )
                    );
                break;
            case "2":
                DB::table('TBLSYS')
                    ->where([
                             ['TSDSCD','=',$TBLSYS['TSDSCD']],
                             ['TSSYCD','=',$TBLSYS['TSSYCD']]
                            ])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLSYS, $UserName, '2',  
                                    ['TSSYNM','TSDPFG','TSREMK',
                                     'TSSYT1','TSLST1','TSSYT2','TSLST2','TSSYT3','TSLST3',
                                     'TSSYV1','TSLSV1','TSSYV2','TSLSV2','TSSYV3','TSLSV3'], 
                                    $UnikNo )
                    );
                break;
            case "3":
                DB::table('TBLSYS')
                    ->where([
                             ['TSDSCD','=',$TBLSYS['TSDSCD']],
                             ['TSSYCD','=',$TBLSYS['TSSYCD']]
                            ])      
                    ->delete();
                break;
        }
        // return array("success"=> false, "message"=> "coba cccc disini");

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpTBLSYS($request);
                    }
                 , $request->AppUserName);
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

}

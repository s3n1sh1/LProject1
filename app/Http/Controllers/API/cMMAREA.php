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


    public function StpMMAREA ($request) {

        $MMAREA = json_encode($request->frmMMAREA);
        $MMAREA = json_decode($MMAREA, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = $request->AppUserName;
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMAREA", 
                                  "Key"=>['MAAREAIY'], 
                                  "Data"=>$MMAREA, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $MMAREA['MAAREAIY'] = $this->fnTBLNOR ($UserName, "MMAREA");
                DB::table('MMAREA')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $MMAREA, $UserName, '1', 
                            ['MAAREAIY','MAAREA','MANAME','MADPFG','MAREMK'], 
                            $UnikNo )
                    );
                break;
            case "2":
                DB::table('MMAREA')
                    ->where('MAAREAIY',$MMAREA['MAAREAIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($MMAREA, $UserName, '2',  
                            ['MANAME','MADPFG','MAREMK'], 
                            $UnikNo )
                    );
                break;
            case "3":
                DB::table('MMAREA')
                    ->where('MAAREAIY',$MMAREA['MAAREAIY'])      
                    ->delete();
                break;
        }

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpMMAREA($request);
                    }
                 , $request->AppUserName);
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

}

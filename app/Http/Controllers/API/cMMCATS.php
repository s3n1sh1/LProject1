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



    public function StpMMCATS ($request) {

        $MMCATS = json_encode($request->frmMMCATS);
        $MMCATS = json_decode($MMCATS, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = $request->AppUserName;
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMCATS", 
                                  "Key"=>['C2C2CDIY'], 
                                  "Data"=>$MMCATS, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $MMCATS['C2C2CDIY'] = $this->fnTBLNOR ($UserName, "MMCATS");
                DB::table('MMCATS')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $MMCATS, $UserName, '1', 
                            ['C2C2CDIY','C2C1CDIY','C2C2CD','C2NAME','C2DPFG','C2REMK'], 
                            $UnikNo )
                    );
                break;
            case "2":
                DB::table('MMCATS')
                    ->where('C2C2CDIY',$MMCATS['C2C2CDIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($MMCATS, $UserName, '2',  
                            ['C2NAME','C2DPFG','C2REMK'], 
                            $UnikNo )
                    );
                break;
            case "3":
                DB::table('MMCATS')
                    ->where('C2C2CDIY',$MMCATS['C2C2CDIY'])      
                    ->delete();
                break;
        }

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpMMCATS($request);
                    }
                 , $request->AppUserName);
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }


}

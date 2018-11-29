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
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "C1C1CD", "Category Code", "", true, 0, 8, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "C1NAME", "Category Name", "", true, 0, 0);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "C1DPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "C1REMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"C1");

        return response()->jSon($Obj);   
    }



    public function StpMMCATG ($request) {

        $MMCATG = json_encode($request->frmMMCATG);
        $MMCATG = json_decode($MMCATG, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = $request->AppUserName;
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMCATG", 
                                  "Key"=>['C1C1CDIY'], 
                                  "Data"=>$MMCATG, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $MMCATG['C1C1CDIY'] = $this->fnTBLNOR ($UserName, "MMCATG");
                DB::table('MMCATG')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $MMCATG, $UserName, '1', 
                            ['C1C1CDIY','C1C1CD','C1NAME','C1DPFG','C1REMK'], 
                            $UnikNo )
                    );
                break;
            case "2":
                DB::table('MMCATG')
                    ->where('C1C1CDIY',$MMCATG['C1C1CDIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($MMCATG, $UserName, '2',  
                            ['C1NAME','C1DPFG','C1REMK'], 
                            $UnikNo )
                    );
                break;
            case "3":
                DB::table('MMCATG')
                    ->where('C1C1CDIY',$MMCATG['C1C1CDIY'])      
                    ->delete();
                break;
        }

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpMMCATG($request);
                    }
                 , $request->AppUserName);
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }


}

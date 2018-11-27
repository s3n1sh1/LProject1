<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmbagn;
use DB;

class cMMBAGN extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MBBAGNIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBBAGN', 'Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBNAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MBREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MB");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MBBAGN','direction'=>'asc');
        $ColumnGrid = [];

        $MMBAGN = MMBAGN::noLock()
                ->where([
                    ['MBDLFG', '=', '0'],
                  ]);
        $MMBAGN = $this->fnQuerySearchAndPaginate($request, $MMBAGN, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMBAGN,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MBBAGNIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMBAGN = MMBAGN::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->where([
                    ['MBBAGNIY', '=', $request->MBBAGNIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMBAGN, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MBBAGNIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MBBAGN", "Code", "", true, 0, 6, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MBNAME", "Name", "", true, 0, 100);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MBDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MBREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MB");

        return response()->jSon($Obj);   
    }



    public function StpMMBAGN ($request) {

        $MMBAGN = json_encode($request->frmMMBAGN);
        $MMBAGN = json_decode($MMBAGN, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMBAGN", 
                                  "Key"=>['MBBAGNIY'], 
                                  "Data"=>$MMBAGN, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $MMBAGN['MBBAGNIY'] = $this->fnTBLNOR ($UserName, "MMBAGN");
                DB::table('MMBAGN')->insert(
                    $this->fnGetSintaxCRUD ( $MMBAGN, $UserName, '1', 
                        ['MBBAGNIY','MBBAGN','MBNAME','MBDPFG','MBREMK'], 
                        $UnikNo )
                );
                break;
            case "2":
                DB::table('MMBAGN')
                    ->where('MBBAGNIY',$MMBAGN['MBBAGNIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($MMBAGN, $UserName, '2',  
                            ['MBNAME','MBDPFG','MBREMK'], 
                            $UnikNo )
                    );
                break;
            case "3":
                DB::table('MMBAGN')
                    ->where('MBBAGNIY',$MMBAGN['MBBAGNIY'])      
                    ->delete();
                break;
        }

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpMMBAGN($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }
    

}

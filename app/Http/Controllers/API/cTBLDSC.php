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



    public function StpTBLDSC ($request) {

        $TBLDSC = json_encode($request->frmTBLDSC);
        $TBLDSC = json_decode($TBLDSC, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLDSC", 
                                  "Key"=>['TDDSCD'], 
                                  "Data"=>$TBLDSC, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                DB::table('TBLDSC')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $TBLDSC, $UserName, '1', 
                                    ['TDDSCD','TDDSNM','TDLGTH','TDDPFG','TDREMK'], 
                                    $UnikNo )
                    );
                break;
            case "2":
                DB::table('TBLDSC')
                    ->where('TDDSCD',$TBLDSC['TDDSCD'])
                    ->update(
                        $this->fnGetSintaxCRUD ($TBLDSC, $UserName, '2',  
                                    ['TDDSNM','TDLGTH','TDDPFG','TDREMK'], 
                                    $UnikNo )
                    );
                break;
            case "3":
                DB::table('TBLDSC')
                    ->where('TDDSCD',$TBLDSC['TDDSCD'])      
                    ->delete();
                break;
        }
        // return array("success"=> false, "message"=> "coba ssss disini");

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpTBLDSC($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

}


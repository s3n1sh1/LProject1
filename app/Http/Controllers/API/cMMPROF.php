<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Mmprof;
use DB;

class cMMPROF extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'MFPCNOIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFPCNO', 'Profit Center Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFNAME', 'Profit Center Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFDIVI', 'Divisi Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYNM', 'Divisi Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MFREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "MF");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'MFPCNO','direction'=>'asc');
        $ColumnGrid = [];

        $MMPROF = MMPROF::noLock()
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MFDIVI')  
                ->where([
                    ['MFDLFG', '=', '0'],
                  ]);
        $MMPROF = $this->fnQuerySearchAndPaginate($request, $MMPROF, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $MMPROF,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'MFPCNOIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $MMPROF = MMPROF::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request) ) )
                ->leftJoin($this->fnDBRaw("Table","TBLSYS"), 'TSSYCD', '=', 'MFDIVI')  
                ->where([
                    ['MFPCNOIY', '=', $request->MFPCNOIY],
                  ])
                ->get();

        $Hasil = $this->fnFillForm(true, $MMPROF, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "MFPCNOIY", "IY", "", false);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "MFDIVI", "TSSYCD", "TSSYNM", "Divisi", "", true, "TBLSYS_DIVI", false, 1);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "MFPCNO", "Profit Center Code", "", true, 0, 10, "Big");
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "MFNAME", "Profit Center Name", "", true, 0, 0);        
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "MFDPFG", "Status", "", "1", "Radio", "DSPLY");
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "MFREMK", "Remark", "", false, 100);

        $this->fnCrtObjDefault($Obj,"MF");

        return response()->jSon($Obj);   
    }


    public function StpMMPROF ($request) {

        $MMPROF = json_encode($request->frmMMPROF);
        $MMPROF = json_decode($MMPROF, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMPROF", 
                                  "Key"=>['MFPCNOIY'], 
                                  "Data"=>$MMPROF, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $MMPROF['MFPCNOIY'] = $this->fnTBLNOR ($UserName, "MMPROF");
                DB::table('MMPROF')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $MMPROF, $UserName, '1', 
                            ['MFPCNOIY','MFPCNO','MFNAME','MFDIVI','MFDPFG','MFREMK'], 
                            $UnikNo )
                    );
                break;
            case "2":
                DB::table('MMPROF')
                    ->where('MFPCNOIY',$MMPROF['MFPCNOIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($MMPROF, $UserName, '2',  
                            ['MFNAME','MFDIVI','MFDPFG','MFREMK'], 
                            $UnikNo )
                    );
                break;
            case "3":
                DB::table('MMPROF')
                    ->where('MFPCNOIY',$MMPROF['MFPCNOIY'])      
                    ->delete();
                break;
        }
        // return array("success"=> false, "message"=> "coba ssss disini");

    }


    public function SaveData(Request $request) {


        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpMMPROF($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }


    public function SaveDataXXXXXXXXXXX(Request $request) {

        $MMPROF = json_encode($request->frmMMPROF);
        $MMPROF = json_decode($MMPROF, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $Hasil = $this->fnSetExecuteQuery(
                function () use($Mode, $UserName, $UnikNo, $MMPROF) {

                    $HasilCheckBFCS = $this->fnCheckBFCS (
                                        array("Table"=>"MMPROF", 
                                              "Key"=>['MFPCNOIY'], 
                                              "Data"=>$MMPROF, 
                                              "Mode"=>$Mode,
                                              "Menu"=>"", 
                                              "FieldTransDate"=>""));
                    if (!$HasilCheckBFCS["success"]) {
                        return $HasilCheckBFCS;
                    }

                    switch ($Mode) {
                        case "1":
                            $MMPROF['MFPCNOIY'] = $this->fnTBLNOR ($UserName, "MMPROF");
                            $FinalField = $this->fnGetSintaxCRUD ( $MMPROF, $UserName, '1', 
                                                ['MFPCNOIY','MFPCNO','MFNAME','MFDIVI','MFDPFG','MFREMK'], 
                                                $UnikNo );
                            DB::table('MMPROF')->insert($FinalField);
                            break;
                        case "2":
                            $FinalField = $this->fnGetSintaxCRUD ($MMPROF, $UserName, '2',
                                                ['MFNAME','MFDIVI','MFDPFG','MFREMK'], 
                                                $UnikNo );
                            DB::table('MMPROF')
                                ->where('MFPCNOIY',$MMPROF['MFPCNOIY'])
                                ->update($FinalField);
                            break;
                        case "3":
                            DB::table('MMPROF')
                                ->where('MFPCNOIY',$MMPROF['MFPCNOIY'])      
                                ->delete();
                            break;
                    }

                }
                ,$Delimiter);

        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }


    public function SaveDataXXX(Request $request) {

        $fMMPROF = json_encode($request->frmMMPROF);
        $fMMPROF = json_decode($fMMPROF, true);


        // $Hasil = array("success"=> false, "message"=> " lagi coba coba ");
        // return response()->jSon($Hasil);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);


        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"MMPROF", 
                                  "Key"=>['MFPCNOIY'], 
                                  "Data"=>$fMMPROF, 
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
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFPCNOIY','MFPCNO','MFNAME','MFDIVI','MFDPFG','MFREMK'],
                                        "Where"=>[],
                                        "Iy"=>"MFPCNOIY",
                                    ));
                break;
            case "2":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFNAME','MFDPFG','MFREMK'],
                                        "Where"=> [['MFPCNOIY','=',$fMMPROF['MFPCNOIY']]]
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fMMPROF,
                                        "Table"=>"MMPROF",
                                        "Field"=>['MFPCNOIY'],
                                        "Where"=>[['MFPCNOIY','=',$fMMPROF['MFPCNOIY']]]
                                    ));
                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);        
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }

}

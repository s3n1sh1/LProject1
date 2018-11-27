<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Bbhead;
use App\Models\Bbline;
use DB;

class cBBHEAD extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'BABKNOIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BABKNO', 'BKK No', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BATYPE', 'BKK Type', 100);
        $this->fnCrtColGrid($Obj, "dtp", 1, 1, '', 'BABKDT', 'BKK Date', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BADIVI', 'Divisi', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BADEPT', 'Department', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'MCNAME', 'Cost Center', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BACURR', 'Currency', 100);
        $this->fnCrtColGrid($Obj, "num", 1, 1, '', 'BATOTL', 'Total', 100);
        $this->fnCrtColGridDefault($Obj, "BA");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'BABKDT','direction'=>'desc');
        $Sort[] = array('name'=>'BABKNO','direction'=>'desc');
        $ColumnGrid = [];

        $BBHEAD = BBHEAD::noLock()
                ->leftJoin($this->fnDBRaw("Table","MMCTCR"), 'MCCCNOIY', '=', 'BACCNOIY')
                ->where([
                    ['BADLFG', '=', '0'],
                  ]);
        $BBHEAD = $this->fnQuerySearchAndPaginate($request, $BBHEAD, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $BBHEAD,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'BABKNOIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        // DB::enableQueryLog();

        $BBHEAD = BBHEAD::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request), "", "BBLINE") )
                ->leftJoin($this->fnDBRaw("Table","MMCTCR"), 'MCCCNOIY', '=', 'BACCNOIY')
                ->leftJoin($this->fnDBRaw("Table","MMPROF"), 'MFPCNOIY', '=', 'MCPCNOIY')
                ->leftJoin($this->fnDBRaw("Table","TBLSYS", "BALOCA"), function($join){
                    $join->where('BALOCA.TSDSCD','=',"LOCA");
                    $join->on('BALOCA.TSSYCD','=','BALOCA');
                })
                ->leftJoin($this->fnDBRaw("Table","TBLSYS", "BADIVI"), function($join){
                    $join->where('BADIVI.TSDSCD','=',"DIVI");
                    $join->on('BADIVI.TSSYCD','=','BADIVI');
                })
                ->leftJoin($this->fnDBRaw("Table","TBLSYS", "BADEPT"), function($join){
                    $join->where('BADEPT.TSDSCD','=',"DEPT");
                    $join->on('BADEPT.TSSYCD','=','BADEPT');
                })
                ->leftJoin($this->fnDBRaw("Table","TBLSYS", "BACURR"), function($join){
                    $join->where('BACURR.TSDSCD','=',"CURR");
                    $join->on('BACURR.TSSYCD','=','BACURR');
                })
                ->where([
                    ['BABKNOIY', '=', $request->BABKNOIY],
                  ])
                ->get();


        $BBLINE = $this->LoadBBLINE($request);

        $BBLINE = json_encode($BBLINE);
        $BBLINE = json_decode($BBLINE, true);

        $BBHEAD[0]['BBLINE'] = $BBLINE['original'];
        // var_dump($BBHEAD);
        
        // dd(DB::getQueryLog());
        // var_dump($BBHEAD);

        $Hasil = $this->fnFillForm(true, $BBHEAD, "");
        return response()->jSon($Hasil);      

    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "BABKNOIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "Panel1", "BABKNO", "BKK No", "", false, 0, 0);
        $this->fnCrtObjDtp($Obj, true, "2", "Panel1", "BABKDT", "BKK Date", "", true);

        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BALOCA", "TSSYCD", "TSSYNM", "Location", "", true
                            , "TBLSYS_LOCA", false, 1, "", true);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BADIVI", "TSSYCD", "TSSYNM", "Divisi", "", true
                            , "TBLSYS_DIVI", false, 1, "", true);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "MCPCNOIY", "MFPCNO", "MFNAME", "Profit Center", "", true, "MMPROF", true, 1);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BADEPT", "TSSYCD", "TSSYNM", "Department", "", true
                            , "TBLSYS_DEPT", false, 1, "", true);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BACCNOIY", "MCCCNO", "MCNAME", "Cost Center", "", true, "MMCTCR", true, 1);
        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BACURR", "TSSYCD", "TSSYNM", "Currency", "", true
                            , "TBLSYS_CURR", false, 1, "", true);

        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "BAREMK", "Remark", "", false, 100);

        $this->fnCrtObjGrd($Obj, true, "0", "Panel2", "BBLINE", "Detail BKK", true
                            , "AED", "BBHEAD", "LoadBBLINE", "LoadObjBBLINE");
        $this->fnCrtObjNum($Obj, true, "3", "Panel3", "BATOTL", "Grand Total", "", false, 2);

        $this->fnCrtObjDefault($Obj,"BA");

        return response()->jSon($Obj);   
    }



    public function StpBBHEAD ($request) {

        $BBHEAD = json_encode($request->frmBBHEAD);
        $BBHEAD = json_decode($BBHEAD, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        $UserName = "User AAA";
        $Mode = $request->Mode;    

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"BBHEAD", 
                                  "Key"=>['BABKNOIY'], 
                                  "Data"=>$BBHEAD, 
                                  "Mode"=>$Mode,
                                  "Menu"=>"BBHEAD", 
                                  "FieldTransDate"=>"BABKDT"));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        switch ($Mode) {
            case "1":
                $BBHEAD['BATYPE'] = "";
                $BBHEAD['BABKNOIY'] = $this->fnTBLNOR ($UserName, "BBHEAD");
                $BBHEAD['BABKNO'] = "BKK-".substr("0000000".$BBHEAD['BABKNOIY'],-5);
                DB::table('BBHEAD')
                    ->insert(
                        $this->fnGetSintaxCRUD ( $BBHEAD, $UserName, '1', 
                            ['BABKNOIY','BABKNO','BABKDT','BATYPE','BALOCA','BADIVI','BADEPT','BACCNOIY',
                             'BACURR','BATOTL','BAREMK'], 
                            $UnikNo )
                    );


                $i=0;
                if (is_array($BBHEAD['BBLINE'])) {
                    foreach($BBHEAD['BBLINE'] as $BBLINE) {
                        $i++;
                        $BBLINE['BBBLNO'] = $i;
                        $BBLINE['BBBKNOIY'] = $BBHEAD['BABKNOIY'];
                        $BBLINE['BBBLNOIY'] = $this->fnTBLNOR ($UserName, "BBLINE");
                        DB::table('BBLINE')
                            ->insert(
                                $this->fnGetSintaxCRUD ( $BBLINE, $UserName, '1', 
                                    ['BBBLNOIY','BBBLNO','BBBKNOIY','BBC2CDIY','BBDESC','BBTOTL','BBREMK'], 
                                    $UnikNo )
                            );
                    }                    
                }

                break;
            case "2":
                DB::table('BBHEAD')
                    ->where('BABKNOIY','=',$BBHEAD['BABKNOIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($BBHEAD, $UserName, '2',  
                            ['BATOTL','BAREMK'], 
                            $UnikNo )
                    );

                DB::table('BBLINE')
                    ->where('BBBKNOIY','=',$BBHEAD['BABKNOIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($BBHEAD, $UserName, '3',  
                            ['BBBKNOIY'], 
                            $UnikNo )
                    );

                $i = 0;
                $i = DB::table("BBLINE")
                        ->select("BBBLNO")
                        ->where('BBBKNOIY','=',$BBHEAD['BABKNOIY'])
                        ->max("BBBLNO");
                if (is_array($BBHEAD['BBLINE'])) {
                    foreach($BBHEAD['BBLINE'] as $BBLINE) {
                        $i++;
                        $BBLINE['BBBLNO'] = $i;
                        $BBLINE['BBBKNOIY'] = $BBHEAD['BABKNOIY'];

                        if ($BBLINE['BBBLNOIY']=="") {
                            $BBLINE['BBBLNOIY'] = $this->fnTBLNOR ($UserName, "BBLINE");
                            DB::table('BBLINE')
                                ->insert(
                                    $this->fnGetSintaxCRUD ( $BBLINE, $UserName, '1', 
                                        ['BBBLNOIY','BBBLNO','BBBKNOIY','BBC2CDIY','BBDESC','BBTOTL','BBREMK'], 
                                        $UnikNo )
                                );
                        } else {
                            DB::table('BBLINE')
                                ->where('BBBLNOIY','=',$BBLINE['BBBLNOIY'])
                                ->update(
                                    $this->fnGetSintaxCRUD ( $BBLINE, $UserName, '2', 
                                        ['BBDESC','BBTOTL','BBREMK'], 
                                        $UnikNo )
                                );
                        }
                    }                    
                }

                break;
            case "3":
                DB::table('BBHEAD')
                    ->where('BABKNOIY','=',$BBHEAD['BABKNOIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($BBHEAD, $UserName, '3',  
                            ['BABKNOIY'], 
                            $UnikNo )
                    );

                DB::table('BBLINE')
                    ->where('BBBKNOIY','=',$BBHEAD['BABKNOIY'])
                    ->update(
                        $this->fnGetSintaxCRUD ($BBHEAD, $UserName, '3',  
                            ['BBBKNOIY'], 
                            $UnikNo )
                    );

                break;

            default:
                return array("success"=> false, "message"=> " No Permision fo this Action!!!");            
                break;
        }
        // return array("success"=> false, "message"=> "coba ssss disini");

    }


    public function SaveData(Request $request) {

        $Hasil = $this->fnSetExecuteQuery(
                    function () use($request) {
                        return $this->StpBBHEAD($request);
                    }
                 );
        // $Hasil = array("success"=> false, "message"=> "coba coba disini");
        return response()->jSon($Hasil);        

    }

    public function SaveDataXXXX(Request $request) {

        $fBBHEAD = json_encode($request->frmBBHEAD);
        $fBBHEAD = json_decode($fBBHEAD, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"BBHEAD", 
                                  "Key"=>"BABKNOIY", 
                                  "Data"=>$fBBHEAD, 
                                  "Mode"=>$request->Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        $SqlStm = [];
        switch ($request->Mode) {
            case "1":
                $fBBHEAD['BATYPE'] = "";
                $NoUrut = $this->fnGetRec("TBLNOR", "TNNOUR", "TNTABL", "BBHEAD", "");                
                $No = isset($NoUrut->TNNOUR) ? $NoUrut->TNNOUR+1 : 1;
                $fBBHEAD['BABKNO'] = "BKK-".substr("0000000".$No,-5);

                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"I",
                                        "Data"=>$fBBHEAD,
                                        "Table"=>"BBHEAD",
                                        "Field"=>['BABKNOIY','BABKNO','BABKDT','BATYPE','BALOCA','BADIVI','BADEPT','BACCNOIY','BACURR','BATOTL','BAREMK'],
                                        "Where"=>[],
                                        "Iy"=>"BABKNOIY"
                                    ));
                $i=0;
                foreach($fBBHEAD['BBLINE'] as $fBBLINE) {
                    $i++;
                    $fBBLINE['BBBLNO'] = $i;
                    // var_dump($fBBLINE);
                    // echo "<hr>";
                    array_push($SqlStm, array(
                                            "UnikNo"=>$UnikNo,
                                            "Mode"=>"I",
                                            "Data"=>$fBBLINE,
                                            "Table"=>"BBLINE",
                                            "Field"=>['BBBLNOIY','BBBLNO','BBBKNOIY','BBC2CDIY','BBDESC','BBTOTL','BBREMK'],
                                            "Where"=>[],
                                            "Iy"=>"BBBLNOIY",
                                            "IyReff"=>array("BBBKNOIY"=>"BABKNOIY")
                                        ));

                }

                break;
            case "2":
                // $fBBHEAD['TMACES'] = implode("",$fBBHEAD['TMACES']);
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fBBHEAD,
                                        "Table"=>"BBHEAD",
                                        "Field"=>['BATOTL','BAREMK'],
                                        "Where"=>[['BABKNOIY','=',$fBBHEAD['BABKNOIY']]],
                                    ));


                $i = DB::table("BBLINE")
                        ->select("BBBLNO")
                        ->where('BBBKNOIY','=',$fBBHEAD['BABKNOIY'])
                        ->max("BBBLNO");

                // echo "<hr>";
                // echo var_dump($i);
                // echo "<hr>";
                foreach($fBBHEAD['BBLINE'] as $fBBLINE) {
                    $fBBLINE['BBBKNOIY'] = $fBBHEAD['BABKNOIY'];
                    // var_dump($fBBLINE);
                    // echo "<hr>";
                    if ($fBBLINE['BBBLNOIY']=="") {
                        $i++;
                        $fBBLINE['BBBLNO'] = $i;
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"I",
                                                "Data"=>$fBBLINE,
                                                "Table"=>"BBLINE",
                                                "Field"=>['BBBLNOIY','BBBLNO','BBBKNOIY','BBC2CDIY','BBDESC','BBTOTL','BBREMK'],
                                                "Where"=>[],
                                                "Iy"=>"BBBLNOIY"
                                            ));
                    } else {
                        array_push($SqlStm, array(
                                                "UnikNo"=>$UnikNo,
                                                "Mode"=>"U",
                                                "Data"=>$fBBLINE,
                                                "Table"=>"BBLINE",
                                                "Field"=>['BBBLNOIY','BBC2CDIY','BBDESC','BBTOTL','BBREMK'],
                                                "Where"=>[['BBBLNOIY','=',$fBBLINE['BBBLNOIY']]]
                                            ));
                    }

                }

                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"DD",
                                        "Data"=>[],
                                        "Table"=>"BBHEAD",
                                        "Field"=>['BABKNOIY'],
                                        "Where"=>[['BABKNOIY','=',$fBBHEAD['BABKNOIY']]],
                                    ));
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"DD",
                                        "Data"=>[],
                                        "Table"=>"BBLINE",
                                        "Field"=>['BBBKNOIY'],
                                        "Where"=>[['BBBKNOIY','=',$fBBHEAD['BABKNOIY']]],
                                    ));

                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);


    }

    public function LoadBBLINE(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '901', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'BBBLNOIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'BBBKNOIY', 'Header IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'BBC2CDIY', 'Sub Category IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'C2C2CD', 'Sub Category Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'C2NAME', 'Sub Category', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BBDESC', 'Description', 100);
        $this->fnCrtColGrid($Obj, "num", 1, 1, '', 'BBTOTL', 'Total', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'BBREMK', 'Remark', 100);
        // $this->fnCrtColGridDefault($Obj, "BB");

        $Filter = [];
        $Sort = [];
        $ColumnGrid = [];

        $BBLINE = BBLINE::noLock()
                ->leftJoin($this->fnDBRaw("Table","MMCATS"), 'C2C2CDIY', '=', 'BBC2CDIY')
                ->where([
                    ['BBDLFG', '=', '0'],
                    ['BBBKNOIY', '=', $request->BABKNOIY],
                  ]);
        $BBLINE = $this->fnQuerySearchAndPaginate($request, $BBLINE, $Obj, $Sort, $Filter, $ColumnGrid);
        // $BBLINE = $BBLINE->get();

        $Hasil = array( "Data"=> $BBLINE,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'BBBLNOIY');

        return response()->jSon($Hasil);        

    }

    public function LoadObjBBLINE(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "BBBLNOIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "BBBKNOIY", "Header IY", "", false);

        $this->fnCrtObjPop($Obj, true, "2", "Panel1", "BBC2CDIY", "C2C2CD", "C2NAME", "Sub Category", "", true, "MMCATS", true, 1);
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "BBDESC", "Description", "", true);
        $this->fnCrtObjNum($Obj, true, "0", "Panel1", "BBTOTL", "Total", "", true, 2);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "BBREMK", "Remark", "", false, 100);

        return response()->jSon($Obj);   
    }

}

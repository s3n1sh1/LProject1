<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Tblmnu;
use DB;

class cTBLMNU extends BaseController {


    public function LoadData(Request $request) {

        $Filter = [];
        if (!is_null($request->cari)) {
            $Filter = $request->cari;
        }

        $Sort = [];
        if (is_null($request->urut)) {
            $Sort[] = array('name'=>'TMNOMR','direction'=>'asc');
            $Sort = json_decode(json_encode($Sort));
        } else {
            $Sort = $request->urut;
        }

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TMMENUIY', 'Menu IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TMNOMR', 'No Urut', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TMMENU', 'Menu Description', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TMSCUT', 'Short Cut', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TMACES', 'Menu Access', 100);
        $this->fnCrtColGrid($Obj, "num", 0, 0, '', 'TMBCDT', 'Back Dt', 100);
        $this->fnCrtColGrid($Obj, "num", 0, 0, '', 'TMFWDT', 'Forward Dt', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TMURLW', 'Form', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TMGRUP', 'Group', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TMUSRM', 'User Remark', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TMREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TM");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'TMNOMR','direction'=>'asc');
        $ColumnGrid = [];

        $TBLMNU = TBLMNU::noLock()->where([
                    ['TMDLFG', '=', '0'],
                  ]);
        $TBLMNU = $this->fnQuerySearchAndPaginate($request, $TBLMNU, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TBLMNU,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TMMENUIY');

        return response()->jSon($Hasil);        

    }    



    public function FillForm(Request $request) {

        $TBLMNU = TBLMNU::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request)) )
                ->where([
                    ['TMMENUIY', '=', $request->TMMENUIY],
                  ])->get();
                
        $Hasil = $this->fnFillForm(true, $TBLMNU, "");
        return response()->jSon($Hasil);        

    }  

    public function ObjectData(Request $request) {
        $Obj = [];
        $this->fnCrtObjNum($Obj, false, "3", "Panel1", "TMMENUIY", "ID", "", false, 0);      
        $this->fnCrtObjTxt($Obj, true, "0", "PanelA", "TMNOMR", "Code Menu", "", true, 0, 20, "Big");     
        $this->fnCrtObjTxt($Obj, true, "0", "PanelA", "TMSCUT", "Short Cut", "", false);    
        $this->fnCrtObjTxt($Obj, true, "0", "PanelB", "TMMENU", "Description", "", true);        
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "TMACES", "Menu Access", "", "1", "toggle", "MODE", false);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "TMDPFG", "Status", "", "1", "Radio", "DSPLY");      
        $this->fnCrtObjTxt($Obj, true, "0", "Panel2", "TMSYFG", "System Flag", "", true);  
        $this->fnCrtObjNum($Obj, true, "0", "Panel2", "TMBCDT", "Back Date", "", false, 2, "","Day", 1, 1, 9999);
        $this->fnCrtObjNum($Obj, true, "0", "Panel2", "TMFWDT", "Forward Date", "", false, 2, "","Day", 1, 1, 9999);
        $this->fnCrtObjTxt($Obj, true, "0", "Panel3", "TMURLW", "URL", "", false);        
        $this->fnCrtObjTxt($Obj, true, "0", "Panel3", "TMGRUP", "File Group", "", false);        
            $this->fnUpdObj($Obj, "TMGRUP", array("Helper"=>'Jika File Group diisi, Maka Menu tersebut akan refer ke file yang sama'));
        $this->fnCrtObjRmk($Obj, true, "0", "Panel4", "TMUSRM", "User Remark", "User Remark", false, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel4", "TMREMK", "Remark", "Everything You Want", false, 100);
            $this->fnUpdObj($Obj, "TMREMK", array("Helper"=>'Terserah anda mau isi apa?'));

        $this->fnCrtObjDefault($Obj,"TM");        


        return response()->jSon($Obj);   
    }



    public function SaveData(Request $request) {


        $fTBLMNU = json_encode($request->frmTBLMNU);
        $fTBLMNU = json_decode($fTBLMNU, true);

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);

        $HasilCheckBFCS = $this->fnCheckBFCS (
                            array("Table"=>"TBLMNU", 
                                  "Key"=>"TMMENUIY", 
                                  "Data"=>$fTBLMNU, 
                                  "Mode"=>$request->Mode,
                                  "Menu"=>"", 
                                  "FieldTransDate"=>""));
        if (!$HasilCheckBFCS["success"]) {
            return $HasilCheckBFCS;
        }

        $SqlStm = [];
        switch ($request->Mode) {
            case "1":
                $fTBLMNU['TMACES'] = implode("",$fTBLMNU['TMACES']);
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"I",
                                        "Data"=>$fTBLMNU,
                                        "Table"=>"TBLMNU",
                                        "Field"=>['TMMENUIY','TMNOMR','TMSCUT','TMMENU','TMACES','TMDPFG','TMSYFG',
                                                  'TMBCDT','TMFWDT','TMURLW','TMGRUP','TMUSRM','TMREMK'],
                                        "Where"=>[],
                                        "Iy"=>"TMMENUIY"
                                    ));
                break;
            case "2":
                $fTBLMNU['TMACES'] = implode("",$fTBLMNU['TMACES']);
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"U",
                                        "Data"=>$fTBLMNU,
                                        "Table"=>"TBLMNU",
                                        "Field"=>['TMNOMR','TMSCUT','TMMENU','TMACES','TMDPFG','TMSYFG','TMBCDT','TMFWDT',
                                                  'TMURLW','TMGRUP','TMUSRM','TMREMK'],
                                        "Where"=>['TMMENUIY','=',$fTBLMNU['TMMENUIY']],
                                    ));
                break;
            case "3":
                array_push($SqlStm, array(
                                        "UnikNo"=>$UnikNo,
                                        "Mode"=>"D",
                                        "Data"=>$fTBLMNU,
                                        "Table"=>"TBLMNU",
                                        "Field"=>['TMMENUIY'],
                                        "Where"=>['TMMENUIY','=',$fTBLMNU['TMMENUIY']],
                                    ));

                break;
        }


        $Hasil = $this->fnSetExecuteQuery($SqlStm,$Delimiter);
        // $Hasil = array("success"=> $BerHasil, "message"=> " Sukses... ".$message.$b);
        return response()->jSon($Hasil);

    }


    // public function SaveData(Request $request) {


    //     $arr = json_encode($request->Form);
    //     $arr = json_decode($arr, true);
    //     // $arr['TMACES']['Value'] = implode('',$arr['TMACES']['Value']);
    //     $Delimiter = "";
    //     $UnikNo = $this->fnGenUnikNo($Delimiter);
    //     $s = $this->fnGetSyntax($request->Source, $request->Username, $request->Mode, 'StpTBLMNU', $arr, $UnikNo);        

    //     // dd($d['params']);

    //     $SQLSTM = $s['all'];

    //     $HASIL = $this->fnSetExecuteQuery($SQLSTM,$Delimiter);


    //     return response()->jSon($HASIL);

    // }


}

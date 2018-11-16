<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Tblusr;
use App\Models\Tbldsc;
use DB;
use Illuminate\Support\Facades\Storage as Storage;

class cTBLUSR extends BaseController {

    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TUUSERIY', 'IY', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUUSER', 'Username', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUNAME', 'Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TUEMID', 'NIP', 100);
        $this->fnCrtColGrid($Obj, "txt", 0, 1, '', 'TUREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TU");

        $Filter = [];
        $Sort = [];
        // $Sort[] = array('name'=>'TDDSCD','direction'=>'asc');
        $ColumnGrid = [];

        $TBLUSR = TBLUSR::noLock()
                ->where([
                    ['TUDLFG', '=', '0'],
                  ]);
        $TBLUSR = $this->fnQuerySearchAndPaginate($request, $TBLUSR, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Id"=> $request->IdStore,
                        "Table"=> $TBLUSR,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TUUSERIY');

        return response()->jSon($Hasil);        

    }

    public function FillForm(Request $request) {

        $TBLUSR = TBLUSR::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request)) )
                ->where([
                    ['TUUSERIY', '=', $request->TUUSERIY],
                  ])->get();

        if($TBLUSR[0]['TUFOTO'] == "") {
            $TBLUSR[0]['TUFOTO'] = [];    
        } else {
            $TBLUSR[0]['TUFOTO'] = [$this->fnGenDataFile($TBLUSR[0]['TUFOTO'])];
        }
        
        $Hasil = $this->fnFillForm(true, $TBLUSR, "");
        return response()->jSon($Hasil);

    }

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TUUSERIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "TUUSER", "Username", "", true, 0, 20);
        $this->fnCrtObjTxt($Obj, true, "2", "Panel1", "TUNAME", "Full Name", "", true, 0, 100);
        $this->fnCrtObjPwd($Obj, true, "2", "Panel1", "TUPSWD", "Password", "", true, 6, 0);
        $this->fnCrtObjTxt($Obj, true, "0", "Panel1", "TUEMID", "NIP", "", true, 8, 8);
        $this->fnCrtObjRad($Obj, true, "0", "Panel1", "TUDPFG", "Status", "", "1", "Radio", "DSPLY");        
        $this->fnCrtObjRmk($Obj, true, "0", "Panel1", "TUREMK", "Remark", "", false, 100);

        $this->fnCrtObjFle($Obj, true, "0", "Panel2", "TUFOTO", "Profile Picture", "", false, false, ".jpg, .png");

        $this->fnCrtObjDefault($Obj,"TU");

        return response()->jSon($Obj);   
    }

    public function SaveData(Request $request) {

        // BEGIN HEADER
        $arr = json_encode($request->Form);
        $arr = json_decode($arr, true);
        $arr['TUPSWD']['Value'] = $this->fnEncryptPassword($arr['TUPSWD']['Value']);
        $arr['TUFOTO']['Value'] = $this->fnGenBinaryFile($request->File, 'TUFOTO');

        $Delimiter = "";
        $UnikNo = $this->fnGenUnikNo($Delimiter);
        // $s = $this->fnGetSyntax($arr, 'StpTBLUSR', $request->Mode, $UnikNo);   
        $s = $this->fnGetSyntax($request->Source, $request->Username, $request->Mode, 'StpTBLUSR', $arr, $UnikNo);      

        $SQLSTM = $s['all'];
        // END HEADER

        // BEGIN DETAIL
        // $delimiter = $this->fnGenDelimiter();
        if($request->Mode != '3') {
            $arr2 = json_encode($request->Other);
            $arr2 = json_decode($arr2, true);

            $grid2 = [];
            foreach($arr2 as $contain) {
                $grid2 = [];  
                // $grid2['TANOMRIY'] = array('Value'=>'');
                $grid2['TAUSERIY'] = array('Value'=>$arr['TUUSERIY']['Value'],'Tipe'=>'txt');
                $grid2['TAUSER'] = array('Value'=>$arr['TUUSER']['Value'],'Tipe'=>'txt');
                $grid2['TAMENUIY'] = array('Value'=>$contain['TMMENUIY'],'Tipe'=>'txt');
                $grid2['TAACES'] = array('Value'=>$contain['HAKAKSES'],'Tipe'=>'txt');

                // $s = $this->fnGetSyntax($grid2, 'StpTBLUAM', $request->Mode, $UnikNo);
                $s = $this->fnGetSyntax($request->Source, $request->Username, $request->Mode, 'StpTBLUAM', $grid2, $UnikNo);      
                $SQLSTM .= $Delimiter;
                $SQLSTM .= $s['all'];
            }
        }
        // END DETAIL

        $HASIL = $this->fnSetExecuteQuery($SQLSTM,$Delimiter);
        return response()->jSon($HASIL);
        
    }

    public function getQuery(Request $request) {

        $USERIY = $request->TUUSERIY;
        if(!$USERIY){
            $QUERY = DB::select( 
            DB::raw("
                Select TMMENUIY, RTRIM(TMMENU) TMMENU, RTRIM(TMACES) TMACES, '' [HAKAKSES]
                From TBLMNU With(NoLock)
                Order By TMNOMR Asc
            ") );
        } else {
            $QUERY = DB::select( 
            DB::raw("
                Select TMMENUIY, RTRIM(TMMENU) TMMENU, RTRIM(TMACES) TMACES, IsNull(TAACES,'') [HAKAKSES]
                From TBLMNU With(NoLock)
                Left Join TBLUAM With(NoLock) On TAMENUIY = TMMENUIY And TAUSERIY = '".$USERIY."'
                Order By TMNOMR Asc
            ") );
        }

        $TBLSYS = DB::select( 
        DB::raw("
            Select RTRIM(TSSYCD) value, RTRIM(TSSYNM) label From TBLSYS With(NoLock)
            Where TSDSCD = 'MODE'
        ") );

        $menuaccess = [];
        foreach($QUERY as $row) {
            $a = str_split($row->TMACES);
            $b = array_filter($TBLSYS, function($c) use($a) {
                foreach($a as $row2) {
                    if($c->value == $row2) {
                        return true;
                    }
                }
            });

            $HakAkses = array();
            if($USERIY){
                $HakAkses = str_split(rtrim($row->HAKAKSES));
            }

            $menuaccess[] = array("TMMENUIY"=>$row->TMMENUIY,
                                  "TMMENU"=>$row->TMMENU,
                                  "TMACES"=>array_values($b),
                                  "HAKAKSES"=>$HakAkses
            );
        }

        return response()->jSon($menuaccess);
    }

}

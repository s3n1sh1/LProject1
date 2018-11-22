<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Trfind;
use DB;

class cINF_TRFIND extends BaseController {


    public function LoadData(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '001', 'ACTION', 'Action', 50, 'left');
        $this->fnCrtColGrid($Obj, "hdn", 1, 1, '', 'TFFINDIY', 'Finding IY', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '001', 'TFFINO', 'Finding Code', 100, 'left');
        $this->fnCrtColGrid($Obj, "dtp", 1, 1, '001', 'TFDATE', 'Finding Date', 100, 'center');
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFSUBJ', 'Subject', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFDESC', 'Description', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFSOLU', 'Solution', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFRELO', 'Responsible Officer', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFACPL', 'Planning Action', 100, 'left');
        $this->fnCrtColGrid($Obj, "dtp", 0, 1, '001', 'TFACDT', 'Acceptance Date', 100, 'center');
        $this->fnCrtColGrid($Obj, "dtp", 0, 1, '001', 'TFSLDT', 'Solution Date', 100, 'center');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFRDRM', 'Redirect Remark', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFACTN', 'Action', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 0, 0, '', 'TFRSLT', 'Result', 100, 'left');
        // $this->fnCrtColGrid($Obj, "num", 1, 1, '', 'TDLGTH', 'Panjang Karakter', 100, 'left');
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TFREMK', 'Remark', 100, 'left');
        $this->fnCrtColGridDefault($Obj, "TF");

        $Filter = [];
        $Sort = [];
        $Sort[] = array('name'=>'TFDATE','direction'=>'desc');
        $Sort[] = array('name'=>'TFFINO','direction'=>'desc');
        $ColumnGrid = [];

        $TRFIND = TRFIND::noLock()
                ->where([
                    ['TFDLFG', '=', '0'],
                  ]);
        $TRFIND = $this->fnQuerySearchAndPaginate($request, $TRFIND, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TRFIND,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TFFINDIY');

        return response()->jSon($Hasil);        

    }    


    public function FillForm(Request $request) {
        $TRFIND = TRFIND::noLock()
                ->select( $this->fnGetColumnObj($this->ObjectData($request), "", "TRFIN1,TRFIN2") )
                ->leftJoin($this->fnDBRaw("Table","MMAREA"), 'MAAREAIY', '=', 'TFAREAIY')    
                ->leftJoin($this->fnDBRaw("Table","MMBAGN"), 'MBBAGNIY', '=', 'TFBAGNIY')    
                ->leftJoin($this->fnDBRaw("Table","MMSTAF"), 'MCSTAFIY', '=', 'TFSTAFIY')
                ->where([
                    ['TFFINDIY', '=', $request->TFFINDIY],
                  ])
                ->get();

        $TRFIN1 = DB::table($this->fnDBRaw("Table","TRFIN1"))
                ->select( 'T1FILE' )
                ->where([
                    ['T1FINDIY', '=', $request->TFFINDIY],
                    ['T1DLFG', '=', '0'],
                  ])
                ->get();

        $i = 0;
        $TRFIN1_HASIL = [];
        foreach($TRFIN1 as $key => $field) {
            array_push($TRFIN1_HASIL, $this->fnGenDataFile($field->T1FILE));
            $i++;
        }
        $TRFIND[0]['TRFIN1'] = $TRFIN1_HASIL;


        $TRFIN2 = DB::table($this->fnDBRaw("Table","TRFIN2"))
                ->select( 'T2FILE' )
                ->where([
                    ['T2FINDIY', '=', $request->TFFINDIY],
                    ['T2DLFG', '=', '0'],
                  ])
                ->get();

        $i = 0;
        $TRFIN2_HASIL = [];
        foreach($TRFIN2 as $key => $field) {
            array_push($TRFIN2_HASIL, $this->fnGenDataFile($field->T2FILE));
            $i++;
        }
        $TRFIND[0]['TRFIN2'] = $TRFIN2_HASIL;

        $Hasil = $this->fnFillForm(true, $TRFIND, "");
        return response()->jSon($Hasil);  
    }   

    public function ObjectData(Request $request) {
        $Obj = [];

        $this->fnCrtObjTxt($Obj, false, "2", "Panel1", "TFFINDIY", "IY", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "Panel1", "TFFINO", "Finding No", "", false, 0, 0);
        $this->fnCrtObjDtp($Obj, true, "3", "Panel1", "TFDATE", "Date", "", true);        
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFAREAIY", "MAAREA", "MANAME", "Area", "", true, "MMAREA");
        $this->fnCrtObjPop($Obj, true, "3", "Panel2", "TFBAGNIY", "MBBAGN", "MBNAME", "Bagian", "", true, "MMBAGN");

        $this->fnCrtObjTxt($Obj, true, "3", "Panel3", "TFSUBJ", "Subject", "", true);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFDESC", "Description", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFSOLU", "Solution", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel3", "TFREMK", "Remark", "", false, 100);


        $this->fnCrtObjDtp($Obj, true, "3", "Panel41", "TFACDT", "Acceptance Date", "", true);         
        $this->fnCrtObjPop($Obj, true, "3", "Panel41", "TFSTAFIY", "MCSTAF", "MCNAME", "Account Officer", "", false, "MMSTAF");
        // $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFRELO", "Responsible Officer", "", false, 100);   
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFACPL", "Planning Action", "", false, 100);
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFACRM", "Acceptance Remark", "", false, 100); 
        $this->fnCrtObjRmk($Obj, true, "3", "Panel41", "TFRDRM", "Redirect Remark", "", false, 100);    
        $this->fnCrtObjFle($Obj, true, "3", "Panel41", "TRFIN1", "Document", "", false, true, ".jpg, .png");   

        $this->fnCrtObjDtp($Obj, true, "0", "Panel5", "TFSLDT", "Solution Date", "", true);   
        $this->fnCrtObjRmk($Obj, true, "0", "Panel5", "TFRELO", "Responsible Officer", "", true, 100);   
        $this->fnCrtObjRmk($Obj, true, "0", "Panel5", "TFSLRM", "Solution Remark", "", false, 100);  
        $this->fnCrtObjRmk($Obj, true, "0", "Panel6", "TFACTN", "Action", "", true, 100);
        $this->fnCrtObjRmk($Obj, true, "0", "Panel6", "TFRSLT", "Result", "", true, 100); 
        $this->fnCrtObjFle($Obj, true, "0", "Panel6", "TRFIN2", "Document", "", false, true, ".jpg, .png");         

        $this->fnCrtObjDefault($Obj,"TF");

        return response()->jSon($Obj);
    }

    public function SaveData(Request $request) {
        return response()->jSon("");   
    }

}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController; //as BaseController
use Illuminate\Http\Request;
use App\Models\Tblsys;
use DB;

class cLOADGRID extends BaseController {

    public function TBLSYS(Request $request) {

        $Obj = [];
        $this->fnCrtColGrid($Obj, "act", 1, 0, '', 'ACTION', 'Action', 50);
        $this->fnCrtColGrid($Obj, "hdn", 1, 0, '', 'TSDSCD', 'Desc Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYCD', 'System Code', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSSYNM', 'System Name', 100);
        $this->fnCrtColGrid($Obj, "txt", 1, 1, '', 'TSREMK', 'Remark', 100);
        $this->fnCrtColGridDefault($Obj, "TS");

        $Filter = [];
        $Sort = [];
        $ColumnGrid = [];

        $TBLSYS = TBLSYS::noLock()
                ->where([
                    ['TSDLFG', '=', '0'],
                  ]);
        $TBLSYS = $this->fnQuerySearchAndPaginate($request, $TBLSYS, $Obj, $Sort, $Filter, $ColumnGrid);

        $Hasil = array( "Data"=> $TBLSYS,
                        "Column"=> $ColumnGrid,
                        "Sort"=> $Sort,
                        "Filter"=> $Filter,
                        "Key"=> 'TSSYCD');

        return response()->jSon($Hasil); 

    }    

    public function GetRecord(Request $request) {


        $Hasil = DB::noLock()
                ->table($request->Table)
                ->select($request->Field)
                ->where([
                    [$request->Key, '=', $request->KeyValue],
                  ])
                ->get;
        
        dd($Hasil);

        return response()->jSon($Hasil); 

    }    

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use DB;
use Illuminate\Support\Facades\Storage as Storage;

class BaseController extends Controller {

    public function fnEncryptPassword($Password) {
        $JumlahSQL = 0;
        for ($i = 1; $i <= strlen($Password); $i++) {
            $HurufSQL = substr($Password, $i - 1, 1);
            $HurufSQL = ord($HurufSQL);
            $HurufSQL*=$i;
            $JumlahSQL+=$HurufSQL;
        }
        return md5($JumlahSQL);
    }

    public function fnEncrypt($Data, $UserName) {        
        /*
            Encrypt data json
            Untuk ke Quasar (api/index.js)
            Note : 1. Hasil Encrypt harus sama di file api/index.js
                   2. di CheckToken.php harus dirubah juga, jika logic encrypt berubah
        */  
        // $DataJSon = $Data;
        // echo $Data;
        $DataJSon = json_encode($Data);
        $DataJSon = json_decode($DataJSon, true);

        $DataJSon = json_encode($DataJSon['original']);
        
        $DataJSon = strrev($DataJSon);
        $DataJSon = base64_encode($DataJSon);
        return $DataJSon;  
    }
    
    public function fnDecrypt($Data, $UserName) {        
        /*
            Decrypt data json
            Dari Quasar (WiliPlugin.js)
            Note : Hasil Encrypt harus sama di file WiliPlugin.js
        */          
        $DataJSon = $Data;
        $DataJSon = base64_decode($DataJSon);
        $DataJSon = strrev($DataJSon);
        $DataJSon = json_decode($DataJSon);  
        return $DataJSon;      
    }    

    public function fnCrtColGrid(&$Column, $Tipe, $Required, $Tampil, $TipeGrid, $Field, $Label, $Width, 
                                  $Align = "", $PanjangKalimat = 0) {   

        if ($Align=="") {

            switch (strtoupper($Tipe)) {
                case "ACT":
                    $Align="left";
                    break;
                case "DTP":
                    $Align="center";
                    break;
                case "NUM":
                    $Align="right";
                    break;
                case "TXT":
                    $Align="left";
                    break;
            }

        }

        $Column[] = array ("name" => $Field,
                           "label" => $Label,
                           "field" => $Field,
                           "tipe" => strtolower($Tipe),
                           "tipeGrid" => strtolower($Tipe.$TipeGrid),
                           "width" => $Width,
                           "required" => $Required === 1 ? true : false,
                           "tampil" => $Tampil === 1 ? true : false,
                           "align" => $Align,
                           // "sortable" => $Sortable,
                           "sortable" => false,
                           "FilterOperator" => "",
                           "FilterValue" => "",
                           "panjangKalimat" => $PanjangKalimat,
                           "value" => $Field,
                           // "urut" =>isset($SortArray[$Field]) ? $SortArray[$Field] : '',                           
                           "xxxxx" => "xxxx");
    }        


    public function fnQuerySearchAndPaginate($Request, $TableModel, $Obj, &$Sort, &$Filter, &$ColumnGrid) {   

        $Filter = [];
        if (!is_null($Request->cari)) {
            $Filter = $Request->cari;
        }

        if (!is_null($Request->urut)) {
            $Sort = [];
            $Sort = $Request->urut;
        } else {          
            $Sort = json_decode(json_encode($Sort));
        }

        // $b = array_filter($Obj, function ($a) { return $a['tipe'] != 'hdn'; } );
        // $a = array_splice($b,0,100);
        // dd($a);

        $driver = DB::connection()->getConfig("driver");
        if ($driver=="sqlsrv") {
            $B1 = "["; $B2 = "]";
        } else if ($driver=="mysql") { 
            $B1 = ""; $B2 = "";
        } 

        $Col = []; $ColumnGrid = [];
        foreach($Obj as $k => $f) {
            if ($f['tipe'] != 'act') { $Col[] = $f['field']; }
            if ($f['tipe'] != 'hdn') { $ColumnGrid[] = $f; }
            // echo ($v[$k]);
        }

        $TableModel->select($Col);

        foreach ($Sort as $s) {
            $TableModel->orderBy($s->name,$s->direction);
        }

        $condition = "";
        if (!is_null($Request->AllColumns)) {
        //   $TableModel->where(function ($query) use($Col, $Request) {
        //       foreach ($Col as $c) {
        //         // echo "or ".$c." like '%master%'";
        //         $query->orwhere($c, 'LIKE', '%'.$Request->AllColumns.'%');
        //       }            
        //   });
            $or = "";
            foreach ($Col as $c) {
                $nilai = str_replace("'","''",$Request->AllColumns);
                
                $condition = $condition.$or." ".$B1.$c.$B2."  like '%".$nilai."%'";
                $or = " or ";
            }
            $condition = " (".$condition.") ";
        }

        foreach ($Filter as $f) {

            if($condition != "") {
                $condition = $condition."And";
            }

            $nilai = str_replace("'","''",$f->filterValue);

            if($f->filterOperator == 'in') {
                // $TableModel->whereIn($f->field, explode(',', $f->filterValue));
                $condition = $condition." ".$B1.$f->field.$B2." "." in (".explode(',', $nilai).")";
            } else if($f->filterOperator == 'like') {
                // $TableModel->where($f->field, 'LIKE', '%'.$f->filterValue.'%');
                $condition = $condition." ".$B1.$f->field.$B2." "." like '%".$nilai."%'";
            } else if($f->filterOperator == 'likeRight') {
                // $TableModel->where($f->field, 'LIKE', $f->filterValue.'%');
                $condition = $condition." ".$B1.$f->field.$B2." "." like '".$nilai."%'";
            } else {
                // $TableModel->where($f->field, $f->filterOperator, $f->filterValue);
                $condition = $condition." ".$B1.$f->field.$B2." "." ".$f->filterOperator." '".$nilai."'";
            }
        }  
        
        if (!is_null($Request->SubMethod)) {
            if (rtrim($Request->SubMethod) != "") {
                if($condition != "") {
                    $condition = $condition."And";
                }
                if(substr($Request->Method,0,6) === "TBLSYS"){
                    $condition = $condition." TSDSCD = '".$Request->SubMethod."' ";
                }
            }
        }

        if (!is_null($Request->Condition)) {
            if (rtrim($Request->Condition) != "") {
                if($condition != "") {
                    $condition = $condition."And";
                }
                $condition = $condition." ".$Request->Condition." ";
            }
        }

        if($condition != ""){
            $TableModel->whereRaw($condition);
        }

        // Sintax Check SQL
        // $sql = $TableModel->toSql();
        // dd($sql);
        // dd($TableModel);

        // dd($Request->perPage);
        if($Request->perPage != "0") {
            // $page = $Request->page;
            // $perPage = $Request->perPage;
            // $offset = ($page * $perPage) - $perPage;
            // return $TableModel->offset($offset)->limit($perPage)->get();
            return $TableModel->paginate($Request->perPage);
        } else {
            // dd("masuk else");
            return $TableModel->paginate($TableModel->count());
            // return $TableModel->get();
        }
        

    }     

    public function fnCrtColGridDefault(&$Column, $Prefix) {   

        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'RGID', 'Entry By', 100, 'left');
        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'RGDT', 'Entry Date', 100, 'center');
        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'CHID', 'Change By', 100, 'left');
        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'CHDT', 'Change Date', 100, 'center');
        $this->fnCrtColGrid($Column, "num", 0, 0, '', $Prefix.'CHNO', 'Change Num', 100, 'right');
        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'CSID', 'Change System By', 100, 'left');
        $this->fnCrtColGrid($Column, "txt", 0, 0, '', $Prefix.'CSDT', 'Change System Date', 100, 'center');

    }   


    public function fnGetComboData($Table, $TableCode, $Condition = "") {
        $ListData = [];
        switch (strtoupper($Table)) {
            case "TBLSYS":
                $Model = "Tblsys";        

                $Condition = [];
                array_push($Condition, ['TSDSCD','=',$TableCode]);
                array_push($Condition, ['TSDLFG','=','0']);
                array_push($Condition, ['TSDPFG','=','1']);
                // dd($Condition);

                $NamespacedModel = 'App\\Models\\' . $Model;
                $Table = $NamespacedModel::noLock()->select('TSSYCD','TSSYNM')
                            ->where($Condition)
                            ->get();

                foreach($Table as $row) {  // Begin Looping Record ListData  
                    $ListData[] = array("label"=> rtrim($row->TSSYNM),
                                        // "icon"=> 'home',  
                                        "value"=>rtrim($row->TSSYCD)) ; 
                } // End Looping Record ListData
                // dd($ListData);

                break;
            default:
                break;
        }
        return $ListData;

    }

    public function fnCrtObj(&$Obj, $Show, $Mode, $Tipe, $Panel, $Code, $Name, $Description, $Required) {   
        if ($Description == "") { $Description = $Name; }

        $Obj[$Code] = array(
                       "Code" => $Code,
                       "Tipe" => strtolower($Tipe), 
                       "Panel" => $Panel, 
                       "Mode" => $Mode, 
                       "ReadOnly" => false, 
                       "Show" => $Show, 
                       "Required" => $Required,
                       "Name" => $Name,
                       "Description" => $Description, 
                       "Value" => ''
                       );
    }        

    public function fnUpdObj(&$Obj, $Code, $LainLain) {   
        $Obj[$Code] = array_merge($Obj[$Code], $LainLain);
    }   


    public function fnCrtObjDefault(&$Obj, $Prefix) {   

        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."RGID", "Register ID", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."RGDT", "Register Date", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."CHID", "Change ID", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."CHDT", "Change Date", "", false);
        $this->fnCrtObjNum($Obj, true, "3", "PnlXXX", $Prefix."CHNO", "Change Num", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."CSID", "Change System ID", "", false);
        $this->fnCrtObjTxt($Obj, true, "3", "PnlXXX", $Prefix."CSDT", "Change System Date", "", false);

    }  

// $people = array(
//   array(
//     'name' => 'John',
//     'fav_color' => 'green'
//   ),
//   array(
//     'name' => 'Samuel',
//     'fav_color' => 'blue'
//   )
// );

// $found_key = array_search('blue', array_column($people, 'fav_color'));

// echo $found_key;

// //$people[5] = array_merge($people[5], array('value' => 'kelas1'));
// $people[$found_key] = array_merge($people[$found_key], array('value' => 'kelas1'));


// echo "<br>";
// echo "<br>";
// echo "<br>";

// print_r($people);


    public function fnCrtObjTxt(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, 
                                $Min = 0, $Max = 0, $Capital = "", $Prefix = "", $Suffix = "") {   
        /* 
            $Capital = ['Normal','Big','Small'] 
         */        
        if ($Capital == "") { $Capital = "Normal"; }
        $this->fnCrtObj($Obj, $Show, $Mode, "txt", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array("Min" => $Min, 
                                           "Max" => $Max,
                                           "Prefix" => $Prefix,  
                                           "Suffix" => $Suffix,
                                           "Capital" => strtoupper($Capital)) );
    } 

    public function fnCrtObjPwd(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, 
                                $Min = 0, $Max = 0) {
        $this->fnCrtObj($Obj, $Show, $Mode, "pwd", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array( "Min" => $Min, 
                                            "Max" => $Max) );
    } 

    public function fnCrtObjPop(&$Obj, $Show, $Mode, $Panel, $Code, $popCode, $popDesc, $Name, $Description = "", $Required, 
                                $popTable, $popQuery=true, $searchChar=3, $popCondition="", $alias=false) {

        $SubMethod = "";
        $this->fnCrtObj($Obj, $Show, $Mode, "pop", $Panel, $Code, $Name, $Description, $Required);

        if(!$popQuery) {
            if(substr(strtoupper($popTable),0,6) === "TBLSYS") {
                $SubMethod = str_replace("TBLSYS_", "", strtoupper($popTable));
                $popTable = "TBLSYS";
            } 
        }
        if ($alias) {
            $popCode = $Code."_".$popCode;
            $popDesc = $Code."_".$popDesc;
        }

        $this->fnUpdObj($Obj, $Code, array( "ReadOnly" => true,
                                            "Controller" => $popQuery === true ? $popTable : "LOADGRID",
                                            "Method" => $popQuery === true ? "LoadData" : $popTable,
                                            "SubMethod" => $SubMethod,
                                            "Condition" => $popCondition,
                                            "ShowPopUpModal"=>false,
                                            "Pops"=>array($popCode=>array("Value"=>"",
                                                                          "Show"=>true),
                                                          $popDesc=>array("Value"=>"",
                                                                          "Show"=>true),
                                                          $popCode.$popDesc=>array("Value"=>"",
                                                                                   "Disabled"=>false)),
                                            "Grid"=>"",
                                            "Alias" => $alias,
                                            "SearchChar" => $searchChar,
                                            "PopCode" => $popCode,
                                            "PopDesc" => $popDesc,
                                            "PopData" => "") );
    }

    public function fnCrtObjGrd(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Required, 
                                $action, $controller, $methodGrid, $methodObject) {

        $this->fnCrtObj($Obj, $Show, $Mode, "grd", $Panel, $Code, $Name, $Name, $Required);
        $A = strpos(' '.strtoupper($action), strtoupper("A"), 0) > 0 ? true : false; 
        $E = strpos(' '.strtoupper($action), strtoupper("E"), 0) > 0 ? true : false; 
        $D = strpos(' '.strtoupper($action), strtoupper("D"), 0) > 0 ? true : false; 
        $V = strpos(' '.strtoupper($action), strtoupper("V"), 0) > 0 ? true : false; 
        $this->fnUpdObj($Obj, $Code, array( "Action" => array("A"=> array("has"=>$A,"show"=>$A,"disabled"=>!$A), 
                                                              "E"=> array("has"=>$E,"show"=>$E,"disabled"=>!$E), 
                                                              "D"=> array("has"=>$D,"show"=>$D,"disabled"=>!$D), 
                                                              "V"=> array("has"=>$V,"show"=>$V,"disabled"=>!$V), 
                                                            ),
                                            "ActionMode" => "5",
                                            "Controller" => $controller,
                                            "Method" => $methodGrid,
                                            "ShowPopUpModal"=>false,

                                            // "Action" => $action,
                                            // "MethodGrid" => $methodGrid,
                                            // "MethodObject" => $methodObject,
                                            
                                            "GrdValidation" => false,
                                            "GrdAuth" => "",
                                            "GrdKey" => "") );

/*

*/

    }

    public function fnCrtObjDtp(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, $DateType="date",
                                $FormatDisplay="D-MMMM-YYYY", $Min = "", $Max = "") {
        $this->fnCrtObj($Obj, $Show, $Mode, "dtp", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array( "DateType" => $DateType, 
                                            "FormatDisplay" => $FormatDisplay, 
                                            "Min" => $Min, 
                                            "Max" => $Max) );
    } 

    public function fnCrtObjFle(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, $Multiple = false, 
                                $Extensions = "") {
        $this->fnCrtObj($Obj, $Show, $Mode, "fle", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array( "Multiple" => $Multiple,
                                            "Extensions" => $Extensions,
                                            "FileValue" => "" ) );
    } 

    public function fnGenDataFile($fileBinary) {
        if(rtrim($fileBinary) != '') {
            $UnikNo = date('Ymd_His_U');

            Storage::disk('public')->put($UnikNo, $fileBinary);
            
            // Begin Generate base64
            $path = 'public/temp/' . $UnikNo;
            $full_path = Storage::path($path);
            $base64 = base64_encode(Storage::get($path));
            
            $image_data = 'data:'.mime_content_type($full_path) . ';base64,' . $base64;
            // End Generate base64
    
            Storage::delete($path);

            return $image_data;
        } 

        return '';
    }

    public function fnGenBinaryFile($fileData, $fieldName) {
        if($fileData != ""){
            $publicPath = $fileData->$fieldName->Path;
            $imgData    = file_get_contents(base_path().'/storage/app/'.$publicPath);
            $data       = unpack("H*hex", $imgData);
            $data       = '0x'.$data['hex'];

            unlink(base_path().'/storage/app/'.$publicPath);

            return $data;
        }

        return '0x';
    }

    public function fnCrtObjNum(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, 
                                $Decimal = 0, $Prefix = "", $Suffix = "", $Step = 1, $MinValue = 0, $MaxValue = 0) {   
        $this->fnCrtObj($Obj, $Show, $Mode, "num", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array("Decimal" => $Decimal,
                                           "Step" => $Step,
                                           "Prefix" => $Prefix,  
                                           "Suffix" => $Suffix,
                                           "MinValue" => $MinValue, 
                                           "MaxValue" => $MaxValue,) );
    } 

    public function fnCrtObjCmb(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $DefaultValue, 
                                $Jenis, $TableCode, $Required = true, $Condition = "") {   

        $ListData = $this->fnGetComboData("TBLSYS", $TableCode, $Condition = "");
        /* 
            $Jenis = ['Single','Radio','Multiple'] 
         */
        $this->fnCrtObj($Obj, $Show, $Mode, "cmb", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array("Jenis" => strtoupper($Jenis),
                                           "DefaultValue" => $DefaultValue,
                                           "Options" => $ListData) );
    } 

    public function fnCrtObjRad(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $DefaultValue, 
                                $Jenis, $TableCode, $Required = true, $Condition = "") {   

        $ListData = $this->fnGetComboData("TBLSYS", $TableCode, $Condition = "");
        /* 
            $Jenis = ['Radio','Toggle'] 
         */
        $this->fnCrtObj($Obj, $Show, $Mode, "rad", $Panel, $Code, $Name, $Description, $Required);
        $this->fnUpdObj($Obj, $Code, array("Title" => $Name,
                                           "Jenis" => strtolower($Jenis),
                                           "DefaultValue" => $DefaultValue,
                                           "Value" => strtolower($Jenis) == 'toggle' ? [] : '',
                                           "Options" => $ListData) );
    } 

    public function fnCrtObjTog(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $DefaultValue, 
                                $Left = false) {   
        /* 
            $Value = true || false
         */
        $this->fnCrtObj($Obj, $Show, $Mode, "tog", $Panel, $Code, $Name, $Description, true);
        $this->fnUpdObj($Obj, $Code, array("Left" => $Left,
                                           "DefaultValue" => $DefaultValue) );

    } 

    public function fnCrtObjRmk(&$Obj, $Show, $Mode, $Panel, $Code, $Name, $Description = "", $Required, 
                                $Height = 100) {   
        $this->fnCrtObj($Obj, $Show, $Mode, "rmk", $Panel, $Code, $Name, $Description, $Required );
        $this->fnUpdObj($Obj, $Code, array("Height" => $Height) );
    } 
 

    public function fnCrtPanel($Name, $Tipe, $Width) {

        return array("Name" => $Left,
                     "Tipe" => $Name,
                     "Width" => $Width,
                     "XXXX" => "XXXX");
    } 

    public function fnGetRec($Table, $Field, $FieldKey, $FieldKeyValue, $Condition) {

        // $SP = DB::select('Select dbo.FnsGetMultiRowInOneField(\'STPPARM\',\'StpTBLMNU\',\'\')');
        // $Rec = DB::select("
        //     Select 
        //       ".$Field." 
        //     From ".$Table." 
        //     Where ".$FieldKey." = '".$FieldKeyValue."'
        //     ".$Condition."");

        $Rec = DB::table($Table)
                        ->Select(explode(',',$Field))
                        ->where($FieldKey,'=',$FieldKeyValue)
                        ->where(function ($query) use($Condition) {
                              if ( is_array($Condition) ) {
                                $query->where($Condition);
                              }
                          })
                        ->get();
        
        if (count($Rec) === 0) {
            return [];
        } else {
            return $Rec[0];
        }
    
    }

    public function fnGetColumnObj($ObjectData, $AddObj = "", $DeleteObj = "") {

        $a = $ObjectData;
        $a = json_decode(json_encode($a))->original;

        foreach($a as $key => $value) {
            // $cols[] = $key;
            if ($value->Tipe == "fle") {
                if(!$value->Multiple){
                    $cols[] = $key;
                }
            } else {
                $cols[] = $key;
            }

            if($value->Tipe == "pop") {
                // $cols[] = $value->PopCode;
                // $cols[] = $value->PopDesc;
                if($value->Alias) {
                    // ini karena selectnya ada alias...
                    $cols[] = str_replace("_",".",$value->PopCode)." As ".$value->PopCode;
                    $cols[] = str_replace("_",".",$value->PopDesc)." As ".$value->PopDesc;
                } else {
                    $cols[] = $value->PopCode;
                    $cols[] = $value->PopDesc;
                }      
            }
        }

        if ($AddObj != "") {
            $arrAdd = explode(",",$AddObj);
            foreach($arrAdd as $key) {
                $cols[] = $key;
            }
        }
        if ($DeleteObj != "") {
            $arrDel = explode(",",$DeleteObj);
            foreach($arrDel as $key) {                
                $cols = array_diff($cols, array($key));
            }
            $cols = array_values($cols);
        }

        return $cols;
    }

    function fnDBRaw($Tipe, $Text, $Alias = "") {
        $Hasil = "";
        if ($Alias != "") {
            $Alias = " As ".$Alias;
        }
        switch (strtoupper($Tipe)) {
            case "TABLE":
                $driver = DB::connection()->getConfig("driver");
                if ($driver=="sqlsrv") {
                    $noLock = $Text." with (nolock) ".$Alias;
                } else if ($driver=="mysql") { 
                    $noLock = $Text."".$Alias;
                } 

                $Hasil = DB::raw($noLock);
                break;
            default:
                $Hasil = $Text;
                break;
        }
        return $Hasil;
    }

    function fnGenDelimiter($length = 10) {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '~`!@$^*()_-{}[]:;<>?|0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function fnGenUnikNo(&$Delimiter) {
        $Delimiter = $this->fnGenDelimiter();
        // dd(date('Ymd_H:i:s:u _')); 
        return date('Ymd_His_').strrev($Delimiter);
    }

    function fnGetSintaxCRUD ($AllField, $UserName, $Mode, $Fields, $UnikNo) {

        $FinalField = array_filter( $AllField,
                            function ($key) use ($Fields) {
                                return in_array($key, $Fields);
                            },
                            ARRAY_FILTER_USE_KEY
                        );

        $Prefix = SubStr($Fields[0],0,2);

        switch ($Mode) {
            case "1":
                $FinalField = array_merge($FinalField, array(
                                            $Prefix."DLFG"=>"0",
                                            $Prefix."RGID"=>$UserName,
                                            $Prefix."RGDT"=>Date("Y-m-d H:i:s"),
                                            $Prefix."CHID"=>$UserName,
                                            $Prefix."CHDT"=>Date("Y-m-d H:i:s"),
                                            $Prefix."CHNO"=>"0",
                                            $Prefix."CSID"=>$UserName,
                                            $Prefix."CSDT"=>Date("Y-m-d H:i:s"),
                                            $Prefix."CSNO"=>$UnikNo
                                        ));
                break;
            case "2": 
                $FinalField = array_merge($FinalField, array(
                                            $Prefix."DLFG"=>"0",
                                            $Prefix."CHID"=>$UserName,
                                            $Prefix."CHDT"=>Date("Y-m-d H:i:s"),
                                            // $Prefix."CHNO"=>$AllField[$Prefix."CHNO"]+1,
                                            $Prefix."CHNO"=>DB::raw($Prefix."CHNO  + 1") ,
                                            $Prefix."CSID"=>$UserName,
                                            $Prefix."CSDT"=>Date("Y-m-d H:i:s")
                                        ));           
                break;
            case "3":
                $FinalField = array_merge($FinalField, array(
                                            $Prefix."DLFG"=>"1",
                                            $Prefix."CHID"=>$UserName,
                                            $Prefix."CHDT"=>Date("Y-m-d H:i:s"),
                                            $Prefix."CHNO"=>DB::raw($Prefix."CHNO  + 1") ,
                                            $Prefix."CSID"=>$UserName,
                                            $Prefix."CSDT"=>Date("Y-m-d H:i:s")
                                        ));           
                break;
            default:
                $FinalField = array_merge($FinalField, array(
                                            $Prefix."CSID"=>$UserName,
                                            $Prefix."CSDT"=>Date("Y-m-d H:i:s")
                                        ));                       
                break;
        }

        return $FinalField;
    }

    public function fnCheckBFCS ($Obj) {

        if ($Obj["Mode"]!="1") { // Begin Check Change System Date
            if (is_array($Obj["Key"])) {
                $Prefix = SubStr($Obj["Key"][0],0,2);
                $Key = $Obj["Key"][0];
                $arrCondition = [];
                for ($i=1; $i < count($Obj["Key"]); $i++) {
                    array_push($arrCondition,array($Obj["Key"][$i],
                                                   '=',
                                                   $Obj["Data"][$Obj["Key"][$i]]
                                                   ));
                }
            } else {
                $Prefix = SubStr($Obj["Key"],0,2);
                $Key = $Obj["Key"];
                $arrCondition = "";
            }

            $arrCSDT = $this->fnGetRec($Obj["Table"], 
                                        $Prefix.'DLFG'.",".$Prefix.'CSDT'.",".$Prefix.'CSID', 
                                        $Key, $Obj["Data"][$Key], $arrCondition) ;

            if (count($arrCSDT) == 0) {
                return array("success"=>false, "message"=>"Data not found!!! Please refresh your data");
            }
            // var_dump($arrCSDT);
            $CSDT = $Prefix.'CSDT';
            $CSID = $Prefix.'CSID';
            $DLFG = $Prefix.'DLFG';
            // echo "(".$arrCSDT->$DLFG.")"; 
            if ($arrCSDT->$DLFG == '1') {
                return array("success"=>false, "message"=>"Data has been delete!!! Please refresh your data");
            }

            if ($arrCSDT->$CSDT != $Obj["Data"][$Prefix.'CSDT']) {
                return array("success"=>false, "message"=>"This Record already change by '".$arrCSDT->$CSID. "', Please refresh your data!!!");            
            }

        }  // End Check Change System Date

        // return array("success"=>false, "message"=>"Coba Coba");

        if ($Obj["Menu"]!="") {  // Begin Check BackDate 
            $arrMENU = $this->fnGetRec("TBLMNU","TMMENU,TMBCDT,TMFWDT", "TMURLW", $Obj["Menu"], "") ;

            if (count($arrMENU) == 0) {
                return array("success"=>false, "message"=>"Menu not found Back date & Forward date paramenter (Wrong Menu) ");
            }

            $TGL = date('Ymd');
            $TGL_TRANS = $Obj["Data"][$Obj["FieldTransDate"]]; //"20181120";
            $diff1 = date_diff(date_create($TGL),date_create($TGL_TRANS));
            $daysdiff = $diff1->format("%R%a");
            $daysdiff = abs($daysdiff);
            // echo "<hr>"."TGL : (".$TGL.") ";
            // echo "<hr>"."TGL_TRANS : (".$TGL_TRANS.") ";
            // echo "<hr>";

            if ($TGL < $TGL_TRANS) {
                // echo "masuk (ForwardDate) ".$daysdiff." , menu ".$arrMENU->TMFWDT." <hr>";
                if ($arrMENU->TMFWDT < $daysdiff) {
                  return array("success"=>false, "message"=>"Forward Date only ".$arrMENU->TMFWDT." days");
                } 
            } else if ($TGL > $TGL_TRANS) {
                // echo "masuk (BackDate) ".$daysdiff." , menu ".$arrMENU->TMBCDT." <hr>";
                if ($arrMENU->TMBCDT < $daysdiff) {
                  return array("success"=>false, "message"=>"Back Date only ".$arrMENU->TMBCDT." days");
                } 
            } 

        } // End Check BackDate

        return array("success"=>true, "message"=>"");            
    }


    public function fnTBLNOR ($UserName, $Table) {
        $NoIY = 1;
        $TBLNOUR = DB::table("TBLNOR")
                        ->Select(['TNTABL' , 'TNNOUR'])
                        ->where('TNTABL','=',$Table)
                        // ->where('TNTABL','=','TBLMNU')
                        ->get();
        if (count($TBLNOUR)) {
            // var_dump($TBLNOUR[0]);
            $NoIY = ($TBLNOUR[0]->TNNOUR+1);
            $TBLNOR = array("TNTABL"=>$Table,"TNNOUR"=>$NoIY);
            $FinalTBLNOR = $this->fnGetSintaxCRUD ($TBLNOR, $UserName, '1', ['TNTABL','TNNOUR'], "" );
            DB::table('TBLNOR')
                ->where('TNTABL','=',$Table)
                ->update($FinalTBLNOR);   
        } else {
            $TBLNOR = array("TNTABL"=>$Table,"TNNOUR"=>"1");
            $FinalTBLNOR = $this->fnGetSintaxCRUD ($TBLNOR, $UserName, '1', ['TNTABL','TNNOUR'], "" );
            DB::table('TBLNOR')
                ->insert($FinalTBLNOR);   
        }
        return $NoIY;
    }


    public function fnTBLSLF ($UserName, $QueryLog) {
        $TBLSLF = array("TQUSER"=>$UserName,
                        "TQSTMT"=>$this->fnRawSql($QueryLog),
                        "TQREMK"=>"",);
        $FinalTBLSLF = $this->fnGetSintaxCRUD ($TBLSLF, $UserName, '1', ['TQUSER','TQSTMT','TQREMK'], "" );
        DB::table('TBLSLF')
            ->insert($FinalTBLSLF);   
    }


    public function fnTBLELF ($UserName, $QueryLog, $Error) {
        if (isset($Error->errorInfo)) {
            $errorInfo = $Error->errorInfo;
        } else {
            $errorInfo = [];
            array_push($errorInfo,'Manual');
            array_push($errorInfo,'');
            array_push($errorInfo,$Error->getMessage());
        }
        $TBLELF = array("TEUSER"=>$UserName,
                        "TEERST"=>$errorInfo[0], //error state : $errorInfo[0]
                        "TEERNO"=>$errorInfo[1], //error code  : $errorInfo[1]
                        "TEERMS"=>$errorInfo[2], //error message : $errorInfo[2]
                        "TESPTR"=>"fnTBLELF",
                        "TESTMT"=>$this->fnRawSql($QueryLog),
                        "TEREMK"=>"");
        $FinalTBLELF = $this->fnGetSintaxCRUD ($TBLELF, $UserName, '1', 
                        ['TEUSER','TEERST','TEERNO','TEERMS','TESPTR','TESTMT','TEREMK'], "" );
        DB::table('TBLELF')
            ->insert($FinalTBLELF);   
    }

    public function fnSetExecuteQuery ($Stp, $UserName, $DELIMITER = "") {

        try {
            DB::enableQueryLog();
            DB::transaction(function () use($Stp) {
                $HasilStp = $Stp();
                if (isset($HasilStp)) {
                    abort(404, $HasilStp['message']);
                }
            });
            // $a = DB::getQueryLog();
            $this->fnTBLSLF ($UserName, DB::getQueryLog());
            $BerHasil = true;
        } catch (\Exception $e){ 
            // $a = DB::getQueryLog();
            // var_dump($e);
            // $message = $this->fnGetErrorMessage($e);
            $message = $e->getMessage();
            // $message = $e->getCode();
            // $message = $e->getSql();
            // $message = $e->errorInfo[0];
            // $message = $e->errorInfo[1];
            // $message = $e->errorInfo[2];
            // echo ($e->errorInfo[0]);
            // $a = saveSqlError($e);
            // $message = $a->sql;
            $this->fnTBLELF ($UserName, DB::getQueryLog(), $e);        
            $BerHasil = false;
        }

        if ($BerHasil) {
            $Hasil = array("success"=> true, "message"=> "*** Success ***");
        } else {
            $Hasil = array("success"=> false, "message"=> $message);
        }

            // var_dump($a);
            // echo $a->getSql();
        // $this->fnRawSql($a);
        return $Hasil;

    }



    public function fnRawSql($sintax) {
        if (is_array($sintax)) {
            $data = "";
            foreach ($sintax as $vSql) {
                $sql = $vSql['query'];
                $bindings = $vSql['bindings'];

                foreach ($bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $bindings[$i] = "'$binding'";
                        }
                    }
                }

                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
                $query = vsprintf($query, $bindings);
                $data .= $query."
"; // ini fungsinya untuk new line (jangan di naikin)
            }       
            // echo $data; 
            return $data;
        } 
    }

    public function saveSqlError($exception) {
        
        $sql = $exception->getSql();
        $bindings = $exception->getBindings();

        // Process the query's SQL and parameters and create the exact query
        foreach ($bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }
        }
        $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
        $query = vsprintf($query, $bindings);

        // Here's the part you need
        $errorInfo = $exception->errorInfo;

        $data = [
            'sql'        => $query,
            'message'    => isset($errorInfo[2]) ? $errorInfo[2] : '',
            'sql_state'  => $errorInfo[0],
            'error_code' => $errorInfo[1]
        ];

        return $data;
        // Now store the error into database, if you want..
        // ....
    }

    public function fnSetExecuteQueryXXX ($SQLSTM, $DELIMITER = "") {
    
        try{
            // DB::insert($s['all']);
            // DB::insert($s['syntax'],$s['params']);

            // DB::unprepared("Exec StpTBLUSR '1','CobaCoba','laravel','','','edseds','abcdef','a60937eba57758ed45b6d3e91e8659f3','12345678','','','','','','','','1','','';
            //             Exec StpTBLUSR '1','CobaCoba','laravel','','','edsedq','abcdef','a60937eba57758ed45b6d3e91e8659f3','12345678','','','','','','','','1','','';
            //             ");

            // $a = "Exec StpTBLUSR '1','CobaCoba','laravel','','','edseds','abcdef','a60937eba57758ed45b6d3e91e8659f3','12345678','','','','','','','','1','','';|||||||Exec StpTBLUSR '1','CobaCoba','laravel','','','edsedq','abcdef','a60937eba57758ed45b6d3e91e8659f3','12345678','','','','','','','','1','','';|||||||
            // ";
            // DB::unprepared("Exec StpExecuteQuery 'CobaCoba','laravel', '".str_replace("'","''",$a)."', '|||||||' ");

            // DB::insert("Exec StpExecuteQuery :user, :source, :sql, :delimiter ",
            //             array(":user"=> "CobaCoba", 
            //                   ":source"=> "laravel",
            //                 //   ":sql"=> str_replace("'","''",$SQLSTM),
            //                   ":sql"=> $SQLSTM,
            //                   ":delimiter"=> $DELIMITER,
            //                   ) );


            DB::enableQueryLog();
            DB::transaction(function () use($SQLSTM) {
                // DB::table('TBLDSC')
                //     ->where('TDDSCD','=',$FinalField['TDDSCD'])                    
                //     ->update($FinalField);

                // DB::table('TBLDSC')
                //     ->where('TDDSCD','=',$FinalField['TDDSCD'])                    
                //     ->update(['TDREMK'=>'wwww']);          

                $IY = [];
                foreach($SQLSTM as $k => $Data) {
                    
                    $Prefix = substr(array_values($Data['Field'])[0],0,2);

                    if(isset($Data['IyReff'])) { // Begin IY Reference
                        if (is_array($Data['IyReff'])) {
                            foreach($Data['IyReff'] as $fReff => $kReff) {
                                $Data['Data'][$fReff] = $IY[$kReff];
                            }

                        }
                    } // End IY Reference

                    switch ($Data['Mode']) {
                        case "I":
                            if(isset($Data['Iy'])) { // Begin Insert TBLNOR
                                if($Data['Iy']!="") {
                                    $NoIY = 1;
                                    $TBLNOUR = DB::table("TBLNOR")
                                                    ->Select(['TNTABL' , 'TNNOUR'])
                                                    ->where('TNTABL','=',$Data['Table'])
                                                    // ->where('TNTABL','=','TBLMNU')
                                                    ->get();
                                    if (count($TBLNOUR)) {
                                        // var_dump($TBLNOUR[0]);
                                        $NoIY = ($TBLNOUR[0]->TNNOUR+1);
                                        $TBLNOR = array("TNTABL"=>$Data['Table'],"TNNOUR"=>$NoIY);
                                        $FinalTBLNOR = $this->fnGetSintaxCRUD ($TBLNOR, $UserName, '1', ['TNTABL','TNNOUR'], "" );
                                        DB::table('TBLNOR')
                                            ->where('TNTABL','=',$Data['Table'])
                                            ->update($FinalTBLNOR);   
                                    } else {
                                        $TBLNOR = array("TNTABL"=>$Data['Table'],"TNNOUR"=>"1");
                                        $FinalTBLNOR = $this->fnGetSintaxCRUD ($TBLNOR, $UserName, '1', ['TNTABL','TNNOUR'], "" );
                                        DB::table('TBLNOR')
                                            ->insert($FinalTBLNOR);   
                                    }
                                    $Data['Data'][$Data['Iy']] = $NoIY;
                                    $IY[$Data['Iy']] = $NoIY;
                                }
                            } // End Insert TBLNOR


                            $FinalField = $this->fnGetSintaxCRUD ($Data['Data'], $UserName, '1', $Data['Field'], $Data['UnikNo'] );
                            DB::table($Data['Table'])->insert($FinalField);
                            break;
                        case "U":
                            $FinalField = $this->fnGetSintaxCRUD ($Data['Data'], $UserName, '2', $Data['Field'], $Data['UnikNo'] );
                            DB::table($Data['Table'])
                                ->where($Data['Where'])
                                ->update($FinalField);
                            break;
                        case "D":
                            DB::table($Data['Table'])
                                ->where($Data['Where'])      
                                ->delete();
                            break;
                        case "DD":
                            $FinalField = $this->fnGetSintaxCRUD ($Data['Data'], $UserName, '3', $Data['Field'], $Data['UnikNo'] );
                            DB::table($Data['Table'])
                                ->where($Data['Where'])
                                ->update($FinalField);
                            break;

                    }

                }

            });
            $a = DB::getQueryLog();

            $BerHasil = true;
        } catch (\Exception $e){ 
            // var_dump($e);
            // $message = $this->fnGetErrorMessage($e);
            $message = $e->getMessage();
            // $message = $e->getCode();
            // $message = $e->getSql();
            // $message = $e->errorInfo[0];
            // $message = $e->errorInfo[1];
            // $message = $e->errorInfo[2];
            // $a = saveSqlError($e);
            // $message = $a->sql;
        

            $BerHasil = false;
        }

        if ($BerHasil) {
            $Hasil = array("success"=> true, "message"=> "*** Success ***");
        } else {
            $Hasil = array("success"=> false, "message"=> $message);
        }

        return $Hasil;
    }




    function fnGetErrorMessage($err) {
        $msg = "";
        switch ($err->getCode()) {
            case "22001":
                $msg = "[Field] Data value is too long";
                break;
            case "23000":
                $msg = "[Primary Key] Duplicate Data, Please check again!!!";
                break;
            default:
                // $trace = $err->getTrace();
                // $message =  $err->getMessage().' in '.$err->getFile().' on line '.$err->getLine().' called from '.$trace[0]['file'].' on line '.$trace[0]['line'];
                $msg = $err->getMessage();
                break;
        }
        return $msg;
    }

    function fnFillForm($Sukses, $Hasil, $Message) {   
        if ($Hasil == "") {
            $Data = [];
        } else {
            $Data = $Hasil->count() != 0 ? $Hasil[0] : [];
        }
        return array( "success"=> $Sukses, "data"=> $Data, "message"=> $Message);
    }

}

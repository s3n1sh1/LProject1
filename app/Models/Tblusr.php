<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TUUSERIY
 * @property string $TUUSER
 * @property string $TUNAME
 * @property string $TUPSWD
 * @property string $TUEMID
 * @property string $TUDEPT
 * @property string $TUMAIL
 * @property string $TUWELC
 * @property boolean $TUEXPP
 * @property string $TUEXPD
 * @property int $TUEXPV
 * @property int $TULGCT
 * @property string $TULSLI
 * @property string $TULSLO
 * @property string $TUREMK
 * @property string $TURGID
 * @property string $TURGDT
 * @property string $TUCHID
 * @property string $TUCHDT
 * @property int $TUCHNO
 * @property boolean $TUDLFG
 * @property boolean $TUDPFG
 * @property boolean $TUPTFG
 * @property int $TUPTCT
 * @property string $TUPTID
 * @property string $TUPTDT
 * @property string $TUSRCE
 * @property string $TUUSRM
 * @property string $TUITRM
 * @property string $TUCSDT
 * @property string $TUCSID
 */
class Tblusr extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblusr';

    /**
     * @var array
     */
    protected $fillable = ['TUUSERIY', 'TUUSER', 'TUNAME', 'TUPSWD', 'TUEMID', 'TUDEPT', 'TUMAIL', 'TUWELC', 'TUEXPP', 'TUEXPD', 'TUEXPV', 'TULGCT', 'TULSLI', 'TULSLO', 'TUREMK', 'TURGID', 'TURGDT', 'TUCHID', 'TUCHDT', 'TUCHNO', 'TUDLFG', 'TUDPFG', 'TUPTFG', 'TUPTCT', 'TUPTID', 'TUPTDT', 'TUSRCE', 'TUUSRM', 'TUITRM', 'TUCSDT', 'TUCSID'];

    // protected $casts=['TUCSDT'=>'datetime:Y-m-d H:i:s'];

}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TGMGCDIY
 * @property string $TGMGCD
 * @property string $TGMGDS
 * @property string $TGMSTL
 * @property string $TGMGSC
 * @property string $TGREMK
 * @property string $TGRGID
 * @property string $TGRGDT
 * @property string $TGCHID
 * @property string $TGCHDT
 * @property int $TGCHNO
 * @property boolean $TGDLFG
 * @property boolean $TGDPFG
 * @property boolean $TGPTFG
 * @property int $TGPTCT
 * @property string $TGPTID
 * @property string $TGPTDT
 * @property string $TGSRCE
 * @property string $TGUSRM
 * @property string $TGITRM
 * @property string $TGCSDT
 * @property string $TGCSID
 */
class Tblsmg extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Tblsmg';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TGMGCDIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TGMGCD', 'TGMGDS', 'TGMSTL', 'TGMGSC', 'TGREMK', 'TGRGID', 'TGRGDT', 'TGCHID', 'TGCHDT', 'TGCHNO', 'TGDLFG', 'TGDPFG', 'TGPTFG', 'TGPTCT', 'TGPTID', 'TGPTDT', 'TGSRCE', 'TGUSRM', 'TGITRM', 'TGCSDT', 'TGCSID'];


    protected $casts=['TGCSDT'=>'datetime:Y-m-d H:i:s'];

}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property string $TRPRCD
 * @property string $TRPRNM
 * @property float $TRSYV1
 * @property float $TRSYV2
 * @property float $TRSYV3
 * @property string $TRSYT1
 * @property string $TRSYT2
 * @property string $TRSYT3
 * @property string $TRREMK
 * @property string $TRRGID
 * @property string $TRRGDT
 * @property string $TRCHID
 * @property string $TRCHDT
 * @property int $TRCHNO
 * @property boolean $TRDLFG
 * @property boolean $TRDPFG
 * @property boolean $TRPTFG
 * @property int $TRPTCT
 * @property string $TRPTID
 * @property string $TRPTDT
 * @property string $TRSRCE
 * @property string $TRUSRM
 * @property string $TRITRM
 * @property string $TRCSDT
 * @property string $TRCSID
 * @property string $TRCSNO
 */
class Tblprm extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblprm';

    /**
     * @var array
     */
    protected $fillable = ['TRPRCD', 'TRPRNM', 'TRSYV1', 'TRSYV2', 'TRSYV3', 'TRSYT1', 'TRSYT2', 'TRSYT3', 'TRREMK', 'TRRGID', 'TRRGDT', 'TRCHID', 'TRCHDT', 'TRCHNO', 'TRDLFG', 'TRDPFG', 'TRPTFG', 'TRPTCT', 'TRPTID', 'TRPTDT', 'TRSRCE', 'TRUSRM', 'TRITRM', 'TRCSDT', 'TRCSID', 'TRCSNO'];

}

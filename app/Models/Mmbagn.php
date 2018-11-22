<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $MBBAGNIY
 * @property string $MBBAGN
 * @property string $MBNAME
 * @property string $MBREMK
 * @property string $MBRGID
 * @property string $MBRGDT
 * @property string $MBCHID
 * @property string $MBCHDT
 * @property int $MBCHNO
 * @property boolean $MBDLFG
 * @property boolean $MBDPFG
 * @property boolean $MBPTFG
 * @property int $MBPTCT
 * @property string $MBPTID
 * @property string $MBPTDT
 * @property string $MBSRCE
 * @property string $MBUSRM
 * @property string $MBITRM
 * @property string $MBCSDT
 * @property string $MBCSID
 * @property string $MBCSNO
 */
class Mmbagn extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmbagn';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MBBAGNIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['MBBAGN', 'MBNAME', 'MBREMK', 'MBRGID', 'MBRGDT', 'MBCHID', 'MBCHDT', 'MBCHNO', 'MBDLFG', 'MBDPFG', 'MBPTFG', 'MBPTCT', 'MBPTID', 'MBPTDT', 'MBSRCE', 'MBUSRM', 'MBITRM', 'MBCSDT', 'MBCSID', 'MBCSNO'];

}

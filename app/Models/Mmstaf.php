<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $MCSTAFIY
 * @property string $MCSTAF
 * @property string $MCNAME
 * @property string $MCTITL
 * @property string $MCREMK
 * @property string $MCRGID
 * @property string $MCRGDT
 * @property string $MCCHID
 * @property string $MCCHDT
 * @property int $MCCHNO
 * @property boolean $MCDLFG
 * @property boolean $MCDPFG
 * @property boolean $MCPTFG
 * @property int $MCPTCT
 * @property string $MCPTID
 * @property string $MCPTDT
 * @property string $MCSRCE
 * @property string $MCUSRM
 * @property string $MCITRM
 * @property string $MCCSDT
 * @property string $MCCSID
 * @property string $MCCSNO
 */
class Mmstaf extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmstaf';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MCSTAFIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['MCSTAF', 'MCNAME', 'MCTITL', 'MCREMK', 'MCRGID', 'MCRGDT', 'MCCHID', 'MCCHDT', 'MCCHNO', 'MCDLFG', 'MCDPFG', 'MCPTFG', 'MCPTCT', 'MCPTID', 'MCPTDT', 'MCSRCE', 'MCUSRM', 'MCITRM', 'MCCSDT', 'MCCSID', 'MCCSNO'];

}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TCNOMRIY
 * @property int $TCMENUIY
 * @property string $TCDESC
 * @property string $TCREMK
 * @property string $TCRGID
 * @property string $TCRGDT
 * @property string $TCCHID
 * @property string $TCCHDT
 * @property int $TCCHNO
 * @property boolean $TCDLFG
 * @property boolean $TCDPFG
 * @property boolean $TCPTFG
 * @property int $TCPTCT
 * @property string $TCPTID
 * @property string $TCPTDT
 * @property string $TCSRCE
 * @property string $TCUSRM
 * @property string $TCITRM
 * @property string $TCCSDT
 * @property string $TCCSID
 * @property string $TCCSNO
 * @property TBLMNU $tBLMNU
 */
class Tblcmt extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblcmt';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TCNOMRIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TCMENUIY', 'TCDESC', 'TCREMK', 'TCRGID', 'TCRGDT', 'TCCHID', 'TCCHDT', 'TCCHNO', 'TCDLFG', 'TCDPFG', 'TCPTFG', 'TCPTCT', 'TCPTID', 'TCPTDT', 'TCSRCE', 'TCUSRM', 'TCITRM', 'TCCSDT', 'TCCSID', 'TCCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tBLMNU()
    {
        return $this->belongsTo('App\Models\TBLMNU', 'TCMENUIY', 'TMMENUIY');
    }
}

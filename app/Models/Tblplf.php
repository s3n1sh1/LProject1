<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TZNOMRIY
 * @property int $TZMENUIY
 * @property string $TZNOTR
 * @property string $TZKEYC
 * @property string $TZKEYD
 * @property string $TZSTAT
 * @property string $TZSTTM
 * @property string $TZENTM
 * @property string $TZSTMT
 * @property string $TZREMK
 * @property string $TZRGID
 * @property string $TZRGDT
 * @property string $TZCHID
 * @property string $TZCHDT
 * @property int $TZCHNO
 * @property boolean $TZDLFG
 * @property boolean $TZDPFG
 * @property boolean $TZPTFG
 * @property int $TZPTCT
 * @property string $TZPTID
 * @property string $TZPTDT
 * @property string $TZSRCE
 * @property string $TZUSRM
 * @property string $TZITRM
 * @property string $TZCSDT
 * @property string $TZCSID
 * @property string $TZCSNO
 * @property TBLMNU $tBLMNU
 */
class Tblplf extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblplf';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TZNOMRIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TZMENUIY', 'TZNOTR', 'TZKEYC', 'TZKEYD', 'TZSTAT', 'TZSTTM', 'TZENTM', 'TZSTMT', 'TZREMK', 'TZRGID', 'TZRGDT', 'TZCHID', 'TZCHDT', 'TZCHNO', 'TZDLFG', 'TZDPFG', 'TZPTFG', 'TZPTCT', 'TZPTID', 'TZPTDT', 'TZSRCE', 'TZUSRM', 'TZITRM', 'TZCSDT', 'TZCSID', 'TZCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tBLMNU()
    {
        return $this->belongsTo('App\Models\TBLMNU', 'TZMENUIY', 'TMMENUIY');
    }
}

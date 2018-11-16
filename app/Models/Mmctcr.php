<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $MCCCNOIY
 * @property string $MCCCNO
 * @property string $MCNAME
 * @property int $MCPCNOIY
 * @property string $MCDEPT
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
 * @property Mmprof $mmprof
 * @property Bbhead[] $bbheads
 */
class Mmctcr extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmctcr';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MCCCNOIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['MCCCNO', 'MCNAME', 'MCPCNOIY', 'MCDEPT', 'MCREMK', 'MCRGID', 'MCRGDT', 'MCCHID', 'MCCHDT', 'MCCHNO', 'MCDLFG', 'MCDPFG', 'MCPTFG', 'MCPTCT', 'MCPTID', 'MCPTDT', 'MCSRCE', 'MCUSRM', 'MCITRM', 'MCCSDT', 'MCCSID', 'MCCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mmprof()
    {
        return $this->belongsTo('App\Models\Mmprof', 'MCPCNOIY', 'MFPCNOIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bbheads()
    {
        return $this->hasMany('App\Models\Bbhead', 'BACCNOIY', 'MCCCNOIY');
    }
}

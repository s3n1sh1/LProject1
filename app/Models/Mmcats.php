<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $C2C2CDIY
 * @property string $C2C2CD
 * @property string $C2NAME
 * @property int $C2C1CDIY
 * @property string $C2REMK
 * @property string $C2RGID
 * @property string $C2RGDT
 * @property string $C2CHID
 * @property string $C2CHDT
 * @property int $C2CHNO
 * @property boolean $C2DLFG
 * @property boolean $C2DPFG
 * @property boolean $C2PTFG
 * @property int $C2PTCT
 * @property string $C2PTID
 * @property string $C2PTDT
 * @property string $C2SRCE
 * @property string $C2USRM
 * @property string $C2ITRM
 * @property string $C2CSDT
 * @property string $C2CSID
 * @property string $C2CSNO
 * @property Mmcatg $mmcatg
 * @property Bbline[] $bblines
 */
class Mmcats extends BaseModel
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'C2C2CDIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['C2C2CD', 'C2NAME', 'C2C1CDIY', 'C2REMK', 'C2RGID', 'C2RGDT', 'C2CHID', 'C2CHDT', 'C2CHNO', 'C2DLFG', 'C2DPFG', 'C2PTFG', 'C2PTCT', 'C2PTID', 'C2PTDT', 'C2SRCE', 'C2USRM', 'C2ITRM', 'C2CSDT', 'C2CSID', 'C2CSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mmcatg()
    {
        return $this->belongsTo('App\Models\Mmcatg', 'C2C1CDIY', 'C1C1CDIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bblines()
    {
        return $this->hasMany('App\Models\Bbline', 'BBC2CDIY', 'C2C2CDIY');
    }
}

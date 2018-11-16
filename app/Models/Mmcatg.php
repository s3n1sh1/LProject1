<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $C1C1CDIY
 * @property string $C1C1CD
 * @property string $C1NAME
 * @property string $C1REMK
 * @property string $C1RGID
 * @property string $C1RGDT
 * @property string $C1CHID
 * @property string $C1CHDT
 * @property int $C1CHNO
 * @property boolean $C1DLFG
 * @property boolean $C1DPFG
 * @property boolean $C1PTFG
 * @property int $C1PTCT
 * @property string $C1PTID
 * @property string $C1PTDT
 * @property string $C1SRCE
 * @property string $C1USRM
 * @property string $C1ITRM
 * @property string $C1CSDT
 * @property string $C1CSID
 * @property string $C1CSNO
 * @property Mmcat[] $mmcats
 */
class Mmcatg extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmcatg';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'C1C1CDIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['C1C1CD', 'C1NAME', 'C1REMK', 'C1RGID', 'C1RGDT', 'C1CHID', 'C1CHDT', 'C1CHNO', 'C1DLFG', 'C1DPFG', 'C1PTFG', 'C1PTCT', 'C1PTID', 'C1PTDT', 'C1SRCE', 'C1USRM', 'C1ITRM', 'C1CSDT', 'C1CSID', 'C1CSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mmcats()
    {
        return $this->hasMany('App\Models\Mmcat', 'C2C1CDIY', 'C1C1CDIY');
    }
}

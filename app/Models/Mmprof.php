<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $MFPCNOIY
 * @property string $MFPCNO
 * @property string $MFNAME
 * @property string $MFDIVI
 * @property string $MFREMK
 * @property string $MFRGID
 * @property string $MFRGDT
 * @property string $MFCHID
 * @property string $MFCHDT
 * @property int $MFCHNO
 * @property boolean $MFDLFG
 * @property boolean $MFDPFG
 * @property boolean $MFPTFG
 * @property int $MFPTCT
 * @property string $MFPTID
 * @property string $MFPTDT
 * @property string $MFSRCE
 * @property string $MFUSRM
 * @property string $MFITRM
 * @property string $MFCSDT
 * @property string $MFCSID
 * @property string $MFCSNO
 * @property Mmctcr[] $mmctcrs
 */
class Mmprof extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmprof';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MFPCNOIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['MFPCNO', 'MFNAME', 'MFDIVI', 'MFREMK', 'MFRGID', 'MFRGDT', 'MFCHID', 'MFCHDT', 'MFCHNO', 'MFDLFG', 'MFDPFG', 'MFPTFG', 'MFPTCT', 'MFPTID', 'MFPTDT', 'MFSRCE', 'MFUSRM', 'MFITRM', 'MFCSDT', 'MFCSID', 'MFCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mmctcrs()
    {
        return $this->hasMany('App\Models\Mmctcr', 'MCPCNOIY', 'MFPCNOIY');
    }
}

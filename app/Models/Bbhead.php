<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $BABKNOIY
 * @property string $BABKNO
 * @property string $BATRNO
 * @property string $BATYPE
 * @property string $BABKDT
 * @property string $BADIVI
 * @property string $BALOCA
 * @property string $BADEPT
 * @property int $BACCNOIY
 * @property string $BACURR
 * @property float $BATOTL
 * @property string $BAREMK
 * @property string $BARGID
 * @property string $BARGDT
 * @property string $BACHID
 * @property string $BACHDT
 * @property int $BACHNO
 * @property boolean $BADLFG
 * @property boolean $BADPFG
 * @property boolean $BAPTFG
 * @property int $BAPTCT
 * @property string $BAPTID
 * @property string $BAPTDT
 * @property string $BASRCE
 * @property string $BAUSRM
 * @property string $BAITRM
 * @property string $BACSDT
 * @property string $BACSID
 * @property string $BACSNO
 * @property Mmctcr $mmctcr
 * @property Bbline $bbline
 */
class Bbhead extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'bbhead';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'BABKNOIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['BABKNO', 'BATRNO', 'BATYPE', 'BABKDT', 'BADIVI', 'BALOCA', 'BADEPT', 'BACCNOIY', 'BACURR', 'BATOTL', 'BAREMK', 'BARGID', 'BARGDT', 'BACHID', 'BACHDT', 'BACHNO', 'BADLFG', 'BADPFG', 'BAPTFG', 'BAPTCT', 'BAPTID', 'BAPTDT', 'BASRCE', 'BAUSRM', 'BAITRM', 'BACSDT', 'BACSID', 'BACSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mmctcr()
    {
        return $this->belongsTo('App\Models\Mmctcr', 'BACCNOIY', 'MCCCNOIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bbline()
    {
        return $this->hasOne('App\Models\Bbline', 'BBBKNOIY', 'BABKNOIY');
    }
}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $BBBLNOIY
 * @property int $BBBLNO
 * @property int $BBBKNOIY
 * @property int $BBC2CDIY
 * @property string $BBDESC
 * @property float $BBTOTL
 * @property string $BBREMK
 * @property string $BBRGID
 * @property string $BBRGDT
 * @property string $BBCHID
 * @property string $BBCHDT
 * @property int $BBCHNO
 * @property boolean $BBDLFG
 * @property boolean $BBDPFG
 * @property boolean $BBPTFG
 * @property int $BBPTCT
 * @property string $BBPTID
 * @property string $BBPTDT
 * @property string $BBSRCE
 * @property string $BBUSRM
 * @property string $BBITRM
 * @property string $BBCSDT
 * @property string $BBCSID
 * @property string $BBCSNO
 * @property Bbhead $bbhead
 * @property Mmcat $mmcat
 */
class Bbline extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'bbline';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'BBBLNOIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['BBBLNO', 'BBBKNOIY', 'BBC2CDIY', 'BBDESC', 'BBTOTL', 'BBREMK', 'BBRGID', 'BBRGDT', 'BBCHID', 'BBCHDT', 'BBCHNO', 'BBDLFG', 'BBDPFG', 'BBPTFG', 'BBPTCT', 'BBPTID', 'BBPTDT', 'BBSRCE', 'BBUSRM', 'BBITRM', 'BBCSDT', 'BBCSID', 'BBCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bbhead()
    {
        return $this->belongsTo('App\Models\Bbhead', 'BBBKNOIY', 'BABKNOIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mmcat()
    {
        return $this->belongsTo('App\Models\Mmcat', 'BBC2CDIY', 'C2C2CDIY');
    }
}

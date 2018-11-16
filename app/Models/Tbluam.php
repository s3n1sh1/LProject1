<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TANOMRIY
 * @property int $TAUSERIY
 * @property int $TAMENUIY
 * @property string $TAACES
 * @property string $TALSDT
 * @property int $TAUSCT
 * @property string $TAREMK
 * @property string $TARGID
 * @property string $TARGDT
 * @property string $TACHID
 * @property string $TACHDT
 * @property int $TACHNO
 * @property boolean $TADLFG
 * @property boolean $TADPFG
 * @property boolean $TAPTFG
 * @property int $TAPTCT
 * @property string $TAPTID
 * @property string $TAPTDT
 * @property string $TASRCE
 * @property string $TAUSRM
 * @property string $TAITRM
 * @property string $TACSDT
 * @property string $TACSID
 * @property TBLUSR $tBLUSR
 * @property TBLMNU $tBLMNU
 */
class Tbluam extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tbluam';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TANOMRIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TAUSERIY', 'TAMENUIY', 'TAACES', 'TALSDT', 'TAUSCT', 'TAREMK', 'TARGID', 'TARGDT', 'TACHID', 'TACHDT', 'TACHNO', 'TADLFG', 'TADPFG', 'TAPTFG', 'TAPTCT', 'TAPTID', 'TAPTDT', 'TASRCE', 'TAUSRM', 'TAITRM', 'TACSDT', 'TACSID'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tBLUSR()
    {
        return $this->belongsTo('App\Models\TBLUSR', 'TAUSERIY', 'TUUSERIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tBLMNU()
    {
        return $this->belongsTo('App\Models\TBLMNU', 'TAMENUIY', 'TMMENUIY');
    }
}

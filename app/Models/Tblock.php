<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TINOMRIY
 * @property int $TIMENUIY
 * @property int $TITRID
 * @property string $TITRNO
 * @property string $TIREMK
 * @property string $TIRGID
 * @property string $TIRGDT
 * @property string $TICHID
 * @property string $TICHDT
 * @property int $TICHNO
 * @property boolean $TIDLFG
 * @property boolean $TIDPFG
 * @property boolean $TIPTFG
 * @property int $TIPTCT
 * @property string $TIPTID
 * @property string $TIPTDT
 * @property string $TISRCE
 * @property string $TIUSRM
 * @property string $TIITRM
 * @property string $TICSDT
 * @property string $TICSID
 * @property string $TICSNO
 * @property TBLMNU $tBLMNU
 */
class Tblock extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblock';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TINOMRIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TIMENUIY', 'TITRID', 'TITRNO', 'TIREMK', 'TIRGID', 'TIRGDT', 'TICHID', 'TICHDT', 'TICHNO', 'TIDLFG', 'TIDPFG', 'TIPTFG', 'TIPTCT', 'TIPTID', 'TIPTDT', 'TISRCE', 'TIUSRM', 'TIITRM', 'TICSDT', 'TICSID', 'TICSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tBLMNU()
    {
        return $this->belongsTo('App\Models\TBLMNU', 'TIMENUIY', 'TMMENUIY');
    }
}

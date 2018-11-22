<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $MAAREAIY
 * @property string $MAAREA
 * @property string $MANAME
 * @property string $MAREMK
 * @property string $MARGID
 * @property string $MARGDT
 * @property string $MACHID
 * @property string $MACHDT
 * @property int $MACHNO
 * @property boolean $MADLFG
 * @property boolean $MADPFG
 * @property boolean $MAPTFG
 * @property int $MAPTCT
 * @property string $MAPTID
 * @property string $MAPTDT
 * @property string $MASRCE
 * @property string $MAUSRM
 * @property string $MAITRM
 * @property string $MACSDT
 * @property string $MACSID
 * @property string $MACSNO
 */
class Mmarea extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mmarea';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MAAREAIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['MAAREA', 'MANAME', 'MAREMK', 'MARGID', 'MARGDT', 'MACHID', 'MACHDT', 'MACHNO', 'MADLFG', 'MADPFG', 'MAPTFG', 'MAPTCT', 'MAPTID', 'MAPTDT', 'MASRCE', 'MAUSRM', 'MAITRM', 'MACSDT', 'MACSID', 'MACSNO'];

}

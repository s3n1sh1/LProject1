<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property string $TDDSCD
 * @property string $TDDSNM
 * @property float $TDLGTH
 * @property string $TDREMK
 * @property string $TDRGID
 * @property string $TDRGDT
 * @property string $TDCHID
 * @property string $TDCHDT
 * @property int $TDCHNO
 * @property boolean $TDDLFG
 * @property boolean $TDDPFG
 * @property boolean $TDPTFG
 * @property int $TDPTCT
 * @property string $TDPTID
 * @property string $TDPTDT
 * @property string $TDSRCE
 * @property string $TDUSRM
 * @property string $TDITRM
 * @property string $TDCSDT
 * @property string $TDCSID
 */
class Tbldsc extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tbldsc';

    /**
     * @var array
     */
    protected $fillable = ['TDDSCD', 'TDDSNM', 'TDLGTH', 'TDREMK', 'TDRGID', 'TDRGDT', 'TDCHID', 'TDCHDT', 'TDCHNO', 'TDDLFG', 'TDDPFG', 'TDPTFG', 'TDPTCT', 'TDPTID', 'TDPTDT', 'TDSRCE', 'TDUSRM', 'TDITRM', 'TDCSDT', 'TDCSID'];

}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TFFINDIY
 * @property int $TFAREAIY
 * @property int $TFBAGNIY
 * @property int $TFSTAFIY
 * @property string $TFFINO
 * @property string $TFDATE
 * @property string $TFSUBJ
 * @property string $TFDESC
 * @property string $TFSOLU
 * @property string $TFACDT
 * @property string $TFACBY
 * @property string $TFACRM
 * @property string $TFACPL
 * @property string $TFRELO
 * @property string $TFRDRM
 * @property string $TFSLDT
 * @property string $TFSLBY
 * @property string $TFSLRM
 * @property string $TFACTN
 * @property string $TFRSLT
 * @property string $TFCLDT
 * @property string $TFCLBY
 * @property string $TFREMK
 * @property string $TFRGID
 * @property string $TFRGDT
 * @property string $TFCHID
 * @property string $TFCHDT
 * @property int $TFCHNO
 * @property boolean $TFDLFG
 * @property boolean $TFDPFG
 * @property boolean $TFPTFG
 * @property int $TFPTCT
 * @property string $TFPTID
 * @property string $TFPTDT
 * @property string $TFSRCE
 * @property string $TFUSRM
 * @property string $TFITRM
 * @property string $TFCSDT
 * @property string $TFCSID
 * @property string $TFCSNO
 * @property MMSTAF $mMSTAF
 * @property MMAREA $mMAREA
 * @property MMBAGN $mMBAGN
 */
class Trfind extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'trfind';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TFFINDIY';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['TFAREAIY', 'TFBAGNIY', 'TFSTAFIY', 'TFFINO', 'TFDATE', 'TFSUBJ', 'TFDESC', 'TFSOLU', 'TFACDT', 'TFACBY', 'TFACRM', 'TFACPL', 'TFRELO', 'TFRDRM', 'TFSLDT', 'TFSLBY', 'TFSLRM', 'TFACTN', 'TFRSLT', 'TFCLDT', 'TFCLBY', 'TFREMK', 'TFRGID', 'TFRGDT', 'TFCHID', 'TFCHDT', 'TFCHNO', 'TFDLFG', 'TFDPFG', 'TFPTFG', 'TFPTCT', 'TFPTID', 'TFPTDT', 'TFSRCE', 'TFUSRM', 'TFITRM', 'TFCSDT', 'TFCSID', 'TFCSNO'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mMSTAF()
    {
        return $this->belongsTo('App\Models\MMSTAF', 'TFSTAFIY', 'MCSTAFIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mMAREA()
    {
        return $this->belongsTo('App\Models\MMAREA', 'TFAREAIY', 'MAAREAIY');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mMBAGN()
    {
        return $this->belongsTo('App\Models\MMBAGN', 'TFBAGNIY', 'MBBAGNIY');
    }
}

<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TLNOMRIY
 * @property string $TLUSER
 * @property string $TLEVTY
 * @property string $TLOBNM
 * @property string $TLOBTY
 * @property string $TLCRDT
 * @property string $TLDATA
 * @property string $TLREMK
 * @property string $TLRGID
 * @property string $TLRGDT
 * @property string $TLCHID
 * @property string $TLCHDT
 * @property int $TLCHNO
 * @property boolean $TLDLFG
 * @property boolean $TLDPFG
 * @property boolean $TLPTFG
 * @property int $TLPTCT
 * @property string $TLPTID
 * @property string $TLPTDT
 * @property string $TLSRCE
 * @property string $TLUSRM
 * @property string $TLITRM
 * @property string $TLCSDT
 * @property string $TLCSID
 * @property string $TLCSNO
 */
class Tblhss extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblhss';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TLNOMRIY';

    /**
     * @var array
     */
    protected $fillable = ['TLUSER', 'TLEVTY', 'TLOBNM', 'TLOBTY', 'TLCRDT', 'TLDATA', 'TLREMK', 'TLRGID', 'TLRGDT', 'TLCHID', 'TLCHDT', 'TLCHNO', 'TLDLFG', 'TLDPFG', 'TLPTFG', 'TLPTCT', 'TLPTID', 'TLPTDT', 'TLSRCE', 'TLUSRM', 'TLITRM', 'TLCSDT', 'TLCSID', 'TLCSNO'];

}

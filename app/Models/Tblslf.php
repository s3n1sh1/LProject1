<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $TQNOMRIY
 * @property string $TQUSER
 * @property string $TQSTMT
 * @property string $TQREMK
 * @property string $TQRGID
 * @property string $TQRGDT
 * @property string $TQCHID
 * @property string $TQCHDT
 * @property int $TQCHNO
 * @property boolean $TQDLFG
 * @property boolean $TQDPFG
 * @property boolean $TQPTFG
 * @property int $TQPTCT
 * @property string $TQPTID
 * @property string $TQPTDT
 * @property string $TQSRCE
 * @property string $TQUSRM
 * @property string $TQITRM
 * @property string $TQCSDT
 * @property string $TQCSID
 * @property string $TQCSNO
 */
class Tblslf extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tblslf';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TQNOMRIY';

    /**
     * @var array
     */
    protected $fillable = ['TQUSER', 'TQSTMT', 'TQREMK', 'TQRGID', 'TQRGDT', 'TQCHID', 'TQCHDT', 'TQCHNO', 'TQDLFG', 'TQDPFG', 'TQPTFG', 'TQPTCT', 'TQPTID', 'TQPTDT', 'TQSRCE', 'TQUSRM', 'TQITRM', 'TQCSDT', 'TQCSID', 'TQCSNO'];

}

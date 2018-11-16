
<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;

class CreateTBLMNUTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('TBLUAM');
        // Schema::dropIfExists('TBLMNU');
        Schema::create('TBLMNU', function ($table) {

            $table->integer('TMMENUIY')->primary()->nullable(false)->comment('Menu IY');
            $table->char('TMNOMR',20)->nullable(true)->comment('Nomor Urut');
            $table->char('TMGRUP',250)->nullable(true)->comment('Kelompok Menu');
            $table->char('TMMENU',200)->nullable(true)->comment('Menu');
            $table->longText('TMDESC')->nullable(true)->comment('Menu Deskirpsi');
            $table->char('TMSCUT',20)->nullable(true)->comment('Short Cut');
            $table->char('TMACES',20)->nullable(true)->comment('Menu Akses');
            $table->integer('TMBCDT')->nullable(true)->comment('BackDate');
            $table->integer('TMFWDT')->nullable(true)->comment('ForwardDate');
            $table->char('TMURLW',200)->nullable(true)->comment('URL');
            $table->char('TMSYFG',10)->nullable(true)->comment('System Flag');
            $table->integer('TMUSCT')->nullable(true)->comment('User Hit Count');
            $table->datetime('TMLSDT')->nullable(true)->comment('User Hit Last Date');
            $table->char('TMLSBY',50)->nullable(true)->comment('User Hit Last By');
            $table->char('TMRLDT',8)->nullable(true)->comment('Release Date');
            $table->longText('TMGRID')->nullable(true)->comment('Grid Syntax');

            $this->AutoCreateKolom('TM', $table);

            $table->unique('TMNOMR');

        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        // Schema::dropIfExists('TBLUAM'); 
        Schema::dropIfExists('TBLMNU'); 
    } 
} 
?> 


<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;
// use Database\Migrations\BaseMigrations;

class CreateTBLDSCTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('TBLSYS');
        Schema::dropIfExists('TBLDSC');
        Schema::create('TBLDSC', function ($table) {

            $table->char('TDDSCD',20)->primary()->nullable(false)->comment('Kode Deskirpsi');
            $table->char('TDDSNM',100)->nullable(true)->comment('Nama Deskirpsi');
            $table->decimal('TDLGTH')->nullable(true)->comment('Panjang Karakter');
            
            $this->AutoCreateKolom('TD', $table);
           
        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        // Schema::dropIfExists('TBLSYS');
        Schema::dropIfExists('TBLDSC'); 
    } 
} 
?> 


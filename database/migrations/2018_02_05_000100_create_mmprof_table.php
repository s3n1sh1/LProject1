<?php

use App\Console\BaseMigrations;

class CreateMMPROFTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMPROF');
        Schema::create('MMPROF', function ($table) {

            $table->integer('MFPCNOIY')->primary()->nullable(false)->comment('Profit Center IY');
            $table->char('MFPCNO',20)->nullable(false)->comment('Profit Center Code');
            $table->char('MFNAME',100)->nullable(true)->comment('Profit Center Name');
            $table->char('MFDIVI',10)->nullable(true)->comment('Division');
            $this->AutoCreateKolom('MF', $table);

            $table->unique('MFPCNO');
           
        });

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMPROF'); 
    } 
} 
?> 


<?php

use App\Console\BaseMigrations;

class CreateMMAREATable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMAREA');
        Schema::create('MMAREA', function ($table) {

            $table->integer('MAAREAIY')->primary()->nullable(false)->comment('Area IY');
            $table->char('MAAREA',20)->nullable(false)->comment('Area Code');
            $table->char('MANAME',100)->nullable(true)->comment('Area Name');
            $this->AutoCreateKolom('MA', $table);

            $table->unique('MAAREA');
           
        }); 


    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMAREA'); 
    } 
} 
?> 


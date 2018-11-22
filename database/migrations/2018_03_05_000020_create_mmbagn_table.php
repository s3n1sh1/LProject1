<?php

use App\Console\BaseMigrations;

class CreateMMBAGNTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMBAGN');
        Schema::create('MMBAGN', function ($table) {

            $table->integer('MBBAGNIY')->primary()->nullable(false)->comment('Bagian IY');
            $table->char('MBBAGN',20)->nullable(false)->comment('Bagian Code');
            $table->char('MBNAME',100)->nullable(true)->comment('Bagian Name');
            $this->AutoCreateKolom('MB', $table);

            $table->unique('MBBAGN');
           
        });

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMBAGN'); 
    } 
} 
?> 


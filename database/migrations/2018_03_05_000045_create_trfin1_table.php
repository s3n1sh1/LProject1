<?php

use App\Console\BaseMigrations;

class CreateTRFIN1Table extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('TRFIN1');
        Schema::create('TRFIN1', function ($table) {

            $table->integer('T1NOMRIY')->primary()->nullable(false)->comment('Nomor IY');
            $table->integer('T1FINDIY')->nullable(false)->comment('Finding IY');
            $table->binary('T1FILE')->nullable(true)->comment('File Document');
            $this->AutoCreateKolom('T1', $table);

            // $table->unique('T1FINO');
            $table->foreign('T1FINDIY')->references('TFFINDIY')->on('TRFIND');

        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('TRFIN1'); 
    } 
} 
?> 


<?php

use App\Console\BaseMigrations;

class CreateTRFIN2Table extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('TRFIN2');
        Schema::create('TRFIN2', function ($table) {

            $table->integer('T2NOMRIY')->primary()->nullable(false)->comment('Nomor IY');
            $table->integer('T2FINDIY')->nullable(false)->comment('Finding IY');
            $table->binary('T2FILE')->nullable(true)->comment('File Document');
            $this->AutoCreateKolom('T2', $table);

            // $table->unique('T2FINO');
            $table->foreign('T2FINDIY')->references('TFFINDIY')->on('TRFIND');

        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('TRFILE'); 
    } 
} 
?> 


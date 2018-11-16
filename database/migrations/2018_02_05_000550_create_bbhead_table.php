<?php

use App\Console\BaseMigrations;

class CreateBBHEADTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('BBHEAD');
        Schema::create('BBHEAD', function ($table) {

            $table->integer('BABKNOIY')->primary()->nullable(false)->comment('BKK IY');
            $table->char('BABKNO',30)->nullable(false)->comment('BKK No');
            $table->char('BATRNO',30)->nullable(true)->comment('BKK Transaction No');
            $table->char('BATYPE',10)->nullable(false)->comment('BKK Type');
            $table->char('BABKDT',8)->nullable(false)->comment('BKK Date');
            $table->char('BADIVI',10)->nullable(false)->comment('Division');
            $table->char('BALOCA',10)->nullable(false)->comment('Location');
            $table->char('BADEPT',10)->nullable(false)->comment('Department');
            $table->integer('BACCNOIY')->nullable(false)->comment('Cost Center IY');
            $table->char('BACURR',10)->nullable(false)->comment('Currency');
            $table->decimal('BATOTL')->nullable(false)->comment('Total BKK');
            $this->AutoCreateKolom('BA', $table);

            $table->unique('BABKNO');
            $table->foreign('BACCNOIY')->references('MCCCNOIY')->on('MMCTCR');
           
        });

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('BBHEAD'); 
    } 
} 
?> 


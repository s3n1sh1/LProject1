<?php

use App\Console\BaseMigrations;

class CreateMMCTCRTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMCTCR');
        Schema::create('MMCTCR', function ($table) {

            $table->integer('MCCCNOIY')->primary()->nullable(false)->comment('Cost Center IY');
            $table->char('MCCCNO',20)->nullable(false)->comment('Cost Center Code');
            $table->char('MCNAME',100)->nullable(true)->comment('Cost Center Name');
            $table->integer('MCPCNOIY')->nullable(false)->comment('Profit Center IY');
            $table->char('MCDEPT',10)->nullable(true)->comment('Department');
            $this->AutoCreateKolom('MC', $table);

            $table->unique('MCCCNO');
            $table->foreign('MCPCNOIY')->references('MFPCNOIY')->on('MMPROF');
           
        });

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMCTCR'); 
    } 
} 
?> 


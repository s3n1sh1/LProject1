<?php

use App\Console\BaseMigrations;

class CreateMMCATSTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMCATS');
        Schema::create('MMCATS', function ($table) {

            $table->integer('C2C2CDIY')->primary()->nullable(false)->comment('Sub Category IY');
            $table->char('C2C2CD',20)->nullable(false)->comment('Sub Category Code');
            $table->char('C2NAME',100)->nullable(true)->comment('Sub Category Name');
            $table->integer('C2C1CDIY')->nullable(false)->comment('Category IY');
            $this->AutoCreateKolom('C2', $table);

            $table->unique('C2C2CD');
            $table->foreign('C2C1CDIY')->references('C1C1CDIY')->on('MMCATG');
           
        });
        
    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMCATS'); 
    } 
} 
?> 


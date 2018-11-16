<?php

use App\Console\BaseMigrations;

class CreateMMCATGTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMCATG');
        Schema::create('MMCATG', function ($table) {

            $table->integer('C1C1CDIY')->primary()->nullable(false)->comment('Category IY');
            $table->char('C1C1CD',20)->nullable(false)->comment('Category Code');
            $table->char('C1NAME',100)->nullable(true)->comment('Category Name');
            $this->AutoCreateKolom('C1', $table);

            $table->unique('C1C1CD');
           
        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMCATG'); 
    } 
} 
?> 


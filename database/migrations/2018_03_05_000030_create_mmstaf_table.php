<?php

use App\Console\BaseMigrations;

class CreateMMSTAFTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('MMSTAF');
        Schema::create('MMSTAF', function ($table) {

            $table->integer('MCSTAFIY')->primary()->nullable(false)->comment('Staff IY');
            $table->char('MCSTAF',20)->nullable(false)->comment('Staff NIP');
            $table->char('MCNAME',100)->nullable(true)->comment('Staff Name');
            $table->char('MCTITL',100)->nullable(true)->comment('Jabatan');
            $this->AutoCreateKolom('MC', $table);

            $table->unique('MCSTAF');
           
        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('MMSTAF'); 
    } 
} 
?> 


<?php

use App\Console\BaseMigrations;

class CreateBBLINETable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('BBLINE');
        Schema::create('BBLINE', function ($table) {

            $table->integer('BBBLNOIY')->primary()->nullable(false)->comment('BKK Line IY');
            $table->integer('BBBLNO')->nullable(false)->comment('BKK Line No');
            $table->integer('BBBKNOIY')->nullable(false)->comment('BKK IY');
            $table->integer('BBC2CDIY')->nullable(false)->comment('Sub Category IY');
            $table->longText('BBDESC')->nullable(true)->comment('BKK Line Description');        
            $table->decimal('BBTOTL')->nullable(false)->comment('Line Amount');
            $this->AutoCreateKolom('BB', $table);

            $table->unique(['BBBKNOIY','BBBLNO']);
            $table->foreign('BBBKNOIY')->references('BABKNOIY')->on('BBHEAD');
            $table->foreign('BBC2CDIY')->references('C2C2CDIY')->on('MMCATS');
           
        });

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('BBLINE'); 
    } 
} 
?> 


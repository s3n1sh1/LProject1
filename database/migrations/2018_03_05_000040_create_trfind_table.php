<?php

use App\Console\BaseMigrations;

class CreateTRFINDTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('TRFIND');
        Schema::create('TRFIND', function ($table) {

            $table->integer('TFFINDIY')->primary()->nullable(false)->comment('Finding IY');
            $table->integer('TFAREAIY')->nullable(false)->comment('MMAREA IY');
            $table->integer('TFBAGNIY')->nullable(false)->comment('MMBAGN IY');
            $table->integer('TFSTAFIY')->nullable(true)->comment('MMSTAF IY');
            $table->char('TFFINO',20)->nullable(false)->comment('Finding No');
            $table->char('TFDATE',8)->nullable(true)->comment('Date');
            $table->longText('TFSUBJ')->nullable(true)->comment('Subject');
            $table->longText('TFDESC')->nullable(true)->comment('Description');
            $table->longText('TFSOLU')->nullable(true)->comment('Solution');
            $table->char('TFACDT',8)->nullable(true)->comment('Accepted Date');
            $table->char('TFACBY',50)->nullable(true)->comment('Accepted By');
            $table->longText('TFACRM')->nullable(true)->comment('Accepted Remark');
            $table->longText('TFACPL')->nullable(true)->comment('Accepted Plan');
            $table->longText('TFRELO')->nullable(true)->comment('Relation Officer');
            $table->longText('TFRDRM')->nullable(true)->comment('Redirect Remark');
            $table->char('TFSLDT',8)->nullable(true)->comment('Solution Date');
            $table->char('TFSLBY',50)->nullable(true)->comment('Solution By');
            $table->longText('TFSLRM')->nullable(true)->comment('Solution Remark');
            $table->longText('TFACTN')->nullable(true)->comment('Action Finding');
            $table->longText('TFRSLT')->nullable(true)->comment('Result Finding');
            $table->char('TFCLDT',8)->nullable(true)->comment('Close Date');
            $table->char('TFCLBY',50)->nullable(true)->comment('Close By');
            $this->AutoCreateKolom('TF', $table);

            $table->unique('TFFINO');
            $table->foreign('TFAREAIY')->references('MAAREAIY')->on('MMAREA');
            $table->foreign('TFBAGNIY')->references('MBBAGNIY')->on('MMBAGN');
            $table->foreign('TFSTAFIY')->references('MCSTAFIY')->on('MMSTAF');
           
        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('TRFIND'); 
    } 
} 
?> 


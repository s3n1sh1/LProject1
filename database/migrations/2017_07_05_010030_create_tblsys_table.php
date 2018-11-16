<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;
// use Database\Migrations\BaseMigrations;

class CreateTBLSYSTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('TBLSYS');
        Schema::create('TBLSYS', function ($table) {

            $table->char('TSDSCD',20)->nullable(false)->comment('Kode Description');
            $table->char('TSSYCD',20)->nullable(false)->comment('Kode');
            $table->char('TSSYNM',200)->nullable(true)->comment('Deskirpsi');
            $table->decimal('TSSYV1')->nullable(true)->comment('Value 1');
            $table->decimal('TSSYV2')->nullable(true)->comment('Value 2');
            $table->decimal('TSSYV3')->nullable(true)->comment('Value 3');
            $table->longText('TSSYT1')->nullable(true)->comment('Text 1');
            $table->longText('TSSYT2')->nullable(true)->comment('Text 2');
            $table->longText('TSSYT3')->nullable(true)->comment('Text 3');
            $table->char('TSLSV1',200)->nullable(true)->comment('Label Value 1');
            $table->char('TSLSV2',200)->nullable(true)->comment('Label Value 2');
            $table->char('TSLSV3',200)->nullable(true)->comment('Label Value 3');
            $table->char('TSLST1',200)->nullable(true)->comment('Label Text 1');
            $table->char('TSLST2',200)->nullable(true)->comment('Label Text 2');
            $table->char('TSLST3',200)->nullable(true)->comment('Label Text 3');
            
            $this->AutoCreateKolom('TS', $table);

            $table->primary(['TSDSCD','TSSYCD']);  
            $table->foreign('TSDSCD')->references('TDDSCD')->on('TBLDSC');           
        }); 

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        Schema::dropIfExists('TBLSYS'); 
    } 
} 
?> 


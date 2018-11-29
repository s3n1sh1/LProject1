<?php// use Illuminate\Support\Facades\Schema;// use Illuminate\Database\Schema\Blueprint;// use Illuminate\Database\Migrations\Migration;use App\Console\BaseMigrations;class CreateTBLSLFTable extends BaseMigrations{    /**     * Run the migrations.     *     * @return void     */    public function up()    {        Schema::dropIfExists('TBLSLF');        Schema::create('TBLSLF', function ($table) {            $table->increments('TQNOMRIY')->nullable(false)->comment('NoUrut IY');            $table->char('TQUSER',50)->nullable(false)->comment('User');            $table->longText('TQSTMT')->nullable(false)->comment('Sql Statement');            $this->AutoCreateKolom('TQ', $table);        });             }     /**      * Reverse the migrations.      *      * @return void      */     public function down()     {         Schema::dropIfExists('TBLSLF');     } } ?> 
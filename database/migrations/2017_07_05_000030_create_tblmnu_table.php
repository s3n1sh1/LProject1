
<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;

class CreateTBLMNUTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('TBLUAM');
        // Schema::dropIfExists('TBLMNU');
        Schema::create('TBLMNU', function ($table) {

            $table->integer('TMMENUIY')->primary()->nullable(false)->comment('Menu IY');
            $table->char('TMNOMR',20)->nullable(true)->comment('Nomor Urut');
            $table->char('TMGRUP',250)->nullable(true)->comment('Kelompok Menu');
            $table->char('TMMENU',200)->nullable(true)->comment('Menu');
            $table->longText('TMDESC')->nullable(true)->comment('Menu Deskirpsi');
            $table->char('TMSCUT',20)->nullable(true)->comment('Short Cut');
            $table->char('TMACES',20)->nullable(true)->comment('Menu Akses');
            $table->integer('TMBCDT')->nullable(true)->comment('BackDate');
            $table->integer('TMFWDT')->nullable(true)->comment('ForwardDate');
            $table->char('TMURLW',200)->nullable(true)->comment('URL');
            $table->char('TMSYFG',10)->nullable(true)->comment('System Flag');
            $table->integer('TMUSCT')->nullable(true)->comment('User Hit Count');
            $table->datetime('TMLSDT')->nullable(true)->comment('User Hit Last Date');
            $table->char('TMLSBY',50)->nullable(true)->comment('User Hit Last By');
            $table->char('TMRLDT',8)->nullable(true)->comment('Release Date');
            $table->longText('TMGRID')->nullable(true)->comment('Grid Syntax');

            $this->AutoCreateKolom('TM', $table);

            $table->unique('TMNOMR');

        }); 

        // DB::unprepared("
        //     if exists (select 1
        //               from sysobjects
        //               where  id = object_id('StpTBLMNU')
        //               and type in ('P','PC'))
        //        drop procedure StpTBLMNU");

        // DB::unprepared("

        //     CREATE procedure [dbo].[StpTBLMNU] (
        //         @TIPE     As VarChar(20),
        //         @USERNAME As VarChar(50),
        //         @SOURCE   As VarChar(50),
        //         @UNIKNO   As VarChar(50)
        //         , @TMCSDT   AS VarChar(50) 
        //         , @TMMENUIY  AS VarChar(10) 
        //         , @TMNOMR AS Char(20)
        //         , @TMGRUP AS Char(200)
        //         , @TMMENU AS Char(200)
        //         , @TMDESC  AS VarChar(MAX) 
        //         , @TMSCUT AS Char(20)
        //         , @TMACES AS Char(20)
        //         , @TMBCDT  AS VarChar(10) 
        //         , @TMFWDT  AS VarChar(10) 
        //         , @TMURLW AS Char(200)
        //         , @TMSYFG AS Char(10)
        //         , @TMGRID  AS VarChar(MAX) 
        //         , @TMUSRM AS VARCHAR(MAX)
        //         , @TMREMK AS VARCHAR(MAX)
        //         , @TMDPFG AS VARCHAR(1)
        //     ) 
        //     WITH EXECUTE as
        //     CALLER 
        //     AS
        //     Declare @SQLSTM  As NVarChar(Max)
        //     Declare @TNIPAD  As Char(50)
        //     Declare @TNPCNM  As Char(100)

        //     Set NOCOUNT ON;
        //     Exec StpCheckBFCS @TIPE,@USERNAME,'TBLMNU','TMMENUIY',@TMMENUIY,'',@TMCSDT
        //     IF @TIPE = '1' BEGIN --Add  
        //         EXECUTE @TMMENUIY = StpTBLNOR @USERNAME, 'TBLMNU';   
        //         SET @SQLSTM = ''
        //         SET @SQLSTM = @SQLSTM + ' INSERT INTO TBLMNU ('
        //         SET @SQLSTM = @SQLSTM + ' TMMENUIY,TMNOMR,TMGRUP,TMMENU,TMDESC,TMSCUT,TMACES,TMBCDT,TMFWDT,TMURLW,TMSYFG,TMUSCT,TMRLDT,TMGRID,'
        //         SET @SQLSTM = @SQLSTM + ' TMUSRM,TMREMK,TMRGID,TMRGDT, ' 
        //         SET @SQLSTM = @SQLSTM + ' TMCHID,TMCHDT,TMCHNO, ' 
        //         SET @SQLSTM = @SQLSTM + ' TMDLFG,TMDPFG,TMCSDT,TMCSID,TMSRCE,TMCSNO'
        //         SET @SQLSTM = @SQLSTM + ' ) VALUES ('
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMMENUIY +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMNOMR +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMGRUP +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMMENU +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMDESC +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMSCUT +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMACES +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMBCDT +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMFWDT +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMURLW +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMSYFG +''','
        //         SET @SQLSTM = @SQLSTM + ' ''0'','
        //         SET @SQLSTM = @SQLSTM + ' '''+ Convert(Char(8), GetDate(), 112) +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMGRID +''','
        //         SET @SQLSTM = @SQLSTM + ' '''+ @TMUSRM + ''', '''+ @TMREMK + ''', ''' + @USERNAME + ''' , ''' + Convert(nVarChar, GetDate(), 121)+ ''', ' 
        //         SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''', ''' + Convert(nVarChar, GetDate(), 121) + ''', ''0'', ' 
        //         SET @SQLSTM = @SQLSTM + ' ''0'', '''  + @TMDPFG + ''', ''' + Convert(nVarChar, GetDate(), 121)+ ''', ''' + @USERNAME + ''', ''' + @SOURCE + ''', ''' + @UNIKNO + '''  '  
        //         SET @SQLSTM = @SQLSTM + ')'     
        //         EXEC Sp_ExecuteSql @SQLSTM;
        //         EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;         
        //     END   
        //     IF @TIPE = '2' BEGIN --Edit
        //         SET @SQLSTM = ''
        //         SET @SQLSTM = @SQLSTM + ' Update TBLMNU Set'
        //         SET @SQLSTM = @SQLSTM + '  TMNOMR = ''' +@TMNOMR+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMGRUP = ''' +@TMGRUP+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMMENU = ''' +@TMMENU+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMDESC = ''' +@TMDESC+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMSCUT = ''' +@TMSCUT+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMACES = ''' +@TMACES+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMBCDT = ''' +@TMBCDT+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMFWDT = ''' +@TMFWDT+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMURLW = ''' +@TMURLW+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMSYFG = ''' +@TMSYFG+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMGRID = ''' +@TMGRID+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMUSRM = ''' +@TMUSRM+ ''', '
        //         SET @SQLSTM = @SQLSTM + '  TMREMK = ''' + @TMREMK + '''  '
        //         SET @SQLSTM = @SQLSTM + ', TMDPFG = ''' + @TMDPFG + '''  '
        //         SET @SQLSTM = @SQLSTM + ', TMCHID = ''' + @USERNAME + ''', TMCHDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //         SET @SQLSTM = @SQLSTM + ', TMCHNO = IsNull(TMCHNO,''0'')+1,  TMSRCE = ''' + @SOURCE + '''  '
        //         SET @SQLSTM = @SQLSTM + ', TMCSID = ''' + @USERNAME + ''', TMCSDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //         SET @SQLSTM = @SQLSTM + ' Where TMMENUIY = ''' + @TMMENUIY + '''  '
        //         EXEC Sp_ExecuteSql @SQLSTM;
        //         EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM; 
        //     END 

        //     IF @TIPE = '3' BEGIN --Delete
        //         SET @SQLSTM = ''
        //         SET @SQLSTM = @SQLSTM + ' Delete From TBLMNU Where TMMENUIY = ''' + @TMMENUIY + '''  '
        //         EXEC Sp_ExecuteSql @SQLSTM;
        //         EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;   
        //     END

        // ");

    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    { 
        // Schema::dropIfExists('TBLUAM'); 
        Schema::dropIfExists('TBLMNU'); 
    } 
} 
?> 


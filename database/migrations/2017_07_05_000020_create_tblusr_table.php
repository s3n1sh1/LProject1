
<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;

class CreateTBLUSRTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::dropIfExists('TBLUAM'); 
        Schema::dropIfExists('TBLUSR');

        Schema::create('TBLUSR', function ($table) {

            $table->integer('TUUSERIY')->primary()->nullable(false)->comment('User IY');
            $table->char('TUUSER',50)->nullable(true)->comment('User Login');
            $table->char('TUNAME',100)->nullable(true)->comment('User Name');
            $table->char('TUPSWD',100)->nullable(true)->comment('Password');
            $table->char('TUEMID',20)->nullable(true)->comment('Email');
            $table->char('TUDEPT',100)->nullable(true)->comment('Department');
            $table->char('TUMAIL',100)->nullable(true)->comment('Mail');
            $table->longText('TUWELC')->nullable(true)->comment('Welcome Text');
            $table->boolean('TUEXPP')->nullable(true)->comment('Expired');
            $table->char('TUEXPD',8)->nullable(true)->comment('Expired Date');
            $table->integer('TUEXPV')->nullable(true)->default(((3)))->comment('Expired Value');
            $table->integer('TULGCT')->nullable(true)->default(((0)))->comment('Login Counter');
            $table->datetime('TULSLI')->nullable(true)->comment('Last Login');
            $table->datetime('TULSLO')->nullable(true)->comment('Last Logoff');
            $table->binary('TUFOTO')->nullable(true)->comment('Avatar');

            $this->AutoCreateKolom('TU', $table);

            $table->unique('TUUSER');           
        }); 

        // DB::unprepared("
        //     if exists (select 1
        //               from sysobjects
        //               where  id = object_id('StpTBLUSR')
        //               and type in ('P','PC'))
        //        drop procedure StpTBLUSR");

        // DB::unprepared("

        //     create Procedure [dbo].[StpTBLUSR] (
        //         @TIPE     As VarChar(20),
        //         @USERNAME As VarChar(50),
        //         @SOURCE   As VarChar(10)
        //         , @UNIKNO As VarChar(50)
        //         , @TUCSDT   AS VarChar(50) 
        //         , @TUUSERIY  AS VarChar(10) 
        //         , @TUUSER AS Char(50)
        //         , @TUNAME AS Char(100)
        //         , @TUPSWD AS Char(100)
        //         , @TUEMID AS Char(20)
        //         , @TUDEPT AS Char(100)
        //         , @TUMAIL AS Char(100)
        //         , @TUWELC  AS VarChar(MAX) 
        //         , @TUEXPP  AS VarChar(MAX) 
        //         , @TUEXPD AS Char(8)
        //         , @TUEXPV  AS VarChar(10) 
        //         , @TUREMK AS VARCHAR(MAX)
        //         , @TUDPFG AS VARCHAR(1)
        //         , @TUUSRM AS VARCHAR(MAX)
        //         , @TUITRM AS VARCHAR(MAX)
        //         , @TUFOTO AS VARCHAR(MAX)
        //         ) 
        //         WITH EXECUTE as
        //     CALLER -- , ENCRYPTION
        //     AS
        //     Declare @SQLSTM  As NVarChar(Max)
        //     Set NOCOUNT ON;
        //         Exec StpCheckBFCS @TIPE,@USERNAME,'TBLUSR','TUUSERIY',@TUUSERIY,'',@TUCSDT
        //         IF @TIPE = '1' BEGIN --CREATE TABLE     
        //             EXECUTE @TUUSERIY = StpTBLNOR @USERNAME, 'TBLUSR'; 
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' INSERT INTO TBLUSR ('
        //             SET @SQLSTM = @SQLSTM + ' TUUSERIY,TUUSER,TUNAME,TUPSWD,TUEMID,TUDEPT,TUMAIL,TUWELC,TUEXPP,TUEXPD,TUEXPV,TUUSRM,TUITRM,'
        //             SET @SQLSTM = @SQLSTM + ' TUFOTO,'
        //             SET @SQLSTM = @SQLSTM + ' TUREMK,TURGID,TURGDT, ' 
        //             SET @SQLSTM = @SQLSTM + ' TUCHID,TUCHDT,TUCHNO, ' 
        //             SET @SQLSTM = @SQLSTM + ' TUDLFG,TUDPFG,TUCSDT,TUCSID,TUSRCE,TUCSNO'
        //             SET @SQLSTM = @SQLSTM + ' ) VALUES ('
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUUSERIY +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUUSER +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUNAME +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUPSWD +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUEMID +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUDEPT +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUMAIL +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUWELC +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUEXPP +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUEXPD +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUEXPV +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUUSRM +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUITRM +''','
        //             SET @SQLSTM = @SQLSTM + ' '+ @TUFOTO +','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TUREMK + ''', ''' + @USERNAME + ''' , ''' + Convert(nVarChar, GetDate(), 121)+ ''', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''', ''' + Convert(nVarChar, GetDate(), 121) + ''', ''0'', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''0'', '''  + @TUDPFG + ''', ''' + Convert(nVarChar, GetDate(), 121)+ ''', ''' + @USERNAME + ''', ''' + @SOURCE + ''', ''' + @UNIKNO + '''  ' 
        //             SET @SQLSTM = @SQLSTM + ')'
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;                   
        //         END   
        //         ELSE IF @TIPE = '2' BEGIN
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Update TBLUSR Set'
        //             --SET @SQLSTM = @SQLSTM + '  TUUSERIY = ''' +@TUUSERIY+ ''', '
        //             --SET @SQLSTM = @SQLSTM + '  TUUSER = ''' +@TUUSER+ ''', '
        //             --SET @SQLSTM = @SQLSTM + '  TUNAME = ''' +@TUNAME+ ''', '
        //             --SET @SQLSTM = @SQLSTM + '  TUPSWD = ''' +@TUPSWD+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUEMID = ''' +@TUEMID+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUDEPT = ''' +@TUDEPT+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUMAIL = ''' +@TUMAIL+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUWELC = ''' +@TUWELC+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUEXPP = ''' +@TUEXPP+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUEXPD = ''' +@TUEXPD+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUEXPV = ''' +@TUEXPV+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TUFOTO = '+ @TUFOTO +','
        //             SET @SQLSTM = @SQLSTM + '  TUREMK = ''' + @TUREMK + ''',  '
        //             SET @SQLSTM = @SQLSTM + '  TUUSRM = ''' + @TUUSRM + ''',  '
        //             SET @SQLSTM = @SQLSTM + '  TUITRM = ''' + @TUITRM + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TUDPFG = ''' + @TUDPFG + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TUPTCT = Case When ''' + @TUDPFG+ ''' = 1 Then 0 Else TUPTCT End ' -- Update Counter Gagal Login
        //             SET @SQLSTM = @SQLSTM + ', TUCHID = ''' + @USERNAME + ''', TUCHDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TUCHNO = IsNull(TUCHNO,''0'')+1,  TUSRCE = ''' + @SOURCE + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TUCSID = ''' + @USERNAME + ''', TUCSDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ' Where TUUSERIY = ''' + @TUUSERIY + '''  '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;   
        //         END 
        //         ELSE IF @TIPE = '3' BEGIN 
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Delete From TBLUAM Where TAUSERIY = ''' + @TUUSERIY + '''  '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;
                            
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Delete From TBLUSR Where TUUSERIY = ''' + @TUUSERIY + '''  '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;       
        //         END

        

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
        Schema::dropIfExists('TBLUSR'); 
    } 
} 
?> 


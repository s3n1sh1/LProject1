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



        // DB::unprepared("
        //     if exists (select 1
        //               from sysobjects
        //               where  id = object_id('StpTBLSYS')
        //               and type in ('P','PC'))
        //        drop procedure StpTBLSYS");


        // DB::unprepared("

        //     CREATE procedure [dbo].[StpTBLSYS] (
        //          @TIPE     As VarChar(20),
        //          @USERNAME As VarChar(50),
        //          @SOURCE   As VarChar(10)
        //        , @UNIKNO   AS VarChar(50)
        //        , @TSCSDT   AS VarChar(50) 
        //        , @TSDSCD   AS Char(20)
        //        , @TSSYCD   AS Char(20)
        //        , @TSSYNM   AS Char(200)
        //        , @TSSYV1   AS VarChar(34) 
        //        , @TSSYV2   AS VarChar(34) 
        //        , @TSSYV3   AS VarChar(34) 
        //        , @TSLSV1   AS VarChar(200) 
        //        , @TSLSV2   AS VarChar(200) 
        //        , @TSLSV3   AS VarChar(200)
        //        , @TSSYT1   AS VarChar(MAX) 
        //        , @TSSYT2   AS VarChar(MAX) 
        //        , @TSSYT3   AS VarChar(MAX)
        //        , @TSLST1   AS VarChar(200) 
        //        , @TSLST2   AS VarChar(200) 
        //        , @TSLST3   AS VarChar(200) 
        //        , @TSREMK   AS VARCHAR(MAX)
        //        , @TSDPFG   AS VARCHAR(1)
        //        ) 
        //        WITH EXECUTE as
        //     CALLER -- , ENCRYPTION
        //     AS
        //     Declare @SQLSTM  As NVarChar(Max)
        //     Declare @TDLGTH As Decimal(20,2)
        //     Set NOCOUNT ON;
        //         SET @SQLSTM = ' And TSDSCD = '''+ @TSDSCD +''' '    
        //         Exec StpCheckBFCS @TIPE,@USERNAME,'TBLSYS','TSSYCD',@TSSYCD,@SQLSTM,@TSCSDT
        //         IF @TIPE = '1' BEGIN --CREATE TABLE     
        //             Select @TDLGTH = TDLGTH From TBLDSC Where TDDSCD = @TSDSCD

        //             IF @TDLGTH < LEN(RTrim(@TSSYCD)) BEGIN                   
        //                 --RAISERROR('The Maximum length for System Code Field!',16,1)
        //                 EXEC [StpShowMessage] 'TBLSYS001','The Maximum length for System Code Field must be %s!',@TDLGTH
        //                 RETURN  
        //             END

        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' INSERT INTO TBLSYS ('
        //             SET @SQLSTM = @SQLSTM + ' TSDSCD,TSSYCD,TSSYNM,TSSYV1,TSSYV2,TSSYV3,TSSYT1,TSSYT2,TSSYT3,'
        //             SET @SQLSTM = @SQLSTM + ' TSLSV1,TSLSV2,TSLSV3,TSLST1,TSLST2,TSLST3,'
        //             SET @SQLSTM = @SQLSTM + ' TSREMK,TSRGID,TSRGDT, ' 
        //             SET @SQLSTM = @SQLSTM + ' TSCHID,TSCHDT,TSCHNO, ' 
        //             SET @SQLSTM = @SQLSTM + ' TSDLFG,TSDPFG,TSCSDT,TSCSID,TSSRCE,TSCSNO'
        //             SET @SQLSTM = @SQLSTM + ' ) VALUES ('
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSDSCD +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYCD +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYNM +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYV1 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYV2 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYV3 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYT1 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYT2 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSSYT3 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLSV1 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLSV2 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLSV3 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLST1 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLST2 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSLST3 +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TSREMK + ''', ''' + @USERNAME + ''' , ''' + Convert(nVarChar, GetDate(), 121)+ ''', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''', ''' + Convert(nVarChar, GetDate(), 121) + ''', ''0'', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''0'', '''  + @TSDPFG + ''', ''' + Convert(nVarChar, GetDate(), 121)+ ''', ''' + @USERNAME + ''', ''' + @SOURCE + ''', ''' + @UNIKNO + '''  '  
        //             SET @SQLSTM = @SQLSTM + ')'
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;                   
        //         END   
        //         ELSE IF @TIPE = '2' BEGIN
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Update TBLSYS Set'
        //             SET @SQLSTM = @SQLSTM + '  TSSYNM = ''' +@TSSYNM+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYV1 = ''' +@TSSYV1+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYV2 = ''' +@TSSYV2+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYV3 = ''' +@TSSYV3+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYT1 = ''' +@TSSYT1+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYT2 = ''' +@TSSYT2+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSSYT3 = ''' +@TSSYT3+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLSV1 = ''' +@TSLSV1+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLSV2 = ''' +@TSLSV2+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLSV3 = ''' +@TSLSV3+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLST1 = ''' +@TSLST1+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLST2 = ''' +@TSLST2+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSLST3 = ''' +@TSLST3+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TSREMK = ''' + @TSREMK + ''',  '
        //             SET @SQLSTM = @SQLSTM + '  TSDPFG = ''' + @TSDPFG + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TSCHID = ''' + @USERNAME + ''', TSCHDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TSCHNO = IsNull(TSCHNO,''0'')+1,  TSSRCE = ''' + @SOURCE + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TSCSID = ''' + @USERNAME + ''', TSCSDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ' Where TSSYCD = ''' + @TSSYCD + '''  '
        //             SET @SQLSTM = @SQLSTM + '   And TSDSCD = ''' + @TSDSCD + ''' '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;   
        //         END 
        //         ELSE IF @TIPE = '3' BEGIN 
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Delete From TBLSYS Where TSDSCD = ''' + @TSDSCD + ''' And TSSYCD = ''' + @TSSYCD + '''  '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;       
        //         END

        //     ");

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


<?php

// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
use App\Console\BaseMigrations;
// use Database\Migrations\BaseMigrations;

class CreateTBLDSCTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::dropIfExists('TBLSYS');
        Schema::dropIfExists('TBLDSC');
        Schema::create('TBLDSC', function ($table) {

            $table->char('TDDSCD',20)->primary()->nullable(false)->comment('Kode Deskirpsi');
            $table->char('TDDSNM',100)->nullable(true)->comment('Nama Deskirpsi');
            $table->decimal('TDLGTH')->nullable(true)->comment('Panjang Karakter');
            
            $this->AutoCreateKolom('TD', $table);
           
        }); 


        // DB::unprepared("
        //     if exists (select 1
        //               from sysobjects
        //               where  id = object_id('StpTBLDSC')
        //               and type in ('P','PC'))
        //        drop procedure StpTBLDSC");


        // DB::unprepared("

        //     Create procedure [dbo].[StpTBLDSC] (
        //          @TIPE     As VarChar(20),
        //          @USERNAME As VarChar(50),
        //          @SOURCE   As VarChar(10)
        //        , @UNIKNO   AS VarChar(50)
        //        , @TDCSDT   AS VarChar(50) 
        //        , @TDDSCD AS Char(20)
        //        , @TDDSNM AS Char(200)
        //        , @TDLGTH AS VARCHAR(10)
        //        , @TDREMK AS VARCHAR(MAX)
        //        , @TDDPFG AS VARCHAR(1)
        //        ) 
        //        WITH EXECUTE as
        //     CALLER -- , ENCRYPTION
        //     AS
        //     Declare @SQLSTM  As NVarChar(Max)
        //     Declare @TSDSCD  As VarChar(20)
        //     Declare @TSSYCD  As VarChar(200)
        //     Set NOCOUNT ON;

        //         Exec StpCheckBFCS @TIPE,@USERNAME,'TBLDSC','TDDSCD',@TDDSCD,'',@TDCSDT

        //         IF @TIPE = '1' BEGIN --CREATE TABLE 
                                   
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' INSERT INTO TBLDSC ('
        //             SET @SQLSTM = @SQLSTM + ' TDDSCD,TDDSNM,TDLGTH,'
        //             SET @SQLSTM = @SQLSTM + ' TDREMK,TDRGID,TDRGDT, ' 
        //             SET @SQLSTM = @SQLSTM + ' TDCHID,TDCHDT,TDCHNO, ' 
        //             SET @SQLSTM = @SQLSTM + ' TDDLFG,TDDPFG,TDCSDT,TDCSID,TDSRCE,TDCSNO'
        //             SET @SQLSTM = @SQLSTM + ' ) VALUES ('
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TDDSCD +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TDDSNM +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TDLGTH +''','
        //             SET @SQLSTM = @SQLSTM + ' '''+ @TDREMK + ''', ''' + @USERNAME + ''' , ''' + Convert(nVarChar, GetDate(), 121)+ ''', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''', ''' + Convert(nVarChar, GetDate(), 121) + ''', ''0'', ' 
        //             SET @SQLSTM = @SQLSTM + ' ''0'', '''  + @TDDPFG + ''', ''' + Convert(nVarChar, GetDate(), 121)+ ''', ''' + @USERNAME + ''', ''' + @SOURCE + ''', ''' + @UNIKNO + '''  '  
        //             SET @SQLSTM = @SQLSTM + ')'
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;                   
        //         END   
        //         ELSE IF @TIPE = '2' BEGIN
        //             Select @TSDSCD = TSDSCD, @TSSYCD=TSSYCD From TBLSYS Where TSDSCD = @TDDSCD

        //             IF @TSDSCD <>'' BEGIN                   
        //                 IF @TDLGTH < Len(RTrim(@TSSYCD)) BEGIN                   
        //                     --RAISERROR('The Maximum length for Character Length Field with Master Table System!',16,1)
        //                     EXEC [StpShowMessage] 'TBLDSC001', 'Conflict with Character Length Field Master Table System'
        //                     RETURN  
        //                 END
        //             END
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Update TBLDSC Set'
        //             SET @SQLSTM = @SQLSTM + '  TDDSNM = ''' +@TDDSNM+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TDLGTH = ''' +@TDLGTH+ ''', '
        //             SET @SQLSTM = @SQLSTM + '  TDREMK = ''' + @TDREMK + ''', TDDPFG = '''  + @TDDPFG + ''' '
        //             SET @SQLSTM = @SQLSTM + ', TDCHID = ''' + @USERNAME + ''', TDCHDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TDCHNO = IsNull(TDCHNO,''0'')+1,  TDSRCE = ''' + @SOURCE + '''  '
        //             SET @SQLSTM = @SQLSTM + ', TDCSID = ''' + @USERNAME + ''', TDCSDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
        //             SET @SQLSTM = @SQLSTM + ' Where TDDSCD = ''' + @TDDSCD + '''  '
        //             EXEC Sp_ExecuteSql @SQLSTM;
        //             EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;   
        //         END 
        //         ELSE IF @TIPE = '3' BEGIN 
        //             SET @SQLSTM = ''
        //             SET @SQLSTM = @SQLSTM + ' Delete From TBLDSC Where TDDSCD = ''' + @TDDSCD + '''  '
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
        // Schema::dropIfExists('TBLSYS');
        Schema::dropIfExists('TBLDSC'); 
    } 
} 
?> 


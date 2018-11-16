
<?php

use App\Console\BaseMigrations;

class CreateTBLUAMTable extends BaseMigrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('TBLUAM');
        Schema::create('TBLUAM', function ($table) {

            $table->increments('TANOMRIY')->nullable(false)->comment('IY');
            $table->integer('TAUSERIY')->nullable(false)->comment('TBLUSR IY');
            $table->integer('TAMENUIY')->nullable(false)->comment('TBLMNU IY');
            $table->char('TAACES',20)->nullable(true)->comment('Access Menu');
            $table->datetime('TALSDT')->nullable(true)->comment('Last Date Use');
            $table->integer('TAUSCT')->nullable(true)->comment('Use Count');

            $this->AutoCreateKolom('TA', $table);

            $table->unique(array('TAUSERIY', 'TAMENUIY'));
            $table->foreign('TAUSERIY')->references('TUUSERIY')->on('TBLUSR');
            $table->foreign('TAMENUIY')->references('TMMENUIY')->on('TBLMNU');    
        }); 

//         DB::unprepared("
//             if exists (select 1
//                       from sysobjects
//                       where  id = object_id('StpTBLUAM')
//                       and type in ('P','PC'))
//                drop procedure StpTBLUAM");

//         DB::unprepared("

//             CREATE procedure [dbo].[StpTBLUAM] (
//                 @TIPE     As VarChar(20),
//                 @USERNAME As VarChar(50),
//                 @SOURCE   As VarChar(10)
//                 , @UNIKNO As VarChar(50)
//                 , @TANOMRIY  AS VarChar(10) 
//                 , @TAUSERIY  AS VarChar(10) 
//                 , @TAUSER    AS VarChar(50) 
//                 , @TAMENUIY  AS VarChar(10) 
//                 , @TAACES AS Char(20)
//                 ) 
//                 WITH EXECUTE as
//             CALLER -- , ENCRYPTION
//             AS
//             Declare @SQLSTM  As NVarChar(Max)
//             Set NOCOUNT ON;
            
//                 IF @TIPE = '1' BEGIN --CREATE TABLE
// atas:                    
//                     Select @TAUSERIY = Cast(TUUSERIY As VarChar) From TBLUSR Where TUUSER = @TAUSER
//                     IF @TAUSERIY = '' BEGIN
//                         RAISERROR('USER IY Tidak boleh Kosong',16,1)
//                     END
                
//                     --EXECUTE @TANOMRIY = StpTBLNOR @USERNAME, 'TBLUAM'; 
//                     SET @SQLSTM = ''
//                     SET @SQLSTM = @SQLSTM + ' INSERT INTO TBLUAM ('
//                     SET @SQLSTM = @SQLSTM + 'TAUSERIY,TAMENUIY,TAACES,'
//                     SET @SQLSTM = @SQLSTM + 'TARGID,TARGDT, ' 
//                     SET @SQLSTM = @SQLSTM + 'TACHID,TACHDT,TACHNO, ' 
//                     SET @SQLSTM = @SQLSTM + 'TADLFG,TACSDT,TACSID,TASRCE,TACSNO'
//                     SET @SQLSTM = @SQLSTM + ' ) VALUES ('
//                     SET @SQLSTM = @SQLSTM + ' '''+ @TAUSERIY +''','
//                     SET @SQLSTM = @SQLSTM + ' '''+ @TAMENUIY +''','
//                     SET @SQLSTM = @SQLSTM + ' '''+ @TAACES +''','
//                     SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''' , ''' + Convert(nVarChar, GetDate(), 121)+ ''', ' 
//                     SET @SQLSTM = @SQLSTM + ' ''' + @USERNAME + ''', ''' + Convert(nVarChar, GetDate(), 121) + ''', ''0'', ' 
//                     SET @SQLSTM = @SQLSTM + ' ''0'', ''' + Convert(nVarChar, GetDate(), 121)+ ''', ''' + @USERNAME + ''', ''' + @SOURCE + ''', ''' + @UNIKNO + '''  '	
//                     SET @SQLSTM = @SQLSTM + ')'
//                     EXEC Sp_ExecuteSql @SQLSTM;
//                     EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;					
//                 END   
//                 Else IF @TIPE = '2' BEGIN
//                     IF not exists(Select * From TBLUAM Where TAUSERIY = @TAUSERIY And TAMENUIY = @TAMENUIY) Begin
//                         goto Atas;
//                     End                
//                     SET @SQLSTM = ''
//                     SET @SQLSTM = @SQLSTM + ' Update TBLUAM Set'
//                     SET @SQLSTM = @SQLSTM + '  TAACES = ''' +@TAACES+ ''' '
//                     SET @SQLSTM = @SQLSTM + ', TACHID = ''' + @USERNAME + ''', TACHDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
//                     SET @SQLSTM = @SQLSTM + ', TACHNO = IsNull(TACHNO,''0'')+1,  TASRCE = ''' + @SOURCE + '''  '
//                     SET @SQLSTM = @SQLSTM + ', TACSID = ''' + @USERNAME + ''', TACSDT = ''' + Convert(nVarChar, GetDate(), 121) + '''  '
//                     SET @SQLSTM = @SQLSTM + ' Where TAUSERIY = ''' + @TAUSERIY + '''  '
//                     SET @SQLSTM = @SQLSTM + ' And TAMENUIY = ''' + @TAMENUIY + '''  '
//                 --			SET @SQLSTM = @SQLSTM + ' Where TANOMRIY = ''' + @TANOMRIY + '''  '
//                     EXEC Sp_ExecuteSql @SQLSTM;
//                     EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;	
//                 END 	
//                 Else IF @TIPE = '3' BEGIN 
//                     SET @SQLSTM = ''
//                     SET @SQLSTM = @SQLSTM + ' Delete From TBLUAM Where TANOMRIY = ''' + @TANOMRIY + '''  '
//                     EXEC Sp_ExecuteSql @SQLSTM;
//                     EXEC [dbo].[StpTBLSLF] @USERNAME,@SQLSTM;		
//                 END

//         ");
    } 
    /** 
     * Reverse the migrations. 
     * 
     * @return void 
     */ 
    public function down() 
    {       
        Schema::dropIfExists('TBLUAM'); 
    } 
} 
?> 


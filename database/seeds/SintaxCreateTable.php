Alter Procedure [dbo].[StpXXXLaravelMigrate] ( @TableName As VarChar(50), @FilePath As VarChar(1000) )
WITH EXECUTE as CALLER -- , ENCRYPTION
AS

Set NoCount On;

Declare @Sintax VarChar(Max)

Declare @B_CONSTRAINT_NAME VarChar(Max)
Declare @B_COLUMN_NAME VarChar(Max)
Declare @B_TABLE_NAME VarChar(Max)

Declare @PK VarChar(Max) = ''
Declare @FK VarChar(Max) = ''
Declare @UNIQUE VarChar(Max) = ''

Declare @FK_T1 VarChar(Max) = ''
Declare @FK_T2 VarChar(Max) = ''
Declare @FK_C1 VarChar(Max) = ''
Declare @FK_C2 VarChar(Max) = ''


Declare @IDX VarChar(Max) = ''
Declare @ID_IDX VarChar(Max) = ''
Declare @ARR_IDX VarChar(Max) = ''
Declare @JML_IDX  Int = 0

Declare @PrintSintaxLaravel  VarChar(Max) = ''

Set @PrintSintaxLaravel = ''
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '' + Char(13)

--Declare @TableName VarChar(50)
--Set @TableName = 'TBLUSR'

Set @PrintSintaxLaravel = @PrintSintaxLaravel + '<?php' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + 'use Illuminate\Support\Facades\Schema;' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + 'use Illuminate\Database\Schema\Blueprint;' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + 'use Illuminate\Database\Migrations\Migration;' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + 'class Create' + @TableName + 'Table extends Migration' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '{' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    /**' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     * Run the migrations.' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     *' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     * @return void' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     */' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    public function up()' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    {' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '        Schema::dropIfExists(''' + @TableName + ''');' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '        Schema::create(''' + @TableName + ''', function (Blueprint $table) {' + Char(13)

    Declare Loop1_cursor Cursor For

        Select 
        '$table->' + 
        Case When DATA_TYPE = 'int' then 'integer' 
             When DATA_TYPE = 'varchar' then 'string' 
             When DATA_TYPE = 'bit' then 'boolean' 
             Else DATA_TYPE end + 
        '(' + 
        Case When DATA_TYPE = 'char' Then '''' + A.COLUMN_NAME + '''' + ',' + Cast(CHARACTER_MAXIMUM_LENGTH As VarChar)
             When DATA_TYPE = 'varchar' Then '''' + A.COLUMN_NAME + '''' + ',' + case when CHARACTER_MAXIMUM_LENGTH < 0 then '''Max''' else Cast(CHARACTER_MAXIMUM_LENGTH As VarChar) End
             Else '''' + A.COLUMN_NAME + '''' End + 
        ')' + 
        '' + 
        Case When IsNull(PK_Field,'') <> '' Then '->unique()' else '' End + 
        Case When IS_NULLABLE = 'YES' Then '->nullable(true)' else '->nullable(false)' End + 
        --Case When IsNull(COLUMN_DEFAULT,'') = '' Then '' else '-->default(' + replace(replace(COLUMN_DEFAULT,'(',''),')','') + ')' End + 
        Case When IsNull(COLUMN_DEFAULT,'') = '' Then '' 
             When IsNull(COLUMN_DEFAULT,'') = '(suser_sname())' Then '' 
             When IsNull(COLUMN_DEFAULT,'') = '(getdate())' Then '' 
             Else '->default(' + COLUMN_DEFAULT + ')' End + 
        ';' [Sintax] 
        , A.TABLE_NAME, A.COLUMN_NAME, '' CONSTRAINT_NAME
        From INFORMATION_SCHEMA.columns [A] 
        Left Join (
            SELECT 
                c.name AS PK_Field,
                i.name AS PK_Name,
                c.is_identity PK_Identity
            FROM sys.indexes i
                inner join sys.index_columns ic  ON i.object_id = ic.object_id AND i.index_id = ic.index_id
                inner join sys.columns c ON ic.object_id = c.object_id AND c.column_id = ic.column_id
            WHERE i.is_primary_key = 1
                and i.object_ID = OBJECT_ID(@TableName)
        ) PK On PK_Field = A.COLUMN_NAME
        --Where A.TABLE_NAME = 'TBLUSR'
        Where A.TABLE_NAME = @TableName

    Open Loop1_cursor
    Fetch Next From Loop1_cursor 
    Into @Sintax, @B_TABLE_NAME, @B_COLUMN_NAME, @B_CONSTRAINT_NAME
    While @@FETCH_STATUS = 0
    Begin
        Set @PrintSintaxLaravel = @PrintSintaxLaravel + '         ' + @Sintax + Char(13)
        
        IF LEFT(@B_CONSTRAINT_NAME,2) = 'PK' BEGIN
            Set @PK = @PK + '$table->primary(''' + @B_COLUMN_NAME + ''');' + Char(13)
        END

        Fetch Next From Loop1_cursor
        Into @Sintax, @B_TABLE_NAME, @B_COLUMN_NAME, @B_CONSTRAINT_NAME
    End
    Close Loop1_cursor
    Deallocate Loop1_cursor


    --PRINT Char(13)

    --SELECT 
    --  STUFF((
    --      SELECT ',''' + c.name + ''''
    --      FROM sys.indexes i
    --          inner join sys.index_columns ic  ON i.object_id = ic.object_id AND i.index_id = ic.index_id
    --          inner join sys.columns c ON ic.object_id = c.object_id AND c.column_id = ic.column_id
    --      WHERE i.is_primary_key = 1
    --          and i.object_ID = OBJECT_ID('TBLSYS')
    --      FOR XML PATH('')
    --      ), 1, 1, '') 

    Set @PrintSintaxLaravel = @PrintSintaxLaravel + '         ' + @PK  + Char(13)


    Declare Loop2_cursor Cursor For

        SELECT o2.name AS Referenced_Table_Name
                ,c2.name AS Referenced_Column_As_FK
                ,o1.name AS Referencing_Table_Name
                ,c1.name AS Referencing_Column_Name
                --,s.name AS Constraint_name
        FROM  sysforeignkeys fk
        INNER JOIN sysobjects o1 ON fk.fkeyid = o1.id
        INNER JOIN sysobjects o2 ON fk.rkeyid = o2.id
        INNER JOIN syscolumns c1 ON c1.id = o1.id AND c1.colid = fk.fkey
        INNER JOIN syscolumns c2 ON c2.id = o2.id AND c2.colid = fk.rkey
        INNER JOIN sysobjects s ON fk.constid = s.id
        Where o1.name = @TableName
        ORDER BY o2.name

    Open Loop2_cursor
    Fetch Next From Loop2_cursor 
    Into @FK_T1, @FK_C1, @FK_T2, @FK_C2
    While @@FETCH_STATUS = 0
    Begin

        Set @FK = @FK + '$table->foreign(''' + @FK_C2 + ''')->references(''' + @FK_C1 + ''')->on(''' + @FK_T1 + ''');' + Char(13) + '           '

        Fetch Next From Loop2_cursor
        Into @FK_T1, @FK_C1, @FK_T2, @FK_C2
    End
    Close Loop2_cursor
    Deallocate Loop2_cursor

    IF @FK <> '' BEGIN              
        Set @PrintSintaxLaravel = @PrintSintaxLaravel + '         ' + @FK + Char(13)
    END

    Select 
        a.index_id, b.name [index_name], c.name [column_name]
        Into #TableIndex
    From sys.index_columns A
    Left Join Sys.indexes B On B.object_id = A.object_id And B.index_id = A.index_id
    Left Join Sys.Columns C On C.object_id = B.object_id And C.Column_id = A.Column_id
    Where a.object_id = Object_Id(@TableName)
    and b.type = 2


    Declare Loop3_cursor Cursor For


        Select [ID] , Jml_IDX,
            STUFF((
                SELECT ',''' + column_name + ''''
                FROM #TableIndex
                WHERE index_id = [ID]
                FOR XML PATH('')
                ), 1, 1, '') [ArrIDX]
        From (
            Select index_id [ID], Count(*) Jml_IDX From #TableIndex
            Group By index_id
        ) A


    Open Loop3_cursor
    Fetch Next From Loop3_cursor 
    Into @ID_IDX, @JML_IDX, @ARR_IDX
    While @@FETCH_STATUS = 0
    Begin
        if @JML_IDX > 1 BEGIN
            Set @IDX = @IDX + '$table->unique(array(' + @ARR_IDX + ')); ' + Char(13) + '           '
        END ELSE BEGIN
            Set @IDX = @IDX + '$table->unique(' + @ARR_IDX + ');' + Char(13) + '           '
        END
        Fetch Next From Loop3_cursor
        Into @ID_IDX, @JML_IDX, @ARR_IDX
    End
    Close Loop3_cursor
    Deallocate Loop3_cursor
    
    IF @IDX <> '' BEGIN
        Set @PrintSintaxLaravel = @PrintSintaxLaravel + '         ' + @IDX + Char(13)
    END

Set @PrintSintaxLaravel = @PrintSintaxLaravel + '        }); ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    } ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    /** ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     * Reverse the migrations. ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     * ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     * @return void ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '     */ ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    public function down() ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    { ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '        Schema::dropIfExists(''' + @TableName + '''); ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '    } ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '} ' + Char(13)
Set @PrintSintaxLaravel = @PrintSintaxLaravel + '?> ' + Char(13)


Print @PrintSintaxLaravel;


    DECLARE @OLE            INT 
    DECLARE @FileID         INT 
    DECLARE @FileNAME VarChar(100)

    IF @FilePath <> '' BEGIN

        SElect replace(replace(replace(Convert(VarChar,GetDate(),120),'-','_'),' ','_'),':','')

        --'2013_04_10_000050_create_tblusr_table'
        Set @FileNAME = replace(replace(replace(Convert(VarChar,GetDate(),120),'-','_'),' ','_'),':','') + '_create_'+lower(@TableName)+'_table.php'
        Set @FilePath = @FilePath + '\' + @FileNAME

        EXECUTE sp_OACreate 'Scripting.FileSystemObject', @OLE OUT 

        EXECUTE sp_OAMethod @OLE, 'OpenTextFile', @FileID OUT, @FilePath, 8, 1 

        EXECUTE sp_OAMethod @FileID, 'WriteLine', Null, @PrintSintaxLaravel

        EXECUTE sp_OADestroy @FileID 
        EXECUTE sp_OADestroy @OLE 
    END

/*



Exec StpXXXLaravelMigrate 'TBLUSR', 'C:\nginx\html\LaravelWili\database\migrations'
Exec StpXXXLaravelMigrate 'TBLMNU', 'C:\nginx\html\LaravelWili\database\migrations'
Exec StpXXXLaravelMigrate 'TBLUAM', 'C:\nginx\html\LaravelWili\database\migrations'
Exec StpXXXLaravelMigrate 'TBLDSC', 'C:\nginx\html\LaravelWili\database\migrations'
Exec StpXXXLaravelMigrate 'TBLSYS', 'C:\nginx\html\LaravelWili\database\migrations'
Exec StpXXXLaravelMigrate 'TBLSMG', 'C:\nginx\html\LaravelWili\database\migrations'

Exec StpXXXLaravelMigrate 'TBLSYS', ''

TBLUAM



SELECT 
    c.name AS column_name,
    i.name AS index_name,
    c.is_identity
FROM sys.indexes i
    inner join sys.index_columns ic  ON i.object_id = ic.object_id AND i.index_id = ic.index_id
    inner join sys.columns c ON ic.object_id = c.object_id AND c.column_id = ic.column_id
WHERE i.is_primary_key = 1
    and i.object_ID = OBJECT_ID('TBLUSR');



SELECT 
  STUFF((
        SELECT ',''' + c.name + ''''
        FROM sys.indexes i
            inner join sys.index_columns ic  ON i.object_id = ic.object_id AND i.index_id = ic.index_id
            inner join sys.columns c ON ic.object_id = c.object_id AND c.column_id = ic.column_id
        WHERE i.is_primary_key = 1
            and i.object_ID = OBJECT_ID('TBLSYS')
        FOR XML PATH('')
        ), 1, 1, '') 





SELECT * FROM sys.sysobjects WHERE type!='u' AND name LIKE 'TBLUSR'
select * from sys.indexes where object_id = object_id('TBLUSR') 
 where is_unique_constraint = 1

SELECT * 
FROM sys.tables  
where name = 'TBLUSR'


Select * From Sys.Tables Where Name = 'TBLUSR'
Select * From Sys.Columns Where object_id = Object_Id('TBLUSR')
Select * From Sys.indexes Where object_id = Object_Id('TBLUSR')
select * From sys.index_columns Where object_id = Object_Id('TBLUSR')


Select * From Sys.indexes Where object_id = Object_Id('HRDSPL')
select * From sys.index_columns Where object_id = Object_Id('HRDSPL')

select 
    a.index_id, b.name [index_name], c.name [column_name]
    --Into #TableIndex
From sys.index_columns A
Left Join Sys.indexes B On B.object_id = A.object_id And B.index_id = A.index_id
Left Join Sys.Columns C On C.object_id = B.object_id And C.Column_id = A.Column_id
Where a.object_id = Object_Id('TBLUSR')
and b.type = 2

Select *,
    STUFF((
        SELECT ',''' + column_name + ''''
        FROM #TableIndex
        WHERE index_id = [ID]
        FOR XML PATH('')
        ), 1, 1, '') 
From (
    Select index_id [ID] From #TableIndex
    Group By index_id
) A


WHERE OBJECTPROPERTY(object_id,'IsIndexed') = 0;
 
Select 
        '$table->' + 
        Case When DATA_TYPE = 'int' then 'integer' 
             When DATA_TYPE = 'varchar' then 'string' 
             When DATA_TYPE = 'bit' then 'boolean' 
             Else DATA_TYPE end + 
        '(' + 
        Case When DATA_TYPE = 'char' Then '''' + A.COLUMN_NAME + '''' + ',' + Cast(CHARACTER_MAXIMUM_LENGTH As VarChar)
             When DATA_TYPE = 'varchar' Then '''' + A.COLUMN_NAME + '''' + ',' + case when CHARACTER_MAXIMUM_LENGTH < 0 then '''Max''' else Cast(CHARACTER_MAXIMUM_LENGTH As VarChar) End
             Else '''' + A.COLUMN_NAME + '''' End + 
        ')' + 
        '' + 
        Case When Left(CONSTRAINT_NAME,2) = 'PK' Then '->unique()' else '' End + 
        Case When IS_NULLABLE = 'YES' Then '->nullable(true)' else '->nullable(false)' End + 
        --Case When IsNull(COLUMN_DEFAULT,'') = '' Then '' else '-->default(' + replace(replace(COLUMN_DEFAULT,'(',''),')','') + ')' End + 
        Case When IsNull(COLUMN_DEFAULT,'') = '' Then '' 
             When IsNull(COLUMN_DEFAULT,'') = '(suser_sname())' Then '' 
             When IsNull(COLUMN_DEFAULT,'') = '(getdate())' Then '' 
             Else '->default(' + COLUMN_DEFAULT + ')' End + 
        ';' [Sintax] 
        , *
        From INFORMATION_SCHEMA.columns [A] 
        Left Join INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE [B] On 
            B.TABLE_CATALOG = A.TABLE_CATALOG And 
            B.TABLE_SCHEMA = A.TABLE_SCHEMA And
            B.TABLE_NAME = A.TABLE_NAME And
            B.COLUMN_NAME = A.COLUMN_NAME
        Where A.TABLE_NAME = 'TBLSYS'

        Select * From INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE
        Where TABLE_NAME = 'TBLSYS'
        
        Select * From INFORMATION_SCHEMA.columns [A] 
        Left Join INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE [B] On 
            B.TABLE_CATALOG = A.TABLE_CATALOG And 
            B.TABLE_SCHEMA = A.TABLE_SCHEMA And
            B.TABLE_NAME = A.TABLE_NAME And
            B.COLUMN_NAME = A.COLUMN_NAME
        Where A.TABLE_NAME = 'TBLSYS'

SELECT OBJECT_NAME(OBJECT_ID) AS NameofConstraint,
SCHEMA_NAME(schema_id) AS SchemaName,
OBJECT_NAME(parent_object_id) AS TableName,
type_desc AS ConstraintType
FROM sys.objects 
WHERE type_desc IN ('FOREIGN_KEY_CONSTRAINT','PRIMARY_KEY_CONSTRAINT')
And OBJECT_NAME(parent_object_id) = 'TBLUSR'

SELECT o2.name AS Referenced_Table_Name,
       c2.name AS Referenced_Column_As_FK,
       o1.name AS Referencing_Table_Name,
       c1.name AS Referencing_Column_Name,
s.name AS Constraint_name
FROM  sysforeignkeys fk
INNER JOIN sysobjects o1 ON fk.fkeyid = o1.id
INNER JOIN sysobjects o2 ON fk.rkeyid = o2.id
INNER JOIN syscolumns c1 ON c1.id = o1.id AND c1.colid = fk.fkey
INNER JOIN syscolumns c2 ON c2.id = o2.id AND c2.colid = fk.rkey
INNER JOIN sysobjects s ON fk.constid = s.id
Where o2.name = 'TBLUAM'
ORDER BY o2.name

Select 
'$table->' + 
Case When [Tipe] = 'int' then 'integer' else [Tipe] end + 
'(' + 
Case When [Tipe] = 'char' Then Kolom + ',' + Cast(LGTH As VarChar)
     When [Tipe] = 'varchar' Then Kolom + ',' + case when LGTH < 0 then '''Max''' else Cast(LGTH As VarChar) End
     Else Kolom End + 
')' + 
'' + 
Case When IS_NULLABLE = '1' Then '-->nullable(false)' else '-->nullable(true)' End + 
Case When IsNull(COLUMN_DEFAULT,'') = '' Then '' else '-->default(' + replace(replace(COLUMN_DEFAULT,'(',''),')','') + ')' End + 
'' ,
* 
From (
    Select 
        b.name [Tabel], 
        a.name [Kolom], 
        c.name [Tipe], 
        a.max_length [Lgth], 
        a.precision, a.scale,
        isnull(d.index_id,0) as [primary], 
        a.is_nullable,
        IsNull(e.VALUE,'') [Deskripsi] 
        --, *
    From sys.all_columns [a] 
    Left Join sysobjects [b] on a.object_id = b.id
    Left Join sys.types [c] on a.system_type_id = c.system_type_id
    Left Join sys.index_columns [d] on a.object_id = d.object_id and a.column_id = d.column_id
    Left Join SYS.EXTENDED_PROPERTIES [e] ON [e].major_id = b.id And e.minor_id = a.column_id
    Where b.name = 'TBLSYS'
) Semua




SElect 
'DB::table(''TBLMNU'')->insert([ ' + 
'''TMMENUIY'' => ''' + IsNull(Cast(TMMENUIY As VarChar),'') + ''', ' + 
'''TMNOMR'' => ''' + IsNull(Cast(TMNOMR As VarChar),'') + ''', ' + 
'''TMGRUP'' => ''' + IsNull(Cast(TMGRUP As VarChar),'') + ''', ' + 
'''TMMENU'' => ''' + IsNull(Cast(TMMENU As VarChar),'') + ''', ' + 
'''TMDESC'' => ''' + IsNull(Cast(TMDESC As VarChar),'') + ''', ' + 
'''TMSCUT'' => ''' + IsNull(Cast(TMSCUT As VarChar),'') + ''', ' + 
'''TMACES'' => ''' + IsNull(Cast(TMACES As VarChar),'') + ''', ' + 
'''TMBCDT'' => ''' + IsNull(Cast(TMBCDT As VarChar),'') + ''', ' + 
'''TMFWDT'' => ''' + IsNull(Cast(TMFWDT As VarChar),'') + ''', ' + 
'''TMURLW'' => ''' + IsNull(Cast(TMURLW As VarChar),'') + ''', ' + 
'''TMSYFG'' => ''' + IsNull(Cast(TMSYFG As VarChar),'') + ''', ' + 
'''TMUSCT'' => ''' + IsNull(Cast(TMUSCT As VarChar),'') + ''', ' + 
'''TMLSDT'' => ''' + IsNull(Cast(TMLSDT As VarChar),'') + ''', ' + 
'''TMLSBY'' => ''' + IsNull(Cast(TMLSBY As VarChar),'') + ''', ' + 
'''TMRLDT'' => ''' + IsNull(Cast(TMRLDT As VarChar),'') + ''', ' + 
'''TMGRID'' => '''', ' + 
'''TMREMK'' => ''' + IsNull(Cast(TMREMK As VarChar),'') + ''', ' + 
'''TMRGID'' => ''' + IsNull(Cast(TMRGID As VarChar),'') + ''', ' + 
'''TMRGDT'' => ''' + IsNull(Cast(TMRGDT As VarChar),'') + ''', ' + 
'''TMCHID'' => ''' + IsNull(Cast(TMCHID As VarChar),'') + ''', ' + 
'''TMCHDT'' => ''' + IsNull(Cast(TMCHDT As VarChar),'') + ''', ' + 
'''TMCHNO'' => ''' + IsNull(Cast(TMCHNO As VarChar),'') + ''', ' + 
'''TMDLFG'' => ''' + IsNull(Cast(TMDLFG As VarChar),'') + ''', ' + 
'''TMDPFG'' => ''' + IsNull(Cast(TMDPFG As VarChar),'') + ''', ' + 
'''TMPTFG'' => ''' + IsNull(Cast(TMPTFG As VarChar),'') + ''', ' + 
'''TMPTCT'' => ''' + IsNull(Cast(TMPTCT As VarChar),'') + ''', ' + 
'''TMPTID'' => ''' + IsNull(Cast(TMPTID As VarChar),'') + ''', ' + 
'''TMPTDT'' => ''' + IsNull(Cast(TMPTDT As VarChar),'') + ''', ' + 
'''TMSRCE'' => ''' + IsNull(Cast(TMSRCE As VarChar),'') + ''', ' + 
'''TMUSRM'' => ''' + IsNull(Cast(TMUSRM As VarChar),'') + ''', ' + 
'''TMITRM'' => ''' + IsNull(Cast(TMITRM As VarChar),'') + ''', ' + 
'''TMCSDT'' => ''' + IsNull(Cast(TMCSDT As VarChar),'') + ''', ' + 
'''TMCSID'' => ''' + IsNull(Cast(TMCSID As VarChar),'') + ''', ' + 
' ]);'
From TBLMNU Order By TMNOMR

*/
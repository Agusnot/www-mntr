<?
$link = mysql_connect("localhost","root","");
mysql_select_db("afiliados",$link) OR DIE ("Error: Imposible Conectar");
$cons2 = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS'
        . ' WHERE table_name = \'usuarios\''
        . ' AND table_schema = \'afiliados\' LIMIT 0, 30 '; 
$res2=mysql_query($cons2);
$cons3 = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS'
        . ' WHERE table_name = \'auxafiliados\''
        . ' AND table_schema = \'afiliados\' LIMIT 0, 30 '; 
$res3=mysql_query($cons3);

?>



<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><? echo $Nombre?></td></tr>    
    <tr><td align="center"><img src="<? echo $Ruta?>"></td></tr>
    <tr><td align="center"><input type="button" value="Regresar" onClick="location.href='Anexos.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table>    
</body>
</html>

<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	$cons="select codigo,nombre,tipo,ruta from odontologia.procedimientosimgs where compania='$Compania[0]' and codigo='$Codigo'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<body>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<? 	if($fila[3]!='')
	{?>		
   		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Nombre: <? echo "$fila[1]"?></td></tr>
   		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td><img src="<? echo "$fila[3]"?>" title="<? echo $fila[3]?>"/></td></tr>
<?	}
	else
	{?>
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No se ha podido localizar la imagen</td></tr>
<?	}?> 
	<tr align="center">
    	<td><input type="button" value="Regresar" onClick="location.href='ImgsOdontologia.php?DatNameSID=<? echo $DatNameSID?>'" /></td>
  	</tr>       
</table>    
</body>
</form>
</html>

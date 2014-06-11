<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		$cons="update salud.tiemposconsulta set tiempoantes=$TiempoAntes,tiempodespues=$TiempoDespues where compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError($res);
	}
	if(!$TiempoAntes||!$TiempoDespues){
		$cons="Select * from salud.tiemposconsulta where compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila = ExFetchArray($res);
		$TiempoAntes=$fila[1];
		$TiempoDespues=$fila[2];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<? 	$cons="select activacioncitas from salud.intervalostiempos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila = ExFetchArray($res);
	$intervalo=$fila[0]?>
<tr bgcolor="#e5e5e5" style=" font-weight:bold">
	<td>Tiempo Antes</td><td>Tiempo Despues</td>
</tr>
<tr>
	<td align="center"><select name="TiempoAntes">
    <? 	for($i=-300;$i<=240;$i=$i+$intervalo){
			if($TiempoAntes==$i){
				echo "<option value='$i' selected>$i</option>";
			}
			else{
				echo "<option value='$i'>$i</option>";
			}
		}?>    	
        </select>
    </td>
    <td align="center"><select name="TiempoDespues">
    <? 	for($i=-20;$i<=240;$i=$i+$intervalo){
			if($TiempoDespues==$i){
				echo "<option value='$i' selected>$i</option>";
			}
			else{
				echo "<option value='$i'>$i</option>";
			}
		}?>    	
        </select>
    </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="Guardar" name="Guardar"></td>
</tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>

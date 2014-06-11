<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{	
		$cons="Delete from Salud.motivocancelcita where origencalcel='$OrigenCancel' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
		$cons="Delete from Salud.origencancelcita where origencancelacion='$OrigenCancel' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">  
	<tr bgcolor="#e5e5e5" style=" font-weight:bold">
    	<td>Origen Cancelacion</td><td colspan="2"></td>
    </tr>
    <?	$cons="Select origencancelacion from Salud.origencancelcita where Compania='$Compania[0]'";
		$result=ExQuery($cons);    
	while($row = ExFetchArray($result))
	{ 
		if($row[2]==1){$ConsExt='Si';}else{$ConsExt='No';}
		echo "<tr><td>$row[0]</td><td>";?>
		<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfOrigenCancelCita.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&OrigenCancel=<? echo $row[0]?>'"></td><td>
		<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfOrigenCancelCita.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&OrigenCancel=<? echo $row[0]?>';}" src="/Imgs/b_drop.png"></td></tr>
<?	} ?>
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="button" onClick="location.href='NewConfOrigenCancelCita.php?DatNameSID=<? echo $DatNameSID?>'" value="Nuevo"></td>
    </tr>
</table>    
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

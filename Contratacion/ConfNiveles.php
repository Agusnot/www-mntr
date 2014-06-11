<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Salud.Nivelesusu where nivel='$Nivel'";
		$res=ExQuery($cons);echo ExError();
	}
	$result=ExQuery("Select * from Salud.nivelesusu");
?>
<html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body background="/Imgs/Fondo.jpg">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<TR bgcolor="#e5e5e5" style="font-weight:bold">
		<TD>Proceso</TD><td colspan="2"></td>
	</TR>
<?php 
	while($row = ExFetchArray($result))
	{ 
		echo "<tr><td>".$row['nivel']."</td><td>";?>
		<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfNiveles.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Nivel=<? echo $row['nivel']?>'"></td><td>
		<img style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfNiveles.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Nivel=<? echo $row['nivel']?>';}" src="/Imgs/b_drop.png"></td></tr>
<?	} 

?>
</table><br>
<input type="button" onClick="location.href='NewConfNiveles.php?DatNameSID=<? echo $DatNameSID?>'" value="Nuevo">
</body>
</html>

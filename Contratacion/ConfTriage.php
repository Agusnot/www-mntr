<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Salud.Triage where Prioridad='$Prioridad' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}	
	$result=ExQuery("Select * from Salud.Triage where Compania='$Compania[0]' order by triage");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<TR bgcolor="#e5e5e5" style="font-weight:bold">
		<TD>Triage</TD><td>Prioridad</td><td colspan="2"></td>
	</TR>
<?php 
	while($row = ExFetchArray($result))
	{ 
		echo "<tr><td>".$row['triage']."</td><td>".$row['prioridad']."</td><td>";?>
		<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfTriage.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Prioridad=<? echo $row['prioridad']?>'"></td><td>
		<img style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfTriage.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Prioridad=<? echo $row['prioridad']?>';}" src="/Imgs/b_drop.png"></td></tr>
<?	} 

?>
<tr align="center">
	<td colspan="4"><input type="button" onClick="location.href='NewConfTriage.php?DatNameSID=<? echo $DatNameSID?>&'" value="Nuevo"></td>
</tr>
</table><br>

</body>
</html>

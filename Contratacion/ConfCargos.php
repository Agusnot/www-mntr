<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Salud.Cargos where Cargos='$Cargo' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}	
	$result=ExQuery("Select * from Salud.Cargos where Compania='$Compania[0]' order by cargos");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">   
	<TR bgcolor="#e5e5e5" style="font-weight:bold">
		<TD>Cargo</TD><TD>Asistencial</TD><td>Tratante</td><td>Visto Bueno Jefe</td><td>Visto Bueno Auxiliar</td><td>Interpretar</td><td>Asignar Imagen</td>
        <td>Autoriza Egreso</td>
        <td colspan="2"></td>
	</TR>
<?php 
	while($row = ExFetchArray($result))
	{ 
		if($row['asistencial']==1){$Asis="Si";}else{$Asis="No";}
		if($row['tratante']==1){$Trat="Si";}else{$Trat="No";}
		if($row['vistobuenojefe']==1){$Jefe="Si";}else{$Jefe="No";}
		if($row['vistobuenoaux']==1){$Aux="Si";}else{$Aux="No";}
		if($row['interpretar']==1){$Interpreta="Si";}else{$Interpreta="No";}
		if($row['asigrutaimg']==1){$Ruta="Si";}else{$Ruta="No";}
		if($row['autorizaegr']==1){$AutorizaEgr="Si";}else{$AutorizaEgr="No";}
		echo "<tr align='center'><td>".$row['cargos']."</td><td align='center'>".$Asis."</td><td align='center'>".$Trat."</td><td align='center'>".$Jefe."</td>
		<td align='center'>".$Aux."</td>
		<td>".$Interpreta."</td><td>".$Ruta."</td><td>$AutorizaEgr</td><td>";?>
		<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfCargos.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Cargo=<? echo $row['cargos']?>'"></td><td>
		<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfCargos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cargo=<? echo $row['cargos']?>';}" src="/Imgs/b_drop.png"></td></tr>        
<?	} 
?>
	<tr align="center">
		<td colspan="11"><input type="button" onClick="location.href='NewConfCargos.php?DatNameSID=<? echo $DatNameSID?>'" value="Nuevo"></td>
	</tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

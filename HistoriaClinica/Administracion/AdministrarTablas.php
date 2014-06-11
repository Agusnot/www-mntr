<?
	session_start();
	include("Funciones.php");
	if($Elim)
	{
		$Criterio=str_replace("|","'",$Criterio);
		$cons1="Delete from $Tabla where $Criterio";
		$cons1 = $cons1 . " Limit 1";
		$res1=ExQuery($cons1);
	}
?>
<body background="/Imgs/Fondo.jpg">
<table border="1" background="/Imgs/encabezado.jpg" border='1' cellspacing='0' style="font : normal normal small-caps 13px Tahoma;">
<?
	$cons="Select * from $Tabla";
	$res=ExQuery($cons);

	echo "<tr style='font-weight:bold;color:white;text-align:center'>";
	for($i=0;$i<=mysql_num_fields($res)-1;$i++)
	{
		echo "<td>".mysql_field_name($res,$i)."</td>";
		$NumCols++;
	}
	echo "</tr>";

	while($fila=ExFetch($res))
	{
		echo "<tr bgcolor='white'>";
		for($i=0;$i<=mysql_num_fields($res)-1;$i++)
		{
				$Criterio=$Criterio . mysql_field_name($res,$i) . "=|$fila[$i]| and ";
				echo "<td>$fila[$i]</td>";
		}
		$Criterio=substr($Criterio,0,strlen($Criterio)-4);?>
		<td><a href="NuevoRegistro.php?Tabla=<?echo $Tabla?>&Edit=1&Criterio=<?echo $Criterio?>"><img border='0' src="/Imgs/b_edit.png"></a></td>
		<td><a style="cursor:hand" onclick="if(confirm('Desea eliminar?')==true){location.href='AdministrarTablas.php?Tabla=<?echo $Tabla?>&Elim=1&Criterio=<?echo $Criterio?>';}"><img border=0 src="/Imgs/b_drop.png"></td>
		</tr>
<?		$Criterio="";
	}
?>
<tr><td align="center" colspan="<?echo $NumCols+2?>"><input type="Button" value="Nuevo Registro" onClick="location.href='NuevoRegistro.php?Tabla=<?echo $Tabla?>'"></td></tr>
</table>
</body>
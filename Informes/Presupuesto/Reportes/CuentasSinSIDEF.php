<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>

<table border="1"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
<tr><td colspan="2">VIGENCIA ACTUAL</td></tr>
	<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Cuenta</td><td>Detalle</td></tr>
<?	$cons="Select Cuenta,Nombre from Presupuesto.PlanCuentas where (codigocgr='' Or CodigoCGR IS NULL) and Tipo='Detalle' and Anio=$Anio and Vigencia='Actual' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td></tr>";
	}
?>
</table>

<table border="1"  bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" >
<tr><td colspan="2">VIGENCIAS ANTERIORES</td></tr>
	<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Cuenta</td><td>Detalle</td></tr>
<?	$cons="Select Cuenta,Nombre from Presupuesto.PlanCuentas where (codigocgr='' Or CodigoCGR IS NULL) and Tipo='Detalle' and Anio=$Anio and Vigencia='Anteriores' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td></tr>";
	}
?>
</table>
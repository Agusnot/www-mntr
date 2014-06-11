<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-$MesFin-$DiaFin");

?>
<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 12px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>
<form name="FORMA" method="post">
Tarifario:
<select name="Tarifario" onchange="FORMA.submit()"><option></option>
<?
	$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' 
	and AlmacenPpal='$AlmacenPpal' and Estado='AC'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Tarifario){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td></tr></table>
<? if ($Tarifario) { ?>
<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
<td rowspan="2">Codigo</td><td rowspan="2">Nombre Generico</td><td rowspan="2">Nombre x Laboratorio<td colspan="2">Saldo</td><td rowspan="2">Costo Unidad</td><td rowspan="2">Precio de Venta</td></tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Cantidad</td><td>Valores</td></tr>
<?
	$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,ValorVenta,Max(FechaFin),TarifasxProducto.AutoId,NombreProd2 
	from Consumo.CodProductos,Consumo.TarifasxProducto
	where CodProductos.AutoId=TarifasxProducto.AutoId and CodProductos.Compania='$Compania[0]' and CodProductos.AlmacenPpal='$AlmacenPpal'
	and Tarifario='$Tarifario' and FechaFin>='$Anio-$MesFin-$DiaFin'
	group by TarifasxProducto.AutoId ";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$CantExistencias=$VrSaldoIni[$fila[6]][0];
		$VrtExistencias=$VrSaldoIni[$fila[6]][1];
		$CostoUnidad=$VrtExistencias/$CantExistencias;
		echo "<tr><td>$fila[0]</td><td>$fila[8] $fila[2] $fila[3]</td><td>$fila[1] $fila[2] $fila[3]</td>
		<td align='right'>".number_format($CantExistencias,2)."</td>
		<td align='right'>".number_format($VrtExistencias,2)."</td>
		<td align='right'>".number_format($CostoUnidad,2)."</td>
		<td align='right'>".number_format($fila[4],2)."</td></tr>";	
	}
?>
</table>
<? } ?>
</form>

<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");
?>
<form name="FORMA" method="post">
<table border="1">
<tr><td>Comprobante</td>
<td><select name="Comprobante" onchange="document.FORMA.submit();">
<option>
<?
	$cons="Select Comprobante from Presupuesto.Comprobantes where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>
</select></td>
</tr>
</table>
<input type="Hidden" name="MesIni" value="<?echo $MesIni?>">
<input type="Hidden" name="MesFin" value="<?echo $MesFin?>">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
</form>
<?
	if($Comprobante)
	{
?>
<table border="1">
<tr><td>Numero</td><td>Valor</td><td>Debitos</td><td>Creditos</td><td>Cuenta Cero</td><td>Diferencia</td></tr>
<?
	$cons="Select Numero,Debe,Haber,Cuenta,DocDestino from Contabilidad.Movimiento 
	where Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-31' and Estado='AC'
	and Cuenta like '0%' and DocDestino like '$Comprobante%'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$NumDocOrig=explode("-",$fila[4]);
		$cons1="Select Credito,ContraCredito from Presupuesto.Movimiento where Comprobante='$NumDocOrigen[0]' and Numero='$NumDocOrigen[1]' and Compania='$Compania[9]' and Estado='AC'
		and Vigencia='Actual'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		echo "<tr><td>$NumDocOrig[1]</td><td>$fila1[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td></tr>";
	}
?>
</table>
<?	}?>
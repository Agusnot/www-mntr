<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Corte="$Anio-$MesFin-$DiaFin";
	if(!$Generar){
	?>
<form name="FORMA">
	<table border="1">
	<?
	$cons="Select UsuarioCre from Contabilidad.Movimiento where CierrexCajero='$Corte' and Estado='AC' Group By UsuarioCre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td><em>$fila[0]</td><td><input type='Checkbox' name='Seleccion[$fila[0]]'></td></tr>";
	}
?>
<tr><td colspan="2"><center><input type="Submit" value="Generar" name="Generar"></td></tr>
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="DiaFin" value="<?echo $DiaFin?>">
<input type="Hidden" name="MesFin" value="<?echo $MesFin?>">
<input type="Hidden" name="Corte" value="<?echo $Corte?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</table>
</form>

<?	
	}
	else
	{
		$condAdc=" and (";
		while (list($val,$cad) = each ($Seleccion)) 
		{

			$condAdc=$condAdc." UsuarioCre='$val' Or ";
		}
		$condAdc=substr($condAdc,1,strlen($condAdc)-5);
		$condAdc=$condAdc.")";

	
?>
	<table width="60%" border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>CIERRE DE CAJA</td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	</table>

	<table border="1" rules="groups" bordercolor="#ffffff" width="90%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style='font-weight:bold' bgcolor="#e5e5e5"><td>FECHA</td><td>NUMERO</td><td>CONCEPTO</td><td>VALOR</td></tr>
<?
	$consCta="Select Cuenta from Contabilidad.PlanCuentas where Cuenta like '110505%' and Compania='$Compania[0]' and Tipo='Detalle' and Anio=$Anio";
	$resCta=ExQuery($consCta);
	while($filaCta=ExFetch($resCta))
	{

			$cons2="Select Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[5]' and Anio='$Anio' and Compania='$Compania[0]'";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);

$cons="Select Fecha,Numero,sum(Debe),FechaCre,Detalle,Cuenta from Contabilidad.Movimiento where Cuenta='$filaCta[0]' 
		and CierrexCajero='$Corte' and Estado='AC' $condAdc and Compania='$Compania[0]' and Debe>0 Group By Numero,Fecha,FechaCre,Detalle,Cuenta Order By Numero";

		$res=ExQuery($cons);echo mysql_error();
		while($fila=ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[4]</td><td align='right'>".number_format($fila[2],2)."</td></tr>";
			$Total=$Total+$fila[2];
		}
		echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td colspan=3>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";
	?>
	</table><br><br>
   <?
	}
?>
____________________________________<br>

<? echo $usuario[0];
	}
?>
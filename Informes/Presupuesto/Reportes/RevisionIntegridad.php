<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
	$cons="SELECT sum(Credito),sum(ContraCredito),sum(Credito)-sum(ContraCredito) as Total,Movimiento.Comprobante,Numero,Fecha
	FROM Presupuesto.Movimiento,Presupuesto.TiposComprobante,Presupuesto.Comprobantes 
	where Movimiento.Comprobante=Comprobantes.Comprobante and Tipo=TipoComprobant  
	and Fecha>='$PerIni' and Fecha<='$PerFin' and Descuadre='No'
	and Movimiento.Compania='$Compania[0]'	and Comprobantes.Compania='$Compania[0]'
	group By Movimiento.Comprobante,Numero,Fecha having (sum(Credito)-sum(ContraCredito))!=0 Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Documentos descuadrados : ".ExNumRows($res)."</em>";?>
	<table cellpadding="4" width="80%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Fecha</td><td>Numero</td><td>Debitos</td><td>Creditos</td><td>Diferencia</td></tr>
<?
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[3]</td><td>$fila[5]</td><td>$fila[4]</td><td align='right'>".number_format($fila[0],2)."</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[2],2)."</td></tr>";
	}
?>
</table>

<?
	echo "<hr><em>Compromisos sin Afectacion Adecuada:</em>";
	$cons="Select Movimiento.Cuenta,Movimiento.Comprobante,DocSoporte,CompAfectado,TipoComprobant,Fecha,Numero,Cuenta,Detalle
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Compromiso presupuestal' and Estado='AC' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Fecha>='$PerIni' and Fecha<='$PerFin'";

	$res=ExQuery($cons);
?>	<table cellpadding="4" width="80%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Fecha</td><td>Comprobante</td><td>Numero</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$cons2="Select Comprobante,Numero from Presupuesto.Movimiento where Comprobante='$fila[3]' and Numero='$fila[2]' 
		and Cuenta='$fila[7]' and Movimiento.Compania='$Compania[0]'";
		$res2=ExQuery($cons2);echo ExError();
		if(ExNumRows($res2)==0)
		{
			echo "<tr><td>$fila[5]</td><td>$fila[1]</td><td>$fila[6]</td><td>$fila[8]</tr>";
		}
	}
?>
</table>
<?
	echo "<hr><em>Obligaciones sin Afectacion Adecuada:</em>";
	$cons="Select Movimiento.Cuenta,Movimiento.Comprobante,DocSoporte,CompAfectado,TipoComprobant,Fecha,Numero,Cuenta,Detalle
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Obligacion presupuestal' and Estado='AC' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Fecha>='$PerIni' and Fecha<='$PerFin'";

	$res=ExQuery($cons);
?>	<table cellpadding="4" width="80%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Fecha</td><td>Comprobante</td><td>Numero</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$cons2="Select Comprobante,Numero from Presupuesto.Movimiento where Comprobante='$fila[3]' and Numero='$fila[2]' and Cuenta='$fila[7]' and Movimiento.Compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)==0)
		{
			echo "<tr><td>$fila[5]</td><td>$fila[1]</td><td>$fila[6]</td><td>$fila[8]</td></tr>";
		}
	}
?>


</table>
<?
	echo "<hr><em>Egresos sin Afectacion Adecuada:</em>";
	$cons="Select Movimiento.Cuenta,Movimiento.Comprobante,DocSoporte,CompAfectado,TipoComprobant,Fecha,Numero,Cuenta,Detalle
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Egreso presupuestal' and Estado='AC' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Fecha>='$PerIni' and Fecha<='$PerFin'";

	$res=ExQuery($cons);
?>	<table cellpadding="4" width="80%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Fecha</td><td>Comprobante</td><td>Numero</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$cons2="Select Comprobante,Numero from Presupuesto.Movimiento where Comprobante='$fila[3]' and Numero='$fila[2]' 
		and Cuenta='$fila[7]' and Movimiento.Compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)==0)
		{
			echo "<tr><td>$fila[5]</td><td>$fila[1]</td><td>$fila[6]</td><td>$fila[8]</td></tr>";
		}
	}
?>
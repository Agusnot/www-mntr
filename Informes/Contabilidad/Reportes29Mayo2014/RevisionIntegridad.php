<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");

	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";

	
	$cons="SELECT sum(Debe),sum(Haber),sum(Debe)-sum(Haber) as Total,Comprobante,Numero,Fecha 
	FROM Contabilidad.Movimiento WHERE Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]'
	group By Comprobante,Numero,Fecha
	HAVING (sum(Debe)-sum(Haber))>=0.1
	Order By Fecha";

	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Documentos descuadrados : ".ExNumRows($res)."</em>";?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Fecha</td><td>Numero</td><td>Debitos</td><td>Creditos</td><td>Diferencia</td></tr>
<?
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[3]</td><td>$fila[5]</td><td>$fila[4]</td><td align='right'>".number_format($fila[0],2)."</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[2],2)."</td></tr>";
	}
?>
</table>

<?
	$cons="Select Movimiento.Identificacion,Terceros.Identificacion,Comprobante,Numero,Fecha from Contabilidad.Movimiento
	left join Central.Terceros ON Movimiento.Identificacion=Terceros.Identificacion 
	where Terceros.Identificacion IS NULL and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Movimiento.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' Group By Movimiento.Identificacion,Terceros.Identificacion,Comprobante,Numero,Fecha Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Terceros Invalidos: ".ExNumRows($res)."</em>";?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Fecha</td><td>Numero</td><td>Identificacion</td></tr>
<?
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[2]</td><td>$fila[4]</td><td>$fila[3]</td><td>$fila[0]</td></tr>";
	}
?>

</table>


<?
	$cons="Select Movimiento.Cuenta,PlanCuentas.Cuenta,Comprobante,Numero from Contabilidad.Movimiento
	left join Contabilidad.PlanCuentas ON Movimiento.Cuenta=PlanCuentas.Cuenta 
	where PlanCuentas.Cuenta IS NULL and Movimiento.Compania='$Compania[0]' and PlanCuentas.Compania='$Compania[0]' 
	and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' Group By PlanCuentas.Cuenta,Movimiento.Cuenta,Comprobante,Numero,Fecha Order By Fecha";

	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Cuentas Inv&aacute;lidas: ".ExNumRows($res)."</em>";?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Cuenta</td></tr>
<?
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[2]</td><td>$fila[3]</td><td>$fila[0]</td></tr>";
	}
?>
</table>

<?
	$cons="Select Comprobante,Numero,Fecha,DocOrigen,NoDocOrigen,sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento where Estado='AC'
	and Movimiento.Compania='$Compania[0]'
	Group By Comprobante,Numero,Fecha,DocOrigen,NoDocOrigen";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$MovPresupuestal[$fila[3]][$fila[4]]="$fila[0]|$fila[1]|$fila[2]|$fila[5]|$fila[6]";
	}


	$cons="Select Movimiento.Comprobante,Numero,Fecha,sum(Debe),sum(Haber),Formato,Detalle from Contabilidad.Movimiento,Contabilidad.Comprobantes where 
	Movimiento.Comprobante=Comprobantes.Comprobante and Estado='AC' and TipoComprobant='Cuentas x Pagar' and Detalle!='Documento Reservado' 
	and Movimiento.Compania='$Compania[0]'	and Comprobantes.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin'
	Group By Movimiento.Comprobante,Numero,Fecha,Formato,Detalle Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Cuentas x Pagar sin Afectacion Presupuestal:</em>";
?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$Archivo=$fila[5];
		if($MovPresupuestal[$fila[0]][$fila[1]]=="")
		{
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onclick="open('/Informes/Contabilidad/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>','','width=650,height=500,scrollbars=yes')">
			<?echo "$fila[1] ".substr($fila[6],0,60)."</td><td>$fila[2]</td></tr>";
		}
		else
		{
			$ValorPresupuestal=explode("|",$MovPresupuestal[$fila[0]][$fila[1]]);
			if($ValorPresupuestal[3]!=$fila[4])
			{
				echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onclick="open('/Informes/Contabilidad/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>','','width=650,height=500,scrollbars=yes')">
				<?echo "$fila[1] ".substr($fila[6],0,60)."<font color='#ff0000'> (Total: ".number_format($ValorPresupuestal[3],2)." vs ".number_format($fila[4],2).")</font></strong></td><td>$fila[2]</td></tr>";
			}
		}
	}
	?>

</table>

<?	$cons="Select Movimiento.Comprobante,Numero,Fecha,sum(Debe),sum(Haber),Formato,Detalle from Contabilidad.Movimiento,Contabilidad.Comprobantes where 
	Movimiento.Comprobante=Comprobantes.Comprobante and Estado='AC' and TipoComprobant='Egreso' and Detalle!='Documento Reservado' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin'
	 Group By Movimiento.Comprobante,Numero,Formato,Detalle,Fecha Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Egresos sin Afectacion Presupuestal:</em>";
?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$Archivo=$fila[5];
		if($MovPresupuestal[$fila[0]][$fila[1]]=="")
		{
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onclick="open('/Informes/Contabilidad/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>','','width=650,height=500,scrollbars=yes')">
			<?echo "$fila[1] ".substr($fila[6],0,60)."</td><td>$fila[2]</td></tr>";
		}
	}
	?>
</table>

<?	$cons="Select Movimiento.Comprobante,Numero,Fecha,sum(Debe),sum(Haber),Formato,Detalle from Contabilidad.Movimiento,Contabilidad.Comprobantes where 
	Movimiento.Comprobante=Comprobantes.Comprobante and Estado='AC' and TipoComprobant='Ingreso' and Detalle!='Documento Reservado' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin'
	 Group By Movimiento.Comprobante,Numero,Formato,Detalle,Fecha Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Ingresos sin Afectacion Presupuestal:</em>";
?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$Archivo=$fila[5];
		if($MovPresupuestal[$fila[0]][$fila[1]]=="")
		{
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onclick="open('/Informes/Contabilidad/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>','','width=650,height=500,scrollbars=yes')">
			<?echo "$fila[1] $fila[6]</td><td>$fila[2]</td></tr>";
		}
	}
	?>

</table>
<?	$cons="Select Movimiento.Comprobante,Numero,Fecha,sum(Debe),sum(Haber),Formato,Detalle from Contabilidad.Movimiento,Contabilidad.Comprobantes where 
	Movimiento.Comprobante=Comprobantes.Comprobante and Estado='AC' and TipoComprobant='Facturas' and Detalle!='Documento Reservado' 
	and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
	and Fecha>='$PerIni' and Fecha<='$PerFin'
	Group By Movimiento.Comprobante,Numero,Formato,Detalle,Fecha Order By Fecha";
	$res=ExQuery($cons);echo ExError();
	echo "<hr><em>Facturas sin Afectacion Presupuestal:</em>";
?>
	<table cellpadding="4" width="100%" border="1" rules="groups" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
	<tr style="font-weight:bold;text-align:center" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td></tr>
<?
	while($fila=ExFetch($res))
	{
		$Archivo=$fila[5];
		if($MovPresupuestal[$fila[0]][$fila[1]]=="")
		{
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onclick="open('/Informes/Contabilidad/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>','','width=650,height=500,scrollbars=yes')">
			<?echo "$fila[1] $fila[6]</td><td>$fila[2]</td></tr>";
		}
	}
	?>

</table>



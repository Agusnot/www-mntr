<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Presupuesto/CalcularSaldos.php");
	$PerIni="$Anio-$MesIni-$DiaIni";
	$PerFin="$Anio-$MesFin-$DiaFin";
?>

<body background="/Imgs/Fondo.jpg">

<em>Disponibilidades con saldo contrario</em>
<hr>
<table border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Valor</td></tr>
<?

	ObtieneValoresxDocxCuenta($PerIni,$PerFin,'Certificado%');
	ObtieneValoresxDoc($PerIni,$PerFin);

	$cons="Select Movimiento.Comprobante,Numero,Fecha,Archivo,Cuenta,Vigencia,ClaseVigencia from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Disponibilidad' and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC'";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Valor=CalcularSaldoxDocxCuenta($fila[4],$fila[1],$fila[0],$PerIni,$PerFin,$fila[5],$fila[6]);
//		echo "$fila[4] $fila[1] $fila[0] $Valor<br>";
		if($Valor<0)
		{
			$Archivo=$fila[3];
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">
<?			echo "$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
			$cons1="Select Movimiento.Comprobante,Numero,Fecha,sum(Credito),sum(ContraCredito),Archivo 
			from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and CompAfectado='$fila[0]' and DocSoporte='$fila[1]' 
			and Movimiento.Compania='$Compania[0]' and Estado='AC' and Cuenta='$fila[4]'
			Group By Movimiento.Comprobante,Numero,Fecha,Archivo";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{
				$Archivo=$fila1[5];
				if($fila1[3]>0){$Valor=$fila1[3];}
				if($fila1[4]>0){$Valor=$fila1[4];}
				echo "<tr><td><ul>->$fila1[0]</td>";?>
				<td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $fila1[1]?>&Comprobante=<?echo $fila1[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">				
<?				echo "$fila1[1]</td><td>$fila1[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
				}
		}
	}
?>
</table>


<br>
<em>Compromisos con saldo contrario</em>
<hr>
<table border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Valor</td></tr>
<?

	ObtieneValoresxDocxCuenta($PerIni,$PerFin,'Compromiso presupuestal');
	$cons="Select Movimiento.Comprobante,Numero,Fecha,Archivo,Cuenta,Vigencia,ClaseVigencia from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Compromiso presupuestal' and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC'";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Valor=CalcularSaldoxDocxCuenta($fila[4],$fila[1],$fila[0],$PerIni,$PerFin,$fila[5],$fila[6]);
		if($Valor<0)
		{
			$Archivo=$fila[3];
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">
<?			echo "$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
			$cons1="Select Movimiento.Comprobante,Numero,Fecha,sum(Credito),sum(ContraCredito),Archivo 
			from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and CompAfectado='$fila[0]' and DocSoporte='$fila[1]' and Estado='AC'
			and Movimiento.Compania='$Compania[0]' and Estado='AC' and Cuenta='$fila[4]'
			Group By Movimiento.Comprobante,Numero,Fecha,Archivo";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{
				$Archivo=$fila1[5];
				if($fila1[3]>0){$Valor=$fila1[3];}
				if($fila1[4]>0){$Valor=$fila1[4];}
				echo "<tr><td><ul>->$fila1[0]</td>";?>
				<td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila1[1]?>&Comprobante=<?echo $fila1[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">				
<?				echo "$fila1[1]</td><td>$fila1[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
				}
		}
	}
?>
</table>

<br>
<em>Obligaciones con saldo contrario</em>
<hr>
<table border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Valor</td></tr>
<?

	ObtieneValoresxDocxCuenta($PerIni,$PerFin,'Obligacion presupuestal');
	$cons="Select Movimiento.Comprobante,Numero,Fecha,Archivo,Cuenta,Vigencia,ClaseVigencia from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Obligacion presupuestal' and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC'";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Valor=CalcularSaldoxDocxCuenta($fila[4],$fila[1],$fila[0],$PerIni,$PerFin,$fila[5],$fila[6]);
		$Saldo=CalcularSaldoxDoc($fila[1],$fila[0],$PerIni,$PerFin,'Actual','');
		if($Saldo<0)
		{
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">
<?			echo "$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
		}
		if($Valor<0)
		{
			$Archivo=$fila[3];
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">
<?			echo "$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
			$cons1="Select Movimiento.Comprobante,Numero,Fecha,sum(Credito),sum(ContraCredito),Archivo 
			from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and CompAfectado='$fila[0]'  and Estado='AC'
			and DocSoporte='$fila[1]' and Movimiento.Compania='$Compania[0]' and Cuenta='$fila[4]'
			Group By Movimiento.Comprobante,Numero,Fecha,Archivo";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{
				$Archivo=$fila1[5];
				if($fila1[3]>0){$Valor=$fila1[3];}
				if($fila1[4]>0){$Valor=$fila1[4];}
				echo "<tr><td><ul>->$fila1[0]</td>";?>
				<td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila1[1]?>&Comprobante=<?echo $fila1[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">				
<?				echo "$fila1[1]</td><td>$fila1[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
				}
		}
	}
?>
</table>

<br>
<em>Egresos con saldo contrario</em>
<hr>
<table border="1" bordercolor="white" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="font-weight:bold" bgcolor="#e5e5e5"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Valor</td></tr>
<?

	ObtieneValoresxDocxCuenta($PerIni,$PerFin,'Egreso presupuestal');
	$cons="Select Movimiento.Comprobante,Numero,Fecha,Archivo,Cuenta,Vigencia,ClaseVigencia from Presupuesto.Movimiento,Presupuesto.Comprobantes 
	where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='Egreso presupuestal' and Fecha>='$PerIni' and Fecha<='$PerFin' and Movimiento.Compania='$Compania[0]' and Estado='AC'";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Valor=CalcularSaldoxDocxCuenta($fila[4],$fila[1],$fila[0],$PerIni,$PerFin,$fila[5],$fila[6]);
		if($Valor<0)
		{
			$Archivo=$fila[3];
			echo "<tr><td>$fila[0]</td>";?><td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila[1]?>&Comprobante=<?echo $fila[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">
<?			echo "$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
			$cons1="Select Movimiento.Comprobante,Numero,Fecha,sum(Credito),sum(ContraCredito),Archivo 
			from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Comprobante=Comprobantes.Comprobante and CompAfectado='$fila[0]' and Estado='AC'
			and DocSoporte='$fila[1]' and Movimiento.Compania='$Compania[0]' and Cuenta=$fila[4]
			Group By Comprobante,Numero,Fecha";
			$res1=ExQuery($cons1);
			while($fila1=ExFetch($res1))
			{
				$Archivo=$fila1[5];
				if($fila1[3]>0){$Valor=$fila1[3];}
				if($fila1[4]>0){$Valor=$fila1[4];}
				echo "<tr><td><ul>->$fila1[0]</td>";?>
				<td style="cursor:hand;color:blue" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?Numero=<?echo $fila1[1]?>&Comprobante=<?echo $fila1[0]?>&Vigencia=Actual','','width=650,height=500,scrollbars=yes')">				
<?				echo "$fila1[1]</td><td>$fila1[2]</td><td align='right'>".number_format($Valor,2)."</td></tr>";
				}
		}
	}
?>
</table>

</body>
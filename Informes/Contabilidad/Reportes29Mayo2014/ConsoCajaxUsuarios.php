<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Corte="$Anio-$MesFin-$DiaFin";
?>
	<table width="90%" border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<? echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>CONSOLIDADO DIARIO DE CAJA X USUARIOS<br>CORTE A <?echo $Corte?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	</table>

	<table border="1" rules="groups" bordercolor="#ffffff" width="90%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?
		$consCta="Select Cuenta from Contabilidad.PlanCuentas where Cuenta like '110505%' and Compania='$Compania[0]' and Tipo='Detalle' and Anio=$Anio";
		$resCta=ExQuery($consCta);
		while($filaCta=ExFetch($resCta))
		{
			$consPrev="Select Comprobante from Contabilidad.Movimiento  where Fecha='$Corte' and Cuenta='$filaCta[0]' and Estado='AC' and Debe>0 Group By Comprobante";
			$resPrev=ExQuery($consPrev);
			while($filaPrev=ExFetch($resPrev))
			{
				echo "<tr><td colspan=2 align='center' bgcolor='#e5e5e5'><strong>".strtoupper($filaPrev[0])."</td></tr>";
				echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>USUARIO</td><td align='center'>INGRESOS</td></tr>";
				$cons="Select sum(Debe),UsuarioCre from Contabilidad.Movimiento where Comprobante='$filaPrev[0]' and Cuenta='$filaCta[0]' 
				and (CierrexCajero='$Corte') and Estado='AC' Group By UsuarioCre";
				$res=ExQuery($cons);echo mysql_error();
				while($fila=ExFetch($res))
				{
					echo "<tr><td>$fila[1]</td><td align='right'>".number_format($fila[0],2)."</td></tr>";
					$Total=$Total+$fila[0];
				}
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";
		}
		$TotIngresos=$Total;
		$Total=0;
	
		$consPrev="Select Comprobante from Contabilidad.Movimiento  where Fecha='$Corte' and Cuenta='$filaCta[0]' and Estado='AC' and Haber>0 Group By Comprobante";
		$resPrev=ExQuery($consPrev);
		while($filaPrev=ExFetch($resPrev))
		{
			echo "<tr><td colspan=2 align='center' bgcolor='#e5e5e5'><strong>".strtoupper($filaPrev[0])."</td></tr>";
			echo "<tr style='font-weight:bold' bgcolor='#e5e5e5'><td>USUARIO</td><td align='center'>EGRESOS</td></tr>";
			$cons="Select sum(Haber),UsuarioCre from Contabilidad.Movimiento where Comprobante='$filaPrev[0]' and Cuenta='$filaCta[0]' 
			and (Fecha='$Corte') and Estado='AC' and Compania='$Compania[0]' Group By UsuarioCre";
			$res=ExQuery($cons);echo mysql_error();
			while($fila=ExFetch($res))
			{
				echo "<tr><td>$fila[1]</td><td align='right'>".number_format($fila[0],2)."</td></tr>";
				$Total=$Total+$fila[0];
			}
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='right'><td>TOTAL</td><td align='right'>".number_format($Total,2)."</td></tr>";
		}
		$TotEgresos=$Total;
	
		$consPrev="Select Cuenta,Nombre,Naturaleza from Contabilidad.PlanCuentas
		where Cuenta='$filaCta[0]' and Compania='$Compania[0]'";
		$resPrev=ExQuery($consPrev);echo mysql_error();
		$filaPrev=ExFetch($resPrev);
	
		$cons2="Select sum(Debe) from Contabilidad.Movimiento where CierrexCajero<'$Corte' and CierrexCajero IS NOT NULL 
		and Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Estado='AC'";
	
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);echo mysql_error();
		$DebitosSI=$fila2[0];
		
		$cons2="Select sum(Haber) from Contabilidad.Movimiento where Fecha<'$Corte'  and Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Estado='AC'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);echo mysql_error();
		$CreditosSI=$fila2[0];
	
		if($filaPrev[2]=="Debito"){$SaldoI=$DebitosSI-$CreditosSI;$MovSI="Debito";}
		elseif($filaPrev[2]=="Credito"){$SaldoI=$CreditosSI-$DebitosSI;$MovSI="Credito";}
		$SaldoF=$SaldoI+$TotIngresos-$TotEgresos;
?>
</table><br><br>
<center>
	<table border="1" rules="groups" bordercolor="#e5e5e5" cellpadding="5" width="20%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>SALDO INICIAL</td><td align="right"><?echo number_format($SaldoI,2)?></td></tr>
<tr><td>INGRESOS</td><td align="right"><?echo number_format($TotIngresos,2)?></td></tr>
<tr><td>EGRESOS</td><td align="right"><?echo number_format($TotEgresos,2)?></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>SALDO FINAL</td><td align="right"><?echo number_format($SaldoF,2)?></td></tr>
</table><br><br>

<?	}?>
</center>
____________________________________<br>
<?echo $usuario[0];
?>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$ND=getdate();
	include("Funciones.php");

	$cons="Select * from Central.Meses where Numero=$MesIni";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesIniLet=$fila[0];

	$cons="Select * from Central.Meses where Numero=$MesFin";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$MesFinLet=$fila[0];
	$UltDia=$fila[2];
?>
	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="15"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Traslado de Fondos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="15" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Comprobante</td><td>Numero</td><td>Fecha</td><td>Cta Banco Or</td><td>Detalle Bco<td>Banco Origen</td><td>No Cuenta Bancaria</td><td>Fte Financiacion</td><td>Valor Traslado</td><td>Cta Banco Dest</td><td>Detalle Bco</td><td>Banco Receptor</td><td>No Cuenta Bancaria</td><td>Fte Financiacion</td></tr>

<?


	$cons="Select Comprobante,Numero,Fecha,Cuenta,Debe from Contabilidad.Movimiento where Cuenta like '1110%' and Debe>0 and Compania='$Compania[0]' and Estado='AC' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		while($fila=ExFetch($res))
		{
			$cons2="Select Cuenta from Contabilidad.Movimiento where Cuenta like '1110%' and Haber>0 and Compania='$Compania[0]' and Estado='AC' and Fecha>='$Anio-$MesIni-01' and Fecha<='$Anio-$MesFin-$UltDia'
			and Comprobante='$fila[0]' and Numero='$fila[1]'";

			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{

			$cons3="Select NomBanco,NumCuenta,FteFinanciacion,Nombre from Contabilidad.PlanCuentas where Cuenta='$fila[3]' and Anio=$Anio and Compania='$Compania[0]'";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);

			$cons4="Select Codigo from Central.EntidadesBancarias where Nombre='$fila3[0]'";
			$res4=ExQuery($cons4);
			$fila4=ExFetch($res4);
			
			$cons5="Select Codigo from Contabilidad.FuentesFinanciacion where FteFinanciacion='$fila3[2]' and Compania='$Compania[0]'";
			$res5=ExQuery($cons5);
			$fila5=ExFetch($res5);

			while($fila2=ExFetch($res2))
			{
				$cons30="Select NomBanco,NumCuenta,FteFinanciacion,Nombre from Contabilidad.PlanCuentas where Cuenta='$fila2[0]' and Anio=$Anio and Compania='$Compania[0]'";
				$res30=ExQuery($cons30);
				$fila30=ExFetch($res30);
				
				$cons40="Select Codigo from Central.EntidadesBancarias where Nombre='$fila30[0]'";
				$res40=ExQuery($cons40);
				$fila40=ExFetch($res40);
				
				$cons50="Select Codigo from Contabilidad.FuentesFinanciacion where FteFinanciacion='$fila30[2]' and Compania='$Compania[0]'";
				$res50=ExQuery($cons50);
				$fila50=ExFetch($res50);
	
				echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila2[0]</td><td>$fila30[3]</td><td>$fila40[0]</td><td>$fila30[1]</td><td>$fila50[0]</td><td align='center'>".number_format($fila[4],2)."</td><td>$fila[3]</td><td>$fila4[0]</td><td>$fila3[3]</td><td>$fila3[1]</td><td>$fila5[0]</td></tr>";
			}
			}
		}
	}

?>

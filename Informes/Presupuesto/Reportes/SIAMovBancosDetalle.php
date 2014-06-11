<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Movimiento de Bancos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Cod Contable</td><td>Banco</td><td>Cod Banco<td>No. de Cuenta</td><td>Denominacion</td><td>Fte de Financiacion</td><td>Cod Fte</td><td>Mes</td><td>Saldo Ini Extr</td><td>Saldo a 1o</td><td>Ingresos</td><td>Egresos</td><td>Notas Debito</td><td>Notas Credito</td><td>Saldo a 31 x Libros</td><td>Saldo a 31  x Extractos</td><td>Saldo Concil</td></tr>
<?


	$consCta="Select Cuenta,Nombre,Tipo,Naturaleza,NomBanco,NumCuenta,Destinacion,Nombre,FteFinanciacion from Contabilidad.PlanCuentas 
	where Banco=1 and Compania='$Compania[0]' and Anio=$Anio
	Order By Cuenta";
	$resCta=ExQuery($consCta);
	while($filaCta=ExFetch($resCta))
	{

		for($i=1;$i<=$MesFin;$i++)
		{

			$cons1="Select Mes,NumDias from Central.Meses where Numero=$i";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$UltDia=$fila1[1];
			$MesLet=$fila1[0];
			

			$cons40="Select Codigo from Central.EntidadesBancarias where Nombre='$filaCta[4]'";
			$res40=ExQuery($cons40);
			$fila40=ExFetch($res40);
			
			$cons50="Select Codigo from Contabilidad.FuentesFinanciacion where FteFinanciacion='$filaCta[8]' and Compania='$Compania[0]'";
			$res50=ExQuery($cons50);
			$fila50=ExFetch($res50);

			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<'$Anio-$i-01' and Estado='AC'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Saldo1Ene=$fila[0]-$fila[1];
	
			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
			where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
			and (Comprobantes.TipoComprobant='Ingreso' Or Comprobantes.Comprobante='Consignacion bancaria')
			and Fecha>='$Anio-$i-01' and Fecha<='$Anio-$i-$UltDia' and Estado='AC'";
			echo $cons;
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$Ingresos=$fila[0];
	
			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
			where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
			and (Comprobantes.TipoComprobant='Egreso')
			and Fecha>='$Anio-$i-01' and Fecha<='$Anio-$i-$UltDia' and Estado='AC'";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$Egresos=$fila[1];
	
			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
			where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
			and (Comprobantes.TipoComprobant!='Ingreso' And Comprobantes.Comprobante!='Consignacion Bancaria')
			and Fecha>='$Anio-$i-01' and Fecha<='$Anio-$i-$UltDia' and Estado='AC'";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$MovDebe=$fila[0];
	
			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento,Contabilidad.Comprobantes 
			where Cuenta='$filaCta[0]' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' and Comprobantes.Comprobante=Movimiento.Comprobante 
			and (Comprobantes.TipoComprobant!='Egreso')
			and Fecha>='$Anio-$i-01' and Fecha<='$Anio-$i-$UltDia' and Estado='AC'";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$MovHaber=$fila[1];
	
	
			$cons="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta='$filaCta[0]' and Compania='$Compania[0]' and Fecha<='$Anio-$i-$UltDia' and Estado='AC'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$SaldoF=$fila[0]-$fila[1];
	
			$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Compania='$Compania[0]' and Cuenta='$filaCta[0]' and Anio=$Anio and Mes=$i";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$SaldoExt=$fila[0];

			if($i==1){$MesEvalua="12";$AnioEvalua=$Anio-1;}
			else{$MesEvalua=$i-1;$AnioEvalua=$Anio;}
			$cons="Select SaldoExtracto from Contabilidad.SaldosConciliacion where Compania='$Compania[0]' and Cuenta='$filaCta[0]' and Anio=$AnioEvalua and Mes=$MesEvalua";
			$res=ExQuery($cons);echo ExError();
			$fila=ExFetch($res);
			$SaldoIniExtr=$fila[0];

			$SaldoConci=$SaldoExt-$SaldoF;
			echo "<tr><td>$filaCta[0]</td><td>$filaCta[4]</td><td>$fila40[0]</td><td>$filaCta[5]</td><td>$filaCta[7]</td><td>$filaCta[8]</td><td>$fila50[0]</td><td>$MesLet</td><td align='right'>".number_format($SaldoIniExtr,2)."</td><td align='right'>".number_format($Saldo1Ene,2)."</td><td align='right'>".number_format($Ingresos,2)."</td><td align='right'>".number_format($Egresos,2)."</td><td align='right'>".number_format($MovDebe,2)."</td><td align='right'>".number_format($MovHaber,2)."</td><td align='right'>".number_format($SaldoF,2)."</td><td align='right'>".number_format($SaldoExt,2)."</td><td align='right'>".number_format($SaldoConci,2)."</td></tr>";
			$Archivo=$Archivo."$filaCta[4],$filaCta[5],$filaCta[7],$filaCta[8],$Saldo1Ene,$Ingresos,$Egresos,$MovDebe,$MovHaber,$SaldoF,$SaldoExt<br>";
		}
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f03_cdn.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td><a href='formato_200801_f03_cdn.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";

?>


<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$mes = "0".$ND[mon];}
	else{$mes = $ND[mon];}
	if($ND[mday]<10){$dia = "0".$ND[mday];}
	else{$dia = $ND[mday];}
	if(!$FechaEjecucion){$FechaEjecucion="$ND[year]-$mes-$dia";}
	$cons = "Select Id,SUM(PorcDist) from Contabilidad.ProgDiferidosXCC Where Compania='$Compania[0]' Group by Id";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$PorcDistxId[$fila[0]] = $fila[1];	
	}
	if($Iniciar)
	{
		$cons="Select Comprobante,CtaCredito,VrDiferidoMensual,Tercero,Concepto,Fecha,SaldoIni,Id from Contabilidad.ProgDiferidos
		Where Compania = '$Compania[0]' and Fecha <= '$FechaEjecucion'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($PorcDistxId[$fila[7]] >= 100)
			{
				$cons10="Select sum(VrDiferido),count(Numero) from Contabilidad.ProgDiferidosEjec where Compania='$Compania[0]' and Id=$fila[7]";
				$res10=ExQuery($cons10);
				$fila10=ExFetch($res10);
				$NoCuotasDif=$fila10[1];$VrDif=$fila10[0];
				$SaldoAct=$fila[6]-$VrDif;
				if($SaldoAct>0){
				
				/////////////AFECTACION CONTABLE /////////////////////////
				$cons9="Select Numero from Contabilidad.Movimiento where Comprobante='$fila[0]' 
				and Compania='$Compania[0]' Group By Numero Order By CAST(Numero as Integer) Desc";
				$res9=ExQuery($cons9,$conex);
				$fila9=ExFetch($res9);
				$Numero=$fila9[0]+1;
				$i=1;
				$cons1="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,Compania,UsuarioCre,FechaCre,FechaDocumento)
				values($i,'$FechaEjecucion','$fila[0]',$Numero,'$fila[3]','$fila[4]','$fila[1]','0','$fila[2]','000','000','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]')";
				$res1=ExQuery($cons1);echo ExError();
				
				$cons2="Select CC,VrxCC,CtaDebito from Contabilidad.ProgDiferidosxCC where Compania='$Compania[0]' and Id=$fila[7]";
				$res2=ExQuery($cons2);
				while($fila2=ExFetch($res2))
				{
					$i++;
					$cons3="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,Compania,UsuarioCre,FechaCre,FechaDocumento)
					values($i,'$FechaEjecucion','$fila[0]',$Numero,'$fila[3]','$fila[4]','$fila2[2]','$fila2[1]','0','$fila2[0]','000','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]')";
					$res3=ExQuery($cons3);echo ExError();
				}
				
				/////////////ACTUALIZACION DE DATOS DE AMORTIZACION /////////////////////////
				
				$cons4="Insert into Contabilidad.ProgDiferidosEjec (Compania,Concepto,FechaEjec,Comprobante,Numero,VrDiferido,Saldo,Id) values
				('$Compania[0]','$fila[4]','$FechaEjecucion','$fila[0]',$Numero,$fila[2],0,$fila[7])";
				$res4=ExQuery($cons4);echo ExError();}
				
			}
		}
		?><script language="JavaScript">alert("Proceso finalizado exitosamente");</script><?
	}
?>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="0" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:11px;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td>Fecha de Ejecuci&oacute;n</td>
<td><input type="text" name="FechaEjecucion" size="8" style="width:100px; text-align:right" value="<? echo $FechaEjecucion?>" 
     onclick="popUpCalendar(this, FORMA.FechaEjecucion, 'yyyy-mm-dd')"  readonly /></td>
<td><input type="Submit" name="ver" value="Ver"></td>
</tr>
</table>


<table border="0" cellpadding="3" style="font-family:<?echo $Estilo[8]?>;font-size:11px;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td colspan="8">Amortizaciones a generar</td></tr>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">

<td>Concepto Diferido</td><td>Tercero</td><td>Cta Cred</td><td>Total</td><td>Cuotas x Dif</td><td>Vr x Cuota</td><td>Cuotas Dif</td><td>Saldo Actual</td></tr>
<?
	$cons="Select Concepto,Tercero,CtaCredito,SaldoIni,NoCuotas,VrDiferidoMensual,Id,Fecha from Contabilidad.ProgDiferidos where Compania='$Compania[0]'
	and Fecha <= '$FechaEjecucion' Order By Id";
	$res=ExQuery($cons);
	if(ExNumRows($res) > 0){$InicEjec = 1;}
	while($fila=ExFetch($res))
	{
		$cons2="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[1]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NomTerc="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";

		$cons1="Select sum(VrDiferido),count(Numero) from Contabilidad.ProgDiferidosEjec where Compania='$Compania[0]' and Id=$fila[6]";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$NoCuotasDif=$fila1[1];$VrDif=$fila1[0];
		$SaldoAct=$fila[3]-$VrDif;
		if($SaldoAct>0){
		echo "<tr><td>$fila[0]</td><td>$fila[1] $NomTerc</td><td>$fila[2]</td>";
		echo "<td align='right'>".number_format($fila[3],2)."</td><td align='right'>$fila[4]</td><td align='right'>".number_format($fila[5],2)."</td><td align='right'>$NoCuotasDif</td><td align='right'>".number_format($SaldoAct,2)."</td>";
		}
		if($PorcDistxId[$fila[6]]>=100)
		{
			?><td><img src="/Imgs/b_check.png" title="El Concepto se puede Ejecutar"></td><?	
		}
		else
		{
			?><td><img src="/Imgs/b_drop.png" title="El Concepto NO se puede Ejecutar"></td><?	
		}
		echo "</tr>";
	}
?>
</table>
<? if($InicEjec) {?><input type="submit" name="Iniciar" value="Iniciar" onClick="return(confirm('Desea Iniciar la Ejecucion de Diferidos?'))" /><? } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");?>
	<table border=1 bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?	
	if($Comprobante){$CondAdc=" and Comprobante='$Comprobante'";}
	
	$consComp="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' $CondAdc";
	$resComp=ExQuery($consComp);
	while($filaComp=ExFetch($resComp))
	{
		$cons="Select Fecha,Numero,Cuenta,Debe,Haber,DocSoporte,UsuarioCre,Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Detalle 
		from Contabilidad.Movimiento,Central.Terceros 
		where Movimiento.Identificacion=Terceros.Identificacion and Terceros.Compania='$Compania[0]' and Comprobante='$filaComp[0]'
		and Estado='AC' and Movimiento.Compania='$Compania[0]' and Fecha>='$Anio-$MesIni-$DiaIni' and Fecha<='$Anio-$MesFin-$DiaFin' Order By Numero,Debe";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			echo "<tr><td colspan=9 bgcolor='#e5e5e5' align='center'><strong>".strtoupper($filaComp[0])."</td></tr>";
			echo "<tr><td>Fecha</td><td>Numero</td><td>Cuenta</td><td>Debitos</td><td>Creditos</td><td>Doc Soporte</td><td>Tercero</td><td>Detalle</td><td>Usuario Creador</td></tr>";
			while($fila=ExFetch($res))
			{
				if(!$NumSaltar){$NumSaltar=$fila[1];$Aux=$NumSaltar;}
				$NumSaltar=$fila[1];
				if($NumSaltar!=$Aux)
				{
					echo "<tr bgcolor='#F1EFEF' style='font-weight:bold;font-size:10px;' align='right'><td colspan=3>SUMAS</td><td>".number_format($SumDB,2)."</td><td>".number_format($SumCR,2)."</td><td colspan='4'></td></tr>";
					$Aux=$NumSaltar;
					$SumCR=0;$SumDB=0;
				}
				$SumDB=$SumDB+$fila[3];
				$SumCR=$SumCR+$fila[4];
				$TotDB=$TotDB+$fila[3];$TotCR=$TotCR+$fila[4];
				echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($fila[3],2)."</td><td align='right'>".number_format($fila[4],2)."</td><td>$fila[5]</td><td>$fila[7] - $fila[8] $fila[9] $fila[10] $fila[11]</td><td>".substr($fila[12],0,20)."</td><td>$fila[6]</td></tr>";
			}
			echo "<tr bgcolor='#F1EFEF' style='font-weight:bold;font-size:10px;' align='right'><td colspan=3>SUMAS</td><td>".number_format($SumDB,2)."</td><td>".number_format($SumCR,2)."</td><td colspan='2'></td></tr>";

		}
	}
	echo "<tr bgcolor='#F1EFEF' style='font-weight:bold;font-size:10px;' align='right'><td colspan=3>TOTAL GENERAL</td><td>".number_format($TotDB,2)."</td><td>".number_format($TotCR,2)."</td><td colspan='4'></td></tr>";
	
?>
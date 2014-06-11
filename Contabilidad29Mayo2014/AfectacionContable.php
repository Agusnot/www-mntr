<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Presupuesto/CalcularSaldos.php");
	$ND=getdate();

	$cons="Select CruzarCon,Movimiento,Cuenta,CuentaCruzar,Varios from Contabilidad.CruzarComprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]' and Varios=1";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Varios=$fila[4];

	if($Varios==1)
	{
		$CondVarios="Or Movimiento.Identificacion='99999999999-0'";
	}


	if($AfectaReconocimiento)
	{
		$cons="Select CompPresupuestoAdc,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";

		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$ComprobanteDest=$fila[0];$DocPresupuestal=$fila[1];

		$NumeroDest=$Numero;

		$cons="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Movimiento.Compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetchArray($res))
		{
			$cons1="Select AfectacionPresup from Contabilidad.PlanCuentas where Cuenta='".$fila['cuenta']."' and Anio=$Anio and Compania='$Compania[0]'";
			$res1=ExQuery($cons1);echo ExError($res1);
			$fila1=ExFetch($res1);
			$CtaAfectacion=$fila1[0];
			$Fecha=$fila['fecha'];
			if($CtaAfectacion)
			{
				$AutoId++;
				$cons1="Insert into Presupuesto.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,Compania,UsuarioCre,FechaCre,DocOrigen,NoDocOrigen,Vigencia,Anio)
				values($AutoId,'".$fila['fecha']."','".$ComprobanteDest."','".$NumeroDest."','".$fila['identificacion']."','".$fila['detalle']."','$CtaAfectacion',0,".$fila['haber'].",".$fila['numero'].",'".$fila['compania']."','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]'".",'".$fila['comprobante']."','$Numero','Actual',".substr($Fecha,0,4).")";
				$res1=ExQuery($cons1);
			}
		}
		
		$ComprobSel[0]=$NumeroDest;
		$Vig[$NumeroDest]="Actual";
		$FechaDoc[$NumeroDest]=$Fecha;
		$Registrar=1;
		$AutoId=0;
		$Anio=substr($Fecha,0,4);
		$Mes=substr($Fecha,5,2);
		$Dia=substr($Fecha,8,2);

		
		$cons="Select TipoComprobant from Presupuesto.Comprobantes where Comprobante='$ComprobanteDest'";
		$res=ExQuery($cons);echo ExError($res);
		$fila=ExFetch($res);
		$DocDestino=$fila[0];

	}
	
	
	if($Registrar)
	{
		if(!$Anio){$Anio=$ND[year];}
		if(!$Mes){$Mes=$ND[mon];}
		if(!$Dia){$Dia=$ND[mday];}
		ObtieneValoresxDocxCuenta("$Anio-01-01","$Anio-12-31",$DocDestino);

		$NUMREG=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]");
		while (list($val,$cad) = each ($ComprobSel)) 
		{
			$cons="Select Movimiento.Comprobante,Numero,Fecha,Detalle,TipoComprobant,Cuenta,sum(Credito) as Credito,sum(ContraCredito) 
			as ContraCredito,Vigencia,ClaseVigencia 
			from Presupuesto.Movimiento,Presupuesto.Comprobantes
			where Movimiento.Comprobante=Comprobantes.Comprobante and TipoComprobant='$DocDestino' and (Movimiento.Identificacion='$Tercero' $CondVarios)
			and Movimiento.Compania='$Compania[0]' and Numero='$cad'  and Estado='AC' and Fecha='$FechaDoc[$cad]' and Vigencia='$Vig[$cad]'
			and Comprobantes.Compania='$Compania[0]' 
			and ClaseVigencia='$ClaseVig[$cad]'
			Group By Movimiento.Comprobante,Numero,Fecha,Detalle,TipoComprobant,Cuenta,Vigencia,ClaseVigencia";
			$res=ExQuery($cons);
			while($fila=ExFetchArray($res))
			{	
				$AutoId++;
				$Vigencia=$fila[8];$ClaseVigencia=$fila[9];
				$Valor=CalcularSaldoxDocxCuenta($fila[5],$fila[1],$fila[0],"$fila[2]","$Anio-12-31",$Vigencia,$ClaseVigencia);
				if($fila['credito']){$Credito=$Valor;$CCredito=0;}
				if($fila['contracredito']){$CCredito=$Valor;$Credito=0;}

				if($Credito>0 || $CCredito>0)
				{
					$cons2="Insert into Presupuesto.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia)
					values('$NUMREG',$AutoId,'$DocPresupuestal','".$fila['cuenta']."','$Tercero',$Credito,$CCredito,'$cad','$Compania[0]','$Detalle','$Vigencia','$ClaseVigencia')";
					$res2=ExQuery($cons2);echo ExError($res2);
				}
				$CompAfectado=$DocDestino;
			}
		}
			?>
		<script language="JavaScript" type="text/javascript">
			opener.location.href="/Presupuesto/NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&NUMREG=<?echo $NUMREG?>&Numero=<?echo $NoDocOrigen?>&Comprobante=<?echo $DocPresupuestal?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&Identificacion=<?echo $Tercero?>&Tercero=<?echo $NombreTercero?>&Detalle=<?echo $Detalle?>&DocOrigen=<?echo $DocOrigen?>&NoDocOrigen=<?echo $NoDocOrigen?>&CompAfectado=<?echo $CompAfectado?>&ValidarSaldo=3&SaldoLim=<?echo $SaldoLim?>&Tipo=<?echo $Tipo?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>";
			window.close();
		</script>
<?	}
	$cons="Select CompDestino from  Presupuesto.CruceComprobantes where lower(CompOrigen)=lower('$DocPresupuestal')";
	$res=ExQuery($cons);echo ExError($res);
	$fila=ExFetch($res);
	$DocDestino=$fila[0];

	$cons="Select lower(Movimiento.Comprobante),Numero,Fecha,Detalle,TipoComprobant,PrimApe,SegApe,PrimNom,SegNom,sum(Credito),sum(ContraCredito),Vigencia,ClaseVigencia 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes,Central.Terceros 
	where lower(Movimiento.Comprobante)=lower(Comprobantes.Comprobante) and Terceros.Identificacion=Movimiento.Identificacion and lower(TipoComprobant)=lower('$DocDestino') 
	and Terceros.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
	and (Movimiento.Identificacion='$Tercero' $CondVarios )  and Fecha>='$Anio-01-01' and Fecha<='$Anio-$Mes-$Dia'
	and Movimiento.Compania='$Compania[0]' and Estado='AC' 
	Group By Numero,Movimiento.Comprobante,Fecha,Detalle,TipoComprobant,PrimApe,SegApe,PrimNom,SegNom,Vigencia,ClaseVigencia";


	$res=ExQuery($cons);echo ExError($res);

	ObtieneValoresxDoc("$Anio-01-01","$Anio-12-31");
?>
<title>Compuconta Software 1.1</title>
<form name="FORMA" method="post">
	<table border="1" width="100%" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<?	
	$Comp=ExFetch($res);?>
	<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center"><td colspan=6><?echo $Comp[0]?></td></tr><?$res=ExQuery($cons);?>
	<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
	<td><input type="Checkbox" onclick="Marcar()" name="Marcacion"></td>
	<td>No</td><td>Fecha</td><td>Detalle</td><td>Valor</td></tr>
<?	while($fila=ExFetch($res))
	{
		$Vigencia=$fila[11];$ClaseVigencia=$fila[12];
		$AnioDocumento=substr($fila[2],0,4);

		$TotDocumento=CalcularSaldoxDoc($fila[1],$fila[0],"$fila[2]","$Anio-12-31",$Vigencia,$ClaseVigencia);
		if($TotDocumento>0){
		echo "<tr><td><input type='Checkbox' name='ComprobSel[$i]' value='$fila[1]'></td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
		echo "<td align='right'>".number_format($TotDocumento,2)."</td>";}
		$NombreTercero="$fila[5] $fila[6] $fila[7] $fila[8]";
		$VrsPagados=0;$TotDocumento=0;
		echo "<input type='Hidden' name='FechaDoc[$fila[1]]' value='$fila[2]'>";
		echo "<input type='Hidden' name='Vig[$fila[1]]' value='$Vigencia'>";
		echo "<input type='Hidden' name='ClaseVig[$fila[1]]' value='$ClaseVigencia'>";
	}
?>
</table><br>
<input type="Hidden" name="Tercero" value="<?echo $Tercero?>">
<input type="Hidden" name="DocPresupuestal" value="<?echo $DocPresupuestal?>">
<input type="Hidden" name="NombreTercero" value="<?echo $NombreTercero?>">
<input type="Hidden" name="Detalle" value="<?echo $Detalle?>">
<input type="Hidden" name="DocDestino" value="<?echo $DocDestino?>">
<input type="Hidden" name="DocOrigen" value="<?echo $Comprobante?>">
<input type="Hidden" name="NoDocOrigen" value="<?echo $Numero?>">
<input type="Hidden" name="Tipo" value="<?echo $Tipo?>">

<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="Mes" value="<?echo $Mes?>">
<input type="Hidden" name="Dia" value="<?echo $Dia?>">

<input type="Submit" name="Registrar" value="Registrar">
</form>
<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
function ObtieneValoresxDoc($PerIni,$PerFin)
	{

		global $GralVrIniciales;
		global $GralVrAfectaciones;
		global $Compania;
		global $GralVrDisminuciones;

		if($Cuenta){$condCuenta=" and Cuenta='$Cuenta'";}
		if($DocSoporte){$condDocSoporte=" and DocSoporte='$DocSoporte'";}

		$cons="Select sum(Credito) as Credito, sum(ContraCredito) as ContraCredito,Numero,lower(Comprobante),lower(Vigencia),lower(ClaseVigencia)  
		from Presupuesto.Movimiento where 
		Movimiento.Compania='$Compania[0]' and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' 
		Group By Numero,Comprobante,Vigencia,ClaseVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Valor=0;
			if($fila[0]){$Valor=$fila[0];}
			if($fila[1]){$Valor=$fila[1];}
			$GralVrIniciales[$fila[2]][$fila[3]][$fila[4]][$fila[5]]=$Valor;
		}

		$cons="Select sum(Credito),sum(ContraCredito),DocSoporte,lower(CompAfectado),lower(TipoComprobant),Numero,lower(Vigencia),lower(ClaseVigencia) 
		from Presupuesto.Movimiento,Presupuesto.Comprobantes 
		where lower(Movimiento.Comprobante)=lower(Comprobantes.Comprobante) and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
		and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' 
		GROUP BY CompAfectado,DocSoporte,TipoComprobant,Movimiento.Comprobante,Numero,Vigencia,ClaseVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$i++;
			$GralVrAfectaciones[$fila[2]][$fila[3]][$fila[6]][$fila[7]][$i]=array($fila[4],$fila[0]+$fila[1],$fila[5]);
		}
		$cons="Select sum(Credito),sum(ContraCredito),DocSoporte,lower(CompAfectado),lower(Vigencia),lower(ClaseVigencia) from Presupuesto.Movimiento
		where Movimiento.Compania='$Compania[0]' and  Comprobante ilike 'Disminuc%'
		and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC'  
		GROUP BY DocSoporte,CompAfectado,Vigencia,ClaseVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$GralVrDisminuciones[$fila[2]][$fila[3]][$fila[4]][$fila[5]]=$fila[0]+$fila[1];
		}
	}
	function ObtieneValoresxDocxCuenta($PerIni,$PerFin,$DocPresupuestal)
	{

		global $GralVrIniciales;
		global $GralVrAfectaciones;
		global $Compania;
		global $GralVrDisminuciones;

		if($Cuenta){$condCuenta=" and Cuenta='$Cuenta'";}
		if($DocSoporte){$condDocSoporte=" and DocSoporte='$DocSoporte'";}

		$cons="Select sum(Credito) as Credito, sum(ContraCredito) as ContraCredito,Numero,lower(Comprobante),Cuenta,Vigencia,ClaseVigencia  
		from Presupuesto.Movimiento 
		where Movimiento.Compania='$Compania[0]' and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and Comprobante ilike '$DocPresupuestal'
		Group By Numero,Comprobante,Cuenta,Vigencia,ClaseVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Valor=0;
			if($fila[0]){$Valor=$fila[0];}
			if($fila[1]){$Valor=$fila[1];}
	//		echo "$fila[2] $fila[3] $fila[4] $fila[5] $fila[6] = $Valor<br>";
			$GralVrIniciales[$fila[2]][$fila[3]][$fila[4]][$fila[5]][$fila[6]]=$Valor;
		}
		

		$cons="Select sum(Credito),sum(ContraCredito),DocSoporte,lower(CompAfectado),lower(TipoComprobant),Numero,Cuenta,Vigencia,ClaseVigencia
		from Presupuesto.Movimiento,Presupuesto.Comprobantes
		where lower(Movimiento.Comprobante)=lower(Comprobantes.Comprobante) and Movimiento.Compania='$Compania[0]'
		and Comprobantes.Compania='$Compania[0]' 
		and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' and CompAfectado ilike '$DocPresupuestal'
		GROUP BY CompAfectado,DocSoporte,TipoComprobant,Cuenta,Movimiento.Comprobante,Numero,Vigencia,ClaseVigencia";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$i++;
//			echo "$fila[2] $fila[3] $fila[6] $fila[7] $fila[8] = $fila[0] + $fila[1]<br>";
			$GralVrAfectaciones[$fila[2]][$fila[3]][$fila[6]][$fila[7]][$fila[8]][$i]=array($fila[4],$fila[0]+$fila[1],$fila[5]);
		}
		
		$cons="Select sum(Credito),sum(ContraCredito),DocSoporte,lower(CompAfectado),Cuenta,Vigencia,ClaseVigencia from Presupuesto.Movimiento
		where Movimiento.Compania='$Compania[0]' and  Comprobante ilike 'Disminuc%' 
		and Fecha>='$PerIni' and Fecha<='$PerFin' and Estado='AC' GROUP BY DocSoporte,CompAfectado,Cuenta,Vigencia,ClaseVigencia";

		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$GralVrDisminuciones[$fila[2]][$fila[3]][$fila[4]][$fila[5]][$fila[6]]=$fila[0]+$fila[1];
		}

	}

	
	function CalcularSaldoxDoc($NoDocumento,$Comprobante,$PerIni,$PerFin,$Vigencia,$ClaseVigencia)
	{
		global $Compania;
		global $GralVrIniciales;
		global $GralVrAfectaciones;
		global $GralVrDisminuciones;
		$Comprobante=strtolower($Comprobante);
		$Vigencia=strtolower($Vigencia);
		$ClaseVigencia=strtolower($ClaseVigencia);

		$VrInicial=$GralVrIniciales[$NoDocumento][$Comprobante][$Vigencia][$ClaseVigencia];
		if(is_array($GralVrAfectaciones[$NoDocumento][$Comprobante][$Vigencia][$ClaseVigencia]))
		{
			while (list($val,$cad) = each ($GralVrAfectaciones[$NoDocumento][$Comprobante][$Vigencia][$ClaseVigencia])) 
			{

				$VrAfectaciones=$VrAfectaciones+$GralVrAfectaciones[$NoDocumento][$Comprobante][$Vigencia][$ClaseVigencia][$val][1];
				$NumDocAfectacion=$GralVrAfectaciones[$NoDocumento][$Comprobante][$Vigencia][$ClaseVigencia][$val][2];
				$VrDisminuciones=$VrDisminuciones+$GralVrDisminuciones[$NumDocAfectacion][$cad[0]][$Vigencia][$ClaseVigencia];
			}
		}
		$Total=$VrInicial+$VrDisminuciones-$VrAfectaciones;
	//	echo "<br><br><br><br><br>$Comprobante.$NoDocumento-->$VrInicial-->$VrAfectaciones-->$VrDisminuciones<br>";
		return $Total;
	}


	function CalcularSaldoxDocxCuenta($Cuenta,$NoDocumento,$Comprobante,$PerIni,$PerFin,$Vigencia,$ClaseVigencia)
	{
		global $Compania;
		global $GralVrIniciales;
		global $GralVrAfectaciones;
		global $GralVrDisminuciones;
		$Comprobante=strtolower($Comprobante);


		$VrInicial=$GralVrIniciales[$NoDocumento][$Comprobante][$Cuenta][$Vigencia][$ClaseVigencia];
		if(is_array($GralVrAfectaciones[$NoDocumento][$Comprobante][$Cuenta][$Vigencia][$ClaseVigencia]))
		{
			while (list($val,$cad) = each ($GralVrAfectaciones[$NoDocumento][$Comprobante][$Cuenta][$Vigencia][$ClaseVigencia])) 
			{
				$VrAfectaciones=$VrAfectaciones+$GralVrAfectaciones[$NoDocumento][$Comprobante][$Cuenta][$Vigencia][$ClaseVigencia][$val][1];
				$NumDocAfectacion=$GralVrAfectaciones[$NoDocumento][$Comprobante][$Cuenta][$Vigencia][$ClaseVigencia][$val][2];
				$VrDisminuciones=$VrDisminuciones+$GralVrDisminuciones[$NumDocAfectacion][$cad[0]][$Cuenta][$Vigencia][$ClaseVigencia];
			}
		}
		$Total=$VrInicial+$VrDisminuciones-$VrAfectaciones;
	//	echo "$Comprobante.$NoDocumento-->$Cuenta-->$VrInicial-->$VrDisminuciones-->$VrAfectaciones ==$Total<br>";
		return $Total;
	}


?>
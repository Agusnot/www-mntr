<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();

	$cons="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=$Anio Order By Nivel";
	$res=ExQuery($cons,$conex);
	$NivelMax=1;
	while($fila=ExFetchArray($res))
	{
		if(!$fila[0]){$fila[0]="-100";}
		$TotCaracteres=$TotCaracteres+$fila[0];
		$Digitos[$NivelMax]=$TotCaracteres;
		$NivelMax++;
	}

	function GeneraValores()
	{	
		global $MesIni;global $MesFin;global $Cuenta;global $Anio;
		global $Compania;global $Digitos;global $NivelMax;global $Vigencia;global $ClaseVigencia;
		
		if(!$ClaseVigencia){$Vigencia="Actual";}
		else{$Vigencia="Anteriores";}

		$cons="Select sum(Credito),sum(ContraCredito),TipoComprobant,Cuenta from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Compania='$Compania[0]' and
		Movimiento.Comprobante=Comprobantes.Comprobante and Estado='AC' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]'
		and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<=$MesFin and date_part('year',Fecha)=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' 
		Group By TipoComprobant,Cuenta ";

		$res=ExQuery($cons);echo ExError();
		while($fila=ExFetch($res))
		{
			for($Nivel=1;$Nivel<=$NivelMax;$Nivel++)
			{
				$ParteCuenta=substr($fila[3],0,$Digitos[$Nivel]);
				if($ParteAnt!=$ParteCuenta)
				{
					$Ejecucion[$fila[2]][$ParteCuenta]["Credito"]=$Ejecucion[$fila[2]][$ParteCuenta]["Credito"]+$fila[0];
					$Ejecucion[$fila[2]][$ParteCuenta]["CCredito"]=$Ejecucion[$fila[2]][$ParteCuenta]["CCredito"]+$fila[1];
					
				}
				$ParteAnt=$ParteCuenta;	
			}
		}
		return $Ejecucion;
	}

	function GeneraApropiacion()
	{
		global $Compania;
		global $MesIni;global $MesFin;global $Cuenta;global $Anio;global $Digitos;global $NivelMax;
		global $Vigencia;global $ClaseVigencia;
		if(!$ClaseVigencia){$Vigencia="Actual";}
		else{$Vigencia="Anteriores";}

		$cons2="Select Apropiacion,Cuenta from Presupuesto.PlanCuentas 
		where Compania='$Compania[0]' and Apropiacion>0 and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";

		$res2=ExQuery($cons2);echo ExError();

		while($fila2=ExFetch($res2))
		{
			for($Nivel=1;$Nivel<=$NivelMax;$Nivel++)
			{
				$ParteCuenta=substr($fila2[1],0,$Digitos[$Nivel]);
				if($ParteAnt!=$ParteCuenta)
				{
					$ApropInicial[$ParteCuenta]=$ApropInicial[$ParteCuenta]+$fila2[0];
				}
				$ParteAnt=$ParteCuenta;
			}
		}	
		return $ApropInicial;
	}
?>
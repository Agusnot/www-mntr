<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	function GeneraValor($TipoComprobante,$Lado,$TipoWhere)
	{	global $MesIni;global $MesFin;global $Cuenta;global $Anio;
		global $Compania;global $Vigencia;global $ClaseVigencia;

		if($TipoWhere==0){$CondAdc="";}
		if($TipoWhere==1){$CondAdc=" and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<=$MesFin and date_part('year',Fecha)=$Anio";}//Periodos totales
		if($TipoWhere==2){$CondAdc="and date_part('month',Fecha)>=$MesIni and date_part('month',Fecha)<$MesFin and date_part('year',Fecha)=$Anio";}//Periodos anteriores al actual
		if($TipoWhere==3){$CondAdc="and date_part('month',Fecha)=$MesFin and date_part('year',Fecha)=$Anio";}//Periodo actual

		$cons="Select sum(Credito),sum(ContraCredito),TipoComprobant from Presupuesto.Movimiento,Presupuesto.Comprobantes where Movimiento.Compania='$Compania[0]' and
		Movimiento.Comprobante=Comprobantes.Comprobante and Cuenta ilike '$Cuenta%' and TipoComprobant='$TipoComprobante' $CondAdc and Estado='AC' 
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'
		and Comprobantes.Compania='$Compania[0]'
		Group By TipoComprobant";
		$res=ExQuery($cons);echo ExError($res);
		$fila=ExFetch($res);
		if($Lado=="Ambos"){if($fila[0]){return $fila[0];}if($fila[1]){return $fila[1];}}
		if($Lado=="Credito"){return $fila[0];}
		if($Lado=="ContraCredito"){return $fila[1];}
	}
	
	function GeneraApropiacion()
	{
		global $MesIni;global $MesFin;global $Cuenta;global $Anio;
		global $Compania;global $Vigencia;global $ClaseVigencia;
		$cons2="Select sum(Apropiacion) from Presupuesto.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio and Vigencia='$Vigencia' 
		and ClaseVigencia='$ClaseVigencia'";
		$res2=ExQuery($cons2);echo ExError($res2);
		$fila2=ExFetch($res2);
		return $fila2[0];
	}
?>
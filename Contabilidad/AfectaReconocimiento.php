<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<?
	$cons="Select CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$ComprobanteDest=$fila[0];
	$NumeroDest=$Numero;
	$ImpComprobante=0;
	$cons="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Debe>0 and Movimiento.Compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetchArray($res))
	{
		$cons1="Select AfectacionPresup from Contabilidad.PlanCuentas where Cuenta='".$fila['cuenta']."' and Anio=$Anio and Compania='$Compania[0]'";
		$res1=ExQuery($cons1);echo ExError($res1);
		$fila1=ExFetch($res1);
		$CtaAfectacion=$fila1[0];
		if($CtaAfectacion)
		{
			$ImpComprobante=1;
			$AutoId++;
			$cons1="Insert into Presupuesto.Movimiento (AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,Compania,UsuarioCre,FechaCre,DocOrigen,NoDocOrigen,Vigencia,Anio)
			values($AutoId,'".$fila['fecha']."','".$ComprobanteDest."','".$NumeroDest."','".$fila['identificacion']."','".$fila['detalle']."','$CtaAfectacion',0,".$fila['debe'].",".$fila['numero'].",'".$fila['compania']."','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]'".",'".$fila['comprobante']."','$Numero','Actual',$Anio)";
			$res1=ExQuery($cons1);
		}
		else{echo "<br><br><br><center><em>La Cuenta ".$fila['cuenta']." no tiene amarre presupuestal, realice amarre para generar presupuesto</em>";}
	}
	if($ImpComprobante && !$NoImprima)
	{
?>
<script language="JavaScript">
	open("/Informes/Contabilidad/Formatos/ComprobanteFrm1.php?Comprobante=<? echo $Comprobante?>&Cuenta=&Numero=<?echo $Numero?>","","width=650,height=500,scrollbars=yes")
	window.close();
</script>
<?}?>
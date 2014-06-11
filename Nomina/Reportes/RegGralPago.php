<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$Raiz=$_SERVER['DOCUMENT_ROOT'];
	$logo=$Raiz."/Imgs/Logo.jpg";
	$consm="select mes from central.meses where numero='$Mes'";
    $resm=ExQuery($consm);
	$filam=ExFetch($resm);
	if(!$Vinculacion==""){$Vin=" and nomina.vinculacion='$Vinculacion'";}
//	echo $Vin;
//	echo $Mes." - ".$Anio."<br>"
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<table border="1" bordercolor="#ffffff" width="80%" style='font : normal normal small-caps 14px Tahoma;'>
<tr ><td colspan="3"><font style="font-weight:bold"><? echo strtoupper($Compania[0])."<br>Registro Consolidado de Pagos de Nomina<br>Mes: ".$filam[0]." - ".$Anio;?></font></td>
<td colspan="2"><img border="0" src="/Imgs/Logo.jpg" style="width:60; height:80" align="right" align="absmiddle"></td></tr>
<tr align="center" style="font-weight:bold" bgcolor="#C4C4C4"><td colspan="5">Registro General de Pagos</td></tr>
<tr align="center" style="font-weight:bold" bgcolor="#C4C4C4"><td>Concepto</td><td>Cantidad</td><td>Deducidos</td><td>Devengados</td><td>Neto</td></tr>
<?
$cons1="SELECT sum(Valor),nomina.concepto,conceptosliquidacion.detconcepto FROM nomina.nomina,nomina.conceptosliquidacion where Mes='$Mes' and Anio='$Anio' and (ClaseRegistro='AutoRegistro' or ClaseRegistro='Valor') and (nomina.Movimiento='Devengados' or nomina.Movimiento='PostDevengados') and nomina.concepto=conceptosliquidacion.concepto $Vin group by nomina.concepto,conceptosliquidacion.detconcepto order by sum(Valor) desc";
//		echo $cons1;
$res1=ExQuery($cons1);
	while($fila1 = ExFetch($res1))
	{	
		if($Fondo==1){$BG="#e5e5e5";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		if($fila1[0]>=0)
		{
			?>
			<tr <? echo "bgcolor='$BG'" ?>><td><? echo $fila1[2]?></td><td>&nbsp;</td><td align="right"><? echo $fila1[0]?></td><td>&nbsp;</td><td>&nbsp;</td></tr><?
			$Deveng=$Deveng+$fila1[0];
		}
	}
$cons2="SELECT sum(Valor),nomina.concepto,conceptosliquidacion.detconcepto FROM nomina.nomina,nomina.conceptosliquidacion where Mes='$Mes' and Anio='$Anio' and (ClaseRegistro='AutoRegistro' or ClaseRegistro='Valor') and (nomina.Movimiento='Deducidos' or nomina.Movimiento='PostDeducidos') and nomina.concepto=conceptosliquidacion.concepto $Vin group by nomina.concepto,conceptosliquidacion.detconcepto order by sum(Valor) desc";
//		echo $cons2;
$res2=ExQuery($cons2);
	while($fila2 = ExFetch($res2))
	{	
		if($Fondo==1){$BG="#e5e5e5";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		if($fila2[0]>=0)
		{
			?>
			<tr <? echo "bgcolor='$BG'" ?>><td><? echo $fila2[2];?></td><td>&nbsp;</td><td>&nbsp;</td><td align="right"><? echo $fila2[0];?></td><td>&nbsp;</td></tr><?
			$Deduc=$Deduc+$fila2[0];
		}
	}
//		echo $cons1;
?>	
<tr align="center"><td>TOTAL</td><td>&nbsp;</td><td><? echo "$ ".$Deveng;?></td><td><? echo "$ ".$Deduc;?></td><td><? $Tot=$Deveng-$Deduc; echo "$ ".$Tot;?></td></tr>
</table>
</body>
</html>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>

	<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
	<?echo $Compania[1]?><br>S.I.A. Modificaciones al Presupuesto de Egresos<br>Periodo: <?echo "$MesIni a $MesFin de $Anio"?></td></tr>
	<tr><td colspan="8" align="right">Fecha de Impresión <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo Presupuestal</td><td>Acto Administrativo</td><td>Fecha</td><td>Adición</td><td>Reducción</td>
	<td>Creditos</td><td>Contra Creditos</td></tr>
<?
	$cons="Select Movimiento.Cuenta,SIA,Detalle,Fecha,ContraCredito,Credito,TipoComprobant from Presupuesto.Movimiento,Presupuesto.Comprobantes,Presupuesto.PlanCuentas 
	where Movimiento.Comprobante=Comprobantes.Comprobante and PlanCuentas.Cuenta=Movimiento.Cuenta and Estado='AC'
	
	and PlanCuentas.Anio=$Anio 
	and Movimiento.Anio=$Anio
	
	and PlanCuentas.Compania='$Compania[0]'
	and Movimiento.Compania='$Compania[0]' 
	and Comprobantes.Compania='$Compania[0]' 
	
	and PlanCuentas.Vigencia='Actual'
	
	and Estado='AC' 
	and (TipoComprobant='Adicion' Or TipoComprobant='Reduccion' Or TipoComprobant='Traslado') and Movimiento.Cuenta like '2%'";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$fila[2]=str_replace("."," ",$fila[2]);
		$fila[2]=str_replace(","," ",$fila[2]);
		$fila[3]=str_replace("-","/",$fila[3]);
	if($fila[6]=="Adicion" || $fila[6]=="Reduccion")
	{
		$Adicion=$fila[5];$Reduccion=$fila[4];$Cred=0;$CCred=0;
	}
	elseif($fila[6]=="Traslado")
	{
		$Cred=$fila[5];$CCred=$fila[4];$Adicion=0;$Reduccion=0;
	}
		echo "<tr><td><strong>$fila[1]</strong>$fila[0]</td><td>$fila[2]</td><td>$fila[3]</td><td align='right'>".number_format($Adicion,2)."</td><td align='right'>".number_format($Reduccion,2)."</td><td align='right'>".number_format($Cred,2)."</td><td align='right'>".number_format($CCred,2)."</td></tr>";
		$Archivo=$Archivo."$fila[1]$fila[0],$fila[2],$fila[3],$Adicion,$Reduccion,$Cred,$CCred<br>";
	}
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("formato_200801_f08b_agr.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);

	echo "<tr><td colspan=3><a href='formato_200801_f08b_agr.csv'><br><strong>DESCARGAR ARCHIVO CSV</a></td></tr>";
	echo "</table>";


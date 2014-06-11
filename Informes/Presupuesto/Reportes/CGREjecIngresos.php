<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("GeneraValoresEjecucion2.php");
	$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Clase=$fila9[0];
	
	$Valores=GeneraValores();

	function Encabezados()
	{
	global $Compania;global $Anio;global $MesIniLet;global $MesFinLet;global $Trimestre;global $Clase;
?>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br><br><br>C. G. R.<br>EJECUCION DE INGRESOS<br>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>D</td><td>Codigo</td><td>Nombre</td><td>Rec</td><td>Origen Espec</td><td>Destinac</td><? if ($Clase=="Territorial"){?><td>No. Der x Cobrar</td><? }?><td>No. Reg Recaudo</td><td>Tercero</td><? if ($Clase=="Territorial"){?><td>Acto Admin</td><td>Derechos x Cobrar</td><td>Rev Derechos x Cobrar</td><? }?><td>Ingresos</td><td>Devoluciones</td><td>Dism Ingresos</td><td>Otras Ejecuciones</td><td>Rev Otras Ejecuciones</td><? if ($Clase=="Territorial"){?><td>Reconocimientos</td><? }?><td>Recaudos VA</td><td>Dism Recaudos VA</td></tr>

<?
	}
	Encabezados();
	if($Anio){
	ExQuery("Delete from Presupuesto.CGR");

	$cons="Select Cuenta,Nombre,codigocgr,recursocgr,origenreccgr,destinacioncgr,finalidadcgr,dependenciacgr,situacioncgr,vigencia,clasevigencia 
	from Presupuesto.PlanCuentas where Cuenta like '1%' and Tipo='Detalle' and codigocgr!='' and Anio=$Anio and Vigencia='Actual' Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$i++;
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}

		$Cuenta=$fila[0];

		$IngTotales=$Valores["Ingreso presupuestal"][$fila[0]]["CCredito"];
		$DismIng=$Valores["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];
		if(!$IngTotales){$IngTotales=0;}
		if(!$DismIng){$DismIng=0;}
		$cons4="Insert into Presupuesto.CGR (codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
            dependenciacgr, situacioncgr, vr1, vr2,vigencia,clasevigencia)
		values('$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]',$IngTotales,$DismIng,'Actual','')";
		$res4=ExQuery($cons4);echo ExError();
	}
	$cons="Select codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
            dependenciacgr, situacioncgr, sum(vr1), sum(vr2),vigencia,clasevigencia
	from Presupuesto.CGR Group By  codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, dependenciacgr, situacioncgr,vigencia,clasevigencia";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$cons2="Select Descripcion from Presupuesto.CodigosCGR where Codigo='$fila[0]' and Clase='$Clase'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NombreRubro=$fila2[0];
		$NumRec++;
		$SumIng=$SumIng+$fila[4];
		echo "<tr bgcolor='$BG'><td>D</td><td>$fila[0]</td><td>$NombreRubro</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
		if ($Clase=="Territorial"){echo "<td>N/A</td>";}
		echo "<td>N/A</td><td>000000000000000</td>";
		if ($Clase=="Territorial"){
			echo "<td>N/A</td>
			<td align='right'>0.00</td>
			<td align='right'>0.00</td>";}
		echo "<td align='right'>".number_format($fila[7],2)."</td><td  align='right'>".number_format($fila[8],2)."</td>
		<td align='right'>0.00</td>
		<td align='right'>0.00</td>
		<td align='right'>0.00</td>";
		if ($Clase=="Territorial"){echo "<td align='right'>0.00</td>";}
		echo "<td align='right'>0.00</td>
		<td align='right'>0.00</td>
		</tr>";
	}
	}
	echo "</table>";

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("CGREjecIngresos.txt", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "</table>";
	echo "<a href='CGREjecIngresos.txt'><br>Generar Archivo CSV<br></a>";

?>
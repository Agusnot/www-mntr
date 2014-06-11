<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Clase=$fila9[0];
	include("GeneraValoresEjecucion2.php");
	
	if ($Clase=="Territorial"){
	$MatVig[0]=array("Actual","");
	$MatVig[1]=array("Anteriores","CxP");
	$MatVig[2]=array("Anteriores","Reservas");}
	else
	{
		$MatVig[0]=array("Actual","");
	}



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
<?echo $Compania[1]?><br><br><br>C. G. R.<br>EJECUCION DE GASTOS<br>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>D</td><td>Codigo</td><td>Nombre</td><? if ($Clase=="Territorial"){?><td>Vig</td><td>Dep</td><? }?><td>Rec</td><td>Origen Espec</td><td>Destinac</td><td>Finalidad</td><td>Situac Fondos</td><td>No. Compromiso</td><td>No. Obligacion</td><td>No.Pago</td><td>Tercero</td><td>Compromisos Con Ant</td><td>Compromisos</td><td>Dism a Compromisos</td><? if ($Clase=="Territorial"){?><td>Obligaciones</td><td>Dism Obligaciones</td><? }?><td>Pagos</td><td>Dism Pagos</td><? if ($Clase=="Territorial"){?><td>Reservas</td><? }?><td>C x P</td><? if ($Clase=="Territorial"){?><td>Obligaciones x Ejec</td><? }?></tr>

<?
	}
	Encabezados();
	if($Anio){

	ExQuery("Delete from Presupuesto.CGR");

	foreach($MatVig as $Vig)
	{

		$Vigencia=$Vig[0];
		$ClaseVigencia=$Vig[1];
		if ($Clase!="Territorial"){$Vigencia="Actual";$ClaseVigencia="";}
		$Valores=GeneraValores();
		$cons="Select Cuenta,Nombre,codigocgr,recursocgr,origenreccgr,destinacioncgr,finalidadcgr,dependenciacgr,situacioncgr,vigencia,clasevigencia 
		from Presupuesto.PlanCuentas where Cuenta like '2%' and Tipo='Detalle' and codigocgr!='' and Anio=$Anio
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Order By Cuenta";

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
	
			$Compromisos=$Valores["Compromiso presupuestal"][$fila[0]]["Credito"];
			$DismCompromisos=$Valores["Disminucion a compromiso"][$fila[0]]["CCredito"];
	
			$Obligaciones=$Valores["Obligacion presupuestal"][$fila[0]]["Credito"];
			$DismObligaciones=$Valores["Disminucion a obligacion presupuestal"][$fila[0]]["CCredito"];
			$Egresos=$Valores["Egreso presupuestal"][$fila[0]]["Credito"];
			$DismEgresos=$Valores["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];
	
			$CompromisosSinAfecta=$Compromisos-$DismCompromisos-$Obligaciones+$DismObligaciones;
			$ObligacSinAfectar=$Obligaciones-$DismObligaciones-$Egresos+$DismEgresos;
			if(!$Compromisos){$Compromisos=0;}
			if(!$DismCompromisos){$DismCompromisos=0;}
			if(!$Obligaciones){$Obligaciones=0;}
			if(!$DismObligaciones){$DismObligaciones=0;}
			if(!$Egresos){$Egresos=0;}
			if(!$DismEgresos){$DismEgresos=0;}
			if(!$CompromisosSinAfecta){$CompromisosSinAfecta=0;}
			if(!$ObligacSinAfectar){$ObligacSinAfectar=0;}
			if(!$fila[8]){$fila[8]=0;}
			
			$cons4="Insert into Presupuesto.CGR (codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
				dependenciacgr, situacioncgr, vr1, vr2, vr3, vr4, vr5, vr6, vr7, 
				vr8,vigencia,clasevigencia)
			values('$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]',$fila[8],$Compromisos,$DismCompromisos,$Obligaciones,$DismObligaciones,$Egresos,$DismEgresos,$CompromisosSinAfecta,$ObligacSinAfectar,
			'$fila[9]','$fila[10]')";

			$res4=ExQuery($cons4);echo ExError();
		}
	}

	$cons="Select codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
            dependenciacgr, situacioncgr, sum(vr1), sum(vr2), sum(vr3), sum(vr4), sum(vr5), sum(vr6), sum(vr7), 
            sum(vr8),vigencia,clasevigencia
	from Presupuesto.CGR Group By  codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, dependenciacgr, situacioncgr,vigencia,clasevigencia
	Order By Vigencia,ClaseVigencia,CodigoCGR";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="Select Descripcion from Presupuesto.CodigosCGR where Codigo='$fila[0]' and Clase='$Clase'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NombreRubro=$fila2[0];
		
		$Vig="";
		if($fila[16]==""){$Vig=1;}
		if($fila[16]=="Reservas"){$Vig=2;}
		if($fila[16]=="CxP"){$Vig=3;}
		
		if($fila[6]==1){$Situacion="C";}else{$Situacion="S";}
		echo "<tr bgcolor='$BG'><td>D</td><td>$fila[0]</td><td>$NombreRubro</td>";
		if ($Clase=="Territorial"){echo "<td>$Vig</td>";}
		if ($Clase=="Territorial"){echo "<td>$fila[5]</td>";}
		echo "<td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$Situacion</td><td>N/A</td><td>N/A</td><td>N/A</td><td>000000000000000</td>
		<td align='right'>0.00</td>
		<td align='right'>".number_format($fila[7],2)."</td><td  align='right'>".number_format($fila[8],2)."</td>";
		if ($Clase=="Territorial"){echo "<td  align='right'>".number_format($fila[9])."</td>";}
		if ($Clase=="Territorial"){echo "<td  align='right'>".number_format($fila[10])."</td>";}
		echo "<td  align='right'>".number_format($fila[11])."</td><td  align='right'>".number_format($fila[12])."</td>";
		if ($Clase=="Territorial"){echo "<td  align='right'>".number_format($fila[13])."</td>";}
		echo "<td  align='right'>".number_format($fila[14])."</td>";
		if ($Clase=="Territorial"){echo "<td align='right'>0.00</td>";}
		echo "</tr>";
	}
	}
	echo "</table>";
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("CGREjecGastos.txt", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<a href='CGREjecGastos.txt'><br>Generar Archivo CSV<br></a>";
?>
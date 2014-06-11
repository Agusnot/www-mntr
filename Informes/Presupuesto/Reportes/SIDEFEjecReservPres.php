<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");

	session_register("DatosSIDEF");
	$DatosSIDEF="";
	$CodEntidad=$Compania[6]."00";

	include("GeneraValoresEjecucion2.php");
	
	if($Trimestre=="01"){$MesIni=1;$MesFin=3;}
	if($Trimestre=="02"){$MesIni=4;$MesFin=6;}
	if($Trimestre=="03"){$MesIni=7;$MesFin=9;}
	if($Trimestre=="04"){$MesIni=10;$MesFin=12;}
	if($Trimestre=="00"){$MesIni=1;$MesFin=12;}

	$Valores=GeneraValores();

	function Encabezados()
	{
	global $Compania;global $Anio;global $MesIniLet;global $MesFinLet;global $Trimestre;
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
<?echo $Compania[1]?><br><br><br>SIDEF<br>EJECUCION DE RESERVAS<br>
Trimestre <? echo $Trimestre;?>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>SIDEF</td><td>Rec</td><td>Sub Rec</td><td>Sit</td><td>Descripcion</td><td>Gast Compr sin Anticipos</td><td>Obligaciones Contraidas</td><td>Pagos</td><td>Reservas presupuestales</td><td>C x P</td></td>

<?
	}
	Encabezados();
	if($Anio){

	ExQuery("Delete from SIDEF");
	$Vigencia="Anteriores";$ClaseVigencia="Reservas";
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos,SIDEF,Rec,SREc,NomSIDEF,Dependencia,Situacion 
	from PlanCuentas where Cuenta like '2%' and Tipo='Detalle' and SIDEF!='' and Anio=$Anio and Vigencia='$Vigencia'
	and ClaseVigencia='$ClaseVigencia' Order By Cuenta";
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

		if($fila[8]=="on"){$Situacion="S";}else{$Situacion="C";}

		$TotCompromisos=$Valores["Compromiso Presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a compromiso"][$fila[0]]["CCredito"];
		$TotObligaciones=$Valores["Obligacion presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a obligacion presupuestal"][$fila[0]]["CCredito"];
		$Egresos=$Valores["Egreso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];

		$CompromisosSinAfecta=$TotCompromisos-$TotObligaciones;
		$ObligacSinAfectar=$TotObligaciones-$Egresos;

		$cons4="Insert into SIDEF (CodSIDEF,Recurso,SubRec,Descripcion,Cmp1,Cmp2,Cmp3,Cmp4,Cmp5)
		values('$fila[3]','$fila[4]','$fila[5]','$fila[6]','$TotCompromisos','$TotObligaciones','$Egresos','$CompromisosSinAfecta','$ObligacSinAfectar')";
		$res4=ExQuery($cons4);echo ExError();
	}
	

	$cons="Select CodSIDEF,Recurso,SubRec,Descripcion,sum(Cmp1),sum(Cmp2),sum(Cmp3),sum(Cmp4),sum(Cmp5)
	from SIDEF Group By CodSIDEF,Recurso,SubRec";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$Situacion</td><td>".substr($fila[3],0,40)."</td>
		<td align='right'>".number_format($fila[4],2)."</td><td  align='right'>".number_format($fila[5],2)."</td><td  align='right'>".number_format($fila[6])."</td><td  align='right'>".number_format($fila[7])."</td><td  align='right'>".number_format($fila[8])."</td></tr>";

		$Archivo=$Archivo."$Anio;$Trimestre;$CodEntidad;0;$fila[0];$fila[1];$fila[2];$Situacion;$IdEtra;0;0;0;0;$fila[4];$fila[5];$fila[6];$fila[7];$fila[8]<br>";

		$DatosSIDEF[$i]=array($Anio,$Trimestre,$CodEntidad,0,$fila[0],$fila[1],$fila[2],$Situacion,$IdEtra,$fila[4],$fila[5],0,0,0,0,0,$fila[6],$fila[7],$fila[8]);
		$NumRec++;
		$SumTotCompromisos=$SumTotCompromisos+$fila[4];$SumTotObligaciones=$SumTotObligaciones+$fila[5];$SumEgresos=$SumEgresos+$fila[6];
		$SumCompromisosSinAfecta=$SumCompromisosSinAfecta+$fila[7];$SumObligacSinAfectar=$SumObligacSinAfectar+$fila[8];
	}
	echo "<tr style='font-weight:bold' align='right' bgcolor='#e5e5e5'><td colspan=5>SUMAS</td><td>".number_format($SumTotCompromisos,2)."</td><td>".number_format($SumTotObligaciones,2)."</td><td>".number_format($SumEgresos,2)."</td><td>".number_format($SumCompromisosSinAfecta,2)."</td><td>".number_format($SumObligacSinAfectar,2)."</td>";
	}
	echo "</table>";

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("EjecGastos$Trimestre.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<a href='EjecGastos$Trimestre.csv'><br>Generar Archivo CSV<br></a>";

?>
<br>
<a style="border:1px solid;border-color:#e5e5e5" href="TransSIDEF.php?Trimestre=<?echo $Trimestre?>&Codigo=<?echo $CodEntidad?>&Anio=<?echo $Anio?>&Tabla=IngrEjec">
Transferir a SIDEF</a>

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

	$Apropiacion=GeneraApropiacion();
	$Valores=GeneraValores();


	if($Trimestre=="02"){$MesIni=1;$MesFin=3;}
	if($Trimestre=="03"){$MesIni=1;$MesFin=6;}
	if($Trimestre=="04"){$MesIni=1;$MesFin=9;}
	if($Trimestre=="02" || $Trimestre=="03" || $Trimestre=="04"){$ValoresAnt=GeneraValores();}

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
<?echo $Compania[1]?><br><br><br>SIDEF<br>PROGRAMACION DE GASTOS<br>
Trimestre <? echo $Trimestre;?>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>SIDEF</td><td>Rec</td><td>Sub Rec</td><td>Descripcion</td><td>Apropiacion Inicial</td><td>Adiciones</td><td>Reducciones</td><td>Creditos</td><td>Contra creditos</td><td>Apropiacion Definitiva</td><td>CDP's</td><td>Apropiacion x Afectar</td>

<?
	}
	Encabezados();
	if($Anio){

	ExQuery("Delete from SIDEF");
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos,SIDEF,Rec,SREc,NomSIDEF from PlanCuentas where Cuenta like '2%' and Tipo='Detalle' and SIDEF!='' and Anio=$Anio and Vigencia='Actual' Order By SIDEF";
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


		if($Valores["Adicion"][$fila[0]]["Credito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["Credito"];}
		elseif($Valores["Adicion"][$fila[0]]["CCredito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["CCredito"];}
		else{$Adiciones=0;}

		if($ValoresAnt["Adicion"][$fila[0]]["Credito"]){$AdicionesAnt=$ValoresAnt["Adicion"][$fila[0]]["Credito"];}
		elseif($ValoresAnt["Adicion"][$fila[0]]["CCredito"]){$AdicionesAnt=$ValoresAnt["Adicion"][$fila[0]]["CCredito"];}
		else{$AdicionesAnt=0;}
		
		
		if($Valores["Reduccion"][$fila[0]]["Credito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["Credito"];}
		elseif($Valores["Reduccion"][$fila[0]]["CCredito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["CCredito"];}
		else{$Reducciones=0;}

		if($ValoresAnt["Reduccion"][$fila[0]]["Credito"]){$ReduccionesAnt=$ValoresAnt["Reduccion"][$fila[0]]["Credito"];}
		elseif($ValoresAnt["Reduccion"][$fila[0]]["CCredito"]){$ReduccionesAnt=$ValoresAnt["Reduccion"][$fila[0]]["CCredito"];}
		else{$ReduccionesAnt=0;}

		$Creditos=$Valores["Traslado"][$fila[0]]["Credito"];
		$CreditosAnt=$ValoresAnt["Traslado"][$fila[0]]["Credito"];
		$CCreditos=$Valores["Traslado"][$fila[0]]["CCredito"];
		$CCreditosAnt=$ValoresAnt["Traslado"][$fila[0]]["CCredito"];


		$ApropIni1=$Apropiacion[$fila[0]];
		$TotDispoAnt=$ValoresAnt["Disponibilidad"][$fila[0]]["Credito"]-$ValoresAnt["Disminucion a disponibilidad"][$fila[0]]["CCredito"];
		$ApropDefAnt=$ApropIni1+$AdicionesAnt-$ReduccionesAnt+$CreditosAnt-$CCreditosAnt;

		$TotDispoAnt=$ValoresAnt["Disponibilidad"][$fila[0]]["Credito"]-$ValoresAnt["Disminucion a disponibilidad"][$fila[0]]["CCredito"];
		$SaldoDispoAnt=$ApropDefAnt-$TotDispoAnt;

		if($Trimestre=="02" || $Trimestre=="03" || $Trimestre=="04"){$ApropIni=$SaldoDispoAnt;}
		else{$ApropIni=$Apropiacion[$fila[0]];}

		$TotDispo=$Valores["Disponibilidad"][$fila[0]]["Credito"]-$Valores["Disminucion a disponibilidad"][$fila[0]]["CCredito"];

		$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;

		$SaldoDispo=$ApropDef-$TotDispo;

		$cons4="Insert into SIDEF (CodSIDEF,Recurso,SubRec,Descripcion,Cmp1,Cmp2,Cmp3,Cmp4,Cmp5,Cmp6,Cmp7,Cmp8)
		values('$fila[3]','$fila[4]','$fila[5]','$fila[6]','$ApropIni','$Adiciones','$Reducciones','$Creditos','$CCreditos','$ApropDef','$TotDispo','$SaldoDispo')";
		$res4=ExQuery($cons4);echo ExError();

	}
	$cons="Select CodSIDEF,Recurso,SubRec,Descripcion,sum(Cmp1),sum(Cmp2),sum(Cmp3),sum(Cmp4),sum(Cmp5),sum(Cmp6),sum(Cmp7),sum(Cmp8)
	from SIDEF Group By  CodSIDEF,Recurso,SubRec";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>".substr($fila[3],0,40)."</td>
		<td align='right'>".number_format($fila[4],2)."</td><td  align='right'>".number_format($fila[5],2)."</td><td  align='right'>".number_format($fila[6])."</td><td  align='right'>".number_format($fila[7])."</td><td  align='right'>".number_format($fila[8])."</td><td  align='right'>".number_format($fila[9])."</td><td  align='right'>".
		number_format($fila[10])."</td><td  align='right'>".number_format($fila[11])."</td></tr>";

		$Archivo=$Archivo."$Anio;$Trimestre;$CodEntidad;$fila[0];$fila[1];$fila[2];$fila[4];$fila[5];$fila[6];$fila[7];$fila[8];$fila[9];$fila[10];$fila[11]<br>";

		$DatosSIDEF[$i]=array($Anio,$Trimestre,$CodEntidad,$fila[0],$fila[1],$fila[2],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11]);
		$NumRec++;
		$SumApropIni=$SumApropIni+$fila[4];$SumAdiciones=$SumAdiciones+$fila[5];$SumReducciones=$SumReducciones+$fila[6];
		$SumCreditos=$SumCreditos+$fila[7];$SumCCreditos=$SumCCreditos+$fila[8];$SumApropDef=$SumApropDef+$fila[9];
		$SumCDPs=$SumCDPs+$fila[10];$SumSaldoDispo=$SumSaldoDispo+$fila[11];
	}
	echo "<tr style='font-weight:bold' align='right' bgcolor='#e5e5e5'><td colspan=4>SUMAS</td><td>".number_format($SumApropIni,2)."</td><td>".number_format($SumAdiciones,2)."</td><td>".number_format($SumReducciones,2)."</td><td>".number_format($SumCreditos,2)."</td><td>".number_format($SumCCreditos,2)."</td><td>".number_format($SumApropDef,2)."</td>
	<td>".number_format($SumCDPs,2)."</td><td>".number_format($SumSaldoDispo,2)."</td>";
	}
	echo "</table>";

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("ProgGastos$Trimestre.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<a href='ProgGastos$Trimestre.csv'><br>Generar Archivo CSV<br></a>";

?>
<br>
<a style="border:1px solid;border-color:#e5e5e5" href="TransSIDEF.php?Trimestre=<?echo $Trimestre?>&Codigo=<?echo $CodEntidad?>&Anio=<?echo $Anio?>&Tabla=IngrEjec">
Transferir a SIDEF</a>

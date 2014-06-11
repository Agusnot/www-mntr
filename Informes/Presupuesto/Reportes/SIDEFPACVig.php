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
<?echo $Compania[1]?><br><br><br>SIDEF<br>EJECUCION DE GASTOS<br>
Trimestre <? echo $Trimestre;?>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>SIDEF</td><td>Rec</td><td>Sub Rec</td><td>Descripcion</td><td>Pac Inicial</td><td>Adiciones</td><td>Reducciones</td><td>Pac Definitivo</td><td>Pagos</td><td>Saldo x Ejecutar</td>
<?
	}
	Encabezados();
	if($Anio){

	ExQuery("Delete from SIDEF");
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos,SIDEF,Rec,SREc,NomSIDEF,Dependencia,Situacion from PlanCuentas where Cuenta like '2%' and Tipo='Detalle' and SIDEF!='' and Anio=$Anio and Vigencia='Actual' Order By Cuenta";
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

		$cons2="Select sum(PACProgramado) from Presupuesto.PAC where Cuenta='$Cuenta' and Anio=$Anio and Mes>=$MesIni and Mes<=$MesFin";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);

		$Egresos=$Valores["Egreso presupuestal"][$fila[0]]["Credito"]-$Valores["Disminucion a egreso presupuestal"][$fila[0]]["CCredito"];
		$PACInicial=$Egresos;
		$SaldoxEjec=$PACInicial-$Egresos;

		$cons4="Insert into SIDEF (CodSIDEF,Recurso,SubRec,Descripcion,Cmp1,Cmp2,Cmp3,Cmp4,Cmp5,Cmp6)
		values('$fila[3]','$fila[4]','$fila[5]','$fila[6]','$PACInicial',0,0,'$PACInicial',$PACInicial,0)";
		$res4=ExQuery($cons4);echo ExError();

		}
		
	$cons="Select CodSIDEF,Recurso,SubRec,Descripcion,sum(Cmp1),sum(Cmp2),sum(Cmp3),sum(Cmp4),sum(Cmp5),sum(Cmp6)
	from SIDEF Group By CodSIDEF,Recurso,SubRec";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{

		echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>".substr($fila[3],0,40)."</td>
		<td align='right'>".number_format($fila[4],2)."</td><td align='right'>$fila[5]</td><td align='right'>$fila[6]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td><td align='right'>".number_format($fila[9],2)."</td>";

		$Archivo=$Archivo."$Anio;$Trimestre;$CodEntidad;0;$fila[0];$fila[1];$fila[2];$fila[4];$fila[5];$fila[6];$fila[7];$fila[8];$fila[9]<br>";
		$DatosSIDEF[$i]=array($Anio,$Trimestre,$CodEntidad,$fila[0],$fila[1],$fila[2],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9]);
		$SumPacI=$SumPacI+$fila[4];
		$NumRec++;
	}
	echo "<tr style='font-weight:bold' align='right' bgcolor='#e5e5e5'><td colspan=4>SUMAS</td><td>".number_format($SumPacI,2)."</td><td>".number_format(0,2)."</td><td>".number_format(0,2)."</td><td>".number_format($SumPacI,2)."</td><td>".number_format($SumPacI,2)."</td><td>".number_format(0,2)."</td>";
	}

?>
</table><br>
<?
	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("PACVigencia$Trimestre.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<a href='PACVigencia$Trimestre.csv'><br>Generar Archivo CSV<br></a>";
?>
<br>
<a style="border:1px solid;border-color:#e5e5e5" href="TransSIDEF.php?Trimestre=<?echo $Trimestre?>&Codigo=<?echo $CodEntidad?>&Anio=<?echo $Anio?>&Tabla=IngrEjec">
Transferir a SIDEF</a>

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
<?echo $Compania[1]?><br><br><br>SIDEF<br>EJECUCION DE INGRESOS<br>
Trimestre <? echo $Trimestre;?>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>SIDEF</td><td>Rec</td><td>Sub Rec</td><td>Descripcion</td><td>Recaudo</td>

<?
	}
	Encabezados();
	if($Anio){
	ExQuery("Delete from SIDEF");
	$cons="Select Cuenta,Nombre,length(Cuenta) as Digitos,SIDEF,Rec,SREc,NomSIDEF from PlanCuentas where Cuenta like '1%' and Tipo='Detalle' and SIDEF!='' and Anio=$Anio and Vigencia='Actual' Order By SIDEF,Rec,SRec";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo $fila[0];
		$i++;
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}

		$Cuenta=$fila[0];

		$IngTotales=$Valores["Ingreso presupuestal"][$fila[0]]["CCredito"]-$Valores["Disminucion a ingreso presupuestal"][$fila[0]]["Credito"];

		$cons4="Insert into SIDEF (CodSIDEF,Recurso,SubRec,Descripcion,Cmp1)
		values('$fila[3]','$fila[4]','$fila[5]','$fila[6]',$IngTotales)";
		$res4=ExQuery($cons4);echo ExError();

	}


	$cons="Select CodSIDEF,Recurso,SubRec,Descripcion,sum(Cmp1),sum(Cmp2),sum(Cmp3),sum(Cmp4) from SIDEF Group By  CodSIDEF,Recurso,SubRec";
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$Archivo=$Archivo."$Anio;$Trimestre;$CodEntidad;$fila[0];$fila[1];$fila[2];$IdEtra;0;$fila[4];0;0;0;0;0;0<br>";
		$DatosSIDEF[$i]=array($Anio,$Trimestre,$CodEntidad,$fila[0],$fila[1],$fila[2],$fila[3],$IdEtra,0,$fila[4],0,0,0,0,0,0);
		$NumRec++;
		$SumIng=$SumIng+$fila[4];
	}
		echo "<tr style='font-weight:bold' align='right' bgcolor='#e5e5e5'><td colspan=4>SUMAS</td><td>".number_format($SumIng,2)."</td></tr>";
	}
	echo "</table>";

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("EjecIngresos$Trimestre.csv", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "</table>";
	echo "<a href='EjecIngresos$Trimestre.csv'><br>Generar Archivo CSV<br></a>";

?>
<br>
<a style="border:1px solid;border-color:#e5e5e5" href="TransSIDEF.php?Trimestre=<?echo $Trimestre?>&Codigo=<?echo $CodEntidad?>&Anio=<?echo $Anio?>&Tabla=IngrEjec">
Transferir a SIDEF</a>

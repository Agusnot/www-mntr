<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("GeneraValoresEjecucion2.php");
	
	$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Clase=$fila9[0];
	$Apropiacion=GeneraApropiacion();

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
<?echo $Compania[1]?><br><br><br>C.G.R.<br>PROGRAMACION DE INGRESOS<br>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>D</td><td>Codigo</td><td>Nombre</td><td>Rec</td><td>Origen Espec</td><td>Destinac</td><? if ($Clase=="Territorial"){?><td>Acto Admin</td><? }?><td>Aprop Ini</td><td>Adiciones</td><td>Reducciones</td><td>Aprop Def</td></tr>

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

		$ApropIni=$Apropiacion[$fila[0]];

		if($Valores["Adicion"][$fila[0]]["Credito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["Credito"];}
		elseif($Valores["Adicion"][$fila[0]]["CCredito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["CCredito"];}
		else{$Adiciones=0;}

		if($Valores["Reduccion"][$fila[0]]["Credito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["Credito"];}
		elseif($Valores["Reduccion"][$fila[0]]["CCredito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["CCredito"];}
		else{$Reducciones=0;}

		$ApropDef=$ApropIni+$Adiciones-$Reducciones;
		if(!$ApropIni){$ApropIni=0;}
		if(!$Adiciones){$Adiciones=0;}
		if(!$Reducciones){$Reducciones=0;}
		if(!$ApropDef){$ApropDef=0;}
		$cons4="Insert into Presupuesto.CGR (codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
            dependenciacgr, situacioncgr, vr1, vr2,vr3,vr4,vigencia,clasevigencia)
		values('$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]',$ApropIni,$Adiciones,$Reducciones,$ApropDef,'Actual','')";
		$res4=ExQuery($cons4);echo ExError();
	}

	$cons="Select codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
            dependenciacgr, situacioncgr, sum(vr1), sum(vr2), sum(vr3), sum(vr4),vigencia,clasevigencia
	from Presupuesto.CGR Group By  codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, dependenciacgr, situacioncgr,vigencia,clasevigencia
	Order By Vigencia,ClaseVigencia,CodigoCGR";

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$cons2="Select Descripcion from Presupuesto.CodigosCGR where Codigo='$fila[0]' and Clase='$Clase'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NombreRubro=$fila2[0];
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="";$Fondo=1;}
		$NumRec++;
		echo "<tr bgcolor='$BG'><td>D</td><td>$fila[0]</td><td>$NombreRubro</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
		if ($Clase=="Territorial"){echo "<td>N/A</td>";}
		echo "<td align='right'>".number_format($fila[7],2)."</td><td  align='right'>".number_format($fila[8],2)."</td>
		<td align='right'>".number_format($fila[9],2)."</td><td  align='right'>".number_format($fila[10],2)."</td>
		</tr>";
	}
	}

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("CGRProgIngresos.txt", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "</table>";
	echo "<a href='CGRProgIngresos.txt'><br>Generar Archivo<br></a>";

?>
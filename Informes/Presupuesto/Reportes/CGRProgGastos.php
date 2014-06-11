<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("GeneraValoresEjecucion2.php");

	$cons9="Select clase from central.compania where Nombre='$Compania[0]'";
	$res9=ExQuery($cons9);
	$fila9=ExFetch($res9);
	$Clase=$fila9[0];

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
<?echo $Compania[1]?><br><br><br>C.G.R.<br>PROGRAMACION DE GASTOS<br>
<br>

<table  bordercolor="white" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" align="center"><td>D</td><td>Codigo</td><td>Nombre</td><? if ($Clase=="Territorial"){?><td>Vig</td><? }?><td>Rec</td><td>Origen Espec</td><td>Destinac</td><td>Finalidad</td><td>Apropiacion</td><td>Adiciones</td><td>Reducciones</td><? if ($Clase=="Territorial"){?><td>Cancelac</td><? }?><td>Creditos</td><td>Contra Creditos</td><td>Aprop Def</td><? if ($Clase=="Territorial"){?><td>CDP's</td><td>Dism CDP's</td><? }?></tr>

<?
	}
	Encabezados();
	if($Anio){

	ExQuery("Delete from Presupuesto.CGR");

	foreach($MatVig as $Vig)
	{
		$Vigencia=$Vig[0];
		$ClaseVigencia=$Vig[1];

		$Apropiacion=GeneraApropiacion();
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
	
	
			if($Valores["Adicion"][$fila[0]]["Credito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["Credito"];}
			elseif($Valores["Adicion"][$fila[0]]["CCredito"]){$Adiciones=$Valores["Adicion"][$fila[0]]["CCredito"];}
			else{$Adiciones=0;}
	
		
			
			if($Valores["Reduccion"][$fila[0]]["Credito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["Credito"];}
			elseif($Valores["Reduccion"][$fila[0]]["CCredito"]){$Reducciones=$Valores["Reduccion"][$fila[0]]["CCredito"];}
			else{$Reducciones=0;}
	
			$ApropIni=$Apropiacion[$fila[0]];
	
			$Creditos=$Valores["Traslado"][$fila[0]]["Credito"];
	
			$CCreditos=$Valores["Traslado"][$fila[0]]["CCredito"];
	
			$TotDispo=$Valores["Disponibilidad"][$fila[0]]["Credito"];
			$DismCDPS=$Valores["Disminucion a disponibilidad"][$fila[0]]["CCredito"];
	
			$ApropDef=$ApropIni+$Adiciones-$Reducciones+$Creditos-$CCreditos;
	
			$SaldoDispo=$ApropDef-$TotDispo;
			
			if(!$ApropIni){$ApropIni=0;}
			if(!$Adiciones){$Adiciones=0;}
			if(!$Reducciones){$Reducciones=0;}
			if(!$Creditos){$Creditos=0;}
			if(!$CCreditos){$CCreditos=0;}
			if(!$ApropDef){$ApropDef=0;}
			if(!$TotDispo){$TotDispo=0;}
			if(!$DismCDPS){$DismCDPS=0;}
			$cons4="Insert into Presupuesto.CGR (codigocgr, recursocgr, origenreccgr, destinacioncgr, finalidadcgr, 
				dependenciacgr, situacioncgr, vr1, vr2, vr3, vr4, vr5, vr6, vr7, 
				vr8,vigencia,clasevigencia)
			values('$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]',$ApropIni,$Adiciones,$Reducciones,$Creditos,$CCreditos,$ApropDef,$TotDispo,$DismCDPS,
			'$fila[9]','$fila[10]')";
			$res4=ExQuery($cons4);
	
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
		if($fila[16]==""){$Vig=1;}
		if($fila[16]=="Reservas"){$Vig=2;}
		if($fila[16]=="CxP"){$Vig=3;}

		$cons2="Select Descripcion from Presupuesto.CodigosCGR where Codigo='$fila[0]' and Clase='$Clase'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NombreRubro=$fila2[0];

		echo "<tr bgcolor='$BG'><td>D</td><td>$fila[0]</td><td>$NombreRubro</td>";
		if ($Clase=="Territorial"){echo "<td>$Vig</td>";}
		echo "<td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td>
		<td align='right'>".number_format($fila[7],2)."</td><td  align='right'>".number_format($fila[8],2)."</td><td  align='right'>".number_format($fila[9])."</td>";
		if ($Clase=="Territorial"){echo "<td align='right'>0.00</td>";}
		echo "<td  align='right'>".number_format($fila[10]).
		"</td><td  align='right'>".number_format($fila[11])."</td><td  align='right'>".number_format($fila[12])."</td>";
		if ($Clase=="Territorial"){echo "<td  align='right'>".number_format($fila[13])."</td><td  align='right'>".number_format($fila[14])."</td>";}
		echo "</tr>";

		$NumRec++;
	}
	}
	echo "</table>";

	$Archivo=str_replace("<br>","\r\n",$Archivo);
	$fichero = fopen("CGRProgGastos.txt", "w+") or die('Error de apertura');
	fwrite($fichero, $Archivo);	
	fclose($fichero);
	echo "<a href='CGRProgGastos.txt'><br>Generar Archivo CSV<br></a>";

?>
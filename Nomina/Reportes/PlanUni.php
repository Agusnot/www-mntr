<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
//echo $Compania[0]."//".$Compania[1]."//".$Compania[2]."//".$Compania[3]."//".$Compania[4]."//".$Compania[5]."//".$Compania[6]."//".$Compania[7]."//".$Compania[8]."//".$Compania[9]."//".$Compania[10]."//".$Compania[11]."//".$Compania[12]."//".$Compania[13]."//".$Compania[14]."//".$Compania[15]."//".$Compania[16]."//".$Compania[17]."//".$Compania[18]."//".$Compania[19]."//";
$ND=getdate();
if(!$Vinculacion==""){$Vin=" and nomina.vinculacion='$Vinculacion'";}
$cons="select mes from central.meses where numero='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
if("$ND[mday]"<10)
{
	$Dia="0$ND[mday]";
}
else
{
	$Dia="$ND[mday]";
}
$fecha="$Anio-$Mes-$Dia";
$MesR=$fila[0];
function Ajustar($Cadena,$Espacios,$Tipo)
{
	for($n=0;$n<=$Espacios;$n++){$Esp=$Esp . $Tipo;}
	if($Tipo==" "){echo strlen($Cadena)." // ".$Espacios."<br>";return $Cadena . substr($Esp,1,$Espacios-strlen($Cadena));}
	if($Tipo=="0"){return substr($Esp,1,$Espacios-strlen($Cadena)).$Cadena;}
}
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
$Comp=Ajustar("$Compania[0]","200"," ");
$Encab=strtoupper($Comp);
//---------------encabezado de novedades
echo "<table border=1 cellspacing=3 bordercolor='#ffffff' style='font : normal normal small-caps 11px Tahoma;'>";
		echo "<tr style='font-weight:bold;'><td>Re</td><td>Cons</td><td>TI</td><td>No Ide</td><td>Co</td><td>Sc</td><td>Ex</td><td>Re</td><td>Dpto</td><td>Mpo</td><td>Ape 1</td><td>Ape 2</td><td>Nom 1</td><td>Nom 2</td><td>ING</td><td>RET</td><td>TDE</td><td>TAE</td><td>TDP</td><td>TAP</td><td>VSP</td><td>VTE</td><td>VST</td><td>SLN</td><td>IGE</td><td>LMA</td><td>VAC</td><td>AVP</td><td>VCT</td><td>IRP</td><td>AFP Ant</td><td>AFP Nue</td><td>EPS Ant</td><td>EPS Nue</td><td>CCF</td><td>Dias Pens</td><td>Dias Salud</td><td>Dias ARP</td><td>Dias CCF</td><td>SalBas</td><td>Int</td><td>IBC Pens</td><td>IBC Salud</td><td>IBC ARP</td><td>IBC CCF</td><td>% Pens</td><td>Ap Pens</td><td>Vol Pens</td><td>Cot Pens</td><td>Total Pens</td><td>Fondo</td><td>Fondo</td><td>Vr NR</td><td>% Salud</td><td>Cot Salud</td><td>UPC</td><td>Auto EG</td><td>Vr Incapac</td><td>Auto LicMat</td><td>Vr Lic</td><td>% ARP</td><td>CT</td><td>Cot ARP</td><td>% CCF</td><td>Ap CCF</td><td>% SENA</td><td>Ap SENA</td><td>% ICBF</td><td>Ap ICBF</td><td>% ESAP</td><td>Ap ESAP</td><td>% ME</td><td>Ap ME</td><td></td></tr>";
//---------------busqueda de empleados
$consnom="select terceros.tipodoc,terceros.identificacion,primape,segape,primnom,segnom from central.terceros,nomina.nomina where terceros.compania='$Compania[0]' and nomina.compania=terceros.compania and nomina.identificacion=terceros.identificacion and nomina.mes='$Mes' and nomina.anio='$Anio' group by terceros.identificacion,primape,segape,primnom,segnom,tipodoc order by primape,segape,primnom,segnom,terceros.identificacion";
$ResNom=ExQuery($consnom);
while($fila=ExFetch($ResNom))
{
	$I++;
	$RE="02";
	$Conse=Ajustar("$I","5","0");
	$Identi=$fila[0];
	$NumeIdenti=Ajustar("$fila[1]","16"," ");
	echo $NumeIdenti;
	$ConsContr="select tipovinculacion from nomina.contratos where compania='$Compania[0]' and identificacion='$fila[1]' and fecinicio<'$fecha' and (fecfin>'$fecha' or fecfin is null)";
	$ResContr=ExQuery($ConsContr);
	$filacont=Exfetch($ResContr);
	$TipoV=Ajustar("$filacont[0]","2","0");
	$SubCot="00";
	$ExtrNoObli=" ";
	$ColomExte=" ";
	$CodDep=Ajustar("$Compania[18]","2","0");
	$CodMun=Ajustar("$Compania[19]","3","0");
	$Primape=Ajustar("$fila[2]","20"," ");
	$SegApe=Ajustar("$fila[3]","30"," ");
	$PrimNom=Ajustar("$fila[4]","20"," ");
	$SegNom=Ajustar("$fila[5]","30"," ");
	//	echo $consnom;
	echo "<tr><td>$RE</td><td>$Conse</td><td>$Identi</td><td>$NumeIdenti</td><td>$TipoV</td><td>$SubCot</td><td>$ExtrNoObli</td><td>$ColomExte</td><td>$CodDep</td><td>$CodMun</td><td>$Primape</td><td>$SegApe</td><td>$PrimNom</td><td>$SegNom</td><td></td></tr>";
	$Registro=$Registro.$RE.$Conse.$Identi.$NumeIdenti.$TipoV.$SubCot.$ExtrNoObli.$ColomExte.$CodDep.$CodMun.$Primape.$SegApe.$PrimNom.$SegNom."<br>";
}
$Archivo = str_replace("<br>","\r\n",$Registro);
$Fichero = fopen("PLANILLA$Mes$Anio.TXT", "w+") or die('Error de apertura');
fwrite($Fichero, $Archivo);
fclose($Fichero);
echo "<a href='PLANILLA$Mes$Anio.TXT'><br>Archivo plano<br></a>";
?>


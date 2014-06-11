<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if(!$Vinculacion==""){$Vin=" and nomina.vinculacion='$Vinculacion'"; $VinC=" and conceptosliquidacion.tipovinculacion='$Vinculacion'";}

$cons="select mes from central.meses where numero='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesR=$fila[0];
//------------------consulta de empleados
$ConsEmple="select terceros.identificacion,primape,segape,primnom,segnom from central.terceros,nomina.contratos,nomina.nomina where terceros.compania='$Compania[0]' and
terceros.compania=contratos.compania and terceros.identificacion=contratos.identificacion and estado='Activo' 
and terceros.identificacion=nomina.identificacion $Vin group by terceros.identificacion,primape,segape,primnom,segnom order by primape,segape,primnom,segnom";
$ResEmple=ExQuery($ConsEmple);
while($filaE=ExFetch($ResEmple))
{
	$Empleado_E[$filaE[0]]=array($filaE[0],$filaE[1],$filaE[2],$filaE[3],$filaE[4]);
}
//--------------------accion
?>
<html>
<body background="/Imgs/Fondo.jpg">
<font size=3 face='Tahoma'>
<? echo strtoupper($Compania[0]);?><br></font>
<font size=2 face='Tahoma'>
<? echo $Compania[1];?><br>
Reporte Total X Empleado</font><br>
Mes: <? echo $MesR?> / <? echo $Anio?>
<center><br><br>
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 14px Tahoma;'>
<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td>IDENTIFICACION</td><td width="800px">NOMBRE</td>
<?
//------------------consulta de conceptos
$consconc="select detconcepto from nomina.conceptosliquidacion where claseconcepto != 'Dias' $VinC";
$resconc=ExQuery($consconc);
while($fila=ExFetch($resconc))
{
	$Concepto[$fila[0]]=array($fila[0]);
	?>
    <td><? echo $fila[0]?></td>
    <?
}
?> 
</tr>
<?
if($Empleado_E)
{
	foreach($Empleado_E as $Id)
	{
		if($Fondo==1){$BG="#e5e5e5";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		?>
        <tr align="center" <? echo "bgcolor='$BG'" ?> >
        <td><? echo "$Id[0]</td><td>$Id[1] $Id[2] $Id[3] $Id[4]</td>";
		if($Concepto)
		{
			foreach($Concepto as $NomC)
			{
				$ConsNom="select nomina.valor from nomina.nomina where compania='$Compania[0]' and identificacion='$Id[0]' and detconcepto='$NomC[0]' 
				and mes='$Mes' and anio='$Anio'";
//				echo $ConsNom;
				$resNom=ExQuery($ConsNom);
				$filaN=ExFetch($resNom);
				echo "<td>".number_format($filaN[0],0,'','.')."</td>";
			}
		}
		?>
        </tr>
        <?
	}
}
/*$consconc="select detconcepto from nomina.conceptosliquidacion where claseconcepto != 'Dias' $VinC";
$resconc=ExQuery($consconc);
while($fila=ExFetch($resconc))
{
	$Concepto[$fila[0]]=array ($fila[0]);
}
if($Concepto)
{
	foreach($Concepto as $Nombre)
	{
		echo $Nombre[0]."<br>";
	}
}*/

?>

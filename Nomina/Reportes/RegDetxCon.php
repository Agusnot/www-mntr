<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if(!$Vinculacion==""){$Vin=" and tipovinculacion='$Vinculacion'"; $Vinc="and vinculacion='$Vinculacion'";}
$cons="select mes from central.meses where numero='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesR=$fila[0];
$c=0;
?>
<html>
<body background="/Imgs/Fondo.jpg">
<font size=3 face='Tahoma'>
<? echo strtoupper($Compania[0]);?><br></font>
<font size=2 face='Tahoma'>
<? echo $Compania[1];?>o<br>
Registro Detallado x Concepto</font><br>
Mes: <? echo $MesR?> / <? echo $Anio?>
<center><br><br>
<table border="1" bordercolor="#ffffff" width="80%" style='font : normal normal small-caps 14px Tahoma;'>
<?
$cons="select detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]' $Vin and (claseconcepto='AutoRegistro' or claseconcepto='Valor') and (movimiento='Devengados' or movimiento='PostDevengados')";
$res=ExQuery($cons);
//echo $cons;
echo "<tr bgcolor='#EEF6F6' align='center' style='font-weight:bold'><td colspan='3'><font style='font-weight:bold'>DEVENGADOS</font></td></tr>";
while($fila=ExFetch($res))
{
	$consId="select terceros.identificacion,primape,segape,primnom,segnom from central.terceros,nomina.contratos,nomina.nomina where terceros.compania='$Compania[0]' and
terceros.compania=contratos.compania and terceros.identificacion=contratos.identificacion and estado='Activo' 
and terceros.identificacion=nomina.identificacion and detconcepto='$fila[0]' and valor!=0 $Vinc group by terceros.identificacion,primape,segape,primnom,segnom order by primape,segape,primnom,segnom";
//	echo $consId."<br><br><br>";
	$resId=ExQuery($consId);
	$ContId=ExNumRows($resId);
//	echo $ContId."<br>";
	if($ContId!=0)
	{
		?>
		<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td colspan="3"><font style="font-weight:bold"><? echo $fila[0]?></font></td></tr>
		<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td>Identificacion</td><td>Nombre</td><td>Valor</td>
		</tr>
		<?
	}
	while($filaId=ExFetch($resId))
	{
		$consNom="select valor from nomina.nomina where compania='$Compania[0]' and identificacion='$filaId[0]' and detconcepto='$fila[0]'";
		$resNom=ExQuery($consNom);
		$filaNom=ExFetch($resNom);
		if($filaNom[0]!=0)
		{
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $filaId[0];?></td><td><? echo "$filaId[1] $filaId[2] $filaId[3] $filaId[4]";?></td><td><? if($filaNom[0]!=0){echo "$ ".$filaNom[0];}else{echo "$ 0";}?></td></tr>
			<?
			$c++;
			$SumTotalDev=$SumTotalDev+$filaNom[0];
		}
		//echo $fila[0]."  -->  ".$filaId[0]."  -->  ".$filaNom[0]."<br>";
	}
}
//echo $SumTotalDev;
$cons="select detconcepto from nomina.conceptosliquidacion where compania='$Compania[0]' $Vin and (claseconcepto='AutoRegistro' or claseconcepto='Valor') and (movimiento='Deducidos' or movimiento='PostDeducidos')";
$res=ExQuery($cons);
echo "<tr bgcolor='#EEF6F6' align='center' style='font-weight:bold'><td colspan='3'><font style='font-weight:bold'>DEDUCIDOS</font></td></tr>";
while($fila=ExFetch($res))
{
	$consId="select terceros.identificacion,primape,segape,primnom,segnom from central.terceros,nomina.contratos,nomina.nomina where terceros.compania='$Compania[0]' and
terceros.compania=contratos.compania and terceros.identificacion=contratos.identificacion and estado='Activo' 
and terceros.identificacion=nomina.identificacion and detconcepto='$fila[0]' and valor!=0 $Vinc group by terceros.identificacion,primape,segape,primnom,segnom order by primape,segape,primnom,segnom";
//	echo $consId."<br><br><br>";
	$resId=ExQuery($consId);
	$ContId=ExNumRows($resId);
//	echo $ContId."<br>";
	if($ContId!=0)
	{
		?>
		<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td colspan="3"><font style="font-weight:bold"><? echo $fila[0]?></font></td></tr>
		<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td>Identificacion</td><td>Nombre</td><td>Valor</td>
		</tr>
		<?
	}
	while($filaId=ExFetch($resId))
	{
		$consNom="select valor from nomina.nomina where compania='$Compania[0]' and identificacion='$filaId[0]' and detconcepto='$fila[0]'";
		$resNom=ExQuery($consNom);
		$filaNom=ExFetch($resNom);
		if($filaNom[0]!=0)
		{
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $filaId[0];?></td><td><? echo "$filaId[1] $filaId[2] $filaId[3] $filaId[4]";?></td><td><? if($filaNom[0]!=0){echo "$ ".$filaNom[0];}else{echo "$ 0";}?></td></tr>
			<?
			$c++;
			$SumTotalDed=$SumTotalDed+$filaNom[0];
		}
		//echo $fila[0]."  -->  ".$filaId[0]."  -->  ".$filaNom[0]."<br>";
	}
}
//echo $SumTotalDed;
?>
</table>
<table>
<tr  bgcolor="#C4C4C4" align="center"><td colspan="5"><font size="+2">GRAN TOTAL</font></td></tr>
<tr bgcolor="#C4C4C4" align="center"><td>TOTAL DEVENGADOS</td><td>TOTAL DEDUCIDOS</td><td>TOTAL</td></tr>
<tr bgcolor="#C4C4C4" align="center"><td><? echo "$ ".$SumTotalDev;?></td><td><? echo "$ ".$SumTotalDed;?></td><td><? $NetoT=$SumTotalDev-$SumTotalDed; echo "$ ".$NetoT; ?></td></tr>
</table>
</center>
</body>
</html>
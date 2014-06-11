<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if(!$Vinculacion==""){$Vin=" and nomina.vinculacion='$Vinculacion'";}
$cons="select mes from central.meses where numero='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesR=$fila[0];
?>
<html>
<body background="/Imgs/Fondo.jpg">
<font size=3 face='Tahoma'>
<? echo strtoupper($Compania[0]);?><br></font>
<font size=2 face='Tahoma'>
<? echo $Compania[1];?><br>
Comprobantes por Funcionario</font><br>
Mes: <? echo $MesR?> / <? echo $Anio?>
<center><br><br>
<table border="1" bordercolor="#ffffff" width="80%" style='font : normal normal small-caps 14px Tahoma;'>
<tr bgcolor='#EEF6F6' align="center" style="font-weight:bold"><td>Identificacion</td><td>Nombre</td><td>Devengados</td><td>Deducidos</td><td>Neto</td></tr>
<?
$consId="select terceros.identificacion,primape,segape,primnom,segnom from central.terceros,nomina.contratos,nomina.nomina where terceros.compania='$Compania[0]' and
terceros.compania=contratos.compania and terceros.identificacion=contratos.identificacion and estado='Activo' 
and terceros.identificacion=nomina.identificacion $Vin group by terceros.identificacion,primape,segape,primnom,segnom";
//	echo $consId."<br><br><br>";
$resId=ExQuery($consId);
$ContId=ExNumRows($resId);
while($filaId=ExFetch($resId))
{
	if($Fondo==1){$BG="#e5e5e5";$Fondo=0;}
	else{$BG="white";$Fondo=1;}
	$cons1="select sum(valor) from nomina.nomina where identificacion='$filaId[0]' and (Movimiento='Deducidos' or Movimiento='PostDeducidos')
	and (ClaseRegistro='AutoRegistro' Or ClaseRegistro='Valor')";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$cons2="select sum(valor) from nomina.nomina where identificacion='$filaId[0]' and (Movimiento='Devengados' or Movimiento='PostDevengados')
	and (ClaseRegistro='AutoRegistro' Or ClaseRegistro='Valor')";
	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	$SumDev=$SumDev+$fila2[0];
	$SumDed=$SumDed+$fila1[0];
	?>
    <tr align="center" <? echo "bgcolor='$BG'" ?> onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $filaId[0];?></td><td><? echo $filaId[1]." ".$filaId[2]." ".$filaId[3]." ".$filaId[4];?></td><td><? echo "$ ".$fila2[0];?></td>
    <td><? echo "$ ".$fila1[0];?></td><td><? $NetoP=$fila2[0]-$fila1[0]; echo "$ ".$NetoP;?></td></tr>
    <?
}
if($SumDed>0||$SumDev>0)
{
	?>
    <tr><td><? //echo "$TotSumDev+$SumDev    #$cont<br>";?>----------------------------------------------------</td></tr>
   	<tr bgcolor="#C4C4C4"><td colspan="2">TOTALES</td><td><? echo "$ ".$SumDev;?></td><td><? echo "$ ".$SumDed;?></td><td><? $Neto=$SumDev-$SumDed; echo "$ ".$Neto; ?></td></tr>
   	<?
}
?>
</table>
</center>
</body>
</html>
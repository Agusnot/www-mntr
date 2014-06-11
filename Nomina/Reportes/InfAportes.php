<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if(!$Vinculacion==""){$Vin=" and nomina.vinculacion='$Vinculacion'";}
$cons="select mes from central.meses where numero='$Mes'";
$res=ExQuery($cons);
$fila=ExFetch($res);
$MesR=$fila[0];
$FechaIni="$Anio-$Mes-01";
$FechaFin="$Anio-$Mes-30";
//echo $FechaIni." ----  ".$FechaFin."<br>";
echo "<tr ><td>TIPO DE APORTE&nbsp;</td><td><select name='DetConcep' id='DetConcep' onChange='document.FORMA.submit()'><option></option>";
$consded="select detconcepto from nomina.conceptosliquidacion where movimiento='Deducidos' and claseconcepto='AutoRegistro'";
$resded=ExQuery($consded);
while($filaded=ExFetch($resded))
{
	if($filaded[0]==$DetConcep)
	{
		echo "<option value='$filaded[0]' selected>$filaded[0]</option>"; 
	}
	else
	{
		echo "<option value='$filaded[0]'>$filaded[0]</option>";
	}
}
echo "</select>
        </td><tr><br><br>";
?>
<html>
<body background="/Imgs/Fondo.jpg">
<font size=3 face='Tahoma'>
<? echo strtoupper($Compania[0]);?><br></font>
<font size=2 face='Tahoma'>
<? echo $Compania[1];?><br>
Informe de Aportes de <? echo $DetConcep;?></font><br>
Mes: <? echo $MesR?> / <? echo $Anio?>
<center><br><br>
<table border="1" bordercolor="#ffffff" width="80%" style='font : normal normal small-caps 14px Tahoma;'>
<?
$consul="select ";
?>
</table>
</center>
</body>
</html>
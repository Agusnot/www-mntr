<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<body background="/Imgs/Fondo.jpg">
<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br>C. G. R.
<br><br>

<table bordercolor="#e5e5e5" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td><a href="CGREjecGastos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&Encabezados=<? echo $Encabezados?>">Ejecucion de Gastos</a></td></tr>
<tr><td><a href="CGREjecIngresos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&Encabezados=<? echo $Encabezados?>">Ejecución Ingresos</a></td></tr>
<tr bgcolor="#e5e5e5"><td><a href="CGRProgGastos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&Encabezados=<? echo $Encabezados?>">Programacion de Gastos</a></td></tr>
<tr><td><a href="CGRProgIngresos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&Encabezados=<? echo $Encabezados?>">Programacion de Ingresos</a></td></tr>
<tr bgcolor="#e5e5e5"><td><a href="CGRTesoreria.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&MesIni=<? echo $MesIni?>&MesFin=<? echo $MesFin?>&Encabezados=<? echo $Encabezados?>">Prog y Ejec de Tesoreria</a></td></tr>
</table>
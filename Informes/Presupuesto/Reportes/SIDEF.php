<?
	session_start();
	include("Funciones.php");
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>

<font face="tahoma" style="font-variant:small-caps" style="font-size:11px">
<strong><?echo strtoupper($Compania[0])?></strong><br>
<?echo $Compania[1]?><br>S  I  D  E  F
<br><br>

<table bordercolor="#e5e5e5" cellspacing="0" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td><a href="SIDEFProgIngresos.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Programación Ingresos</a></td></tr>
<tr><td><a href="SIDEFEjecIngresos.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Ejecución Ingresos</a></td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIDEFProgGastos.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Programación de gastos de Vigencia</td></tr>
<tr><td><a href="SIDEFEjecGastos.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Ejecución de gastos de Vigencia</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIDEFProgReservPres.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Programación reservas presupuestales</td></tr>
<tr><td><a href="SIDEFEjecReservPres.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Ejecución reservas presupuestales</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIDEFProgCxP.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Programación cuentas por pagar</td></tr>
<tr><td><a href="SIDEFEjecCxP.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">Ejecución cuentas por pagar</td></tr>
<tr bgcolor="#e5e5e5"><td><a href="SIDEFPACVig.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">PAC Vigencia</td></tr>
<tr><td><a href="SIDEFPACReg.php?Trimestre=<?echo $Trimestre?>&Anio=<?echo $Anio?>&Encabezados=<?echo $Encabezados?>">PAC Resago</td></tr>
</table>
<?
 	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Fecha Inicio</td><td>Fecha Fin</td><td>Tarifario CUPS</td><td>Tarifario Medicamentos</td><td>Monto Contrato</td><td>Ejecucion</td></tr>
<tr><td>2009-01-01</td><td>2009-12-31</td><td>ISS 2001</td><td>Farmaprecios</td><td>$ 100.000.000.oo</td><td>$ 1,418,643.00</td></tr>
</table>
<br><br><hr>
<iframe src="ListaFacturas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" align="center" style="width:500px;">

</iframe>
</body>
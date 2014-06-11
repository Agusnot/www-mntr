<?php
include("../Funciones.php");
$ambito=$_GET['ambito'];
		$qa="ambito='$ambito'";
		if(($ambito==NULL)||($ambito=="0")){$qa="ambito is not null";}
$entidad=$_GET['entidad'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>DETALLE CENSO</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #333333;
}
-->
</style></head>
<body>
<table cellpadding="5">
  <tr>
    <td bgcolor="#CCCCCC"><strong>NO.</strong></td>
    <td bgcolor="#CCCCCC"><strong>IDENTIFICACI&Oacute;N</strong></td>
    <td bgcolor="#CCCCCC"><strong>NOMBRE PACIENTE </strong></td>
    <td bgcolor="#CCCCCC"><strong>INGRESO</strong></td>
    <td bgcolor="#CCCCCC"><strong>EGRESO</strong></td>
    <td bgcolor="#CCCCCC"><strong>CAUSA SALIDA</strong> </td>
    <td bgcolor="#CCCCCC"><strong>PABELL&Oacute;N</strong></td>
    <td bgcolor="#CCCCCC"><strong>NIT ENTIDAD </strong></td>
    <td bgcolor="#CCCCCC"><strong>ENTIDAD</strong></td>
  </tr>
  <?php
$cons="SELECT cedula, pabellon, salud.pagadorxservicios.entidad, central.terceros.primape,salud.pagadorxservicios.fechaini, salud.pagadorxservicios.fechafin
FROM salud.pacientesxpabellones
INNER JOIN salud.pagadorxservicios ON salud.pacientesxpabellones.numservicio=salud.pagadorxservicios.numservicio
INNER JOIN central.terceros ON salud.pagadorxservicios.entidad=central.terceros.identificacion
WHERE $qa
and salud.pagadorxservicios.entidad='$entidad'
GROUP BY cedula, pabellon,salud.pagadorxservicios.entidad, central.terceros.primape,salud.pagadorxservicios.fechaini, salud.pagadorxservicios.fechafin
ORDER BY primape;";
$res=ExQuery($cons);
$i=1;
while($fila=ExFetch($res)){
	$cons2="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila[0]'";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){ ?>

  <tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $fila[0]; ?></td>
    <td><?php echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3] "; ?></td>
    <td><?php echo $fila[4]; ?></td>
    <td><?php echo $fila[5]; ?></td>
    <td>&nbsp;</td>
    <td><?php echo $fila[1]; ?></td>
    <td><?php echo $fila[2]; ?></td>
    <td><?php echo $fila[3]; ?></td>
  </tr>

	<?php $i++; }
}
?>
</table>
</body>
</html>

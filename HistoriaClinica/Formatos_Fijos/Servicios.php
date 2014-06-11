<?
	session_start();
	include("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<table border="1" background="/Imgs/encabezado.jpg" width="100%" cellspacing="0">
<tr style="font-weight:bold;text-align:center;color:white"><td>Id</td><td>Tipo de Servicio</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Medico Tte</td><td>Entidad</td><td>Convenio</td><td>Tipo Usuario</td><td>Nivel</td><td>Autoriza 1</td><td>Autoriza 2</td><td>Autoriza 3</td><td>Estado</td></tr>
<?
	$Nuevo="Si";
	$cons="Select * from Central.Servicios where Cedula='$Paciente[1]' Order By NumServicio";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr bgcolor='#ffffff'><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$fila[7]</td><td>$fila[8]</td><td>$fila[9]</td><td>$fila[10]</td><td>$fila[11]</td><td>$fila[12]</td><td>$fila[13]</td></tr>";
		if($fila[13]=="AC"){$Nuevo="No";}
	}
	
?>
</table>
<?if($Nuevo=="Si"){?><br><input onclick="location.href='AutorizarServicio.php'" type="Button" value="Autorizar Servicio"><?}?>
</body>
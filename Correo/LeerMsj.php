<?
	session_start();
	include("Funciones.php");
	if(!$usuario[0]){exit;}
	mysql_select_db("Correo");
	$ND=getdate();
	$cons="Update Mensajes set Leido='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]'
	where Id=$Id and Leido='0000-00-00' and UsuarioDest='$usuario[0]'";
	$res=mysql_query($cons);
	echo mysql_error();
?>
<html>
<head>
	<title>Correo electrónico Institucional</title>
</head>
<body background="/Imgs/Fondo.jpg">
<center>
<font style="font-size:24px">
<?
	$cons="Select UsuarioCre,Fecha,Asunto,Mensaje,UsuarioDest from Mensajes where Id=$Id";
	$res=mysql_query($cons);
	$fila=ExFetch($res);
?>
</center>
<table border="1" width="100%" background="/Imgs/encabezado.jpg" style="font-family:Tahoma; font-size:11px; font-variant:small-caps;">
<tr bgcolor="E5E5E5"><td style="color:white"><em><strong>Enviado por:</td><td><?echo $fila[0]?></td></tr>
<tr bgcolor="E5E5E5">
  <td style="color:white"><em><strong>Enviado a:</td>
  <td><?echo $fila[4]?></td>
</tr>
<tr><td style="color:white"><em><strong>Fecha y Hora</td><td><?echo $fila[1]?></td></tr>
<tr bgcolor="E5E5E5"><td style="color:white"><em><strong>Asunto</td><td><?echo $fila[2]?></td></tr>
<tr><td style="color:white"><em><strong>Mensaje</td></tr>
<tr bgcolor="E5E5E5"><td colspan="2"><?echo $fila[3]?></td></tr>
</table>
</body>
</html>

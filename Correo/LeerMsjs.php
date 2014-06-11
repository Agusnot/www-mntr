<?
	session_start();
	include("Funciones.php");
	if(!$usuario[0]){exit;}
	mysql_select_db("Correo");
?>
<html>
<head>
	<title>Correo electrónico Institucional</title>
</head>
<body background="/Imgs/Fondo.jpg">
<center>
<font style="font-size:24px">
<table border="1" cellspacing="0" bordercolor="#ffffff" width="100%" style="font-family:Tahoma; font-size:11px; font-variant:small-caps;">
<tr style="font-weight:bold">
	<td background="/Imgs/encabezado.jpg" style="color:white">Fecha y Hora</td>
    <td background="/Imgs/encabezado.jpg" style="color:white">Enviado por</td>
    <td background="/Imgs/encabezado.jpg" style="color:white">Asunto</td>
    <td background="/Imgs/encabezado.jpg" style="color:white">Fecha de Lectura</td>
    <td background="/Imgs/encabezado.jpg" style="color:white">Destinatario</td></tr>
<?
	if($Tipo==1)
	{
		$cons="Select Fecha,UsuarioCre,Asunto,Id,Leido,UsuarioDest from Mensajes where Leido='0000-00-00 00:00:00' and UsuarioDest='$usuario[0]' Order By Fecha Desc";
	}
	if($Tipo==2)
	{
		$cons="Select Fecha,UsuarioCre,Asunto,Id,Leido,UsuarioDest from Mensajes where Leido!='0000-00-00 00:00:00' and UsuarioDest='$usuario[0]' Order By Fecha Desc";
	}
	if($Tipo==3)
	{
		$cons="Select Fecha,UsuarioCre,Asunto,Id,Leido,UsuarioDest from Mensajes where Leido='0000-00-00 00:00:00' and UsuarioCre='$usuario[0]' Order By Fecha Desc";
	}
	if($Tipo==4)
	{
		$cons="Select Fecha,UsuarioCre,Asunto,Id,Leido,UsuarioDest from Mensajes where Leido!='00000-00-00 00:00:00' and UsuarioCre='$usuario[0]' Order By Fecha Desc";
	}

	$res=mysql_query($cons);
	$MsjNoLeidos=mysql_num_rows($res);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td><a href='LeerMsj.php?Id=$fila[3]&Tipo=$Tipo'>$fila[2]</a></td><td>$fila[4]</td><td>$fila[5]</td></tr>";
	}
?>
</table>

</body>
</html>

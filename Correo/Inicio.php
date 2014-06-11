<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Correo");
	if(!$usuario[0]){exit;}
?>

<html>
<head>
	<title>Correo electrónico Institucional</title>
</head>
<body background="/Imgs/Fondo.jpg">
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<center>
<font style="font-size:24px">
Hospital San Rafael de Pasto</font><br>
<em>Sistema Administrador Hospitalario<br>
Correo Electronico Institucional</em><br>
<em>Bienvenido Señor(a) <?echo $usuario[0]?><br>
<font color="#ff0000">
Recuerde que la información contenida en los mensajes <br>puede ser monitoreada por las directivas de la Institución
</font>
<br><br>
<?
	$cons="Select * from Mensajes where Leido='0000-00-00 00:00:00' and UsuarioDest='$usuario[0]'";
	$res=mysql_query($cons);
	$MsjNoLeidos=mysql_num_rows($res);

	$cons="Select * from Mensajes where Leido!='0000-00-00 00:00:00' and UsuarioDest='$usuario[0]'";
	$res=mysql_query($cons);
	$MsjLeidos=mysql_num_rows($res);

	$cons="Select * from Mensajes where Leido='0000-00-00 00:00:00' and UsuarioCre='$usuario[0]'";
	$res=mysql_query($cons);
	$EnvNOLeidos=mysql_num_rows($res);

	$cons="Select * from Mensajes where Leido!='0000-00-00 00:00:00' and UsuarioCre='$usuario[0]'";
	$res=mysql_query($cons);
	$EnvLeidos=mysql_num_rows($res);

?>
<table border="1" bordercolor="#ffffff">
<tr bgcolor="E5E5E5"><td colspan="2"><center><strong>RESUMEN DE SU BUZON</td></tr>
<tr><td></td><td><strong>TOTAL</td></tr>
<tr bgcolor="E5E5E5"><td>Correo NO leído</td><td><center><a href="LeerMsjs.php?Tipo=1"><?echo $MsjNoLeidos?></a></td></tr>
<tr><td>Correo Leído</td><td><center><a href="LeerMsjs.php?Tipo=2"><?echo $MsjLeidos?></td></tr>
<tr bgcolor="E5E5E5"><td>Mensajes Enviados NO leidos</td><td><center><a href="LeerMsjs.php?Tipo=3"><?echo $EnvNOLeidos?></td></tr>
<tr><td>Mensajes Enviados leidos</td><td><center><a href="LeerMsjs.php?Tipo=4"><?echo $EnvLeidos?></td></tr>
<tr bgcolor="E5E5E5"><td colspan="2"><center><a href="NuevoMsj.php"><strong>Escribir Nuevo Mensaje</a></td></tr>
</table>
<input type="Button" onClick="location.href='/salud/Portada.php'" value="Cerrar"><br><br>
</center> 
</body>
</html>


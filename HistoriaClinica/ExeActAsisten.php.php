<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select especialidad from salud.medicos where compania='$Compania[0]' and usuario='$usuario[1]'";
	$res=ExQuery($cons);
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	</tr>
</table>
</form>
</body>
</html>

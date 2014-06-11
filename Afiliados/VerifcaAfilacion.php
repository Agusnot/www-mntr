<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<html>
<head>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<?
$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
mysql_select_db("BDAfiliados", $conex);
$cons="select Entidad from Afiliados where Identifiacion='$Identifiacion'";
?>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
</table>
</body>
</body>
</html>

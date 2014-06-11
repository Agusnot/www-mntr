
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener2').style.position='absolute';
		parent.document.getElementById('FrameOpener2').style.top='1px';
		parent.document.getElementById('FrameOpener2').style.left='1px';
		parent.document.getElementById('FrameOpener2').style.width='1';
		parent.document.getElementById('FrameOpener2').style.height='1';
		parent.document.getElementById('FrameOpener2').style.display='none';
	}
	function Asignar()
	{
				
		CerrarThis();
		//parente.location.href="AsigCamas.php";
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
	<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<tr><td style="font-weight:bold"><input type="radio" name="Asignar" onClick="parent.location.href='AsigCamaPaciente.php?DatNameSID=<? echo $DatNameSID?>&Idcama=<? echo $Idcama?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>'">Asignar</td></tr>
    <tr><td style="font-weight:bold"><input type="radio" name="Reasignar" onClick="parent.location.href='ReasigCamaPaciente.php?DatNameSID=<? echo $DatNameSID?>&Idcama=<? echo $Idcama?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>'">Reasignar</td></tr>
</table>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

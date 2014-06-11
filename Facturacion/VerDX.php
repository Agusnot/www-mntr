<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
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
	function CerrarThisNoGuarda()
	{
		parent.document.FORMA.Parto.checked=false;
		CerrarThis();
	}	
</script>	
</head>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Codigo.focus()">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr>    
	<td bgcolor="#e5e5e5" style="font:bold">Codigo</td>
    <td><input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxPart.location.href='BusqDxPart.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" style="width:90"></td>
    <td bgcolor="#e5e5e5" style="font:bold">Nombre</td>
    <td><input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxPart.location.href='BusqDxPart.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" style="width:450"></td>
</tr>
</table>
</form>
<iframe id="BusqDxPart" name="BusqDxPart" frameborder="0" width="100%" height="85%" src="BusqDxPart.php?DatNameSID=<? echo $DatNameSID?>"></iframe>
</body>	
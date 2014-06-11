<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
	}
?>
<html>
<head>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
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
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">

<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
	<tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center" > 
    	<td>codigo</td><td>Nombre</td>
	</tr>
    <tr>
    	<td>
        	<input type="text" name="Codigo" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxRestric.location.href='BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" 
            style="width:90">
        </td>
        <td width="100%">
        	<input type="text" name="Nombre" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this);frames.BusqDxRestric.location.href='BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>&Codigo='+FORMA.Codigo.value+'&Nombre='+FORMA.Nombre.value;" 
            style="width:100%">
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="CodCup" value="<? echo $CodCup?>">
</form>
<iframe id="BusqProcedimientos" name="BusqDxRestric" frameborder="0" width="100%" height="85%" src="BusqDxRestric.php?DatNameSID=<? echo $DatNameSID?>&CodCup=<? echo $CodCup?>"></iframe>
</body>
</html>
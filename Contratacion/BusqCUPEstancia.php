<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
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
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Codigo.focus()">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
       	<td>Codigo</td><td>Procedimiento</td>
    </tr>
    <tr>
       	<td><input type="text" name="Codigo" style="width:100" 
        onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='ListaBusqCups.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Codigo='+this.value+'&Nombre='+FORMA.Nombre.value;" o
        nKeyDown="xLetra(this)"/></td>
        <td><input type="text" name="Nombre" style="width:430"
        onkeyup="xLetra(this);frames.BusqProcedimientos.location.href='ListaBusqCups.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;"
        onFocus="frames.BusqProcedimientos.location.href='ListaBusqCups.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;" onKeyDown="xLetra(this)" /></td>
 	</tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe id="BusqProcedimientos" name="BusqProcedimientos" frameborder="0" width="100%" height="75%" src="ListaBusqCups.php?DatNameSID=<? echo $DatNameSID?>"></iframe>
</body>
</html>

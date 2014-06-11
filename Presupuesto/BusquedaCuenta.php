<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js">
</script>
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
<form name="FORMA" method="post">
<input type="button" name="x" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">

<table  border="0" style="font-family:<?echo $Estilo[8]?>;font-size:13;font-style:<?echo $Estilo[10]?>" align="center" width="100%">
	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Codigo</td><td>Nombre</td></tr>
   	<tr>
     	<td><input type="text" name="Codigo" style="width:100" 
        onkeyup="xLetra(this);frames.Cuentas.location.href='Cuentas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla?>&Codigo='+this.value+'&Nombre='+FORMA.Nombre.value;" onKeyDown="xLetra(this)"/></td>
        <td><input type="text" name="Nombre" style="width:430"
        onkeyup="xLetra(this);frames.Cuentas.location.href='Cuentas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;"
        onFocus="frames.Cuentas.location.href='Cuentas.php?DatNameSID=<? echo $DatNameSID?>&Tabla=<? echo $Tabla?>&Codigo='+FORMA.Codigo.value+'&Nombre='+this.value;" onKeyDown="xLetra(this)" /></td>
 	</tr>
	<tr bgcolor="#666699" style="color:white;font-weight:bold">	
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe id="Cuentas" name="Cuentas" frameborder="0" width="100%" height="75%" src="Cuentas.php"></iframe>
</body>
</html>

<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
</script>	
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Codigo.focus()">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 	
    <tr align="right">
        <td colspan="2">
            <input type="button" value=" X " onClick="CerrarThis()" title="Cerrar esta ventana">
        </td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td>Nombre</td></tr>
    <tr>
    <td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="xLetra(this);frames.ListaCUPS.location.href='ListaCUPS2.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Nombre='+Nombre.value" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td>
    <td>
    <input type="Text" name="Nombre" style="width:500px;" onKeyUp="xLetra(this);frames.ListaCUPS.location.href='ListaCUPS2.php?DatNameSID=<? echo $DatNameSID?>&Nombre='+this.value+'&Codigo='+Codigo.value"
    onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td>
    </tr>
</table>   
</form>

<iframe name="ListaCUPS" id="ListaCUPS" src="ListaCUPS2.php" name="ListaCUPS" style="height:250px;width:550px;" frameborder="0">
</body>
</html>

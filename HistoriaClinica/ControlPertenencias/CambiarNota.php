<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
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
<?
	if($Guardar)
	{
		$cons="update salud.elementoscustodia set nota='$NewNota' where cedula='$Ced' and compania='$Compania[0]' and numservicio='$NumServ' and elemento='$Elemento'";
		$res=ExQuery($cons);
		
		?><script language="javascript">
			CerrarThis();
			parent.location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $Ced?>&NumServ=<? echo $NumServ?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UnidadHosp?>';
		</script>
        <?
	}	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language='javascript' src="/Funciones.js"></script>	
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.NewNota.focus();">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
	<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Nota</td></tr>
	<tr><td><input type="text" name="NewNota" id="NewNota" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)"/></td></tr>
    <tr><td align="center"><input type="submit" value="Guardar" name="Guardar" id="Guardar"/></td></tr>
</table>
<input type="hidden" name="Ced" value="<? echo $Ced?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="Elemento" value="<? echo $Elemento?>" />
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UndHosp" value="<? echo $UndHosp?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

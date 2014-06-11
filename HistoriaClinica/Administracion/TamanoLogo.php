<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		$cons="Update HistoriaClinica.Formatos set AnchoLogo=$Ancho, AltoLogo=$Alto	where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		?><script language="javascript">		
		parent.document.getElementById('TamLogo').style.position='absolute';
		parent.document.getElementById('TamLogo').style.top='0';
		parent.document.getElementById('TamLogo').style.left='0';
		parent.document.getElementById('TamLogo').style.display='';
		parent.document.getElementById('TamLogo').style.width='0';
		parent.document.getElementById('TamLogo').style.height='0';
		parent.document.FORMA.submit();
        </script><?	
	}	
	$cons="Select AnchoLogo,AltoLogo from HIstoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
	and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);	
	if(!$Ancho){$Ancho=$fila[0];}
	if(!$Alto){$Alto=$fila[1];}
?>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Salir()
{
	parent.document.getElementById('TamLogo').style.position='absolute';
	parent.document.getElementById('TamLogo').style.top='0';
	parent.document.getElementById('TamLogo').style.left='0';
	parent.document.getElementById('TamLogo').style.display='';
	parent.document.getElementById('TamLogo').style.width='0';
	parent.document.getElementById('TamLogo').style.height='0';		
}
function Validar()
{
	if(document.FORMA.Ancho.value==""){alert("Por favor ingrese el ancho del Logo!!!");return false;}	
	if(document.FORMA.Alto.value==""){alert("Por favor ingrese el alto del Logo!!!");return false;}	
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Formato" value="<? echo $Formato?>" />
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>" />
<table border="1" bordercolor="#e5e5e5" align="center" style='font : normal normal small-caps 11px Tahoma;'>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td align="center" colspan="2">Tamaño Logo</td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Ancho (px)</td><td>Alto (px)</td></tr>
<tr align="center">
<td><input type="text" name="Ancho" value="<? echo $Ancho?>" onKeyDown="xNumero(this);" onKeyUp="xNumero(this)" maxlength="3" size="4" style="text-align:right" /></td>
<td><input type="text" name="Alto" value="<? echo $Alto?>" onKeyDown="xNumero(this);" onKeyUp="xNumero(this)" maxlength="3" size="4" style="text-align:right"/></td>
</tr>
</table>
<center>
<input type="submit" name="Guardar" value="Guardar" style="font-size:11px" />
<input type="button" name="Cancelar" value="Cancelar" onClick="Salir();" style="font-size:11px"  />
</center>
</form>
</body>
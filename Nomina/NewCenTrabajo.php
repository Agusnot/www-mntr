<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if($Guardar)
{
	$cons="select porcentaje from nomina.centrabajo where compania='$Compania[0]' and codigo='$Codigo'";
	$res=ExQuery($cons);					
	if(ExNumRows($res)==0)
	{			
		$cons="insert into nomina.centrabajo (compania,codigo,detalle,clase,porcentaje) values ('$Compania[0]','$Codigo','$Detalle','$Clase','$Porcentaje')";
		$res=ExQuery($cons);
		?><script language="javascript">location.href="CenTrabajos.php?DatNameSID=<? echo $DatNameSID?>";</script><?
	}
	else
	{
		?><script language="javascript">alert("El Centro de trabajo ya Existe !!!");</script><?
		?><script language="javascript">location.href="CenTrabajos.php?DatNameSID=<? echo $DatNameSID?>";</script><?		
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{
	if(document.FORMA.Codigo.value==""){alert("Por favor Ingrese el Codigo !!!");return false;}
	if(document.FORMA.Detalle.value==""){alert("Por favor Ingrese el Detalle !!!");return false;}
	if(document.FORMA.Clase.value==""){alert("Por favor Ingrese la Clase de Riesgo !!!");return false;}
	if(document.FORMA.Porcentaje.value==""){alert("Por favor Ingrese el Porcentaje !!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr  bgcolor="#666699" style="color:white" align="center">
<td colspan="4">Centro de Trabajo</td>
<tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td >Codigo</td>
    <td >Detalle</td>
    <td >Clase de Riesgo</td>
    <td >Porcentaje de Riesgo</td>
</tr>
<tr>
     <td><input type="text" name="Codigo"  value="<? echo $Codigo?>" maxlength="2" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" /></td>
     <td><input type="text" name="Detalle"  value="<? echo $Detalle?>" /></td>
     <td><input type="text" name="Clase"  value="<? echo $Clase?>" /></td>
     <td><input type="text" name="Porcentaje"  value="<? echo $Porcentaje?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" /></td>          
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='CenTrabajos.php?DatNameSID=<? echo $DatNameSID?>';">
</center>
</form>
</body>
</html>
<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' order by tipovinculacion";
	$res=ExQuery($cons);
	if(!$Codigo){$Codigo=$fila[0];}
	if(!$TipVinculacion){$TipVinculacion=$fila[1];}	
	if(!$CodAnt){$CodAnt=$fila[0];}
	if($Guardar)
	{
		$cons="select codigo from nomina.tiposvinculacion where codigo='$Codigo' and compania='$Compania[0]'";
		$res=ExQuery($cons);					
		$cons1="select tipovinculacion from nomina.tiposvinculacion where tipovinculacion='$TipVinculacion' and compania='$Compania[0]'";
		$res1=ExQuery($cons1);
		if(ExNumRows($res)==0)
		{
			$cons="insert into nomina.Tiposvinculacion(tipovinculacion,codigo,compania) values('$TipVinculacion','$Codigo','$Compania[0]')";
			$res=ExQuery($cons);
			?><script language="javascript">location.href="Vinculacion.php?DatNameSID=<? echo $DatNameSID?>";</script><?
		}
		else
		{
			?><script language="javascript">alert("El Codigo del Tipo de Vinculacion que desea ingresar ya existe!!!");</script><?
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
   if(document.FORMA.Codigo.value==""){alert("Por favor ingrese el Codigo del Cargo!!!");return false;}
   if(document.FORMA.TipVinculacion.value==""){alert("Por favor ingrese el Tipo de Vinculacion!!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="2" bgcolor="#666699" style="color:white" align="center">NUEVO TIPO DE VINCULACION</td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >codigo</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >Tipo de Vinculacion</td>
</tr>
<tr>
	<td><input type="text" name="Codigo" value="<? echo $Codigo?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:70px" maxlength="4"/></td>
    <td><input type="text" name="TipVinculacion" value="<? echo $TipVinculacion?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)"/></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" ><input type="button" value="Cancelar" onClick="location.href='Vinculacion.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</form>
</body>
</html>
<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");	
//echo $TipoVinc
if($Guardar)
{
	$cons="select nit from nomina.bancos where nit='$Nit'";
	//echo $cons;
	$res1=ExQuery($cons);
	if(ExNumRows($res1)<1)
	{
		$cons="select nit from nomina.bancos where banco='$Banco'";
		//echo $cons;
		$res2=ExQuery($cons);
		if(ExNumRows($res2)<1)
		{
			$cons="insert into nomina.Bancos (compania,nit,banco) values('$Compania[0]','$Nit','$Banco')";
			$res=ExQuery($cons); 
			?><script language="javascript">location.href="Bancos.php?DatNameSID=<? echo $DatNameSID?>";</script><?
		}
		else
		{
			?><script language="javascript">alert("El Nombre del Banco que desea ingresar ya existe!!!");</script><?
		}
	}
	else
	{
		?><script language="javascript">alert("El Nit del Banco que desea ingresar ya existe!!!");</script><?		
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
   if(document.FORMA.Nit.value==""){alert("Por favor ingrese el Nit del Banco!!!");return false;}
   if(document.FORMA.Banco.value==""){alert("Por favor ingrese el Nombre del Banco!!!");return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td colspan="2" bgcolor="#666699" style="color:white" align="center"> <? echo $fila[0];?></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >NIT</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >BANCO</td>
</tr>
<tr>
	<td><input type="text" name="Nit" value="<? echo $Nit?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:70px" /></td>
    <td><input type="text" name="Banco" value="<? echo $Banco?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)"/></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" ><input type="button" value="Cancelar" onClick="location.href='Bancos.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</form>
</body>
</html>
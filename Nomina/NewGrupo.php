<?php
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");	
//echo $TipoVinc
if(!$Codigo)
{
	$conscon="select codigo from nomina.grupos where compania='$Compania[0]' order by codigo desc";
	$rescon=ExQuery($conscon);
	$filaC=ExFetch($rescon);
	$Codigo=$filaC[0]+1;
	if($Codigo>0&&$Codigo<10)
	{
		$Codigo = "000$Codigo";
	}
	elseif($Codigo>9&&$Codigo<100)
	{
		$Codigo = "00$Codigo";
	}
	elseif($Codigo>99&&$Codigo<1000)
	{
		$Codigo = "0$Codigo";
	}
}
/*$conscon="select codigo from nomina.cargos where compania='$Compania[0]' and vinculacion='$TipoVinc' order by codigo desc";
$rescon=ExQuery($conscon);
$filaC=ExFetch($rescon);
$Codigo=$filaC[0];*/
if($Guardar)
{
	$cons="select codigo from nomina.grupos where compania='$Compania[0]' and codigo='$Codigo'";
	//echo $cons;
	$res1=ExQuery($cons);
	if(ExNumRows($res1)<1)
	{
		$cons="select codigo from nomina.grupos where compania='$Compania[0]' and grupo='$Grupo'";
		//echo $cons;
		$res2=ExQuery($cons);
		if(ExNumRows($res2)<1)
		{
			$cons="insert into nomina.grupos (compania,codigo,grupo) values('$Compania[0]','$Codigo','$Grupo')";
			$res=ExQuery($cons); 
			?><script language="javascript">location.href="Grupos.php?DatNameSID=<? echo $DatNameSID?>";</script><?
		}
		else
		{
			?><script language="javascript">alert("El Nombre del Grupo que desea ingresar ya existe!!!");</script><?
		}
	}
	else
	{
		?><script language="javascript">alert("El Codigo del Grupo que desea ingresar ya existe!!!");</script><?		
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
   if(document.FORMA.Codigo.value==""){alert("Por favor ingrese el Codigo del Grupo!!!");return false;}
   if(document.FORMA.Grupo.value==""){alert("Por favor ingrese el Nombre del Grupo!!!");return false;}
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
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >CODIGO</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >GRUPO</td>
</tr>
<tr>
	<td><input type="text" name="Codigo" value="<? echo $Codigo?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" style="width:70px" maxlength="4"/></td>
    <td><input type="text" name="Grupo" value="<? echo $Grupo?>" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)"/></td>
</tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" ><input type="button" value="Cancelar" onClick="location.href='Grupos.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</form>
</body>
</html>
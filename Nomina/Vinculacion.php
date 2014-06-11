<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' order by codigo asc";
	$res=ExQuery($cons);
//	$fila=ExFetch($res)
if($Eliminar==1)
{
	$cons="delete from nomina.cargos where compania='$Compania[0]' and vinculacion='$Codigo'";
	$res=ExQuery($cons);
	$cons="delete from nomina.tiposvinculacion where compania='$Compania[0]' and codigo='$Codigo'";
	$res=ExQuery($cons);
	?><script language="javascript">location.href="Vinculacion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=0";</script><?
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
<td bgcolor="#666699" style="color:white" align="center" colspan="3">TIPOS DE VINCULACION</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">CODIGO</td>
<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">VINCULACION</td>
<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">&nbsp;</td>
</tr>
<? while ($fila = ExFetch($res))
         { ?>
		 <tr>
         <td><? echo $fila[0];?></td>
         <td><? echo $fila[1];?></td>
         <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar esta Vinculacion y todos sus Cargos?')){location.href='Vinculacion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[0]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>
         </tr>						 
       <?  } ?>
</table>
</form>
<center><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NewVinculacion.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</html>
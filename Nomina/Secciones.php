<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==1)
	{
		//echo $Banco." --> ".$Nit;
		$cons="Delete from nomina.secciones where Compania='$Compania[0]' and codigo='$Codigo' and seccion='$Seccion'";					
		$res=ExQuery($cons);		
		$Eliminar=0;	
	}	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<?
$cons="select codigo,seccion from nomina.secciones order by seccion";
$res=ExQuery($cons);
$Cont=ExNumRows($res);
if($Cont>15)
{
?>
<center><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NewSeccion.php?DatNameSID=<? echo $DatNameSID?>';"></center>
<?
}?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center">
<tr bgcolor="#666699" style="color:white" align="center" style="font-weight:bold"><td colspan="3" >SECCIONES</td></tr>
<?
if($Cont>0)
	{	?>
		<tr >
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" style="width:75">CODIGO</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" style="width:200" >NOMBRE</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" ></td>
        </tr>
<?	}
while($fila=ExFetch($res))
	{ 
	//echo $fila;
	?>
		<tr>
            <td align="center"><?  echo $fila[0]; ?></td>
            <td><?  echo $fila[1]; ?></td>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar la Seccion?')){location.href='Secciones.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Seccion=<? echo $fila[1]?>&Codigo=<? echo $fila[0]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>
        </tr>
<?		}
?>
</table>
<center><input type="button" name="Nuevo" value="Nuevo" onClick=" location.href='NewSeccion.php?DatNameSID=<? echo $DatNameSID?>';"></center>
</body>
</html>
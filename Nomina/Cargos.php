<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==1)
	{
		$cons="Delete from nomina.cargos where Compania='$Compania[0]' and codigo='$Codigo' and vinculacion='$TipoVinc'";					
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
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Vinculacion</td>
    <td><select name="TipoVinc" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' order by codigo";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[0]==$TipoVinc)
						 {echo "<option value='$fila[0]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
</tr>
</table>
</form>
<?
$cons="select codigo,cargo from nomina.cargos where vinculacion='$TipoVinc' order by codigo,cargo";
$res=ExQuery($cons);
$Cont=ExNumRows($res);
if($Cont>15)
{
?>
<center><input type="button" name="Nuevo" value="Nuevo" onClick=" location.href='NewCargo.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>';"></center>
<?
}?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center">
<?
if($Cont>0)
	{	?>
		<tr >
        	<td bgcolor="#e5e5e5" style="font-weight:bold;width:75" align="center" >CODIGO</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold;width:200" align="center" >CARGO</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" ></td>
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
            <td width="16px"><a href="NewCargo.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&TipoVinc=<? echo $TipoVinc?>&Codigo=<? echo $fila[0]?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" /></a></td>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Cargo?')){location.href='Cargos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&TipoVinc=<? echo $TipoVinc?>&Codigo=<? echo $fila[0]?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>
        </tr>
<?		}
//echo $TipoVinc;
?>
</table>
<center><input type="button" name="Nuevo" <? if(!$TipoVinc){echo "disabled";}?> value="Nuevo" onClick=" location.href='NewCargo.php?DatNameSID=<? echo $DatNameSID?>&TipoVinc=<? echo $TipoVinc?>';"></center>

</body>
</html>
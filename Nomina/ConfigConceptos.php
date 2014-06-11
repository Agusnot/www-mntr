<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar==1)
	{
		$cons="Delete from nomina.conceptosliquidacion where movimiento='$Movimiento' and codigo='$Codigo' and concepto='$Concepto' and tipovinculacion='$TipoVinculacion'";					
//		echo $cons;
		$res=ExQuery($cons);		
		$Eliminar=0;	
	}
?>
<html>
<head>
<meta http-equiv="Content-
Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Vinculacion</td>
    <td><select name="TipoVinculacion" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]'";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[1]==$TipoVinculacion)
						 {echo "<option value='$fila[1]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Concepto de Liquidacion</td>
    <td><select name="Movimiento" style="width:300px;" onChange="FORMA.submit();">
            <option ></option>
                    <?
                    $cons = "select codigo,concepto from nomina.movimientos where compania='$Compania[0]' order by codigo";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						 if($fila[1]==$Movimiento)
						 {echo "<option value='$fila[1]' selected>$fila[1]</option>"; }
						 else{echo "<option value='$fila[1]'>$fila[1]</option>";}						 
                    }
				?>
            </select></td>
</tr>
</table>
</form>
<? $cons="select codigo,detconcepto,concepto,claseconcepto,tipoconcepto,opera,arrastracon from nomina.conceptosliquidacion where movimiento='$Movimiento' and tipovinculacion='$TipoVinculacion' order by concepto";
$res=ExQuery($cons);
$cont=ExNumRows($res);
if($cont>15)
{ ?>
	<center><input type="button" name="Nuevo" <? if(!$Movimiento || !$TipoVinculacion){echo "disabled";}?> value="Nuevo" onClick=" location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&Movimiento=<? echo $Movimiento?>&TipoVinculacion=<? echo $TipoVinculacion?>';"></center>
<? }
?>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5"  align="center">
<?
if($cont>0)
	{?>
		<tr >
<!--        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">CODIGO</td> -->
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">NOMBRE DEL CONCEPTO</td>
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">DETALLE DEL CONCEPTO</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">CLASE DE CONCEPTO</td>
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">TIPO CONCEPTO</td>
       	    <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >OPERACION</td>	
            <td bgcolor="#e5e5e5" style="font-weight:bold" align="center" >VIENE CON</td>
        </tr>
<? 	}
while($fila=ExFetch($res))
	{ 
	//echo $fila;
	?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        <!--	<td align="center"><?// echo $fila[0]; ?></td>-->
            <td><? echo $fila[2]; ?></td>
            <td><? echo $fila[1]; ?>&nbsp;</td>
            <td><? echo $fila[3]; ?>&nbsp;</td>
            <td><? echo $fila[4]; ?>&nbsp;</td>
            <td><? echo $fila[5]; ?>&nbsp;</td>
            <td><? echo $fila[6]; ?>&nbsp;</td>
            <td width="16px"><a href="NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Concepto=<? echo $fila[2];?>&Movimiento=<? echo $Movimiento?>&Codigo=<? echo $fila[0];?>&TipoVinculacion=<? echo $TipoVinculacion ?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" /></a></td>
            <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar el Concepto <? echo $fila[2];?> ?')){location.href='ConfigConceptos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigo=<? echo $fila[0];?>&Concepto=<? Echo $fila[2];?>&Movimiento=<? echo $Movimiento?>&TipoVinculacion=<? echo $TipoVinculacion?>'}"><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></a>
            </td>
<?		}
?>
</table>
<center><input type="button" name="Nuevo" <? if(!$Movimiento || !$TipoVinculacion){echo "disabled";}?> value="Nuevo" onClick=" location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&Movimiento=<? echo $Movimiento?>&TipoVinculacion=<? echo $TipoVinculacion?>';"></center>
</body>
</html>
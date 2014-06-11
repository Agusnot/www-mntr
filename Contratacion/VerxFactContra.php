<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$Entidad'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="right">Entidad</td>
        <td colspan="3"><? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Contrato</td>
        <td><? echo "$Contrato";?></td>
    
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">No Contrato</td>
        <td><? echo "$Nocontrato";?></td>
    </tr>
</table>
<br>
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>No Liquidacion</td><td>Usuario</td><td>Identificacion</td><td>SubTotal</td><td>Copago</td><td>Descuento</td><td>Total</td>
    </tr>
<?	
	$cons2="select noliquidacion,subtotal,valorcopago,valordescuento,total,primape,segape,primnom,segnom,cedula,estado
	from facturacion.liquidacion,central.terceros
	where liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]' and estado='AC'
	and pagador='$Entidad' and contrato='$Contrato' and nocontrato='$Nocontrato' and nofactura is null and identificacion=cedula order by noliquidacion";
	//echo $cons2;
	
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)>0){
		while($fila2=ExFetch($res2)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
				<td align="center" style="cursor:hand" title="Ver"
                    onClick="open('/Facturacion/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila2[0]?>&Ced=<? echo $fila2[9]?>&Estado=<? echo $fila2[10]?>','','left=10,top=10,width=900,height=700,menubar=yes,scrollbars=YES')">
					<? echo $fila2[0]?>
				</td>
                <td><? echo "$fila2[5] $fila2[6] $fila2[7] $fila2[8]";?></td><td align="center"><? echo $fila2[9]?></td>
				<td align="right"><? echo number_format($fila2[1],2)?></td><td align="right"><? echo number_format($fila2[2],2)?></td>
				<td align="right"><? echo number_format($fila2[3],2)?></td><td align="right"><? echo number_format($fila2[4],2)?></td>
			</tr>	
	<?		$Total=$Total+$fila2[4];
		}?>
        <tr align="right">
        	<td style="font-weight:bold" colspan="6">Total</td>
        	<td align="right"><? echo number_format($Total,2)?></td>
        </tr>
<?	}
	else{
		echo "<tr align='center'> <td colspan='7'>No Se Han Realiazado Facturas Para Esta Contrato</td> </tr>";
	}
?>
</table>
</form>    
</body>
</html>

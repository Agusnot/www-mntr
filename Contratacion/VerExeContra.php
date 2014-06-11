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
    	<td>No Factura</td><td>SubTotal</td><td>Copago</td><td>Descuento</td><td>Total</td>
    </tr>
<?	$cons2="select nofactura,subtotal,copago,descuento,total,estado from facturacion.facturascredito   
	where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Nocontrato' and estado='AC' order by nofactura";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)>0){
		while($fila2=ExFetch($res2)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
				<td align="center" style="cursor:hand" title="Ver"
                    onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila2[0]?>&Estado=<? echo $fila2[5]?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
					<? echo $fila2[0]?>
				</td>
				<td align="right"><? echo number_format($fila2[1],2)?></td><td align="right"><? echo number_format($fila2[2],2)?></td>
				<td align="right"><? echo number_format($fila2[3],2)?></td><td align="right"><? echo number_format($fila2[4],2)?></td>
			</tr>	
	<?		$Total=$Total+$fila2[4];
		}?>
        <tr align="right">
        	<td style="font-weight:bold" colspan="4">Total</td>
        	<td align="right"><? echo number_format($Total,2)?></td>
        </tr>
<?	}
	else{
		echo "<tr align='center'> <td colspan='5'>No Se Han Realiazado Facturas Para Esta Contrato</td> </tr>";
	}
?>
</table>
</form>    
</body>
</html>

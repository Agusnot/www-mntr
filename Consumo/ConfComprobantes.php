<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($Eliminar)
	{
            //Revisar consumo.actasmovimiento, consumo.entradasxremisiones, consumo.movimiento
			$cons = "Select * from Consumo.ActasMovimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				$MensajeElim = "No se puede eliminar comprobante. Aun se lo referencia desde ActasMovimiento";
			}
			$cons = "Select * from Consumo.EntradasxRemisiones Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and CompEntrada='$Comprobante'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0)
			{
				if(!$MensajeElim){ $MensajeElim = "No se puede eliminar comprobante. Aun se lo referencia desde EntradasxRemisiones"; }
				else{$MensajeElim=$MensajeElim.",EntradasxRemisiones";}
			}
			$cons = "Select * from Consumo.Movimiento Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' 
			and TipoComprobante='$Tipo' and Comprobante='$Comprobante'";
			$res = ExQUery($cons);
			if(ExNumRows($res)>0)
			{
				if(!$MensajeElim){ $MensajeElim = "No se puede eliminar comprobante. Aun se lo referencia desde Movimientos"; }
				else{$MensajeElim=$MensajeElim.",Movimientos";}
			}
            if(!$MensajeElim)
			{
				$cons = "Delete from Consumo.Comprobantes where Comprobante='$Comprobante' and AlmacenPpal = '$AlmacenPpal' and Compania = '$Compania[0]'";
				$res = ExQuery($cons);
			}
	}
?>
<script language="javascript">
	function Validar()
	{
		document.FORMA.action = "NewConfComprobante.php";
		document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<select name="AlmacenPpal" onChange="document.FORMA.submit()">
        	<?
            $cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			echo ExError();
			while($fila=ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
			?>
</select>
<?
	if($AlmacenPpal)
	{
		$cons="Select Comprobante,NumeroInicial,Tipo,Costo,Venta,Iva,Descto,ReteFte,ICA,ComprobanteContable,
		CtaTipoVenta,ExigeOC,Formato,Mensaje1,Mensaje2,DesvioTotal 
		from Consumo.Comprobantes where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
		$res = ExQuery($cons);
		echo ExError();
		echo "<table width='100%' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
			<tr style='font-weight:bold' width='280px' bgcolor='#e5e5e5' align='center'>
			<td>Nombre Comprobante</td><td>NoInicial</td><td>Tipo</td>
			<td>Costo</td><td>Venta</td><td>Iva</td><td>Descuento</td><td>ReteFuente</td><td>ICA</td>
			<td>Comprobante Contable</td><td>Cta TipoVenta</td><td>ExigeOC</td><td>Formato</td><td>Mensaje1</td><td>Mensaje2</td>
			<td>Ajustar Sobre</td></tr>";
		while($fila=ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td width='70px' align='center'>$fila[2]</td><td width='70px' align='center'>$fila[3]</td>
			<td width='70px' align='center'>$fila[4]</td><td width='70px' align='center'>$fila[5]</td><td width='70px' align='center'>$fila[6]</td>
			<td width='70px' align='center'>$fila[7]</td><td width='70px' align='center'>$fila[8]</td><td>$fila[9]</td><td>$fila[10]</td>
			<td>$fila[11]</td><td>$fila[12]</td>";
			?>
            <td onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" title="<? echo $fila[13]?>"><? echo substr($fila[13], 0, 15);?></td>
            <td onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'" title="<? echo $fila[14]?>"><? echo substr($fila[14], 0, 15);?></td>
            <td><? echo $fila[15]?></td>
            <td width="20px">
            <a href="NewConfComprobante.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Comprobante=<? echo $fila[0];?>"><img border="0" src="/Imgs/b_edit.png" /></a>
            </td>
			<td width="20px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
            {location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Comprobante=<? echo $fila[0];?>&Tipo=<? echo $fila[2]?>';}">
			<img border="0" src="/Imgs/b_drop.png"/></a></td></tr>
            <?		
		}
?>
		</table>
        <input type="button" name="Nuevo" value="Nuevo" onClick="Validar()"  />
		</form>
	<? } 
	if($MensajeElim)
	{
		?><script language="javascript">
        	alert("<? echo $MensajeElim;?>");
        </script><?
	}?>
</body>
<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
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
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	if($Registrar)
	{
		$cons = "Delete from Consumo.TmpMovimiento where TMPCOD = '$TMPCOD'";
		$res = ExQuery($cons);
		$cons = "Select AutoId,ValorUnidad
		from Consumo.ProductosxContrato where NumeroContrato='$OpcionContrato' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			$cons2="Insert into Consumo.TmpMovimiento 
			(TMPCOD,AutoId,Cantidad,VrCosto,TotCosto,VrVenta,TotVenta,PorcIVA,VrIVA,PorcReteFte,VrReteFte,PorcDescto,VrDescto,PorcICA,VrICA,DocAfectado,NoDocAfectado
			 ,NumeroContrato)
			values
			('$TMPCOD',$fila[0],1,$fila[1],$fila[1],0,0,0,0,0,0,0,0,0,0,' ',' ','$OpcionContrato')";
			$res2=ExQuery($cons2);echo ExError();	
		}
		?><script language="javascript">
		parent.document.FORMA.Detalle.value = "Contrato No - <? echo $OpcionContrato?>";
		parent.frames.NuevoMovimiento.location.href=parent.frames.NuevoMovimiento.location.href;
		parent.frames.TotMovimientos.location.href='TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<?echo $TMPCOD?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $Numero?>&AlmacenPpal=<? echo $AlmacenPpal?>';
		CerrarThis();
		parent.frames.NuevoMovimiento.document.FORMA.Nuevo.disabled = true;
        </script><?
	}
	if(!$Cedula)
	{
		echo "<em>Usted no ha escogido un tercero Valido<br></em>";	
	}
	else
	{
		$cons = "Select  Numero,Proveedor,FechaInicio,FechaFin,Valor,PrimApe,SegApe,PrimNom,SegNom
		from Consumo.Contratos, Central.Terceros where Contratos.Proveedor = Terceros.Identificacion
		and Contratos.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' 
		and AlmacenPpal = '$AlmacenPpal' and Proveedor = '$Cedula'";
		$res = ExQuery($cons);
		echo "<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width='100%'>
		<tr bgcolor='#e5e5e5' align='center' style='font-weight:bold'>
		<td></td><td>Numero</td><td>Proveedor</td><td>FechaInicio</td><td>FechaFin</td><td>Valor</td></tr>";
		while($fila = ExFetch($res))
		{
			?>
			<tr><td><input type="radio" name="OpcionContrato" value="<? echo $fila[0]?>" id="<? echo $fila[0]?>" /></td>
			<?
			echo "<td>$fila[0]</td><td>$fila[5] $fila[6] $fila[7] $fila[8]</td><td>$fila[2]</td><td>$fila[3]</td>
			<td align='right'>".number_format($fila[4],2)."</td><tr>";
			$cons1="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,Cantidad,ValorUnidad
			from Consumo.ProductosxContrato NATURAL JOIN Consumo.CodProductos where NumeroContrato='$fila[0]' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
			//echo $cons1;
			$res1 = ExQuery($cons1);
			if (ExNumRows($res1)==0)
			{
			?>
            	<script language="javascript">
                	document.getElementById('<? echo $fila[0]?>').disabled = true;
					document.getElementById('<? echo $fila[0]?>').title = "El Contrato no referencia a ningun producto";
                </script>
			<?
			}
		}
	}
?>
</table>
	<input type="submit" name="Registrar" value="Registrar" />
    <input type="button" name="Cancelar" value="Cancelar" onClick="CerrarThis()" />
</form>
</body>

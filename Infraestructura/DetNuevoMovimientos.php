<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Tipo == "Orden Compra"){ $NumeroTipo = "NumeroOrdenCompra";}
	if($Tipo == "Compras"){ $NumeroTipo = "NumeroCompra";}
	if($Eliminar)
	{
		if($Tipo=="Orden Compra")
		{
			$cons = "Delete from Infraestructura.CodElementos Where Compania='$Compania[0]' and Tipo='$Tipo' and Clase='$Clase'
			and TMPCOD='$TMPCOD' and AutoId=$AutoId";	
		}
		else
		{
			$cons = "Update Infraestructura.CodElementos set Tipo = 'Compras', estadoOrdenCompra='Aprobado',
			Codigo=NULL, Serie='', TMPCOD = '' 
			Where Compania='$Compania[0]' and Tipo='$Tipo' and Clase='$Clase'
			and TMPCOD='$TMPCOD' and AutoId=$AutoId";	
		}
		$res = ExQuery($cons);
	}
?>
<body background="/Imgs/Fondo.jpg" 
onload="if(document.FORMA.NumFilasGuarda.value>0){parent.document.FORMA.Guardar.disabled = false;}
else{parent.document.FORMA.Guardar.disabled = true;}">
<form name="FORMA" method="post" action="NuevoLevInicial.php">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Numero" value="<? echo $Numero;?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD;?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo;?>"  />
<input type="hidden" name="Compra" value="<? echo $Compra?>"  />
<input type="hidden" name="NumeroTipo" value="<? echo $NumeroTipo?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Mes" value="<? echo $Mes?>" />
<input type="hidden" name="Dia" value="<? echo $Dia?>" />

<?	
	if ($Tipo == "Compras"){$AdCompras = " or Tipo='Compras'";}
	$cons = "Select Nombre,Marca,Modelo,CostoInicial,AutoId,VrIva,IncluyeIva,
	PorcIva,Estado".str_replace(" ","",$Tipo).",Codigo from Infraestructura.CodElementos 
	Where Compania='$Compania[0]' and (Tipo='Orden Compra' $AdCompras)and TMPCOD='$TMPCOD'";
	$res = ExQuery($cons);
	$NumFilas = ExNumRows($res);
	$NumFilasGuarda = $NumFilas;
	if($NumFilas > 0)
	{
?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td>Nombre</td><td>Marca</td><td>Modelo</td><td>Costo</td><td>Iva</td><td>TotCosto</td><td colspan="3">&nbsp;</td>    
        </tr>
    <?
    while($fila = ExFetch($res))
	{
		if($fila[6])
		{
			$Costo = $fila[3] - ($fila[3]*$fila[7]/100);
			$Iva = $fila[3]*$fila[7]/100;
			$TotCosto = $fila[3];
		}
		else
		{
			$Costo = $fila[3];
			$Iva = $fila[5];
			$TotCosto = $Costo + $Iva;	
		}
		if($fila[9] == NULL && $Tipo=="Compras" ){ $Titulo = " title = 'No esta Listo para Ingreso' ";
		$Imagen = "<td><img src='/Imgs/b_alert.png' title='Presione Editar para Codificar el producto' /></td>";}
		echo "<tr $Titulo><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td align='right'>".number_format($Costo,2)."</td>
		<td align='right'>".number_format($Iva,2)."</td><td align='right'>".number_format($TotCosto,2)."</td>";
		?>
        <td width="20px">
		<a href="#" 
        onclick="location.href='NuevoLevInicial.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&TMPCOD=<? echo $TMPCOD;?>&Editar=1&Tipo=<? echo $Tipo?>&Clase=<? echo $Clase?>&Numero=<? echo $Numero ?>&AutoId=<? echo $fila[4];?>'">
        	<img src="/Imgs/b_edit.png" border="0" title="Editar" />
        </a></td>
        <td width="20px"><img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand;"
         onclick="if(confirm('Desea Eliminar el Registro')){location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Dia=<? echo $Dia?>&AutoId=<? echo $fila[4];?>&Eliminar=1&Numero=<? echo $Numero;?>&Clase=<? echo $Clase;?>&TMPCOD=<? echo $TMPCOD;?>&Tipo=<? echo $Tipo;?>';}"  /></td><? echo $Imagen;?></tr>
		<?	
		$SubTotal = $SubTotal + $Costo;
		$TotalIva = $TotalIva + $Iva;
		$Totales = $Totales + $TotCosto;
	}	
	?>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">&nbsp;</td><td align="right">TOTALES</td>
    <td align="right"><? echo number_format($SubTotal,2);?></td>
    <td align="right"><? echo number_format($TotalIva,2);?></td>
    <td align="right"><? echo number_format($Totales,2);?></td></tr>
	</table>
<?
		if($Tipo == "Compras")
		{
			$cons = "Select * From InfraEstructura.CodElementos Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and Codigo IS NOT NULL";
			$res = ExQuery($cons);
			$NumFilasGuarda = ExNumRows($res);
		}
	}
if($Tipo == "Orden Compra"){?><input type="submit" name="Nuevo" value="Nuevo" /><?	}?>
<input type="hidden" name="NumFilasGuarda" value="<? echo $NumFilasGuarda;?>" />
</form>
<script language="javascript">
	parent.document.FORMA.Factura.value = "<? echo $Totales;?>";
</script>
</body>
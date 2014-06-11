<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Tipo == "Orden Compra"){ $NumeroTipo = "NumeroOrdenCompra";}
	if($Tipo == "Compras"){ $NumeroTipo = "NumeroCompra";}
	if(!$MesI){$MesI=$MesTrabajo;}if(!$AnioI){$AnioI=$ND[year];}
	if(strlen($MesI)==1){$MesI="0".$MesI;}
	$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Consumo' and Anio=$AnioI and Mes=$MesI";
	$res = ExQuery($cons);
	if(ExNumRows($res)==1)
	{
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.disabled=true;
		parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
		</script>
		<?
		$NoEdEl = 1;
	}
	else
	{
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.disabled=false;
		parent(0).document.FORMA.Nuevo.title="";
		</script>
		<?
		unset($NoEdEl);
	}
	$cons="Select Mes,NumDias from Central.Meses where Numero=$MesI";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	echo ExError();$UltDia=$fila[1];
	if($Elim)
	{
		if($Tipo == "Orden Compra")
		{
			$cons = "Update Infraestructura.CodElementos set EstadoOrdenCompra='ANULADO', EstadoOrdenCompraX = '$usuario[0]'
			Where Compania='$Compania[0]' and NumeroOrdenCompra = '$Numero' and (EstadoOrdenCompra='Solicitado' or EstadoOrdenCompra='Aprobado')";	
		}
		if($Tipo == "Compras")
		{
			$cons = "Update Infraestructura.CodElementos set EstadoCompras='ANULADO', EstadoComprasX = '$usuario[0]'
			Where Compania='$Compania[0]' and NumeroCompra = '$Numero' and EstadoCompras='Ingresado'";
		}
		$res = ExQuery($cons);
		$Numero="";		
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Abrir(Tipo,Numero)
	{
		if(Tipo=="Orden Compra")
		{open("/Informes/Infraestructura/Formatos/OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Orden Compra&Numero="+Numero,'','width=800,height=600,scrollbars=yes');}
		if(Tipo=="Compras")
		{open("/Informes/Infraestructura/Formatos/Compra.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Compras&Numero="+Numero,'','width=800,height=600,scrollbars=yes');}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<? 
	if($Tipo=="Orden Compra"){ $FechaTipo = " FechaOrdenCompra";}else{ $FechaTipo = " FechaCompra";}
	if($DiaI){ $conFecha = " and $FechaTipo='$AnioI-$MesI-$DiaI' ";}
	if($Numero){ $conNumero = " and $NumeroTipo like '%$Numero' ";}
	if($Detalle){ $conDetalle = " and Detalle ilike '%$Detalle%' ";}
	if($Identificacion){ $conIdentificacion= " and Cedula like '$Identificacion%' ";}
	if($TotalCosto){ $H = " HAVING ";}
	if($TotalCosto){ $conTotalCosto = " Sum(CostoInicial)+sum(VrIVA) = $TotalCosto ";}
	if($Tipo == "Orden Compra"){ $AdCons = " or CodElementos.Tipo='Compras' ";}
	
	$cons = "Select $FechaTipo,$NumeroTipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,Sum(CostoInicial)+sum(VrIVA),
	Sum(Costo".str_replace(" ","",$Tipo)."),CodElementos.Tipo,Estado".str_replace(" ","",$Tipo)."
	from Infraestructura.CodElementos,Central.Terceros where CodElementos.Cedula=Terceros.Identificacion and CodElementos.Compania='$Compania[0]'
	and Terceros.Compania='$Compania[0]' and $FechaTipo>='$AnioI-$MesI-01' and $FechaTipo<='$AnioI-$MesI-$UltDia' 
	and (CodElementos.Tipo='$Tipo' $AdCons)
	and Clase='$Clase' $conFecha $conNumero $conDetalle $conIdentificacion 
	Group by $NumeroTipo,$FechaTipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,CodElementos.Tipo,Estado".str_replace(" ","",$Tipo).",VrFactura,NoFactura
	$H $conTotalCosto 
	Order By $NumeroTipo,$FechaTipo";
	//echo $cons;
	$res = ExQuery($cons);
	echo "<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width='100%'>";
	?>
	<tr align="center" valign="middle" bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF; font-weight:bold">
    	<td>Fecha</td><td>Numero</td><td>Detalle</td><td>Tercero</td><td>Costo</td><td width="8%" colspan="3">Buscar</td>
    </tr>
    <tr align="center" valign="middle">
    	<td width="8%"><? echo "$AnioI-$MesI-"?><input type="text" name="DiaI" value="<? echo $DiaI?>" maxlength="2" style="width:20px"
        onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
        
        <td width="8%"><? echo $AnioI?><input type="text" name="Numero" value="<? echo $Numero?>" maxlength="6" style="width:50px"
         onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
         
         <td><input type="text" name="Detalle" value="<? echo $Detalle?>" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
         <td width="30%"><input type="text" name="Identificacion" value="<? echo $Identificacion?>" style="text-align:center" 
         onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" maxlength="20"></td>
         <td width="8%">
         	<input type="text" name="TotalCosto" value="<? echo $TotalCosto?>" style="width:100%; text-align:right"
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
         </td>
         <td colspan="3">
         <input type="hidden" name="Tipo" value="<? echo $Tipo?>">
         <input type="hidden" name="Comprobante" value="<? echo $Comprobante?>">
         <input type="hidden" name="AnioI" value="<? echo $AnioI?>">
         <input type="hidden" name="MesI" value="<? echo $MesI?>">
         <input type="hidden" name="Clase" value="<? echo $Clase;?>" >
        <button name="Buscar" type="submit"><img src="/Imgs/b_search.png" title="Buscar Registro"></button>
		<input type="checkbox" name="Recursivo" title="Busqueda Recursiva" value="1">
         </td>
    </tr>
	<?
	while ($fila=ExFetch($res))
	{
		if($NumAnt != $fila[1])
		{
			if($fila[11]=="Aprobado" || $fila[11]=="Rechazado" || $fila[11]=="ANULADO"){$NoEdEl = 1;}else{$NoEdEl = "";}
			if($fila[11]=="ANULADO"){$Est=" color:red;text-decoration:underline ";}else{$Est="";$TotalMovs = $TotalMovs + $fila[8];}
			
			?><tr style="  <? echo $Est ?>" 
				onMouseOver="this.bgColor='#AAD4FF'" 
				onmouseout="this.bgColor='#FFFFFF'" 
				><?
			echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3] $fila[4] $fila[5] $fila[6] - $fila[7]</td><td align='right'>".number_format($fila[8],2)."</td>";
			?>
			<td>
            <img onClick="Abrir('<? echo $Tipo;?>','<? echo $fila[1];?>','<? echo $fila[7]?>')" src="/Imgs/b_print.png" title="ver imprimible" style="cursor:hand" />
            </td>
            <td>
			<?
				if(!$NoEdEl)
				{
					?><img onClick="if(confirm('Desea anular documento?')){location.href='ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Elim=1&Clase=<? echo $Clase;?>&Tipo=<? echo $Tipo?>&Numero=<? echo $fila[1]?>&AnioI=<? echo substr($fila[0],0,4)?>&MesI=<? echo substr($fila[0],5,2)?>';}"  style="cursor:hand" title="Anular Documento" src='/Imgs/b_drop.png'><?
				}
				else
				{
					?><img src="/Imgs/b_drop_gray.png" title="NO SE PUEDE ANULAR" /><?
				}
			?>
			</td>
            <td>
			<? if(!$NoEdEl)
			{
				?><img onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Clase=<? echo $Clase?>&Tipo=<? echo $Tipo?>&Numero=<? echo $fila[1]?>&Anio=<? echo substr($fila[0],0,4)?>&Mes=<? echo  substr($fila[0],5,2)?>&Dia=<? echo  substr($fila[0],8,2)?>&Detalle=<? echo $fila[2]?>&Identificacion=<? echo $fila[7]?>&Tercero=<? echo "$fila[3] $fila[4] $fila[5] $fila[6]"?>'" style='cursor:hand' title="Editar Documento" src='/Imgs/b_edit.png'><?
			}
			else
			{
				?><img src="/Imgs/b_edit_gray.png" title="NO SE PUEDE EDITAR" /><?
			}
			?>
			</td>
	<?		echo "</tr>";	
		}
	$NumAnt = $fila[1];
	}
	if($TotalMovs)
	{
	?><tr><td colspan="10"><hr></td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4" align="right">TOTAL:</td>
    	<td align="right"><? echo number_format($TotalMovs,2);?></td>
    </tr><?
	}
	echo "</table>";
	if(($DiaI || $Numero || $Detalle || $Identificacion) && $Recursivo==1)
	{
		$consxx = "Select $FechaTipo,$NumeroTipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,Sum(CostoInicial)+sum(VrIVA),
		CodElementos.Tipo,Estado".str_replace(" ","",$Tipo)."
		from InfraEstructura.CodElementos,Central.Terceros where CodElementos.Cedula=Terceros.Identificacion and CodElementos.Compania='$Compania[0]'
		and Terceros.Compania='$Compania[0]' and $NumeroTipo not in(Select $NumeroTipo
		from InfraEstructura.CodElementos,Central.Terceros where CodElementos.Cedula=Terceros.Identificacion and CodElementos.Compania='$Compania[0]'
		and Terceros.Compania='$Compania[0]' and $FechaTipo>='$AnioI-$MesI-01' and $FechaTipo<='$AnioI-$MesI-$UltDia' and CodElementos.Tipo='$Tipo'
		and Clase='$Clase' $conFecha $conNumero $conDetalle $conIdentificacion 
		Group by $NumeroTipo,$FechaTipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,CodElementos.Tipo,Estado".str_replace(" ","",$Tipo)."
		$H $conTotalCosto 
		Order By $NumeroTipo,$FechaTipo) and Clase='$Clase' and CodElementos.Tipo='$Tipo' $conFecha $conNumero $conDetalle $conIdentificacion 
		Group by $NumeroTipo,$FechaTipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,CodElementos.Tipo,Estado".str_replace(" ","",$Tipo)."
		$H $conTotalCosto 
		Order By $NumeroTipo,$FechaTipo";
		$resxx = ExQuery($consxx);
		if(ExNumRows($resxx)>0)
		{
		?>
		<hr size="2">
        <table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width='100%'>
        	<tr bgcolor="#E5E5E5" style="font-weight:bold"><td align="center" colspan="7">
            OTRAS COINCIDENCIAS FUERA DEL PERIODO</td></tr>
            <tr align="center" valign="middle" bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF; font-weight:bold">
    		<td>Fecha</td><td>Numero</td><td>Detalle</td><td>Tercero</td><td>Total Costo</td><td>Total Venta</td>
    		</tr>
            <?
            	while($filaxx = ExFetch($resxx))
				{
					echo "<tr><td>$filaxx[0]</td><td>$filaxx[1]</td><td>$filaxx[2]</td><td> $filaxx[3] $filaxx[4] $filaxx[5] $filaxx[6]
					- $filaxx[7]<td align='right'>".number_format($filaxx[8],2)."</td><td align='right'>".number_format($filaxx[9],2)."</td></tr>";
				}
			?>
        </table>
		<?
		}
	}
?>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</form>
</body>

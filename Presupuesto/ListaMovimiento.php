<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	if(!$Vigencia){$Vigencia="Actual";}
	$ND=getdate();

	if($AnioI){$AnioTrabajo=$AnioI;}else{$AnioI=$AnioTrabajo;}
	if($MesI){$MesTrabajo=$MesI;}else{$MesI=$MesTrabajo;}
	$MesI=$MesTrabajo;$AnioI=$AnioTrabajo;

	if(strlen($MesI)==1){$MesI="0".$MesI;}

	$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Presupuesto' and Anio=$AnioI and Mes=$MesI";
	$res = ExQuery($cons);
	if(ExNumRows($res)==1)
	{	$NoEditar=2;
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
		parent(0).document.FORMA.Nuevo.disabled=true;
		</script>
		<?
		$NoEdEl = 1;
	}
	else
	{
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.title="";
		parent(0).document.FORMA.Nuevo.disabled=false;
		</script>
		<?
		unset($NoEdEl);
	}


	if(!$Comprobante){
	$cons8="SELECT Comprobante FROM 
	Presupuesto.Comprobantes,Presupuesto.TiposComprobante 
	WHERE Tipo=TipoComprobant and  TipoGr='$Tipo' and Compania='$Compania[0]' ORDER BY Comprobantes.Comprobante";
	$res8=ExQuery($cons8,$conex);echo ExError($conex);
	$fila8=ExFetch($res8);
	$Comprobante=$fila8[0];}

	$cons="Select Archivo from Presupuesto.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Archivo=$fila[0];


	if(!$AnioI){$AnioI=$ND[year];}
	if(!$MesI){$MesI=$ND[mon];}
		if($Elim)
	{
		$cons="Select * from Presupuesto.Movimiento where CompAfectado='$Comprobante' and DocSoporte='$Numero' and Estado='AC' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>=1)
		{?>
			<script language="JavaScript">
				alert("Este documento ya tiene afectaciones. No puede ser anulado!!!");
			</script>
<?		}
		else
		{
			$cons="Update Presupuesto.Movimiento set Estado='AN' where Estado='AC' and Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
			$res=ExQuery($cons,$conex);
		}
	}
	if($Buscar)
	{
		if($Numero){$CondAdc=" and Numero like '%$Numero' ";}
		elseif($Fecha){$CondAdc=" and Fecha='$AnioI-$MesI-$Fecha' ";}
		elseif($DebeBusq){$CondAdc=" and Debe='$DebeBusq' ";}
		elseif($HaberBusq){$CondAdc=" and Haber='$HaberBusq' ";}

		else{$CondAdc=" and Detalle ilike '$Detalle%' and PrimApe ilike '$Tercero%' and Movimiento.Identificacion ilike '$IdTercero%'";}
	}
?>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr class="Tit1"><td>Fecha</td><td>N&uacute;mero</td><td>Detalle</td><td>Tercero</td><td>Credito</td><td>Contra Credito</td><td colspan="4"></td></tr>
<tr><td><?echo "$AnioI - $MesI - "?><input type="Text" name="Fecha" style="width:20px;"></td>
<td><input type="Text" name="Numero" style="width:70px;"></td>
<td><input type="Text" name="Detalle" style="width:130px;"></td>
<td><input type="Text" name="Tercero" style="width:130px;"> - 
<input type="Text" name="IdTercero" style="width:40px;">
</td>
<td align="right"><input type="Text" name="DebeBusq" style="width:70px;"></td>
<td align="right"><input type="Text" name="HaberBusq" style="width:70px;"></td>



<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="AnioI" value="<?echo $AnioI?>">
<input type="Hidden" name="MesI" value="<?echo $MesI?>">

<td colspan="4" align="center"><input type="Submit" name="Buscar" value="Buscar" style="width:80px;"></td>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</tr>
<?
	if($Comprobante){

	if($Vigencia=="Actual"){$Vig="Actual";$ClaseVig="";}
	else{$Vig="Anteriores";$ClaseVig=$Vigencia;}

	$cons="Select Fecha,Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,sum(Credito),sum(ContraCredito),Terceros.Identificacion,Estado,Vigencia,ClaseVigencia
	from Presupuesto.Movimiento,Central.Terceros 
	where Movimiento.Identificacion=Terceros.Identificacion and date_part('year',Fecha)=$AnioI and date_part('month',Fecha)=$MesI and Comprobante='$Comprobante'
	and Vigencia='$Vig' and ClaseVigencia='$ClaseVig'
	$CondAdc
	and Terceros.Compania='$Compania[0]'
	and Movimiento.Compania='$Compania[0]'
	Group By Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,Terceros.Identificacion,Estado,Vigencia,ClaseVigencia,Fecha
	Order By Numero Desc";

	$res=ExQuery($cons,$conex);echo ExError($conex);
	while($fila=ExFetchArray($res))
	{
		if($NoEditar!=2){$NoEditar=0;}
		if($fila[10]=="AN")
		{
			if($NoEditar!=2)
			{
				$NoEditar=3;
			}
			$Est="color:red;text-decoration:underline";
		}
		else
		{
			$Est="";
		}

		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}

		if($NoEditar!=2){
		$cons1="Select * from Presupuesto.Movimiento where CompAfectado='$Comprobante' and DocSoporte='$fila[1]' and Estado='AC' 
		and Movimiento.Compania='$Compania[0]' and Vigencia='$Vig' and ClaseVigencia='$ClaseVig'";
		$res1=ExQuery($cons1);
		if(ExNumRows($res1)>=1){$NoEditar=1;}
		}

		$Numero=$fila[1];
		echo "<tr style='$Est' bgcolor='$BG'><td>$fila[0]</td><td>$Numero</td><td>".substr($fila[2],0,30)."</td><td>$fila[3] $fila[4] $fila[5] $fila[6]</td><td align='right'>".number_format($fila[7],2)."</td><td align='right'>". number_format($fila[8],2)."</td>";
		echo "<a name='$Numero'>";?>
		<td align="center"><img src="/Imgs/b_ftext.png" alt="Ver Afectaciones de documento" style="cursor:hand" onClick="open('SeguimientoAfectaciones.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Vigencia=<? echo $fila['vigencia'] ?>&ClaseVigencia=<? echo $fila['clasevigencia']?>&Anio=<?echo $AnioI?>&Mes=<?echo $MesI?>','','width=800,height=400,scrollbars=yes')">

		<a style="cursor:hand" onClick="open('/Informes/Presupuesto/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Vigencia=<?echo $fila['vigencia']?>&ClaseVigencia=<?echo $fila['clasevigencia']?>','','width=700,height=500,scrollbars=yes')"><img border="0" src="/Imgs/b_print.png"></a>
		
		<? if($NoEditar==0) { ?><a target="_parent" href="NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Edit=1&Tipo=<?echo $Tipo?>&Vigencia=<?echo $fila['vigencia']?>&ClaseVigencia=<?echo $fila['clasevigencia']?>"><? }
		elseif($NoEditar==1){ ?><a onClick="alert('Este documento tiene afectaciones. No puede editarse');" style="cursor:hand"><? }
		elseif($NoEditar==2){ ?><a onClick="alert('Periodo Cerrado. No puede editarse');" style="cursor:hand"><? }
		elseif($NoEditar==3){ ?><a onClick="alert('Documento Anulado');" style="cursor:hand"><? } ?>
		<img src='/Imgs/b_edit.png' border="0"></a>
		<img style="cursor:hand" <? if($NoEditar==0){?>onClick="if(confirm('Desea anular este registro?')==true){location.href='ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<? echo $Comprobante?>&Numero=<? echo $fila[1]?>&Elim=1&AnioI=<? echo $AnioI?>&Vigencia=<? echo $fila['Vigencia']?>&ClaseVigencia=<? echo $fila['ClaseVigencia']?>&MesI=<? echo $MesI?>&Tipo=<? echo $Tipo?>#<? echo $Numero?>'}"<?}else{?> onClick="alert('Este documento no puede anularse');"<?}?> src='/Imgs/b_drop.png' border="0"></a></td>
<?		echo "</a></tr>";
	}
	}
?>
</table>
</body>
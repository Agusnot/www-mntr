<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	if($AnioI){$AnioTrabajo=$AnioI;}else{$AnioI=$AnioTrabajo;}
	if($MesI){$MesTrabajo=$MesI;}else{$MesI=$MesTrabajo;}
	$MesI=$MesTrabajo;$AnioI=$AnioTrabajo;

	if(!$Comprobante)
	{
		$cons="SELECT Comprobante FROM Contabilidad.Comprobantes WHERE TipoComprobant='$Tipo' and Compania='$Compania[0]'
		ORDER BY Comprobante";
		$res=ExQuery($cons,$conex);echo ExError($res);
		$fila=ExFetch($res);
		$Comprobante=$fila[0];
	}


	$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Contabilidad' and Anio=$AnioI and Mes=$MesI";
	$res = ExQuery($cons);
	if(ExNumRows($res)==1)
	{	
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
		parent(0).document.FORMA.Nuevo.disabled=true;
		</script>
		<?
		$NoEdEl = 1;$NoEditar=2;
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


	$cons="Select Formato,FormatoAdc from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Archivo=$fila[0];
	$ArchivoAdc=$fila[1];
	if($Elim)
	{
		$ComsAfectados="(";
		$cons="Select Comprobante from Contabilidad.CruzarComprobantes where CruzarCon='$Comprobante' and Compania='$Compania[0]' Group By Comprobante";
		$res=ExQuery($cons);
		$nr=0;
		while($fila=ExFetch($res))
		{
			$ComsAfectados=$ComsAfectados."Comprobante='$fila[0]' Or ";
			$nr++;
		}
		$ComsAfectados=substr($ComsAfectados,0,strlen($ComsAfectados)-3);
		$ComsAfectados=$ComsAfectados.")";
		if($nr==0){$ComsAfectados="1=2";}//Siempre va a ser cero, siempre anulará el registro
		$cons="Select * from Contabilidad.Movimiento where DocSoporte='$Numero' and $ComsAfectados and Compania='$Compania[0]' and Estado='AC'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>=1)
		{?>
			<script language="JavaScript">
				alert("Este documento tiene afectaciones. No puede ser anulado!!!");
			</script>
<?		}
		else
		{
			$cons="Update Contabilidad.Movimiento set Estado='AN' where Estado='AC' and Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]'";
			$res=ExQuery($cons,$conex);echo ExError($res);

			$cons="Update Presupuesto.Movimiento set Estado='AN' where Estado='AC' and DocOrigen='$Comprobante' and NoDocOrigen='$Numero' and Compania='$Compania[0]'";
			$res=ExQuery($cons,$conex);echo ExError($res);
		}
	}
	if($Buscar)
	{
		if($Numero){$CondAdc=" and Numero like '%$Numero' ";}
		elseif($Fecha){$CondAdc=" and Fecha='$AnioI-$MesI-$Fecha' ";}
		elseif($DebeBusq){$CondAdc=" and Debe='$DebeBusq' ";}
		elseif($HaberBusq){$CondAdc=" and Haber='$HaberBusq' ";}

		else{$CondAdc=" and Detalle ilike '$Detalle%' and PrimApe ilike '$Tercero%' and Movimiento.Identificacion ilike '$IdTercero%'";$CondTerc=", Central.Terceros";
		$CondWhere1=" and Terceros.Identificacion=Movimiento.Identificacion and Terceros.Compania='$Compania[0]'";}
	}
	
	
?>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr class="Tit1"><td>Fecha</td><td>Numero</td><td>Detalle</td><td>Tercero</td><td>Debe</td><td>Haber</td><td colspan="4"></td></tr>
<tr><td><?echo "$AnioI - $MesI - "?><input type="Text" name="Fecha" style="width:15px;"></td>
<td><input type="Text" name="Numero" style="width:40px;"></td>
<td><input type="Text" name="Detalle" style="width:150px;"></td>
<td><input type="Text" name="Tercero" style="width:100px;"> - 
<input type="Text" name="IdTercero" style="width:60px;">
</td>
<td align="right"><input type="Text" name="DebeBusq" style="width:50px;"></td>
<td align="right"><input type="Text" name="HaberBusq" style="width:50px;"></td>



<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="AnioI" value="<?echo $AnioI?>">
<input type="Hidden" name="MesI" value="<?echo $MesI?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<td colspan="4" align="center"><input type="Submit" name="Buscar" value="Buscar"></td>
</form>
</tr>
<?
	if($Comprobante){

	$cons6="select PrimApe,SegApe,PrimNom,SegNom,Terceros.Identificacion,Comprobante,Numero,AutoId
			from Central.Terceros,Contabilidad.Movimiento 
			where 
			Movimiento.Identificacion=Terceros.Identificacion 
			$CondAdc
			and 
			Movimiento.Compania='$Compania[0]' and
			Terceros.Compania='$Compania[0]'
			and Comprobante='$Comprobante'
			and date_part('month',Fecha)=$MesI and date_part('year',Fecha)=$AnioI
			Order By AutoId Desc";
//			echo $cons6;
	$res6=ExQuery($cons6);

	while($fila6=ExFetch($res6))
	{
		$MatTerc[$fila6[6]]=array($fila6[0],$fila6[1],$fila6[2],$fila6[3],$fila6[4]);
	}


	$cons1="Select Comprobante from Contabilidad.CruzarComprobantes where CruzarCon='$Comprobante' and Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
	$ComsAfectados=$fila1[0];

	$cons1="Select DocSoporte from Contabilidad.Movimiento where Comprobante='$ComsAfectados' and Compania='$Compania[0]' and Estado='AC'";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		$MatComsAfectados[$fila1[0]]=1;
	}



	$cons="Select Fecha,Numero,Detalle,sum(Debe),sum(Haber),Estado,NoCheque
	from Contabilidad.Movimiento $CondTerc
	where date_part('year',Fecha)=$AnioI and date_part('month',Fecha)=$MesI and Comprobante='$Comprobante'
	$CondWhere1
	$CondAdc
	and Movimiento.Compania='$Compania[0]'
	Group By Numero,Fecha,Detalle,Estado,NoCheque
	Order By Numero Desc";
//echo $cons;
	$res=ExQuery($cons,$conex);echo ExError($res);
	while($fila=ExFetch($res))
	{
		if($NoEditar!=2){$NoEditar=0;}
		$Tercero=$MatTerc[$fila[1]];
		if($NoEditar!=2){
		if($DetComsAfectados=$MatComsAfectados[$fila[1]]==1)
		{
			$NoEditar=1;
		}
		else{$NoEditar=0;}}

		if($fila[5]=="AN"){if($NoEditar!=2){$NoEditar=3;}$Est="color:red;text-decoration:underline";}else{$Est="";if($NoEditar!=2){$NoEditar=0;}}
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}

		$Numero=$fila[1];
		echo "<tr style='$Est' bgcolor='$BG'><td>$fila[0]</td><td>$Numero</td><td>".substr($fila[2],0,30)."</td><td>$Tercero[0] $Tercero[1] $Tercero[2] $Tercero[3]</td><td align='right'>".number_format($fila[3],2)."</td><td align='right'>". number_format($fila[4],2)."</td>";
		echo "<a name='$Numero'>";
		?>

		<td align="center"><img src="/Imgs/b_ftext.png" alt="Ver Afectaciones de documento"  style="cursor:hand" onClick="open('SeguimientoAfectaciones.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Tipo=<?echo $Tipo?>','','width=600,height=200,scrollbars=yes')">
		<a style="cursor:hand" onClick="open('/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Cuenta=<?echo $CuentaCheque?>&Numero=<?echo $fila[1]?>','','width=700,height=500,scrollbars=yes')"><img alt="Imprimir Comprobante" border="0" src="/Imgs/b_print.png"></a>
		<? if($fila[6]){?>
		<img alt="Imprimir Cheque" style="cursor:hand" onClick="open('ImpCheque.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Cuenta=<?echo $CuentaCheque?>','','width=800,height=250')" src="/Imgs/b_sbrowse.png" border="0"><?}?>
		
		<? if($NoEditar==0){?><a style="cursor:hand" onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Edit=1&Tipo=<?echo $Tipo?>'"><?}
		elseif($NoEditar==1){?><a onClick="alert('Este documento tiene afectaciones. No puede editarse');" style="cursor:hand"><?}
		elseif($NoEditar==2){?><a onClick="alert('Periodo cerrado');" style="cursor:hand"><?}
		elseif($NoEditar==3){?><a onClick="alert('Documento Anulado');" style="cursor:hand"><?}?>
		<img src='/Imgs/b_edit.png' border="0"></a>
		<img style="cursor:hand" <?if($NoEditar==0){?> onClick="if(confirm('Desea anular este registro?')==true){location.href='ListaMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $fila[1]?>&Elim=1&Tipo=<?echo $Tipo?>&AnioI=<?echo $AnioI?>&MesI=<?echo $MesI?>#<?echo $Numero?>'}"<?}else{?> onclick="alert('Periodo Cerrado');"<?}?> src="/Imgs/b_drop.png" border="0"></a>

<? 		if($ArchivoAdc)
		{?>
			<a style="cursor:hand" onClick="open('/Informes/Contabilidad/<? echo $ArchivoAdc ?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Cuenta=<?echo $CuentaCheque?>&Numero=<?echo $fila[1]?>','','width=700,height=500,scrollbars=yes')"><img alt="Imprimir Comprobante" border="0" src="/Imgs/b_print.png"></a>
<?		}

		if($fila[5]!="AN")
		{
			$SumDebes=$SumDebes+$fila[3];$SumHaberes=$SumHaberes+$fila[4];
		}
		echo "</td></tr>";
		echo "</a>";
	}

	echo "<tr><td colspan='4'></td><td colspan=2><hr></td></tr>";
	echo "<tr  align='right' style='font-weight:bold'><td colspan='3'></td><td>SUMAS</td><td>".number_format($SumDebes,2)."</td><td>".number_format($SumHaberes,2)."</td></tr>";

	}
?>

</table>
</body>
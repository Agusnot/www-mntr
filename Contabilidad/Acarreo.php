<?
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
<?
	$ND=getdate();
	if(!$AnioI){$AnioI=$Anio;}
	if(!$MesI){$MesI=$ND[mon];}
	if($Registrar)
	{
		while (list($val,$cad) = each ($Opc)) 
		{
			$cons="Select Cuenta,Movimiento.Identificacion,Debe,Haber,CC
			from Contabilidad.Movimiento,Central.Terceros 
			where Movimiento.Identificacion=Terceros.Identificacion 
			and Terceros.Compania='$Compania[0]'
			and Numero='$val' and Comprobante='$Comprobante'
			and Movimiento.Compania='$Compania[0]' and Estado='AC'";

			$res=ExQuery($cons);echo ExError();
			while($fila=ExFetch($res))
			{
				$AutoId++;
				$cons1="Insert into Contabilidad.TmpMovimiento(NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle)
				values('$NUMREG',$AutoId,'$Comprobante',$fila[0],'$fila[1]',$fila[2],$fila[3],'$fila[4]',$DocSoporte,'$Compania[0]','$Detalle')";
				$res1=ExQuery($cons1);echo ExError();
			}
		}?>
		<script language="JavaScript">
			parent.document.FORMA.ValidacionCruce.value=1;
			parent.frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>';
			CerrarThis();
		</script>
		
<?	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Compuconta Software</title>
</head>
<body>
<form name="FORMA" method="post">
<table border="1"  style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>" bordercolor="#e5e5e5">
<tr><td>Mes</td><td>A&ntilde;o</td><td>Numero</td></tr>
<tr>
<td>
<select name="MesI" onChange="FORMA.submit();">
<? $cons="Select * from Central.Meses "; 
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($MesI==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
		else{echo "<option value='$fila[1]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td><input type="text" name="AnioI" value="<? echo $AnioI ?>" style="width:40px;"></td>
<td><input type="text" name="Numero" value="<? echo $Numero ?>" style="width:90px;"></td>
<td><input type="submit" name="Buscar" value="Buscar"><input type="button" value="Cerrar" onClick="CerrarThis()"></td>
</tr>
</table>
</form>
<form name="FORMA1" method="post">
<table border="1"  style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>" bordercolor="#e5e5e5">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td></td><td>Fecha</td><td>Numero</td><td>Detalle</td><td>Tercero</td></tr>
<?
	if($Numero){$CondAdc=" and Numero like '%$Numero%'";}
	$cons="Select Fecha,Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,sum(Debe),sum(Haber),Terceros.Identificacion,Estado,NoCheque
	from Contabilidad.Movimiento,Central.Terceros 
	where Movimiento.Identificacion=Terceros.Identificacion and date_part('year',Fecha)=$AnioI and date_part('month',Fecha)=$MesI and Comprobante='$Comprobante' and Estado='AC'
	and Terceros.Compania='$Compania[0]'
	$CondAdc
	and Movimiento.Compania='$Compania[0]'
	Group By Fecha,Numero,Detalle,PrimApe,SegApe,PrimNom,SegNom,Terceros.Identificacion,Estado,NoCheque
	Order By Numero Desc";

	$res=ExQuery($cons,$conex);echo ExError();
	while($fila=ExFetch($res))
	{
		echo "<tr><td><input type='radio' name='Opc[$fila[1]]' value='$fila[1]'></td><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3] $fila[4] $fila[5] $fila[6]</td></tr>";
	}
?>
</table>
<input type="hidden" name="Anio" value="<? echo $Anio?>">
<input type="submit" name="Registrar" value="Registrar">
</form>
</body>
</html>

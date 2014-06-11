<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$FechaIni="$Anio-$MesIni-$DiaIni";
	$FechaFin="$Anio-$MesFin-$DiaFin";
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$FechaIni);
	$VrEntradas=Entradas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,$FechaIni,$FechaFin);
    $Devoluciones=Devoluciones($Anio,$AlmacenPpal,$FechaIni,$FechaFin);

	$ND=getdate();
	if($TipoPro){$conTipoPro = " and TipoProducto = '$TipoPro'";}
	if($GrupoPro){$conGrupoPro = "and Grupo = '$GrupoPro'";}
	if($Bodega || $Bodega=="0"){$conBodega = "and Bodega = '$Bodega'";}
	if($Estante || $Estante=="0"){$conEstante = "and Estante = '$Estante'";}
	if($Nivel || $Nivel=="0"){$conNivel = "and Nivel = '$Nivel'";}
	if($Estado){ $conEstado = " and Estado = '$Estado'";}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>1</title>
</head>

<style>
P{PAGE-BREAK-AFTER: always;}
</style>
<body>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table border="0" style='font : normal normal small-caps 11px Tahoma;' align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Tipo de Producto:</td>
        <td><select name="TipoPro" onChange="FORMA.submit()"><option value=""></option>
        	<?
            	$cons = "Select TipoProducto from Consumo.TiposProducto where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($TipoPro == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
        <td>Grupo de Producto:</td>
        <td><select name="GrupoPro" onChange="FORMA.submit()"><option value=""></option>
        	<?
            	$cons = "Select Grupo from Consumo.Grupos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $Anio
				order by Grupo";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($GrupoPro == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
        <td>Bodega:</td>
        <td><select name="Bodega" onChange="FORMA.submit()"><option value=""></option>
        	<?
            	$cons = "Select Bodega from Consumo.Bodegas where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Bodega == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
        <td>Estante:</td>
        <td><select name="Estante" onChange="FORMA.submit()"><option value=""></option>
        	<?
            	$cons = "Select distinct(Estante) from Consumo.CodProductos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $Anio";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Estante == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
        <td>Nivel:</td>
        <td><select name="Nivel" onChange="FORMA.submit()"><option value=""></option>
        	<?
            	$cons = "Select distinct(Nivel) from Consumo.CodProductos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $Anio";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Nivel == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
        <td>Estado:</td>
        <td><select name="Estado" onChange="FORMA.submit()"><option value=""></option>
        	<option <? if($Estado=="AC"){ echo " selected ";}?> value="AC">Activo</option>
            <option <? if($Estado=="IN"){ echo " selected ";}?> value="IN">Inactivo</option>
        </select></td>
    </tr>
</table>
<?
	function Encabezados()
	{
		global $Compania;global $Fecha;global $NumPag;global $TotPaginas;global $ND;
		?>
		<table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
		<tr><td colspan="11"><center><strong><? echo strtoupper($Compania[0])?><br>
		<? echo $Compania[1]?><br>LISTADO DE PRODUCTOS - <? echo $AlmacenPpal?><br>A&ntilde;o: <? echo $ND[year]?></td></tr>
		<tr><td colspan="11" align="right">Fecha de Impresi&oacute;n <? echo "$ND[year]-$ND[mon]-$ND[mday]"?></td></tr></td>
		</tr></strong></center>

<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<td rowspan="2">Codigo</td><td rowspan="2">Nombre</td><td rowspan="2">Tipo de Producto</td><td rowspan="2">Grupo</td><td colspan="3">Localizaci&oacute;n</td>
<td colspan="2">Saldo Corte</td></tr>
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
<td>Bodega</td><td>Estante</td><td>Nivel</td><td>Saldo</td><td>Valor</td></tr>

<?	}
	Encabezados();
	$cons = "Select AutoId,Codigo1,NombreProd1,UnidadMedida,Presentacion,TipoProducto,Grupo,Bodega,Estante,Nivel 
	from Consumo.CodProductos where Compania = '$Compania[0]' and Anio = $Anio
	and AlmacenPpal='$AlmacenPpal' $conTipoPro $conGrupoPro $conBodega $conEstante $conNivel $conEstado order by AutoId";
	//echo $cons;
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$CantFinal=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0]+$Devoluciones[$fila[0]][0];
		$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1]+$Devoluciones[$fila[0]][1];
		if($NumRec>=$Encabezados)
		{
			echo "</table><P>&nbsp;</P>";
			$NumPag++;
			Encabezados();
			$NumRec=0;
		}
		?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
		echo "<td align='center'>$fila[1]</td><td>$fila[2] $fila[3] $fila[4]</td><td>$fila[5]</td>
		<td>$fila[6]</td><td align='center'>$fila[7]</td><td align='center'>$fila[8]</td><td align='center'>$fila[9]</td>
		<td align='right'>".number_format($CantFinal,2)."</td>
		<td align='right'>".number_format($SaldoFinal,2)."</td></tr>";
		$TotalCantidad = $TotalCantidad + $CantFinal;
		$TotalSaldo = $TotalSaldo + $SaldoFinal;
		$NumRec++;
	}
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold">
	<td colspan="7" align="right">TOTALES</td>
    <td align="right"><? echo number_format($TotalCantidad,2)?></td>
    <td align="right"><? echo number_format($TotalSaldo,2)?></td>
</tr>
</table>
</form>
</body>
</html>
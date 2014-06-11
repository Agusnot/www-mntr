<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	include("ObtenerSaldos.php");
	$ND=getdate();
	$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-$ND[mon]-$ND[mday]");
	$AnioA=$Anio-1;
	if($Guardar)
	{
		while( list($cad,$val) = each($Cant))
		{
			$Values="";
			while( list($cad1,$val1) = each($val))
			{
				$Values=$Values."'$val1',";
			}
			$Values=substr($Values, 0, -1);
			//echo $Values."</br>";
			if(!$Editar)
			{
				$cons="Insert into Consumo.PlanCompras (Compania,AlmacenPpal,CentroCostos,AutoId,Anio,Ene,Feb,Mar,Abr,May,Jun,Jul,Ago,Sep,Oct,Nov,Dic,Promedio)
				values ('$Compania[0]','$AlmacenPpal','$CC','$cad','$Anio',".$Values.",$Precio[$cad])";
				//echo $cons;	
			}
			else
			{
				$Datos=explode(",","$Values");
				$cons="Update Consumo.PlanCompras set Ene=$Datos[0],Feb=$Datos[1],Mar=$Datos[2],Abr=$Datos[3],May=$Datos[4],Jun=$Datos[5],
				Jul=$Datos[6],Ago=$Datos[7],Sep=$Datos[8],Oct=$Datos[9],Nov=$Datos[10],Dic=$Datos[11],Promedio='$Precio[$cad]' where
				Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and CentroCostos='$CC' and AutoId='$cad' and Anio='$Anio'";
				//echo $cons."<br>";
			}
			$res=ExQuery($cons);
		}
		?><script language="javascript">location.href="PAC.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&CC=<? echo $CC?>&Anio=<? echo $Anio?>";</script><?
	}
?>
<script language='javascript' src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width="50%"> 
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<? 
		$cons = "Select CentroCostos from Central.CentrosCosto where Compania='$Compania[0]' and Codigo='$CC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		echo "<td>$AlmacenPpal</td><td>$CC-$fila[0]</td><td>A&ntilde;o: $Anio</td>";
		?>
    </tr>
    
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width="100%">
	<tr bgcolor='#e5e5e5' style='font-weight=bold;'><td>Producto</td>
    	<td>Ene</td><td>Feb</td><td>Mar</td><td>Abr</td><td>May</td><td>Jun</td>
		<td>Jul</td><td>Ago</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dic</td><td>Precio Promedio</td>
    </tr>
<form name='FORMA' method='post'>
<?		
	if(!$Editar)
	{
		$cons="SELECT Movimiento.AutoId, date_part('month',Movimiento.Fecha ), sum( Movimiento.Cantidad ),NombreProd1,UnidadMedida,Presentacion
		FROM Consumo.Movimiento, Consumo.CodProductos 
		WHERE TipoComprobante = 'Salidas' AND Movimiento.Estado = 'AC' AND Movimiento.AutoId = CodProductos.AutoId AND CentroCosto = '$CC'
		AND Movimiento.Compania='$Compania[0]' AND date_part('year',Movimiento.Fecha)=$AnioA AND Movimiento.AlmacenPpal = '$AlmacenPpal'
		GROUP BY Movimiento.AutoId,date_part('month',Movimiento.Fecha),NombreProd1,UnidadMedida,Presentacion";
	}
	else
	{
		$cons="Select AutoId,Ene,Feb,Mar,Abr,May,Jun,Jul,Ago,Sep,Oct,Nov,Dic,Promedio
		from Consumo.PlanCompras where Compania='$Compania[0]' and CentroCostos='$CC' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio";	
	}
	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		if(!$Editar){$Producto[$fila[0]][$fila[1]]=$fila[2];}
		else
		{
			for($i=1;$i<=12;$i++){$Producto[$fila[0]][$i]=$fila[$i];}
			$Precio[$fila[0]]=$fila[13];
		}
	}
	$cons="Select AutoId,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos
	where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado='AC' group by AutoId,NombreProd1,UnidadMedida,Presentacion order by NombreProd1";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CantFinal=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0];
		$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1];
		if($CantFinal>0)
		{
			$CostoUnidad=$SaldoFinal/$CantFinal;	
		}
		echo "<tr><td>$fila[1] $fila[2] $fila[3]</td>";
		for($i=1;$i<=12;$i++)
		{
			?><td align="center"><input type="text" name="Cant[<? echo $fila[0]?>][<? echo $i?>]" value="<? echo $Producto[$fila[0]][$i];?>" size="3"
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td><?	
		}
		?><td align="center"><input type="text" name="Precio[<? echo $fila[0]?>]" 
        	value="<? 
			if(!$Editar){echo number_format($CostoUnidad, 2, '.', '');}
			else{echo number_format($Precio[$fila[0]],2, '.', '');}?>" size="5" style="text-align:right;" 
            onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td></tr><?
    }
	echo "</table>";
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="Hidden" name="Editar" value="<? echo $Editar?>"  />
<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="CC" value="<? echo $CC?>" />
<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='PAC.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&CC=<? echo $CC?>&Anio=<? echo $Anio?>'" />
</form>
</body>

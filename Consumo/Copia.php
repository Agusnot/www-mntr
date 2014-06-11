<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("ObtenerSaldos.php");
	$ND=getdate();
	$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-$ND[mon]-$ND[mday]");
?>
<body background="/Imgs/Fondo.jpg">
 <form name="FORMA" method="post">
 <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
  <table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5">
   <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Almacen Principal</td><td>Centro de Costos</td><td>A&ntilde;o</td>
   </tr>
   <tr>
    <td><select name="AlmacenPpal" onChange="document.FORMA.submit();">
<?
			$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
    </select></td>
    <td><select name="CC">
    <?
    	$cons = "Select Codigo,CentroCostos from Central.CentrosCosto where Compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$CC){ echo "<option selected value='$fila[0]'>$fila[0] - $fila[1]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0] - $fila[1]</option>";}
		}
	?>
    </select></td>
    <td><select name="Anio">
    <option value="<? echo $ND[year]+1?>"><? echo $ND[year]+1?></option>
<?
				$cons = "Select Anio from Central.Anios Order By Anio Desc";
				$res = ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$Anio){echo "<option selected value='$fila[0]'>$fila[0]</option>'";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>'";}
				}
?>
    </select></td>
    <td><input type="submit" name="Ver" value="Ver" /></td>
   </tr>
  </table>
  </form>
  <?
  	if($Ver)
	{
		$AnioA=$Anio-1;
		echo "<form name='FORMA2' method='post'><table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
		<tr bgcolor='#e5e5e5' style='font-weight=bold;'><td>Producto</td><td>Ene</td><td>Feb</td><td>Mar</td><td>Abr</td><td>May</td><td>Jun</td>
		<td>Jul</td><td>Ago</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dic</td><td>Precio Promedio</td></tr>";
		$cons="SELECT Movimiento.AutoId, month( Movimiento.Fecha ), sum( Movimiento.Cantidad ),NombreProd1,UnidadMedida,Presentacion
				FROM Consumo.Movimiento, Consumo.CodProductos
				WHERE TipoComprobante = 'Salidas'
				AND Movimiento.Estado = 'AC'
				AND Movimiento.AutoId = CodProductos.AutoId
				AND CentroCosto = '000'
				AND Movimiento.Compania='$Compania[0]'
				AND year(Movimiento.Fecha)='$AnioA'
				AND Movimiento.AlmacenPpal = 'Almacen Consumo'
				GROUP BY Movimiento.AutoId,month(Movimiento.Fecha)";
		//echo $cons;
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Producto[$fila[0]][$fila[1]]=$fila[2];
			//echo "Producto[$fila[0]][$fila[1]]=".$Producto[$fila[0]][$fila[1]]=$fila[2]."</br>";
		}
		$cons="Select AutoId,NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos
				where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado='AC'
				group by AutoId";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$CantFinal=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0];
			$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1];
			$CostoUnidad=$SaldoFinal/$CantFinal;
			echo "<tr><td>$fila[1] $fila[2] $fila[3]</td>";
			for($i=1;$i<=12;$i++)
			{
				?><td><input type="text" name="Cant[<? echo $fila[0]?>][<? echo $i?>]" value="<? echo $Producto[$fila[0]][$i];?>" size="3"></td><?	
			}
			?><td><input type="text" name="Precio[<? echo $fila[0]?>]" value="<? echo number_format($CostoUnidad,2)?>"
            		style="text-align:right; width:100%" ></td></tr><?
        }
		echo "</table>";
	}
  ?>
 <input type="submit" name="Guardar" value="Guardar">
 </form>
</body>

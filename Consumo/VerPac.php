<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
?>
<body background="/Imgs/Fondo.jpg">
<?
	if($AlmacenPpal && $CC && $Anio)
	{
		$cons="Select AutoId,NombreProd1,UnidadMedida,Presentacion,Ene,Feb,Mar,Abr,May,Jun,Jul,Ago,Sep,Oct,Nov,Dic,Promedio
				from Consumo.PlanCompras NATURAL JOIN Consumo.CodProductos 
				where Compania='$Compania[0]' and CentroCostos='$CC' and AlmacenPpal='$AlmacenPpal' and Anio='$Anio' order by NombreProd1";
		//echo $cons;
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			echo "<em><font color='red'>No existen registros</font></em>";
			?>
			<form name="FORMA" method="post" action="NewPac.php" target="_parent">
            	<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
				<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    			<input type="Hidden" name="CC" value="<? echo $CC?>" />
    			<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
    			<input type="submit" name="NewPAC" value="Generar Plan de Compras" />
			</form>	
			<?
			
		}
		else
		{
			?>
			<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width="100%">
				<tr align="center" bgcolor='#e5e5e5' style='font-weight=bold;'>
                	<td>Producto</td><td>Ene</td><td>Feb</td><td>Mar</td><td>Abr</td><td>May</td><td>Jun</td>
					<td>Jul</td><td>Ago</td><td>Sep</td><td>Oct</td><td>Nov</td><td>Dic</td><td>Precio Promedio</td></tr>
            <?
			while($fila=ExFetch($res))
			{
				echo "<tr><td>$fila[1] $fila[2] $fila[3]</td>
				<td align='right'>".number_format($fila[4],2)."</td><td align='right'>".number_format($fila[5],2)."</td><td align='right'>".number_format($fila[6],2)."</td>
				<td align='right'>".number_format($fila[7],2)."</td><td align='right'>".number_format($fila[8],2)."</td><td align='right'>".number_format($fila[9],2)."</td>
				<td align='right'>".number_format($fila[10],2)."</td><td align='right'>".number_format($fila[11],2)."</td><td align='right'>".number_format($fila[12],2)."</td>
				<td align='right'>".number_format($fila[13],2)."</td><td align='right'>".number_format($fila[14],2)."</td><td align='right'>".number_format($fila[15],2)."</td>
				<td align='right'>".number_format($fila[16],2)."</td></tr>";	
			}
			?>
            </table>
			<form name="FORMA" method="post" action="NewPac.php?Editar=1" target="_parent">
				<input type="Hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    			<input type="Hidden" name="CC" value="<? echo $CC?>" />
    			<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
                <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    			<input type="submit" name="NewPAC" value="Editar Plan de Compras" />
			</form>
			<? 
		}
	}
?></body>
	
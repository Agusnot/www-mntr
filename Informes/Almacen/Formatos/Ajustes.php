<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo $NomSistema[$NoSistema]?></title>
</head>


<body background="/Imgs/Fondo.jpg">
<font style='font : normal normal small-caps 11px Tahoma;'>
<strong>
<font size="+1">
Informe de Ajustes de Inventario</strong><br></font>
<?
echo "$Compania[0]<br>$Compania[1]<br>$Compania[2] - $Compania[3]";
?>
</font>
<br><br><br>
<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 11px Tahoma;'>

<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="8" align="center"><? echo "$NomInventario - $usuario[0]"?></td></tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Cod</td><td>Producto</td><td>Exist</td><td>Cont 1</td><td>Cont 2</td><td>Cont Def</td><td>Dif</td><td>Costo</td></tr>
<?
	$cons="Select Codigo1,NombreProd1,UnidadMedida,Presentacion,CodProductos.AutoId,Existencias,Cont1,Cont2,ContDef,VrCosto,Diferencia,TotCostoDif 
	from Consumo.CodProductos,Consumo.Inventarios where CodProductos.AutoId=Inventarios.AutoId
	and CodProductos.Compania='$Compania[0]' and Inventarios.Compania='$Compania[0]' and CodProductos.AlmacenPpal='$AlmacenPpal' and Inventarios.AlmacenPpal='$AlmacenPpal'
	and NomInventario='$NomInventario' and Consumo.CodProductos.anio='$Anio'
	GROUP BY Codigo1,NombreProd1,UnidadMedida,Presentacion,CodProductos.AutoId,Existencias,Cont1,Cont2,ContDef,VrCosto,Diferencia,TotCostoDif
	Order By NombreProd1,UnidadMedida,Presentacion";
//echo"$cons";



	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{

		$VrCosto=$fila[9];$CantExistencias=$fila[5];$Conteo1=$fila[6];$Conteo2=$fila[7];$Conteo3=$fila[8];$Diferencia=$fila[10];$TotCosto=$fila[11];
		?>
<?		echo "<tr><td>$fila[0]</td><td>$fila[1] $fila[2] $fila[3]</td><td align='right'>".number_format($CantExistencias,2)."</td>
		<td align='right'>".number_format($Conteo1,2)."</td>
		<td align='right'>".number_format($Conteo2,2)."</td><td align='right'>".number_format($Conteo3,2)."</td>";
		echo "<td align='right'>".number_format($Diferencia,2)."</td>
		<td align='right'>".number_format($TotCosto,2)."</td>
		</tr>";
		$CantDif=$CantDif+$fila[10];$TotDif=$TotDif+$fila[11];
		$CantDifAbs=$CantDifAbs+abs($fila[10]);$TotDifAbs=$TotDifAbs+abs($fila[11]);
	}
?>
<tr><td colspan="6"></td><td colspan="2">
<hr />
</td></tr>
<tr style="font-weight:bold"><td  colspan="2"></td>
<td bgcolor="#e5e5e5"  colspan="4" align="right">Total Ajuste (Rel)</td><td bgcolor="#e5e5e5"  align="right"><? echo number_format($CantDif,2)?></td><td bgcolor="#e5e5e5"  align="right"><? echo number_format($TotDif,2)?></td></tr>

<tr style="font-weight:bold"><td  colspan="2"></td>
<td bgcolor="#e5e5e5"  colspan="4" align="right">Total Ajuste (Abs)</td><td bgcolor="#e5e5e5"  align="right"><? echo number_format($CantDifAbs,2)?></td><td bgcolor="#e5e5e5"  align="right"><? echo number_format($TotDifAbs,2)?></td></tr>

</table>
<br>

<br>
<center><br><br>
<table border="1" bordercolor="white" style='font : normal normal small-caps 11px Tahoma;'>
<tr><td>______________________________________</td><td style="width:150px;"></td><td>______________________________________</td></tr>
<tr align="center"><td>Firma Responsable Almacen</td><td></td><td>Firma Financiero</td></tr>

</table>
</body>
</html>

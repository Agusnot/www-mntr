<?
	//echo $Anio;
        if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("Consumo/ObtenerSaldos.php");
	$ND=getdate();
        //UM:27-04-2011
	$Fecha="$ND[year]-$ND[mon]-$ND[mday]";//$Anio=$ND[year];
	$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01");
	$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
	$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-$ND[mon]-01",$Fecha);
        $VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
?>
<body background="/Imgs/Fondo.jpg">
<style>
	a{color:black; text-decoration:none;}
	a:hover{color:blue; text-decoration:underline;}
	
</style>
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;'>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
<td>Codigo</td><td>Nombre</td><td>Nombre 2</td><td>Saldo Actual</td></tr>

<?
	$cons="Select Codigo1,NombreProd1,NombreProd2,AutoId,Presentacion,UnidadMedida 
	from Consumo.CodProductos 
	where (NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '$NomProducto%' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Estado='AC'
	and Anio = $Anio and AutoId not in(Select AutoId from Consumo.TMPSolicitudConsumo where TMPCOD = '$TMPCOD')
	Order by NombreProd1";
        //echo $cons;
        $res=ExQuery($cons);echo ExError();
	while($fila=ExFetch($res))
	{
		$CantExistencias=$VrSaldoIni[$fila[3]][0]+$VrEntradas[$fila[3]][0]-$VrSalidas[$fila[3]][0]+$VrDevoluciones[$fila[3]][0];
		echo "<tr><td>$fila[0]</td><td>"?>
		<a onclick="parent.document.FORMA.Producto.value='<? echo "$fila[1] $fila[4] $fila[5]"?>'; 
		parent.document.FORMA.AutoId.value='<? echo $fila[3];?>';
		parent.document.FORMA.Codigo.value='<? echo $fila[0];?>';
		parent.document.FORMA.Cantidad.focus();
		" href="#">
		<? echo "$fila[1] $fila[4] $fila[5]</a></td><td>$fila[2]</td><td align='right'>".number_format($CantExistencias,2)."</a></td>";
	}
?>
</table>
</body>


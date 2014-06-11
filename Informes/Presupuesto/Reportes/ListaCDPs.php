<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
mysql_select_db("Presupuesto", $conex);
?>
<table border="1">
<tr><td>Fecha</td><td>Numero</td><td>Tercero</td><td>Valor</td><td>Concepto</td>
<?
	$cons="Select Fecha,Numero,Identificacion,PrimApe,SegApe,PrimNom,SegNom,Credito,Detalle from Movimiento,TiposComprobante,Central.Terceros
	where Movimiento.Identificacion=Terceros.Identificacion and Movimiento.Comprobante=TiposComprobante.Comprobante and Compania='$Compania[0]'
	and Estado='AC' and Fecha>='$PerIni' and Fecha<='$PerFin'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2] - $fila[3] $fila[4] $fila[5] $fila[6]</td><td>$fila[7]</td><td>$fila[8]</td></tr>";
	}

?>
</table>
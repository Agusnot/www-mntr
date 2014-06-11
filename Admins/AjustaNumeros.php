<?
include("Funciones.php");
$NoNuevo=20684;

$cons99="Select Numero from Contabilidad.Movimiento where Comprobante='Consignacion bancaria' and Anio=2012 
Group By Numero,fechaCre Order By fechaCre";
$res99=ExQuery($cons99);
while($fila99=ExFetch($res99))
{
	$cons="Update Contabilidad.Movimiento set Numero='$NoNuevo' where Comprobante='Consignacion bancaria'
	and Numero='$fila99[0]' and Anio=2012";
	echo $cons."<br>";
	$res=ExQuery($cons);
	$NoNuevo++;
}
?>
<?
	include("Funciones.php");
	$cons="Select sum(Cantidad),AutoId from Consumo.Movimiento where TipoComprobante='Devoluciones' and Estado='AC' and Anio=2011 Group By AutoId";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="Update consumo.saldosinicialesxanio set Cantidad=Cantidad+$fila[0] where AutoId=$fila[1] and Anio=2012";
		$res2=ExQuery($cons2);

		echo $fila[1]."--->".$fila[0]."<br>";
	}
echo "Finalizado!!";
?>
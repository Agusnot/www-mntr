<?
	include("Funciones.php");
	$cons="SELECT (A.cantidad*A.vrcosto)AS TOTAL, A.idregistro,B.vrcosto FROM consumo.movimiento A
INNER JOIN consumo.movimiento B ON B.autoid=A.autoid AND A.nodocafectado=B.numero 
WHERE A.fecha>='2012-05-09'
AND A.tipocomprobante!='Entradas' AND A.tipocomprobante!='Ordenes de Compra' AND A.tipocomprobante!='Orden de Compra' 
GROUP BY A.cantidad,A.vrcosto, A.idregistro,A.fecha, A.tipocomprobante, A.vrcosto, B.vrcosto, B.numero,A.nodocafectado, B.tipocomprobante";
	
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{
			$cons3="Update Consumo.Movimiento set totcosto='$fila[0]'
			where idregistro='$fila[1]'";
			echo $cons3."<br>";
			$res3=ExQuery($cons3);
	}
?>
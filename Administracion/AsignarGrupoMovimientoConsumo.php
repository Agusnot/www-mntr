<?
	include("Funciones.php");
	$cons = "Select Anio,Compania,AlmacenPpal,AutoId,Grupo from Consumo.CodProductos";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Grupo[$fila[0]][$fila[1]][$fila[2]][$fila[3]]=$fila[4];	
	}
	$cons = "Select Anio,Compania,AlmacenPpal,AutoId From Consumo.Movimiento Group by Compania,AlmacenPpal,Anio,AutoId";
	$res = ExQuery($cons);
	while($fila=ExFetch($res))
	{
		//echo $Grupo[$fila[0]][$fila[1]][$fila[2]][$fila[3]];
		$cons1 = "Update Consumo.Movimiento set Grupo='".$Grupo[$fila[0]][$fila[1]][$fila[2]][$fila[3]]."'
		Where Anio=$fila[0] and Compania='$fila[1]' and AlmacenPpal='$fila[2]' and AutoId=$fila[3]";
		echo $cons1." Exitosa!!<br>";
		$res1=ExQuery($cons1);
	}
?>

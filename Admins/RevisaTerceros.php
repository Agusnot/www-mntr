<?
	include("Funciones.php");
	$cons="Select Movimiento.Identificacion,Terceros.Identificacion,Comprobante,Numero,Fecha,Movimiento.Compania from Contabilidad.Movimiento
	left join Central.Terceros ON Movimiento.Identificacion=Terceros.Identificacion 
	and Movimiento.Compania=Terceros.Compania
	where Terceros.Identificacion IS NULL and Estado='AC' 
	Group By Movimiento.Identificacion,Terceros.Identificacion,Comprobante,Numero,Fecha,Movimiento.Compania Order By Fecha";
	
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{
		$cons2="Select Identificacion from Central.Terceros where Identificacion like '$fila[0]%' and Compania='$fila[5]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		
		if(ExNumRows($res2)>0){
			$cons3="Update Contabilidad.Movimiento set Identificacion='$fila2[0]'
			where Comprobante='$fila[2]' and Numero='$fila[3]' and Identificacion='$fila[0]'";
			echo $cons3."<br>";
			$res3=ExQuery($cons3);}
	}
?>
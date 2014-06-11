<?
	include("Funciones.php");
	$cons="SELECT codsecretaria.codsecre, codsecretaria.codprod  from consumo.codsecretaria, consumo.codproductos where codproductos.codigo2=codsecretaria.codprod";
	
	$res=ExQuery($cons);
	
	while($fila=ExFetch($res))
	{
			$cons3="Update consumo.codproductos set codsecretaria='$fila[0]'
			where codproductos.codigo2='$fila[1]'";
			echo $cons3."<br>";
			$res3=ExQuery($cons3);
	}
?>
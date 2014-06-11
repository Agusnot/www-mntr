<html>
<?php
include("Funciones.php");
$cons1="select count (*) from facturacion.detallefactura where Tipo like '%Medicamentos%' and forma='' and presentacion=''";
$res1=ExQuery($cons1);	
$fila1=ExFetch($res1);
for($i=0;$i<=$fila1[0];$i++){
	$cons2="select nombre,cantidad,vrunidad,vrtotal,forma,presentacion from facturacion.detallefactura where Tipo like '%Medicamentos%' and forma='' and presentacion='' limit 1";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
		$cons3="select (nombreprod1||' '||unidadmedida||' '||presentacion),nombreprod1,unidadmedida,presentacion from consumo.codproductos";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3)){
			if("$fila2[0]"=="$fila3[0]"){
				$cons4="update facturacion.detallefactura set nombre='$fila3[1]', forma='$fila3[3]', presentacion='$fila3[2]' where nombre='$fila3[0]'";
				$res4=ExQuery($cons4);
			}
		}
	}
}
echo"Actualizados todos los campos posibles!";
?>
</html>
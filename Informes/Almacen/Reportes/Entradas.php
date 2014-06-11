<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
?>
<form name="FORMA" method="post">

    <?
	$cons ="SELECT fecha, numero, consumo.movimiento.cedula, central.terceros.primape,central.terceros.segape, central.terceros.primnom, central.terceros.segnom, 
			salud.pacientesxpabellones.pabellon, consumo.movimiento.autoid, consumo.codproductos.nombreprod1, consumo.codproductos.unidadmedida, consumo.codproductos.presentacion, 
			cantidad, motivodevolucion
			  FROM consumo.movimiento inner join central.terceros on consumo.movimiento.cedula=central.terceros.identificacion
			  inner join salud.pacientesxpabellones on consumo.movimiento.cedula=salud.pacientesxpabellones.cedula
			  inner join consumo.codproductos on consumo.movimiento.autoid=consumo.codproductos.autoid
			  where comprobante='Devoluciones' and fecha between '$Anio-$MesIni-$DiaIni' and '$Anio-$MesFin-$DiaFin' and consumo.movimiento.estado='AC' 
			  and consumo.codproductos.almacenppal=consumo.movimiento.almacenppal and consumo.codproductos.almacenppal='$AlmacenPpal' 
			  and consumo.codproductos.anio='$Anio' and salud.pacientesxpabellones.estado='AC' 
			  order by motivodevolucion";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>No.</td>
			<td>Fecha</td>
            <td>Numero</td>
            <td>Cedula</td>
            <td>Nombre</td>
            <td>Servicio</td>
            <td>Autoid</td>
            <td>Producto</td>
            <td>Medida</td>
            <td>Presentacion</td>
            <td>Cantidad</td>
			<td>Motivo Devolucion</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?><td><?echo $fila[0]?></td><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td>
                    <td><?echo $fila[3]." ".$fila[4]." ".$fila[5]." ".$fila[6]?></td><td><?echo $fila[7]?></td><td><?echo $fila[8]?></td>
                    <td><?echo $fila[9]?></td><td><?echo $fila[10]?></td><td><?echo $fila[11]?></td><td><?echo $fila[12]?></td>
                    <td><?echo $fila[13]?></td>
                </tr><?
    $count++;}
    ?>
</table>
</form>
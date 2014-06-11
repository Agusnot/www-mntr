<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../Funciones.php");
$cn=getdate();
?>
<form name="FORMA" method="post">

    <?
	$cons ="SELECT consumo.codproductos.codigo1, consumo.codproductos.codigo2, consumo.codproductos.nombreprod1, consumo.codproductos.presentacion, consumo.codproductos.unidadmedida, lote, vence, 
consumo.lotes.laboratorio, consumo.lotes.reginvima, consumo.lotes.presentacion, (consumo.lotes.cantidad-consumo.lotes.salidas), consumo.cumsxproducto.cum 
FROM consumo.lotes 
INNER JOIN consumo.codproductos ON consumo.lotes.autoid=consumo.codproductos.autoid 
INNER JOIN consumo.cumsxproducto on consumo.lotes.reginvima=consumo.cumsxproducto.reginvima and consumo.lotes.presentacion=consumo.cumsxproducto.presentacion 
WHERE consumo.codproductos.anio='$cn[year]' AND consumo.codproductos.almacenppal='FARMACIA'
AND consumo.lotes.almacenppal='FARMACIA' and consumo.lotes.cantidad!=consumo.lotes.salidas 
AND consumo.codproductos.grupo like 'Medicame%'
group by consumo.codproductos.codigo1, consumo.codproductos.codigo2, consumo.codproductos.nombreprod1, consumo.codproductos.presentacion, consumo.codproductos.unidadmedida, lote, vence, 
consumo.lotes.laboratorio, consumo.lotes.reginvima, consumo.lotes.presentacion, consumo.lotes.cantidad, consumo.lotes.salidas, consumo.cumsxproducto.cum 
ORDER BY nombreprod1 asc";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
			<td>No.</td>
			<td>C&Oacute;DIGO</td>
			<td>ATC</td>
            <td>NOMBRE PRODUCTO</td>
			<td>FORMA FARMAC&Eacute;UTICA</td>
			<td>UNIDAD DE MEDIDA</td>
            <td>LOTE</td>
			<td>FECHA DE VENCIMIENTO</td>
			<td>LABORATORIO</td>
			<td>REG. INVIMA</td>
			<td>PRESENTACI&Oacute;N</td>
			<td>CANTIDAD</td>
			<td>CUM</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?></td><td><?echo $fila[0]?></td><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td>
                    <td><?echo $fila[3]?></td><td><?echo $fila[4]?></td><td><?echo $fila[5]?></td>
                    <td><?echo $fila[6]?></td><td><?echo $fila[7]?></td><td><?echo $fila[8]?></td>
					<td><?echo $fila[9]?></td><td><?echo $fila[10]?></td><td><?echo $fila[11]?></td>
                </tr><?
    $count++;}
    ?>
</table>
</form>
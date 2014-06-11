<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../Funciones.php");
?>
<form name="FORMA" method="post">

    <?
	$cons ="SELECT count (salud.ordenesmedicas.cedula), salud.ordenesmedicas.cedula,central.terceros.primape, central.terceros.segape, central.terceros.primnom, central.terceros.segnom, salud.pacientesxpabellones.pabellon
FROM salud.ordenesmedicas
INNER JOIN central.terceros ON salud.ordenesmedicas.cedula=central.terceros.identificacion
INNER JOIN salud.pacientesxpabellones ON salud.ordenesmedicas.cedula=salud.pacientesxpabellones.cedula
WHERE salud.ordenesmedicas.estado='AC' and salud.ordenesmedicas.tipoorden='Medicamento Programado' and salud.pacientesxpabellones.estado='AC'
GROUP BY salud.ordenesmedicas.cedula,central.terceros.primape, central.terceros.segape, central.terceros.primnom, central.terceros.segnom, salud.pacientesxpabellones.pabellon
ORDER BY --central.terceros.primape
count (salud.ordenesmedicas.cedula)";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>No.</td>
			<td width="10">Cantidad Medicamentos</td>
			<td>Cedula</td>
            <td>Nombre</td>
			<td>Servicio</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?></td><td><?echo $fila[0]?></td><td><?echo $fila[1]?>
					<td><?echo $fila[2]." ".$fila[3]." ".$fila[4]." ".$fila[5]?></td><td><?echo $fila[6]?></td>
                </tr><?
    $count++;}
    ?>
</table>
</form>
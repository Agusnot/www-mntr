<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../Funciones.php");
?>
<form name="FORMA" method="post">

    <?
	$cons ="SELECT salud.ordenesmedicas.cedula, central.terceros.primape, central.terceros.segape, central.terceros.primnom, central.terceros.segnom,
    salud.pacientesxpabellones.pabellon, salud.ordenesmedicas.detalle, salud.ordenesmedicas.posologia
  FROM salud.ordenesmedicas
  INNER JOIN salud.pacientesxpabellones ON salud.ordenesmedicas.cedula=salud.pacientesxpabellones.cedula
  INNER JOIN central.terceros ON salud.ordenesmedicas.cedula=central.terceros.identificacion
  WHERE posologia like '%.%' 
    AND salud.ordenesmedicas.estado= 'AC' 
       AND tipoorden = 'Medicamento Programado' 
       AND salud.pacientesxpabellones.estado='AC'
       order by salud.ordenesmedicas.detalle";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>No.</td>
			<td>Cedula</td>
            <td>Nombre</td>
			<td>Servicio</td>
			<td>Detalle</td>
            <td>Posolog&iacute;a</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?><td><?echo $fila[0]?></td><td><?echo $fila[1]." ".$fila[2]." ".$fila[3]." ".$fila[4]?></td><td><?echo $fila[5]?></td>
                    <td><?echo $fila[6]?></td><td><?echo $fila[7]?></td><td><?echo $fila[8]?></td>
                    <td><?echo $fila[9]?></td>
                </tr><?
    $count++;}
    ?>
</table>
</form>
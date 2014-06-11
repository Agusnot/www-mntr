<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../../Funciones.php");

	$cons ="select entidad, nomresppago, contrato, tipoasegurador, numero, fechaini, fechafin, monto, mttoejecutado, porcentajeejecutado, porcentajedias, estado  from contratacionsalud.contratos 
	inner join central.terceros on contratacionsalud.contratos.entidad=central.terceros.identificacion	where porcentajedias between '80.0' and '100' and estado='AC'";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
            <td>No.</td>
			<td>Entidad</td>
            <td>Contrato</td>
			<td>N&uacute;mero Contrato</td>
			<td>Tipo Asegurador</td>
			<td>Fecha Inicio</td>
            <td>Fecha Final</td>
			<td>Monto</td>
			<td>Monto Ejecutado</td>
			<td>% Ejecutado</td>
			<td>% Dias >80</td>
			<td>Estado</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             ?><tr>
                    <td><?echo $count?><td><?echo $fila[1]?></td><td><?echo $fila[2]?></td><td><?echo $fila[4]?></td>
                    <td><?echo $fila[3]?></td><td><?echo $fila[5]?></td><td><?echo $fila[6]?></td>
					<td><?echo number_format($fila[7],2)?></td>
                    <td><?echo number_format($fila[8],2)?></td><td><?echo $fila[9]?></td></td><td><?echo $fila[10]?></td><td><?echo $fila[11]?></td>
                </tr><?
    $count++;}
    ?>
</table>
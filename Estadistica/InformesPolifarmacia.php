<?	
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("../Funciones.php");
?><title>Informe Polifarmacia Detallado</title>
<form name="FORMA" method="post">

    <?
	$cons ="SELECT
	salud.ordenesmedicas.cedula,
	central.terceros.primape,
	central.terceros.segape,
	central.terceros.primnom,
	central.terceros.segnom,
	salud.pacientesxpabellones.pabellon,
	salud.ordenesmedicas.detalle,
	salud.ordenesmedicas.posologia
FROM
	salud.ordenesmedicas
	INNER JOIN central.terceros
	ON salud.ordenesmedicas.cedula=central.terceros.identificacion
	INNER JOIN salud.pacientesxpabellones
	ON salud.ordenesmedicas.cedula=salud.pacientesxpabellones.cedula
	WHERE salud.ordenesmedicas.estado='AC'
	AND salud.ordenesmedicas.tipoorden='Medicamento Programado' 
	AND salud.pacientesxpabellones.estado='AC'
ORDER BY salud.ordenesmedicas.cedula";
	//echo"$cons</br></br>";
    $res = ExQuery($cons);
    ?>
    <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'>
        <tr bgcolor="#e5e5e5" style="font-weight: bold">
			<td>No.</td>
			<td>C&Eacute;DULA</td>
			<td>NOMBRE</td>
			<td>PABELL&Oacute;N</td>
			<td>MEDICAMENTO</td>
			<td>POSOLOG&Iacute;A</td>
        </tr>
	<? $count=1;
    while($fila=ExFetch($res)){
             echo"<tr>
                    <td>$count</td>
					<td>$fila[0]</td>
					<td>$fila[1] $fila[2] $fila[3] $fila[4]</td>
					<td>$fila[5]</td>
                    <td>$fila[6]</td>
					<td>$fila[7]</td>
                </tr>";
	$count++;
	}
    ?>
</table>
</form>
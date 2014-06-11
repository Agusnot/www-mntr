<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if($_GET['cedula']){
        $fecha_actual = date("Y-m-d H:i:s");
        
        $cons="UPDATE central.terceros SET vobo85dias='".$fecha_actual."' where identificacion='".$_GET['cedula']."'";
        $res=ExQuery($cons);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        
        <!--<script src="link/js/jquery-1.10.2.js"></script>
        <script src="link/js/jquery-ui-1.10.4.js"></script>-->
    </head>
    
    <body>
        <p style="font-family: verdana,arial,sans-serif; font-size:12px; font-weight:bold;">PACIENTES CON MÁS DE 85 DÍAS DE HOSPITALIZACIÓN</p>
        <table class="imagetable">
            <?php
            // Para alertas de porcentaje de monto ejecutado del contrato
            if($_GET['alerta']){
                $cons="SELECT * FROM central.terceros,(SELECT consulta1.cedula, sum(consulta1.totalanio) AS sumatoriafinal FROM (SELECT sum(CURRENT_TIMESTAMP-servicios.fechaing) AS totalanio, servicios.cedula  FROM salud.servicios WHERE servicios.estado='AC' AND servicios.tiposervicio='Hospitalizacion' AND date_part('year', CURRENT_TIMESTAMP)=date_part('year', servicios.fechaing) GROUP BY servicios.cedula 
                    UNION 
                        SELECT sum(servicios.fechaegr-servicios.fechaing) AS totalanio, servicios.cedula  FROM salud.servicios WHERE servicios.estado<>'AC' AND servicios.tiposervicio='Hospitalizacion' AND date_part('year', CURRENT_TIMESTAMP)=date_part('year', servicios.fechaing) GROUP BY servicios.cedula) AS consulta1 WHERE consulta1.totalanio>INTERVAL '85 days' GROUP BY consulta1.cedula) AS consulta2 WHERE terceros.identificacion=consulta2.cedula AND (date_part('year', terceros.vobo85dias)<date_part('year', CURRENT_TIMESTAMP) OR terceros.vobo85dias IS NULL)";
            	$res=ExQuery($cons);
            }
            else{
                $cons="SELECT * FROM central.terceros,(SELECT consulta1.cedula, sum(consulta1.totalanio) AS sumatoriafinal FROM (SELECT sum(CURRENT_TIMESTAMP-servicios.fechaing) AS totalanio, servicios.cedula  FROM salud.servicios WHERE servicios.estado='AC' AND servicios.tiposervicio='Hospitalizacion' AND date_part('year', CURRENT_TIMESTAMP)=date_part('year', servicios.fechaing) GROUP BY servicios.cedula 
                    UNION 
                        SELECT sum(servicios.fechaegr-servicios.fechaing) AS totalanio, servicios.cedula  FROM salud.servicios WHERE servicios.estado<>'AC' AND servicios.tiposervicio='Hospitalizacion' AND date_part('year', CURRENT_TIMESTAMP)=date_part('year', servicios.fechaing) GROUP BY servicios.cedula) AS consulta1 WHERE consulta1.totalanio>INTERVAL '85 days' GROUP BY consulta1.cedula) AS consulta2 WHERE terceros.identificacion=consulta2.cedula";
            	$res=ExQuery($cons);
            }
                //echo ExError();
		//$fila=ExFetch($res);
                ?>
                    <tr>
                        <th>IDENTIFICACIÓN</th><th>NOMBRE</th><th>DÍAS HOSPITALIZACIÓN</th><th>OPERACIONES</th>
                    </tr>
                <?php
                while($fila=ExFetchAssoc($res)){
                    ?>    
                    <tr>
                        <td><?php echo $fila['cedula']; ?></td>
                        <td><?php echo $fila['primape']." ".$fila['segape']." ".$fila['primnom']." ".$fila['segnom']; ?></td>
                        <td><?php echo $fila['sumatoriafinal']; ?></td>
                        <td style="text-align: center;">
                            <a href="/HistoriaClinica/ResultBuscarHC.php?DatNameSID=<?php echo $DatNameSID; ?>&Cedula=<?php echo $fila['cedula']; ?>&Buscar=1" ><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/HistoriaClinica/VoBo85Dias.php?DatNameSID=<?php echo $DatNameSID; ?>&cedula=<?php echo $fila['cedula']; ?>"><img src="../Imgs/b_check.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            //}
            ?>
        </table>
    </body>
</html>

<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    <body>
        <p style="font-family: verdana,arial,sans-serif; font-size:12px; font-weight:bold;">PETICIONES PENDIENTES POR GESTIONAR</p>
        <table class="imagetable">
            <tr>
                <th>TIPO</th><th>ASUNTO</th><th>USUARIO</th><th>FECHA</th><th>TELEFONO</th><th>DIRECCIÓN</th><th>PACIENTE</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="SELECT * FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.gestor_pqrs='$usuario[1]' ORDER BY pqrs.id_pqrs ASC";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_tipopqrs']; ?></td>
                        <td><?php echo $fila['asunto_pqrs']; ?></td>
                        <td><?php echo $fila['nombrepersona_pqrs']; ?></td>
                        <td><?php echo $fila['fecha_pqrs']; ?></td>
                        <td><?php echo $fila['telefono_pqrs']; ?></td>
                        <td><?php echo $fila['direccion_pqrs']; ?></td>
                        <td><?php echo $fila['paciente_pqrs']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&pqrs=<?php echo $fila['id_pqrs']; ?>"><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <!--<a href="/PQRS/TipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_pqrs']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a>--></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

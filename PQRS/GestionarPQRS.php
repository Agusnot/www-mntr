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
                //$cons="SELECT * FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.gestor_pqrs='$usuario[1]' ORDER BY pqrs.id_pqrs ASC";
                $cons="SELECT * FROM pqrs.pqrs WHERE pqrs.gestor_pqrs='$usuario[1]' AND pqrs.id_pqrs NOT IN (SELECT pqrs.id_pqrs FROM pqrs.pqrs,pqrs.respuesta,pqrs.secuencia WHERE pqrs.gestor_pqrs='$usuario[1]' AND pqrs.id_pqrs=respuesta.id_pqrs AND secuencia.id_secuencia=respuesta.id_secuencia GROUP BY pqrs.id_pqrs)
                         UNION
                       SELECT * FROM pqrs.pqrs WHERE pqrs.gestor_pqrs='$usuario[1]' AND pqrs.id_pqrs IN (SELECT respuesta.id_pqrs FROM pqrs.respuesta,(SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) FROM pqrs.pqrs,pqrs.respuesta,pqrs.secuencia WHERE pqrs.id_pqrs=respuesta.id_pqrs AND secuencia.id_secuencia=respuesta.id_secuencia GROUP BY pqrs.id_pqrs) AS consulta1 WHERE respuesta.id_pqrs=consulta1.id_pqrs AND respuesta.fecha_respuesta=consulta1.max AND respuesta.sigsecuencia_respuesta!=0)
                         UNION
                       SELECT * FROM pqrs.pqrs WHERE id_pqrs IN (SELECT respuesta.id_pqrs FROM pqrs.respuesta, pqrs.secuencia, pqrs.secuenciaxusuario WHERE secuencia.id_secuencia=secuenciaxusuario.id_secuencia AND secuenciaxusuario.id_usuario='$usuario[1]' AND respuesta.sigsecuencia_respuesta=secuencia.id_secuencia AND (id_pqrs, fecha_respuesta) IN (SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) FROM pqrs.pqrs,pqrs.respuesta,pqrs.secuencia WHERE pqrs.id_pqrs=respuesta.id_pqrs AND secuencia.id_secuencia=respuesta.id_secuencia GROUP BY pqrs.id_pqrs))
                         UNION
                       SELECT * FROM pqrs.pqrs WHERE pqrs.id_pqrs IN (SELECT consulta1.id_pqrs FROM central.usuarios,(SELECT jerarquia.padre_jerarquia,pqrs.id_pqrs FROM pqrs.pqrs,central.usuarios,pqrs.jerarquia WHERE pqrs.gestor_pqrs=usuarios.usuario AND usuarios.id_jerarquia=jerarquia.id_jerarquia AND pqrs.id_pqrs IN (SELECT respuesta.id_pqrs FROM pqrs.respuesta, pqrs.secuencia WHERE respuesta.sigsecuencia_respuesta=secuencia.id_secuencia AND reqvobo_secuencia=1 AND (id_pqrs, fecha_respuesta) IN (SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) FROM pqrs.pqrs,pqrs.respuesta,pqrs.secuencia WHERE pqrs.id_pqrs=respuesta.id_pqrs AND secuencia.id_secuencia=respuesta.id_secuencia GROUP BY pqrs.id_pqrs))) AS consulta1 WHERE usuarios.usuario='$usuario[1]' AND usuarios.id_jerarquia=consulta1.padre_jerarquia)";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
					$constipo="SELECT tipopqrs.* FROM pqrs.tipopqrs, pqrs.clasetipopqrs WHERE tipopqrs.id_tipopqrs=clasetipopqrs.id_tipopqrs AND id_clasetipopqrs=".$fila['id_clasetipopqrs']." ";
                    $restipo=ExQuery($constipo);
                    $filatipo=ExFetchAssoc($restipo)
                    ?>
                    <tr>
                        <td><?php echo $filatipo['nombre_tipopqrs']; ?></td>
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

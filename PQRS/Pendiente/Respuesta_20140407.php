<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    $conspqrs="select * from pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs where pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs and clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs and pqrs.id_pqrs=$pqrs";
    $respqrs=ExQuery($conspqrs);
    $filapqrs=ExFetchAssoc($respqrs);
    
    /*if($Guardar){
        // Para conocer cuantas respuestas deben asignarse a esta petición
        $constxe="select count(*) from pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.estadoxtipo 
            where pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                and clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                and tipopqrs.id_tipopqrs=estadoxtipo.id_tipopqrs 
                and nivel_estadoxtipo<>0 
                AND pqrs.id_pqrs=$pqrs";
        $restxe=ExQuery($constxe);
        $filatxe=ExFetchAssoc($restxe);
        
        // Conteo de cuantas respuestas hay registradas para esta petición
        $consrs="select count(*) from pqrs.respuesta 
                where respuesta.id_pqrs=$pqrs";
        $resrs=ExQuery($consrs);
        $filars=ExFetchAssoc($resrs);
        
        $fecha_respuesta = date("Y-m-d H:i:s");
        $cons="insert into pqrs.respuesta(comentario_respuesta, fecha_respuesta, id_estadorespuesta, id_pqrs) values ('".$comentario_respuesta."', '".$fecha_respuesta."', '".$id_estadorespuesta."', '".$pqrs."')";
        $res=ExQuery($cons);
    }*/
    if($editar){
        $cons="select * from pqrs.respuesta where id_respuesta=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $fecha_respuesta = date("Y-m-d H:i:s");
        $cons="update pqrs.respuesta set comentario_respuesta='$comentario_respuesta', id_estadorespuesta='$id_estadorespuesta', id_pqrs='$pqrs', fecha_respuesta='$fecha_respuesta' where id_respuesta=$id_respuesta";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    /*if($eliminar){
        $cons2="delete from pqrs.estadoxtipo where id_tipopqrs='".$eliminar."'";
        $res2=ExQuery($cons2);
        
        $cons="delete from pqrs.tipopqrs where id_tipopqrs=$eliminar";
        $res=ExQuery($cons);
    }*/
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <?php //echo "PQRS Actual".$pqrs; ?>
        <form name='FORMA' method="post" action="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">RESPUESTAS PQRS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['nombre_tipopqrs']; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">ASUNTO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['asunto_pqrs']; ?></td>
                        </tr>
                        <?php
                        if($editar){
                        ?>
                        <tr>
                            <td style="text-align: right;">COMENTARIO</td>
                            <td><textarea name="comentario_respuesta" id="comentario_respuesta" cols="60"><?php echo $fila['comentario_respuesta']; ?></textarea></td>
                            <!--<td><input type="text" name="comentario_respuesta" id="comentario_respuesta" value="<?php echo $fila['comentario_respuesta']; ?>" style="width:200px;"></td>-->
                        </tr>
                        <tr>
                            <td style="text-align: right;">ESTADO</td>
                            <td>
                            <select name="id_estadorespuesta" id="id_estadorespuesta" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.estadorespuesta order by nombre_estadorespuesta";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            echo "<option value=".$fila['id_estadorespuesta'].">".$fila['nombre_estadorespuesta']."</option>";
                                        }
                                    ?>
                            </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_respuesta" id="id_respuesta" value="<?php echo $editar; ?>">
                                <?php
                                }
                                else{
                                ?>
                                    <!--<input type="submit" name="Guardar" id="Guardar" value="Nuevo">-->
                                <?php
                                }
                                ?>
                                <input type="hidden" name="pqrs" id="pqrs" value="<?php echo $pqrs; ?>">
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                    <th>FECHA</th><th>ESTADO</th><th>COMENTARIO</th><th>ESTADO RESPUESTA</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.respuesta, pqrs.estadorespuesta, pqrs.estadopqrs where respuesta.id_estadorespuesta=estadorespuesta.id_estadorespuesta and estadopqrs.id_estadopqrs=respuesta.id_estadopqrs and respuesta.id_pqrs=".$pqrs." order by id_respuesta asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                $bandera = 0;
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['fecha_respuesta']; ?></td>
                        <td><?php echo $fila['nombre_estadopqrs']; ?></td>
                        <td><?php echo $fila['comentario_respuesta']; ?></td>
                        <td><?php echo $fila['nombre_estadorespuesta']; ?></td>
                        <td style="text-align: center;">
                            <?php
                            if($fila['id_estadorespuesta']!=1 && $fila['comentario_respuesta']!=NULL && $fila['fecha_respuesta']!=NULL){
                                
                            }
                            else{
                                if($bandera==0){
                                ?>
                            <a href="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_respuesta']; ?>&pqrs=<?php echo $pqrs; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                                <?php
                                    $bandera = 1;
                                }
                            }
                            ?>
                        </td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

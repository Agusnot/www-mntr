<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    $conspqrs="select * from pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs where pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs and clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs and pqrs.id_pqrs=$pqrs";
    $respqrs=ExQuery($conspqrs);
    $filapqrs=ExFetchAssoc($respqrs);
    
    if($Guardar){
        $fecha_respuesta = date("Y-m-d H:i:s");
        $sigsecuencia_respuesta = array_shift(array_keys($Guardar));
        if(!isset($id_secuencia)){
            $id_secuencia = array_shift(array_keys($Guardar));
        }
        
        // Consulto la secuencia para comprobar si requiere de visto bueno y no se ha creado ningún visto bueno para esa respuesta
        $conssec="SELECT * FROM pqrs.secuencia, pqrs.respuesta 
            WHERE secuencia.id_secuencia=respuesta.id_secuencia 
                AND secuencia.id_secuencia=".$id_secuencia." 
                AND reqvobo_secuencia=1 
                AND vobo_respuesta IS NULL";
        $ressec=ExQuery($conssec);
        $filasec=ExFetchAssoc($ressec);
        
        if(ExNumRows($ressec)>0){
            $cons="update pqrs.respuesta set vobo_respuesta='".$comentario_respuesta."', vobofecha_respuesta='".$fecha_respuesta."', vobousuario_respuesta='".$usuario[1]."', estado_respuesta=1 where respuesta.id_respuesta='".$id_respuesta."'";
            $res=ExQuery($cons);
        }
        else{
            if($comentario_respuesta==""){
                // Consulto si la secuencia era de selección para insertar un texto por defecto
                $consdos="SELECT * FROM pqrs.secuencia 
                    WHERE secuencia.id_secuencia=".$id_secuencia." ";
                $resdos=ExQuery($consdos);
                $filados=ExFetchAssoc($resdos);
                $botones = explode("|", $filados['id_tiposecuencia']);
                if(count($botones)>1){
                    $comentario_respuesta=$Guardar[$sigsecuencia_respuesta];
                }
            }
            $cons="insert into pqrs.respuesta(comentario_respuesta, fecha_respuesta, id_secuencia, id_pqrs, sigsecuencia_respuesta) values ('".$comentario_respuesta."', '".$fecha_respuesta."', ".$id_secuencia.", ".$pqrs.", ".$sigsecuencia_respuesta.")";
            $res=ExQuery($cons);
        }
    }
    /*if($editar){
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
    }*/
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
                            <td style="text-align: right; font-style: italic;">TIPO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['nombre_tipopqrs']; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-style: italic;">ASUNTO</td>
                            <td colspan=4 style="text-align: left;"><?php echo $filapqrs['asunto_pqrs']; ?></td>
                        </tr>
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_respuesta" id="id_respuesta" value="<?php echo $editar; ?>">
                                <?php
                                }
                                else{
                                    // Consulto las respuestas creadas para esta petición
                                    $cons2="SELECT * FROM pqrs.respuesta, pqrs.pqrs 
                                        WHERE pqrs.id_pqrs=respuesta.id_pqrs
                                            AND pqrs.id_pqrs=$pqrs
                                        ORDER BY respuesta.fecha_respuesta DESC
                                        LIMIT 1";
                                    $res2=ExQuery($cons2);
                                    $fila2=ExFetchAssoc($res2);
                                    //echo "<br>";
                                    
                                    if($fila2['sigsecuencia_respuesta']==='0'){
                                        echo "<tr><td></td><td style='font-style:italic; text-decoration:underline;'>ESTA PETICIÓN YA FUE GESTIONADA.</td></tr>";
                                    }
                                    else{
                                        if(ExNumRows($res2)){
                                            // Consulto la secuencia para comprobar si requiere de visto bueno y no se ha creado ningún visto bueno para esa respuesta
                                            $conssec="SELECT * FROM pqrs.secuencia, pqrs.respuesta 
                                                WHERE secuencia.id_secuencia=respuesta.id_secuencia 
                                                    AND secuencia.id_secuencia=".$fila2['id_secuencia']." 
                                                    AND reqvobo_secuencia=1
                                                    AND vobo_respuesta IS NULL";
                                            $ressec=ExQuery($conssec);
                                            $filasec=ExFetchAssoc($ressec);
                                        }

                                        if(ExNumRows($ressec)>0){
                                            // Consultar el nivel jerárquico padre para validar si puede guardar el VoBo
                                            $cons3="SELECT padre_jerarquia FROM pqrs.jerarquia, central.usuarios 
                                                WHERE jerarquia.id_jerarquia=usuarios.id_jerarquia 
                                                    AND usuarios.usuario='".$filapqrs['gestor_pqrs']."'";
                                            $res3=ExQuery($cons3);
                                            $fila3=ExFetchAssoc($res3);

                                            // Busca usuarios en la jerarquia consultada anteriormente
                                            $cons4="SELECT * FROM central.usuarios 
                                                WHERE usuarios.id_jerarquia=".$fila3['padre_jerarquia']."";
                                            $res4=ExQuery($cons4);

                                            // si encuentra 1 o más secuencias asignadas x usuario
                                            if(ExNumRows($res4)>0){
                                                // Si el usuario está en el listado anterior permita la edición
                                                while($fila4=ExFetchAssoc($res4)){
                                                    if($usuario[1]==$fila4['usuario']){
                                                        ?>
                                                            <tr>
                                                                <td style="text-align: right; font-style: italic;">PASO</td>
                                                                <td style="text-align: left;">Vo. Bo. <?php echo $filasec['nombre_secuencia']; ?></td>
                                                            </tr>
                                                            <tr><td style="text-align: right; font-style: italic;">COMENTARIO</td>
                                                                <td><textarea name="comentario_respuesta" id="comentario_respuesta" cols="60"><?php echo $fila['comentario_respuesta']; ?></textarea></td>
                                                                <!--<td><input type="text" name="comentario_respuesta" id="comentario_respuesta" value="<?php echo $fila['comentario_respuesta']; ?>" style="width:200px;"></td>-->
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td style="text-align: left;">
                                                                    <input type="hidden" id="id_respuesta" name="id_respuesta" value="<?php echo $fila2['id_respuesta']; ?>">
                                                                    <input type="submit" name="Guardar[<?php echo $fila2['id_secuencia']; ?>]" id="Guardar[<?php echo $fila2['id_secuencia']; ?>]" value="Guardar">
                                                        <?php
                                                        break;
                                                    }
                                                }
                                            }
                                            // Mostrar el boton de guardado a los usuarios de mayor jerarquía
                                        }
                                        else{
                                            // Si encuentra alguna respuesta busca la ultima secuencia
                                            if(ExNumRows($res2)>0){
                                                // Consulto las secuencias hijas en base a la secuencia actual
                                                /*echo $consbot = "SELECT secuencia.* FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.secuencia
                                                    WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                                        AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                        AND secuencia.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                        AND secuencia.padre_secuencia=".$fila2['id_secuencia']."
                                                        AND pqrs.id_pqrs=$pqrs";*/
                                                $consbot = "SELECT secuencia.* FROM pqrs.respuesta, pqrs.secuencia 
                                                        WHERE respuesta.id_secuencia=secuencia.padre_secuencia 
                                                        AND secuencia.id_secuencia=".$fila2['sigsecuencia_respuesta']."";
                                            }else{
                                                $consbot = "SELECT secuencia.* FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.secuencia
                                                    WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                                        AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                        AND secuencia.id_tipopqrs=tipopqrs.id_tipopqrs 
                                                        AND secuencia.padre_secuencia=0 
                                                        AND pqrs.id_pqrs=$pqrs";
                                            }
                                            $resbot=ExQuery($consbot);
                                            $filabot=ExFetchAssoc($resbot);

                                            ?>
                                                            <input type="hidden" id="id_secuencia" name="id_secuencia" value="<?php echo $filabot['id_secuencia']; ?>">
                                            <?php
                                            if(ExNumRows($res2)){
                                                // Si encuentra un usuario asignado a esa secuencia solo pueden hacer cambios esas personas
                                                $cons33="SELECT * FROM pqrs.secuencia, pqrs.secuenciaxusuario 
                                                    WHERE secuencia.id_secuencia=secuenciaxusuario.id_secuencia
                                                        AND secuencia.id_secuencia=".$fila2['sigsecuencia_respuesta']."";
                                                $res33=ExQuery($cons33);
                                                //echo "<hr>";
                                            }

                                            // si encuentra 2 o más secuencias asignadas x usuario
                                            if(ExNumRows($res33)>0){
                                                // Si el usuario está en el listado anterior permita la edición
                                                while($fila33=ExFetchAssoc($res33)){
                                                    if($usuario[1]==$fila33['id_usuario']){
                                                        $botones = explode("|", $filabot['id_tiposecuencia']);
                                                        if(count($botones)<2){
                                                        ?>
                                                            <tr><td style="text-align: right; font-style: italic;">COMENTARIO</td>
                                                                <td><textarea name="comentario_respuesta" id="comentario_respuesta" cols="60"><?php echo $fila['comentario_respuesta']; ?></textarea></td>
                                                                <!--<td><input type="text" name="comentario_respuesta" id="comentario_respuesta" value="<?php echo $fila['comentario_respuesta']; ?>" style="width:200px;"></td>-->
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                            <tr>
                                                                <td style="text-align: right; font-style: italic;">PASO</td>
                                                                <td style="text-align: left;"><?php echo $filabot['nombre_secuencia']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td style="text-align: left;">
                                                        <?php
                                                        foreach($botones as $value){
                                                            $nombreboton = explode(";", $value);
                                                            ?>
                                                                <input type="submit" name="Guardar[<?php echo $nombreboton[2]; ?>]" id="Guardar[<?php echo $nombreboton[0]; ?>]" value="<?php echo $nombreboton[0]; ?>">
                                                            <?php
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                            else{
                                                $consultima="SELECT sigsecuencia_respuesta FROM pqrs.respuesta 
                                                    WHERE id_pqrs=$pqrs 
                                                        AND sigsecuencia_respuesta = 0 
                                                        ORDER BY fecha_respuesta ASC
                                                        LIMIT 1";
                                                $resultima=ExQuery($consultima);
                                                $filaultima=ExFetchAssoc($resultima);

                                                //if($filaultima['sigsecuencia_respuesta']!=0){
                                                    $conspqrs="SELECT gestor_pqrs FROM pqrs.pqrs 
                                                        WHERE pqrs.id_pqrs=$pqrs";
                                                    $respqrs=ExQuery($conspqrs);
                                                    $filapqrs=ExFetchAssoc($respqrs);

                                                    if($filapqrs['gestor_pqrs']==$usuario[1]){
                                                        $botones = explode("|", $filabot['id_tiposecuencia']);
                                                        if(count($botones)<2){
                                                        ?>
                                                            <td style="text-align: right; font-style: italic;">COMENTARIO</td>
                                                                <td><textarea name="comentario_respuesta" id="comentario_respuesta" cols="60"><?php echo $fila['comentario_respuesta']; ?></textarea></td>
                                                                <!--<td><input type="text" name="comentario_respuesta" id="comentario_respuesta" value="<?php echo $fila['comentario_respuesta']; ?>" style="width:200px;"></td>-->
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                            <tr>
                                                                <td style="text-align: right; font-style: italic;">PASO</td>
                                                                <td style="text-align: left;"><?php echo $filabot['nombre_secuencia']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td style="text-align: left;">
                                                        <?php
                                                        foreach($botones as $value){
                                                            $nombreboton = explode(";", $value);
                                                            ?>
                                                                <input type="submit" name="Guardar[<?php echo $nombreboton[2]; ?>]" id="Guardar[<?php echo $nombreboton[0]; ?>]" value="<?php echo $nombreboton[0]; ?>">
                                                            <?php
                                                        }
                                                    }
                                                //}
                                            }
                                        }
                                    }
                                }
                                ?>
                                <input type="hidden" name="pqrs" id="pqrs" value="<?php echo $pqrs; ?>">
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                    <th>PASO</th><th>FECHA</th><th>COMENTARIO</th>
            </tr>
            <?php
                $cons="select * from pqrs.respuesta, pqrs.secuencia where respuesta.id_secuencia=secuencia.id_secuencia and respuesta.id_pqrs=".$pqrs." order by id_respuesta asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_secuencia']; ?></td>
                        <td><?php echo $fila['fecha_respuesta']; ?></td>
                        <td><?php echo $fila['comentario_respuesta']; ?></td>
                        <!--<td style="text-align: center;">
                            <a href="/PQRS/Respuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_respuesta']; ?>&pqrs=<?php echo $pqrs; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                        </td>-->
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

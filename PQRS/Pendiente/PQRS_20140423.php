<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if($Guardar){
        extract($_POST);
        foreach ($_POST as $key => $value){
            if($value==""){
                $TypeTip = "warning";
                $MessageTip = "Debe diligenciar todos los campos.";
                break;
            }
        }
        
        if(!$MessageTip){
            $cons="insert into pqrs.pqrs(asunto_pqrs, direccion_pqrs, fecha_pqrs, gestor_pqrs, id_buzon, id_canalcomunicacion, id_clasetipopqrs, id_proceso, identificacion_tercero, nombre_pabellon, nombre_usuario, nombrepersona_pqrs, numidepersona_pqrs, otraclase_pqrs, paciente_pqrs, telefono_pqrs)"
                    . "values ('".$asunto_pqrs."', '".$direccion_pqrs."', '".$fecha_pqrs."', '".$gestor_pqrs."', '".$id_buzon."', '".$id_canalcomunicacion."', '".$id_clasetipopqrs."', '".$id_proceso."', '".$identificacion_tercero."', '".$nombre_pabellon."', '".$usuario[0]."', '".$nombrepersona_pqrs."', '".$numidepersona_pqrs."', '".$otraclase_pqrs."', '".$paciente_pqrs."', '".$telefono_pqrs."') returning *";
            $res=ExQuery($cons);
            $fila=ExFetchAssoc($res);

            // Para conocer cuantas respuestas deben asignarse a esta petición
            $constxe="select estadoxtipo.* from pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs, pqrs.estadoxtipo
                where pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                    and clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                    and tipopqrs.id_tipopqrs=estadoxtipo.id_tipopqrs 
                    and estadoxtipo.nivel_estadoxtipo<>0 
                    AND pqrs.id_pqrs=".$fila['id_pqrs']." 
                    order by estadoxtipo.nivel_estadoxtipo asc";
            $restxe=ExQuery($constxe);

            while($filatxe=ExFetchAssoc($restxe)){
                //$fecha_respuesta = date("Y-m-d H:i:s");
                $cons="insert into pqrs.respuesta(comentario_respuesta, fecha_respuesta, id_pqrs, id_estadopqrs) values (NULL, NULL, '".$fila['id_pqrs']."', '".$filatxe['id_estadopqrs']."')";
                $res=ExQuery($cons);
                echo $cons;
            }
            //header("Location: /PQRS/PQRS.php?DatNameSID=".$DatNameSID);
        }
    }
?>
<html>
    <head>
        <script language='javascript' src="/calendario/popcalendar.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    <body>
        <?php
        if($MessageTip!=''){
            echo "<div class='".$TypeTip."'>".$MessageTip."</div>";
        }
        ?>
        <form name='FORMA' method="post" action="/PQRS/PQRS.php?DatNameSID=<?php echo $DatNameSID; ?>">
            <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">PETICIONES, QUEJAS, RECLAMOS y SUGERENCIAS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">BUZÓN</td>
                            <?php
                                $cons="select * from pqrs.buzon where estado_buzon=1";
                                $res=ExQuery($cons);
                                $fila=ExFetchAssoc($res);
                            ?>
                            <td>
                                <input type="text" name="codigo_buzon" id="codigo_buzon" value="<?php echo substr(str_replace("-", "", $fila['fecha_buzon']),0,8); ?>" style="width:100px;" readonly>
                                <input type="hidden" name="id_buzon" id="id_buzon" value="<?php echo $fila['id_buzon']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FECHA</td>
                            <td><input type="text" name="fecha_pqrs" id="fecha_pqrs" readonly onClick="popUpCalendar(this, FORMA.fecha_pqrs, 'yyyy-mm-dd')" style="width:200px;" value="<?php echo $fecha_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PERSONA QUE DILIGENCIA EL FORMATO</td>
                            <td><input name="nombrepersona_pqrs" type="Text" id="nombre_usuario" style="width:200px;" value="<?php echo $nombrepersona_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO DOCUMENTO</td>
                            <td>
                                <select name="id_tipoidentificacion" id="id_tipoidentificacion" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.tiposdocumentos";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_tipoidentificacion==$fila['codigo'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['codigo']."' ".$sel.">".strtoupper($fila['tipodoc'])."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NÚMERO DE IDENTIFICACIÓN</td>
                            <td><input name="numidepersona_pqrs" type="Text" id="numidepersona_pqrs" style="width:200px;" value="<?php echo $numidepersona_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">DIRECCIÓN RESPUESTA</td>
                            <td><input type="text" name="direccion_pqrs" id="direccion_pqrs" style="width:200px;" value="<?php echo $direccion_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TELÉFONO</td>
                            <td><input name="telefono_pqrs" type="Text" id="telefono_pqrs" style="width:200px;" value="<?php echo $telefono_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE PACIENTE</td>
                            <td><input name="paciente_pqrs" type="Text" id="paciente_pqrs" style="width:200px;" value="<?php echo $paciente_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">ASEGURADORA</td>
                            <td>
                                <select name="identificacion_tercero" id="identificacion_tercero" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select identificacion,(primape||' '||segape||' '||primnom||' '||segnom) as nombre_aseguradora from central.terceros where tipo='Asegurador' and compania='$Compania[0]' order by primape";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($identificacion_tercero==$fila['identificacion'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['identificacion']."' ".$sel.">".$fila['nombre_aseguradora']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PROCESO</td>
                            <td>
                                <select name="id_proceso" id="id_proceso" style="width:200px; font-size: 12px;" onchange="FORMA.submit();">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.proceso order by nombre_proceso";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_proceso==$fila['id_proceso'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_proceso']."' ".$sel.">".$fila['nombre_proceso']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">SERVICIO</td>
                            <td>
                                <?php
                                    if($id_proceso!=14)
                                        $dis = "disabled";
                                ?>
                                <select name="nombre_pabellon" id="nombre_pabellon" style="width:200px; font-size: 12px;" <?php echo $dis; ?>>
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        if($id_proceso==14){
                                            $cons="select * from salud.pabellones order by ambito,pabellon asc";
                                            $res=ExQuery($cons);
                                            while($fila=ExFetchAssoc($res)){
                                                if($nombre_pabellon==$fila['pabellon'])
                                                    $sel = ' selected';
                                                else
                                                    $sel = '';
                                                echo "<option value='".$fila['pabellon']."' ".$sel.">".$fila['pabellon']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">CANAL DE COMUNICACIÓN</td>
                            <td>
                            <select name="id_canalcomunicacion" id="id_canalcomunicacion" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.canalcomunicacion order by nombre_canalcomunicacion";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_canalcomunicacion==$fila['id_canalcomunicacion'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_canalcomunicacion']."' ".$sel.">".$fila['nombre_canalcomunicacion']."</option>";
                                        }
                                    ?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">GESTOR ASIGNADO</td>
                            <td>
                                <select name="gestor_pqrs" id="gestor_pqrs" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.usuarios order by usuarios asc";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($gestor_pqrs==$fila['usuario'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['usuario']."' ".$sel.">".$fila['usuario']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td style="text-align: right;">TIPO PQRS</td>
                            <td>
                                <select name="id_tipopqrs" id="id_tipopqrs" style="width:200px; font-size: 12px;" onchange="FORMA.submit();">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.tipopqrs";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_tipopqrs==$fila['id_tipopqrs'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_tipopqrs']."' ".$sel.">".$fila['nombre_tipopqrs']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">CLASIFICACIÓN TIPO PQRS</td>
                            <td>
                                <select name="id_clasetipopqrs" id="id_clasetipopqrs" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                    if($id_tipopqrs){
                                        $cons="select * from pqrs.clasetipopqrs where id_tipopqrs='".$id_tipopqrs."'";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_clasetipopqrs==$fila['id_clasetipopqrs'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_clasetipopqrs']."' ".$sel.">".$fila['nombre_clasetipopqrs']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">OTRA, CUÁL?</td>
                            <td><input name="otraclase_pqrs" type="Text" id="otraclase_pqrs" style="width:200px;" value="<?php echo $otraclase_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">DESCRIPCION</td>
                            <td colspan=6>
                                <textarea name="asunto_pqrs" id="asunto_pqrs" cols="30" rows="4"><?php echo $asunto_pqrs; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;"><input type="submit" name="Guardar" id="Guardar" value="Guardar"></td>
                        </tr>
                
            </table>
        </form>
        
        <table class="imagetable">
            <tr>
                <th>TIPO</th><th>ASUNTO</th><th>USUARIO</th><th>FECHA</th><th>TELEFONO</th><th>DIRECCIÓN</th><th>PACIENTE</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="SELECT * FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs ORDER BY pqrs.id_pqrs ASC";
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

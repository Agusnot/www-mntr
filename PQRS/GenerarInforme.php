<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    /*if($accionxusuario){
        $fecha_actual = date("Y-m-d H:i:s");
        
        $cons="UPDATE pqrs.accionxusuario SET descripcion_accionxusuario='$descripcion_accionxusuario', fecha_accionxusuario='$fecha_actual', vobo_accionxusuario='1' where id_accionxusuario=$accionxusuario";
        $res=ExQuery($cons);
    }*/
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        
        <link rel="stylesheet" type="text/css" href="../js/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
        <link rel="stylesheet" type="text/css" href="../js/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_terrace.css">
        <script src="../js/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
        
        <script src="link/js/jquery-1.10.2.js"></script>
        <script src="link/js/jquery-ui-1.10.4.js"></script>
        <script>
            $(document).ready(function(){
                $("#toggle").click(function(){
                    $("#div_busqueda").toggle();
                    if($("#toggle").text()=='OCULTAR BÚSQUEDA'){
                        $("#toggle").text("MOSTRAR BÚSQUEDA");
                    }
                    else{
                        $("#toggle").text("OCULTAR BÚSQUEDA");
                    }
                });
            });
        </script>
    </head>
    <body>
        <div id="div_busqueda"><form name='FORMA' method="post" action="/PQRS/GenerarInforme.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td></td>
                            <td style="text-align: left; font-weight: bold;">BÚSQUEDA DE PQRS</td>
                        </tr>
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
                            <td style="text-align: right;">CLASIFICACION TIPO PQRS</td>
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
                            <td style="text-align: right;">PASO TIPO PQRS</td>
                            <td>
                                <select name="id_secuencia" id="id_secuencia" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                    if($id_tipopqrs){
                                        $cons="select * from pqrs.secuencia where id_tipopqrs='".$id_tipopqrs."'";
                                        $res=ExQuery($cons);
                                        if(ExNumRows($res)>0){
                                            $sel = '';
                                            if($id_secuencia=="0")
                                                $sel = ' selected';
                                            echo "<option value='0' ".$sel.">SIN INICIAR</option>";
                                        }
                                        while($fila=ExFetchAssoc($res)){
                                            $sel = '';
                                            if($id_secuencia!="0"){
                                                if($id_secuencia==$fila['id_secuencia'])
                                                    $sel = ' selected';
                                            }
                                            echo "<option value='".$fila['id_secuencia']."' ".$sel.">".$fila['nombre_secuencia']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">ASUNTO</td>
                            <td><input type="text" name="asunto_pqrs" id="asunto_pqrs" value="<?php echo $asunto_pqrs; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PERIODO</td>
                            <td>
                                <input type="text" name="fechaini_busqueda" id="fechaini_busqueda" value="<?php echo $fechaini_busqueda; ?>" style="width:200px;"> HASTA 
                                <input type="text" name="fechafin_busqueda" id="fechafin_busqueda" value="<?php echo $fechafin_busqueda; ?>" style="width:200px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">ASEGURADORA</td>
                            <td>
                                <select name="identificacion_tercero" id="identificacion_tercero" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.terceros where tipo='Asegurador' and compania='$Compania[0]' order by primape";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($identificacion_tercero==$fila['identificacion'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['identificacion']."' ".$sel.">".$fila['primape']." ".$fila['segape']." ".$fila['primnom']." ".$fila['segnom']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">COLABORADOR</td>
                            <td>
                                <select name="gestor_pqrs" id="gestor_pqrs" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.usuarios order by usuario";
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
                            <td style="text-align: right;">PROCESO</td>
                            <td>
                                <select name="id_proceso" id="id_proceso" style="width:200px; font-size: 12px;">
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
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_secuencia" id="id_secuencia" value="<?php echo $editar; ?>">
                                <?php
                                }
                                else{
                                ?>
                                    <input type="submit" name="Buscar" id="Buscar" value="Buscar">
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                </table>
        </form></div>
        <a href="#" id="toggle" style="font-weight: bold; text-decoration: none; font-family: Tahoma, Geneva, sans-serif; font-size: 13px;">OCULTAR BÚSQUEDA</a>
        <p style="font-family: verdana,arial,sans-serif; font-size:12px; font-weight:bold;"></p>
        <table class="imagetable">
            <tr>
                <th>TIPO</th><th>CLASIFICACION TIPO</th><th>ASUNTO</th><th>FECHA CREACIÓN</th><th>ASEGURADORA</th><th>PROCESO</th><th>SECUENCIA</th><th>COLABORADOR</th><th>CANAL DE COMUNICACION</th>
            </tr>
            <?php
                if($id_tipopqrs!="")
                    $considtipo = "AND tipopqrs.id_tipopqrs=".$id_tipopqrs;
                if($id_clasetipopqrs!="")
                    $consclasetipo = "AND clasetipopqrs.id_clasetipopqrs=".$id_clasetipopqrs;
                if($asunto_pqrs!=""){
                    if(strlen($asunto_pqrs)>3)
                        $consasunto = "AND consulta0.asunto_pqrs ILIKE '%".$asunto_pqrs."%'";
                }
                if($fechaini_busqueda!="" && $fechafin_busqueda!="")
                    $consperiodo = "AND consulta0.fecha_pqrs<='$fechafin_busqueda 23:59:59' AND consulta0.fecha_pqrs>='$fechaini_busqueda 00:00:00'";
                if($identificacion_tercero!="")
                    $considetercero = "AND consulta0.identificacion_tercero='".$identificacion_tercero."'";
                if($id_proceso!="")
                    $consproceso = "AND consulta0.id_proceso='".$id_proceso."'";
                if($id_secuencia!="")
                    $conssecuencia = "AND consulta0.id_secuencia=".$id_secuencia;
                if($id_secuencia=="0")
                    $conssecuencia = "AND consulta0.id_secuencia IS NULL";
                if($gestor_pqrs!="")
                    $consgestor = "AND consulta0.gestor_pqrs='".$gestor_pqrs."'";
                
                // Primera consulta que no mostraba las PQRS no iniciadas
                /*$cons="SELECT * FROM pqrs.pqrs, pqrs.respuesta, pqrs.secuencia, pqrs.buzon, pqrs.canalcomunicacion, pqrs.proceso, pqrs.clasetipopqrs, pqrs.tipopqrs, central.terceros 
                        WHERE pqrs.id_pqrs=respuesta.id_pqrs 
                                AND respuesta.id_secuencia=secuencia.id_secuencia 
                                AND pqrs.id_canalcomunicacion=canalcomunicacion.id_canalcomunicacion 
                                AND pqrs.id_proceso=proceso.id_proceso 
                                AND pqrs.id_buzon=buzon.id_buzon 
                                AND pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs 
                                AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs 
                                AND terceros.identificacion=pqrs.identificacion_tercero 
                                ".$considtipo." ".$consclasetipo." ".$consasunto." ".$consperiodo." ".$considetercero." ".$consproceso." ".$conssecuencia." ".$consgestor." 
                                AND (pqrs.id_pqrs, respuesta.fecha_respuesta) IN (SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) 
                                        FROM pqrs.pqrs,pqrs.respuesta,pqrs.secuencia 
                                        WHERE pqrs.gestor_pqrs='".$usuario[1]."' 
                                                AND pqrs.id_pqrs=respuesta.id_pqrs 
                                                AND secuencia.id_secuencia=respuesta.id_secuencia 
                                        GROUP BY pqrs.id_pqrs)";*/
                
                // Consulta todos los PQRS incluyendo los que no tienen respuestas
                //$cons="SELECT DISTINCT ON (consulta0.id_pqrs) consulta0.*,buzon.*,canalcomunicacion.*,proceso.*,clasetipopqrs.*,tipopqrs.*,terceros.* FROM (SELECT consulta1.* FROM (SELECT pqrs.*, respuesta.id_respuesta, respuesta.comentario_respuesta, respuesta.fecha_respuesta, respuesta.id_secuencia, respuesta.sigsecuencia_respuesta, respuesta.id_usuario FROM pqrs.pqrs LEFT OUTER JOIN pqrs.respuesta ON pqrs.id_pqrs=respuesta.id_pqrs) AS consulta1, (SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) AS fecha_respuesta FROM pqrs.pqrs LEFT OUTER JOIN pqrs.respuesta ON pqrs.id_pqrs=respuesta.id_pqrs GROUP BY pqrs.id_pqrs) AS consulta2 WHERE consulta1.id_pqrs=consulta2.id_pqrs AND (consulta1.fecha_respuesta=consulta2.fecha_respuesta OR consulta1.fecha_respuesta IS NULL OR consulta1.fecha_respuesta IS NULL)) AS consulta0, pqrs.secuencia, pqrs.buzon, pqrs.canalcomunicacion, pqrs.proceso, pqrs.clasetipopqrs, pqrs.tipopqrs, central.terceros WHERE (consulta0.id_secuencia=secuencia.id_secuencia OR consulta0.id_secuencia IS NULL) AND consulta0.id_canalcomunicacion=canalcomunicacion.id_canalcomunicacion AND consulta0.id_proceso=proceso.id_proceso AND consulta0.id_buzon=buzon.id_buzon AND consulta0.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND terceros.identificacion=consulta0.identificacion_tercero 
                $cons = "SELECT DISTINCT ON (consulta0.id_pqrs) consulta0.*,buzon.*,canalcomunicacion.*,proceso.*,clasetipopqrs.*,tipopqrs.*,terceros.* FROM (SELECT consulta1.* FROM (SELECT pqrs.*, respuesta.id_respuesta, respuesta.comentario_respuesta, respuesta.fecha_respuesta, respuesta.id_secuencia, respuesta.sigsecuencia_respuesta, respuesta.id_usuario FROM pqrs.pqrs LEFT OUTER JOIN pqrs.respuesta ON pqrs.id_pqrs=respuesta.id_pqrs) AS consulta1 INNER JOIN (SELECT pqrs.id_pqrs,max(respuesta.fecha_respuesta) AS fecha_respuesta FROM pqrs.pqrs LEFT OUTER JOIN pqrs.respuesta ON pqrs.id_pqrs=respuesta.id_pqrs GROUP BY pqrs.id_pqrs) AS consulta2 ON consulta1.id_pqrs=consulta2.id_pqrs WHERE consulta1.fecha_respuesta=consulta2.fecha_respuesta OR consulta1.fecha_respuesta IS NULL OR consulta1.fecha_respuesta IS NULL) AS consulta0 
                                INNER JOIN pqrs.buzon ON consulta0.id_buzon=buzon.id_buzon
                                INNER JOIN pqrs.canalcomunicacion ON consulta0.id_canalcomunicacion=canalcomunicacion.id_canalcomunicacion
                                INNER JOIN pqrs.proceso ON consulta0.id_proceso=proceso.id_proceso
                                INNER JOIN pqrs.clasetipopqrs ON consulta0.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs
                                INNER JOIN pqrs.tipopqrs ON clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs
                                INNER JOIN central.terceros ON terceros.identificacion=consulta0.identificacion_tercero
                                LEFT JOIN pqrs.secuencia ON consulta0.id_secuencia=secuencia.id_secuencia   
                        WHERE 1=1 ".$considtipo." ".$consclasetipo." ".$consasunto." ".$consperiodo." ".$considetercero." ".$consproceso." ".$conssecuencia." ".$consgestor." ORDER BY consulta0.id_pqrs ASC";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    if($fila['id_secuencia']!=NULL){
                        $conssec="SELECT nombre_secuencia FROM pqrs.secuencia WHERE secuencia.id_secuencia=".$fila['id_secuencia']."";
                        $ressec=ExQuery($conssec);
                        $filasec=ExFetchAssoc($ressec);
                    }
                    else{
                        $filasec['nombre_secuencia']="SIN INICIAR";
                    }
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_tipopqrs']; ?></td>
                        <td><?php echo $fila['nombre_clasetipopqrs']; ?></td>
                        <td style="width:200px;"><?php echo $fila['asunto_pqrs']; ?></td>
                        <td><?php echo $fila['fecha_pqrs']; ?></td>
                        <td><?php echo $fila['primape']." ".$fila['segape']." ".$fila['primnom']." ".$fila['segnom']." "; ?></td>
                        <td><?php echo $fila['nombre_proceso']; ?></td>
                        <td><?php echo $filasec['nombre_secuencia']; ?></td>
                        <td><?php echo $fila['gestor_pqrs']; ?></td>
                        <td><?php echo $fila['nombre_canalcomunicacion']; ?></td>
                        <!--<td style="text-align: center;"><a href="#" onclick="enviarAccion(descripcion_accionxusuario_<?php echo $fila['id_accionxusuario']; ?>, <?php echo $fila['id_accionxusuario']; ?>)"><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> </td>-->
                    </tr>
            <?php
                }
            ?>
        </table>
        
        <script>
            dhtmlXCalendarObject.prototype.langData["es"] = {
                    dateformat: '%Y-%m-%d',
                    // Nombres de mes completos
                    monthesFNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                    // Nombres de mes cortos
                    monthesSNames: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
                    // Nombre de dias completos
                    daysFNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
                    // Nombre de dias cortos
                    daysSNames: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
                    // Dia de inicio de semana. Numero 1 (Lunes) a 7(Domingo)
                    weekstart: 1
            }

            var myCalendar = new dhtmlXCalendarObject(["fechaini_busqueda","fechafin_busqueda"]);
            
            myCalendar.loadUserLanguage("es");
            myCalendar.setDateFormat("%Y-%m-%d");
            myCalendar.setSkin('dhx_terrace');
            myCalendar.hideTime();
        </script>
    </body>
</html>

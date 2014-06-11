<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if(isset($MessageTip)){
        unset($MessageTip);
    }
    
    // Sumatoria de la cantidad de PQRS permitidas en el Buzón activo
    $cons1="SELECT sum(buzonxtipopqrs.cantidad_pqrs) as res1 "
            . "FROM pqrs.buzon, pqrs.buzonxtipopqrs "
            . "WHERE buzon.id_buzon=buzonxtipopqrs.id_buzon "
            . "AND buzon.estado_buzon=1";
    $res1=ExQuery($cons1);
    $fila1=ExFetchAssoc($res1);
    
    // Para conocer cuantas peticiones PQRS han sido creadas en el Buzón activo
    $cons2="SELECT count(*) as res2 "
            . "FROM pqrs.buzon, pqrs.buzonxtipopqrs, pqrs.tipopqrs, pqrs.pqrs, pqrs.clasetipopqrs "
            . "WHERE buzon.id_buzon=buzonxtipopqrs.id_buzon "
            . "AND buzonxtipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs "
            . "AND tipopqrs.id_tipopqrs=clasetipopqrs.id_tipopqrs "
            . "AND pqrs.id_buzon=buzon.id_buzon "
            . "AND pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs "
            . "AND buzon.estado_buzon=1";
    $res2=ExQuery($cons2);
    $fila2=ExFetchAssoc($res2);
    
    if($fila1['res1']<=$fila2['res2']){
        if($fila1['res1']!=NULL){
            $consui="UPDATE pqrs.buzon SET estado_buzon=0 WHERE estado_buzon=1";
            $resui=ExQuery($consui);
        }
        
        $TypeTip = "info";
        $MessageTip = "Todas las peticiones para el buzón activo han sido registradas. Debe realizar la apertura de uno nuevo.";
    }
    
    if($Guardar){
        $campito = "";
        extract($_POST);
        foreach ($_POST as $key => $value){
            if($value==""){
                $TypeTip = "warning";
                $MessageTip = "Debe diligenciar todos los campos.";
                //echo "<script language='javascript'>alert('Llave: '+$value);</script>";
                $campito = $key;
                break;
            }
        }
        
        if(!$MessageTip){
            // Consulto la cantidad permitida para agregar peticiones PQRS según lo registrado en la apertura del buzón
            $consb="SELECT * FROM pqrs.buzon, pqrs.buzonxtipopqrs WHERE buzon.id_buzon=buzonxtipopqrs.id_buzon AND buzon.estado_buzon=1 AND buzonxtipopqrs.id_tipopqrs=$id_tipopqrs";
            $resb=ExQuery($consb);
            $filab=ExFetchAssoc($resb);
            
            // Consulto la cantidad de peticiones PQRS registradas para ese buzón
            $const="SELECT * FROM pqrs.pqrs,pqrs.tipopqrs,pqrs.clasetipopqrs WHERE clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND pqrs.id_buzon=".$filab['id_buzon']." AND clasetipopqrs.id_tipopqrs=$id_tipopqrs";
            $rest=ExQuery($const);
            $filat=ExFetchAssoc($rest);
            
            //echo $filab['cantidad_pqrs']." : ".ExNumRows($rest);
            if($filab['cantidad_pqrs']>ExNumRows($rest)){
                $cons="insert into pqrs.pqrs(asunto_pqrs, direccion_pqrs, fecha_pqrs, gestor_pqrs, id_buzon, id_canalcomunicacion, id_clasetipopqrs, id_proceso, identificacion_tercero, nombre_pabellon, nombre_usuario, nombrepersona_pqrs, numidepersona_pqrs, otraclase_pqrs, paciente_pqrs, telefono_pqrs, numidepaciente_pqrs, identificacion_paciente, identificacion_persona, id_enteemisor)"
                    . "values ('".$asunto_pqrs."', '".$direccion_pqrs."', '".$fecha_pqrs."', '".$gestor_pqrs."', '".$id_buzon."', '".$id_canalcomunicacion."', '".$id_clasetipopqrs."', '".$id_proceso."', '".$identificacion_tercero."', '".$nombre_pabellon."', '".$usuario[1]."', '".$nombrepersona_pqrs."', '".$numidepersona_pqrs."', '".$otraclase_pqrs."', '".$paciente_pqrs."', '".$telefono_pqrs."', '".$numidepaciente_pqrs."', '".$identificacion_paciente."', '".$identificacion_persona."', '".$id_enteemisor."') returning *";
                $res=ExQuery($cons);
                $fila=ExFetchAssoc($res);

                $usuarios_imp = explode(",", $usuarios_implicados);

                foreach($usuarios_imp as $key => $value){
                    $uimp = trim($value);
                    if($uimp!=""){
                        $consui="insert into pqrs.implicadoxpqrs(id_pqrs, id_usuario) "
                                . "values ('".$fila['id_pqrs']."', '".$uimp."')";
                        $resui=ExQuery($consui);
                    }
                }

                header("Location: /PQRS/PQRS.php?DatNameSID=".$DatNameSID);
            }
            else{
                $TypeTip = "error";
                $MessageTip = "Ha ingresado la máxima cantidad de ".$filat['nombre_tipopqrs']." permitidas para este buzón.";
                //echo "<script language='javascript'>alert('Llave: '+$value);</script>";
                $campito = $key;
            }
        }
    }
?>
<html>
    <head>
        <script language='javascript' src="/calendario/popcalendar.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        <script src="link/jquery-1.10.2.js"></script>
        <script src="link/jquery-ui.js"></script>
        <link rel="stylesheet" href="link/multiselect.css">
        <?php
        // Consulto los usuarios del sistema
        $consu="SELECT nombre,usuario FROM central.usuarios";
        $resu=ExQuery($consu);
        ?>
        <script>
	$(function() {
		var availableTags = [
                    <?php
                        $i=0;
                        while($filau=ExFetchAssoc($resu)){
                            echo "\"".$filau['usuario']."\"";
                            $i++;
                            if(ExNumRows($resu)!=$i){
                                echo ",";
                            }
                        }
                    ?>
		];
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#usuarios_implicados" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "ui-autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
	});
	</script>
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
                                
                                $consconteo2="SELECT nombre_tipopqrs, cantidad_pqrs FROM pqrs.tipopqrs,pqrs.buzon,pqrs.buzonxtipopqrs WHERE buzon.id_buzon=buzonxtipopqrs.id_buzon AND tipopqrs.id_tipopqrs=buzonxtipopqrs.id_tipopqrs AND buzon.estado_buzon=1 ORDER BY nombre_tipopqrs ASC";
                                $resconteo2=ExQuery($consconteo2);
                                
                                while($filaconteo2=ExFetchAssoc($resconteo2)){
                                    if($filaconteo2['cantidad_pqrs']=="")
                                        $filaconteo2['cantidad_pqrs']=0;
                                    $arreglotipopqrs[$filaconteo2['nombre_tipopqrs']] = $filaconteo2['cantidad_pqrs'];
                                }
                                
                                //$consconteo="SELECT tipopqrs.nombre_tipopqrs, consulta.conteo FROM pqrs.tipopqrs LEFT JOIN (SELECT tipopqrs.id_tipopqrs,tipopqrs.nombre_tipopqrs,count(*) AS conteo FROM pqrs.buzon,pqrs.buzonxtipopqrs,pqrs.pqrs,pqrs.tipopqrs,pqrs.clasetipopqrs WHERE buzonxtipopqrs.id_buzon=buzon.id_buzon AND buzonxtipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND tipopqrs.id_tipopqrs=clasetipopqrs.id_tipopqrs AND buzon.estado_buzon=1 GROUP BY tipopqrs.nombre_tipopqrs,tipopqrs.id_tipopqrs) AS consulta ON tipopqrs.id_tipopqrs=consulta.id_tipopqrs ORDER BY nombre_tipopqrs ASC";
                                $consconteo="SELECT tipopqrs.nombre_tipopqrs, consulta.conteo FROM pqrs.tipopqrs LEFT JOIN (SELECT tipopqrs.id_tipopqrs,tipopqrs.nombre_tipopqrs,count(*) AS conteo FROM pqrs.buzon,pqrs.buzonxtipopqrs,pqrs.pqrs,pqrs.tipopqrs,pqrs.clasetipopqrs WHERE buzonxtipopqrs.id_buzon=buzon.id_buzon AND buzonxtipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND tipopqrs.id_tipopqrs=clasetipopqrs.id_tipopqrs AND pqrs.id_buzon=buzon.id_buzon AND buzon.estado_buzon=1 GROUP BY tipopqrs.nombre_tipopqrs,tipopqrs.id_tipopqrs) AS consulta ON tipopqrs.id_tipopqrs=consulta.id_tipopqrs ORDER BY nombre_tipopqrs ASC";
                                $resconteo=ExQuery($consconteo);
                                
                                if(ExNumRows($resconteo2)>0){
                                    $mensajebuzon = "ESTE BUZÓN TIENE ";
                                    while($filaconteo=ExFetchAssoc($resconteo)){
                                        if($filaconteo['conteo']=="")
                                            $filaconteo['conteo']=0;
                                        foreach($arreglotipopqrs as $key => $value){
                                            if($key==$filaconteo['nombre_tipopqrs']){
                                                $valor = $value;
                                                break;
                                            }
                                        }
                                        $mensajebuzon .= $filaconteo['conteo']."/".$valor." ".$filaconteo['nombre_tipopqrs']." ";
                                    }
                                }
                            ?>
                            <td>
                                <input type="text" name="codigo_buzon" id="codigo_buzon" value="<?php echo substr(str_replace("-", "", $fila['fecha_buzon']),0,8); ?>" style="width:100px;" readonly>
                                <input type="hidden" name="id_buzon" id="id_buzon" value="<?php echo $fila['id_buzon']; ?>"> <?php echo $mensajebuzon; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FECHA</td>
                            <td><input type="text" name="fecha_pqrs" id="fecha_pqrs" readonly onClick="popUpCalendar(this, FORMA.fecha_pqrs, 'yyyy-mm-dd')" style="width:200px;" value="<?php echo $fecha_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PERSONA QUIEN REPORTA</td>
                            <td><input name="nombrepersona_pqrs" type="Text" id="nombre_usuario" style="width:200px;" value="<?php echo $nombrepersona_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO DOCUMENTO</td>
                            <td>
                                <select name="identificacion_persona" id="identificacion_persona" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.tiposdocumentos";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($identificacion_persona==$fila['codigo'])
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
                            <td style="text-align: right;">TELÉFONO RESPUESTA</td>
                            <td><input name="telefono_pqrs" type="Text" id="telefono_pqrs" style="width:200px;" value="<?php echo $telefono_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE PACIENTE</td>
                            <td><input name="paciente_pqrs" type="Text" id="paciente_pqrs" style="width:200px;" value="<?php echo $paciente_pqrs; ?>"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO DOCUMENTO PACIENTE</td>
                            <td>
                                <select name="identificacion_paciente" id="identificacion_paciente" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from central.tiposdocumentos";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($identificacion_paciente==$fila['codigo'])
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
                            <td style="text-align: right;">NÚMERO DE IDENTIFICACIÓN PACIENTE</td>
                            <td><input name="numidepaciente_pqrs" type="Text" id="numidepaciente_pqrs" style="width:200px;" value="<?php echo $numidepaciente_pqrs; ?>"></td>
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
                            <td style="text-align: right;">ENTE EMISOR</td>
                            <td>
                            <select name="id_enteemisor" id="id_enteemisor" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.enteemisor order by nombre_enteemisor";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_enteemisor==$fila['id_enteemisor'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_enteemisor']."' ".$sel.">".$fila['nombre_enteemisor']."</option>";
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
                            <td style="text-align: right;">COLABORADORES IMPLICADOS</td>
                            <td><input name="usuarios_implicados" type="Text" id="usuarios_implicados" style="width:200px;" value="<?php echo $usuarios_implicados; ?>"></td>
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
    <?php
    if($campito!=''){
    ?>
        <script language='javascript'>
            document.forms[0].elements["<?php echo $campito; ?>"].focus();
        </script>
    <?php
    }
    ?>
</html>

<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if($Guardar){
        foreach($buzonxtipo as $key => $value){
            if($value==""){
                $TypeTip = "warning";
                $MessageTip = "Debe diligenciar todos los campos.";
                break;
            }
        }
        if(!$MessageTip){
            // Sumatoria de la cantidad de PQRS permitidas en el Buzón activo
            // Fecha creación: 04 de marzo de 2014
            $cons1="SELECT sum(buzonxtipopqrs.cantidad_pqrs) as res1 "
                    . "FROM pqrs.buzon, pqrs.buzonxtipopqrs "
                    . "WHERE buzon.id_buzon=buzonxtipopqrs.id_buzon "
                    . "AND buzon.estado_buzon=1";
            $res1=ExQuery($cons1);
            $fila1=ExFetchAssoc($res1);

            // Para conocer cuantas peticiones PQRS han sido creadas en el Buzón activo
            // Fecha creación: 04 de marzo de 2014
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

            // Compara las cantidades e inserta en la BD
            // Fecha creación: 04 de marzo de 2014
            if($fila1['res1']<$fila2['res2']){
                $fecha_buzon = date("Y-m-d H:i:s");
                $cons="insert into pqrs.buzon(fecha_buzon,nombre_usuario) values ('$fecha_buzon','$usuario[0]') returning id_buzon";
                $res=ExQuery($cons);
                $fila=ExFetchAssoc($res);

                foreach($buzonxtipo as $key => $value){
                    $consbxt="insert into pqrs.buzonxtipopqrs(id_buzon, id_tipopqrs, cantidad_pqrs) values ('".$fila['id_buzon']."','".$key."','".$value."') returning id_buzon";
                    $resbxt=ExQuery($consbxt);
                }
            }
            else{
                // Tipos de mensajes: info, success, warning, error
                $TypeTip = "error";
                $MessageTip = "No es posible crear un nuevo buzón hasta que termine de ingresar todas las peticiones PQRS pendientes.";
            }
        }
    }
    /*if($editar){
        $cons="select * from pqrs.buzon, pqrs.buzonxtipopqrs where buzon.id_buzon=buzonxtipopqrs.id_buzon and buzon.id_buzon=$editar and estado_buzon=1";
        $res=ExQuery($cons);
        $fila=ExFetchAssoc($res);
    }*/
    if($editarb){
        foreach($buzonxtipo as $key => $value){
            if($value==""){
                $TypeTip = "warning";
                $MessageTip = "Debe diligenciar todos los campos.";
                break;
            }
        }
        if(!$MessageTip){
            foreach($buzonxtipo as $key => $value){
                $consbxt="update pqrs.buzonxtipopqrs set cantidad_pqrs='$value' where id_tipopqrs=$key and id_buzon=$id_buzon";
                $resbxt=ExQuery($consbxt);
            }
        }
    }
    if($eliminar){
        /*$cons="delete from pqrs.buzon where id_buzon=$eliminar";
        $res=ExQuery($cons);*/
    }
?>
<html>
    <head>
        <!--<script language='javascript' src="/calendario/popcalendar.js"></script>-->
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <?php
        if($MessageTip!=''){
            echo "<div class='".$TypeTip."'>".$MessageTip."</div>";
        }
        ?>
        <form name='FORMA' method="post" action="/PQRS/Buzon.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td style="text-align: right;"></td>
                            <td style="font-weight: bold;">BUZÓN</td>
                        </tr>
                        <?php
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
                            $mensajebuzon = "EL BUZÓN ACTIVO TIENE REGISTRADAS ";
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
                        <tr>
                            <td style="text-align: right;"></td>
                            <td><?php echo $mensajebuzon; ?></td>
                        </tr>
                        <?php
                        // Cuando se va ha editar se consulta los valores actuales del buzón en buzonxtipopqrs
                        // Fecha creación: 04 de marzo de 2014
                        if(!$editar){
                            $cons="select * from pqrs.tipopqrs";
                            $res=ExQuery($cons);
                        }
                        else{
                            $cons="SELECT * FROM pqrs.tipopqrs, pqrs.buzonxtipopqrs, pqrs.buzon "
                                    . "WHERE tipopqrs.id_tipopqrs=buzonxtipopqrs.id_tipopqrs "
                                    . "AND buzon.id_buzon=buzonxtipopqrs.id_buzon "
                                    . "AND buzon.estado_buzon=1";
                            $res=ExQuery($cons);
                        }
                        
                        while($fila=ExFetchAssoc($res)){
                            $campo = "<tr>";
                            $campo .= "<td style='text-align: right;'>NÚMERO DE ".$fila['nombre_tipopqrs']."</td>";
                            $campo .= "<td><input type='text' name='buzonxtipo[".$fila['id_tipopqrs']."]' id='buzonxtipo[".$fila['id_tipopqrs']."]' value='".$fila['cantidad_pqrs']."' style='width:200px;'></td>";
                            $campo .= "</tr>";
                            echo $campo;
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_buzon" id="id_buzon" value="<?php echo $editar; ?>">
                                <?php
                                }
                                else{
                                ?>
                                    <input type="submit" name="Guardar" id="Guardar" value="Nuevo">
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                    <th>ID</th><th>FECHA</th><th>USUARIO QUE REGISTRA</th><th>PQRS</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.buzon order by fecha_buzon desc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo substr(str_replace("-", "", $fila['fecha_buzon']),0,8); ?></td>
                        <td><?php echo $fila['fecha_buzon']; ?></td>
                        <td><?php echo $fila['nombre_usuario']; ?></td>
                        <td>
                            <?php
                            $cons2="select tipopqrs.nombre_tipopqrs, buzonxtipopqrs.cantidad_pqrs from pqrs.buzon,pqrs.buzonxtipopqrs,pqrs.tipopqrs where buzon.id_buzon=buzonxtipopqrs.id_buzon and buzonxtipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs and buzon.id_buzon=".$fila['id_buzon']." order by fecha_buzon desc";
                            $res2=ExQuery($cons2);
                            
                            while($fila2=ExFetchAssoc($res2)){
                                echo $fila2['nombre_tipopqrs'].": ".$fila2['cantidad_pqrs']."<br>";
                            }
                            ?>
                        </td>
                        <td style="text-align: center;">
                            <?php
                            // Limita el enlace para edición del buzón a 4 horas
                            // Fecha creación: 04 de marzo de 2014
                            //$consfb="SELECT fecha_buzon FROM pqrs.buzon WHERE estado_buzon=1";
                            //$resfb=ExQuery($consfb);
                            //$filafb=ExFetchAssoc($resfb);
                            
                            $fecha_bd = date($fila['fecha_buzon']);
                            $time_bd = strtotime($fecha_bd."+4hours");
                            
                            $fecha_actual = date("Y-m-d H:i:s");
                            $time_actual = strtotime($fecha_actual);
                            
                            if(($time_bd>$time_actual)&&($fila['estado_buzon']==1)){
                            ?>
                                <a href="/PQRS/Buzon.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_buzon']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <?php
                            }
                            ?>
                            <!--<a href="/PQRS/Buzon.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_buzon']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>-->
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

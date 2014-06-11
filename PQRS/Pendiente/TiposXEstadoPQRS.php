<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    // Permite guardar los campos de la tabla en la tabla de interseccion estadoxtipo
    if($Guardar){
        foreach($estadoxtipo as $keyy => $valuey){
            foreach($valuey as $keyx => $valuex){
                //echo "".$keyx."-".$keyy.">>>".$valuey."<br>";
                //echo "".$keyx."-".$keyy.">>>".$estadoxtipo_vobo[$keyy][$keyx]."<br>";
                if($estadoxtipo_vobo[$keyy][$keyx]=="on")
                    $vobo = 1;
                else
                    $vobo = 0;
                $cons="update pqrs.estadoxtipo set nivel_estadoxtipo='".$valuex."', reqvobo_estadoxtipo=$vobo where id_estadopqrs='".$keyy."' and id_tipopqrs='".$keyx."'";
                $res=ExQuery($cons);
            }
        }
    }
    if($editar){
        $cons="select * from pqrs.estadopqrs where id_estadopqrs=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.estadopqrs set nombre_estadopqrs='$nombre_estadopqrs', definicion_estadopqrs='$definicion_estadopqrs' where id_estadopqrs=$id_estadopqrs";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    if($eliminar){
        $cons="delete from pqrs.estadopqrs where id_estadopqrs=$eliminar";
        $res=ExQuery($cons);
    }
    
    /*$cons_pre="select * from pqrs.estadoxtipo";
    $res_pre=ExQuery($cons_pre);
    $fila_pre=ExFetchAssoc($res_pre);*/
?>
<html>
    <head>
        <!-- CSS goes in the document HEAD or added to your external stylesheet -->
    <style type="text/css">
        table.imagetable {
                font-family: verdana,arial,sans-serif;
                font-size:11px;
                color:#333333;
                border-width: 1px;
                border-color: #999999;
                border-collapse: collapse;
        }
        table.imagetable th {
                background:#b5cfd2 url('../Imgs/cell-blue.jpg');
                border-width: 1px;
                padding: 8px;
                border-style: solid;
                border-color: #999999;
        }
        table.imagetable td {
                background:#dcddc0 url('../Imgs/cell-grey.jpg');
                border-width: 1px;
                padding: 8px;
                border-style: solid;
                border-color: #999999;
        }
    </style>
    </head>
    
    <body>
        <form action="TiposXEstadoPQRS.php">
        <table class="imagetable">
            <tr>
                    <th>ESTADO \ TIPOS</th>
            <?php
                $cons="select * from pqrs.tipopqrs";
            	$res=ExQuery($cons);
                //echo ExError();
            	//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <th><?php echo $fila['nombre_tipopqrs']; ?></th>
            <?php
                }
            ?>
            </tr>
            <?php
                $cons="select * from pqrs.estadopqrs";
            	$res=ExQuery($cons);
                //echo ExError();
            	//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_estadopqrs']; ?></td>
                        <?php
                            $consestado="select * from pqrs.tipopqrs";
                            $resestado=ExQuery($consestado);
                            //echo ExError();
                            //$fila=ExFetch($res);
                            while($filaestado=ExFetchAssoc($resestado)){
                                $consnivel="select * from pqrs.estadoxtipo where id_tipopqrs='".$filaestado['id_tipopqrs']."' and id_estadopqrs='".$fila['id_estadopqrs']."'";
                                $resnivel=ExQuery($consnivel);
                                $filanivel=ExFetchAssoc($resnivel);
                                
                                $checked="";
                                if($filanivel['reqvobo_estadoxtipo']==1)
                                    $checked="checked";
                                ?>
                        <td><input type="text" name="estadoxtipo[<?php echo $fila['id_estadopqrs']."][".$filaestado['id_tipopqrs']; ?>]" id="estadoxtipo[<?php echo $fila['id_estadopqrs']."][".$filaestado['id_tipopqrs']; ?>]" value="<?php echo $filanivel['nivel_estadoxtipo']; ?>">Vo.Bo.<input type="checkbox" id="estadoxtipo_vobo[<?php echo $fila['id_estadopqrs']."][".$filaestado['id_tipopqrs']; ?>]" name="estadoxtipo_vobo[<?php echo $fila['id_estadopqrs']."][".$filaestado['id_tipopqrs']; ?>]" <?php echo $checked; ?>></td>
                        <?php   
                            }
                        ?>
                    </tr>
            <?php
                }
            ?>
        </table>
            <input type="submit" name="Guardar" id="Guardar" value="Enviar">
        </form>
    </body>
</html>

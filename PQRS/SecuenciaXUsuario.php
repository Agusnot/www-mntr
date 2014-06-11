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
            $cons="insert into pqrs.secuenciaxusuario(id_secuencia, id_usuario) values ('$id_secuencia', '$id_usuario')";
            $res=ExQuery($cons);
            $fila=ExFetchAssoc($res);
        }
    }
    /*if($editar){
        $conse="select * from pqrs.jerarquia where id_jerarquia=$editar";
        $rese=ExQuery($conse);
        //echo ExError();
        $filae=ExFetchAssoc($rese);
    }
    if($editarb){
        $cons="update pqrs.jerarquia set nombre_jerarquia='$nombre_jerarquia', padre_jerarquia='$padre_jerarquia' where id_jerarquia=$id_jerarquia";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }*/
    if($eliminar){
        $cons2="delete from pqrs.secuenciaxusuario where id_secuenciaxusuario='".$eliminar."'";
        $res2=ExQuery($cons2);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <?php
        if($MessageTip!=''){
            echo "<div class='".$TypeTip."'>".$MessageTip."</div>";
        }
        ?>
        <form name='FORMA' method="post" action="/PQRS/SecuenciaXUsuario.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">SECUENCIA</td>
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
                            <td style="text-align: right;">SECUENCIA</td>
                            <td>
                                <select name="id_secuencia" id="id_secuencia" style="width:200px; font-size: 12px;" onchange="FORMA.submit();">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                    if($id_tipopqrs){
                                        $cons="select * from pqrs.secuencia where id_tipopqrs='".$id_tipopqrs."' order by id_secuencia asc";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($fila['id_secuencia']==$id_secuencia)
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_secuencia']."' ".$sel.">".$fila['nombre_secuencia']."</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">USUARIO</td>
                            <td>
                                <select name="id_usuario" id="id_usuario" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select nombre, usuario from central.usuarios ORDER BY usuario ASC";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($id_usuario==$fila['usuario'])
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
                                    if($id_secuencia){
                                ?>
                                        <input type="submit" name="Guardar" id="Guardar" value="Nuevo">
                                <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                </table>
        </form>
        <?php
        if($id_secuencia){
        ?>
        <table class="imagetable">
            <tr>
                    <th>ID SECUENCIA</th><th>USUARIO</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.secuenciaxusuario where id_secuencia='".$id_secuencia."' order by id_secuencia asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['id_secuencia']; ?></td>
                        <td><?php echo $fila['id_usuario']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/SecuenciaXUsuario.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_secuenciaxusuario']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/SecuenciaXUsuario.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_secuenciaxusuario']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
        }
            ?>
        </table>
    </body>
</html>

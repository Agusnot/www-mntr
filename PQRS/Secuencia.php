<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    if($Guardar){
        if($reqvobo_secuencia=="on")
            $vobo = 1;
        else
            $vobo = 0;
        $cons="insert into pqrs.secuencia(padre_secuencia, nombre_secuencia, id_tipopqrs, id_tiposecuencia, reqvobo_secuencia) values ('$padre_secuencia', '$nombre_secuencia', '$id_tipopqrs', '$id_tiposecuencia', '$vobo')";
        $res=ExQuery($cons);
        $fila=ExFetchAssoc($res);
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
        $cons2="delete from pqrs.secuencia where id_secuencia='".$eliminar."'";
        $res2=ExQuery($cons2);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <form name='FORMA' method="post" action="/PQRS/Secuencia.php?DatNameSID=<?php echo $DatNameSID; ?>">
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
                            <td style="text-align: right;">PADRE</td>
                            <td>
                                <select name="padre_secuencia" id="padre_secuencia" style="width:200px; font-size: 12px;">
                                    <option value="0" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                    if($id_tipopqrs){
                                        $cons="select * from pqrs.secuencia where id_tipopqrs='".$id_tipopqrs."' order by id_secuencia asc";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($filae['padre_secuencia']==$fila['id_secuencia'])
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
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_secuencia" id="nombre_secuencia" value="<?php echo $filae['nombre_secuencia']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">DECISIONES POSIBLES</td>
                            <td><input type="text" name="id_tiposecuencia" id="id_tiposecuencia" value="<?php echo $filae['id_tiposecuencia']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">REQUIERE Vo.Bo.</td>
                            <td><input type="checkbox" name="reqvobo_secuencia" id="reqvobo_secuencia"></td>
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
                                    if($id_tipopqrs){
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
        if($id_tipopqrs){
        ?>
        <table class="imagetable">
            <tr>
                    <th>ID</th><th>NOMBRE SECUENCIA</th><th>PADRE</th><th>REQUIERE Vo.Bo.</th><th>DECISIONES</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.secuencia where id_tipopqrs='".$id_tipopqrs."' order by id_secuencia asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    $cons2="select * from pqrs.secuencia where id_secuencia=".$fila['padre_secuencia'];
                    $res2=ExQuery($cons2);
                    $fila2=ExFetchAssoc($res2);
                    ?>
                    <tr>
                        <td><?php echo $fila['id_secuencia']; ?></td>
                        <td><?php echo $fila['nombre_secuencia']; ?></td>
                        <td><?php echo $fila2['nombre_secuencia']; ?></td>
                        <td><?php echo $fila['reqvobo_secuencia']; ?></td>
                        <td><?php echo $fila['id_tiposecuencia']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/Secuencia.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_secuencia']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/Secuencia.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_secuencia']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
        }
            ?>
        </table>
    </body>
</html>

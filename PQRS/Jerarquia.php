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
            $cons="insert into pqrs.jerarquia(nombre_jerarquia,padre_jerarquia) values ('$nombre_jerarquia', '$padre_jerarquia')";
            $res=ExQuery($cons);
            $fila=ExFetchAssoc($res);
        }
    }
    if($editar){
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
    }
    if($eliminar){
        $cons2="delete from pqrs.jerarquia where id_jerarquia='".$eliminar."'";
        $res2=ExQuery($cons2);
        
        $cons="delete from pqrs.jerarquia where id_jerarquia=$eliminar";
        $res=ExQuery($cons);
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
        <form name='FORMA' method="post" action="/PQRS/Jerarquia.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">JERARQUÍA</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_jerarquia" id="nombre_jerarquia" value="<?php echo $filae['nombre_jerarquia']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PADRE</td>
                            <td>
                                <select name="padre_jerarquia" id="padre_jerarquia" style="width:200px; font-size: 12px;">
                                    <option value="0" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.jerarquia";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            if($filae['padre_jerarquia']==$fila['id_jerarquia'])
                                                $sel = ' selected';
                                            else
                                                $sel = '';
                                            echo "<option value='".$fila['id_jerarquia']."' ".$sel.">".$fila['nombre_jerarquia']."</option>";
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
                                    <input type="hidden" name="id_jerarquia" id="id_jerarquia" value="<?php echo $editar; ?>">
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
                    <th>NOMBRE JERARQUIA</th><th>PADRE</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.jerarquia";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    $cons2="select * from pqrs.jerarquia where id_jerarquia=".$fila['padre_jerarquia'];
                    $res2=ExQuery($cons2);
                    $fila2=ExFetchAssoc($res2);
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_jerarquia']; ?></td>
                        <td><?php echo $fila2['nombre_jerarquia']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/Jerarquia.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_jerarquia']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/Jerarquia.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_jerarquia']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

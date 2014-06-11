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
            $cons="insert into pqrs.clasetipopqrs(nombre_clasetipopqrs, id_tipopqrs) values ('".$nombre_clasetipopqrs."','".$id_tipopqrs."')";
            $res=ExQuery($cons);
        }
    }
    if($editar){
        $cons="select * from pqrs.clasetipopqrs where id_clasetipopqrs=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.clasetipopqrs set nombre_clasetipopqrs='$nombre_clasetipopqrs', id_tipopqrs='$id_tipopqrs' where id_clasetipopqrs=$id_clasetipopqrs";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    if($eliminar){
        $cons2="delete from pqrs.clasetipopqrs where id_clasetipopqrs='".$eliminar."'";
        $res2=ExQuery($cons2);
    }
?>
<html>
    <head>
        <!-- CSS goes in the document HEAD or added to your external stylesheet -->
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <?php
        if($MessageTip!=''){
            echo "<div class='".$TypeTip."'>".$MessageTip."</div>";
        }
        ?>
        <form name='FORMA' method="post" action="/PQRS/ClasificacionTipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">CLASIFICACIÓN TIPOS PQRS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_clasetipopqrs" id="nombre_clasetipopqrs" value="<?php echo $fila['nombre_clasetipopqrs']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">TIPO PQRS</td>
                            <td>
                                <select name="id_tipopqrs" id="id_tipopqrs" style="width:200px; font-size: 12px;">
                                    <option value="" style="color:#cccccc;">SELECCIONA UNA OPCIÓN</option>
                                    <?php
                                        $cons="select * from pqrs.tipopqrs";
                                        $res=ExQuery($cons);
                                        while($fila=ExFetchAssoc($res)){
                                            echo "<option value=".$fila['id_tipopqrs'].">".$fila['nombre_tipopqrs']."</option>";
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
                                    <input type="hidden" name="id_clasetipopqrs" id="id_clasetipopqrs" value="<?php echo $editar; ?>">
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
                    <th>TIPO PQRS</th><th>NOMBRE CLASIFICACIÓN</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.clasetipopqrs, pqrs.tipopqrs where clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs order by clasetipopqrs.id_tipopqrs, id_clasetipopqrs";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_tipopqrs']; ?></td>
                        <td><?php echo $fila['nombre_clasetipopqrs']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/ClasificacionTipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_clasetipopqrs']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/ClasificacionTipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_clasetipopqrs']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

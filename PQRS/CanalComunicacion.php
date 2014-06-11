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
            $cons="insert into pqrs.canalcomunicacion(nombre_canalcomunicacion) values ('$nombre_canalcomunicacion')";
            $res=ExQuery($cons);
        }
    }
    if($editar){
        $cons="select * from pqrs.canalcomunicacion where id_canalcomunicacion=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.canalcomunicacion set nombre_canalcomunicacion='$nombre_canalcomunicacion' where id_canalcomunicacion=$id_canalcomunicacion";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    if($eliminar){
        $cons="delete from pqrs.canalcomunicacion where id_canalcomunicacion=$eliminar";
        $res=ExQuery($cons);
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
        <form name='FORMA' method="post" action="/PQRS/CanalComunicacion.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">CANALES DE COMUNICACIÓN</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_canalcomunicacion" id="nombre_canalcomunicacion" value="<?php echo $fila['nombre_canalcomunicacion']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_canalcomunicacion" id="id_canalcomunicacion" value="<?php echo $fila['id_canalcomunicacion']; ?>">
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
                    <th>NOMBRE</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.canalcomunicacion order by id_canalcomunicacion asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_canalcomunicacion']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/CanalComunicacion.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_canalcomunicacion']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/CanalComunicacion.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_canalcomunicacion']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

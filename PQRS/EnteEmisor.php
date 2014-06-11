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
            $cons="insert into pqrs.enteemisor(nombre_enteemisor) values ('$nombre_enteemisor')";
            $res=ExQuery($cons);
        }
    }
    if($editar){
        $cons="select * from pqrs.enteemisor where id_enteemisor=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.enteemisor set nombre_enteemisor='$nombre_enteemisor' where id_enteemisor=$id_enteemisor";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    if($eliminar){
        $cons="delete from pqrs.enteemisor where id_enteemisor=$eliminar";
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
        <form name='FORMA' method="post" action="/PQRS/EnteEmisor.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">ENTES EMISORES</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_enteemisor" id="nombre_enteemisor" value="<?php echo $fila['nombre_enteemisor']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_enteemisor" id="id_enteemisor" value="<?php echo $fila['id_enteemisor']; ?>">
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
                $cons="select * from pqrs.enteemisor order by id_enteemisor asc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_enteemisor']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/EnteEmisor.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_enteemisor']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/EnteEmisor.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_enteemisor']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

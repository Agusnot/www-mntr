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
            $cons="insert into pqrs.tipopqrs(nombre_tipopqrs) values ('$nombre_tipopqrs') returning id_tipopqrs";
            $res=ExQuery($cons);
            $fila=ExFetchAssoc($res);

            $cons2="select * from pqrs.estadopqrs";
            $res2=ExQuery($cons2);
            while($fila2=ExFetchAssoc($res2)){
                $cons3="insert into pqrs.estadoxtipo(id_cargo,id_estadopqrs,id_tipopqrs,nivel_estadoxtipo) values ('0','".$fila2['id_estadopqrs']."','".$fila['id_tipopqrs']."','0')";
                $res3=ExQuery($cons3);
            }
        }
    }
    if($editar){
        $cons="select * from pqrs.tipopqrs where id_tipopqrs=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.tipopqrs set nombre_tipopqrs='$nombre_tipopqrs' where id_tipopqrs=$id_tipopqrs";
        $res=ExQuery($cons);
        //echo ExError();
        //$fila=ExFetchAssoc($res);
    }
    if($eliminar){
        $cons2="delete from pqrs.estadoxtipo where id_tipopqrs='".$eliminar."'";
        $res2=ExQuery($cons2);
        
        $cons="delete from pqrs.tipopqrs where id_tipopqrs=$eliminar";
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
        <form name='FORMA' method="post" action="/PQRS/TipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">TIPOS PQRS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_tipopqrs" id="nombre_tipopqrs" value="<?php echo $fila['nombre_tipopqrs']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_tipopqrs" id="id_tipopqrs" value="<?php echo $editar; ?>">
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
                    <th>NOMBRE TIPO PQRS</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from pqrs.tipopqrs";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_tipopqrs']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/TipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_tipopqrs']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/TipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_tipopqrs']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

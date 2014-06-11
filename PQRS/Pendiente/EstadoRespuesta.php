<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    if($Guardar){
        $cons="insert into pqrs.estadorespuesta(nombre_estadorespuesta) values ('$nombre_estadorespuesta')";
        $res=ExQuery($cons);
    }
    if($editar){
        $cons="select * from pqrs.estadorespuesta where id_estadorespuesta=$editar";
        $res=ExQuery($cons);
        //echo ExError();
        $fila=ExFetchAssoc($res);
    }
    if($editarb){
        $cons="update pqrs.estadorespuesta set nombre_estadorespuesta='$nombre_estadorespuesta' where id_estadorespuesta=$id_estadorespuesta";
        $res=ExQuery($cons);
        //echo ExError();
    }
    if($eliminar){
        $cons="delete from pqrs.estadorespuesta where id_estadorespuesta='".$eliminar."'";
        $res=ExQuery($cons);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
    </head>
    
    <body>
        <form name='FORMA' method="post" action="/PQRS/EstadoRespuesta.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">ESTADOS RESPUESTA</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_estadorespuesta" id="nombre_estadorespuesta" value="<?php echo $fila['nombre_estadorespuesta']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_estadorespuesta" id="id_tipopqrs" value="<?php echo $editar; ?>">
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
                $cons="select * from pqrs.estadorespuesta";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre_estadorespuesta']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/EstadoRespuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_estadorespuesta']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/EstadoRespuesta.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_estadorespuesta']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

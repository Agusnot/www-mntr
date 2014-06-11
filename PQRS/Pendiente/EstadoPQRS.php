<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    if($Guardar){
        $cons="insert into pqrs.estadopqrs(nombre_estadopqrs,definicion_estadopqrs) values ('$nombre_estadopqrs','$definicion_estadopqrs') returning id_estadopqrs";
        $res=ExQuery($cons);
        $fila=ExFetchAssoc($res);
        
        $cons2="select * from pqrs.tipopqrs";
        $res2=ExQuery($cons2);
        while($fila2=ExFetchAssoc($res2)){
            $cons3="insert into pqrs.estadoxtipo(id_cargo,id_estadopqrs,id_tipopqrs,nivel_estadoxtipo) values ('0','".$fila['id_estadopqrs']."','".$fila2['id_tipopqrs']."','0')";
            $res3=ExQuery($cons3);
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
        $cons2="delete from pqrs.estadoxtipo where id_estadopqrs='".$eliminar."'";
        $res2=ExQuery($cons2);
        
        $cons="delete from pqrs.estadopqrs where id_estadopqrs=$eliminar";
        $res=ExQuery($cons);
    }
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
        <form name='FORMA' method="post" action="/PQRS/EstadoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">ESTADOS PQRS</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">NOMBRE</td>
                            <td><input type="text" name="nombre_estadopqrs" id="nombre_estadopqrs" value="<?php echo $fila['nombre_estadopqrs']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">DEFINICIÓN</td>
                            <td><textarea rows="4" cols="50" name="definicion_estadopqrs" id="definicion_estadopqrs"><?php echo $fila['definicion_estadopqrs']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                <?php
                                if($editar){
                                ?>
                                    <input type="submit" name="editarb" id="editarb" value="Editar">
                                    <input type="hidden" name="id_estadopqrs" id="id_estadopqrs" value="<?php echo $fila['id_estadopqrs']; ?>">
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
                    <th>NOMBRE</th><th>DEFINICIÓN</th><th>OPERACIONES</th>
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
                        <td><?php echo $fila['definicion_estadopqrs']; ?></td>
                        <td style="text-align: center;">
                            <a href="/PQRS/EstadoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_estadopqrs']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/PQRS/EstadoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_estadopqrs']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

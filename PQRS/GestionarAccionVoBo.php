<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if($accionxusuario){
        $fecha_actual = date("Y-m-d H:i:s");
        
        if($voboaccionxusuario=="si")
            $cons="UPDATE pqrs.accionxusuario SET vobodescripcion_accionxusuario='$vobodescripcion_accionxusuario', vobofecha_accionxusuario='$fecha_actual', vobo_accionxusuario='2' where id_accionxusuario=$accionxusuario";
        
        if($voboaccionxusuario=="no")
            $cons="UPDATE pqrs.accionxusuario SET vobodescripcion_accionxusuario='$vobodescripcion_accionxusuario', vobofecha_accionxusuario='$fecha_actual', vobo_accionxusuario='0' where id_accionxusuario=$accionxusuario";
        
        $res=ExQuery($cons);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        
        <script src="link/js/jquery-1.10.2.js"></script>
        <script src="link/js/jquery-ui-1.10.4.js"></script>
        <script>
            function enviarAccion (campo, iden, estado){
                //$(campo).append("Hola");
                $.ajax({
                    type: "POST",
                    url: "GestionarAccionVoBo.php",
                    data: { vobodescripcion_accionxusuario: $(campo).val(), accionxusuario: iden, voboaccionxusuario: estado }
                })
                .done(function( msg ) {
                    window.location.reload();
                    //$('#dialog').dialog( "close" );
                });
            }
        </script>
    </head>
    <body>
        <p style="font-family: verdana,arial,sans-serif; font-size:12px; font-weight:bold;">ACCIONES PENDIENTES POR VISTO BUENO</p>
        <table class="imagetable">
            <tr>
                <th>ACCION</th><th>FECHA</th><th>FECHA LIMITE</th><th>RESPUESTA</th><th>VISTO BUENO</th><th>OPERACIONES</th>
            </tr>
            <?php
                //$cons="SELECT * FROM pqrs.pqrs, pqrs.clasetipopqrs, pqrs.tipopqrs WHERE pqrs.id_clasetipopqrs=clasetipopqrs.id_clasetipopqrs AND clasetipopqrs.id_tipopqrs=tipopqrs.id_tipopqrs AND pqrs.gestor_pqrs='$usuario[1]' ORDER BY pqrs.id_pqrs ASC";
                $cons="SELECT * FROM pqrs.pqrs, pqrs.accion, pqrs.accionxusuario WHERE pqrs.id_pqrs=accion.id_pqrs AND accion.id_accion=accionxusuario.id_accion AND accionxusuario.vobo_accionxusuario=1 AND pqrs.gestor_pqrs='".$usuario[1]."'";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['descripcion_accion']; ?></td>
                        <td><?php echo $fila['fecha_accion']; ?></td>
                        <td><?php echo $fila['fechalimite_accion']; ?></td>
						<td><?php echo $fila['descripcion_accionxusuario']; ?></td>
                        <td><textarea name="descripcion_accionxusuario_<?php echo $fila['id_accionxusuario']; ?>" id="descripcion_accionxusuario_<?php echo $fila['id_accionxusuario']; ?>" rows="3" cols="40"><?php echo $fila['vobodescripcion_accionxusuario']; ?></textarea></td>
                        <td style="text-align: center;">
                            <a href="#" onclick="enviarAccion(descripcion_accionxusuario_<?php echo $fila['id_accionxusuario']; ?>, <?php echo $fila['id_accionxusuario']; ?>, 'si')"><img src="../Imgs/success.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="#" onclick="enviarAccion(descripcion_accionxusuario_<?php echo $fila['id_accionxusuario']; ?>, <?php echo $fila['id_accionxusuario']; ?>, 'no')"><img src="../Imgs/error.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a>
                            <!--<a href="/PQRS/GestionarAccion.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_accionxusuario']; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a>--></td>
                    </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>

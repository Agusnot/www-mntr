<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    // Consulto los usuarios del sistema
    $consu="SELECT nombre,usuario FROM central.usuarios";
    $resu=ExQuery($consu);
?>
    <script>
	$(function() {
            var availableTags = [
                <?php
                    $i=0;
                    while($filau=ExFetchAssoc($resu)){
                        echo "\"".$filau['usuario']."\"";
                        $i++;
                        if(ExNumRows($resu)!=$i){
                            echo ",";
                        }
                    }
                ?>
            ];
            function split( val ) {
                    return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                    return split( term ).pop();
            }

            $( "#id_gestoraccion" )
                    // don't navigate away from the field on tab when selecting an item
                    .bind( "keydown", function( event ) {
                            if ( event.keyCode === $.ui.keyCode.TAB &&
                                            $( this ).data( "ui-autocomplete" ).menu.active ) {
                                    event.preventDefault();
                            }
                    })
                    .autocomplete({
                            minLength: 0,
                            source: function( request, response ) {
                                    // delegate back to autocomplete, but extract the last term
                                    response( $.ui.autocomplete.filter(
                                            availableTags, extractLast( request.term ) ) );
                            },
                            focus: function() {
                                    // prevent value inserted on focus
                                    return false;
                            },
                            select: function( event, ui ) {
                                    var terms = split( this.value );
                                    // remove the current input
                                    terms.pop();
                                    // add the selected item
                                    terms.push( ui.item.value );
                                    // add placeholder to get the comma-and-space at the end
                                    terms.push( "" );
                                    this.value = terms.join( ", " );
                                    return false;
                            }
                    });
	});
        
        function eliminarAxU (usuAcc){
            //alert("Usuario a eliminar: "+usuAcc);
            $.ajax({
                type: "POST",
                url: "EliminarAccionesXUsuario.php",
                data: { usuAcc: usuAcc }
            })
            .done(function( msg ) {
                window.self.location.reload();
                //$('#dialog').dialog( "close" );
            });
        }
    </script>
<!-- Formulario para crear Acciones -->
<div id="dialog" title="Acciones">
    <form id="accionForm" name="accionForm" action="/">
        <table>
            <tr>
                <td>Descripción</td>
                <td><textarea name="descripcion_accion" id="descripcion_accion" rows="3" cols="55" class="text ui-widget-content ui-corner-all"></textarea></td>
            </tr>
            <tr>
                <td>Fecha</td>
                <td><input type="text" name="fechalimite_accion" id="fechalimite_accion" value="" class="text ui-widget-content ui-corner-all"></td>
            </tr>
            <tr>
                <td>Gestor</td>
                <td><input name="id_gestoraccion" type="Text" id="id_gestoraccion" value="<?php echo $id_gestoraccion; ?>" class="text ui-widget-content ui-corner-all"></td>
            </tr>
        </table>
    </form>
    <hr>
    <table class="imagetable">
        <tr>
            <th>FECHA</th><th>DESCRIPCIÓN</th><th>COLABORADOR</th><th>FECHA LÍMITE</th><th>OPERACIONES</th>
        </tr>
        <?php
            $constacc="SELECT * FROM pqrs.accion, pqrs.accionxusuario WHERE accion.id_accion=accionxusuario.id_accion AND accion.id_pqrs=$pqrs ORDER BY accion.id_accion ASC";
            $restacc=ExQuery($constacc);
            //echo ExError();
            //$fila=ExFetch($res);
            while($filatacc=ExFetchAssoc($restacc)){
                ?>
                <tr>
                    <td><?php echo $filatacc['fecha_accion']; ?></td>
                    <td><?php echo $filatacc['descripcion_accion']; ?></td>
                    <td><?php echo $filatacc['id_usuario']; ?></td>
                    <td><?php echo $filatacc['fechalimite_accion']; ?></td>
                    <td style="text-align: center;">
                        <!--<a href="/PQRS/AccionesXUsuario.php?DatNameSID=<?php echo $DatNameSID; ?>&pqrs=<?php echo $filatacc['id_accion']; ?>"><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> -->
                        <a href="#" onclick="eliminarAxU(<?php echo $filatacc['id_accionxusuario']; ?>)"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                </tr>
        <?php
            }
        ?>
    </table>
</div>

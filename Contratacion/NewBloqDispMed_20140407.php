<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    if($Guardar){
        $cons="insert into salud.bloqconsexterna(compania_bloqconsexterna,fechaini_bloqconsexterna,fechafin_bloqconsexterna,medico_bloqconsexterna,motivo_bloqconsexterna) values "
                . "('".$Compania[0]."','".$fechaini_bloqconsexterna."','".$fechafin_bloqconsexterna."','".$Medico."','".$motivo_bloqconsexterna."')";
        $res=ExQuery($cons);
    }
    /*if($editar){
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
    }*/
    if($eliminar){
        $cons2="delete from salud.bloqconsexterna where id_bloqconsexterna='".$eliminar."'";
        $res2=ExQuery($cons2);
    }
?>
<html>
    <head>
        <!-- CSS goes in the document HEAD or added to your external stylesheet -->
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        
        <link rel="stylesheet" type="text/css" href="../js/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
        <link rel="stylesheet" type="text/css" href="../js/dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_terrace.css">
        <script src="../js/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
    </head>
    
    <body style="text-align: center;">
        <form name='FORMA' method="post" action="/Contratacion/NewBloqDispMed.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">Bloqueos por Usuario</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">MÉDICO:</td>
                            <td><?php echo $Medico; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FECHA INICIO</td>
                            <td><input type="text" name="fechaini_bloqconsexterna" id="fechaini_bloqconsexterna" value="<?php echo $fila['fechaini_bloqconsexterna']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FECHA FIN</td>
                            <td><input type="text" name="fechafin_bloqconsexterna" id="fechafin_bloqconsexterna" value="<?php echo $fila['fechafin_bloqconsexterna']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">MOTIVO</td>
                            <td><input type="text" name="motivo_bloqconsexterna" id="motivo_bloqconsexterna" value="<?php echo $fila['motivo_bloqconsexterna']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                    <input type="hidden" name="Medico" id="Medico" value="<?php echo $Medico; ?>">
                                    <input type="submit" name="Guardar" id="Guardar" value="Nuevo">
									<input type="button" name="Cancelar" id="Cancelar" value="Cancelar" onclick="location.href='EncDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>'">
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                <th>FECHA INICIO</th><th>FECHA FIN</th><th>MOTIVO</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from salud.bloqconsexterna where medico_bloqconsexterna='".$Medico."'";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['fechaini_bloqconsexterna']; ?></td>
                        <td><?php echo $fila['fechafin_bloqconsexterna']; ?></td>
                        <td><?php echo $fila['motivo_bloqconsexterna']; ?></td>
                        <td style="text-align: center;">
                            <!--<a href="/PQRS/ClasificacionTipoPQRS.php?DatNameSID=<?php echo $DatNameSID; ?>&editar=<?php echo $fila['id_bloqconsexterna']; ?>"><img src="../Imgs/b_edit.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a>-->
                            <a href="/Contratacion/NewBloqDispMed.php?DatNameSID=<?php echo $DatNameSID; ?>&eliminar=<?php echo $fila['id_bloqconsexterna']; ?>&Medico=<?php echo $Medico; ?>"><img src="../Imgs/b_drop.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            ?>
        </table>
        
        <script>
			dhtmlXCalendarObject.prototype.langData["es"] = {
				dateformat: '%Y-%m-%d %H:%i',
				// Nombres de mes completos
				monthesFNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
				// Nombres de mes cortos
				monthesSNames: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
				// Nombre de dias completos
				daysFNames: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
				// Nombre de dias cortos
				daysSNames: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"],
				// Dia de inicio de semana. Numero 1 (Lunes) a 7(Domingo)
				weekstart: 1
			}

            var myCalendar = new dhtmlXCalendarObject(["fechaini_bloqconsexterna","fechafin_bloqconsexterna"]);
            
			myCalendar.loadUserLanguage("es");
			myCalendar.setDateFormat("%Y-%m-%d %H:%i");
            myCalendar.setSkin('dhx_terrace');
        </script>
    </body>
</html>

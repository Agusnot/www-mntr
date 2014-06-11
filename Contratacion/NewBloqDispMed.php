<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    if($Guardar){
		$fechacreacion = date("Y-m-d H:i:s");
		$fechainicio = substr($fechaini_bloqconsexterna, 0);
        $fechafin = substr($fechafin_bloqconsexterna, 0);
        
        //$horainicio = substr($fechaini_bloqconsexterna, 11);
        //$horafin = substr($fechafin_bloqconsexterna, 11);
        $horainicio = $horaini_bloqconsexterna;
        $horafin = $horafin_bloqconsexterna;
        
        $timefin = strtotime($fechafin);
        $timeite = strtotime($fechainicio);
        do{
            $timeite = strtotime($fechainicio." +1 days");
            
            $cons="insert into salud.bloqconsexterna(compania_bloqconsexterna,fechaini_bloqconsexterna,fechafin_bloqconsexterna,medico_bloqconsexterna,motivo_bloqconsexterna,usuario_bloqconsexterna,fechacreacion_bloqconsexterna) values "
                    . "('".$Compania[0]."','".$fechainicio." ".$horainicio."','".$fechainicio." ".$horafin."','".$Medico."','".$motivo_bloqconsexterna."', '".$usuario[1]."', '".$fechacreacion."')";
            $res=ExQuery($cons);
            
            $fechainicio = date("Y-m-d", $timeite);
        }while($timefin>=$timeite);
		
        //$cons="insert into salud.bloqconsexterna(compania_bloqconsexterna,fechaini_bloqconsexterna,fechafin_bloqconsexterna,medico_bloqconsexterna,motivo_bloqconsexterna) values "
        //        . "('".$Compania[0]."','".$fechaini_bloqconsexterna."','".$fechafin_bloqconsexterna."','".$Medico."','".$motivo_bloqconsexterna."')";
        //$res=ExQuery($cons);
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
		
		<script type="text/javascript">
            function checkForm() {
                re = /^\d{4}-\d{1,2}-\d{1,2}$/;
                if(document.FORMA.fechaini_bloqconsexterna.value === "" || !document.FORMA.fechaini_bloqconsexterna.value.match(re)){
                    alert("Fecha no válida: " + document.FORMA.fechaini_bloqconsexterna.value);
                    document.FORMA.fechaini_bloqconsexterna.focus();
                    return false;
                }
                
                if(document.FORMA.fechafin_bloqconsexterna.value === "" || !document.FORMA.fechafin_bloqconsexterna.value.match(re)){
                    alert("Fecha no válida: " + document.FORMA.fechafin_bloqconsexterna.value);
                    document.FORMA.fechafin_bloqconsexterna.focus();
                    return false;
                }
                
                re = /^\d{1,2}:\d{2}$/;
                if(document.FORMA.horaini_bloqconsexterna.value === "" || !document.FORMA.horaini_bloqconsexterna.value.match(re)){
                    alert("Hora no válida: " + document.FORMA.horaini_bloqconsexterna.value);
                    document.FORMA.horaini_bloqconsexterna.focus();
                    return false;
                }
                
                if(document.FORMA.horafin_bloqconsexterna.value === "" || !document.FORMA.horafin_bloqconsexterna.value.match(re)){
                    alert("Hora no válida: " + document.FORMA.horafin_bloqconsexterna.value);
                    document.FORMA.horafin_bloqconsexterna.focus();
                    return false;
                }
                
                //alert("Todos los campos fueron validados!");
                document.getElementById("Guardar").value="Guardar";
                document.getElementById("FORMA").submit();
                //return true;
            }
        </script>
    </head>
    
    <body style="text-align: center;">
        <form name='FORMA' method="post" action="/Contratacion/NewBloqDispMed.php?DatNameSID=<?php echo $DatNameSID; ?>">
                <table bordercolor='#ffffff' style='font-family: Tahoma, Geneva, sans-serif; font-size: 12px;'>
                        <tr>
                            <td colspan=4 style="text-align: center; font-weight: bold;">Bloqueos por Usuario</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">PROFESIONAL:</td>
                            <td><?php echo $Medico; ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">FECHA INICIO</td>
                            <td><input type="text" name="fechaini_bloqconsexterna" id="fechaini_bloqconsexterna" value="<?php echo $fila['fechaini_bloqconsexterna']; ?>" style="width:200px;"></td>
                            <td style="text-align: right;">FECHA FIN</td>
                            <td><input type="text" name="fechafin_bloqconsexterna" id="fechafin_bloqconsexterna" value="<?php echo $fila['fechafin_bloqconsexterna']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">HORA INICIO</td>
                            <td><input type="text" name="horaini_bloqconsexterna" id="horaini_bloqconsexterna" value="<?php echo $fila['horaini_bloqconsexterna']; ?>" style="width:200px;"></td>
                            <td style="text-align: right;">HORA FIN</td>
                            <td><input type="text" name="horafin_bloqconsexterna" id="horafin_bloqconsexterna" value="<?php echo $fila['horafin_bloqconsexterna']; ?>" style="width:200px;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">MOTIVO</td>
                            <td colspan="3"><input type="text" name="motivo_bloqconsexterna" id="motivo_bloqconsexterna" value="<?php echo $fila['motivo_bloqconsexterna']; ?>" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: left;">
                                    <input type="hidden" name="Medico" id="Medico" value="<?php echo $Medico; ?>">
                                    <input type="hidden" name="Guardar" id="Guardar" value="">
                                    <input type="button" name="Guardar1" id="Guardar1" value="Nuevo" onclick="checkForm()">
									<input type="button" name="Cancelar" id="Cancelar" value="Cancelar" onclick="location.href='EncDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>'">
                            </td>
                        </tr>
                </table>
        </form>
        
        <table class="imagetable">
            <tr>
                <th>FECHA INICIO</th><th>FECHA FIN</th><th>MOTIVO</th><th>CREADO POR</th><th>OPERACIONES</th>
            </tr>
            <?php
                $cons="select * from salud.bloqconsexterna where medico_bloqconsexterna='".$Medico."' order by fechaini_bloqconsexterna desc";
		$res=ExQuery($cons);
                //echo ExError();
		//$fila=ExFetch($res);
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['fechaini_bloqconsexterna']; ?></td>
                        <td><?php echo $fila['fechafin_bloqconsexterna']; ?></td>
                        <td><?php echo $fila['motivo_bloqconsexterna']; ?></td>
						<td><?php echo $fila['usuario_bloqconsexterna']; ?></td>
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
				dateformat: '%Y-%m-%d',
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
			myCalendar.setDateFormat("%Y-%m-%d");
            myCalendar.setSkin('dhx_terrace');
			myCalendar.hideTime();
        </script>
    </body>
</html>

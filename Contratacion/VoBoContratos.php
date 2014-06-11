<?php
    if($DatNameSID){session_name("$DatNameSID");}	
    session_start();
    include("Funciones.php");
    
    if($_GET['vobo'] && $_GET['alertapor']){
        $cons="UPDATE contratacionsalud.contratos SET vobotiempo=".$_GET['vobo']." where contrato='".$_GET['contrato']."'";
        $res=ExQuery($cons);
    }
    
    if($_GET['vobo'] && $_GET['alertamon']){
        $cons="UPDATE contratacionsalud.contratos SET vobomonto=".$_GET['vobo']." where contrato='".$_GET['contrato']."'";
        $res=ExQuery($cons);
    }
    
    if($_GET['vobo'] && $_GET['alertadias']){
        $cons="UPDATE contratacionsalud.contratos SET vobodias=".$_GET['vobo']." where contrato='".$_GET['contrato']."'";
        $res=ExQuery($cons);
    }
        
    if($accionxusuario){
        $fecha_actual = date("Y-m-d H:i:s");
        
        $cons="UPDATE pqrs.accionxusuario SET descripcion_accionxusuario='$descripcion_accionxusuario', fecha_accionxusuario='$fecha_actual', vobo_accionxusuario='1' where id_accionxusuario=$accionxusuario";
        $res=ExQuery($cons);
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/pqrs.css">
        
        <!--<script src="link/js/jquery-1.10.2.js"></script>
        <script src="link/js/jquery-ui-1.10.4.js"></script>-->
    </head>
    
    <body>
        <p style="font-family: verdana,arial,sans-serif; font-size:12px; font-weight:bold;">CONTRATOS POR REVISAR</p>
        <table class="imagetable">
            <?php
            // Para alertas de dias restantes del contrato
            if($_GET['alertadias']){
                $cons="SELECT * FROM contratacionsalud.contratos WHERE CURRENT_TIMESTAMP > (contratos.fechafin - (INTERVAL '".$_GET['alertadias']." days')) AND contratos.estado='AC' AND CURRENT_TIMESTAMP>contratos.fechaini AND CURRENT_TIMESTAMP<contratos.fechafin AND (contratos.vobodias IS NULL OR contratos.vobodias>".$_GET['alertadias'].")";
            	$res=ExQuery($cons);
                
                //echo ExError();
		//$fila=ExFetch($res);
                ?>
                    <tr>
                        <th>CONTRATO</th><th>FECHA INICIO</th><th>FECHA FIN</th><th>OPERACIONES</th>
                    </tr>
                <?php
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['contrato']; ?></td>
                        <td><?php echo $fila['fechaini']; ?></td>
                        <td><?php echo $fila['fechafin']; ?></td>
                        <td style="text-align: center;">
                            <a href="/Contratacion/NewContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&Edit=1&Entidad=<?php echo $fila['entidad']; ?>&Contrato=<?php echo $fila['contrato']; ?>&Numero=<?php echo $fila['numero']; ?>" ><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/Contratacion/VoBoContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&vobo=<?php echo $_GET['alertadias']; ?>&contrato=<?php echo $fila['contrato']; ?>&alertadias=<?php echo $_GET['alertadias']; ?>"><img src="../Imgs/b_check.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            }
            
            // Para alertas de porcentaje de monto ejecutado del contrato
            if($_GET['alertamon']){
                $cons="SELECT entidad, contrato, fechaini, fechafin, mttoejecutado, numero FROM contratacionsalud.contratos WHERE contratos.mttoejecutado>(contratos.monto*".$_GET['alertamon']."/100) AND contratos.estado='AC' AND CURRENT_TIMESTAMP>contratos.fechaini AND CURRENT_TIMESTAMP<contratos.fechafin AND (contratos.vobomonto IS NULL OR contratos.vobomonto<".$_GET['alertamon'].")";
            	$res=ExQuery($cons);
                
                //echo ExError();
		//$fila=ExFetch($res);
                ?>
                    <tr>
                        <th>CONTRATO</th><th>FECHA INICIO</th><th>FECHA FIN</th><th>MONTO EJECUTADO</th><th>OPERACIONES</th>
                    </tr>
                <?php
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['contrato']; ?></td>
                        <td><?php echo $fila['fechaini']; ?></td>
                        <td><?php echo $fila['fechafin']; ?></td>
                        <td style="text-align: right;"><?php echo $fila['mttoejecutado']; ?></td>
                        <td style="text-align: center;">
                            <a href="/Contratacion/NewContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&Edit=1&Entidad=<?php echo $fila['entidad']; ?>&Contrato=<?php echo $fila['contrato']; ?>&Numero=<?php echo $fila['numero']; ?>" ><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/Contratacion/VoBoContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&vobo=<?php echo $_GET['alertamon']; ?>&contrato=<?php echo $fila['contrato']; ?>&alertamon=<?php echo $_GET['alertamon']; ?>"><img src="../Imgs/b_check.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            }
            
            // Para alertas de porcentaje de monto ejecutado del contrato
            if($_GET['alertapor']){
                $cons="SELECT entidad, contrato, fechaini, fechafin, porcentajedias, numero FROM contratacionsalud.contratos WHERE contratos.porcentajedias>".$_GET['alertapor']." AND CURRENT_TIMESTAMP>contratos.fechaini AND CURRENT_TIMESTAMP<contratos.fechafin AND contratos.estado='AC' AND (contratos.vobotiempo IS NULL OR contratos.vobotiempo<".$_GET['alertapor'].")";
            	$res=ExQuery($cons);
                
                //echo ExError();
		//$fila=ExFetch($res);
                ?>
                    <tr>
                        <th>CONTRATO</th><th>FECHA INICIO</th><th>FECHA FIN</th><th>MONTO EJECUTADO</th><th>OPERACIONES</th>
                    </tr>
                <?php
                while($fila=ExFetchAssoc($res)){
                    ?>
                    <tr>
                        <td><?php echo $fila['contrato']; ?></td>
                        <td><?php echo $fila['fechaini']; ?></td>
                        <td><?php echo $fila['fechafin']; ?></td>
                        <td><?php echo $fila['porcentajedias']; ?></td>
                        <td style="text-align: center;">
                            <a href="/Contratacion/NewContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&Edit=1&Entidad=<?php echo $fila['entidad']; ?>&Contrato=<?php echo $fila['contrato']; ?>&Numero=<?php echo $fila['numero']; ?>" ><img src="../Imgs/rightr.gif" style="padding-left: 2px; padding-right: 2px; border: none;"></a> 
                            <a href="/Contratacion/VoBoContratos.php?DatNameSID=<?php echo $DatNameSID; ?>&vobo=<?php echo $_GET['alertapor']; ?>&contrato=<?php echo $fila['contrato']; ?>&alertapor=<?php echo $_GET['alertapor']; ?>"><img src="../Imgs/b_check.png" style="padding-left: 2px; padding-right: 2px; border: none;"></a></td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
    </body>
</html>

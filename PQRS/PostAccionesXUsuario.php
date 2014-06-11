<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    $fecha_accion = date("Y-m-d H:i:s");
    
    $cons="insert into pqrs.accion(descripcion_accion, fecha_accion, fechalimite_accion, id_pqrs) values ('".$_POST['descripcion_accion']."', '".$fecha_accion."', '".$_POST['fechalimite_accion']."', ".$_POST['id_pqrs'].") returning id_accion";
    $res=ExQuery($cons);
    $fila=ExFetchAssoc($res);
    
    $usuarios_acc = explode(",", $id_gestoraccion);

    foreach($usuarios_acc as $key => $value){
        $uacc = trim($value);
        if($uacc!=""){
            $cons="insert into pqrs.accionxusuario(id_accion, id_usuario) values (".$fila['id_accion'].", '".$uacc."')";
            $res=ExQuery($cons);
        }
    }
?>
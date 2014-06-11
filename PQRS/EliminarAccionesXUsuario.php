<?php
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Funciones.php");
    
    $cons="DELETE FROM pqrs.accionxusuario WHERE accionxusuario.id_accionxusuario=".$_POST['usuAcc']." returning id_accion";
    $res=ExQuery($cons);
    $fila=ExFetchAssoc($res);
    
    $consa="SELECT * FROM pqrs.accionxusuario WHERE accionxusuario.id_accion=".$fila['id_accion'];
    $resa=ExQuery($consa);
    
    if(ExNumRows($resa)==0){
        $cons="DELETE FROM pqrs.accion WHERE accion.id_accion=".$fila['id_accion']."";
        $res=ExQuery($cons);
    }
?>
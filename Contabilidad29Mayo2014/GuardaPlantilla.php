<?
	session_name($DatNameSID);
	session_start();
	include("Funciones.php");
	$ND=getdate();
        if($NombrePlantilla){
            $cons="INSERT INTO contabilidad.planohrplantilla(compania, nombre, detalle)
            values('$Compania[0]','$NombrePlantilla','$Esquema')";
            $res=ExQuery($cons);
        }
        
?>
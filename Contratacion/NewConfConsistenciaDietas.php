<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		if(!$Edit){
			$cons="select id from salud.consistenciadietas where compania='$Compania[0]' order by id desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$AutoId=$fila[0]+1;
			$cons="insert into salud.consistenciadietas(compania,consistencia,id) values ('$Compania[0]','$Consistencia','$AutoId')";
			//echo $cons;
		}
		else{
			$cons="update salud.consistenciadietas set consistencia='$Consistencia',id=$Id where compania='$Compania[0]' and consistencia='$ConsistenciaAnt' and id=$IdAnt";
			//echo $cons;			
		}		
		$res=ExQuery($cons);?>
		<script language="javascript">
		location.href='ConfConsistenciaDietas.php?DatNameSID=<? echo $DatNameSID?>';
		</script><?		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar(){
	if(document.FORMA.Interprograma.value==""){
		alert("Debe digitar una Consistencia Dietas!!!");return false;
	}
}
function evitarSubmit(evento){
	if(document.all){ tecla = evento.keyCode;}
	else{ tecla = evento.which;}
	return(tecla != 13);
}
function Pasar(evento,proxCampo){ 
	if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
}

</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2"> 	
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Consistencia Dieta</td><td><input type="text" name="Consistencia" value="<? echo $Consistencia?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)"></td></tr>    
    <tr><td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfConsistenciaDietas.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table>
<input type="hidden" name="ConsistenciaAnt" value="<? echo $Consistencia?>">
<input type="hidden" name="IdAnt" value="<? echo $Id?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>        
</body>
</html>

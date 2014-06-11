<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Eliminar){
		$cons="delete from salud.preguntasegreso where compania='$Compania[0]' and pregunta='$PreguntaEgreso' and obligatorio='$Obligatorio' and area='$Area'";
		//echo $cons;
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
<? 	$cons="select pregunta,obligatorio,area from salud.preguntasegreso where compania='$Compania[0]' order by pregunta";
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>	
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Pregunta Ingreso</td><td>Area</td><td>Obligatorio</td><td colspan="2"></td></tr><?
		while($fila=ExFetch($res)){
			echo "<tr align='center'><td>$fila[0]</td>";
			if($fila[2]=="ASISTENCIAL"){
				echo "<td>ASISTENCIAL</td>";
			}
			else{
				echo "<td>ADMINISTRATIVA</td>";
			}			
			if($fila[1]==1){
				echo "<td>Si</td><td>";
			}
			else{
				echo "<td>No</td><td>";
			}?>
            <img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfPreguntasEgreso.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&PreguntaEgreso=<? echo $fila[0]?>&Obligatorio=<? echo $fila[1]?>&Area=<? echo $fila[2]?>'"></td><td>
			<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfPreguntasEgreso.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&PreguntaEgreso=<? echo $fila[0]?>&Obligatorio=<? echo $fila[1]?>&Area=<? echo $fila[2]?>';}" src="/Imgs/b_drop.png"></td>
<?		}?>
        </tr>
<?	}
	else{?>
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>No se han registrado preguntas</td></tr>
<?	}?>	    
	<tr><td colspan="4" align="center"><input type="button" value="Nuevo" onClick="location.href='NewConfPreguntasEgreso.php?DatNameSID=<? echo $DatNameSID?>&'"></td></tr>    
</table>    
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

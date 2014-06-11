<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Edit){
			$cons="insert into salud.preguntasegreso (compania,pregunta,obligatorio,area) values ('$Compania[0]','$PreguntaEgreso','$Obligatorio','$Area')";			
			//echo $cons;		
		}
		else{
			$cons="update salud.preguntasegreso set pregunta='$PreguntaEgreso',obligatorio='$Obligatorio',area='$Area' where compania='$Compania[0]' and pregunta='$PreguntaEgresoAnt' and obligatorio='$ObligatorioAnt' and area='$AreaAnt'";
			//echo $cons;
		}
			$res=ExQuery($cons);echo ExError();
		?>	<script language="javascript">
				location.href='ConfPreguntasEgreso.php?DatNameSID=<? echo $DatNameSID?>';
			</script><?
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function validar()
	{
		if(document.FORMA.PreguntaEgreso.value=="")
		{
			alert("Debe digitar una Pregunta de Salida!!!");return false;
		}
	}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Pregunta Egreso</td></tr>
    <tr><td align="center"><input type="text" name="PreguntaEgreso" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)" value="<? echo $PreguntaEgreso?>"></td></tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Obligatorio</td></tr>
    <tr>
    	<td align="center"><select name="Obligatorio">	
        	<option value="1">Si</option>
		<? 	if($Obligatorio=='0'){?>
				<option value="0" selected>No</option>
		<?	}
			else{?>
            	<option value="0">No</option>
		<?	}?>
        </select></td>
    </tr>
	    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Area</td></tr>
    <tr>
    	<td align="center"><select name="Area">	
        	<option value="ASISTENCIAL">ASISTENCIAL</option>
		<? 	if($Area=='ADMINISTRATIVA'){?>
				<option value="ADMINISTRATIVA" selected>ADMINISTRATIVA</option>
		<?	}
			else{?>
            	<option value="ADMINISTRATIVA">ADMINISTRATIVA</option>
		<?	}?>
        </select></td>
    </tr>
    
    <tr><td align="center">	<input type="submit" value="Guardar" id="Guardar" name="Guardar">
    						<input type="button" value="Cancelar" onClick="location.href='ConfPreguntasEgreso.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table>
<input type="hidden" name="PreguntaEgresoAnt" value="<? echo $PreguntaEgreso?>">
<input type="hidden" name="ObligatorioAnt" value="<? echo $Obligatorio?>">
<input type="hidden" name="AreaAnt" value="<? echo $Area?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>

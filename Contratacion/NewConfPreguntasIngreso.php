<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Edit){
			$cons="insert into salud.preguntasingreso (compania,pregunta,obligatorio,area) values ('$Compania[0]','$PreguntaIngreso','$Obligatorio','$Area')";			
			//echo $cons;		
		}
		else{
			$cons="update salud.preguntasingreso set pregunta='$PreguntaIngreso',obligatorio='$Obligatorio',area='$Area' where compania='$Compania[0]' and pregunta='$PreguntaIngresoAnt' and obligatorio='$ObligatorioAnt' and area='$AreaAnt'";
			//echo $cons;
		}
			$res=ExQuery($cons);echo ExError();
		?>	<script language="javascript">
				location.href='ConfPreguntasIngreso.php?DatNameSID=<? echo $DatNameSID?>';
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
		if(document.FORMA.PreguntaIngreso.value=="")
		{
			alert("Debe digitar una Pregunta de Ingreso!!!");return false;
		}
	}	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Pregunta Ingreso</td></tr>
    <tr><td align="center"><input type="text" name="PreguntaIngreso" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)" value="<? echo $PreguntaIngreso?>"></td></tr>
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
    						<input type="button" value="Cancelar" onClick="location.href='ConfPreguntasIngreso.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table>
<input type="hidden" name="PreguntaIngresoAnt" value="<? echo $PreguntaIngreso?>">
<input type="hidden" name="ObligatorioAnt" value="<? echo $Obligatorio?>">
<input type="hidden" name="AreaAnt" value="<? echo $Area?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>

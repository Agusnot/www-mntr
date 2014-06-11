<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Edit){
			$cons="insert into salud.motivosalida (compania,motivosalida,estadosalida) values ('$Compania[0]','$MotivoSalida','$EstadoSalida')";			
			//echo $cons;		
		}
		else{
			$cons="update salud.motivosalida set motivosalida='$MotivoSalida' where compania='$Compania[0]' and estadosalida='$EstadoSalida' and motivosalida='$MotivoSalidaAnt'";
			//echo $cons;
		}
			$res=ExQuery($cons);echo ExError();
		?>	<script language="javascript">
				location.href='ConfMotivosSalida.php?DatNameSID=<? echo $DatNameSID?>&EstadoSalida=<? echo $EstadoSalida?>';
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
		if(document.FORMA.MotivoSalida.value=="")
		{
			alert("Debe digitar el Motivo de Salida!!!");return false;
		}
	}
	function evitarSubmit(evento)
	{
		if(document.all){ tecla = evento.keyCode;}
		else{ tecla = evento.which;}
		return(tecla != 13);
	}
	function Pasar(evento,proxCampo)
	{
		if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="left" cellpadding="4">
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Estado Salida</td></tr>
    <tr><td align="center"><? echo $EstadoSalida?></td></tr>
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="2">Motivo de Salida</td></tr>
    <tr><td align="center"><input type="text" name="MotivoSalida" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);Pasar(event,'Guardar')" onKeyPress="return evitarSubmit(event)" value="<? echo $MotivoSalida?>"></td></tr>
    <tr><td align="center"><input type="submit" value="Guardar" id="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfMotivosSalida.php?DatNameSID=<? echo $DatNameSID?>&EstadoSalida=<? echo $EstadoSalida?>'"></td></tr>
</table>
<input type="hidden" name="MotivoSalidaAnt" value="<? echo $MotivoSalida?>">
<input type="hidden" name="EstadoSalida" value="<? echo $EstadoSalida?>">
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>    
</body>
</html>

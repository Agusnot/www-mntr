<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="insert into salud.motivolevantamientomulta (compania,motivo,origen) values ('$Compania[0]','$Motivo','$Origen')";			
		}
		else
		{
			$cons="update salud.motivolevantamientomulta set motivo='$Motivo' where compania='$Compania[0]' and motivo='$MotivoAnt' and origen='$Origen'";		
		}
		$res=ExQuery($cons);
		?>
        	<script language="javascript">
				location.href='MotivoLevMulta.php?DatNameSID=<? echo $DatNameSID?>&Origen=<? echo $Origen?>';
			</script>
        <?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Motivo.value==""){alert("Debe digitar el motivo!!!");return false;}	
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
	<tr title='Asignar' align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Origen: <? echo $Origen?></td>
	</tr>    
    <tr title='Asignar' align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Motivo</td>
	</tr>    
    <tr align="center">
    	<td>
        	<input type="text" name="Motivo" onkeydown="xLetra(this)" onkeypress="xLetra(this)" onkeyup="xLetra(this)" value="<? echo $Motivo?>"/>
        </td>
    </tr>
    <tr>
    <tr align="center">
    	<td><input type="submit" value="Guardar" name="Guardar"/>
        	<input type="button" value="Cancelar" 
        	onclick="location.href='MotivoLevMulta.php?DatNameSID=<? echo $DatNameSID?>&Origen=<? echo $Origen?>'"/>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="Origen" value="<? echo $Origen?>"/>
<input type="hidden" name="MotivoAnt" value="<? echo $Motivo?>" />
<input type="hidden" name="Edit" value="<? echo $Edit?>" />
</form>    
</body>
</html>
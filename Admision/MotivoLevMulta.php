<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="delete from salud.motivolevantamientomulta where compania='$Compania[0]' and motivo='$Motivo' and origen='$Origen'";	
		$res=ExQuery($cons);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4" >
<? 	$cons="select origen from salud.origenlevantamientomulta where compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
?>
	<tr title='Asignar' align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="3">Origen</td>
	</tr>    
    <tr align="center">
    	<td colspan="3">
        	<select name="Origen" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Origen)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
				}?>
            </select>
       	</td>
    </tr>
<?	$cons="select motivo from salud.motivolevantamientomulta where compania='$Compania[0]' and origen='$Origen'";
	$res=ExQuery($cons);?>    
    <tr title='Asignar' align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="3">Motivo</td>
	</tr>
    <? 	while($fila=ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>";?>
            <img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewMotivLevMulta.php?DatNameSID=<? echo $DatNameSID?>&Motivo=<? echo $fila[0]?>&Edit=1&Origen=<? echo $Origen?>'"></td><td>
			<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='MotivoLevMulta.php?DatNameSID=<? echo $DatNameSID?>&Motivo=<? echo $fila[0]?>&Eliminar=1&Origen=<? echo $Origen?>';}" src="/Imgs/b_drop.png">
            </td>
            </tr>
	<?	}
    ?>    
    <tr align="center">
    	<td colspan="3"><input type="button" value="Nuevo" 
        	onClick="location.href='NewMotivLevMulta.php?DatNameSID=<? echo $DatNameSID?>&Origen='+document.FORMA.Origen.value">
      	</td>
    <tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>    
</body>
</html>
<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Salud.motivocancelcita where origencalcel='$OrigenCancel' and motivocancelcita='$Motivocancel' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">   
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">   
<tr bgcolor="#e5e5e5" style=" font-weight:bold">
   	<td colspan="3" align="center">Origen Cancelacion</td>
</tr>
<? 	if(!$OrigenCancel){
		$cons="Select origencancelacion from Salud.origencancelcita where Compania='$Compania[0]'";
		$result=ExQuery($cons);
		$row = ExFetchArray($result);
		$OrgCancel=$row[0];
	}
	else{
		$OrgCancel=$OrigenCancel;
	}?>
<tr>    
    <td colspan="3" align="center">
   <?	$cons="Select origencancelacion from Salud.origencancelcita where Compania='$Compania[0]'";
		$result=ExQuery($cons);?>    
        <select name="OrigenCancel" onChange="document.FORMA.submit()">
	<?	while($row = ExFetchArray($result)){
			if($OrigenCancel==$row[0]){
				echo "<option value='$row[0]' selected>$row[0]</option>";
			}
			else{
				echo "<option value='$row[0]'>$row[0]</option>";
			}
		}?>    	
    	</select>
    </td>
</tr>
<tr bgcolor="#e5e5e5" style=" font-weight:bold">
   	<td>Motivo Cancelacion</td><td colspan="2"></td>
</tr>
    <?	$cons="Select motivocancelcita from Salud.motivocancelcita where origencalcel='$OrgCancel' and Compania='$Compania[0]'";
		$result=ExQuery($cons);    
	while($row = ExFetchArray($result))
	{ 		
		echo "<tr><td>$row[0]</td><td>";?>
		<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfMotivoCancelCita.php?DatNameSID=<? echo $DatNameSID?>&Motivocancel=<? echo $row[0]?>&Edit=1&OrigenCancel=<? echo $OrgCancel?>'"></td><td>
		<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfMotivoCancelCita.php?DatNameSID=<? echo $DatNameSID?>&Motivocancel=<? echo $row[0]?>&Eliminar=1&OrigenCancel=<? echo $OrgCancel?>';}" src="/Imgs/b_drop.png"></td></tr>
<?	} ?>
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="button" onClick="location.href='NewConfMotivoCancelCita.php?DatNameSID=<? echo $DatNameSID?>&OrigenCancel=<? echo $OrgCancel?>'" value="Nuevo"></td>
    </tr>
</table>   
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form> 
</body>
</html>
<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.motivocancelcita(origencalcel,motivocancelcita,Compania) values ('$OrigenCancel','$MotivoCancel','$Compania[0]')";
		}
		else
		{
			$cons="Update Salud.motivocancelcita set origencalcel='$OrigenCancel',motivocancelcita='$MotivoCancel' where motivocancelcita='$MotivoCancelAnt' and origencalcel='$OrigenCancel' and Compania='$Compania[0]'";
			
			
		}
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	       location.href='ConfMotivoCancelCita.php?DatNameSID=<? echo $DatNameSID?>&OrigenCancel=<? echo $OrigenCancel?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.motivocancelcita where origencalcel='$OrigenCancel' and motivocancelcita='$Motivocancel' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
	}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar(){
	if(document.FORMA.MotivoCancel.value=="")
	{
		alert("Debe ingresar un Motivo de Cancelacion!!!");return false;
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
    <tr><td align="center" bgcolor="#e5e5e5" style=" font-weight:bold">Origen Cancelacion</td></tr>
    <tr><td align="center"><? echo $OrigenCancel?></td></tr>
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Motivo Cancelacion</td>
	</tr>
    <tr>        
       	<td><input type="text" maxlength="30" name="MotivoCancel" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[2]?>"></td>             
    </tr>

    <tr>
    	<td align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfMotivoCancelCita.php?DatNameSID=<? echo $DatNameSID?>&OrigenCancel=<? echo $OrigenCancel?>'"></td>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="OrigenCancel" value="<? echo $OrigenCancel?>">
<input type="hidden" name="MotivoCancelAnt" value="<? echo $Motivocancel?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

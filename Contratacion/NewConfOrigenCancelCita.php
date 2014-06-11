<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.origencancelcita(origencancelacion,Compania) values ('$OrigenCancel','$Compania[0]')";
		}
		else
		{
			$cons="Update Salud.origencancelcita set origencancelacion='$OrigenCancel' where origencancelacion='$OrigenCancelAnt' and Compania='$Compania[0]'";
		}
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	        location.href='ConfOrigenCancelCita.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.origencancelcita where origencancelacion='$OrigenCancel' and Compania='$Compania[0]'";
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
	if(document.FORMA.OrigenCancel.value=="")
	{
		alert("Debe ingresar un Origen de Cancelacion!!!");return false;
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Origen Cancelacion</td><td><input type="text" maxlength="30" name="OrigenCancel" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[1]?>"></td>             
    </tr>

    <tr>
    	<td colspan="4" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfOrigenCancelCita.php?DatNameSID=<? echo $DatNameSID?>'"></td>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="OrigenCancelAnt" value="<? echo $OrigenCancel?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

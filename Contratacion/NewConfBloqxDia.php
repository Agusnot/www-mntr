<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		
		
		if(!$Edit)
		{
			$cons="select * from Salud.bloqueoxdia where compania='$Compania[0]' and dia='$Dia'";
			$res=ExQuery($cons);echo ExError();
			if(ExNumRows($res)>0){
				?><script language="javascript">alert("La fecha ya ha sido registrada!!!");</script><?
			}
			else{
				$cons="Insert into Salud.bloqueoxdia (dia,motivo,compania,usuario,fechacrea) 
				values ('$Dia','$Motivo','$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
				$res=ExQuery($cons);echo ExError();
				?>
	    		<script language="javascript">
		    		location.href='ConfBloqxDia.php?DatNameSID=<? echo $DatNameSID?>&';
		        </script>
    		    <?	
			}
		}
		else
		{
			$cons="select * from Salud.bloqueoxdia where compania='$Compania[0]' and dia='$Dia' and dia!='$DiaAnt'";
			$res=ExQuery($cons);echo ExError();
			if(ExNumRows($res)>0){
				?><script language="javascript">alert("La fecha ya ha sido registrada!!!");</script><?
			}
			else{
				$cons="Update Salud.bloqueoxdia set dia='$Dia',motivo='$Motivo' where dia='$DiaAnt' and Compania='$Compania[0]'";
				$res=ExQuery($cons);echo ExError();
				?>
		    	<script language="javascript">
			    	location.href='ConfBloqxDia.php?DatNameSID=<? echo $DatNameSID?>';
	    	    </script>
		        <?	
			}
		}		
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar(){
	if(document.FORMA.Dia.value=="")
	{
		alert("Debe seleccionar un dia!!!");return false;
	}
	if(document.FORMA.Motivo.value=="")
	{
		alert("Debe digitar un motivo!!!");return false;
	}
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr align="center">
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Dia a Bloquear</td>
  	</tr>
    <tr align="center">      
        <td><input type="text" name="Dia" style="width:80px" readonly value="<? echo $Dia?>" onClick="popUpCalendar(this, FORMA.Dia, 'yyyy-mm-dd')"></td>             
    </tr>
	<tr align="center">
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Motivo</td>
    </tr>
    <tr align="center">
    	<td><input type="text" name="Motivo" style="width:200px" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $Motivo?>"></td>
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfBloqxDia.php?DatNameSID=<? echo $DatNameSID?>&'"></td>
    </tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="DiaAnt" value="<? echo $Dia?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
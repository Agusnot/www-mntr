<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table ORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Especialidad</td>
        <td><? $cons="select especialidad from salud.especialidades where compania='$Compania[0]'";
			$res=ExQuery($cons);?>		
        <select name="Cargo" onChange="frames.VerConsulExtr.location.href='VerConsulExtr.php?DatNameSID=<? echo $DatNameSID?>&Cargo='+this.value">
        <option></option>
        <? while($fila=ExFetch($res))
			{
				if($fila[0]==$Cargo){?>
					<option value="<? echo $fila[0]?>" selected><? echo $fila[0]?></option>
			<?	}
				else{?>
					<option value="<? echo $fila[0]?>"><? echo $fila[0]?></option>
			<?	}				
			}
		?>
        </select></td>
    </tr>
</table> 
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="VerConsulExtr" src="VerConsulExtr.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>" width="100%" height="85%"></iframe>
<?
	if($Cargo)
	{
		?><script language="javascript">
        	frames.BusquedaCUPSxPlanServ.location.href="VerConsulExtr.php?DatNameSID=<? echo $DatNameSID?>&Cargo=<? echo $Cargo?>";
        </script><?
	}
?>
</body>
</html>

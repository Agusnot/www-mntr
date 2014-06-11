<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function AbrirCama(Cama)
	{		
		frames.FrameOpener.location.href="ConfCama.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>&Id="+Cama;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='300px';
		document.getElementById('FrameOpener').style.left='280px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400px';
		document.getElementById('FrameOpener').style.height='270px';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr>
    	<td><input type="button" value="Aumentar Camas" onClick="location.href='ConfCamasAumentar.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>'"></td>
        <td><input type="button" value="Reducir Camas" onClick="location.href='ConfCamasReducir.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>'"></td>
        <td><input type="button" value="Trasladar Camas" onClick="location.href='ConfCamasTrasladar.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>'"></td>
	</tr>
</table>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	
 <? $cons="select * from salud.camasxunidades where compania='$Compania[0]' and ambito='$Ambito' and unidad='$UnidadMod' order by idcama"; 	
// echo $cons;
 	$res=ExQuery($cons);echo ExError();
		echo "<tr>";
		while($row = ExFetch($res)){
			$i++;
			if($i==8){echo "</tr><tr>";$i=1;}
			echo "<td align='center' style='width:100px'>$row[4]<br>";
			if($row[6]=='AC'){?>            
				<img title='Configurar' src='/Imgs/CAMAP.png' style='cursor:hand' onClick="AbrirCama('<? echo $row[3]?>')">
		<?	}
			else{?>
				<img title='Configurar' src='/Imgs/CAMAPX.png' style='cursor:hand' onClick="AbrirCama('<? echo $row[3]?>')">
		<?	}            
			echo "<br>$row[5]</td>";
			
		}
	?>
    <tr>
    	<td colspan="11" align="center"><input type="button" value="Regresar" onClick="location.href='ConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>'"></td>
    </tr>
</table>
<input type="hidden" name="Cama">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>
</html>

<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Reducir){
		
		$cons5 = "Select idcama from Salud.camasxunidades where ambito='$Ambito' and unidad='$UnidadMod' and Compania = '$Compania[0]' order by idcama desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		$fila5 = ExFetch($res5);			
		$Id = $fila5[0];
		for($i=0;$i<$CamasxReducir;$i++){
			$cons3="select cedula from salud.pacientesxpabellones where pabellon='$UnidadMod' and ambito='$Ambito' and idcama=$Id and estado='AC' and Compania = '$Compania[0]'"; 
			$res3=ExQuery($cons3);echo ExError();
			//echo "cons3=$cons3<br>\n";
			if(ExNumRows($res3)==0){
				$realcamas++;
				$cons2="delete from Salud.camasxunidades where ambito='$Ambito' and unidad='$UnidadMod' and idcama=$Id and Compania = '$Compania[0]'";			   	
				$res2=ExQuery($cons2);echo ExError();
				//echo "cons2=$cons2<br>\n";
			}
			$Id--;
		}
		$newcamas=$NocamasAnt-$realcamas;
		$cons="update salud.pabellones set nocamas=$newcamas where ambito='$Ambito' and Compania='$Compania[0]' and pabellon='$UnidadMod'";
		//echo "cons=$cons<br>\n";
		$res=ExQuery($cons);
		if($realcamas!=$CamasxReducir){?>
			<script language="javascript">
				alert("Algunas camas no se puedieron reducir debido a que se encunetran ocupadas!!!");
			</script>	
	<?	}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<? 
$cons="select nocamas from salud.pabellones where ambito='$Ambito' and Compania='$Compania[0]' and pabellon='$UnidadMod'";
$res=ExQuery($cons);
$fila = ExFetch($res);
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold"><? echo "$Ambito - $UnidadMod";?></td></tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold"></td></tr>
	<tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">No Total de Camas</td></tr>
    <tr><td align="center"><? echo $fila[0]?></td></tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">No de Camas a Reducir</td></tr>
    <tr><td align="center"><select name="CamasxReducir">
    	<? 	for($i=0;$i<$fila[0];$i++){
				echo "<option value='$i'>$i</option>";
			}?>
    		</select>
    </td></tr>
    <tr><td align="center"><input type="submit" name="Reducir" value="Reducir"><input type="button" value="Regresar" onClick="location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>';"></td></tr>
</table>
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UnidadMod" value="<? echo $UnidadMod?>">
<input type="hidden" name="NocamasAnt" value="<? echo $fila[0]?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Aumentar){
		$newcamas=$NocamasAnt+$CamasxAumentar;
		$cons="update salud.pabellones set nocamas=$newcamas where ambito='$Ambito' and Compania='$Compania[0]' and pabellon='$UnidadMod'";
		//echo $cons;
		$res=ExQuery($cons);
		$cons5 = "Select idcama from Salud.camasxunidades where ambito='$Ambito' and unidad='$UnidadMod' and Compania = '$Compania[0]' order by idcama desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		$fila5 = ExFetch($res5);			
		$Id = $fila5[0]+1;
		for($i=0;$i<$CamasxAumentar;$i++){
			$cons2="Insert into salud.camasxunidades(compania,ambito,unidad,idcama,nombre) values ('$Compania[0]','$Ambito','$UnidadMod',$Id,'$Id') ";			   	
			$res2=ExQuery($cons2);echo ExError();
			//echo $cons2;
			$Id++;
		}
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
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">No de Camas a Aumentar</td></tr>
    <tr><td align="center"><select name="CamasxAumentar">
    	<? 	for($i=0;$i<$fila[0];$i++){
				echo "<option value='$i'>$i</option>";
			}?>
    		</select>
    </td></tr>
    <tr><td align="center"><input type="submit" name="Aumentar" value="Aumentar"><input type="button" value="Regresar" onClick="location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>';"></td></tr>
</table>
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UnidadMod" value="<? echo $UnidadMod?>">
<input type="hidden" name="NocamasAnt" value="<? echo $fila[0]?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

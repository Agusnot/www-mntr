<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Trasladar){	
		//disminuye las camas de la unidad origen	
		$newcamas=$NocamasAnt-$CamasxTrasladar;
		$cons="update salud.pabellones set nocamas=$newcamas where ambito='$Ambito' and Compania='$Compania[0]' and pabellon='$UnidadMod'";
		//echo "$cons<br>\n";
		$res=ExQuery($cons);
		
		//Aumenta las camas de la unidad destino
		$cons="select nocamas from salud.pabellones where ambito='$AmbitoaTrasl' and Compania='$Compania[0]' and pabellon='$PabellonaTrasl'";
		$res=ExQuery($cons);
		$fila = ExFetch($res);		
		$Nocamas=$fila[0];//numero de camas anterio und destino
		$newcamas=$Nocamas+$CamasxTrasladar;//nuevo numero de camas und destino
		$cons="update salud.pabellones set nocamas=$newcamas where ambito='$AmbitoaTrasl' and Compania='$Compania[0]' and pabellon='$PabellonaTrasl'";
		//echo "$cons<br>\n";
		$res=ExQuery($cons);
		
		$cons5 = "Select idcama from Salud.camasxunidades where ambito='$Ambito' and unidad='$UnidadMod' and Compania = '$Compania[0]' order by idcama desc";					
		//echo $cons5;
		$res5 = ExQuery($cons5);
		$fila5 = ExFetch($res5);			
		$IdR = $fila5[0];
		$cons6 = "Select idcama from Salud.camasxunidades where ambito='$Ambito' and unidad='$UnidadMod' and Compania = '$Compania[0]' order by idcama desc";					
		//echo $cons6;		
		$res6 = ExQuery($cons6);
		$fila6 = ExFetch($res6);		
		$IdA = $fila6[0]+1;
		for($i=0;$i<$CamasxTrasladar;$i++){
			$cons2="update Salud.camasxunidades set ambito='$AmbitoaTrasl',unidad='$PabellonaTrasl',idcama=$IdA,nombre='$IdA' where ambito='$Ambito' and unidad='$UnidadMod' and idcama=$IdR and Compania = '$Compania[0]'";			   	
			$res2=ExQuery($cons2);echo ExError();
			//echo "$cons2<br>\n";
			$IdR--;
			$IdA++;
		}		
		
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function validar(){	
	if(document.FORMA.AmbitoaTrasl.value==""){
		alert("Debe seleccionar un Ambito!!!");return false;
	}		
	if(document.FORMA.PabellonaTrasl.value==""){
		alert("Debe haber un Servicio al cual trasladar!!!");return false;
	}
}
</script>
</head>

<body  background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<? 
$cons="select nocamas from salud.pabellones where ambito='$Ambito' and Compania='$Compania[0]' and pabellon='$UnidadMod'";
$res=ExQuery($cons);
$fila = ExFetch($res);
$Nocamas=$fila[0];
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr><td colspan="2" align="center" bgcolor="#e5e5e5" style="font-weight:bold"><? echo "$Ambito - $UnidadMod";?></td></tr>
    <tr><td colspan="2" align="center" bgcolor="#e5e5e5" style="font-weight:bold"></td></tr>
	<tr><td colspan="2" align="center" bgcolor="#e5e5e5" style="font-weight:bold">No Total de Camas</td></tr>
    <tr><td colspan="2" align="center"><? echo $fila[0]?></td></tr>
    <tr><td colspan="2" align="center" bgcolor="#e5e5e5" style="font-weight:bold">No de Camas a Trasladar</td></tr>
    <tr><td colspan="2" align="center"><select name="CamasxTrasladar">
    	<? 	for($i=0;$i<$fila[0];$i++){
				if($i==$CamasxTrasladar){
					echo "<option value='$i' selected>$i</option>";
				}
				else{
					echo "<option value='$i'>$i</option>";
				}
			}?>
    		</select>
    </td></tr>
    <tr><td colspan="2" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Trasladar a:</td></tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    	<td><select name="AmbitoaTrasl" onChange="document.FORMA.submit()"><option></option>
		<? $cons="select ambito from salud.ambitos where compania='$Compania[0]' and consultaextern=0 order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$AmbitoaTrasl){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
        	</select></td>
    </tr>
    <tr><td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Servicio</td>
<?	$consult="Select * from Salud.Pabellones where ambito='$AmbitoaTrasl' and Compania='$Compania[0]'";		
	$result=ExQuery($consult);
	if(ExNumRows($result)>0){
		if(ExNumRows($result)==1){
			$row = ExFetchArray($result);
			if($row[0]==$UnidadMod&&$row[4]==$Ambito){
				if($AmbitoaTrasl){?>
					<input type="hidden" name="PabellonaTrasl" value="">
				<?	echo "<td align='center'>Este Proceso no tiene otro servicio al cual trasladar camas</td>";
				}
			}
			else{?>
            <td><select name="PabellonaTrasl">
                	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
                </select></td>
		<?	}
		}
		else{?>
	   		<td><select name="PabellonaTrasl">
<?			while($row = ExFetchArray($result)){
				if($row[4]==$Ambito){
					if($row[0]!=$UnidadMod){
						echo "<option value='$row[0]'>$row[0]</option>";
					}
				}
			}?>
			</select></td>
	<?	}
	}
	else{
		if($AmbitoaTrasl){?>
        	<input type="hidden" name="PabellonaTrasl" value="">
		<? echo "<td align='center'>Este Proceso no tiene unidades asignadas</td>";
		}
	}?>
    </tr>
   	<tr><td colspan="2" align="center"><input type="submit" name="Trasladar" value="Trasladar"><input type="button" value="Regresar" onClick="location.href='ConfCamasxUnd.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadMod=<? echo $UnidadMod?>';"></td></tr>
</table>
</table>
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="UnidadMod" value="<? echo $UnidadMod?>">
<input type="hidden" name="NocamasAnt" value="<? echo $Nocamas?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>

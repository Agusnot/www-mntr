<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	//echo "UnidadHosp=$UnidadHosp Ambito=$Ambito AmbitoAnt=$AmbitoAnt";	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body background="/Imgs/Fondo.jpg"> 

<form name="FORMA" method="post">
<input type="hidden" name="CamasDispo">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
    <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="15">CENSO DE ALIMENTOS</td>
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <td>
 <?	?>
    <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	
			$cons="select ambito from salud.ambitos where compania='$Compania[0]'  and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   		</select></td>
  
   	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Servicio</td>
   <? 	
   		$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]' order by pabellon";		
		$result=ExQuery($consult);
		if(ExNumRows($result)>0){?>        	           
		<td><select name="UnidadHosp" onChange="document.FORMA.submit()">       	
        	<option></option>
		<?	while($row = ExFetchArray($result)){				
				if($row[0]==$UnidadHosp){
					echo "<option value='$row[0]' selected>$row[0]</option>";
				}
				else{
					echo "<option value='$row[0]'>$row[0]</option>";
				}
			}
		?>	</select></td><?
		}
		else{
			if($Ambito){
				echo "<input type='hidden' name='UnidadHosp' value=''>";
				if($Ambito!=1){
					echo "<td style='font-weight:bold' align='center' colspan='7'>No se han hasignado unidades a este proceso</td>";
				}
			}			
		}?> 	

	    <td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Comedor</td>
   	<?	$consult="Select pabellon from Salud.Pabellones where Compania='$Compania[0]' order by pabellon";		
		$result=ExQuery($consult);?>        	           
		<td><select name="Comedor" onChange="document.FORMA.submit()">       	
        	<option></option>
            <option value="SinComedor"<? if($Comedor=="SinComedor"){?> selected="selected"<? }?>>Sin Comedor</option>
		<?	while($row = ExFetchArray($result)){				
				if($row[0]==$Comedor){
					echo "<option value='$row[0]' selected>$row[0]</option>";
				}
				else{
					echo "<option value='$row[0]'>$row[0]</option>";
				}
			}
		?>	</select>
       	</td>
        <td>
        	<input type="submit" value="Ver" name="Ver" />
        </td>
	</tr>        
</table>
<br />
<?
if($Ver)
{
	if($Ambito){$Amb="and tiposervicio='$Ambito'"; $Amb2="and ambito='$Ambito'";}
	if($UnidadHosp){$Und="and pabellon='$UnidadHosp'";}
	$cons="select cedula,pabellon,numservicio from salud.pacientesxpabellones where compania='$Compania[0]' and estado='AC' and fechae is null $Amb2 $Und
	order by fechai desc";
	//echo $cons;
	$res=ExQuery($cons);
	$BanPab=0;
	while($fila=ExFetch($res))
	{
		$Entra=1;
		$PacxPab[$fila[0]]=$fila[1];	
		if($UnidadHosp){
			if($BanPab==0){$RestricPabs="and numservicio in ($fila[2]";$BanPab=1;}
			else{$RestricPabs=$RestricPabs.",$fila[2]";}
		}
	}	
	if($UnidadHosp&&$Entra){$RestricPabs=$RestricPabs.")";}
	if($Comedor){
		if($Comedor!='SinComedor'){$Come="and comedor='$Comedor'";}
		else{$Come="and comedor is null";}
	}
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' ||segnom),cedula,tiposervicio,comedor 
	from salud.servicios,central.terceros where servicios.compania='$Compania[0]' and estado='AC' and identificacion=Cedula $Amb $Come $RestricPabs
	order by primape,segape,primnom,segnom";
	//echo $cons;
	$res=ExQuery($cons);?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
    	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
        	<td></td><td>Paciente</td><td>Identificacion</td><td>Proceso</td><td>Servicio</td><td>Comedor</td>
        </tr>
  	<?	$cont=1;
		while($fila=ExFetch($res))
		{
			echo "<tr><td>$cont</td><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>".$PacxPab[$fila[1]]."&nbsp;</td><td>$fila[3]&nbsp;</td></tr>";	
			$cont++;
		}?>
    </table>	<?
}?>
<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
<input type="hidden" name="Regresa" value="">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>

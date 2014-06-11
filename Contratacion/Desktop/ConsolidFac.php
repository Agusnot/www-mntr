<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar()
	{		
		if(document.FORMA.Desde.value>document.FORMA.Hasta.value){alert("La fecha final debe ser mayor a la fecha final!!!");return false;}	
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="11">Consolidacion de Facturas</td>
   	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Ambito</td>
        <td>
        <?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' 
			and (consultaextern=1 or pyp =1) order by ambito";
			$res=ExQuery($cons);?>
            <select name="Ambito" onChange="document.FORMA.submit()">
	            <option></option>                
          	<? 	while($fila=ExFetch($res))
				{
					if($Ambito==$fila[0]){
						echo "<option value='$fila[0]' selected>$fila[0]</option>";	
					}	
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";	
					}
				} ?>      
           	</select>
        </td>
        <!--
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Unidad</td>
        <td>
        <?	$cons="select pabellon from salud.pabellones where compania='$Compania[0]' and ambito='$Ambito' order by pabellon";
			$res=ExQuery($cons);  
			if(ExNumRows($res)>0){?>
				<select name="Pabellon">
                	<option></option>
                <?	while($fila=ExFetch($res))
					{
						if($Pabellon==$fila[0]){
							echo "<option value='$fila[0]' selected>$fila[0]</option>";	
						}	
						else{
							echo "<option value='$fila[0]'>$fila[0]</option>";	
						}
					}?>
                </select>
		<?	}
			else{
				echo "<strong>Ambito Sin Unidades</strong>";
			}?>
        </td>
   	<?	if(!$Desde){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$Desde="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        -->
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Identificacion</td>
		<td>
        	<input type="text" name="CedPac" value="<? echo $CedPac?>" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)"
            style="width:100">
        </td>  
   <!--      
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
        <td>
        	<input type="text" readonly name="Desde" onClick="popUpCalendar(this, FORMA.Desde, 'yyyy-mm-dd')" value="<? echo $Desde?>" 
            style="width:80"/>
        </td>
     <?	if(!$Hasta){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$Hasta="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>            
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td>
        	<input type="text" readonly name="Hasta" onClick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')" value="<? echo $Hasta?>" 
            style="width:80"/>
        </td>        
    </tr>-->
    <tr>
    	<td align="center" colspan="11">
        	<input type="submit" name="Ver" value="Ver">
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="PacientesxConsolid" src="PacientesxConsolid.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Pabellon?>&FechaIni=<? echo $Desde?>&FechaFin=<? echo $Hasta?>&Ver=<? echo $Ver?>&CedPac=<? echo $CedPac?>" width="100%" height="85%">
</iframe>
</body>
</html>
<?php	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	if($Agregar==1){
		$cons = "Select Idhorario from salud.dispoconsexterna where  fecha='$Anio-$Mes-$Dia' and usuario='$Medico' and compania='$Compania[0]' order by Idhorario desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Idhorario = $fila[0] +1;
		if($MaxCitas){$MC1=",citaspermitidas";$MC2=",$MaxCitas";}
		$cons="insert into salud.dispoconsexterna (horaini,minsinicio,horasfin,minsfin,idhorario,usuario,fecha,compania $MC1) values 
		($HoraIni,$MinsInicio,$HorasFin,$MinsFin,$Idhorario,'$Medico','$Anio-$Mes-$Dia','$Compania[0]' $MC2)";
			
			
		$res = ExQuery($cons);echo ExError();	
		$HoraIni='';$MinsInicio=''?>
		<script language="javascript">
		document.FORMA.submit();
		</script>
        <?php
	}
	if($Eliminar==1){
		$cons = "Select Idhorario from salud.dispoconsexterna where  fecha='$Anio-$Mes-$Dia' and usuario='$Medico' and compania='$Compania[0]' order by Idhorario desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Idhorario = $fila[0];
		$cons="Delete from salud.dispoconsexterna where idhorario=$Idhorario and fecha='$Anio-$Mes-$Dia' and usuario='$Medico' and compania='$Compania[0]'";
		$res = ExQuery($cons);echo ExError();
	}
	function CalcDia($Dia)
	{
		global $Anio;
		global $Mes;
		$d=date('w',mktime(0,0,0,$Mes,$Dia,$Anio));	
		switch($d){
			case 1: $Diasem='Lun';return $Diasem; break;
			case 2: $Diasem='Mar';return $Diasem; break;
			case 3: $Diasem='Mie';return $Diasem; break;
			case 4: $Diasem='Juv';return $Diasem; break;
			case 5: $Diasem='Vie';return $Diasem; break;
			case 6: $Diasem='Sab';return $Diasem; break;
			case 0: $Diasem='Dom';return $Diasem; break;
		}
	}	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function eliminar(){
	document.FORMA.Eliminar.value=1;
	document.FORMA.submit();
}
function agregar(){
	
		document.FORMA.Agregar.value=1;
		document.FORMA.submit();
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr><?
    	$res=ExQuery("Select nombre,Medicos.usuario as usu from Salud.Medicos,central.usuarios 
					where Medicos.usuario=usuarios.usuario and Medicos.usuario='$Medico' and Compania='$Compania[0]'");
			$r=ExFetchArray($res);
		?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6"><? echo $r[0]?></td>
    </tr>	
    <tr><? $DiaSemana=CalcDia($Dia);?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6"><? echo $Anio."-".$Mes."-".$Dia." ".$DiaSemana?></td>
    </tr>
    <tr>
  		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora inicio</td><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="2">Hora Fin</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Max Citas</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold"  ></td>
    </tr>
    <?
    	$cons="select horaini,minsinicio,horasfin,minsfin,idhorario,citaspermitidas
		from salud.dispoconsexterna where fecha='$Anio-$Mes-$Dia' and compania='$Compania[0]'and usuario='$Medico' order by idhorario";
	$res=ExQuery($cons);		
	$n=ExNumRows($res);
	$n2=ExNumRows($res);
		while($fila=ExFetch($res))
		{	if($fila[1]==0){$Cero2=0;}else{$Cero2='';}
			if($fila[3]==0){$Cero3=0;}else{$Cero3='';}				
			if($n==1)
			{
				 $HorI=$fila[0]; $MinI=$fila[1]; $HorF=$fila[2]; $MinF=$fila[3];					
				echo "<tr align='center'><td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>$fila[5]&nbsp;</td><td>";?>				
				<img title="Eliminar" style="cursor:hand" onClick="eliminar()" src="/Imgs/b_drop.png"></td></tr>
               
         <? }
			else
			{
				echo "<tr align='center'><td colspan='2'>$fila[0]:$fila[1]$Cero2</td><td colspan='2'>$fila[2]:$fila[3]$Cero3</td><td>$fila[5]&nbsp;</td></tr>";
			}
			$n--;			
		}
	if($HorF!=21){
		if($n2>0)
		{ ?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    			<td >Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td><td >No. Max Citas</td><td></td>
    		</tr>
	    	<tr align="center">
    	       	<td> 
        	    <select name="HoraIni" onChange="document.FORMA.submit()"><option></option>
	         <? for($j=$HorF;$j<21;$j++) {
					if($j==$HoraIni){ ?>
	    	    		<option value="<? echo $j?>" selected><? echo $j?></option>
	          <?	}
					else{?>
        	        	<option value="<? echo $j?>"><? echo $j?></option>
	          <?	} 
			  	}?>        		
	        	</select>     
	          	</td>
    	    	<td><? if($HoraIni==$HorF){
							$MI=$MinF;
						}
						else{							
							$MI=0;
						}?>
        	     <select name="MinsInicio" onChange="document.FORMA.submit()"> <option></option>
	        <? if($HoraIni!=''){
					 for($k=$MI;$k<60;$k+=10) { 
						if($k==$MinsInicio&&$MinsInicio!=''){ ?>
	    	    			<option value="<? echo $k?>" selected><? echo $k?></option>
	            	<? }else
						{?>
        	        		<option value="<? echo $k?>"><? echo $k?></option>
	        	  <?	} 
			  		}
				}?>
	    	    </select>
            	</td>
	            <td><? if($MinsInicio==50){$HoraI=$HoraIni+1;}else{$HoraI=$HoraIni;}?>           
    	         <select name="HorasFin" onChange="document.FORMA.submit()">
        	 	<?	 
				 if($MinsInicio!=''){
		 			for($j=$HoraI;$j<22;$j++){   
                		if($j==$HorasFin&&HorasFin>$HoraIni) { $Ban1=1;?>            								 	
                  			<option value="<? echo $j?>" selected><? echo $j?></option>
	             <? 	}
				 		else{?>
        	            	<option value="<? echo $j?>"><? echo $j?></option>
					<?	}
			 		}				 
				}?>        		
		        </select>  
        	    <td><?
            		if($Ban1==1){
						if($HorasFin!=$HoraIni){
							$MinsI=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
							$BanMF=1;				
						}
						else{
							if($MinsFin>$MinsInicio)
							{							
								$BanMF=1;
							}
							$MinsI=$MinsInicio+10;					
						}
					}
					else{					
						$MinsI=$MinsInicio+10;
						if($HoraIni==($HoraI-1)&&$MinsInicio==50){
							$MinsI=0;
						}					
					}?>
	        	    <select name="MinsFin">
    	    <?	if($MinsInicio!=''){
					if($HorasFin!=21){	
						if($MinsInicio!=50||$HoraIni!=20){
							for($k=$MinsI;$k<60;$k+=10) {  
        	        	    	if($BanMF==1&&$MinsFin==$k&&$MinsFin!=''){?>		
									<option value="<? echo $k?>" selected><? echo $k?></option> 
	                	      <?  }
    	                	    else
								{?>
            	             		<option value="<? echo $k?>"><? echo $k?></option>        		 
					  	<?		}
							} 
						}
						else
						{?>
            	    			<option value="0">0</option>
		           <? } 
				 	}
					else{?>
						<option value="0">0</option>
			<?		}
				}?>				
		        </select> 
                 <td>
            	<select name="MaxCitas">
                <option></option>
            <?	for($i=1;$i<=100;$i++)
				{
					if($MaxCitas==$i){echo "<option value='$i' selected>$i</option>";}	
					else{echo "<option value='$i'>$i</option>";}	
				}?>
            	</select>
            </td>
    	        <td>  <? if($MinsInicio!=''){?><img title="Correcto" onClick="agregar()" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    		    </td>   
	        </tr>
 <? 	}
 		else
		{?>		
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	    	<td>Hora</td><td>Minutos</td><td>Hora</td><td>Minutos</td>
    	</tr>
        <tr align="center">
           	<td><select name="HoraIni" onChange="document.FORMA.submit()">
         <? for($j=6;$j<21;$j++) {
				if($j==$HoraIni){?>
	        		<option value="<? echo $j?>" selected><? echo $j?></option>
          <?	}
				else{?>
                	<option value="<? echo $j?>"><? echo $j?></option>
          <?	} 
		  	}?>        		
	        </select>
          	</td>
        	<td>
             <select name="MinsInicio" onChange="document.FORMA.submit()"> <option></option>
        <?  for($k=0;$k<60;$k+=10) { 
				if($k==$MinsInicio&&$MinsInicio!=''){?>
	        		<option value="<? echo $k?>" selected><? echo $k?></option>
            <? }else
				{?>
                	<option value="<? echo $k?>"><? echo $k?></option>
          <?	} 
		  	}?>
	        </select>
            </td>
            <td><? if($MinsInicio==50){$HoraI=$HoraIni+1;}else{$HoraI=$HoraIni;}  ?>           
             <select name="HorasFin" onChange="document.FORMA.submit()">
         	<?	 
			 if($MinsInicio!=''){
		 		for($j=$HoraI;$j<22;$j++){   
                	if($j==$HorasFin&&HorasFin>$HoraIni) { $Ban1=1;?>            								 	
                  		<option value="<? echo $j?>" selected><? echo $j?></option>
             <? 	}
			 		else{?>
                    	<option value="<? echo $j?>"><? echo $j?></option>
				<?	}
			 	}				 
			}?>        		
	        </select>  
            <td><?
            	if($Ban1==1){
					if($HorasFin!=$HoraIni){
						$MinsI=0;//Los minutos iniciales son 50 pero las horas finales son mayores a las horas iniciales
						$BanMF=1;				
					}
					else{
						if($MinsFin>$MinsInicio)
						{							
							$BanMF=1;
						}						
						$MinsI=$MinsInicio+10;					
					}
				}
				else{					
					$MinsI=$MinsInicio+10;
					if($HoraIni==($HoraI-1)&&$MinsInicio==50){
						$MinsI=0;
					}					
				}
				?>
            <select name="MinsFin">
        <?	if($MinsInicio!=''){
				if($HorasFin!=21){	
					if($MinsInicio!=50||$HoraIni!=20){
						for($k=$MinsI;$k<60;$k+=10) {  
        	            	if($BanMF==1&&$MinsFin==$k&&$MinsFin!=''){?>		
								<option value="<? echo $k?>" selected><? echo $k?></option> 
                	      <?  }
                    	    else
							{?>
                         		<option value="<? echo $k?>"><? echo $k?></option>        		 
				  	<?		}
						} 
					}
					else
					{?>
                			<option value="0">0</option>
	           <? } 
			 	}
				else{?>
					<option value="0">0</option>
		<?		}
			}?>
				
	        </select> 
            <td>
            	<select name="MaxCitas">
                <option></option>
            <?	for($i=1;$i<=100;$i++)
				{
					if($MaxCitas==$i){echo "<option value='$i' selected>$i</option>";}	
					else{echo "<option value='$i'>$i</option>";}	
				}?>
            	</select>
            </td>
            <td>  <? if($MinsInicio!=''){?><img title="Correcto" onClick="agregar()" src="/Imgs/b_check.png" style="cursor:hand" > <? }?></td>          
    	    </td>   
        </tr>
 <?		}
 	}?> 	
    <tr>
    	<td align="center" colspan="6"><input type="button" value="Agregar" onClick="location.href='DisponibilidadMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>'"><input type="button" value="Cancelar" onClick="location.href='DisponibilidadMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>'"></td>        
    </tr>
</table>
<input type="hidden" name="Anio" value="<? echo $Anio?>">
<input type="hidden" name="Mes" value="<? echo $Mes?>">
<input type="hidden" name="Dia" value="<? echo $Dia?>">
<input type="hidden" name="Medico" value="<? echo $Medico?>">
<input type="hidden" name="Eliminar" value="0">
<input type="hidden" name="Agregar" value="0">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

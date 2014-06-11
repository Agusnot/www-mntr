<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select nombre,usuarios.usuario,cargo from central.usuarios,salud.medicos where compania='$Compania[0]' and medicos.usuario=usuarios.usuario and medicos.usuario='$usuario[1]'";	
	$res=ExQuery($cons);
	$Medico=ExFetch($res);
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td align="center" colspan="8">Agenda</td>       
	</tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td align="center" colspan="8"><? echo "$Medico[0] - $Medico[2]";?></td>       
	</tr>
    <tr align="center">    	
    <?	if(!$Fecha){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$Fecha="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
    	<td>Dia: <input type="text" name="Fecha" readonly="readonly" onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')" value="<? echo $Fecha?>" style="width:70px" onChange="document.FORMA.submit()"/></td>
    </tr>
    <tr align="center">
    	<td><input type="submit" value="Ver" name="Ver" />
    </tr>
</table>
<br>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<?	if($Ver){
	 	$cons="select dia,motivo from salud.bloqueoxdia where dia='$Fecha' and compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();
		if(ExNumRows($res)>0){
			$fila=ExFetch($res);?>
			 <tr bgcolor="#e5e5e5" style=" font-weight:bold" align="center">
				<td>El dia <? echo $fila[0]?> por motivo de <? echo $fila[1]?></td>
			</tr>
	<?	}
		else{
			$cons="select horaini,minsinicio,horasfin,minsfin,idhorario from salud.dispoconsexterna 
			where usuario='$Profecional' and fecha='$Fecha' and compania='$Compania[0]' order by idhorario"; 			
			$res=ExQuery($cons);echo ExError();
			if(ExNumRows($res)>0){?>     					
				<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6"> <? echo "$Fecha";?></td></tr>
				<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
					<td>Hora</td><td>Cedula</td><td>Nombre<td>Telefono</td><td>Entidad</td><td>Estado</td> 
				</tr>               
			<?
				while($fila=ExFetch($res))
				{
					$tim=((($fila[2]-$fila[0])*60)-$fila[1])+$fila[3];
					//echo "tim=$tim";
					$HI=$fila[0];$MI=$fila[1];
					if($MI==50){$HF=$HI+1;$MF=0;}else{$HF=$HI;$MF=$MI+10;}
					for($i=0;$i<$tim;$i=$i+10)
					{
						if($MI<10){$cero='0';}else{$cero='';}			
						$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons,fecha,id from central.terceros,salud.agenda 
						where terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' and (estado='Pendiente' or estado='Activa' or estado='Atendida') 
						and hrsini=$HI and minsini=$MI 
						and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' order by hrsini,minsini,fecha";
						//echo $cons2;
						$res2=ExQuery($cons2);echo ExError();
						if(ExNumRows($res2)>0)
						{					
							$azul=1;
							while($fila2=ExFetch($res2))
							{
								if($fila2[3]<10){$cero2='0';}else{$cero2='';}	
								$cons5="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where  identificacion='$fila2[10]' and 
								Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
								$res5=ExQuery($cons5);echo ExError();//consulta de la agenda
								$fila5=ExFetchArray($res5);
								if($fila2[11]=='Atendida'){?>
									<tr onMouseOver="this.bgColor='#AAD4FF'"  onMouseOut="this.bgColor=''" style="cursor:hand" align="center" title="Abrir Historia Clinica"
                                     onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
						<?		}
								else{
									if($fila2[11]=='Activa'){?>
										<tr onMouseOver="this.bgColor='#AAD4FF'" style="cursor:hand" onMouseOut="this.bgColor=''" align="center" title="Abrir Historia Clinica"
                                        onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">	
								<?	}
									else{?>
										<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center" title="Abrir Historia Clinica"
                                        onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">													
								<?	}
								}
								if($azul==1){						
									echo "<td>$HI:$cero$MI-$fila2[2]:$cero2$fila2[3]</td><td>$fila2[4]</td><td>$fila2[5] $fila2[6] $fila2[7] $fila2[8]</td><td>$fila2[9]</td><td>$fila5[0]</td>
									<td>$fila2[11]</td></tr>";  							
									$HIAux=$fila2[2];$MIAux=$fila2[3];												
									$iAux=$i+$fila2[12]-10;
									//echo "<tr><td>$iAux</td><tr>";
									$azul=0;
								}
								else{                        	
									echo "<td><font color='#0000FF'>$HI:$cero$MI-$fila2[2]:$cero2$fila2[3]</font></td><td><font color='#0000FF'>$fila2[4]</font></td><td><font color='#0000FF'>
									$fila2[5] $fila2[6] $fila2[7] $fila2[8]*</font></td><td><font color='#0000FF'>$fila2[9]</font></td><td><font color='#0000FF'>$fila5[0]</font></td>
									<td><font color='#0000FF'>$fila2[11]</font></td></tr>"; 
								}
							}									
						}
						else
						{
							if($MF<10){$cero2='0';}else{$cero2='';}?>	
							<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
						<?	echo "<td>$HI:$cero$MI-$HF:$cero2$MF</td><td colspan='5' align='center'>-Sin Asignar-</td></td></tr>";	
						}	
						//if($HIAux!=''){$HI=$HIAux;$MI=$MIAux;}					
						if($HIAux!=''){
							$HI=$HIAux; $HIAux='';
							$MI=$MIAux; $MIAux='';
							$i=$iAux;	$iAux='';	
							$HF=$HI;
							$MF=$MI;		
						}
						else{	
							$MI=$MI+10;
						}				
						if($MI==50){
							$HF++;$MF=0;
						}else{
							if($MI==60){
								$HI++;$MI=0;
							}
							$MF=$MF+10;
						}
						//echo "HI=$HI,MI=$MI";
					}
				} 
					
		//-----------------------------------------------------------------------------Citas Canceladas-----------------------------------------------------------------------------
				$cons4="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons from central.terceros,salud.agenda where
				terceros.identificacion=agenda.cedula and medico='$Profecional' and fecha='$Fecha' and (estado='Cancelada') and agenda.compania='$Compania[0]' 
				and terceros.compania='$Compania[0]'";
				$res4=ExQuery($cons4);echo ExError();
				if(ExNumRows($res4)>0){?> 		
					<tr>
						<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="6">Citas Canceladas</td>    
					</tr>
					<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
						<td>Hora</td><td>Cedula</td><td>Nombre<td>Telefono</td><td>Entidad</td><td>Estado</td>        
					</tr>
			 <? 	while($fila4 = ExFetchArray($res4)){ 
						$cons6="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros 
						where  identificacion='$fila4[10]' and Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
						$res6=ExQuery($cons6);echo ExError();//consulta de la agenda
						$fila6=ExFetchArray($res6);
						if($fila4[3]==0){$cero5='0';}else{$cero5='';}
						if($fila4[1]==0){$cero4='0';}else{$cero4='';}?>  
						<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
				<?  	echo "<td>$fila4[0]:$fila4[1]$cero4-$fila4[2]:$fila4[3]$cero5</td><td>$fila4[4]</td><td>$fila4[5] $fila4[6] $fila4[7] $fila4[8]</td><td>$fila4[9]</td>
						<td>$fila6[0]</td><td>$fila4[11]</td><tr>";        
					}		
				}
			//cierra if de disponibilidad para el dia seleccionado
			}
			else{?>
				<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center"> <? echo "$F[0]-$Especialidad ";?></td></tr>
				<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center"> <? echo "$Fecha";?></td></tr>
				<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No existe disponibilidad para este dia</td></tr>
	<?		}	
		}
	}
?>    
 
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="Especialidad" value="<? echo $Medico[2]?>" />
<input type="hidden" name="Profecional" value="<? echo $Medico[1]?>" />

</form>
</body>
</html>

		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
			//echo "UnidadHosp=$UnidadHosp Ambito=$Ambito AmbitoAnt=$AmbitoAnt";	
		?>
		
		
	<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				
				<script language="javascript">
				function raton(e,Id) { 
					x = e.clientX; 
					y = e.clientY; 	
					frames.FrameOpener2.location.href="OpcsAsigCamas.php?DatNameSID=<? echo $DatNameSID?>&Idcama="+Id+"&Ambito=<? echo $Ambito?>&UnidadHosp="+document.FORMA.UnidadHosp.value;
					document.getElementById('FrameOpener2').style.position='absolute';
					document.getElementById('FrameOpener2').style.top=y+25;
					document.getElementById('FrameOpener2').style.left=x;
					document.getElementById('FrameOpener2').style.display='';
					document.getElementById('FrameOpener2').style.width='140px';
					document.getElementById('FrameOpener2').style.height='90px';
				} 
				</script>
			</head>

		<body <?php echo $backgroundBodyMentor; ?>> 
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
				$rutaarchivo[2] = "ASIGNAR CAMAS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
				<input type="hidden" name="CamasDispo">
					<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="15">ASIGNAR CAMAS</td>
						</tr>	
						<tr>
							<td class='encabezado2HorizontalInvertido'>PROCESO</td>
							<td>
								<?	if($Ambito==''){ echo "<input type='hidden' name='Ambito' value='1'>";$Ambito=1;}?>
									<select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
										<?	
											$cons="select ambito from salud.ambitos where compania='$Compania[0]' and consultaextern=0 and ambito!='Sin Ambito' order by ambito";	
											$res=ExQuery($cons);echo ExError();	
											while($fila = ExFetch($res)){
												if($fila[0]==$Ambito){
													echo "<option value='$fila[0]' selected>$fila[0]</option>";
												}
												else{
													echo "<option value='$fila[0]'>$fila[0]</option>";
												}
											}?>
									</select>
							</td>
				  
							<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
							   <? if(!$Regresa){
									if(!$UnidadHosp){
										$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
										$result=ExQuery($consult);
										$row = ExFetchArray($result);
										$UnidadHosp=$row[0];
									}
									if($Ambito!=$AmbitoAnt){
										$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
										$result=ExQuery($consult);
										$row = ExFetchArray($result);
										$UnidadHosp=$row[0];
										
									}
								}	
									
									$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
									$result=ExQuery($consult);
									if(ExNumRows($result)>0){?>        	           
									<td><select name="UnidadHosp" onChange="document.FORMA.submit()">       	
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
												echo "<td class='encabezado2HorizontalInvertido' colspan='7'>No se han asignado unidades a este proceso</td>";
											}
										}			
									}?>
						</tr>
					</table>
				<br>
				<table class="tabla2"  style="text-align:center;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >      
					<? $cons="select * from salud.camasxunidades where compania='$Compania[0]' and ambito='$Ambito' and unidad='$UnidadHosp' order by idcama";
					$res=ExQuery($cons);echo ExError();
					//echo $cons;
						echo "<tr>";		
						while($row = ExFetch($res)){			
							$i++;
							$cons2="select primnom,segnom,primape,segape from central.terceros,salud.pacientesxpabellones  where identificacion=cedula and pabellon='$UnidadHosp' and ambito='$Ambito' and idcama=$row[3] and estado='AC' and terceros.compania='$Compania[0]' and pacientesxpabellones.compania='$Compania[0]'";	
							$res2=ExQuery($cons2);echo ExError();			
							//echo $cons2."<br>\n";		
							if($i==8){echo "</tr><tr>";$i=1;}
							echo "<td align='center' style='width:100px'>$row[4]<br>";			            
							if(ExNumRows($res2)>0){
							//echo "entra";
								$row2 = ExFetch($res2);
								if($row[6]=='AC'){?>            
									<img title='Ocupada' src='/Imgs/CAMAP.png'>
							<?	}
								else{?>
									<img title='Inactiva' src='/Imgs/CAMAPX.png'>
							<?	} 
								echo "<br><font color='#FF0000'> $row2[0] $row2[1]<br> $row2[2] $row2[3]</font></td>";
							}
							else{
								if($row[6]=='AC'){//AbrirCama('<? echo $row[3]')?>            
									<img title='Asignar' src='/Imgs/CAMAP.png' style='cursor:hand' onClick="raton(event,'<? echo $row[3]?>')">
									<br> <span style="color:#002147;font-weight:bold;">Libre</span>
							<?	}
								else{?>
									<img title='Inactiva' src='/Imgs/CAMAPX.png'>
									<br><span style="color:#FF0000;">Inactiva </span>                    
							<?	} ?>
								
						<?	}			
						}
					?>
					<tr>    
				</table>
				<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
				<input type="hidden" name="Regresa" value="">
				<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form>
				<iframe scrolling="no" id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>    
			</div>	
		</body>
	</html>

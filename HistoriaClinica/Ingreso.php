		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$cero1='0';}else{$cero1='';}
			if($ND[mday]<10){$cero2='0';}else{$cero2='';}
			$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
		?>	

		
		<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				</head>

				<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "INGRESO";																
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
							<form name="FORMA" method="post">
								<table class="tabla2" style="margin-top:25px;margin-bottom:25px;" width="250px;"  <?php echo $bordertabla2Mentor; echo $bordercolortabla2Mentor; echo $cellspacingtabla2Mentor; echo $cellpaddingtabla2Mentor; ?>>
									<tr>
										<td class='encabezado2Horizontal' colspan="15">INGRESOS PACIENTES</td>
									<tr>
										<td class='encabezado2HorizontalInvertido' >PROCESO</td>
									<tr>
										<td style="text-align:center;">
											<select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
												<?	
													$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";	
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
									</tr>
								</table>       
								
								<? 	if($Ambito){
									
									$consCargo = "SELECT cargo FROM salud.medicos WHERE usuario = '$usuario[1]'";
									$resCargo = ExQuery($consCargo);
									$filaCargo = ExFetch($resCargo);
									
									
									if($filaCargo[0] == 'AUXILIAR DE ENFERMERIA' || $filaCargo[0] == 'JEFE DE ENFERMERIA' ){
										$ingreso = 2;		
									}
									
									if($filaCargo[0] == 'SIAU' ){
										$ingreso = 0;
									}
												
									?>
									
									
									<table class="tabla2" style="margin-top:25px;margin-bottom:25px;text-align:justify;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>><?		
										$cons="select primape,segape,primnom,segnom,identificacion,numservicio from salud.servicios,central.terceros where 
										servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and servicios.estado='AC' and servicios.cedula=terceros.identificacion and servicios.ingreso=$ingreso
										and tiposervicio='$Ambito' group by primnom,segnom,primape,segape,identificacion,numservicio
										order by primnom,segnom,primape,segape";

										$res=ExQuery($cons); 
										if(ExNumRows($res)>0){?>
										<tr>
											<td  class='encabezado2Horizontal' >CEDULA</td>
											<td  class='encabezado2Horizontal'>NOMBRE</td>
											<td  class='encabezado2Horizontal'>ASEGURADORA</td>
											<td  class='encabezado2Horizontal'>CONTRATO</td>
											<td  class='encabezado2Horizontal'>NO. CONTRATO</td>
										</tr>
										<?	while($fila=ExFetch($res)){
												$cons2="select primape,segape,primnom,segnom,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
												where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
												and pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
												$res2=ExQuery($cons2);	
												//echo $cons2;  		  	
												if(ExNumRows($res2)>0){
													$fila2=ExFetch($res2);
													$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
												}
												else{			
													$cons3="select primape,segape,primnom,segnom,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
													where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
													pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini";
													$res3=ExQuery($cons3);	
													if(ExNumRows($res3)>0){				
														$fila3=ExFetch($res3);
														if(!$fila3[6]){
															$EPS="$fila3[0] $fila3[1] $fila3[2] $fila3[3]"; $Contra="$fila3[4]"; $NoContra="$fila3[5]";	
														}
														else{
															$EPS=""; $Contra=""; $NoContra="";
														}
													}
													else{
														$EPS=""; $Contra=""; $NoContra="";
													}
												}

												$cont = 0;
												if($filaCargo[0] == 'AUXILIAR DE ENFERMERIA' || $filaCargo[0] == 'JEFE DE ENFERMERIA' ){
													$area = "ADMINISTRATIVA";
													$cons5 = "SELECT numservicio FROM salud.servicios WHERE cedula = '$fila[4]' AND estado = 'AC' ORDER BY numservicio DESC LIMIT 1 ";
													$res5 = ExQuery($cons5);
													$numServicio = ExFetch($res5);
													
													$cons3 = "SELECT pregunta FROM salud.preguntasingreso WHERE compania='$Compania[0]' AND area = '$area' ";
													$res3 = ExQuery($cons3);
													while($fila3 = ExFetch($res3)){
														$fecha = $ND[year]."-".$ND[mon]."-".$ND[mday];
														$cons4 = "SELECT pregunta FROM salud.checkeos WHERE cedula = '$fila[4]' AND pregunta = '$fila3[0]' AND numservicio = $numServicio[0]";

														$res4 = ExQuery($cons4);
														if(ExNumRows($res4) > 0){
															$cont++;						
														}
													}
													
													if(ExNumRows($res3) == $cont){

												?>
														<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="location.href='NewIngreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[4]?>&Ambito=<? echo $Ambito?>'"><? 
														echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
														if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td></tr>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td></tr>";}
													}
												}else{ ?>
													<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="location.href='NewIngreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[4]?>&Ambito=<? echo $Ambito?>'"><? 
													echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
													if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td></tr>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td></tr>";}
												}
											}
										}
										else
										{?>
											<tr>
												<td class="mensaje1">No hay pacientes con orden de ingreso en esta unidad</td>
								<?		}?>
									</table> 
							<?	}?>    
								<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							</form>
				</body>
			</html>

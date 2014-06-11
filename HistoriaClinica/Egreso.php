		<?php


			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
			$ND=getdate();
			
			if($ND[mon]<10){$cero='0';}else{$cero='';}
			if($ND[mday]<10){$cero1='0';}else{$cero1='';}
			$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
			$cons="select tipoformato,formato,tblformat from historiaclinica.formatos where compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$Formatos[$fila[0]][$fila[1]]=$fila[2];
			}
			$parametro2 = $_GET['y']; 
			/*echo "<pre>";
			print_r($_GET);*/
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

					function validar( yy, nom ){

						var r = confirm("¿Esta seguro de Iniciar Egreso del Paciente "+nom);
						if(r){		
								document.FORMA.con_egreso.value=yy;
								document.FORMA.nom_egreso.value=nom;
								document.FORMA.submit();
						}
						
					}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "EGRESO";										
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
					
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
						<form name="FORMA" method="post"> 
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="15" class="encabezado2Horizontal">EGRESOS PACIENTES</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">PROCESO</td>
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
										
									}
												
									?>
								</select></td>
							</tr>
						</table>       
						<br>
						<? 	
						if ($con_egreso) {
							$query = "UPDATE salud.servicios SET egreso = 1  WHERE cedula = '$con_egreso' ";
							$query1 = "UPDATE salud.ordenesmedicas SET usuinicio = '$usuario[1]', fechainicio= 'NOW'  WHERE cedula = '$con_egreso' ";
							$res1 = ExQuery($query1);
							if ($res=ExQuery($query)) {
								echo '<script>alert("Se ha iniciado el proceso de egreso para el paciente '.$nom_egreso.'");</script>';
							}
						}
						$ne= $Ambito;

						if($Ambito){
								$Amb="and tiposervicio='$Ambito'";
								$consCargo = "SELECT cargo FROM salud.medicos WHERE usuario = '$usuario[1]'";
								$resCargo = ExQuery($consCargo);
								$filaCargo = ExFetch($resCargo);
								$cargo = $filaCargo[0]; 
								
								if($cargo == 'AUXILIAR DE ENFERMERIA' || $cargo == 'JEFE DE ENFERMERIA' ){
									$egreso = "AND egreso = 1";
									$t=1;
								}
								
								if($cargo == 'SIAU' ){
								if($parametro2 == '1'){
									$egreso = "AND egreso = 1 AND ordenesmedicas.estado = 'AC'";
									$t = 1;
								}
								else if($parametro2 == '3'){
								   $egreso = "AND egreso = 3 AND ordenesmedicas.estado = 'AN' ";
								   $t = 3;
								}else{
									$egreso = "AND egreso = 0 AND ordenesmedicas.estado = 'AC' ";
									$t = 0;
								}
									//$egreso = "AND egreso = (SELECT CASE WHEN usuegreso IS NULL AND tbl00030.cedula = ordenesmedicas.cedula  THEN 0 ELSE 3 END )";
								}
								
							?>  
							<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>><?
								/*$cons="select primnom,segnom,primape,segape,identificacion,servicios.numservicio,tiposervicio from salud.ordenesmedicas,salud.servicios,central.terceros 
								where ordenesmedicas.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and tipoorden='Orden Egreso' $Amb 
								and servicios.estado='AC' and ordenesmedicas.estado='AC' and ordenesmedicas.cedula=servicios.cedula and ordenesmedicas.cedula=terceros.identificacion 
								group by primnom,segnom,primape,segape,identificacion,servicios.numservicio,tiposervicio order by primnom,segnom,primape,segape";*/
								$cons="select primnom,segnom,primape,segape,identificacion,ordenesmedicas.numservicio, egreso 
								from salud.ordenesmedicas,central.terceros , salud.servicios, histoclinicafrms.tbl00030
								where ordenesmedicas.compania = '$Compania[0]'
								and terceros.compania = ordenesmedicas.compania 
								and ordenesmedicas.cedula = identificacion 
								and ordenesmedicas.tipoorden = 'Orden Egreso' 
								and servicios.cedula = ordenesmedicas.cedula
								and ordenesmedicas.numservicio = servicios.numservicio
								and ordenesmedicas.cedula=terceros.identificacion
								and ordenesmedicas.cedula = tbl00030.cedula 
								$egreso";
								//echo $cons;
								$res=ExQuery($cons); 
								if(ExNumRows($res)>0){
						   
								?>
								<tr>
									<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
									<td class="encabezado2Horizontal">NOMBRE</td>
									<td class="encabezado2Horizontal">ASEGURADORA</td>
									<td class="encabezado2Horizontal">CONTRATO</td>
									<td class="encabezado2Horizontal">NO. CONTRATO</td>
									<td class="encabezado2Horizontal">PROCESO</td>
									<td class="encabezado2Horizontal">D&Iacute;AS ORDEN EGRESO</td>
									<td class="encabezado2Horizontal">D&Iacute;S DESDE ORDEN EGRESO</td>
									<td class="encabezado2Horizontal">ACTIVIDAD</td>
								</tr>
								<?	while($fila=ExFetch($res)){
									
										$BanFormatos=NULL;
										$BanEpic="2";
										if($Ambito){$Amb="and tiposervicio='$Ambito'";}
										$consS="Select numservicio,tiposervicio,ordensalida,egreso from salud.servicios 
										where compania='$Compania[0]' and numservicio=$fila[5] and cedula='$fila[4]' and estado='AC' $Amb";
										
										if($t == '0'){
											$yy= 1;
										}else if($t =='1'){
											$yy=2;
										}else if($t == '3'){
											$yy='4';
										}else{
											$yy= '3';
										}
										//echo $yy= ($fila[7] == 0?1:3);
										
										//echo $consS;
										$resS=ExQuery($consS);
										if(ExNumRows($resS)>0){
																					
											//echo $consS."<br>";
											$filaS=ExFetch($resS);
											$fila[6]=$filaS[1];
											$fila[7] = $filaS[3];
											$cons2="select tipoformato,formato from salud.formatosegreso where compania='$Compania[0]' and ambito='$fila[6]'";
											$res2=ExQuery($cons2);
											//echo $cons2;
											if(ExNumRows($res2)>0){
												while($fila2=ExFetch($res2))
												{ 
													$cons3="select cedula,fecha,numservicio,formato,tipoformato,id_historia from histoclinicafrms.".$Formatos[$fila2[0]][$fila2[1]]." 
													where compania='$Compania[0]' and tipoformato='$fila2[0]' and formato='$fila2[1]'	and cedula='$fila[4]' and numservicio=$fila[5]";	
													//echo $cons3;
													$res3=ExQuery($cons3);
													if(ExNumRows($res3)==0)
													{
														$BanFormatos="Falta";	
													}
												}
												if($BanFormatos=="Falta"){$BanEpic="2"; }else{$BanEpic="1";}
											}
											else
											{
												$cons2="select formato,tipoformato,tblformat from historiaclinica.formatos where compania='$Compania[0]' and epicrisis='Si'";
												$res2=ExQuery($cons2);
												while($fila2=ExFetch($res2))
												{
													$cons3="select cedula,fecha,numservicio,formato,tipoformato,id_historia from histoclinicafrms.".$fila2[2]." where compania='$Compania[0]' and formato='$fila2[0]' 
													and tipoformato='$fila2[1]'	and cedula='$fila[4]' and numservicio=$fila[5]";
													$res3=ExQuery($cons3);
													//if($fila[4]=="24707620"){echo $cons3."<br>";}
													if(ExNumRows($res3)>0){
														$BanEpic="1";
														$fila3=ExFetch($res3);
														$fecEpic=$fila3[1]; $NumServEpic=$fila3[2]; $FormatEpic=$fila3[3]; $TipoFormatEpic=$fila3[4]; $Id_HistoEpic=$fila[5];
													}
												}	
											}					
											if($fila[5])
											{
												$cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
												where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
												and pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
												$res2=ExQuery($cons2);	
												//echo $cons2;  		  	
												if(ExNumRows($res2)>0){
													$fila2=ExFetch($res2);
													$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
												}
												else{			
													$cons3="select primnom,segnom,primape,segape,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
													where terceros.compania='$Compania[0]' and pagadorxservicios.compania=terceros.compania and terceros.identificacion=pagadorxservicios.entidad and 
													pagadorxservicios.numservicio=$fila[5]	and '$FechaComp'>=fechaini order by fechaini desc";
													$res3=ExQuery($cons3);						
													if(ExNumRows($res3)>0){	//echo $cons3;			
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
											}
											if($fila[7]!= 0){					?>
											<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" 
											<?	if($BanEpic=="1"){?>
													onClick="location.href='NewEgreso.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[4]?>&Ambito=<? echo $Ambito?>&FecEpic=<? echo $fecEpic?>&NumServEpic=<? echo $NumServEpic?>&FormatEpic=<? echo $FormatEpic?>&TipoFormatEpic=<? echo $TipoFormatEpic?>&Id_HistoEpic=<? echo $Id_HistoEpic?>&par=<? echo $parametro2?>&yy=<? echo $yy ?>'" 
											<?	}
												else{?>
													onClick="alert('No se ha diligenciado los formatos necesarios para realizar el egreso!!!')"
											<?	}?>>
											
										<?	
										}else{
											?>
											<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" >
											<?php
										}
										if($Ambito){if($fila[6]){$Amb=" and tiposervicio='$fila[6]' ";}else{$Amb='Sin Ambito';}}else{$Amb="";}
											$FecOrd=explode(" ",$filaS[2]);
											$FecO=explode("-",$FecOrd[0]);
											$timestamp1 = mktime(0,0,0,$FecO[1],$FecO[2],$FecO[0]);
											$timestamp2 = mktime(0,0,0,$ND[mon],$ND[mday],$ND[year]);
											$segundos_diferencia = $timestamp1 - $timestamp2;
											$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
											$dias_diferencia = abs($dias_diferencia); 
											$dias_diferencia = floor($dias_diferencia); 
											echo "<td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td>";
											if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td>";}
											echo "<td>$fila[6]</td><td>$FecOrd[0]</td><td>$dias_diferencia</td>";
											if($fila[7] != 0){echo "<td> Iniciada </td>";}else{echo "<td><a href=\"#\" onClick=\"validar('$fila[4]', '$fila[0] $fila[1] $fila[2] $fila[3]');\"><b>Iniciar Egreso</b></a></td>"; }
											echo"</tr>";
										}
									}
								}
								else
								{?>
									<tr><td class="mensaje1">No hay pacientes con orden de egreso en esta unidad</td></tr>
						<?		}
							}?>
							</table> 
						  
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<input type="Hidden" name="con_egreso">
						<input type="Hidden" name="nom_egreso">
						</form>
				</div>	
			</body>
		</html>

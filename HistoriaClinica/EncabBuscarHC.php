		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			function listarAmbitos($compania){
				$consProc = "SELECT ambito FROM Salud.ambitos WHERE compania = '$compania' ORDER BY ambito ASC";
				$resProc = ExQuery($consProc);
				
				return  $resProc;	
			}
			
			/*function listarPabellones($ambito,$compania){
					if ($ambito != "false"){
						$condServ = " AND ambito = '$ambito'";
					} 
					else{
						$condServ = "";
					}
				$consServ = "SELECT pabellon FROM Salud.Pabellones WHERE compania = '$compania' $condServ ORDER BY pabellon ASC";
				$resServ = ExQuery($consServ);
				
				return  $resServ;	
			}*/
			
			function listarMdTratantes($compania){
				$consMdTrat = "select nombre,usuarios.usuario from salud.medicos,central.usuarios,salud.cargos  WHERE medicos.compania='$compania' and cargos.compania='$compania' and medicos.cargo=cargos.cargos and usuarios.usuario=medicos.usuario and cargos.tratante=1 and medicos.estadomed='Activo'  order by nombre";
				$resMdTrat = ExQuery($consMdTrat);
				
				return  $resMdTrat;	
			}
			
			function listarAseguradoras($compania){
				$consAseg = "SELECT primape,identificacion FROM central.terceros WHERE compania='$compania' AND tipo='Asegurador' ORDER BY primape ASC";
				$resAseg = ExQuery($consAseg);
				
				return  $resAseg;	
			}
			
			
		?>
	
	<html>			
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">	

			<script language='javascript' src="/Funciones.js"></script>
			<script language="javascript">	
				function enviarForm(){
						//var amb=document.getElementById("Ambito").options[document.getElementById("Ambito").selectedIndex];
						var ser=document.getElementById("Pabellon").options[document.getElementById("Pabellon").selectedIndex];
						var med=document.getElementById("Medicotte").options[document.getElementById("Medicotte").selectedIndex];
						var ent=document.getElementById("Enttidad").options[document.getElementById("Enttidad").selectedIndex];
						if(ser.value!="" || med.value!="" || ent.value!=""){
							document.getElementById("FORMA").submit();
						}
				}
					
					function cargarServicios(){
						var amb=document.getElementById("Ambito").options[document.getElementById("Ambito").selectedIndex];
						var serv=document.getElementById("Pabellon");
						if(amb.value!=""){
							//document.getElementById("FORMA").submit();
							window.location = "/HistoriaClinica/EncabBuscarHC.php?UnidadIraLista=&DatNameSID=<? echo $DatNameSID?>&ambi="+amb.value;
							serv.disabled=false;
						}
						else{
							serv.disabled=true;
						}
				}
			</script>	
		</head>
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "ABRIR HISTORIA CL&Iacute;NICA";
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			
			<div <?php echo $alignDiv3Mentor; ?> class="div3">
		
					<form id ="FORMA" method="post" target="Abajo" action="ResultBuscarHC.php" >
						<table class="tabla3" width="1000px"  style="text-align:center;"  <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
							<tr>
								<td class='encabezado1Horizontal'>IDENTIFICACI&Oacute;N</td>
								<td class='encabezado1Horizontal' colspan="2">APELLIDOS</td>
								<td class='encabezado1Horizontal' colspan="2">NOMBRES</td>								
								<td class='encabezado1Horizontal'> PROCESO</td>						
								<td class='encabezado1Horizontal'> SERVICIO </td>
								<td class='encabezado1Horizontal'>MED. TRATANTE</td>
								<td class='encabezado1Horizontal'>ASEGURADORA</td>
								<td class='encabezado1Horizontal'>&nbsp;</td> 
							</tr>
							<tr>
								<td><input type="Text" name="Cedula" style="width:90px;" onChange="enviarForm()" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
								<td><input type="Text" name="PrimApe" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
								<td><input type="Text" name="SegApe" style="width:90px;"  onkeydown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
								<td><input type="Text" name="PrimNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
								<td><input type="Text" name="SegNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
								
								
								<td style="width:90px;"> <?php $resAmb = listarAmbitos($Compania[0]); 
										
										echo "<select name='Ambito' id='Ambito' onChange='cargarServicios()' style='width:90px; font-family:Tahoma; font-size:11px; text-transform:uppercase;'>  ";
										echo "<option value=''> SELECCIONAR </option>";
											
											while($filaAmb = ExFetchArray($resAmb)){
																						if($filaAmb['ambito']==$ambi)
																							echo "<option value='$filaAmb[ambito]' selected>".$filaAmb['ambito']."</option>";
																						else
																							echo "<option value='$filaAmb[ambito]'>".$filaAmb['ambito']."</option>";
											}
										echo "</select>";	
									?>
								</td>
								<td style="width:90px;"><?php 
										/*if ($ambi){
											$resPab = listarPabellones($ambi,$Compania[0]);
										} else {
											$resPab = listarPabellones("false",$Compania[0]);
										}*/
																		
										
										//echo "<select name='Pabellon' onChange='enviarForm()' style='width:90px;'>";
														?> <select name='Pabellon' id="Pabellon" onChange='enviarForm()' style='width:90px; font-family:Tahoma; font-size:11px; text-transform:uppercase;' <?php if($ambi==''){ echo "disabled";} ?>>
																			<option value=''>SELECCIONAR </option>
																			<?php
																				if($ambi){
																					$consServ = "SELECT pabellon FROM Salud.Pabellones WHERE compania='$Compania[0]' and ambito='$ambi' ORDER BY pabellon ASC";
																					$resServ = ExQuery($consServ);
																					
																					while($filaPab = ExFetchArray($resServ)){
																							echo "<option value='".$filaPab['pabellon']."'>".$filaPab['pabellon']."</option>";
																					}
																				}
									?>
																			</select>
									<?php //echo $ambi." ".$consServ; ?>
								</td>    
								
								<td style="width:90px;"><?php 
										$resMdTrat = listarMdTratantes($Compania[0]); 							
										echo "<select name='Medicotte' id='Medicotte' onChange='enviarForm()' style='width:90px; font-family:Tahoma; font-size:11px; text-transform:uppercase;'>";
										echo "<option value=''> SELECCIONAR </option>";
											
											while($filaTrat = ExFetchArray($resMdTrat)){
												echo "<option value='$filaTrat[usuario]'>".$filaTrat['nombre']."</option>";
											}
										echo "</select>";	
									?>
									
								</td> 
								<td style="width:90px;">
									<?php 
										$resAseg = listarAseguradoras($Compania[0]); 							
										echo "<select name='Enttidad' id='Enttidad' onChange='enviarForm()' style='width:90px; font-family:Tahoma; font-size:11px; text-transform:uppercase;'>";
										echo "<option value=''> SELECCIONAR </option>";
											
											while($filaAseg = ExFetchArray($resAseg)){
												echo "<option value='$filaAseg[primape]'>".$filaAseg['primape']."</option>";
											}
										echo "</select>";	
									?>
								</td>
								
								
								<?
								$cons="select clinica from salud.clinicashc where compania='$Compania[0]' order by clinica";
								$res=ExQuery($cons);?>
								 <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
														 <input type="hidden" name="Buscar" value="Buscar">
								<td><input type="Submit" class="boton2Envio"  value="Buscar"></td>
							</tr>
						</table>
					</form>
			</div>	
		</body>
	</html>
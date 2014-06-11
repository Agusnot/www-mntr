		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
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
					$rutaarchivo[1] = "UTILIDADES";
					$rutaarchivo[2] = "ORDENES SIN REVISAR";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td  class="encabezado2Horizontal" colspan="15">ORDENES SIN REVISAR</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">PROCESO</td>
								<td>
								<?	if($Ambito==''){ echo "<input type='hidden' name='Ambito' value='1'>";$Ambito=1;}?>
								<select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
									<?	
										$cons="select ambito from salud.ambitos where compania='$Compania[0]'  and (hospitalizacion=1 or hospitaldia=1 or urgencias=1 ) order by ambito";	
										$res=ExQuery($cons);
										while($fila = ExFetch($res)){
											if($fila[0]==$Ambito){
												echo "<option value='$fila[0]' selected>$fila[0]</option>";
											}
											else{
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
										}?>
									</select></td>
							  
								<td class="encabezado2VerticalInvertido">SERVICIO</td>
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
												echo "<td style='font-weight:bold' align='center' colspan='7'>No se han asignado servicios a este Proceso</td>";
											}
										}			
									}?>
								</tr>        
							</table>
							<br>
							<?
							//echo "Ambito=$Ambito UnidadHosp=$UnidadHosp";
							if(($Ambito!=''&&$UnidadHosp!='')||$Ambito=='Consulta Externa'||$Ambito=='Sin Ambito')
							{?>
								<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>><?
								$cons="select vistobuenojefe,vistobuenoaux from salud.medicos,salud.cargos 
								where cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]' and usuario='$usuario[1]' and medicos.cargo=cargos.cargos";
								$res=ExQuery($cons);
								$fila=ExFetch($res);
								//echo $cons;
								if($Ambito!='Consulta Externa'&&$Ambito!='Sin Ambito'){
									$A=",salud.pacientesxpabellones"; $A2="and  pacientesxpabellones.compania='$Compania[0]'"; 
									$A3="and ordenesmedicas.cedula=pacientesxpabellones.cedula";
									$A4="and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and pacientesxpabellones.ambito='$Ambito'";
								}
								if($Ambito!='Sin Ambito')
								{
									$ServAC=" and servicios.estado='AC'";			
									$ServAmb="and servicios.tiposervicio='$Ambito'";
								}
								
								$ban=0;
								if($fila[0]==1){
									$ban=1;
									$cons2="select  primnom,segnom,primape,segape,identificacion,servicios.numservicio
									from central.terceros,salud.servicios,salud.ordenesmedicas $A
									where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and ordenesmedicas.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and 
									servicios.cedula=ordenesmedicas.cedula $A3 $ServAC $A2 and ordenesmedicas.jefeenfermeria is null   $A4 $ServAmb
									and tipoorden!='Suspencion'
									group by primnom,segnom,primape,segape,identificacion,servicios.numservicio order by primnom,segnom,primape,segape";
											
									$res2=ExQuery($cons2);
									//echo $cons2;
									if(ExNumRows($res2)){
										
										?>
										<tr>
											<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
											<td class="encabezado2Horizontal">NOMBRE</td>
											<td class="encabezado2Horizontal">PROFESIONAL QUE ORDEN&Oacute;</td>
											<td class="encabezado2Horizontal">FECHA</td>
										</tr>
									<?	while($fila2=ExFetch($res2)){
											$cons3="select idescritura,numorden,nombre,ordenesmedicas.fecha from salud.ordenesmedicas,salud.servicios,central.usuarios 
											where ordenesmedicas.cedula='$fila2[4]' and servicios.numservicio='$fila2[5]' and ordenesmedicas.cedula=servicios.cedula and ordenesmedicas.usuario=usuarios.usuario
											order by fecha desc";
											$res3=ExQuery($cons3);
											$fila3=ExFetch($res3);?>
											<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
											 onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
											
										<?	echo "<td>$fila2[4]</td><td>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila3[2]</td><td>$fila3[3]</td></tr>";?>
									<?	}
									}
									else
									{?>
										<tr>
											<td class="mensaje1">No hay pacientes con ordenes sin revisar</td></tr>
								<?	}
									?>
							<?	}
								if($fila[1]==1&&$ban==0){
									$ban=1;
									$cons2="select  primnom,segnom,primape,segape,identificacion,servicios.numservicio
									from central.terceros,salud.servicios,salud.ordenesmedicas $A
									where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and ordenesmedicas.compania='$Compania[0]' $A2
									and terceros.identificacion=servicios.cedula and servicios.cedula=ordenesmedicas.cedula $A3 $ServAC and 		 
									ordenesmedicas.revisadopor is null  $A4 $ServAmb
									group by primnom,segnom,primape,segape,identificacion,servicios.numservicio order by primnom,segnom,primape,segape";
											
									$res2=ExQuery($cons2);
									//echo $cons2;
									if(ExNumRows($res2)){
										
										?>
										<tr>
											<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
											<td class="encabezado2Horizontal">NOMBRE</td>
											<td class="encabezado2Horizontal">PROFESIONAL QUE ORDEN&Oacute;</td>
											<td class="encabezado2Horizontal">FECHA</td>
										</tr>
									<?	while($fila2=ExFetch($res2)){
											$cons3="select idescritura,numorden,nombre,ordenesmedicas.fecha from salud.ordenesmedicas,salud.servicios,central.usuarios 
											where ordenesmedicas.cedula='$fila2[4]' and servicios.numservicio='$fila2[5]' and ordenesmedicas.cedula=servicios.cedula and ordenesmedicas.usuario=usuarios.usuario
											order by fecha desc";
											$res3=ExQuery($cons3);
											$fila3=ExFetch($res3);				
									?>
											<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" 
											onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila2[4]?>&Buscar=1'">
											
										<?	echo "<td>$fila2[4]</td><td>$fila2[0] $fila2[1] $fila2[2] $fila2[3]</td><td>$fila3[2]</td><td>$fila3[3]</td></tr>";?>
									<?	}
									}
									else
									{?>
										<tr><td class="mensaje1">No hay pacientes con ordenes sin revisar</td></tr>
								<?	}
									
									if($ban==0){?>        
										<td class="mensaje1">No tiene permisos para ver ordenes</td>
							<?		}
								}			
							?>	
								</table><?
							}?>


							<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
							<input type="hidden" name="Regresa" value="">
							<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
		</html>

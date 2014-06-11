		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$cero1='0';}else{$cero1='';}
			if($ND[mday]<10){$cero2='0';}else{$cero2='';}
			$FechaComp="$ND[year]-$cero1$ND[mon]-$cero2$ND[mday]";	
			$cons="select identificacion, (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$Aseguradoras[$fila[0]]=$fila[1];
			}
		?>

		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "UTILIDADES";
						$rutaarchivo[2] = "DIETAS / CENSOS ALIMENTICIOS";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					<form name="FORMA" method="post">
						<div <?php echo $alignDiv2Mentor; ?> class="div2">	
							<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal" colspan="15">DIETAS / CENSOS ALIMENTICIOS</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">PROCESO</td>
								<td>
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
							  
								<td class="encabezado2VerticalInvertido">SERVICIO</td>
							   <? 	if($Ambito){$Amb=" and ambito='$Ambito' ";}
									$consult="Select * from Salud.Pabellones where Compania='$Compania[0]' $Amb";		
									$result=ExQuery($consult);?>        	           
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
									?>	</select></td>
									<td class="encabezado2VerticalInvertido">DIETA</td>
									<td>
										<?	$consD="select dieta from salud.dietas where compania='$Compania[0]' order by dieta";
										$resD=ExQuery($consD);?>
										<select name="Dieta" onChange="document.FORMA.submit();">
											<option></option>
											<?	while($filaD=ExFetch($resD)){
													if($filaD[0]==$Dieta){ echo "<option value='$filaD[0]' selected>$filaD[0]</option>";}
													else{ echo "<option value='$filaD[0]'>$filaD[0]</option>";}
												}	?>
										</select>
									</td>
									<td class="encabezado2VerticalInvertido">COMEDOR</td>
									<td>
										<?	$consD="select comedor from salud.comedores where compania='$Compania[0]' order by comedor";
											$resD=ExQuery($consD);?>
											<select name="Comedor" onChange="document.FORMA.submit();"><option></option>
												<?	while($filaD=ExFetch($resD)){
													if($filaD[0]==$Comedor){ echo "<option value='$filaD[0]' selected>$filaD[0]</option>";}
													else{ echo "<option value='$filaD[0]'>$filaD[0]</option>";}
												}	?>
											</select>
									</td>
									<td>
										<input type="submit" name="Ver" value="Ver" class="boton2Envio">
									</td>
								</tr>        
							</table>
							<br>
							<?
							if($Ver)
							{	
								//if($Ambito)	
								if($Ambito){$AmbCons=" and tiposervicio='$Ambito' ";}
								if($UnidadHosp){$UndH=" and pabellon='$UnidadHosp' ";}
								if($Dieta){$Diet="and dieta='$Dieta'";}	
								if($Comedor){$Comedo="and plantillacomedores.comedor='$Comedor'";}					
								$cons="SELECT primnom,segnom,primape,segape,identificacion,dieta,servicios.numservicio, plantillacomedores.comedor,pabellon,observacion, 
										consistenciadieta FROM salud.pacientesxpabellones 
										LEFT JOIN salud.plantillacomedores ON salud.plantillacomedores.cedula=salud.pacientesxpabellones.cedula 
										and plantillacomedores.compania='$Compania[0]' and plantillacomedores.estado='AC' 
										LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.pacientesxpabellones.cedula
										and plantilladietas.compania='$Compania[0]' and plantilladietas.estado='AC'  
										LEFT JOIN central.terceros ON terceros.identificacion=pacientesxpabellones.cedula 
										and terceros.compania='$Compania[0]' 
										LEFT JOIN salud.servicios ON servicios.cedula=pacientesxpabellones.cedula
										and servicios.compania='$Compania[0]' 
										WHERE servicios.estado='AC' and pacientesxpabellones.estado='AC' 
										$AmbCons $UndH $Diet $Comedo
										group by primnom,segnom,primape,segape,identificacion,dieta,servicios.numservicio, plantillacomedores.comedor,pabellon,observacion,consistenciadieta 
										order by pabellon";
								
								//echo $cons;
								$res=ExQuery($cons);?>
								
								
								<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
									<?	if(ExNumRows($res)>0){
											$cont=0;?>
									<tr>
										<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
										<td class="encabezado2Horizontal">NOMBRE</td>
										<td class="encabezado2Horizontal">SERVICIO</td>
										<!--<td class="encabezado2Horizontal">CONTRATO</td>-->
										<!--<td class="encabezado2Horizontal">NO. CONTRATO</td>-->
										<!--<td class="encabezado2Horizontal">DIETA</td>-->
										<td class="encabezado2Horizontal">DIETA</td>
										<td class="encabezado2Horizontal">COMEDOR</td>
									</tr>
								<?	while($fila=ExFetch($res)){
								
										$cons2="select primnom,segnom,primape,segape,contrato,nocontrato from central.terceros,salud.pagadorxservicios 
										where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad 
										and pagadorxservicios.numservicio=$fila[6]	and '$FechaComp'>=fechaini and '$FechaComp'<=fechafin";	
										$res2=ExQuery($cons2);	
										//echo $cons2;  		  	
										if(ExNumRows($res2)>0){
											$fila2=ExFetch($res2);
											$EPS="$fila2[0] $fila2[1] $fila2[2] $fila2[3]"; $Contra="$fila2[4]"; $NoContra="$fila2[5]";
										}
										else{			
											$cons3="select primnom,segnom,primape,segape,contrato,nocontrato,fechafin from central.terceros,salud.pagadorxservicios 
											where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and terceros.identificacion=pagadorxservicios.entidad and 
											pagadorxservicios.numservicio=$fila[6]	and '$FechaComp'>=fechaini";
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
										?>														
										<tr>
											<td style="text-align:left;padding-left:10px;"><? echo $fila[4]?></td>
											<td style="text-align:left;padding-left:10px;"><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"."&nbsp;"?></td>
											<td>
											<? /*if($EPS){*//*echo "$EPS</td><td>$Contra</td><td>$NoContra</td>";*/echo /*"$EPS</td>"*/"$fila[8]</td>";//}/*else{echo "<td colspan='3'> - Sin Asegurador Activo - ";}*/?><!--</td>-->
											<td><? echo $fila[5]." ".$fila[10]." ".$fila[9]."&nbsp;"?></td>
											<td><? echo $fila[7]."&nbsp;"?></td>
										</tr>
										<?	$cont++;
									}?>     
									<tr>
										<td colspan="4" class="filaTotales" style="text-align:right;padding-right:15px;"  >TOTAL</td>
										<td class="filaTotales" style="text-align:center;"><? echo $cont?></td>
									</tr>      
							<?	}
								else
								{?>
									<tr>
										<td class="mensaje1">No hay pacientes con dieta asignada en esta unidad</td>
									</tr>	
							<?	}?>
								</table><?
							}
							
							
							if($cont>0)	{
								$cons="select dieta from salud.dietas where compania='$Compania[0]'";
								$res=ExQuery($cons);
								if(!$Dieta){
								?>
								<table class="tabla1" width="250px" style="margin-top:25px;margin-bottom:25px;"    <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
									<tr>
										<td class="encabezado2Horizontal" colspan="2">RESUMEN</td>
									</tr>	
									<tr>
										<td class="encabezado2HorizontalInvertido">DIETA</td>
										<td class="encabezado2HorizontalInvertido">TOTAL PACIENTES</td></tr>
								<?	while($fila=ExFetch($res)){
								
									if($Ambito!="Consulta Externa"){
										if($Ambito=="1"){
											$cons2="select count(dieta) from
											central.terceros,salud.servicios,salud.plantilladietas 
											where servicios.estado='AC' and servicios.compania='$Compania[0]' and servicios.numservicio=plantilladietas.numservicio 			
											and plantilladietas.compania='$Compania[0]' and plantilladietas.cedula=servicios.cedula and plantilladietas.estado='AC' and terceros.compania='$Compania[0]'
											and terceros.identificacion=servicios.cedula and dieta='$fila[0]' and terceros.compania='$Compania[0]' $Diet group by dieta";
										}
										else{
											if($UnidadHosp){$UndH=" and pabellon='$UnidadHosp' ";}
											if($Ambito){$AmbCons=" and ambito='$Ambito' ";}
											if($Dieta){$Diet="and dieta='$fila[0]'";}	
											$cons2="select count(dieta) from
											central.terceros,salud.pacientesxpabellones,salud.servicios,salud.plantilladietas 
											where servicios.estado='AC' and pacientesxpabellones.estado='AC' 
											$UndH 
											$AmbCons 
											and pacientesxpabellones.compania='$Compania[0]' and 		
											servicios.compania='$Compania[0]' and servicios.numservicio=pacientesxpabellones.numservicio and servicios.cedula=pacientesxpabellones.cedula 
											and plantilladietas.compania='$Compania[0]' and plantilladietas.cedula=pacientesxpabellones.cedula and plantilladietas.estado='AC' 
											and terceros.identificacion=pacientesxpabellones.cedula 
											and dieta='$fila[0]' 
											--$Diet 
											and terceros.compania='$Compania[0]' group by dieta";
										}
									}
									else{
										$cons2="select count(dieta) from
										central.terceros,salud.servicios,salud.plantilladietas 
										where servicios.estado='AC' and tiposervicio='$Ambito' and servicios.compania='$Compania[0]' and servicios.numservicio=plantilladietas.numservicio 			
										and plantilladietas.compania='$Compania[0]' and plantilladietas.cedula=servicios.cedula and plantilladietas.estado='AC' and terceros.compania='$Compania[0]'
										and terceros.identificacion=servicios.cedula and dieta='$fila[0]' and terceros.compania='$Compania[0]' $Diet group by dieta";
									}
										//echo $cons2;
										$res2=ExQuery($cons2); 
										if(ExNumRows($res2)>0){
											$fila2=ExFetch($res2);
											echo "<tr><td>$fila[0]</td><td align='right'>$fila2[0]</td></tr>";
										}
									}?>
								</table><?
							}
							}
							?>
							<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
							<input type="hidden" name="Regresa" value="">
							<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form> 
				</div>	
			</body>
		</html>

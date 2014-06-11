		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($ND[mon]<10){$cero='0';}else{$cero='';}
			if($ND[mday]<10){$cero1='0';}else{$cero1='';}
			$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
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
					$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
					$rutaarchivo[2] = "CONTROL DE PERTENENCIAS";
					$rutaarchivo[3] = "CUSTODIA";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
				<div <?php echo $alignDiv1Mentor; ?> class="div1">	
					<form name="FORMA" method="post">
						<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>>	
							<tr>
								<td class='encabezado2Horizontal' colspan="15">CONTROL DE PERTENENCIAS</td>
							</tr>
							<tr>
								<td class='encabezado2HorizontalInvertido'>PROCESO</td>
								<td class='encabezado2HorizontalInvertido'>
									<?	if($Ambito==''){ echo "<input type='hidden' name='Ambito' value='1'>";$Ambito=1;}?>
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
										<td class='encabezado2HorizontalInvertido'><select name="UnidadHosp" onChange="document.FORMA.submit()">       	
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
													echo "<td class='mensaje1' colspan='7'>No se han asignado servicios a este proceso</td>";
												}
											}			
										}?>
							</tr>        
						</table>
					<br>
					<?
					if($Ambito!=''&&$UnidadHosp!='')
					{?>
					<table  class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingtabla1Mentor; ?>><?
						$cons="select primnom,segnom,primape,segape,identificacion,servicios.numservicio 
						from salud.pacientesxpabellones,salud.servicios,central.terceros 
						where pacientesxpabellones.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and tiposervicio='$Ambito' and servicios.estado='AC' 
						and pacientesxpabellones.cedula=servicios.cedula and servicios.cedula=terceros.identificacion and pabellon='$UnidadHosp' and ambito='$Ambito' 
						group by primnom,segnom,primape,segape,identificacion,servicios.numservicio order by primnom,segnom,primape,segape";
						//echo $cons;
						$res=ExQuery($cons);
						if(ExNumRows($res)>0)
						{?>
							<tr>
								<td class='encabezado2Horizontal'>CEDULA</td>
								<td class='encabezado2Horizontal'>NOMBRE</td>
								<td class='encabezado2Horizontal'>ASEGURADORA</td>
								<td class='encabezado2Horizontal'>CONTRATO</td>
								<td class='encabezado2Horizontal'>NO. CONTRATO</td>
							</tr>
							<?	while($fila=ExFetch($res)){
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
									?>
									<tr align="center" title="Ver Elementos En Custodia" style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
									onclick="location.href='ElementosCustodia.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[4]?>&Ambito=<? echo $Ambito?>&UndHosp=<? echo $UnidadHosp?>&NumServ=<? echo $fila[5]?>'">
									<td ><? echo $fila[4]?>
									</td><td  align="center"><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?></td>
								 <? if($EPS){echo "<td>$EPS</td><td>$Contra</td><td>$NoContra</td></tr>";}else{echo "<td colspan='3'> - Sin Asegurador Activo - </td></tr>";}
								
								}?>
					<?	}
						else
						{?>
							<tr>
								<td class="mensaje1" >No hay pacientes asignados a esta unidad</td>
							</tr>	
					<?	}
					}?>
					</table>
					<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
					<input type="hidden" name="Regresa" value="">
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>  
				</div>
		</body>
	</html>

		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Asignar){
				while(list($cad,$val) = each($Medico)){
					//echo "$cad $val<br>";
					if($val)
					{
						$Dat=explode("+",$cad);
						$cons="update salud.plantillainterprogramas set medico='$val' 
						where interprograma='$Interprog' and compania='$Compania[0]' and cedula='$Dat[0]' and estado='AC'";			
						$res=ExQuery($cons);
						//echo $cons."<br>";
						
						$cons="select especialidad from salud.medicos where compania='$Compania[0]' and usuario='$val'";
						$res=ExQuery($cons); $fila=ExFetch($res); $Especialidad=$fila[0];
						
						$cons="insert into salud.agendainterna (compania,numservicio,cedula,usuario,fechacrea,profecional,especialidad,fecproxima) values
			('$Compania[0]',$Dat[1],'$Dat[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$val','$Dat[2]','$ND[year]-$ND[mon]-$ND[mday]')";
						$res=ExQuery($cons);
						//echo $cons."<br>";
					}
				}
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
					$rutaarchivo[2] = "ASIGNAR INTERCONSULTA";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2" width="250px"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
							<tr>
								<td class="encabezado2Horizontal"  colspan="6" >INTERCONSULTAS</td>
							</tr>
							<tr>
								<td colspan="6" align="center">
									<select name="Interprog" onChange="document.FORMA.submit()"><option></option>
							<?		$cons="select interprograma,cargo from salud.interprogramas where compania='$Compania[0]' order by interprograma";
									$res=ExQuery($cons);
									//echo $cons;
									while($fila=ExFetch($res))
									{	
										$cargos[$fila[0]]=$fila[1];
										if($fila[0]==$Interprog){					
											echo "<option value='$fila[0]' selected>$fila[0]</option>";
										}
										else{
											echo "<option value='$fila[0]'>$fila[0]</option>";
										}
									}?>	
								</select></td>
							</tr>
							
						<?	if($Interprog){
								$cons="select salud.plantillainterprogramas.cedula,primape,segape,primnom,segnom,salud.servicios.numservicio,salud.plantillainterprogramas.usuario,salud.plantillainterprogramas.fechaini from salud.plantillainterprogramas,central.terceros,salud.servicios 
								where plantillainterprogramas.compania='$Compania[0]' and terceros.compania='$Compania[0]' and salud.plantillainterprogramas.cedula=identificacion and interprograma='$Interprog' 
								and medico is null and plantillainterprogramas.estado='AC' and servicios.compania='$Compania[0]' 
								and  plantillainterprogramas.numservicio=servicios.numservicio
								order by primape,segape,primnom,segnom";
								$res=ExQuery($cons);
								//echo $cons;
								if(ExNumRows($res)>0){?>    
									<tr>
										<td colspan="6" style="text-align:center;" >
											<input type="submit" class="boton2Envio" name="Asignar" value="Asignar">
										</td>
									</tr>
									<tr>
										<td class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N</td>
										<td class='encabezado2HorizontalInvertido'>NOMBRE</td>
										<td class='encabezado2HorizontalInvertido'>QUIEN REMITE</td>
										<td class='encabezado2HorizontalInvertido'>FECHA REMISI&Oacute;N</td>
										<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
										<td>PROFESIONAL</td>
									</tr>	
						<?			while($fila=ExFetch($res))
									{ 
										$cons3="select ambito,pabellon from salud.pacientesxpabellones where compania='$Compania[0]' and cedula='$fila[0]' and estado='AC' and numservicio=$fila[5]";
										$res3=ExQuery($cons3);
										$fila3=Exfetch($res3);
										$consqr="select nombre from central.usuarios where usuario='$fila[6]'";
										$resqr=ExQuery($consqr);
										$filaqr=Exfetch($resqr);
										?>
										<tr align='center' onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Abrir Historia"><?           
										echo '<td><a href="'."ResultBuscarHC.php?DatNameSID=$DatNameSID&Cedula=$fila[0]&Buscar=1".'"target="_self">'."$fila[0]</a></td><td> $fila[1] $fila[2] $fila[3] $fila[4]</td><td>$filaqr[0]</td><td>$fila[7]</td><td>$fila3[1]</td>";
										$cons2="select nombre,cargo,medicos.usuario from salud.medicos,central.usuarios where cargo='$cargos[$Interprog]' and medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]' order by nombre";
										//echo $cons2;
										$res2=ExQuery($cons2);?>
										<td><select name="Medico[<? echo $fila[0]."+".$fila[5]."+".$Interprog?>]" id="Medico_<? echo $fila[0]?>"><option></option>
									<?	while($fila2=ExFetch($res2)){?>
											<option value="<? echo $fila2[2]?>"><? echo strtoupper($fila2[0])?></option>
									<?	}?>
										</select></td>
									<!--   <td><img title="Asignar" style="cursor:hand" src="/Imgs/b_check.png" onClick="location.href='AsigInterprograma.php?DatNameSID=<? echo $DatNameSID?>&Interprog=<? echo $Interprog?>&Ced=<? echo $fila[0]?>&NumServAct=<? echo $fila[5]?>&Asignar=1&Med='+document.getElementById('Medico_<? echo $fila[0]?>').value"></td>-->
									</tr>           
						<?			}?>
									 <tr>
										<td colspan="5"d align="center">
											<input type="submit" name="Asignar" value="Asignar">
										</td>
									</tr>
							<?	}
								else{
									echo "<tr>";
										echo "<td class='mensaje1' colspan='4' >No hay pacientes para asignar</td>";
									echo "</tr>";
								}
							}?>
						</table>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
				</form> 
			</div>		
		</body>
	</html>

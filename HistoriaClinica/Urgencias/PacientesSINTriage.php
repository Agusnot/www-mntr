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
					<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
					<script language='javascript' src="/calendario/popcalendar.js"></script>
					<script language='javascript' src="/Funciones.js"></script>
				</head>
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "URGENCIAS";
					$rutaarchivo[2] = "PACIENTES SIN TRIAGE";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">

			
					<form name="FORMA" id="FORMA" method="post" action="/HistoriaClinica/Urgencias/PacientesSINTriage.php?DatNameSID=<?php echo $DatNameSID; ?>">
						<table width="85%" style="text-align:center;margin-top:25px;margin-bottom:25px;" class="tabla2" style="margin-top:25px;margin-bottom:25px;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>

							<tr>
								<td class='encabezado2Horizontal' colspan="6">PACIENTES EN SALA DE ESPERA</td>        
							</tr>
							
							<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
								<td colspan="5">MOSTRAR POR FECHA: <input type="Text" name="fechai" readonly onClick="popUpCalendar(this, FORMA.fechai, 'yyyy-mm-dd');" value="<?php echo $fechai; ?>"> <input type="submit" class="boton2Envio" value="Buscar"></td>
							</tr>
							
							<tr>
								<td class='encabezado2HorizontalInvertido'>NOMBRE DEL PACIENTE</td>
								<td class='encabezado2HorizontalInvertido'>FECHA DE INGRESO</td>
								<td class='encabezado2HorizontalInvertido'>USUARIO QUE ENVÍA</td>
								<td class='encabezado2HorizontalInvertido'>REQUISAR</td>
								<td class='encabezado2HorizontalInvertido'>ATENCIÓN</td>
							</tr>
							
							<?php
								if($fechai!=''){
									$fecha = date('Y-m-d',strtotime($fechai));
								   $cons0="select cedula,fecha,usuario,requisa,atender from salud.salasintriage where date_trunc('day',salasintriage.fecha)='".$fecha."' order by autoid asc";
								}
								else
							$cons0="select cedula,fecha,usuario,requisa,atender from salud.salasintriage where salasintriage.fecha>current_date or estado=1 order by autoid asc";
							
							
							$res0=ExQuery($cons0);
							while($fila0=ExFetch($res0)){
						?>
							
						<tr>
								<td>
									<?php
									$cons1="select primnom,segnom,primape,segape from central.terceros where identificacion='$fila0[0]'";
									$res1=ExQuery($cons1);
									$fila1=ExFetch($res1);
									echo" $fila1[2] $fila1[3]
									$fila1[0] $fila1[1]";
									?>
								</td>
								<td>
									<?php
									echo"$fila0[1]";
							?>
								</td>
								<td>
									<?php
									$cons2="select nombre from central.usuarios where usuario='$fila0[2]'";
									$res2=ExQuery($cons2);
									$fila2=ExFetch($res2);
									echo"$fila2[0]";
									?>
								</td>
								<td>
									<?php
									if($fila0[3]==1){
										echo"Requisado";
									}
									else{
										$cons3="select cargo from salud.medicos where usuario='$usuario[1]'";
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
										if(($fila3[0]=="AUXILIAR DE ENFERMERIA")||($fila3[0]=="JEFE DE ENFERMERIA")){
							?>
								<a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Requisar</a>
							<?php
										}
										else{
											echo "Requisar";
										}
							}
							?>
								</td>
								<td>
									<?php
							if($fila0[4]==1){
										echo"Atendido";
									}
									else{
									   $cons3="select cargo from salud.medicos where usuario='$usuario[1]'";
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
										
									   $cons4="select extra from salud.extraordinario order by fecha desc limit 1";
										$res4=ExQuery($cons4);
										$fila4=ExFetch($res4);
										if($fila4[0]==0){
											
											if((($fila3[0]=="MEDICO GENERAL")||($fila3[0]=="MEDICO GENERAL DE URGENCIAS"))&&($fila0[3]==1)){
													?>
												<a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Atender</a>
												<?php
											} else{
												echo "Atender";
											}
										}
										if($fila4[0]==1){ 
											if((($fila3[0]=="MEDICO GENERAL")||($fila3[0]=="MEDICO GENERAL DE URGENCIAS")||($fila3[0]=="INTERNO")||($fila3[0]=="RESIDENTE")||($fila3[0]=="PSIQUIATRA"))&&($fila0[3]==1)){
							?>
												<a href="/HistoriaClinica/HistoriaClinica.php?DatNameSID=<?php echo $DatNameSID ?>&Pacie=<?php echo $fila0[0] ?>" target="_parent">Atender</a>
							<?php
											}
											else{
												echo "Atender";
											}
										}
									}
							?>
								</td>	
							</tr>
						<?php
								}
						?>
					</table>
				</form>
			</div>	
		</body>
	</html>
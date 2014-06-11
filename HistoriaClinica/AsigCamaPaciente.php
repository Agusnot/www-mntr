		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			//echo "UnidadHosp=$UnidadHosp Ambito=$Ambito Id=$Idcama";
			if($Cedula!=''){
				$cons="update salud.pacientesxpabellones set idcama='$Idcama' where pabellon='$UnidadHosp'  and cedula='$Cedula' and ambito='$Ambito' and idcama=0 and estado='AC' and compania='$Compania[0]'";
				//echo $cons;
				$res=ExQuery($cons);echo ExError();?>
				<script language="javascript">
					location.href='AsigCamas.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>&Regresa=1';
				</script>
		<?	}
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
				$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
				$rutaarchivo[2] = "ASIGNAR CAMAS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">				
						<table class="tabla2" width="500px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<? 	$cons="select pacientesxpabellones.cedula,primnom,segnom,primape,segape,ingreso from central.terceros,salud.pacientesxpabellones,salud.servicios
							where identificacion=pacientesxpabellones.cedula and pabellon='$UnidadHosp' and ambito='$Ambito' and idcama=0 and pacientesxpabellones.estado='AC' 
							and servicios.numservicio=pacientesxpabellones.numservicio and servicios.estado='AC'
							and terceros.compania='$Compania[0]' and pacientesxpabellones.compania='$Compania[0]' and servicios.compania='$Compania[0]' 
							order by primape,segape,primnom,segnom";
							//echo $cons;
							$res=ExQuery($cons);echo ExError();
							if(ExNumRows($res)){?>
							<tr title='Asignar'>
								<td class='encabezado2Horizontal'>IDENTIFICACI&Oacute;N</td>
								<td class='encabezado2Horizontal'>NOMBRE</td>
							</tr>    
						<?		while($row=ExFetch($res)){?>
									<tr title='Asignar' style='cursor:hand'  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" 
									<?	if($row[5]!=1){?>
											onClick="alert('No se puede asignar cama a este paciente debido a que no ha sido registrado su ingreso!!!')"
									<?	}
										else{?>
											onClick="location.href='AsigCamaPaciente.php?DatNameSID=<? echo $DatNameSID?>&Idcama=<? echo $Idcama?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>&Cedula=<? echo $row[0]?>'"
									<?	}?>
									>
										<td><? echo $row[0]?></td><td><? echo "$row[3] $row[4] $row[1] $row[2]";?></td>
									</tr>	
							<?	}
							}
							else{?>
							<tr>
								<td class="mensaje1">No hay pacientes para asignar en esta unidad</td>
							</tr>
						<?	}?>
							<tr>
								<td colspan="2" align="center">
									<input type="button" class="boton2Envio" value="Cancelar" onClick="location.href='AsigCamas.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&UnidadHosp=<? echo $UnidadHosp?>&Regresa=1'">
								</td>
							</tr>
						</table>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
		</html>

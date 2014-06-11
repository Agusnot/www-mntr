		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){
				$cons="update historiaclinica.interpretacionlabs set interpreta='$Interpreta' where compania='$Compania[0]'";
				$res=ExQuery($cons);
			}
			$cons="select interpreta from historiaclinica.interpretacionlabs where compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			if(!$fila[0]){
				$cons="insert into historiaclinica.interpretacionlabs (compania,interpreta) values ('$Compania[0]','No')";
				$res=ExQuery($cons);
				$fila[0]="No";
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
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "INTERPRETACI&Oacute;N LABORATORIOS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv1Mentor; ?> class="div1">	
					<form name="FORMA" method="post">
						<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>    	
							<tr>
								<td class="encabezado2Horizontal"> PERMITIR INTERPRETACI&Oacute;N DE LABORATORIOS</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<select name="Interpreta">
										<option value="Si" <? if($fila[0]=="Si"){?> selected="selected"<? }?>>Si</option>
										<option value="No" <? if($fila[0]=="No"){?> selected="selected"<? }?>>No</option>
									</select>
								</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<input type="submit" class="boton2Envio" value="Guardar" name="Guardar"/>
								</td>
							</tr>
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>  
				</div>	
			</body>
		</html>

		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Guardar)
			{
				$cons="insert into pacienteseguro.comite (compania,usucomite) values ('$Compania[0]','$UsuComite')";
				$res=ExQuery($cons);
			?>	<script language="javascript">
					location.href='ConfComite.php?DatNameSID=<? echo $DatNameSID?>';
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
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "CONFIGURACI&Oacute;N PACIENTE SEGURO";
					$rutaarchivo[3] = "COMIT&Eacute;";					
					$rutaarchivo[4] = "NUEVO";	
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post" onSubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								<tr>
									<td class="encabezado2Horizontal" >NUEVO USUARIO DE COMIT&Eacute; </td>
								</tr>
							<?	$cons="select nombre,usuario from central.usuarios where usuario not in (select usucomite from pacienteseguro.comite where compania='$Compania[0]')
								order by nombre";
								$res=ExQuery($cons);?>    
								<tr>
									<td style="text-align:center;">
										<select name="UsuComite">
											<option></option>
										<?	while($fila=ExFetch($res))
											{
												echo "<option value='$fila[1]'>".strtoupper($fila[0])."</option>";
											}?>
										</select>
									<td>
								</tr>
								<tr>
									<td style="text-align:center;">
										<input type="submit" class="boton2Envio" name="Guardar" value="Guardar"/>
										<input type="button" class="boton2Envio" value="Cancelar" onclick="location.href='ConfComite.php?DatNameSID=<? echo $DatNameSID?>'"/>
									</td>
								</tr>
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
					</form> 
				</div>	
			</body>
		</html>
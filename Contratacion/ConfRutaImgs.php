		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){		
				$cons="delete from salud.rutaimgsproced where compania='$Compania[0]'";
				$res=ExQuery($cons);
				$Aux=str_replace('\\','/',$Ruta);
				$cons="insert into salud.rutaimgsproced (compania,ruta) values ('$Compania[0]','$Aux')";
				$res=ExQuery($cons);
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
					function Validar()
					{
						if(document.FORMA.Ruta.value=="")
						{
							alert("Debe digitar un ruta!!!");return false;
						}
					}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "RUTA IMAGENES";					
									
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" enctype="multipart/form-data" onSubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
								<tr>
									<td class="encabezado2Horizontal"> RUTA IMAGENES PROCEDIMIENTOS</td></tr>
							<?
								$cons="select ruta from salud.rutaimgsproced where compania='$Compania[0]'";
								$res=ExQuery($cons); $fila=ExFetch($res);
							?>    
								<tr>
									<td style="text-align:center;">
										<input type="text" name="Ruta" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" style="width:500" value="<? echo $fila[0]?>">
									</td>
								</tr>
								<tr>
									<td style="text-align:center;">
										<input type="submit" class="boton2Envio" value="Guardar" name="Guardar">
									</td>
								</tr>
						</table>
					<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
		</html>

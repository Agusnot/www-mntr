		<?php	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){		
				$cons="Delete from historiaclinica.raza where compania='$Compania[0]' and nombre='$raza' and id=$Id";
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
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
					$rutaarchivo[2] = "RAZAS";					
									
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 	
							<tr>
								<td colspan="5" style="text-align:center;">
									<input type="button" class="boton2Envio" value="Nuevo" onClick="location.href='NewConfRaza.php?DatNameSID=<? echo $DatNameSID?>'"/>
								</td>
							</tr>
							<?php	$cons="select nombre,id from historiaclinica.raza where compania='$Compania[0]' order by id";
								$res=ExQuery($cons);
								if(ExNumRows($res)>0){?>
									<tr>
										<td class="encabezado2Horizontal">RAZA</td>
										<td class="encabezado2Horizontal" colspan="2">&nbsp;</td>
									</tr>
										<?php
										while($fila=ExFetch($res)){
										echo "<tr><td>$fila[0]</td><td>";?>
										<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfRaza.php?DatNameSID=<? echo $DatNameSID?>&raza=<? echo $fila[0]?>&Edit=1&Id=<? echo $fila[1]?>'"></td><td>
										<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfRaza.php?DatNameSID=<? echo $DatNameSID?>&raza=<? echo $fila[0]?>&Eliminar=1&Id=<? echo $fila[1]?>';}" src="/Imgs/b_drop.png"></td>
										</tr><?php
									}
								}
								else{?>
									<tr>
										<td class="mensaje1">A&uacute;n no se han ingresado razas</td>            
									</tr>
							<?php	}?>
								
						</table>
						<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
		</html>


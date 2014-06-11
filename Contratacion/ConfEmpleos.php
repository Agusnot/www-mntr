		<?	if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){		
				$cons="Delete from historiaclinica.empleosOIT where compania='$Compania[0]' and empleo='$empleo' and id=$Id";
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
					$rutaarchivo[2] = "OCUPACIONES";					
						
									
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 	
								<tr>
									<td colspan="5" style="text-align:center;">
										<input type="button" class="boton2Envio" value="Nuevo" onClick="location.href='NewConfEmpleos.php?DatNameSID=<? echo $DatNameSID?>'"/>
									</td>
								</tr>
							<? 	$cons="select empleo,id from historiaclinica.empleosOIT where compania='$Compania[0]' order by empleo";	
								$res=ExQuery($cons);
								if(ExNumRows($res)>0){?>
									<tr>
										<td class="encabezado2Horizontal">OCUPACI&Oacute;N</td>
										<td colspan="2" class="encabezado2Horizontal"></td>
									</tr>
							<?		while($fila=ExFetch($res)){
										echo "<tr><td>$fila[0]</td><td>";?>
										<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewConfEmpleos.php?DatNameSID=<? echo $DatNameSID?>&empleo=<? echo $fila[0]?>&Edit=1&Id=<? echo $fila[1]?>'"></td><td>
										<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='ConfEmpleos.php?DatNameSID=<? echo $DatNameSID?>&empleo=<? echo $fila[0]?>&Eliminar=1&Id=<? echo $fila[1]?>';}" src="/Imgs/b_drop.png"></td>
										</tr><?
									}
								}
								else{?>
									<tr>
										<td colspan="5" class="mensaje1">A&uacute;n no se han ingresado ocupaciones</td>            
									</tr>
							<?	}?>
								
						</table>
						<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
		</html>


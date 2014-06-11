		<?php
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
			if($Eliminar){
				$cons="delete from salud.elementos where compania='$Compania[0]' and elemento='$Elemento'";
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
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
					$rutaarchivo[1] = "HOSPITALIZACI&Oacute;N";
					$rutaarchivo[2] = "CONTROL DE PERTENENCIAS";
					$rutaarchivo[3] = "ELEMENTOS";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<div <?php echo $alignDiv1Mentor; ?> class="div1">
					<form name="FORMA" method="post">
						<table class="tabla1" width="250px" style="margin-top:25px;margin-bottom:25px;text-align:center;"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>	
							<tr>
								<td class='encabezado2Horizontal'>ELEMENTOS</td>
								<td class='encabezado2Horizontal' colspan="2"></td>
							</tr>
						<? 	$cons="select elemento from salud.elementos where compania='$Compania[0]'";
							//echo $cons;
							$res=ExQuery($cons);
							if(ExNumRows($res)>0)
							{
								while($fila=ExFetch($res)){?>
									<tr><td><? echo strtoupper($fila[0])?></td><td>
									<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewElemento.php?DatNameSID=<? echo $DatNameSID?>&Elemento=<? echo $fila[0]?>&Edit=1'"></td><td>
									<img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='Elementos.php?DatNameSID=<? echo $DatNameSID?>&Elemento=<? echo $fila[0]?>&Eliminar=1';}" src="/Imgs/b_drop.png"></td>
									</tr>	
						<?		}
							}
							else
							{?>
								<tr><td class="mensaje1">No se han ingresado elementos</td></tr>
						<?	}?>    
							<tr>
								<td colspan="4" align="center">
									<input type="button" class="boton2Envio" value="Nuevo" onClick="location.href='NewElemento.php?DatNameSID=<? echo $DatNameSID?>'">
								</td>
							</tr>
						</table>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>  
				</div>	
			</body>
		</html>

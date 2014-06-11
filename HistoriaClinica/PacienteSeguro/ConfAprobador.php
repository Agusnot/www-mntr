		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Eliminar){
				$cons="delete from pacienteseguro.aprobador where compania='$Compania[0]' and aprobador='$Eliminar'";	
				$res=ExQuery($cons);
			}
			
			$cons="select nombre,usuario from pacienteseguro.aprobador,central.usuarios where compania='$Compania[0]' and usuario=aprobador order by nombre";
			$res=ExQuery($cons);
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
				$rutaarchivo[3] = "APROBADOR";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">	
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="2" style="text-align:center;">
								<input type="button" class="boton2Envio" value="Nuevo" onclick="location.href='NewAprobador.php?DatNameSID=<? echo $DatNameSID?>'"/>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="encabezado2Horizontal">CONFIGURACI&Oacute;N APROBADOR</td>
						</tr>
						
						
								<?	while($fila=ExFetch($res)){?>
									<tr  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
										<td><? echo $fila[0]?></td>
										<td><img src="/Imgs/b_drop.png" title="Eliminar" onclick="if(confirm('¿Esta seguro de eliminar este registro?')){location.href='ConfAprobador.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=<? echo $fila[1]?>';}"/></td>
									</tr>
								<?	
								}?>
						
					</table>
					<input type="hidden" name="Eliminar" value="" />
				</form>
			</div>	
		</body>
	</html>
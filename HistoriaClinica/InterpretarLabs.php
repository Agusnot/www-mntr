		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			//' and usuario='$usuario[1]' and rutaimg is not null
			$cons="select plantillaprocedimientos.cedula,primape,segape,primnom,segnom,tiposervicio,usuario,FechaIni from salud.plantillaprocedimientos,central.terceros,salud.servicios
			where plantillaprocedimientos.compania='$Compania[0]'  and (interpretacion='' or interpretacion is null) 
			
			and terceros.compania='$Compania[0]' and identificacion=plantillaprocedimientos.cedula and servicios.cedula=plantillaprocedimientos.cedula
			and servicios.numservicio=plantillaprocedimientos.numservicio order by primape,segape,primnom,segnom";
			$res=ExQuery($cons);	
			//echo $cons;
			
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
					$rutaarchivo[1] = "INTERPRETAR LABORATORIOS";										
					
					mostrarRutaNavegacionEstatica($rutaarchivo);					
				
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post">
						<table class="tabla2" style="margin-top:25px;margin-bottom:25px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>   
							
							<tr>  
								<td colspan="9" class="encabezado2Horizontal">PACIENTES CON LABORATORIOS POR INTERPRETAR</td>    
							</tr> 
							<tr>  
								<td class="encabezado2VerticalInvertido">NOMBRE</td>
								<td class="encabezado2VerticalInvertido">IDENTIFICACI&Oacute;N</td>
								<td class="encabezado2VerticalInvertido">PROCESO</td>
								<td class="encabezado2VerticalInvertido">SOLICITADO POR</td>
								<td class="encabezado2VerticalInvertido">FECHA</td>
							</tr>
							<?
							while($fila=ExFetch($res))
							{
								$cons2="Select Nombre from Central.usuarios where Usuario='$fila[6]'";
								$res2=ExQuery($cons2);
								$fila2=ExFetch($res2);
								?>
								<tr  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Abrir Historia" style="cursor:hand"
								onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[0]?>&Buscar=1'">
									<td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]";?></td><td><? echo $fila[0]?></td><td><? echo $fila[5]?></td><td><? echo $fila2[0]?></td><td><? echo $fila[7]?></td>
								</tr><?	
							}
							?>
						</table>
					</form>
				</div>	
			</body>
		</html>

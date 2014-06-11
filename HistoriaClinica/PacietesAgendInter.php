		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			
			$cons="select agendainterna.cedula,primape,segape,primnom,segnom,fecultima,fecproxima,servicios.numservicio 
			from salud.agendainterna,salud.servicios,central.terceros 
			where agendainterna.compania='$Compania[0]' and profecional='$usuario[1]' and terceros.compania='$Compania[0]' and servicios.cedula=identificacion
			and fecproxima<='$ND[year]-$ND[mon]-$ND[mday]' and estado='AC' and agendainterna.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'
			order by primape,segape,primnom,segnom";
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
				<script language='javascript' src="/calendario/popcalendar.js"></script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
					<?php
						$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
						$rutaarchivo[1] = "UTILIDADES";
						$rutaarchivo[2] = "AGENDA INTERNA";
						mostrarRutaNavegacionEstatica($rutaarchivo);
					?>
					<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<form name="FORMA" method="post">
							<table class="tabla2" width="750px"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>   
							<tr> 
								<td class="encabezado2Horizontal" colspan="11">AGENDA INTERNA</td>
							</tr>
							<tr> 
								<td class='encabezado2HorizontalInvertido'>IDENTIFICACI&Oacute;N</td>
								<td class='encabezado2HorizontalInvertido'>NOMBRE</td>
								<td class='encabezado2HorizontalInvertido'>ENTIDAD</td>
								<td class='encabezado2HorizontalInvertido'>&Uacute;LTIMA</td>
								<td class='encabezado2HorizontalInvertido'>PR&Oacute;XIMA</td>
							<tr>
							<?
							while($fila=ExFetch($res))
							{
								$cons2="select primape,segape,primnom,segnom from central.terceros,salud.pagadorxservicios where terceros.compania='$Compania[0]' and 
								pagadorxservicios.compania='$Compania[0]' and identificacion=entidad and numservicio=$fila[7] and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' 
								and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null)";
								$res2=ExQuery($cons2); $fila2=ExFetch($res2);?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Abrir Historia" style="cursor:hand"
								onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $fila[0]?>&Buscar=1'">
									<td><? echo $fila[0]?></td><td><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></td><td><? echo "$fila2[0] $fila2[1] $fila2[2] $fila2[3]"?>&nbsp;</td>
									<td><? echo $fila[5]?>&nbsp;</td><td><? echo $fila[6]?></td>
								</tr><?
							}
							?>
							</table>
						</form>
					</div>	
			</body>
		</html>
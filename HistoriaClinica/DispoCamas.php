		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
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
					$rutaarchivo[2] = "DISPONIBILIDAD DE CAMAS";				
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
			?>	
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>> 
					<?
					$cons="select ambito from salud.ambitos where compania='$Compania[0]' and hospitalizacion=1 and ambito!='Sin Ambito' 
					and hospitaldia!=1 order by ambito ";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						$cons2="select pabellon,nocamas from salud.pabellones where compania='$Compania[0]' and ambito='$fila[0]' order by pabellon";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)>0)
						{?>
							<tr>
								<td class='encabezado2Horizontal' colspan="4"><? echo strtoupper($fila[0])?></td>
							</tr>		
							<tr>
								<td class='encabezado2HorizontalInvertido'>SERVICIO</td>
								<td class='encabezado2HorizontalInvertido'>NO. CAMAS</td>
								<td class='encabezado2HorizontalInvertido'>CAMAS OCUPADAS</td>
								<td class='encabezado2HorizontalInvertido'>CAMAS DISPONIBLES</td>
							</tr>
					<?		while($fila2=ExFetch($res2))
							{
								$cons3="select pacientesxpabellones.cedula from salud.pacientesxpabellones,salud.servicios where pacientesxpabellones.compania='$Compania[0]' 
								and pacientesxpabellones.estado='AC' and fechae is null and lugtraslado is null and servicios.compania='$Compania[0]' and servicios.estado='AC'
								and pabellon='$fila2[0]' and ambito='$fila[0]' and pacientesxpabellones.numservicio=servicios.numservicio and fechaegr is null";
								$res3=ExQuery($cons3);
								$CamasOcupadas=ExNumRows($res3); if($CamasOcupadas<=0){$CamasOcupadas="0";}
								$CamasDispo=$fila2[1]-$CamasOcupadas; //if($CamasDispo<=0){$CamasDispo="0";}
								$ban=1;
								echo "<tr>";
									echo "<td>$fila2[0]</td>";
									echo "<td style='text-align:center;'>$fila2[1]</td>
									<td style='text-align:center;'>$CamasOcupadas</td>
									<td style='text-align:center;'>$CamasDispo</td>";
								echo "</tr>";
								$TotCam=$TotCam+$fila2[1]; $TotOcups=$TotOcups+$CamasOcupadas; $TotDispo=$TotDispo+$CamasDispo;
							}
						}
					}
					if($ban)
					{
						if($TotOcups<=0){$TotOcups="0";} if($TotCam<=0){$TotCam="0";} if($TotDispo<=0){$TotDispo="0";}
						echo "<tr>";
							echo "<td class='filaTotales' style='font-weight:bold; text-align:center;'>TOTALES</td>";
							echo "<td class='filaTotales' style='font-weight:bold; text-align:center;'>$TotCam</td>";
							echo "<td class='filaTotales' style='font-weight:bold; text-align:center;'>$TotOcups</td>";
							echo "<td class='filaTotales' style='font-weight:bold; text-align:center;'>$TotDispo</td>";
						echo "</tr>";
					}
					?>
					</table>
				</form>
			</div>
		</body>
	</html>
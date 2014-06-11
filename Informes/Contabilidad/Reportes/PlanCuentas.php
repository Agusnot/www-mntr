	<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Informes.php");$ND=getdate();
		include_once("General/Configuracion/Configuracion.php");
	?>
	
	<html>
				<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
					
				</head>	
				<body <?php echo $backgroundBodyInfContableMentor;?>>
					<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
	
						<table class="tablaInformeContable"   <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
								<tr>
									<td class="encabezadoInformeContable" colspan="8"><?echo strtoupper($Compania[0])?>
										<br>
										<?echo $Compania[1]?><br>PLAN DE CUENTAS<br>VIGENCIA <?echo $Anio?><br>
										FECHA DE IMPRESI&Oacute;N<?echo "$ND[year]-$ND[mon]-$ND[mday]"?>
									</td> 
								</tr>
							<tr>
								<td class='encabezado2HorizontalInfCont'>CUENTA </td>
								<td class='encabezado2HorizontalInfCont'>NOMBRE</td>
								<td class='encabezado2HorizontalInfCont'>NATURALEZA</td>
								<td class='encabezado2HorizontalInfCont'>TIPO</td>
								<td class='encabezado2HorizontalInfCont'>CC</td>
								<td class='encabezado2HorizontalInfCont'>BANCO</td>
								<td class='encabezado2HorizontalInfCont'>TERCERO</td>
								<td class='encabezado2HorizontalInfCont'>PRESUP.</td>
							</tr>
						<?
							$cons="Select Cuenta,Nombre,Naturaleza,Tipo,centrocostos,Banco,Tercero,afectacionpresup from Contabilidad.PlanCuentas where Anio=$Anio and Compania='$Compania[0]' Order By Cuenta";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="white";$Fondo=1;}
								echo "<tr bgcolor='$BG'><td style='text-align:justify;'>$fila[0]</td><td style='text-align:justify;'>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$fila[7]</td></tr>";
							}
						?>
						</table>
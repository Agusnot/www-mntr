<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
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
					<link rel="stylesheet" type="text/css" href="../../../General/Estilos/estilos.css">
					
				</head>	
				<body <?php echo $backgroundBodyInfContableMentor;?>>
					<div class="divInformeContable" <?php echo $alignDivInformeContable;?>>
	
	
						<table class="tablaInformeContable" width="450px" style="text-align:justify;"   <?php echo $borderTablaInfContable; echo $bordercolorTablaInfContable; echo $cellspacingTablaInfContable; echo $cellpaddingTablaInfContable; ?>>
								<tr>
									<td colspan="8"><center><strong><?echo strtoupper($Compania[0])?><br>
										<?echo $Compania[1]?><br>CENTROS DE COSTO<br>VIGENCIA <?echo $Anio?><br>
										FECHA DE IMPRESI&Oacute;N <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
								</tr>
								<tr>
									<td class='encabezado2HorizontalInfCont'>C&Oacute;DIGO</td>
									<td class='encabezado2HorizontalInfCont'>CENTRO</td>
									<td class='encabezado2HorizontalInfCont'>TIPO</td>
								</tr>
						<?
							$cons="Select codigo,centrocostos,tipo from central.centroscosto where Anio=$Anio and Compania='$Compania[0]' Order By Codigo";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{
								if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
								else{$BG="white";$Fondo=1;}
								echo "<tr bgcolor='$BG'><td>$fila[0]</td><td>";
								if($fila[2]=="Titulo")
								{
									echo "<strong>" . strtoupper($fila[1]);
								}
								else
								{
									echo "<ul>".$fila[1];
								}
								echo "</td><td>$fila[2]</td></tr>";
							}
						?>
						</table>
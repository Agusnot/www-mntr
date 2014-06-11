		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){
				$cons = "Delete from Contabilidad.ConceptosAfectacion where Compania = '$Compania[0]' and Comprobante = '$Comprobante' and Concepto = '$Concepto'";
				$res = ExQuery($cons);
				echo ExError();
			}
			if(!$Anio)
			{
				$ND = getdate();
				$Anio = $ND[year];
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
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "COMPROBANTES";
				$rutaarchivo[3] = "CONCEPTOS DE AFECTACION";					
				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
						<table  width="100%" class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td>
									<form name="FORMA" method="post">
										A&Ntilde;O: 
										<select name="Anio" onChange="FORMA.submit()">
										<?
											$cons = "Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
											$res = ExQuery($cons);
											while($fila = ExFetch($res))
											{
												if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else {echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
										?>
										</select>
								</td>
								<?
									if($Anio)
									{
								?>
									<td colspan="6" style="text-align:right;padding-right:10px;">
										<input type="button" name="Nuevo" class="boton2Envio" value="Nuevo Registro" onclick="location.href='NuevoConfConcAfectac.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" />
									</td>
							</tr>
							<tr>
								<td class='encabezado2Horizontal'>COMPROBANTE</td>
								<td class='encabezado2Horizontal'>CONCEPTO</td>
								<td class='encabezado2Horizontal'>CUENTA DESTINO</td>
								<td class='encabezado2Horizontal'>CUENTA BASE</td>
								<td class='encabezado2Horizontal'>OPERACI&Oacute;N</td>
								<td class='encabezado2Horizontal' colspan="2">&nbsp;</td>
							</tr>
								<?
									$cons = "Select Comprobante,Concepto,Cuenta,CuentaBase,Opera from Contabilidad.ConceptosAfectacion where Compania='$Compania[0]'
											and Anio = '$Anio' Order by Comprobante";
									$res = ExQuery($cons);
									while($fila = ExFetch($res))
									{
										?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
										echo "<td>$fila[0]</td><td>$fila[1]</td><td align='right'>$fila[2]</td><td align='right'>$fila[3]</td><td align='center'>$fila[4]</td>";
										?><td width="16px">
										<a href="NuevoConfConcAfectac.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Comprobante=<? echo $fila[0]?>&Concepto=<? echo $fila[1]?>&Anio=<? echo $Anio?>">
										<img border=0 src="/Imgs/b_edit.png"></a></td>
										<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
										{location.href='ConfConceptosAfectacion.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<? echo $fila[0]?>&Concepto=<? echo $fila[1]?>';}">
										<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
									}
								?></table><?
							}
							?>
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
				</body>
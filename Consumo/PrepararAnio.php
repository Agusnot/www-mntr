		<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");
		include("ObtenerSaldos.php");
		include_once("General/Configuracion/Configuracion.php");
		$ND = getdate();
		if(!$Anio){ $Anio = $ND[year];}
		$AnioAnt = $Anio - 1;
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
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "PROCESOS DE CONSUMO";
					$rutaarchivo[2] = "PREPARAR A&Ntilde;O";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>	
				<div <?php echo $alignDiv1Mentor; ?> class="div1">
					<form name="FORMA" method="post">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<table class="tabla1"  width="300px" <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado2Horizontal">PREPARAR A&Ntilde;O</td>
							</tr>
							<tr>
								<td colspan="2" class="encabezadoGrisaceo" style="text-align:center;">A&Ntilde;O 
									<select name="Anio" onChange="FORMA.submit()">
										<?	
										$cons1 = "Select Anio from Central.Anios where Compania='$Compania[0]' ORDER BY anio DESC LIMIT 20";
										$res1 = ExQuery($cons1);
										while($fila1 = ExFetch($res1)){
											if($Anio==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
											else {echo "<option value='$fila1[0]'>$fila1[0]</option>";}
										}										
										?>
									</select>
								</td>
							</tr>
							<?
							if($Anio){?>
							<tr>							
								<td style="padding:0px;text-align:center;">
									<table class="tabla1" width="100%" <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
										<tr>
											<td class="encabezado2HorizontalInvertido"> PAR&Aacute;METRO</td>
											<td class="encabezado2HorizontalInvertido">&nbsp;</td> 
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Grupos</td>
											<td><input type="checkbox" name="Grupos" id="0" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Items por Grupo</td>
											<td><input type="checkbox" name="CuentasxCC" id="1" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Criterios por Grupo</td>
											<td><input type="checkbox" name="Criteriosxgrupo" id="2" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Criterios por Proveedor</td>
											<td><input type="checkbox" name="Criteriosxproveedor" id="3" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Productos</td>
											<td><input type="checkbox" name="Productos" id="4" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Tarifas por Producto</td>
											<td><input type="checkbox" name="CuentasxCC" id="5" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Productos por Contrato</td>
											<td><input type="checkbox" name="ProdsxContrato" id="6" /></td>
										</tr>
										<tr><td colpan="2" align="center"><hr></td></tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Ajustes por Cuenta Contable</td>
											<td><input type="checkbox" name="AjustesxCuentaCont" id="7" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Cuentas por Centros de Costo</td>
											<td><input type="checkbox" name="CuentasxCC" id="8" /></td>
										</tr>
										<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
											<td>Usuarios por Centros de Costo</td>
											<td><input type="checkbox" name="CuentasxCC" id="9" /></td>
										</tr>
									</table>
								</td>
							</tr>
							<?}?>
						</table>
					</form>
				</div>
			</body>
		</html>	
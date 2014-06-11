		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			//echo $Anio;
				$cons="Select Fecha,Usuario,Codigo1,Cantidad,NombreProd1,Presentacion,
				UnidadMedida,SolicitudConsumo.Estado,CentroCostos,SolicitudConsumo.AlmacenPpal,Aprobadox,FechaAprob
			from Consumo.SolicitudConsumo,Consumo.CodProductos 
			where IdSolicitud=$IdSolicitud and SolicitudConsumo.AutoId=CodProductos.AutoId
				and SolicitudConsumo.AlmacenPpal = CodProductos.AlmacenPpal and
			SolicitudConsumo.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]' and CodProductos.Anio = $Anio";
				//echo $cons;
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$Fecha=$fila[0];$Usuario=$fila[1];
			$CC=$fila[8];$Almacen=$fila[9];$Aprobadox=$fila[10];$FechaA=$fila[11];
			if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
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
				
				<body <?php echo $backgroundBodyMentor; ?>>
					<div align="center" style="margin-top:25px;margin-bottom:25px;line-height:5px;">
						<p style="font-weight:bold;font-size:14px;"><?echo $Compania[0]?></p>
						<p style="font-weight:bold;font-size:12px;"> ORDEN DE SOLICITUD DE ELEMENTOS DE CONSUMO</p>
					</div>	
					
					
					
				<div align="center">	
					<table class="tabla2" width="95%" border="0"  <?php echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2VerticalInvertido" width="25%">FECHA Y HORA </td>
							<td width="25%"><?echo $Fecha?>&nbsp;</td>
							<td width="25%">&nbsp;</td>
							<td rowspan="2" width="25%">
								<table border="0" cellpadding="2" cellspacing="0">
									<tr>
										<td class="encabezado2HorizontalInvertido">NO. SOLICITUD</td>
									</tr>
									<tr>
										<td style="text-align:center;"><?echo $IdSolicitud?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido">SOLICITANTE</td>
							<td><?echo $Usuario?></td>
							<td width="25%">&nbsp;</td>
							<td width="25%">&nbsp;</td>
						</tr>
						<tr>
							<td class="encabezado2VerticalInvertido"> ALMAC&Eacute;N PRINCIPAL</td>
							<td><?echo strtoupper($Almacen);?></td>
						</tr>
					</table>
				</div>	
				
				<br> <br>
				
				<table class="tabla2" width="100%"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
					<tr>
						<td colspan="2" class="encabezado2Horizontal" >CENTRO DE COSTOS </td>
						<?
							$cons1="Select CentroCostos from Central.CentrosCosto where Compania='$Compania[0]' and Codigo='$CC'";
							$res1=ExQuery($cons1);
							$fila1=ExFetch($res1);
						?>
						<td colspan="3" class="encabezado2Horizontal"><? echo $CC." - ".strtoupper($fila1[0]);?></td>
					</tr>
					<tr>
						<td class="encabezado2HorizontalInvertido">C&Oacute;DIGO</td>
						<td class="encabezado2HorizontalInvertido">NOMBRE</td>
						<td class="encabezado2HorizontalInvertido">CANTIDAD</td>
						<td class="encabezado2HorizontalInvertido">ESTADO</td>
					</tr>
					<?
						$res=ExQuery($cons);
						while($fila=ExFetch($res))	{
							echo "<tr>";
								echo "<td>$fila[2]</td>";
								echo "<td>$fila[4] $fila[5] $fila[6]</td>";
								echo "<td style='text-align:center;'>$fila[3]</td>";
								echo "<td style='text-align:center;'>$fila[7]</td>";
							echo "</tr>";	
						}
					?>
				</table>

				<br />
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td width="50%" style="text-align:center;">
							<?
								$cons = "Select Cedula from Central.Usuarios Where Nombre = '$Usuario'";
								$res = ExQuery($cons);
								$fila=ExFetch($res);
								if(file_exists($_SERVER["DOCUMENT_ROOT"]."/Firmas/$fila[0].png"))
								{
									?><img src="<?echo "/Firmas/$fila[0].png";?>" style=" width: 140px; height: 80px;"/><br><?
								}
							?>
							_________________________________<br>
							<span style="font-size:12px;font-weight:bold;"><?echo $Usuario?></span>
						</td>
						
						<?
						if($Aprobadox){
							?>
							<td width="50%">
								<?
								$cons = "Select Cedula from Central.Usuarios Where Nombre = '$Aprobadox'";
								$res = ExQuery($cons);
								$fila=ExFetch($res);
								if(file_exists($_SERVER["DOCUMENT_ROOT"]."/Firmas/$fila[0].png")){
									?><img src="<?echo "/Firmas/$fila[0].png";?>" style=" width: 140px; height: 80px;"/><br><?
								}

								?>
								_________________________________<br>
								<span style="font-size:12px;font-weight:bold;"><?echo "Aprueba: $Aprobadox"?></span>
							</td>
						
								<?
						}
						?>
					</tr>	
				</table>
			</body>
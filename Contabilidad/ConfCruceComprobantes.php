		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar)
			{
				$cons = "Delete from Contabilidad.CruzarComprobantes where Compania = '$Compania[0]' and Comprobante = '$Comprobante' and CruzarCon = '$CruzarCon'";
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
				$rutaarchivo[3] = " CRUCE DE COMPROBANTES";	
				
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv3Mentor; ?> class="div3">
						<table  width="100%" class="tabla3"   <?php echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?> >
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
								if($Anio){
								?>
								<td style="text-align:right;padding-right:15px;" colspan="6">
									<input type="button" class="boton2Envio" name="Nuevo" value="Nuevo Registro" onclick="location.href='NuevoConfCruceComp.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'" /></td>
							</tr>
							<tr>
								<td class='encabezado2Horizontal'>COMPROBANTE</td>
								<td class='encabezado2Horizontal'>CRUZAR CON</td>
								<td class='encabezado2Horizontal'>MOVIMIENTO</td>
								<td class='encabezado2Horizontal'>CUENTA</td>
								<td class='encabezado2Horizontal'>CUENTA A CRUZAR</td>
								<td class='encabezado2Horizontal' colspan="2">&nbsp;</td>
							</tr>
								<?
									$cons = "Select Comprobante,CruzarCon,Movimiento,Cuenta,CuentaCruzar from Contabilidad.CruzarComprobantes where Compania='$Compania[0]'
											and Anio = '$Anio' Order by Comprobante";
									$res = ExQuery($cons);
									while($fila = ExFetch($res))
									{
										?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><?
										echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td align='right'>$fila[3]</td><td align='right'>$fila[4]</td>";
										?><td width="16px">
										<a href="NuevoConfCruceComp.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&Comprobante=<? echo $fila[0]?>&CruzarCon=<? echo $fila[1]?>&Movimiento=<? echo $fila[2]?>&Cuenta=<? echo $fila[3]?>&CuentaCruzar=<? echo $fila[4]?>">
										<img border=0 src="/Imgs/b_edit.png"></a></td>
										<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
										{location.href='ConfCruceComprobantes.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Comprobante=<? echo $fila[0]?>&CruzarCon=<? echo $fila[1]?>';}">
										<img border="0" src="/Imgs/b_drop.png"/></a></td></tr><?
									}
								?></table><?
							}
							?>
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						</form>
				</div>		
	</body>
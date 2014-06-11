		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			$ND=getdate();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$cons="select pantalla from historiaclinica.itemsxformatos where tipoformato='$TF' and formato='$NewFormato' and compania='$Compania[0]' group by pantalla order by pantalla";
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
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>><?
						if(ExNumRows($res)>0){?>
							<tr>
								<td class="encabezado2Horizontal">P&Aacute;GINA</td>
								<td>
									<select name="NumPag">
									<?	while($fila=ExFetch($res)){
											echo "<option value='$fila[0]'>$fila[0]</option>";
										}?>
									</select>
								</td>
							</tr>
							<tr align="center">
								<td colspan="2">
									<input type="button" class="boton2Envio" value="Ver" 
									onClick="open('VistaPrevia.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $NewFormato?>&TipoFormato=<? echo $TF?>&IdPantalla='+document.FORMA.NumPag.value,'','width=1100,height=600,scrollbars=1,resizable=1')">
								</td>
							</tr><?
						}
						else{?>
							<tr>
								<td class="mensaje1">A&uacute;n no se han ingresado items</td>
							</tr>
							<?
						}?>
						</table>
					</form>
				</div>	
			</body>
		</html>

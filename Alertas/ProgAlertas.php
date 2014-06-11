		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Eliminar){
				
				$cons="Delete from alertas.CargosxAlertas where compania='$Compania[0]' and idalerta=$Id";
				$res=ExQuery($cons);				
				
				$cons="Delete from  alertas.alertasxmodulos where compania='$Compania[0]' and id=$Id";
				$res=ExQuery($cons);
				
				$cons="Delete from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$Id";
				$res=ExQuery($cons);
				
				$cons="Delete from Alertas.AlertasProgramadas where Compania='$Compania[0]' and Id='$Id'";		
				$res=ExQuery($cons);
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
			
				<?		
				mostrarRutaNavegacion($_SERVER['PHP_SELF']);	
				$cons = "Select Id,InstruccionSQL,MsjAlerta,Archivo,Estado,descripcion,bloqueante from Alertas.AlertasProgramadas where Compania='$Compania[0]' order by Id";
				$res=ExQuery($cons);
				?>
				<div <?php echo $alignDiv3Mentor; ?> class="div3">
					
					<div style="margin-top:15px; margin-bottom: 15px;">
						<input type="button" class="boton2Envio" name="Nuevo" value="Nueva alerta" onClick="location.href='NuevoProgAlertas.php?DatNameSID=<? echo $DatNameSID?>'" />
					</div>
					
					<table class="tabla3"  <?php echo $borderTabla3Mentor ; echo $bordercolorTabla3Mentor ; echo $cellspacingTabla3Mentor ; echo $cellpaddingTabla3Mentor; ?>   width='100%' >
						<tr>
							<td class="encabezado2Horizontal">ID</td>
							<td class="encabezado2Horizontal">INSTRUCCI&Oacute;N SQL</td>
							<td class="encabezado2Horizontal"> MENSAJE</td>
							<td class="encabezado2Horizontal">DESCRIPCI&Oacute;N</td>
							<td class="encabezado2Horizontal"> ARCHIVO </td>
							<td class="encabezado2Horizontal">ESTADO</td>
							<td class="encabezado2Horizontal">BLOQUEANTE</td>
							<td colspan='4' class="encabezado2Horizontal" > CONFIGURACI&Oacute;N</td>
						</tr>
						<?php
						while ($fila=ExFetch($res))	{
							?>
							<tr>
								<td width='3%'>
									<?php echo $fila[0]; ?>
								</td>
								
								<td width='25%' style='word-break:break-all;font-size:11px;'>
									<?php echo str_replace("|","'",$fila[1]);?>
								</td>
								
								<td width='15%' style='word-break:keep-all;text-align:center;'>
									<?php echo $fila[2];?>
								</td>
								
								<td width='15%' style='word-break:keep-all;text-align:center;'>
									<?php echo $fila[5];?>
								</td>
								
								<td width='15%' style='word-break:break-all;text-align:center;'>
									<?php echo $fila[3];?>
								</td>
								
								<td width='5%' style="text-align:center;">
									<?php echo $fila[4]; ?>
								</td>
								
								<td width="5%" style="text-align:center;">
									<?php echo $fila[6]; ?>
								</td>
								
								<td width='5%' style="text-align:center;" >
									<a href="#" onClick="open('CargosxAlerta.php?DatNameSID=<? echo $DatNameSID?>&idalerta=<? echo $fila[0]?>&compania=<? echo $Compania[0];?>','','width=500,height=600,scrollbars=yes')">
										<img border="0" title="CargosxAlerta" src="/Imgs/IconoCargos.png"  width="25px" height="20px" >
									</a>
								</td>
							
									
								<td width='5%' style="text-align:center;">
									<a href="#" onClick="open('UsuariosxAlertas.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila[0]?>','','width=400,height=600,scrollbars=yes')">
										<img border="0" title="UsuariosxAlertas" src="/Imgs/b_usredit.png">
									</a>
								</td>
							
								
											
								<td width='5%' style="text-align:center;">
									<a href="NuevoProgAlertas.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Id=<? echo $fila[0]; ?>">
										<img title="Editar" border="0" src="/Imgs/b_edit.png" />
									</a>
								</td>
								<td width='5%' style="text-align:center;">
									<a href="#" onClick="if(confirm('Desea Eliminar el registro?')){location.href='ProgAlertas.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Id=<? echo $fila[0];?>'}">
										<img title="Desactivar" border="0" src="/Imgs/b_drop.png"/>
									</a>
								</td>
							</tr>
							<?
						}
					echo "</table>";
				echo "</div>";
			?>
			
			</body>
		</html>	
	
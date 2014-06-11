		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");	
			include_once("General/Configuracion/Configuracion.php");
		?>
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
				
				<script language="javascript">
					function AbrirUsusxOrdMeds(Usuario)
					{
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="AutUsusxOrdMeds.php?DatNameSID=<? echo $DatNameSID?>&Usuario="+Usuario;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=st+70;
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='400';
						document.getElementById('FrameOpener').style.height='550';
					}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "USUARIOS POR ORDENES MEDICAS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado2Horizontal"> USUARIOS POR ORDENES MEDICAS</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">NOMBRE USUARIO</td>
								<td>&nbsp;</td>
							</tr>
							<?
							$cons="select nombre,usuariosxhc.usuario from salud.usuariosxhc,central.usuarios where UPPER(modulo)='ORDENES MEDICAS' and usuarios.usuario=usuariosxhc.usuario order by nombre";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))	{
								echo "<tr><td>$fila[0]</td><td>";	?>
								<img src="/Imgs/s_process.png" style="cursor:hand" onClick="AbrirUsusxOrdMeds('<? echo $fila[1]?>')"/></td></tr><?
							}
							?>
						</table>
						<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
				</div>	
			</body>
		</html>

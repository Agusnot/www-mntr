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
					function AbrirUsuariosxHC(Usuario)
					{
						st = document.body.scrollTop;
						frames.FrameOpener.location.href="AutUsuariosxHC.php?DatNameSID=<? echo $DatNameSID?>&Usuario="+Usuario;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=st+70;
						document.getElementById('FrameOpener').style.left='10px';
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
				$rutaarchivo[2] = "USUARIOS POR HISTORIA CL&Iacute;NICA";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="2" class="encabezado2Horizontal"> USUARIOS POR HISTORIA CL&Iacute;NICA </td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">NOMBRE USUARIO</td>
								<td class="encabezado2HorizontalInvertido">&nbsp;</td></tr>
							<?	$cons="Select usuarios.Usuario,usuarios.Cedula,usuarios.Nombre from Central.Usuarios,salud.medicos,salud.cargos
							where medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and usuarios.usuario=medicos.usuario and medicos.cargo=cargos.cargos and cargos.asistencial=1
							Order By Usuario ASC, Nombre ASC";
							$res=ExQuery($cons);
							while($fila=ExFetch($res))
							{?>
								<tr><td><? echo $fila[2] ?></td>
									<td><button onClick="AbrirUsuariosxHC('<? echo $fila[0]?>')"><img title="Autorizar Historia Clinica" src="/Imgs/s_process.png"></button></td>
								</tr>
						<? } ?>
						</table>
						<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
						<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
				</div>	
			</body>
	</html>

		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			
			if($NewClave)
			{
				$ND=getdate();
				$NClave=md5($NewClave);
				$cons="Update Central.Usuarios set Clave='$NClave',CambioClave='$ND[year]-$ND[mon]-$ND[mday]' where Usuario='$Usuario'";
				$res=ExQuery($cons);
				echo ExError($res);
				?>
				<script language="javascript">
					window.close();
				</script>
				<?
			}
		?>
	<html>	
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="General/Estilos/estilos.css">
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>
				
		<form name="FORMA">
		
			<table class="tabla1"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
				<tr>
					<td class="encabezado1Horizontal">ASIGNAR CLAVE</td>
				</tr>
				<tr>
					<td style="text-align:center;">
						<input style="width:250px;" type="password" name="NewClave" />
				<tr>
					<td style="text-align:center;">
						<input type="submit" name="Guardar" class="boton2Envio" value="Guardar Cambio" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
			</form>
		</body>
	</html>	
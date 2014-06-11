		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");	
			
			if($Eliminar){
				$cons="delete from alertas.usuariosxalertas where compania='$Compania[0]' and usuario='$Usu' and idalerta=$Id";
				$res=ExQuery($cons);
			}
			
			$cons="select nombre,usuariosxalertas.usuario, usuarios.usuario from alertas.usuariosxalertas,central.usuarios 
			where compania='$Compania[0]' and idalerta=$Id and usuarios.usuario=usuariosxalertas.usuario ORDER BY usuarios.usuario ASC ";
			$res=ExQuery($cons);
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
					$rutaarchivo[0] = "ADMINISTRADOR";
					$rutaarchivo[1] = "ALERTAS";				
					$rutaarchivo[2] = "USUARIOS X ALERTA";
						
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
				<form name="FORMA" method="post">
					<div align="center">
						<table  class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> width="95%" rules="group" >
							<tr>
								<td class="encabezado2Horizontal"> USUARIO</td>
								<td class='encabezado2Horizontal'> &nbsp; </td>
							</tr>
						 <?	while($fila=ExFetch($res))
							{?>
								<tr>
									<td><? echo $fila[0]?></td>	
									<td>
										<img src="/Imgs/b_drop.png" title="Eliminar" 
										onclick="location.href='UsuariosxAlertas.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>&Usu=<? echo $fila[1]?>&Eliminar=1'"/>
									</td>
								</tr>
						<?	}?>     
							<tr align="center">
								<td colspan="2"><input type="button" class="boton2Envio" value="Nuevo registro " onclick="location.href='NewUsuarioxAlertas.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $Id?>'"/></td>
							</tr>       
						</table>
					</div>	
				</form>
			</body>
		</html>

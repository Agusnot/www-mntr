		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");

			if($Guardar)
			{
				$cons="Insert into HistoriaClinica.PermisosxFormato (Formato,Perfil,Permiso,TipoFormato,Compania) values ('$NewFormato','$Perfil','$ClasePermiso','$TF','$Compania[0]')";
				$res=ExQuery($cons,$conex);
				echo ExError($conex);
			}
			if($Eliminar){
				$cons="Delete from HistoriaClinica.PermisosxFormato where Formato='$NewFormato' and Permiso='$ClasePermiso' and Perfil='$Perfil' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$res=ExQuery($cons,$conex);
			}
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
					<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class="encabezado2Horizontal">PERFIL</td>
							<td class="encabezado2Horizontal" colspan="2">PERMISO</td></tr>
					<?
						$cons="Select * from HistoriaClinica.PermisosxFormato where Formato='$NewFormato' and Permiso='$ClasePermiso' and TipoFormato='$TF' and Compania='$Compania[0]'";
						$res=ExQuery($cons,$conex);
						while($fila=ExFetchArray($res))
						{
							echo "<tr bgcolor='white'><td>" . $fila['perfil'] . "</td><td>" . $fila['permiso'] . "</td>";
							echo "<td><a href='EditPermisos.php?DatNameSID=$DatNameSID&Eliminar=1&NewFormato=$NewFormato&ClasePermiso=$ClasePermiso&Perfil=" . $fila['perfil'] . "&TF=$TF'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
							$consAdc=$consAdc . "'" . $fila['perfil'] . "'" . " and Cargos !=";
						}

						if(!$consAdc){$consAdc=" and 1=1";}
						else{$consAdc=" and Cargos !=" . $consAdc;
							$consAdc=substr($consAdc,1,strlen($consAdc)-14);
						}
						if($ClasePermiso!="Impresion"){$CondAdc2=" and Asistencial=1";}
					?>
					<tr>
					<td>
					<form name="FORMA">
					<select name="Perfil">
					<?

						$cons="Select * from Salud.Cargos where Compania='$Compania[0]' $consAdc $CondAdc2 Order By Cargos";
						$res=ExQuery($cons,$conex);echo ExError($conex);
						while($fila=ExFetch($res))
						{
							echo "<option value='$fila[0]'>$fila[0]</option>";
						}
					?>
					</select>

					<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
					<input type="Hidden" name="ClasePermiso" value="<?echo $ClasePermiso?>">
					<input type="Hidden" name="TF" value="<?echo $TF?>">
					<td>

					<td>
					<?	if(ExNumRows($res)>0){?>
					<input type="Submit" class="boton1Envio" name="Guardar" value="G"><? } ?></td>
					<input type="hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="hidden" name="TF" value="<? echo $TF?>">
					</form>
					</table>
					<input type="button" class="boton2Envio" value="Volver" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
				</div>
		</body>
	</html>		
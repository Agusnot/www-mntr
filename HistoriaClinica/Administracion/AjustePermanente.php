		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			//echo $_SERVER['DOCUMENT_ROOT'];
			if($Guardar)
			{		
				$cons="Insert into HistoriaClinica.AjustePermanente(Formato,Perfil,Permiso,TipoFormato,Compania) values ('$NewFormato','$Perfil','$ClasePermiso','$TF','$Compania[0]')";
				$res=ExQuery($cons,$conex);
				echo ExError($conex);
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.AjustePermanenteDet where Formato='$NewFormato' and TipoFormato='$TF' and Perfil='$Perfil' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				//echo $cons."<br>";
				//--
				$cons="Delete from HistoriaClinica.AjustePermanente where Formato='$NewFormato' and Permiso='$ClasePermiso' and Perfil='$Perfil' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				//echo $cons."<br>";
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
							<td class="encabezado2Horizontal">CARGO</td>
							<td class="encabezado2Horizontal" colspan="2">PERMISO</td></tr>
						<?
							$cons="Select * from HistoriaClinica.AjustePermanente where Formato='$NewFormato' and Permiso='$ClasePermiso' and TipoFormato='$TF' and Compania='$Compania[0]'";
							$res=ExQuery($cons,$conex);
							while($fila=ExFetchArray($res))
							{?>
								<tr><td><a style="color:#0068D4;text-decoration:none;" title="Configurar Items Permitidos para <? echo $fila['perfil']?>" href="AjustePermanenteDet.php?DatNameSID=<? echo $DatNameSID ?>&Perfil=<? echo $fila['perfil'] ?>&NewFormato=<? echo $NewFormato ?>&TF=<? echo $TF ?>&ClasePermiso=<? echo $ClasePermiso?>" ><? echo $fila['perfil']?></a></td><td><? echo $fila['permiso']?></td></a>
							<td><a title='Eliminar Permiso de ajuste para <? echo $fila['perfil']?>' href="#" onClick="if(confirm('Desea eliminar el permiso de modificación para <? echo $fila['perfil']?>? \nNota: Se eliminarán los items que se configuraron para  ser ajustados por <? echo $fila['perfil']?>!!!')){location.href='AjustePermanente.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&ClasePermiso=<? echo $ClasePermiso ?>&Perfil=<? echo $fila['perfil']?>&TF=<? echo $TF?>&ClasePermiso=<? echo $ClasePermiso?>'}"><img src='/Imgs/b_drop.png' border=0></a></td></tr>
							<?
								$consAdc=$consAdc . "'" . $fila['perfil'] . "'" . " and Cargos !=";
							}

							if(!$consAdc){$consAdc=" and 1=1";}
							else{$consAdc=" and Cargos!=" . $consAdc;
								$consAdc=substr($consAdc,1,strlen($consAdc)-15);
							}

						?>
						<tr>
						<td>
						<form name="FORMA">
						<select name="Perfil">
						<?

							$cons="Select * from Salud.Cargos where Compania='$Compania[0]' $consAdc Order By Cargos";
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
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
						<?	if(ExNumRows($res)>0){?>
						<td><input type="Submit" name="Guardar" class="boton1Envio" value="G"></td><? }?>
						</form>
					</table>
					<input type="button" value="Volver" class="boton2Envio" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
				</div>	
			</body>
		</html>	
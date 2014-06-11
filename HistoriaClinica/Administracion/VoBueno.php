		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar)
			{
				if($Cargo=="")
				{
					echo "<script languaje='javascript'>alert('Seleccione algun cargo!');</script>"	;
				}
				else
				{
					$Fecha=date("Y-m-d");
					$cons="Insert into  HistoriaClinica.VoBoxFormatos (UsuarioCre,FechaCre,TipoFormato,Formato,Cargo,Compania) values ('$usuario[0]','$Fecha','$TF','$NewFormato','$Cargo','$Compania[0]')";
					$res=ExQuery($cons,$conex);
					echo ExError($conex);
				}
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.VoBoxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Cargo='$Cargo' and Compania='$Compania[0]'";
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
							<td class="encabezado2Horizontal" colspan="2">CARGO</td>
						</tr>
					<?
						$cons="Select * from HistoriaClinica.VoBoxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
						$res=ExQuery($cons,$conex);
						while($fila=ExFetchArray($res))
						{
							echo "<tr bgcolor='white'><td>" . $fila['cargo'] . "</td>";
							echo "<td><a href='VoBueno.php?DatNameSID=$DatNameSID&Eliminar=1&NewFormato=$NewFormato&TF=$TF&Cargo=" . $fila['cargo'] . "'><img src='/Imgs/b_drop.png' border=0></a></td></tr>";
							$consAdc=$consAdc . "'" . $fila['cargo'] . "'" . " and Cargos !=";
						}
						if(!$consAdc){$consAdc="1=1";}
						else{$consAdc=" Cargos !=" . $consAdc;
							$consAdc=substr($consAdc,1,strlen($consAdc)-14);
							$CondAdc2=" and Asistencial=1";
						}
					?>
					<tr>
					<td>
					<form name="FORMA">
					<select name="Cargo">
					<?

						$cons="Select * from Salud.Cargos where $consAdc $CondAdc2 and Compania='$Compania[0]' Order By Cargos";
						$res=ExQuery($cons,$conex);echo ExError($conex);
						while($fila=ExFetch($res))
						{
							echo "<option value='$fila[0]'>$fila[0]</option>";
						}
					?>
					</select>

					<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<?	if(ExNumRows($res)>0){?>
					<td><input type="Submit" name="Guardar" class="boton1Envio" value="G"></td><? }?>

					</form>
					</table>
					<input type="button" class="boton2Envio" value="Volver" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
				</div>
			</body>
	</html>		
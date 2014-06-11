		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			//echo $_SERVER['DOCUMENT_ROOT'];
			if($Guardar)
			{		
				$cons="Insert into HistoriaClinica.FinalidadxFormato(Formato,TipoFormato,finalidadformat,Compania) 
				values ('$NewFormato','$TF','$Finalidad','$Compania[0]')";
				$res=ExQuery($cons,$conex);
				echo ExError($conex);
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.FinalidadxFormato where Formato='$NewFormato' and TipoFormato='$TF' and finalidadformat='$FinalidadElim' 
				and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				//echo $cons."<br>";
				//--
				
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
							<td class="encabezado2Horizontal">FINALIDAD FORMATO</td>
							<td class="encabezado2Horizontal" colspan="2"></td>
						</tr>
					<?	$Ban=0;
						$cons="Select finalidad,finalidadformat from HistoriaClinica.FinalidadxFormato,salud.finalidadesact
						where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and codigo=finalidadformat order by finalidad";
						$res=ExQuery($cons,$conex);
						while($fila=ExFetch($res))
						{
							$Ban=1;?>
							<tr><td><? echo $fila[0]?></td>
								<td><img title="Eliminar" src='/Imgs/b_drop.png' border=0 onClick="if(confirm('Esta seguro de eliminar este elemento?')){location.href='FinalidadxFormatoFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato ?>&TF=<? echo $TF ?>&Eliminar=1&FinalidadElim=<? echo $fila[1]?>';}" style="cursor:hand"></td>
							</tr>
						<?
							
						}
					?>
					<tr>
					<td>
					<form name="FORMA">
					<?	if($Ban==1)
						{
							$Exculir=" and codigo not in (select finalidadformat from HistoriaClinica.FinalidadxFormato 
							where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]')";
						}
						$cons="Select finalidad,codigo from salud.finalidadesact where tipo=1 $Exculir  Order By finalidad";
						$res=ExQuery($cons);?>
					<select name="Finalidad">
					<?	while($fila=ExFetch($res))
						{
							echo "<option value='$fila[1]'>$fila[0]</option>";
						}
					?>
					</select>

					<input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
					<input type="Hidden" name="ClasePermiso" value="<?echo $ClasePermiso?>">
					<input type="Hidden" name="TF" value="<?echo $TF?>">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<?	if(ExNumRows($res)>0){?>
					<td><input type="Submit" class="boton1Envio" name="Guardar" value="G"></td><? }?>
					</form>
					</table>
					<input type="button" class="boton2Envio" value="Volver" onClick="location.href='/HistoriaClinica/Administracion/ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
				</div>
			</body>
	</html>		
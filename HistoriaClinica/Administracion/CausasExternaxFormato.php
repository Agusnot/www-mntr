		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			//echo $_SERVER['DOCUMENT_ROOT'];
			if($Guardar)
			{		
				$cons="Insert into HistoriaClinica.CausaExternxFormato(Formato,TipoFormato,causaextformat,Compania) 
				values ('$NewFormato','$TF','$CausaExterna','$Compania[0]')";
				$res=ExQuery($cons,$conex);
				echo ExError($conex);
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.CausaExternxFormato where Formato='$NewFormato' and TipoFormato='$TF' and causaextformat='$CausaExternaElim' 
				and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				//echo $cons."<br>";
				//--
				$cons="Delete from HistoriaClinica.CausaExternxFormato where Formato='$NewFormato' and causaextformat='$CausaExterna'  and TipoFormato='$TF' 
				and Compania='$Compania[0]'";
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
										<td class="encabezado2Horizontal">CAUSA EXTERNA</td>
										<td class="encabezado2Horizontal" colspan="2">&nbsp;</td>
									</tr>
									<?	$Ban=0;
										$cons="Select causa,causaextformat from HistoriaClinica.CausaExternxFormato,salud.causaexterna
										where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and codigo=causaextformat order by causa";
										$res=ExQuery($cons,$conex);
										while($fila=ExFetch($res))
										{
											$Ban=1;?>
											<tr><td><? echo $fila[0]?></td>
												<td><img title="Eliminar" src='/Imgs/b_drop.png' border=0 onClick="if(confirm('Esta seguro de eliminar este elemento?')){location.href='CausasExternaxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato ?>&TF=<? echo $TF ?>&Eliminar=1&CausaExternaElim=<? echo $fila[1]?>';}" style="cursor:hand"></td>
											</tr>
										<?
											
										}
									?>
									<tr>
									<td>
									<form name="FORMA">
									<?	if($Ban==1){$Exculir=" and codigo not in (select causaextformat from HistoriaClinica.CausaExternxFormato 
													where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]')";}
										$cons="Select causa,codigo from salud.causaexterna where codigo is not null $Exculir  Order By causa";
										$res=ExQuery($cons);?>
									<select name="CausaExterna">
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
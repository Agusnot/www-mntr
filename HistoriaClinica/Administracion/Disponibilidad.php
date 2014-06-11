		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");			
			
			$cons="select * from Salud.Ambitos where Compania='$Compania[0]'";
			$res=ExQuery($cons,$conex);
			
			if($Guardar)
			{
				$Fecha=date("Y-m-d H:i:s");
				$cons1="insert into HistoriaClinica.AmbitosxFormato (UsuarioCre,FechaCre,TipoFormato,Formato,Ambito,Disponible,Compania)
				values('$usuario[0]','$Fecha','$TF','$NewFormato',";
				if(!$Ambito){$cons1=$cons1."NULL,";}
				else{$cons1=$cons1."'$Ambito',";}
				$cons1=$cons1."'Si','$Compania[0]')";
				$res=ExQuery($cons1);
				
			}
			if($Eliminar)
			{
				$cons="Delete from HistoriaClinica.AmbitosxFormato where Formato='$NewFormato' and Ambito ";
				if(!$Ambito){$cons=$cons." IS NULL";}
				else{$cons=$cons." ='$Ambito' ";}
				$cons=$cons." and TipoFormato='$TF' and Compania='$Compania[0]'";
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
					<form name="form1" action="">				
						<table class="tabla2" width="200px" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal" colspan="2">PROCESO</td>
							</tr>
							  <?
								$cons="Select * from HistoriaClinica.AmbitosxFormato where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
								$res=ExQuery($cons,$conex);
								$consAdc="and (";
								while($fila=ExFetchArray($res))
								{?>
									<tr>
										<td><? 
											if($fila['ambito']==""){echo "No aplica";$consAdc=$consAdc." 2=1 and  ";}
											echo $fila['ambito']?> 
										</td>
										<td>
											<a href='Disponibilidad.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&Ambito=<? echo $fila['ambito']?>&TF=<? echo $TF?>'><img src='/Imgs/b_drop.png' border=0></a>
										</td>
									</tr>
									
							<?		$consAdc=$consAdc . "Ambito!='" . $fila['ambito'] . "' and "; 
								}

								$consAdc=substr($consAdc,0,strlen($consAdc)-5);
								$consAdc=$consAdc.")";
								if(ExNumRows($res)==0){$consAdc="and 1=1";}
								?>
								<tr>
									<td>
										<select name="Ambito">
											<?
											if(ExNumRows($res)==0){?>
											<option value="">No aplica</option><? }?>
											  <?
												$cons="Select Ambito from Salud.Ambitos where compania='$Compania[0]' and Ambito!='Sin Ambito' $consAdc Order By Ambito ";
												$res=ExQuery($cons,$conex);echo ExError($conex);
													while($fila=ExFetch($res))
													{
														echo "<option value='$fila[0]'>$fila[0]</option>";
													}
												?>
										</select>
									</td>
										
							<?	if(ExNumRows($res)>0){
									?>
									
										<td> <input type="Submit" name="Guardar" class="boton1Envio" value="G"></td>
										
										<?
								}?>
								
									</tr>
							</table>
							<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							<input type="Hidden" name="TF" value="<? echo $TF?>">
					</form>
				</div>
			</body>		
		</html>		


		 
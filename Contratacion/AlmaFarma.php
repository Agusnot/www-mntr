		<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");
		include_once("General/Configuracion/Configuracion.php");
		if($Guardar)
		{
			$cons = "Update Consumo.AlmacenesPpales set SSfarmaceutico = NULL,
			IniControlados = NULL, FormatoFormula = NULL, Redondear = NULL, voborm = NULL
			Where Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			while(list($cad,$val)=each($Almacen))
			{
				if($val == "on")
				{
					if($Redondear[$cad] == "on"){$R = 1;}else{$R = 0;}
					//if($VoBoRM[$cad]){$V = 1;}else{$V = 0;}
					if(!$IniControlados[$cad]){$IniControlados[$cad] = 1;}
					$cons1 = "Update Consumo.AlmacenesPpales set SSFarmaceutico = 1,Redondear = $R,VoBoRM = '".$VoBoRM[$cad]."',
					IniControlados = ".$IniControlados[$cad].", FormatoFormula='".$FormatoCONTROL[$cad]."' Where
					Compania='$Compania[0]' and AlmacenPpal = '$cad'";
					$res1 = ExQuery($cons1);
				}
			}
		}
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
				function cambiarVisibility(Obj,Id)
				{
					if(Obj.checked==true)
					{
						document.getElementById("IniControlados["+Id+"]").style.visibility = "visible";
						document.getElementById("FormatoCONTROL["+Id+"]").style.visibility = "visible";
						document.getElementById("Redondear["+Id+"]").style.visibility = "visible";
						document.getElementById("VoBoRM["+Id+"]").style.visibility = "visible";
					}
					else
					{
						document.getElementById("IniControlados["+Id+"]").style.visibility = "hidden";
						document.getElementById("FormatoCONTROL["+Id+"]").style.visibility = "hidden";
						document.getElementById("Redondear["+Id+"]").style.visibility = "hidden";
						document.getElementById("VoBoRM["+Id+"]").style.visibility = "hidden";
					}
				}
			</script>
		</head>	
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "HISTORIA CL&Iacute;NICA";
				$rutaarchivo[1] = "CONFIGURACI&Oacute;N";
				$rutaarchivo[2] = "ALMACENES FARMACEUTICOS";
				mostrarRutaNavegacionEstatica($rutaarchivo);
					
			?>	
				<div <?php echo $alignDiv2Mentor; ?> class="div2">	
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td class="encabezado2Horizontal">ALMAC&Eacute;N PRINCIPAL</td>
								<td class="encabezado2Horizontal">A&Ntilde;ADIR / RETIRAR</td>
								<td class="encabezado2Horizontal">NO. INICIAL CONTROLADOS</td>
								<td class="encabezado2Horizontal">FORMATO F&Oacute;RMULA CONTROLADOS</td>
								<td class="encabezado2Horizontal">REDONDEAR</td>
								<td class="encabezado2Horizontal">VO BO REG.MEDICAMENTOS</td></tr>
							<tr>
							<?
							$cons = "Select AlmacenesPpales.AlmacenPpal,SSFarmaceutico,IniControlados,FormatoFormula,Redondear,VoBoRM
							from Consumo.UsuariosxAlmacenes,Consumo.AlmacenesPpales
							where Usuario='$usuario[1]' and UsuariosxAlmacenes.Compania='$Compania[0]' and AlmacenesPpales.Compania='$Compania[0]'
							and AlmacenesPpales.AlmacenPpal = UsuariosxAlmacenes.AlmacenPpal";
							$res = ExQuery($cons);
							while($fila = ExFetch($res))
							{
									echo "<tr><td>$fila[0]</td>";
									?>
									<td style="text-align:center;">
										<input type="checkbox" name="Almacen[<? echo $fila[0]?>]" <? echo $Check[$fila[0]]?> onClick="cambiarVisibility(this,'<?echo $fila[0]?>')"
											   <? if($fila[1]==1){?> checked <? }?> />
									</td>
									<td style="text-align:center;">
										<input type="text" name="IniControlados[<? echo $fila[0]?>]" id="IniControlados[<? echo $fila[0]?>]" value="<? echo $fila[2]?>" size="4"
										<? if($fila[1]==0){?>style=" visibility: hidden; text-align: right"<? }?> />
									</td>
									<td>
										<select name="FormatoCONTROL[<? echo $fila[0]?>]" id="FormatoCONTROL[<? echo $fila[0]?>]"
											<? if($fila[1]==0){?>style=" visibility: hidden; text-align: right"<? }?>>
											<? $RutaRoot=$_SERVER['DOCUMENT_ROOT'];
											$midir=opendir("$RutaRoot/Informes/Almacen/Reportes/");
											while($files=readdir($midir))
											{
													$ext=substr($files,-3);
													if (!is_dir($files) && ($ext=="php"))
													//$files="Formatos/".$files;
													if($files!="." && $files!="..")
													{
														if(ereg("Formula",$files))
														{
															if($files=="FormulaGenerica.php"){$tit1="Formula general para todas las entidades";}
															if($files=="FormulaXaIDSN.php"){$tit1="Instituto departamental de salud de Nariño";}
															if($files==$fila[3]){echo "<option selected value='$files' title='$tit1'>$files</option>";}
															else{echo "<option value='$files' title='$tit1'>$files</option>";}
														}
													}
											}?>
										</select>
									</td>
									<td align="center">
										<input type="checkbox" name="Redondear[<? echo $fila[0]?>]"
										<? if($fila[4]==1){?> checked <? }
										   if($fila[1]==0){?>style=" visibility: hidden; text-align: right"<? }?> />
									</td>
									<td align="center">
										<select name="VoBoRM[<? echo $fila[0]?>]" <?if($fila[1]==0){?>style=" visibility: hidden; text-align: right"<? }?>
												id="VoBoRM[<? echo $fila[0]?>]"><option></option>
											<?
											$consx = "Select Cargos from Salud.Cargos Where Compania = '$Compania[0]' and Asistencial = 1 order by Cargos";
											$resx = ExQuery($consx);
											while($filax = ExFetch($resx))
											{
												?><option <?if($filax[0]==$fila[5]){echo "selected";}?>
													value='<? echo $filax[0]?>'><?echo $filax[0]?></option><?
											}
											?>
										</select>
									</td>
									<?
							}
							?>
							</tr>
						</table>
						<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
						<input type="Submit" class="boton2Envio" name="Guardar" value="Guardar" />
					</form>
				</div>	
			</body>
		</html>	
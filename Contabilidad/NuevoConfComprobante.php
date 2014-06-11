		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Guardar){
				if($Retencion=="on"){$VrRetencion=1;}else{$VrRetencion=0;}
				if($CuentaCero=="on"){$VrCuentaCero=1;}else{$VrCuentaCero=0;}
				if($Cierre=="on"){$VrCierre=1;}else{$VrCierre=0;}
				if($Acarreo=="on"){$VrAcarreo=1;}else{$VrAcarreo=0;}
						if($Depreciacion=="on"){$VrDepreciacion=1;}else{$VrDepreciacion=0;}
				if($CompPresupuestal){$CompPresupuestal="'$CompPresupuestal'";}else{$CompPresupuestal="NULL";}
				if($CompPresupuestalAdc){$CompPresupuestalAdc="'$CompPresupuestalAdc'";}else{$CompPresupuestalAdc="NULL";}
				if($Editar==0)
				{
					$cons="Insert into Contabilidad.Comprobantes(Comprobante,TipoComprobant,Retencion,NumeroInicial,Compania,CruceCtaCero,
									Formato,CompPresupuesto,CompPresupuestoAdc,Cierre,Acarreo,Depreciacion)
					values ('".trim($Comprobante)."','$TipoComprobante','$VrRetencion','$NoInicial','$Compania[0]','$VrCuentaCero',
									'$Formato',$CompPresupuestal,$CompPresupuestalAdc,'$VrCierre','$VrAcarreo',$VrDepreciacion)";
					$res=ExQuery($cons);
					echo ExError($res);
				}
				if($Editar==1)
				{
					$consValidar = "SELECT COUNT(*) FROM contabilidad.movimiento WHERE comprobante = '$OldComprobante' 
					AND compania = '$Compania[0]'";
					
					$resValida = ExQuery($consValidar);
					$filas = ExFetchArray($resValida);
					
					if($filas[0] > 0){
						?>
							<script>
								alert("No se puede modificar el comprobante porque ya tiene movimientos.");
							</script>
						<?
					}else{
					
						$cons = "UPDATE Contabilidad.Comprobantes 
						SET comprobante = '$Comprobante', TipoComprobant='$TipoComprobante',Retencion='$VrRetencion',NumeroInicial='$NoInicial',
						CruceCtaCero='$VrCuentaCero',Formato='$Formato',
						CompPresupuesto=$CompPresupuestal,CompPresupuestoAdc=$CompPresupuestalAdc,Cierre='$VrCierre', Acarreo='$VrAcarreo', Depreciacion=$VrDepreciacion
						where Comprobante='$OldComprobante' and Compania='$Compania[0]'";
						
						$res = ExQuery($cons);
						echo ExError($res);				
					}			
				}
				
				?>
				<script language="javascript">
					location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>';
				</script>
				<?
			}
			
			if($Editar)
			{
				$cons="Select * from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
				$res=ExQuery($cons);
				$fila=ExFetchArray($res);
				$Comprobante=$fila['comprobante'];
				$TipoComprobante=$fila['tipocomprobant'];$NoInicial=$fila['numeroinicial'];$Formato=$fila['formato'];
				$CompPresupuestal=$fila['comppresupuesto'];$CompPresupuestalAdc=$fila['comppresupuestoadc'];
				$Retencion=$fila['retencion'];$CuentaCero=$fila['crucectacero'];$Cierre=$fila['cierre'];$Acarreo=$fila['acarreo'];
						$Depreciacion = $fila['depreciacion'];
				
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
	
		
			<script language="javascript" src="/Funciones.js"></script>
			<script language="javascript">
			function Validar()
			{
				if (document.FORMA.Comprobante.value == ""){alert("Ingrese un nombre de comprobante");return false;}
				else{if (document.FORMA.TipoComprobante.value == ""){alert("Escoja un Tipo de comprobante");return false;}
					 else{if (document.FORMA.NoInicial.value == ""){alert("Ingrese un Numero Inicial");return false;}
						  else {if (document.FORMA.Formato.value == ""){alert("Ingrese el Formato");return false;}
								else {return true}}}}
			}
			</script>
		</head>	


		<body <?php echo $backgroundBodyMentor; ?>>	
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "COMPROBANTES";
				$rutaarchivo[3] = "COMPROBANTES";											
				$rutaarchivo[4] = "NUEVO";	
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return Validar()">
					<table class="tabla2"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td class='encabezado2Horizontal' colspan="4"> NUEVO COMPROBANTE  </td>
						</tr>	
						<tr>
							<td class='encabezado2VerticalInvertido'>NOMBRE COMPROBANTE</td>
							<td colspan="3">
								<input style="width:300px;" type="text" name="Comprobante" value="<?echo $Comprobante?>"/>
								<input style="width:300px;" type="hidden" name="OldComprobante" value="<?echo $Comprobante?>"/>
							</td>
						<tr>
							<td class='encabezado2VerticalInvertido'>TIPO COMPROBANTE</td>
							<td colspan="3">
								<select name="TipoComprobante">
									<option></option>
										<?
											$cons="Select Tipo from Contabilidad.TiposComprobante";
											$res=ExQuery($cons);
											while($fila=ExFetch($res))
											{
												if($TipoComprobante==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
											if(!$NoInicial){$NoInicial="000001";}
										?>
								</select>
							</td>
						<tr>
							<td class='encabezado2VerticalInvertido'>RETENCI&Oacute;N</td>
								<?if($Retencion=='1'){?><td><input type="checkbox" checked="yes" name="Retencion"/><?}
								else{?><td><input type="checkbox" name="Retencion"/></td><?}?>
							<td class='encabezado2VerticalInvertido'>CUENTA CERO</td>
								<?if($CuentaCero==1){?><td><input type="checkbox" checked="yes" name="CuentaCero"/><?}
								else{?><td><input type="checkbox" name="CuentaCero"/></td>
								
						</tr><?}?>

						<tr>
							<td class='encabezado2VerticalInvertido'>CIERRE FISCAL</td>
							<?if($Cierre==1){?><td><input type="checkbox" checked="yes" name="Cierre"/><?}
							else{?><td><input type="checkbox" name="Cierre"/></td><?}?>

							<td class='encabezado2VerticalInvertido'>ACARREO</td>
								<?if($Acarreo==1){?><td><input type="checkbox" checked="yes" name="Acarreo"/><?}
								else{?><td><input type="checkbox" name="Acarreo"/></td>
						</tr><?}?>


						<tr>
							<td class='encabezado2VerticalInvertido'>N&Uacute;MERO INICIAL</td>
							<td><input type="text" style="width:100px;" maxlength="6" name="NoInicial" value="<?echo $NoInicial?>"/></td>
							<td class='encabezado2VerticalInvertido'>DEPRECIACI&Oacute;N</td>
							<td>
								<input type="checkbox"
							   <? if($Depreciacion==1){echo " checked ";}?>
							   name="Depreciacion" title="Comprobante para depreciacion de infraestructura"/></td>

						</tr>
						<tr>
							<td class='encabezado2VerticalInvertido'>FORMATO</td>
							<td colspan="3">
								<select name="Formato">
								<?
									$RutaRoot=$_SERVER['DOCUMENT_ROOT'];
									$midir=opendir("$RutaRoot/Informes/Contabilidad/Formatos/");
									while($files=readdir($midir))
									{
										$ext=substr($files,-3);
										if (!is_dir($files))
										{
											$files="Formatos/".$files;
										}
										if($files!="." && $files!=".." && $ext=="php"){
										if($files==$Formato){echo "<option selected value='$files'>$files</option>";}
										else{echo "<option value='$files'>$files</option>";}}
									  }

								?>
								</select>
						</tr>		
						<tr>
							<td class='encabezado2VerticalInvertido'>COMPROBANTE PRESUPUESTAL</td>
							<td colspan="3">
								<select name="CompPresupuestal">
								<option></option>
									<?
									$cons="Select Comprobante from Presupuesto.Comprobantes where Compania='$Compania[0]'";
									$res=ExQuery($cons);
									while($fila=ExFetch($res))
									{
										if(strtolower($CompPresupuestal)==strtolower($fila[0])){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
										else{echo "<option value='$fila[0]'>$fila[0]</option>";}
									}
									?>
								</select>

							</td>

						<tr>
							<td class='encabezado2VerticalInvertido'>COMPROBANTE PRESUPUESTAL ADICIONAL</td>
					<td colspan="3">
					<select name="CompPresupuestalAdc">
					<option></option>
						<?
						$cons="Select Comprobante from Presupuesto.Comprobantes where Compania='$Compania[0]'";
						$res=ExQuery($cons);
						while($fila=ExFetch($res))
						{
							if(strtolower($CompPresupuestalAdc)==strtolower($fila[0])){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
							else{echo "<option value='$fila[0]'>$fila[0]</option>";}
						}
						?>
					</select>

					</td>
					<input type="hidden" name="Editar" value="<?echo $Editar?>"/>
					</table>
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="submit" value="Guardar" class="boton2Envio" name="Guardar"/>
					<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onClick="location.href='ConfComprobantes.php?DatNameSID=<? echo $DatNameSID?>&';"/>
				</form>
			</div>
	</html>	
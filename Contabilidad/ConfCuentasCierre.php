		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if(!$AnioSel){$AnioSel=$ND[year];}
			if($Guardar)
			{
				$cons="Select * from Contabilidad.CuentasCierre where Compania='$Compania[0]' and Anio='$AnioSel'";
				$res=ExQuery($cons);
				if(ExNumRows($res)>=1)
				{
					$cons2="Update Contabilidad.CuentasCierre set Ingresos='$Ingresos',Gastos='$Gastos',Utilidad='$Utilidad',Perdida='$Perdida',Costos='$Costos' where Compania='$Compania[0]' and Anio='$AnioSel'";
				}
				else
				{
					$cons2="Insert into Contabilidad.CuentasCierre (Ingresos,Gastos,Utilidad,Perdida,Anio,Compania,Costos) values ('$Ingresos','$Gastos','$Utilidad','$Perdida','$AnioSel','$Compania[0]','$Costos')";
				}
				$res2=ExQuery($cons2);
				echo ExError($res2);
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
		</head>
		<body <?php echo $backgroundBodyMentor; ?>>	
			<?php
				$rutaarchivo[0] = "CONTABILIDAD";
				$rutaarchivo[1] = "CONFIGURACION";
				$rutaarchivo[2] = "CUENTAS CONTABLES";
				$rutaarchivo[3] = "CUENTAS DE CIERRE";
										
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv1Mentor; ?> class="div1">
						<script language="JavaScript">
							function Validar()
							{
								if(document.FORMA.TipoIngresos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
								if(document.FORMA.TipoGastos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
								if(document.FORMA.TipoUtilidad.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
								if(document.FORMA.TipoPerdida.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
								if(document.FORMA.TipoCostos.value!="Detalle"){alert("Las cuentas deben ser de detalle unicamente");return false;}
							}
							
						</script>
					<form name="FORMA" onSubmit="return Validar()">
							<table border="0">
								<tr>
									<td>
										<table class="tabla1"   <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
											<tr>
												<td class='encabezado1Horizontal'>A&Ntilde;O</td>
												<td class='encabezado1Horizontal' >
													<select name="AnioSel" onChange="document.FORMA.submit();">
														<?	

															$cons="Select Anio from Central.Anios where Compania='$Compania[0]' order by Anio desc";
															$res=ExQuery($cons);
															while($fila=ExFetch($res))
															{
																if($AnioSel==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
																else{echo "<option value='$fila[0]'>$fila[0]</option>";}
															}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td class='encabezado1HorizontalInvertido'>CONCEPTO</td>
												<td class='encabezado1HorizontalInvertido'>CUENTA</td>
											</tr>
											<?

											$cons2="Select Ingresos,Gastos,Utilidad,Perdida,Costos from Contabilidad.CuentasCierre where Anio='$AnioSel' and Compania='$Compania[0]'";
											$res2=ExQuery($cons2);
											$fila2=ExFetch($res2);

											$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[0]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoIngresos=$fila3[0];
											$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[1]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoGastos=$fila3[0];
											$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[2]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoUtilidad=$fila3[0];
											$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[3]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoPerdida=$fila3[0];
											$res3=ExQuery("Select Tipo from Contabilidad.PlanCuentas where Cuenta='$fila2[4]' and Compania='$Compania[0]' and Anio=$AnioSel");$fila3=ExFetch($res3);$TipoCostos=$fila3[0];
											?>
											
											<tr>
												<td class="encabezado1VerticalInvertido">INGRESOS</td>
												<td><input type='Text' name='Ingresos' value="<?echo $fila2[0]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Ingresos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Ingresos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td>
											</tr>
											<tr>
												<td class="encabezado1VerticalInvertido">GASTOS</td>
												<td><input type='Text' name='Gastos' value="<?echo $fila2[1]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Gastos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Gastos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td>
											</tr>
											<tr>
												<td class="encabezado1VerticalInvertido">COSTOS</td>
												<td><input type='Text' name='Costos' value="<?echo $fila2[4]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Costos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Costos&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td>
											</tr>
											<tr>
												<td class="encabezado1VerticalInvertido">UTILIDAD</td>
												<td><input type='Text' name='Utilidad' value="<?echo $fila2[2]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Utilidad&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Utilidad&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td>
											</tr>
											<tr>
											<td class="encabezado1VerticalInvertido">P&Eacute;RDIDA</td>
											<td><input type='Text' name='Perdida' value="<?echo $fila2[3]?>" onFocus="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Perdida&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Objeto=Perdida&Tipo=CuentasCierre&Cuenta='+this.value+'&Anio='+document.FORMA.AnioSel.value"></td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;"> <input type="Submit" class="boton2Envio" value="Guardar" name="Guardar"> </td>
										
										</tr>

										<input type="Hidden" name="TipoIngresos" value="<?echo $TipoIngresos?>">
										<input type="Hidden" name="TipoGastos" value="<?echo $TipoGastos?>">
										<input type="Hidden" name="TipoCostos" value="<?echo $TipoCostos?>">
										<input type="Hidden" name="TipoUtilidad" value="<?echo $TipoUtilidad?>">
										<input type="Hidden" name="TipoPerdida" value="<?echo $TipoPerdida?>">
										<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
										
										
									</table>
						
								</td>
								<td>
								<iframe id="Busquedas" name="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
								</td>
							</tr>
						</table>
					</form>
				</div>	
		</body>
	</html>	
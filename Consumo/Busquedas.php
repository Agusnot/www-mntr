		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
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
			
		<body>
			<table height="100%" rules="groups"  width="100%" class="tabla1" style="vertical:align:text-top;"  <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; echo $cellspacingTabla1Mentor; echo $cellpaddingTabla1Mentor; ?>>
					<tr>
						<td class="encabezado1Horizontal" height="15px">ASISTENTE DE B&Uacute;SQUEDA</td>
					</tr>
					<tr><td>
					<?
						if(!$Tipo){
							echo "<div align='center' class='mensaje1'>Haga clic sobre un parámetro de b&uacute;squeda</div>";
						}
						else{
									if($Tipo=="Retenciones")
							{?>
							
								<script language="javascript">
									function CalculaRetencion(Concepto,Porc,Base)
									{
										if(parent.frames.NuevoMovimiento.FORMA.TotCosto.value=="")
										{
											alert("Seleccione primero el producto y la cantidad a ingresar!");
										}
										else
										{
											parent.frames.NuevoMovimiento.FORMA.VrReteFte.value=((parent.frames.NuevoMovimiento.FORMA.TotCosto.value*Base/100)*Porc)/100;
											parent.frames.NuevoMovimiento.FORMA.PorcReteFte.value=Porc;
											parent.frames.NuevoMovimiento.FORMA.ConceptoReteFte.value=Concepto;
											
										}
									}
								</script>	
					<?			echo "<span style='font-weight'>Aplicar Retenciones</span><br><br>";?>

								<a href="#" onclick="CalculaRetencion('','0','')"><li><? echo "Sin Retencion" ?></li></a><br>
					<?			$cons="Select Concepto,Porcentaje,Base from Contabilidad.BasesRetencion where Compania='$Compania[0]' and Anio=$Anio and Consumo=1";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{?>
									<a href="#" onclick="CalculaRetencion('<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo $fila[2]?>')"><li><? echo "$fila[0] ($fila[1])" ?></li></a><br>
					<?			}
							}
						
							if($Tipo=="Cedula"){
							$cons="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion from Central.Terceros where Identificacion='$Cedula' and Compania='$Compania[0]'";
							$res=ExQuery($cons);
							echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
							echo "Criterio <span='style:font-weight:bold;'>$Cedula</span><br>";
							echo "Registros Encontrados (" . ExNumRows($res) . ")";
							$fila=ExFetch($res);
							echo "<li>".strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")."</li>";
								echo "<br>";?>
							<a onclick="open('NuevoTercero.php?Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
							<script language="JavaScript">
							parent.document.FORMA.Tercero.value="<? echo strtoupper("$fila[0] $fila[1] $fila[2] $fila[3]")?>";
							parent.document.FORMA.Cedula.value="<?echo $fila[4]?>";
							parent.document.FORMA.Detalle.focus();
							</script>
					<?
							}
							
							if($Tipo=="Nombre")
							{
								$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where (PrimApe || ' ' || SegApe || ' ' || PrimNom || ' ' || SegNom) ilike '%$Nombre%' and Terceros.Compania='$Compania[0]' Order By PrimApe,SegApe,PrimNom,SegNom";
								$res=ExQuery($cons);echo ExError();
								echo "B&uacute;squeda por identificaci&oacute;n de tercero<br>";
								echo "Criterio <strong>$Nombre</strong><br>";
								echo "Registros Encontrados (" . ExNumRows($res) . ")";
								while($fila=ExFetch($res))
								{
									if(ExNumRows($res)==1){?><script language="javascript">location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID;?>&Tipo=Cedula&Cedula=<? echo $fila[0]?>'</script><? }
									echo "<li><a href='Busquedas.php?DatNameSID=$DatNameSID&Tipo=Cedula&Cedula=$fila[0]'>".strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]")."</a></li>";
								}
								echo "<br>";?>
								<a onclick="open('NuevoTercero.php?Cerrar=1','','width=950,height=550,scrollbars=yes')" href="#">Nuevo Tercero</a>
					<?		}

							if($Tipo=="CC")
							{
					?>
							<script language="JavaScript">
							function PonerCentro(Codigo,Anual,Mensual,ProyMensual,ProyAnual)
							{
								parent.frames.NuevoMovimiento.document.FORMA.CC.value=Codigo;
								parent.frames.NuevoMovimiento.document.FORMA.PACAnual.value=Anual;
								parent.frames.NuevoMovimiento.document.FORMA.PACMensual.value=Mensual;
								parent.frames.NuevoMovimiento.document.FORMA.PACMensualProy.value=ProyMensual;
								parent.frames.NuevoMovimiento.document.FORMA.PACAnualProy.value=ProyAnual;
								parent.frames.NuevoMovimiento.document.FORMA.Cantidad.focus();
								
							}
							</script>
					<?
								echo "B&uacute;squeda por Centros de Costo<br>";
								$SelMeses=array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
								echo "Criterio <strong>$Centro</strong><br>";
								$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto WHERE Compania='$Compania[0]' and Anio=$Anio Order By Codigo";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
									if($AutoId)
									{
										$cons1="Select " .$SelMeses[$Mes-1] . " 
												from Consumo.PlanCompras 
												where AutoId=$AutoId and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and CentroCostos='$fila[0]' and Anio=$Anio";
										$res1=ExQuery($cons1);echo ExError();
										$fila1=ExFetch($res1);
									
										$cons2="Select sum(Cantidad) 
												from Consumo.Movimiento 
												where AutoId=$AutoId and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and CentroCosto='$fila[0]' and Fecha>='$Anio-01-01' and Fecha<='$Anio-12-31' and Estado='AC'";
										$res2=ExQuery($cons2);
										$fila2=ExFetch($res2);

									
										$cons3="Select sum(Cantidad) from Consumo.Movimiento where AutoId=$AutoId and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Estado='AC' and CentroCosto='$fila[0]' and date_part('year',Fecha)=$Anio and date_part('month',Fecha)=$Mes";
										$res3=ExQuery($cons3);
										$fila3=ExFetch($res3);
									
										$cons4="Select Ene+Feb+Mar+Abr+May+Jun+Jul+Ago+Sep+Oct+Nov+Dic 
												from Consumo.PlanCompras 
												where AutoId=$AutoId and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and CentroCostos='$fila[0]' and Anio=$Anio";
										$res4=ExQuery($cons4);echo ExError();
										$fila4=ExFetch($res4);
									}
									if($fila[2]=="Titulo")
									{
									echo "$fila[0] - $fila[1]<br>";
									}
									if($fila[2]=="Detalle"){
								?>
									<a style='font-size:11px;' href='#' onclick="PonerCentro('<? echo $fila[0]?>','<? echo $fila2[0]?>','<? echo $fila3[0]?>','<? echo $fila1[0]?>','<? echo $fila4[0]?>')"><? echo "$fila[0] - $fila[1]"?></a><br>
					<?				}
								}
							}

							if($Tipo=="NomProducto")
							{
								echo "B&uacute;squeda por Nombre de Producto<br>";
								echo "Criterio <strong>$Nombre</strong><br>";
								$cons="Select Codigo1,NombreProd1,Presentacion,UnidadMedida,AutoId from Consumo.CodProductos WHERE 
								Compania='$Compania[0]' and (NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) ilike '$Nombre%' and AlmacenPpal='$AlmacenPpal' 
								and Estado='AC'
								and Anio=$Anio Order By Codigo1";

								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{?>
									<a href="Busquedas.php?DatNameSID=<? echo $DatNameSID;?>&Editar=<? echo $Editar?>&Numero=<? echo $Numero?>&TipoMov=<? echo $TipoMov?>&Tipo=CodProducto&Tarifario=<?echo $Tarifario?>&Anio=<?echo $Anio?>&Fecha=<?echo $Fecha?>&Codigo=<?echo $fila[0]?>&AlmacenPpal=<?echo $AlmacenPpal?>"><?echo "$fila[1] $fila[2] $fila[3]"?><br></a>
								<? }
										
							}
									if($Tipo=="INVIMA")
									{
										?>
										<script language="Javascript">
											function PonerDatosTecnicos(RegINVIMA,Laboratorio,Presentacion)
											{
												<?
												if(!$Factura)
												{
												?>
													parent.frames.FrameOpener.document.FORMA.RegINVIMA.value=RegINVIMA;
													parent.frames.FrameOpener.document.FORMA.ValidaINVIMA.value="1";
													parent.frames.FrameOpener.document.FORMA.Laboratorio.value=Laboratorio;
													parent.frames.FrameOpener.document.FORMA.Presentacion.value=Presentacion;
												<?    
												}
												else
												{
												?>    
													parent.frames.FrameOpener.document.getElementById("RegINVIMA-<?echo "$Factura-$Numero";?>").value=RegINVIMA;
													parent.frames.FrameOpener.document.getElementById("ValidaINVIMA-<?echo "$Factura-$Numero";?>").value="1";
													parent.frames.FrameOpener.document.getElementById("Laboratorio-<?echo "$Factura-$Numero";?>").value=Laboratorio;
													parent.frames.FrameOpener.document.getElementById("Presentacion-<?echo "$Factura-$Numero";?>").value=Presentacion;
												<?
												}
												?>
												
											}
										</script>
										<?
										echo "B&uacute;squeda por registro INVIMA<br>";
										echo "Criterio <strong>$RegINVIMA</strong><br>";
										$cons = "Select RegINVIMA,Laboratorio,Presentacion,cum from consumo.CUMSxProducto
											Where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and AutoId=$AutoId
											and RegInvima like '$RegINVIMA%'";
										$res = ExQuery($cons);
										while($fila=ExFetch($res))
										{
											?>
											<a href="#" onclick="PonerDatosTecnicos('<?echo $fila[0]?>','<?echo $fila[1]?>','<?echo $fila[2]?>')">
												<? echo "<b>$fila[0]</b> <i>($fila[1]-$fila[2]-CUM:$fila[3])</i>";?><br>
											</a>
											<?
										}
										?><br><input type="button" class="boton2Envio" name="ConfCUM" value="Configurar nuevo CUM"
											   onclick="parent.frames.FrameOpener.location.href='ConfCUMxProducto.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AutoId=<? echo $AutoId?>&AlmacenPpal=<? echo $AlmacenPpal?>'" /><?
									}
							if($Tipo=="Unidad")
							{
								?>
								<script language="javascript">
								function PonerUnidadMedida(Objeto,UM,Tipo)
									{
										parent.document.FORMA.EUniMed.value=1;
										parent.document.getElementById("<? echo $Objeto?>").value=UM;
										parent.document.getElementById("<? echo $Objeto?>").focus();
										parent.document.FORMA.Presentacion.focus();
									}
								</script>
							<?  
								echo "B&uacute;squeda por Unidad de Medida<br>";
								echo "Criterio <strong>$UnidadMedida</strong><br>";
								$cons="Select Unidad from Consumo.UnidadMedida WHERE 
								Unidad like '$UnidadMedida%' and Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" onclick="PonerUnidadMedida('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
								}
								?><input type="button" class="boton2Envio" name="Crear" value="Nueva Unidad" 
								onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=UnidadMedida&Campo=Unidad&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
							}
							if($Tipo=="Presentacion")
							{
								?>
								<script language="javascript">
								function PonerPresentacion(Objeto,Pres,Tipo)
									{
										parent.document.FORMA.EPresentacion.value=1;
										parent.document.getElementById("<? echo $Objeto?>").value=Pres;
										parent.document.FORMA.TipoPro.focus();
									}
								</script>
							<?  
								echo "B&uacute;squeda por Presentacion<br>";
								echo "Criterio <strong>$Presentacion</strong><br>";
								$cons="Select Presentacion from Consumo.PresentacionProductos WHERE 
								Presentacion like '$Presentacion%' and Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" onclick="PonerPresentacion('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
								}
								?><input type="button" name="Crear" class="boton2Envio" value="Nueva Presentacion" 
								onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=PresentacionProductos&Campo=Presentacion&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
							}
									/////////////////////////////LABORATORIOS FARMACEUTICOS//////////
									if($Tipo=="Laboratorio")
							{
								echo "B&uacute;squeda por Laboratorio Farmaceutico<br>";
								echo "Criterio <strong>$Laboratorio</strong><br>";
								$cons="Select Laboratorio from Consumo.Laboratorios WHERE
								Laboratorio ilike '$Laboratorio%' and Compania='$Compania[0]'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" 
											onclick="<?echo "$Objeto.value='$fila[0]'"?>;
											<?echo "$Validar.value=1"?>;
											<?echo "$Enfocar.focus()"?>;"><? echo $fila[0]?></a><br><?
								}
								
							}
									/////////////////////////////////////////////////////////////////
									/////////////////////////////LABORATORIOS FARMACEUTICOS (PRESENTACIONES)//////////
									if($Tipo=="PresentacionL")
							{
								echo "B&uacute;squeda por Presentaciones<br>";
								echo "Criterio <strong>$Presentacion</strong><br>";
								$cons="Select Presentacion from Consumo.PresentacionLabs WHERE
								Presentacion ilike '$Presentacion%' and Compania='$Compania[0]'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#"
											onclick="<?echo "$Objeto.value='$fila[0]'"?>;
											<?echo "$Validar.value=1"?>;
											<?echo "$Enfocar.focus()"?>;"><? echo $fila[0]?></a><br><?
								}

							}
									/////////////////////////////////////////////////////////////////
							if($Tipo=="TipoProducto")
							{
								?>
								<script language="javascript">
								function PonerTipoProducto(Objeto,TP,Tipo)
									{
										parent.document.FORMA.ETipoPro.value=1;
										parent.document.getElementById("<? echo $Objeto?>").value=TP;
										parent.document.FORMA.Grupo.focus();
									}
								</script>
							<?  
								echo "B&uacute;squeda por Tipo de Producto<br>";
								echo "Criterio <strong>$TipoProducto</strong><br>";
								$cons="Select TipoProducto from Consumo.TiposProducto WHERE 
								TipoProducto ilike '$TipoProducto%' and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" onclick="PonerTipoProducto('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
								}
								?><input type="button" name="Crear" class="boton2Envio" value="Nuevo Tipo de Producto" onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=TiposProducto&Campo=TipoProducto&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
							}
							if($Tipo=="Bodega")
							{
								?>
								<script language="javascript">
								function PonerBodega(Objeto,Bod,Tipo)
									{
										parent.document.FORMA.EBodega.value=1;
										parent.document.getElementById("<? echo $Objeto?>").value=Bod;
										parent.document.FORMA.Estante.focus();
									}
								</script>
							<?  
								echo "B&uacute;squeda por Bodegas<br>";
								echo "Criterio <strong>$Bodega</strong><br>";
								$cons="Select Bodega from Consumo.Bodegas WHERE 
								Bodega ilike '$Bodega%' and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" onclick="PonerBodega('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
								}
								?><input type="button" name="Crear" class="boton2Envio" value="Nueva Bodega" onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=Bodegas&Campo=Bodega&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
							}
							if($Tipo=="Clasificacion")
							{
								?>
								<script language="javascript">
								function PonerClasificacion(Objeto,Clas,Tipo)
									{
										parent.document.FORMA.EClasificacion.value=1;
										parent.document.getElementById("<? echo $Objeto?>").value=Clas;
										parent.Ocultar();
									}
								</script>
							<?  
								echo "B&uacute;squeda por Clasificaci&oacute;n<br>";
								echo "Criterio <strong>$Clasificacion</strong><br>";
								$cons="Select Clasificacion from Consumo.Clasificaciones WHERE 
								Clasificacion ilike '$Clasificacion%' and AlmacenPpal = '$AlmacenPpal' and Compania='$Compania[0]'";
								$res=ExQuery($cons);
								while($fila=ExFetch($res))
								{
								?><a href="#" onclick="PonerClasificacion('<? echo $Objeto ?>','<? echo $fila[0]?>','<? echo $Tipo?>')"><? echo $fila[0]?></a><br><?	
								}
								?><input type="button" name="Crear" class="boton2Envio" value="Nueva Clasificacion" onclick="frames.parent.NuevoElemento('NewConfigConsumo.php?DatNameSID=<? echo $DatNameSID?>&VienedeOtro=1&Objeto=<? echo $Objeto?>&Tabla=Clasificaciones&Campo=Clasificacion&AlmacenPpal=<? echo $AlmacenPpal?>')" /><?		
							}
							if($Tipo=="NombreProducto")
							{
								$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,$Fecha);
								$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
								$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
								$VrEntradasAnuales=Entradas($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
								$VrSalidasAnuales=Salidas($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
											$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$Fecha);
											$VrDevolucionesAnuales=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
								?>
								<script language="javascript">
									function PonerProducto(Auto,Cod,Pro,Maxp,Minp,CF,CFA,VrC)
									{
										parent.frames.Productos.document.FORMA.<? echo $Objeto?>.value = Pro;
										<? if($Objeto!="NombreD")
										{?>
											parent.frames.Productos.document.FORMA.AutoId.value = Auto;
											parent.frames.Productos.document.FORMA.Codigo.value = Cod;
											
											parent.frames.Productos.document.FORMA.MaxO.value = Maxp;
											parent.frames.Productos.document.FORMA.MinO.value = Minp;
											parent.frames.Productos.document.FORMA.ExistCorteO.value = CF;
											parent.frames.Productos.document.FORMA.ExistAnualO.value = CFA;
											parent.frames.Productos.document.FORMA.VrCosto.value = VrC;  
											parent.frames.Productos.document.FORMA.Cantidad.focus();
										<? }
										else
										{ ?>
											parent.frames.Productos.document.FORMA.AutoIdD.value = Auto;
											parent.frames.Productos.document.FORMA.CodigoD.value = Cod;
											<?
											if($Objeto=="NombreD")
											?>
											parent.frames.Productos.document.FORMA.MaxD.value = Maxp;
											parent.frames.Productos.document.FORMA.MinD.value = Minp;
											parent.frames.Productos.document.FORMA.ExistCorteD.value = CF;
											parent.frames.Productos.document.FORMA.ExistAnualD.value = CFA;
										<? } ?>
										
										parent.Ocultar();
									}
								</script>
								<?
								echo "B&uacute;squeda por Nombre de Producto<br>";
								echo "Criterio <strong>$NomProducto</strong><br>";
								$cons="Select  distinct(AutoId),Codigo1,NombreProd1,UnidadMedida,Presentacion,Max,Min  from Consumo.CodProductos WHERE 
								Compania='$Compania[0]' and (NombreProd1 || ' ' || UnidadMedida || ' ' || Presentacion) ilike '$NomProducto%' and AlmacenPpal='$AlmacenPpal'
								and Estado='AC' and Anio=$Anio";
								$res=ExQuery($cons);echo ExError();	
								while($fila=ExFetch($res))
								{
								$CantFinal=$VrSaldoIni[$fila[0]][0]+$VrEntradas[$fila[0]][0]-$VrSalidas[$fila[0]][0]+$VrDevoluciones[$fila[0]][0];
								$SaldoFinal=$VrSaldoIni[$fila[0]][1]+$VrEntradas[$fila[0]][1]-$VrSalidas[$fila[0]][1]+$VrDevoluciones[$fila[0]][1];
								if($CantFinal>0){$VrCosto=$SaldoFinal/$CantFinal;}else{$VrCosto=0;}
								$CantFinalAnual=$VrSaldoIni[$fila[0]][0]+$VrEntradasAnuales[$fila[0]][0]-$VrSalidasAnuales[$fila[0]][0]+$VrDevolucionesAnuales[$fila[0]][0];
								
								?><a href="#" onclick="PonerProducto('<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo "$fila[2] $fila[3] $fila[4]" ?>','<? echo $fila[5]?>','<? echo $fila[6]?>','<? echo $CantFinal?>','<? echo $CantFinalAnual?>','<? echo $VrCosto?>')"><? echo "$fila[2] $fila[3] $fila[4]"?></a><br><?	
								}
							}
							if($Tipo=="CodProducto")
							{
								echo "B&uacute;squeda por Codigo de Producto<br>";
								echo "Criterio <strong>$Codigo</strong><br>";
								$cons="Select Codigo1,NombreProd1,Presentacion,UnidadMedida,AutoId,VrIva,Min,Max,Grupo from Consumo.CodProductos WHERE 
								Compania='$Compania[0]' and Codigo1 = '$Codigo' and AlmacenPpal='$AlmacenPpal' and Anio=$Anio and Estado='AC' Order By Codigo1";
								$res=ExQuery($cons);echo ExError();
								if(ExNumRows($res)==1)
								{
									$VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01");
									$VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01",$Fecha);
									$VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01",$Fecha);
									$VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-".substr($Fecha,5,2)."-01",$Fecha);
									$fila=ExFetch($res);

									$VrSaldoIniAnual=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-01-01");
									$VrEntradasAnual=Entradas($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
									$VrSalidasAnual=Salidas($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
													$VrDevolucionesAnual=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01","$Anio-12-31");
									$ExAnuales=$VrSaldoIniAnual[$fila[4]][0]+$VrEntradasAnual[$fila[4]][0]-$VrSalidasAnual[$fila[4]][0]+$VrDevolucionesAnual[$fila[4]][0];
									
									$cons20="Select ReteFte,ReteICA from Consumo.Grupos where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' and Grupo='$fila[8]'";
									$res20=ExQuery($cons20);
									$fila20=ExFetch($res20);

									if($Editar)
									{
										$consxx="Select Cantidad,TotCosto from Consumo.Movimiento where AutoId='$fila[4]' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]' 
										and TipoComprobante='$TipoMov' and Numero=$Numero and Estado='AC'";
										$resxx=ExQuery($consxx);
										$filaxx=ExFetch($resxx);
										$CantidadProd = $filaxx[0];
										$ValorProd = $filaxx[1];
									}
									$Minimo=$fila[6];$Maximo=$fila[7];
									if($TipoMov=="Salidas")
									{
										$SumCantExistencias=($VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0])+$CantidadProd;
										$SumVrExistencias=($VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1])+$ValorProd;
									}
									else
									{
										$SumCantExistencias=$VrSaldoIni[$fila[4]][0]+$VrEntradas[$fila[4]][0]-$VrSalidas[$fila[4]][0]+$VrDevoluciones[$fila[4]][0];
										$SumVrExistencias=$VrSaldoIni[$fila[4]][1]+$VrEntradas[$fila[4]][1]-$VrSalidas[$fila[4]][1]+$VrDevoluciones[$fila[4]][1];
									}
									
									if($SumCantExistencias>0){$PromedioPond=$SumVrExistencias/$SumCantExistencias;}else{$PromedioPond=0;}

									$cons2="Select ValorVenta from Consumo.TarifasxProducto where AutoId=$fila[4] and AlmacenPpal='$AlmacenPpal' 
									and Compania='$Compania[0]' and Tarifario='$Tarifario' and FechaIni<='$Fecha' and FechaFin>='$Fecha'";


									$res2=ExQuery($cons2);
									$fila2=ExFetch($res2);
									?>
									<script language="javascript">
										parent.frames.NuevoMovimiento.document.FORMA.AutoId.value='<? echo $fila[4]?>';
										parent.frames.NuevoMovimiento.document.FORMA.Codigo.value='<? echo $fila[0]?>';
										parent.frames.NuevoMovimiento.document.FORMA.Nombre.value='<? echo "$fila[1] $fila[2] $fila[3]"?>';
										if(parent.document.FORMA.Tipo.value=="Entradas" || parent.document.FORMA.Tipo.value=="Orden de Compra" || parent.document.FORMA.Tipo.value=="Remisiones")
										{
											if(parent.frames.NuevoMovimiento.document.FORMA.Editar.value!=1){
											parent.frames.NuevoMovimiento.document.FORMA.VrCosto.value='0';
											parent.frames.NuevoMovimiento.document.FORMA.VrVenta.value='0';}
										}
										else
										{
											parent.frames.NuevoMovimiento.document.FORMA.VrCosto.value='<? echo $PromedioPond?>';
											parent.frames.NuevoMovimiento.document.FORMA.VrVenta.value='<?echo $fila2[0]?>';
										}
										if(parent.frames.NuevoMovimiento.document.FORMA.Editar.value!=1){
										parent.frames.NuevoMovimiento.document.FORMA.PorcIVA.value='<? echo $fila[5]?>';
										parent.frames.NuevoMovimiento.document.FORMA.PorcICA.value='<? echo $fila20[1]?>';
										parent.frames.NuevoMovimiento.document.FORMA.PorcReteFte.value='<? echo $fila20[0]?>';}

										parent.frames.NuevoMovimiento.document.FORMA.ExAnuales.value='<? echo $ExAnuales?>';
										parent.frames.NuevoMovimiento.document.FORMA.Existencias.value='<? echo $SumCantExistencias?>';
										parent.frames.NuevoMovimiento.document.FORMA.Minimo.value='<?echo $Minimo?>';
										parent.frames.NuevoMovimiento.document.FORMA.Maximo.value='<?echo $Maximo?>';
										
										parent.frames.TotMovimientos.document.FORMA.ExAnuales.value='<? echo $ExAnuales?>';
										parent.frames.TotMovimientos.document.FORMA.Existencias.value='<?echo $SumCantExistencias?>';
										parent.frames.TotMovimientos.document.FORMA.Min.value='<?echo $Minimo?>';
										parent.frames.TotMovimientos.document.FORMA.Max.value='<?echo $Maximo?>';
										parent.frames.NuevoMovimiento.document.FORMA.Cantidad.focus();
									</script>				
								<?
									echo "$fila[0] $fila[1] $fila[2] $fila[3]<br>";
								}
								else
								{
									?>
									<script language="javascript">
										parent.frames.NuevoMovimiento.document.FORMA.Nombre.value='';
									</script>
									<?
								}				
							}

							if($Tipo=="RevisaCierre")
							{
								$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
								$res=ExQuery($cons);
								if(ExNumRows($res)==1)
								{?>
									<script language="JavaScript">
										parent.document.FORMA.MesInvalido.value=1;
									</script>
								<?}
								else
								{?>
									<script language="JavaScript">
										parent.document.FORMA.MesInvalido.value=0;
									</script>
								<? }
								
							}
							
							if($Tipo=="HabilitarComprobantes")
							{
								$cons="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
								$res=ExQuery($cons);
								if(ExNumRows($res)==1)
								{?>
									<script language="JavaScript">
										parent.document.FORMA.Nuevo.disabled=true;
									</script>
									
								<?}
								else
								{ ?>
									<script language="JavaScript">
										parent.document.FORMA.Nuevo.disabled=false;
									</script>
					<?			}
							}
							if($Tipo=="PlanCuentas")
							{
					?>
							<script language="JavaScript">
							function PonerCuenta(Objeto,CuentaConta,Tipo)
							{
								parent.document.getElementById("<? echo $Objeto?>").value=CuentaConta;
								parent.document.getElementById("<? echo $Objeto?>").focus();
								<?
								if($ObjetoValida)
								{
								?>
								parent.document.getElementById("<? echo $ObjetoValida?>").value='1';
								<?	
								}
								?>
							}
							</script>
					<?
								$ND=getdate();
								echo "B&uacute;squeda por plan de cuentas<br>";
								echo "Criterio <strong>$Cuenta</strong><br>";
								$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta like '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
								$res=ExQuery($cons);echo ExError();
								while($fila=ExFetch($res))
								{
									echo "<font style='font-size:10px;'>";
									if($fila[2]=="Detalle"){
										
									?><a href='#' onclick="PonerCuenta('<?echo $Objeto?>',<?echo $fila[0]?>,'<?echo $fila[2]?>')"><?}echo "$fila[0] - $fila[1]"?></a><br><?
								}
							}
						}
					?>
					</td></tr>
			</table>
		</body>
	</html>	
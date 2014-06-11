		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include("ObtenerSaldos.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND = getdate();
			if(!$Anio){ $Anio = $ND[year];}
			$AnioAnt = $Anio - 1;
			/////////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.CodProductos where Anio = $Anio and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayProdAnio=1;}
			/////////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.CodProductos where Anio = $AnioAnt and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayProdAnioAnt=1;}
			///////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.CuentasXCC where Anio = $AnioAnt and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayCtasXCCAnioAnt = 1;}
			///////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.CuentasXCC where Anio = $Anio and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayCtasXCCAnio = 1;}
			///////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.UsuariosXCC where Compania='$Compania[0]' and Anio=$AnioAnt";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayUsuXCCAnioAnt = 1;}
			///////////////////////////////////////////////////////////////////////////////////////////////
			$cons = "Select * from Consumo.UsuariosXCC where Compania='$Compania[0]' and Anio=$Anio";
			$res = ExQuery($cons);
			if(ExNumRows($res)>0){$HayUsuXCCAnio = 1;}
					
			$FechaMod = "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
			if($Cierre)
			{
				$FechaIni = $AnioAnt."-01-01";
				$FechaFin = $AnioAnt."-12-31";
				if($Productos)
				{
					/////////////INSERCION DE GRUPOS DE PRODUCTO///////////////////////////////////////////////////////////////////////////////////////////////
					$cons = "Select Grupo,AlmacenPpal,CtaContable,ReteFte,CTAReteFteE,CTAReteFteS,ReteICA,CTAReteICAE,CTAReteICAS,CTAProveedor,CTAIVAE,CTAIVAS
					from Consumo.Grupos where Anio = $AnioAnt and Compania='$Compania[0]'";
					//echo $cons;
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						//TODAS LAS CUENTAS ENTRAN EN 1
						if(!$fila[3]){$fila[3]=0;}if(!$fila[6]){$fila[6]=0;}
										if(!$fila[2]){$fila[2]=1;}
										if(!$fila[4]){$fila[4]=1;}
										if(!$fila[5]){$fila[5]=1;}
										if(!$fila[7]){$fila[7]=1;}
										if(!$fila[8]){$fila[8]=1;}
										if(!$fila[9]){$fila[9]=1;}
										if(!$fila[10]){$fila[10]=1;}
										if(!$fila[11]){$fila[11]=1;}
										$cons0="Insert into Consumo.Grupos(Compania,Grupo,AlmacenPpal,CtaContable,ReteFte,CTAReteFteE,CTAReteFteS,
						ReteICA,CTAReteICAE,CTAReteICAS,CTAProveedor,CTAIVAE,CTAIVAS,Anio) values
						('$Compania[0]','$fila[0]','$fila[1]','$fila[2]',$fila[3],'$fila[4]','$fila[5]',$fila[6],'$fila[7]','$fila[8]',
										'$fila[9]','$fila[10]','$fila[11]',$Anio)";
						//echo $cons0;
						$res0 = ExQuery($cons0);
					}
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					//////////////INSERCION DE PRODUCTOS///////////////////////////////////////////////////////////////////////////////////////////////////////
					$cons = "Select AlmacenPpal,AutoId,Codigo1,Codigo2,Codigo3,NombreProd1,NombreProd2,UnidadMedida,Presentacion,TipoProducto,
					Grupo,Bodega,Estante,Nivel,UsuarioCre,FechaCre,Estado,Max,Min,VrIVA,ActualizaVenta,Clasificacion,cum,control,somatico,riesgo,reginvima,pos,codsecretaria 
					from Consumo.CodProductos where Compania = '$Compania[0]' and Anio = $AnioAnt";
					//echo $cons;
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if(!$fila[17]){$fila[17]=1000;}if(!$fila[18]){$fila[18]=1;}if(!$fila[19]){$fila[19]=0;}if(!$fila[20]){$fila[20]=0;}
										$FechaMod = "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
						if(!$fila[15]){$fila[15]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}
										if(!$fila[14]){$fila[14]="$usuario[0]";}
										$cons0 = "Insert into Consumo.CodProductos (Compania,AlmacenPpal,AutoId,Codigo1,Codigo2,Codigo3,NombreProd1,NombreProd2,
						UnidadMedida,Presentacion,TipoProducto,Grupo,Bodega,Estante,Nivel,UsuarioCre,FechaCre,UsuarioMod,FechaUltMod,
						Estado,Max,Min,VrIVA,ActualizaVenta,Clasificacion,Anio,cum,control,somatico,riesgo,reginvima,pos,codsecretaria) values
						('$Compania[0]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]',
										'$fila[7]','$fila[8]','$fila[9]','$fila[10]','$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$usuario[0]','$FechaMod',
										'$fila[16]','$fila[17]','$fila[18]','$fila[19]','$fila[20]','$fila[21]',$Anio,'$fila[22]','$fila[23]','$fila[24]','$fila[25]','$fila[26]','$fila[27]','$fila[28]')";
						//echo $cons0;
						$res0 = ExQuery($cons0);
						////////////////////////////////////SALDOS INICIALES////////////////////////////////////////////////////////////////////////////////////
						if($SaldosIniciales)
						{
							$VrSaldoIni=SaldosIniciales($AnioAnt,$fila[0],$FechaIni);
							$VrEntradas=Entradas($AnioAnt,$fila[0],$FechaIni,$FechaFin);
							$VrSalidas=Salidas($AnioAnt,$fila[0],$FechaIni,$FechaFin);
						
							$CantFinal=$VrSaldoIni[$fila[1]][0]+$VrEntradas[$fila[1]][0]-$VrSalidas[$fila[1]][0];
							$SaldoFinal=$VrSaldoIni[$fila[1]][1]+$VrEntradas[$fila[1]][1]-$VrSalidas[$fila[1]][1];
							if($CantFinal>0){$CostoUnidad=$SaldoFinal/$CantFinal;}else{$CostoUnidad=0;}
							if(!$CantFinal || $CantFinal<0)    {$CantFinal = 0;} 
							if(!$SaldoFinal || $SaldoFinal<0)  {$SaldoFinal=0;}
							if(!$CostoUnidad || $CostoUnidad<0){$CostoUnidad=0;}
							$cons0 = "Insert into Consumo.SaldosInicialesXAnio(Compania,AlmacenPpal,AutoId,Anio,Cantidad,VrUnidad,VrTotal)
							values ('$Compania[0]','$fila[0]',$fila[1],$Anio,$CantFinal,$CostoUnidad,$SaldoFinal)";
							//echo $cons0;
							$res0 = ExQuery($cons0);
						}
						///////////////////////////////////PRODUCTOS X CONTRATO///////////////////////////////////////////////////////////////////////////////////
						if($ProductosXContrato)
						{
							$cons0 = "Select NumeroContrato,Cantidad,ValorUnidad from Consumo.ProductosXContrato where AlmacenPpal='$fila[0]'
							and AutoId=$fila[1] and Anio=$AnioAnt";
							//echo $cons0;
							$res0 = ExQuery($cons0);
							while($fila0 = ExFetch($res0))
							{
								$cons1 = "Insert into Consumo.ProductosXContrato (Compania,AlmacenPpal,NumeroContrato,AutoId,Cantidad,ValorUnidad,Anio)
								values ('$Compania[0]','$fila[0]','$fila1[0]',$fila[1],'$fila1[1]','$fila1[2]',$Anio)";
								$res1 = ExQuery($cons1);
							}
						}
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
				///////////////////////////////////////////ItemsXGrupo//////////////////////////////////////////////////////////////////////////////////////////
				if($ItemsXGrupo)
				{
					$cons = "Select AlmacenPpal,Grupo,Item from Consumo.ItemsXGrupo where Anio=$AnioAnt and Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						$cons0 = "Insert into Consumo.ItemsXGrupo (Compania,AlmacenPpal,Grupo,Item,Anio) values
						('$Compania[0]','$fila[0]','$fila[1]','$fila[2]',$Anio)";
						$res0 = ExQuery($cons0);
					}
				}
				///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//////////////////////////////////////////CRITERIOS X GRUPO////////////////////////////////////////////////////////////////////////////////////
				if($CriteriosXGrupo)
				{
					$cons = "Select AlmacenPpal,Grupo,Criterio,Peso,Minimo,Tipo,Completo from Consumo.CriteriosXGrupo
					where Anio=$AnioAnt and Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						$cons0="Insert into Consumo.CriteriosXGrupo (Compania,AlmacenPpal,Grupo,Criterio,Peso,Minimo,Tipo,Completo,Anio)
						values ('$Compania[0]','$fila[0]','$fila[1]','$fila[2]',$fila[3],$fila[4],'$fila[5]','$fila[6]',$Anio)";
						$res0 = ExQuery($cons0);
					}
				}
				///////////////////////////////////CUENTAS X CENTRO DE COSTO ///////////////////////////////////////////////////////////////////////////////
				if($CtasXCC)
				{
					$cons = "Select AlmacenPpal,CentroCostos,Cuenta,Grupo from Consumo.CuentasXCC where Anio = $AnioAnt and Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						$cons0="Insert into Consumo.CuentasXCC (Compania,AlmacenPpal,CentroCostos,Cuenta,Anio,Grupo)
						values ('$Compania[0]','$fila[0]','$fila[1]','$fila[2]',$Anio,'$fila[3]')";
						$res0=ExQuery($cons0);
					}
				}
				///////////////////////////////////////USUARIOS X CENTRO DE COSTO ///////////////////////////////////////////////////////////////////////////
				if(UsuariosXCC)
				{
					$cons = "Select Usuario,CC from Consumo.UsuariosXCC where Compania='$Compania[0]' and Anio=$AnioAnt and Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						$cons0 = "Insert into Consumo.UsuariosXCC (Compania,Usuario,CC,Anio)
						values ('$Compania[0]','$fila[0]','$fila[1]',$Anio)";
						$res0 = ExQuery($cons0);
					}
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				?><script language="javascript">
					alert("El cierre se ha realizado con exito");
					location.href = "CierreFiscalConsumo.php?DatNameSID=<? echo $DatNameSID?>";
				</script><?
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
				function ColocarChek(obj)
				{
					if(obj.checked==true)
					{
						for(i=1;i<=4;i++)
						{
							document.getElementById(i).disabled = false;
							document.getElementById(i).checked = true;
						}
					}
					else
					{
						for(i=1;i<=4;i++)
						{
							document.getElementById(i).checked = false;
							document.getElementById(i).disabled = true;
						}
					}
				}
			</script>
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
				$rutaarchivo[0] = "ALMAC&Eacute;N";
				$rutaarchivo[1] = "PROCESOS DE CONSUMO";
				$rutaarchivo[2] = "CIERRE FISCAL";
				mostrarRutaNavegacionEstatica($rutaarchivo);
			?>
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<table class="tabla2" width="300px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
						<tr>
							<td colspan="2" class="encabezado2Horizontal">CIERRE FISCAL</td>
						</tr>
						<!--<tr>
							<td colspan="2" class="encabezado2HorizontalInvertido">NUEVO A&Ntilde;O</td>
						</tr>-->
						<tr>
							<td class="encabezadoGrisaceo" style="text-align:center;" colspan="2">A&Ntilde;O
								<select name="Anio" onChange="FORMA.submit()">
								<?	$cons1 = "Select Anio from Central.Anios where Compania='$Compania[0]' ORDER BY anio DESC LIMIT 20";
									$res1 = ExQuery($cons1);
									while($fila1 = ExFetch($res1))
									{
										if($Anio==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
										else {echo "<option value='$fila1[0]'>$fila1[0]</option>";}
									}
								?></select>
							</td>
						</tr>
						<?
							if($Anio){
							?>
								<tr>
									<td colspan="2">
										<table class="tabla2" width="300px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
											<tr>
												<td colspan="2" class="encabezado2HorizontalInvertido">CIERRE FISCAL </td>
											</tr>
											<?
											
											
											if($HayProdAnioAnt)	{
												if(!$HayProdAnio)
												{?>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Productos</td>
													<td><input type="checkbox" name="Productos" id="0" onClick="ColocarChek(this)" /></td>
												</tr>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Saldos Iniciales</td>
													<td><input type="checkbox" name="SaldosIniciales" id="1" disabled /></td>
												</tr>
												<!-- <tr>
													<td>Precios de Venta</td>
													<td><input type="checkbox" name="PreciosVenta" id="2" disabled /></td>
												</tr> -->
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Items por grupo</td>
													<td><input type="checkbox" name="ItemsXGrupo" id="2" disabled /></td>
												</tr>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Criterios por grupo</td>
													<td><input type="checkbox" name="CriteriosXGrupo" id="3" disabled /></td>
												</tr>
												<!-- <tr>
													<td>Criterios X Proveedor</td>
													<td><input type="checkbox" name="CriteriosXProveedor" id="4" disabled /></td>
												</tr> -->
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Productos por contrato</td>
													<td><input type="checkbox" name="ProductosXContrato" id="4" disabled /></td>
												</tr>
												<? }
												else
												{?><script language="javascript">
														alert("Ya existen productos para el <? echo $Anio?>, No se Podra realizar el cierre de PRODUCTOS");
													</script><?	}
											}
											else
											{?><script language="javascript">
													alert("No hay productos para el <? echo $AnioAnt?>, No se Podra realizar el cierre de PRODUCTOS");
												</script><? }
											
											if($HayCtasXCCAnioAnt)	{
												if(!$HayCtasXCCAnio)
												{?>
												<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
												<td>Cuentas por centro de costos</td>
												<td><input type="checkbox" name="CtasXCC" /></td>
												</tr>	
												<? }
												else
												{?><script language="javascript">
														alert("Ya existen cuentas por centro de costos para el <? echo $Anio?>, No se Podra realizar el cierre de Cuentas X Centro de Costo");
													</script><?	}
											}
											else
											{?><script language="javascript">
													alert("No hay cuentas por centro de costos Para el <? echo $AnioAnt?>, No se Podra realizar el cierre de Cuentas X Centro de Costo");
												</script><? }
											
											if($HayUsuXCCAnioAnt){
												if(!$HayUsuXCCAnio){
													?>
													<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
													<td>Usuarios por centro de costos</td>
													<td><input type="checkbox" name="UsuariosXCC" /></td>
													</tr><?
												}
												else
												{?><script language="javascript">
													alert("Ya existen usuarios por centro de costo para el <? echo $Anio?>, No se podra realizar el cierre de usuarios por centros de costo");
													</script><?	}
											}
											else
											{?><script language="javascript">
													alert("No hay usuarios por centro de costos Para el <? echo $AnioAnt?>, No se podra realizar el cierre de usuarios por centros de Costo");
												</script><? }
											?>
										</table>
									</td>
								</tr>
							<?
							}
						?>
					</table>
					<div style="margin-top:15px;margin-bottom:15px">		
						<input type="submit" name="Cierre"  class="boton2Envio" value="Realizar Cierre" />
					</div>	
					
				</form>
			</div>	
		</body>
		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include ("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
				$ND=getdate();
				if(!$Mes){$Mes=$ND[mon];}
				if(!$Dia){$Dia=$ND[mday];}
			//UM:27-04-2011
				$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales Where AlmacenPpal='$AlmacenPrincipal' 
				and Compania='$Compania[0]' and SSFarmaceutico=1";
			   $res = ExQuery($cons);
			   if(ExNumRows($res) > 0){$ApEssf = 1;};
				$cons="Select AutoId from Consumo.CodProductos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPrincipal' and Anio = $Anio Group By AutoId Order By AutoId Desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			if(!$Editar)$AutoId=$fila[0]+1;
			if(!$Codigo){$Codigo=$AutoId;}
			if ($Guardar)	{
				if($pos=="on"){$pos=1;}else{$pos=0;}
						if(!$Editar)
				{
					if($CUM){$adinsertCUM = ",CUM";$advaluesCUM = ",'$CUM'";}
					if($Control){$adinsertCON = ",Control";$advaluesCON = ",'$Control'";}
					if($Somatico){$adinsertSOM = ",Somatico";$advaluesSOM = ",'$Somatico'";}
					if($Riesgo){$adinsertRIE = ",Riesgo";$advaluesRIE = ",'$Riesgo'";}
								if($regINVIMA){$adinsertINVIMA = ",regINVIMA"; $advaluesINVIMA=" ,'$regINVIMA'";}
					$cons = "Insert into Consumo.CodProductos 
					(AlmacenPpal,AutoId,Codigo1,Codigo2,Codigo3,NombreProd1,NombreProd2,UnidadMedida,Presentacion,TipoProducto,
					 Grupo,Bodega,Estante,Nivel,UsuarioCre,FechaCre,Estado,Max,Min,Compania,VrIva,ActualizaVenta,Anio,Clasificacion,codsecretaria,pos
					 $adinsertCUM $adinsertCON $adinsertSOM $adinsertRIE $adinsertINVIMA)
							values 
					('$AlmacenPrincipal','$AutoId','$Codigo','$Codigo2','$Codigo3','$NomPro','$NomPro2','$UniMed','$Presentacion','$TipoPro',
					'$Grupo','$Bodega','$Estante','$Nivel','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Estado',
					$Max,$Min,'$Compania[0]','$VrIva','$ActualizaVenta',$Anio,'$Clasificacion','$codsecretaria',$pos $advaluesCUM $advaluesCON $advaluesSOM $advaluesRIE $advaluesINVIMA)";
					$Editar=1;
				}
				else
				{
					if(!$VrIva){$VrIva = 0;}
					if(!$ActualizaVenta){$ActualizaVenta = 0;}
					$cons = "Update Consumo.CodProductos set AlmacenPpal = '$AlmacenPrincipal', Codigo1='$Codigo', Codigo2='$Codigo2', Codigo3='$Codigo3',NombreProd1='$NomPro',
					NombreProd2='$NomPro2', UnidadMedida='$UniMed', Presentacion='$Presentacion', TipoProducto='$TipoPro', Grupo='$Grupo', Bodega='$Bodega',Estante='$Estante',
					Nivel='$Nivel', UsuarioMod='$usuario[0]', FechaUltMod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]',
					Estado = '$Estado', Max=$Max, Min=$Min,VrIva=$VrIva,ActualizaVenta=$ActualizaVenta, Clasificacion='$Clasificacion', CUM='$CUM', Control='$Control',
					Somatico = '$Somatico', Riesgo = '$Riesgo', pos=$pos, regINVIMA='$regINVIMA',codsecretaria='$codsecretaria'
								where AlmacenPpal='$AlmacenPrincipal' and AutoId=$AutoId and Compania='$Compania[0]' and Anio=$Anio";
				}
				$res=ExQuery($cons);
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
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
					function Validar()
					{
						var b = 0;
							if(FORMA.Codigo.value==""){alert("El Campo Codigo es Obligatorio");b = 1;}
							else{if(FORMA.NomPro.value==""){alert("El Campo Nombre Producto es Obligatorio");b = 1;}
								else{if(FORMA.UniMed.value == "" || FORMA.EUniMed.value == "0"){if(FORMA.UniMed.value == ""){alert("El Campo Unidad de Medida es Obligatorio");b = 1;}
										else{alert("Debe Asegurarse de haber seleccionado un valor de la lista para el campo Unidad de Medida");b=1;}}
									 else{if(FORMA.Presentacion.value=="" || FORMA.EPresentacion.value == "0"){if(FORMA.Presentacion.value==""){alert("El Campo Presentacion es Obligatorio");b = 1;}
											 else{alert("Debe Asegurarse de haber seleccionado un valor de la lista para el campo Presentacion");b=1;}}
										  else{if(FORMA.TipoPro.value=="" || FORMA.ETipoPro.value == "0"){if(FORMA.TipoPro.value==""){alert("El Campo Tipo de Producto es Obligatorio");b = 1;}
												  else{alert("Debe Asegurarse de haber seleccionado un valor de la lista para el campo Tipo de Producto");b=1;}}
											   else{if(FORMA.Grupo.value==""){alert("El Campo Grupo es Obligatorio");b = 1;}else{if(FORMA.Bodega.value=="" || FORMA.EBodega.value == "0"){
															if(FORMA.Bodega.value==""){alert("El Campo Bodega es Obligatorio");b = 1;}
															else{alert("Debe Asegurarse de haber seleccionado un valor de la lista para el campo Bodega");b=1;}}
														else{if((FORMA.Min.value*1)>=(FORMA.Max.value*1)){alert("El Campo Minimo no debe ser Mayor o Igual que el campo Maximo");b=1;}}}}}}}}
							if(FORMA.EClasificacion.value == "0" && FORMA.Clasificacion != "")
							{b=1;alert("Debe Asegurarse de haber seleccionado un valor de la lista para el campo Clasificacion");}
							if(b == 1){return false;}	
					}
					function Cambiar(x)
					{
						if(x==1)
						{
							if(document.FORMA.Iva.checked == true)
							{
								FORMA.VrIva.readOnly = false; FORMA.VrIva.value="<? echo $VrIva?>"; FORMA.VrIva.focus();
							}
							else
							{
								FORMA.VrIva.value="0"; FORMA.VrIva.readOnly = true;
							}
						}
						if(x==4)
						{
							if(document.FORMA.Venta.checked == true)
							{
								FORMA.ActualizaVenta.readOnly = false; 
								FORMA.ActualizaVenta.value="<? echo $ActualizaVenta?>"; 
								FORMA.ActualizaVenta.focus();
							}
							else
							{
								FORMA.ActualizaVenta.value="0"; FORMA.ActualizaVenta.readOnly = true;
							}
						}
					}
					function AbrirLotes(AlmacenPpal,AutoId,Cantidad,Tipo)
					{
						frames.FrameOpener.location.href='Lotes.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal='+AlmacenPpal+'&AutoId='+AutoId+'&Cantidad='+Cantidad+'&Tipo='+Tipo+'&Anio=<? echo $Anio?>';
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='150px';
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='690';
						document.getElementById('FrameOpener').style.height='290';
					}
						function AbrirSaldoInicial()
					{
						frames.FrameOpener.location.href='SaldosInicialesxAnio.php?AutoId=<? echo $AutoId?>&AlmacenPpal=<? echo $AlmacenPrincipal?>&DatNameSID=<? echo $DatNameSID?>';
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='250px';
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='690';
						document.getElementById('FrameOpener').style.height='190';
					}
					function AbrirPreciodeVenta()
					{
						frames.FrameOpener.location.href='ModPrecioVenta.php?DatNameSID=<? echo $DatNameSID;?>&Anio=<? echo $Anio?>&AutoId=<? echo $AutoId?>&AlmacenPpal='+document.FORMA.AlmacenPrincipal.value;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='80px';
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='380';
						document.getElementById('FrameOpener').style.height='400';
					}
						function AbrirCUM()
					{
						frames.FrameOpener.location.href='ConfCUMxProducto.php?DatNameSID=<? echo $DatNameSID;?>&Anio=<? echo $Anio?>&AutoId=<? echo $AutoId?>&AlmacenPpal='+document.FORMA.AlmacenPrincipal.value;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='80px';
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='620';
						document.getElementById('FrameOpener').style.height='400';
					}
					function NuevoElemento(cadena)
					{
						Ocultar();
						frames.FrameOpener.location.href = cadena;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='80px';
						document.getElementById('FrameOpener').style.left='8px';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='380';
						document.getElementById('FrameOpener').style.height='400';
					}
					function Mostrar()
					{
						document.getElementById('Busquedas').style.position='absolute';
						document.getElementById('Busquedas').style.top='50px';
						document.getElementById('Busquedas').style.right='10px';
						document.getElementById('Busquedas').style.display='';
					}
					
					
					function Ocultar()
					{
						document.getElementById('Busquedas').style.display='none';
					}
						function noenter(sigCampo)
						{
							//alert("entra");
							if(window.event && window.event.keyCode == 13)
							{
								document.getElementById(sigCampo).focus();
								return false;
							}
							
						}
				</script>
			</head>
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "FICHA PRODUCTO";	
					if($Editar) {
					$rutaarchivo[2] = "EDITAR PRODUCTO";		
					}
					else {
					$rutaarchivo[2] = "NUEVO PRODUCTO";
					}
										
					
					mostrarRutaNavegacionEstatica($rutaarchivo);					
				
				?>

			<div align="left" style="margin-left:5px;">	
					<?
						if($Editar)	{
							$cons1 = "Select AutoId from Consumo.Movimiento where AutoId = $AutoId and Anio = $Anio and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPrincipal'
							and Comprobante <> 'Orden de Compra' AND Estado = 'AC'";
							$res1=ExQuery($cons1);
							if(ExNumRows($res1)>0){$GrupoDisabled = " disabled title='El Producto tiene Movimiento, No se puede editar este Item' ";
							$SIxAnio = 1; }
							else{$GrupoDisabled=" title='Grupo' ";}
							echo ExError();
							$cons1 = "Select AlmacenPpal, Codigo1, Codigo2, Codigo3, NombreProd1, NombreProd2, UnidadMedida, Grupo, 
							Presentacion, TipoProducto, Bodega, Estante, Nivel, Estado, 
							Max,Min,VrIva,ActualizaVenta,Clasificacion,CUM,Control,Somatico,Riesgo,pos,regINVIMA,codsecretaria
							from Consumo.CodProductos where AutoId = $AutoId and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPrincipal'
							and Anio = $Anio";
							$res1 = ExQuery($cons1);echo ExError();
							$fila1 = ExFetch($res1);
							$AlmacenPrincipal=$fila1[0];$Codigo=$fila1[1];$Codigo2=$fila1[2];$Codigo3=$fila1[3];
							$NomPro=$fila1[4];$NomPro2=$fila1[5];$UniMed=$fila1[6];$Grupo=$fila1[7];$Presentacion=$fila1[8];
							$TipoPro=$fila1[9];$Bodega=$fila1[10];$Estante=$fila1[11];$Nivel=$fila1[12];$Estado=$fila1[13];
							$Max=$fila1[14];$Min=$fila1[15];$VrIva=$fila1[16];
							$ActualizaVenta=$fila1[17];
							if($ActualizaVenta){$CVenta=" checked "; }
							if($VrIva){$CVrIva = " checked ";}
							$Clasificacion=$fila1[18]; $CUM = $fila1[19]; $Control = $fila1[20]; $Somatico = $fila1[21]; $Riesgo = $fila1[22];
									if($fila1[23]==1){$chkPOS = " checked ";}
									$regINVIMA = $fila1[24]; $codsecretaria= $fila1[25];
						}
					?>
							<form name="FORMA" method="get" onSubmit="return Validar();">
								<input type="Hidden" name="EUniMed" >
								<input type="Hidden" name="EPresentacion" >
								<input type="Hidden" name="ETipoPro">
								<input type="Hidden" name="EBodega">
								<input type="Hidden" name="EClasificacion">
								<input type="Hidden" name="Anio" value="<? echo $Anio?>" />
								<input type="Hidden" name="Mes" value="<? echo $Mes?>" />
								<input type="Hidden" name="Dia" value="<? echo $Dia?>" />
								<input type="hidden" name="Grupo" value="<? echo $Grupo?>" />				
								<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
								
								
								<table class="tabla2"  width="820px;"   <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								   <tr>
										<td colspan="6" class="encabezado2Horizontal"> FICHA DE PRODUCTO A&Ntilde;O <? echo $Anio?></td>
									</tr>
								   <tr>
										<td class="encabezado2VerticalInvertido">ALMAC&Eacute;N</td>
										<td colspan="5">
										   <select name="AlmacenPrincipal" style="width:100%" onChange="document.FORMA.submit();" onFocus="Ocultar()">
										   
												<?
												$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
												$res = ExQuery($cons);
												while($fila = ExFetch($res)){
													if($AlmacenPrincipal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
													else{echo "<option value='$fila[0]'>$fila[0]</option>";}
												}
												?>
										   </select>
										   
										</td>
									</tr>
								   <tr>
										<td class="encabezado2VerticalInvertido">C&Oacute;DIGO </td>
										<td>
											<input type="text" value="<? echo $Codigo?>" name="Codigo" id="Codigo" style="width:100%" onFocus="Ocultar()" onKeyUp="xNumero(this);" onKeyDown="xNumero(this);return noenter('Codigo2');" onBlur="campoNumero(this)" />  									</td>
										<td class="encabezado2VerticalInvertido">
										<?
											if($ApEssf){echo "C&Oacute;DIGO ATC";}
											else{echo "C&Oacute;DIGO 2:";}
											?>  
										</td>
										<td>
											<input type="text" value="<? echo $Codigo2?>" name="Codigo2" id="Codigo2" style="width:100%" onFocus="Ocultar()" onKeyUp="xLetra(this);" onKeyDown="xLetra(this);return noenter('Codigo3');"/>   
										</td>
										<td class="encabezado2VerticalInvertido">C&Oacute;DIGO 3 </td>
										<td>
											<input type="text" value="<? echo $Codigo3?>" name="Codigo3" style="width:100%" onFocus="Ocultar()"	onKeyUp="xLetra(this)" onKeyDown="xLetra(this);return noenter('NomPro');"/>     
										</td>
								   </tr>
								   <tr>
									   <td class="encabezado2VerticalInvertido">NOMBRE PRODUCTO</td>
									   <td  colspan="5">
										   <input type="text" value="<? echo $NomPro?>" name="NomPro" id="NomPro" maxlength="100" style="width:100%;" onFocus="Ocultar()" onKeyUp="xLetra(this)" onKeyDown="xLetra(this);return noenter('NomPro2');"  /> 
										</td>
								   </tr>
								   <tr>
									   <td class="encabezado2VerticalInvertido">NOMBRE PRODUCTO 2</td>
									   <td colspan="5">
										   <input type="text" value="<? echo $NomPro2?>"  name="NomPro2" id="NomPro2" maxlength="100" style="width:100%;" onFocus="Ocultar()"
										   onKeyUp="xLetra(this)" onKeyDown="xLetra(this);return noenter('UniMed');"/>           </td>
								   </tr>
								   <!-- <tr> -->
								   <tr>
										<td rowspan="2" class="encabezado2VerticalInvertido" >DETALLES PRODUCTO </td>
										<td class="encabezado2HorizontalInvertido">
											<?
											if($ApEssf){echo "CONCENTRACI&Oacute;N";}
											else{echo "UNIDAD DE MEDIDA";}
											?>  
										</td>
										<td class="encabezado2HorizontalInvertido">
											<?
											if($ApEssf){echo "FORMA FARMAC&Eacute;UTICA";}
											else{echo "PRESENTACI&Oacute;N";}
											?>            
										</td>
										<td class="encabezado2HorizontalInvertido">TIPO PRODUCTO</td>
										<td colspan="2" class="encabezado2HorizontalInvertido">GRUPO</td>
									</tr>
									<tr>
										<td style="text-align:center;">
											<input type="text" name="UniMed" id="UniMed" title="Unidad de Medida" value="<? echo $UniMed?>"
											onFocus="Mostrar();
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPrincipal?>&Tipo=Unidad&UnidadMedida='+this.value+'&Objeto=UniMed'"
											onkeyup="xLetra(this);
											FORMA.EUniMed.value=0;
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPrincipal?>&Tipo=Unidad&UnidadMedida='+this.value+'&Objeto=UniMed';"
											onKeyDown="xLetra(this);return noenter('Presentacion');"/>      
										</td>
										<td style="text-align:center;">
											<input type="text" name="Presentacion" id="Presentacion" title="Presentacion" style="width:100%;" value="<? echo $Presentacion?>"
											onFocus="Mostrar();
												frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPrincipal?>&Tipo=Presentacion&Presentacion='+this.value+'&Objeto=Presentacion'"
										onkeyup="xLetra(this);
												FORMA.EPresentacion.value=0;
												frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPrincipal?>&Tipo=Presentacion&Presentacion='+this.value+'&Objeto=Presentacion';"
												onKeyDown="xLetra(this);return noenter('TipoPro');"/>     
										</td>
										<td style="text-align:center;">
											<input type="text" name="TipoPro" id="TipoPro" title="Tipo de Producto" value="<? echo $TipoPro?>"
											onFocus="Mostrar();
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TipoProducto&TipoProducto='+this.value+'&Objeto=TipoPro&AlmacenPpal='+FORMA.AlmacenPrincipal.value"
											onkeyup="xLetra(this);
											FORMA.ETipoPro.value=0;
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=TipoProducto&TipoProducto='+this.value+'&Objeto=TipoPro&AlmacenPpal='+FORMA.AlmacenPrincipal.value;"
											onKeyDown="xLetra(this);return noenter('Bodega');"/>   
										</td>
										<td colspan="5" style="text-align:center;">
											<select name="Grupo" style="width:100%;" <? echo $GrupoDisabled ?> onFocus="Ocultar()" >
												  <option value="">Grupo</option>
												  <?
														$cons="Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPrincipal' and Compania='$Compania[0]' and Anio=$Anio order by Grupo";
														$res=ExQuery($cons);
														while($fila=ExFetch($res))
														{
															if($Grupo==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
															else {echo "<option value='$fila[0]'>$fila[0]</option>";}
														}
													?>
											</select>
										</td>
									</tr>
									<tr>
										<td rowspan="2" class="encabezado2VerticalInvertido">LOCALIZACI&Oacute;N:</td>
										<td class="encabezado2HorizontalInvertido">BODEGA</td>
										<td>&nbsp;</td>
										<td class="encabezado2HorizontalInvertido">ESTANTE</td>
										<td>&nbsp;</td>
										<td class="encabezado2HorizontalInvertido">NIVEL</td>
									</tr>
									<tr>
										<td style="text-align:center;">
											<input type="text" name="Bodega" id="Bodega" style="width:100%;" title="Bodega" value="<? echo $Bodega?>"
											onFocus="Mostrar();
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bodega&Bodega='+this.value+'&Objeto=Bodega&AlmacenPpal='+FORMA.AlmacenPrincipal.value"
											onkeyup="xLetra(this);
											FORMA.EBodega.value=0;
											frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bodega&Bodega='+this.value+'&Objeto=Bodega&AlmacenPpal='+FORMA.AlmacenPrincipal.value;"
											onKeyDown="xLetra(this);return noenter('Estante');"/>  
										</td>
										<td>&nbsp;</td>
										<td style="text-align:center;">
											<input type="text" value ="<? echo $Estante ?>" name="Estante" id="Estante" maxlength="10" size="6" onFocus="Ocultar()"
											onKeyUp="xLetra(this)"
											onKeyDown="xLetra(this);return noenter('Nivel');"/>            </td>
										<td>&nbsp;</td>
										<td>
											<input type="text" value="<? echo $Nivel; ?>" name="Nivel" id="Nivel" maxlength="10" size="6" onFocus="Ocultar()"
											onKeyUp="xLetra(this)" onKeyDown="xLetra(this);return noenter('Max');"/>            </td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">ESTADO</td>
										<td>
											<select name="Estado" style="width:100%" title="Estado" onFocus="Ocultar()" >
												<option value=""></option>
												<?
													if($Editar)
														{
																if($Estado=="AC"){echo "<option selected value='AC'>Activo</option><option value='IN'>Inactivo</option>";}
																else{echo "<option selected value='IN'>Inactivo</option><option value='AC'>Activo</option>";}
														}
														else{
												?>
														<option value="AC">Activo</option>
														<option value="IN">Inactivo</option> <?

														} ?>
											</select>   
										</td>
										<td class="encabezado2VerticalInvertido">M&Aacute;XIMO</td>
										<td>
											<input type="text" value="<? echo $Max;?>" name="Max" id="Max" size="6" onFocus="Ocultar()"
											onKeyUp="xNumero(this)" onKeyDown="xNumero(this);return noenter('Min');" onBlur="campoNumero(this)"  />  
										</td>
										<td class="encabezado2VerticalInvertido">M&Iacute;NIMO</td>
										<td>
											<input type="text" value="<? echo $Min;?>" name="Min" id="Min" size="6" onFocus="Ocultar()"
											onKeyUp="xNumero(this)" onKeyDown="xNumero(this);return noenter('Codigo');" onBlur="campoNumero(this)" /> 
										</td>
									</tr>
									<tr>
										<td class="encabezado2VerticalInvertido">IVA <input type="checkbox" name="Iva" onClick="Cambiar(1)" onFocus="Ocultar()" <? echo $CVrIva;?> /></td>
										<td>
												<input type="text" name="VrIva" readonly="yes" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);
											if(parseInt(this.value)>99){this.value='';};" 
											<?  if(!$Editar) {echo "value='0'";}
												else {echo "value='$VrIva'";} ?>
											 size="5" maxlength="5" style="text-align:right;" onFocus="Ocultar()" />%
										</td>
										
										<td class="encabezado2VerticalInvertido">ACTUALIZAR VENTA <input type="checkbox" name="Venta" onClick="Cambiar(4)" onFocus="Ocultar()" <? echo $CVenta; ?>/></td>
										<td>
											<input type="text" name="ActualizaVenta" readonly="yes" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);
											if(parseInt(this.value)>99){this.value='';};" 
											<?  if(!$Editar) {echo "value='0'";}
												else {if($ActualizaVenta){echo "value='$ActualizaVenta'";}else{echo "value='0'";}} ?>
											 size="5" maxlength="5" style="text-align:right;" onFocus="Ocultar()" />%
										</td>
									<td class="encabezado2VerticalInvertido">CLASIFICACI&Oacute;N</td>
									<td>
										<input type="text" size="12" name="Clasificacion" id="Clasificacion" title="Clasificacion" value="<? echo $Clasificacion?>"
										onFocus="Mostrar();										
									   frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Clasificacion&Clasificacion='+this.value+'&Objeto=Clasificacion&AlmacenPpal='+FORMA.AlmacenPrincipal.value" 
										onkeyup="xLetra(this);
										FORMA.EClasificacion.value=0;
										frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Clasificacion&Clasificacion='+this.value+'&Objeto=Clasificacion&AlmacenPpal='+FORMA.AlmacenPrincipal.value;" 
										onKeyDown="xLetra(this)"/>
									</td>
								</tr>
								<? 
								   $cons = "Select AlmacenPpal from Consumo.AlmacenesPpales Where AlmacenPpal='$AlmacenPrincipal' and Compania='$Compania[0]' and SSFarmaceutico=1";
								   $res = ExQuery($cons);
								   if(ExNumRows($res) > 0)
								   {
								?>
								<tr>
									<td class="encabezado2VerticalInvertido">CUM</td>
									<td><!--<input type="text" name="CUM" value="<? echo $CUM?>"
														   onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onFocus="Ocultar();" />-->
										<?if($Editar){?>
											<input type="button" name="configCUM" value="  ...  " title="Configurar CUM para este producto"   onclick="AbrirCUM()" />
										<?}?>                  
									</td>
									<td class="encabezado2VerticalInvertido">CONTROL 
										<select name="Control" onFocus="Ocultar();">
											<option value="NA">No Aplica</option>
											<option <? if($Control=="No"){ echo " Selected";}?> value="No">No</option>
											<option <? if($Control=="Si"){ echo " Selected";}?> value="Si">Si</option>
										</select>
									</td>
									<td class="encabezado2VerticalInvertido">SOM&Aacute;TICO 
										<select name="Somatico" onFocus="Ocultar();">
											<option value="NA">No Aplica</option>
											<option <? if($Somatico=="No"){ echo " Selected";}?> value="No">No</option>
											<option <? if($Somatico=="Si"){ echo " Selected";}?> value="Si">Si</option>
										</select>
									</td>
									<td class="encabezado2VerticalInvertido">RIESGO</td>
									<td>
										<select name="Riesgo" onFocus="Ocultar();">
											<option value="">&nbsp;</option>
											<?
												$cons1 = "Select Riesgo from Consumo.Riesgos Where Compania='$Compania[0]'";
												$res1 = ExQuery($cons1);
												while($fila1 = ExFetch($res1))
												{
													if($fila1[0]==$Riesgo){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
													else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">C&Oacute;DIGO SECRETARIA</td>
										<td colspan="1">
											<input type="text" name="codsecretaria" value="<? echo $codsecretaria?>" style="width:100%" >
										</td>
										<td class="encabezado2VerticalInvertido">REGISTRO INVIMA</td>
										<td colspan="2">
											<input type="text" name="regINVIMA" value="<? echo $regINVIMA?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:100%" onFocus="Ocultar();" >            </td>
										<td class="encabezado2VerticalInvertido">POS 
											<input type="checkbox" <? echo $chkPOS?> name="pos" title="POS/NO POS" onFocus="Ocultar();">
										</td>
								</tr>
									<?
								   }?>
								<?  if($Editar){
										$cons="Select Anio,Cantidad,VrUnidad,VrTotal from Consumo.SaldosInicialesxAnio where Compania='$Compania[0]' and AutoId=$AutoId and 
										AlmacenPpal='$AlmacenPrincipal' and Anio=$Anio Order By Anio Desc";
										$res=ExQuery($cons);
										$fila=ExFetch($res);
										$AnioSI=$fila[0];$CantSI=$fila[1];$VrUnidadSI=$fila[2];$VrTotalSI=$fila[3];
										?>
								<tr>
									<td colspan="2" style="text-align:center;font-weight:bold;">
										<a href="#"  onClick="AbrirPreciodeVenta()"> PRECIO DE VENTA</a>
									</td>
								<td colspan="4" class="encabezado2HorizontalInvertido" style="text-align:center;font-weight:bold;" >
								
									<? if(!$SIxAnio){
										?><a href="#" onClick="AbrirSaldoInicial()"><? 
									}?>
									SALDO INICIAL 
									<? echo $Anio ?><? if(!$SIxAnio){?></a><? } ?>
								
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center;vertical-align:text-top;" >
									<table class="tabla1" style="font-size:11px;" cellpadding="2" cellspacing="0"    <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; ?> width="100%">
										<tr>
											<td class="encabezado1Horizontal" style="font-size:12px;">TARIFARIO</td>
											<td class="encabezado1Horizontal" style="font-size:12px;" >VR. VENTA</td>
											<?
												$cons="Select Tarifario from Consumo.TarifariosVenta where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPrincipal' and Estado='AC'";
												$res=ExQuery($cons);
												while($fila=ExFetch($res)){
													$cons2="Select ValorVenta from Consumo.TarifasxProducto where Compania='$Compania[0]' 
													and AlmacenPpal='$AlmacenPrincipal' 
													and Tarifario='$fila[0]' and AutoId=$AutoId Order By FechaIni desc";
													$res2=ExQuery($cons2);
													$fila2=ExFetch($res2);if(!$fila2[0]){$fila2[0]="0";}
													echo "<tr>";
														echo "<td>$fila[0]</td>";
														echo "<td style='text-align:right;padding-right:5px'>".number_format($fila2[0],2)."</td>";
													echo "</tr>";
												}
												
											?>
									</table>
								</td>
								<td colspan="4" style="text-align:center;vertical-align:text-top;">
									<table class="tabla1" style="font-size:11px;" cellpadding="2" cellspacing="0"    <?php echo $borderTabla1Mentor; echo $bordercolorTabla1Mentor; ?> width="100%">
										<tr>
											<td class="encabezado1Horizontal" style="font-size:12px;">CANT.</td>
											<td class="encabezado1Horizontal" style="font-size:12px;">VR. UNIDAD</td>
											<td class="encabezado1Horizontal" style="font-size:12px;">VR. TOTAL</td>
										</tr>
										<tr>
											<td style="text-align:right;padding-right:5px;"><? echo number_format($CantSI,2)?></td>
											<td style="text-align:right;padding-right:5px;"><?echo number_format($VrUnidadSI,2)?></td>
											<td style="text-align:right;padding-right:5px;"><?echo number_format($VrTotalSI,2)?></td>
									</table>
								</td>
							</tr><?}?>
							<tr>
								<td colspan="6" style="text-align:center;padding-top:25px;padding-bottom:25px;">
									<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
									<input type="button" name="Cancelar" class="boton2Envio" value="Cerrar" onClick="location.href='Productos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&AutoId=<? echo $AutoId?>&AlmacenPrincipal=<? echo $AlmacenPrincipal?>'" />
									<input type="hidden" value="<? echo $Editar?>" name="Editar" />
									<input type="hidden" value="<? echo $AutoId?>" name="AutoId" />
								</td>
							</tr>
						</table>
						
						
					</form>
					<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
			</div>
		</body>
	</html>	
		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Eliminar){
				$cons="select orden,pantalla from HistoriaClinica.ItemsxFormatos 
				where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and id_item=$IdItem";						
				$res=ExQuery($cons); $fila=ExFetch($res);		
				$Orden=$fila[0];
				$Pant=$fila[1];
				if($Orden){
					$cons2="select orden from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and orden>$Orden and formato='$NewFormato' and tipoformato='$TF' and pantalla=$Pant";
					$res2=ExQuery($cons2);
					//echo "<br>$cons2";
					while($fila2=ExFetch($res2)){
						$NewOrden=$fila2[0]-1;
						$cons="update HistoriaClinica.ItemsxFormatos  set orden='$NewOrden' where compania='$Compania[0]' and orden=$fila2[0] and formato='$NewFormato' and tipoformato='$TF'
						and pantalla=$Pant";
						//echo "<br>$cons";
						$res=ExQuery($cons);
					}		
				}		
				$cons="update HistoriaClinica.ItemsxFormatos set estado='AN' where compania='$Compania[0]' and Id_Item=$IdItem and formato='$NewFormato' and tipoformato='$TF'";
				//echo $cons;
				$res=ExQuery($cons);										
			}
			if($Subir)	
			{
				$IdNew=$Orden-1;
				$cons="select orden from HistoriaClinica.ItemsxFormatos where pantalla='$Pantalla' and formato='$NewFormato' and tipoformato='$TF'
				and estado='AC' and (orden<$Orden or orden=0) order by orden desc";
				$res=ExQuery($cons);		
				$fila=ExFetch($res); 
				if($fila[0]||$fila[0]=="0"){//echo $cons;
					if($fila[0]=="0"){
						if($fila[0]!=$Orden){
							$cons2="Update HistoriaClinica.ItemsxFormatos 
							set orden=99999 where orden=$Orden 
							and Formato='$NewFormato' and TipoFormato='$TF'";
							$res2=ExQuery($cons2);
							//echo "<br>$cons2";
							$cons2="Update HistoriaClinica.ItemsxFormatos 
							set orden=$Orden where orden=$fila[0] 
							and Formato='$NewFormato' and TipoFormato='$TF'";
							$res2=ExQuery($cons2);
							//echo "<br>$cons2";
							$cons2="Update HistoriaClinica.ItemsxFormatos 
							set orden=$IdNew where orden=99999 
							and Formato='$NewFormato' and TipoFormato='$TF'";
							$res2=ExQuery($cons2);
							//echo "<br>$cons2";
						}
					} 
					else{
						$cons2="Update HistoriaClinica.ItemsxFormatos 
						set orden=99999 where orden=$Orden 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res2=ExQuery($cons2);
						//echo "<br>$cons2";
						$cons2="Update HistoriaClinica.ItemsxFormatos 
						set orden=$Orden where orden=$fila[0] 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res2=ExQuery($cons2);
						//echo "<br>$cons2";
						$cons2="Update HistoriaClinica.ItemsxFormatos 
						set orden=$fila[0] where orden=99999 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res2=ExQuery($cons2);
						//echo "<br>$cons2";
					}
				}
				/*if($Orden!="0"){echo " entra";
					$cons2="select orden from HistoriaClinica.ItemsxFormatos 
					where pantalla='$Pantalla' and orden='$IdNew' and compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' ";
					//echo "<br>$cons2";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)>0){
						$cons="Update HistoriaClinica.ItemsxFormatos 
						set orden=99999 where orden=$Orden 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res=ExQuery($cons);echo ExError();
						echo "<br>$cons";
						$cons="Update HistoriaClinica.ItemsxFormatos 
						set orden=$Orden where orden=$IdNew 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res=ExQuery($cons);echo ExError();
						echo "<br>$cons";
						$cons="Update HistoriaClinica.ItemsxFormatos 
						set orden=$IdNew where orden=99999 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res=ExQuery($cons);echo ExError();
						echo "<br>$cons";
					}
					else
					{
						$cons="Update HistoriaClinica.ItemsxFormatos 
						set orden=$IdNew where orden=$Orden 
						and Formato='$NewFormato' and TipoFormato='$TF'";
						$res=ExQuery($cons);echo ExError();
						//echo "<br>$cons";
					}
				}*/
			}

			if($Bajar)	
			{
				$cons="select orden from HistoriaClinica.ItemsxFormatos where pantalla='$Pantalla' and formato='$NewFormato' and tipoformato='$TF'
				and estado='AC' and orden>$Orden order by orden asc";
				$res=ExQuery($cons);		
				$fila=ExFetch($res); 
				if($fila[0]){//echo $cons;			
					$cons2="Update HistoriaClinica.ItemsxFormatos 
					set orden=99999 where orden=$Orden 
					and Formato='$NewFormato' and TipoFormato='$TF'";
					$res2=ExQuery($cons2);
					//echo "<br>$cons2";
					$cons2="Update HistoriaClinica.ItemsxFormatos 
					set orden=$Orden where orden=$fila[0] 
					and Formato='$NewFormato' and TipoFormato='$TF'";
					$res2=ExQuery($cons2);
					//echo "<br>$cons2";
					$cons2="Update HistoriaClinica.ItemsxFormatos 
					set orden=$fila[0] where orden=99999 
					and Formato='$NewFormato' and TipoFormato='$TF'";
					$res2=ExQuery($cons2);
					//echo "<br>$cons2";			
				}
				//echo "$Orden";
				/*$IdNew=$Orden+1;
				$cons2="select orden from HistoriaClinica.ItemsxFormatos where pantalla='$Pantalla' and orden='$IdNew' and formato='$NewFormato' and tipoformato='$TF' ";
				//echo "<br>$cons2";
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)>0){
					$cons="Update HistoriaClinica.ItemsxFormatos 
					set orden=99999 where orden=$Orden 
					and Formato='$NewFormato' and TipoFormato='$TF' ";
					$res=ExQuery($cons);echo ExError();
					//echo "<br>$cons";
					$cons="Update HistoriaClinica.ItemsxFormatos 
					set orden=$Orden where orden=$IdNew 
					and Formato='$NewFormato' and TipoFormato='$TF' ";
					$res=ExQuery($cons);echo ExError();
					//echo "<br>$cons";
					$cons="Update HistoriaClinica.ItemsxFormatos 
					set orden=$IdNew where orden=99999 
					and Formato='$NewFormato' and TipoFormato='$TF' ";
					$res=ExQuery($cons);echo ExError();
					//echo "<br>$cons";
				}
				else
				{
					$cons="Update HistoriaClinica.ItemsxFormatos 
					set orden=$IdNew where orden=$Orden 
					and Formato='$NewFormato' and TipoFormato='$TF' ";
					$res=ExQuery($cons);echo ExError();
					//echo "<br>$cons";
				}*/
			}
			if($CUPNoPos||$MedNoPos)
			{
				$cons="select pantalla from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and compania='$Compania[0]' Group By pantalla Order By pantalla Desc";
				$res=ExQuery($cons,$conex);
				$fila=ExFetch($res);
				$Pant=$fila[0];
				if($Pant==0){$Pant++;}
				
				$cons="Select Id_Item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and compania='$Compania[0]' Group By Id_Item Order By Id_Item Desc";
				$res=ExQuery($cons,$conex);
				$fila=ExFetch($res);
				$IdI=$fila[0]+1;
				$cons="Select orden from HistoriaClinica.ItemsxFormatos 
				where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pant Group By orden Order By orden Desc";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Ord=$fila[0]+1;
							
				$cons="select item,id_item from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AN'";
				$res=ExQuery($cons);
				$filaAN=ExFetch($res);
				if($CUPNoPos)
				{	
					$Itm="CUP No Pos";			
				}
				else
				{
					$Itm="Medicamento No Pos";
				}	
				if($filaAN){
					$cons="update historiaclinica.itemsxformatos set estado='AC',orden=$Ord,item='$Itm',pantalla=$Pant,titulo=1
					where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and id_item=$filaAN[1] and item='$filaAN[0]'";
				}
				else
				{
					$cons="insert into historiaclinica.itemsxformatos (compania,formato,tipoformato,id_item,pantalla,titulo,estado,orden,item)
					values('$Compania[0]','$NewFormato','$TF',$IdI,$Pant,1,'AC',$Ord,'$Itm')";			
				}
				//echo $cons;
				$res=ExQuery($cons);
				?>
				<script language="javascript">
					parent.document.FORMA.submit();
				</script>
				<?
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
				
				<script language="javascript" src="/Funciones.js"></script>
				<script language="javascript">
				function Dependencias(Formato,IdItem,Item,TipoFormato)
				{
					//alert(Formato+" - "+IdItem+" - "+Item+" - "+TipoFormato);
					frames.FrameOpener.location.href="/HistoriaClinica/Administracion/Dependencia.php?DatNameSID=<? echo $DatNameSID?>&Formato="+Formato+"&IdItem="+IdItem+"&Item="+Item+"&TipoFormato="+TipoFormato;
					document.getElementById('FrameOpener').style.position='absolute';
					document.getElementById('FrameOpener').style.top='50px';
					document.getElementById('FrameOpener').style.left='17%';
					document.getElementById('FrameOpener').style.display='';
					document.getElementById('FrameOpener').style.width='70%';
					document.getElementById('FrameOpener').style.height='70%';	
				}
				</script>
			</head>
			<body <?php echo $backgroundBodyMentor; ?>>
					<div align="center">
							<form name="FORMA" method="post">
								
							  <table width="100%" class="tabla3"  <?php  echo $borderTabla3Mentor; echo $bordercolorTabla3Mentor; echo $cellspacingTabla3Mentor; echo $cellpaddingTabla3Mentor; ?>>
								<tr>	
									<td colspan="30" style="text-align:left;">
										<table cellpadding="2" cellspacing="0" border="0" style="font-size:12px;">
											<tr>
												<td> <label for="ElementosActivos">Activos </label> </td>
												<td> <input type="radio" name="Elementos" id="ElementosActivos" checked /> </td>
												<td> <label for="ElementosInactivos">Inactivos </label> </td>
												<td> <input type="radio" name="Elementos" id="ElementosInactivos" onClick="location.href='ItemsxFormatoAN.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'" /> </td>
											</tr>
										</table>	
										
									</td>
								</tr>
								<tr >
									<td colspan="30" style="text-align:center">
										<input type="Button" value="Nuevo Registro" class="boton2Envio" onClick="location.href='NuevoItem.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
										<input type="Button" value="Incluir Subtitulo" class="boton2Envio" onClick="location.href='SubTitulo.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
										<input type="Button" value="Sub-Formato" class="boton2Envio" onClick="location.href='SubFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
										<input type="button" value="Diagnosticos" class="boton2Envio" onClick="location.href='DxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
											<?	$consNPFormato="select nopos from historiaclinica.formatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
												//echo $consNPFormato;
												$resNPFormato=ExQuery($consNPFormato);
												$filaNPFormato=ExFetch($resNPFormato);	
												$consYaNP="select item,estado from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' 
												and (item ilike '%Medicamento No POS%' or item ilike '%CUP No POS%')";
												//echo $consYaNP;
												$resYaNP=ExQuery($consYaNP);
												$filaYaNP=ExFetch($resYaNP);
												
												if($filaNPFormato[0]=="Medicamentos No POS"){?>     
													<input type="submit" value="Item Medicamento No POS" name="MedNoPos" <? if($filaYaNP[1]=='AC'){?> disabled <? }?>>	
														<?	}				
												if($filaNPFormato[0]=="CUPS No POS"){?>     
													<input type="submit" class="boton2Envio" value="Item CUPS No POS" name="CUPNoPos" <? if($filaYaNP[1]=='AC'){?> disabled <? }?>>
											<?	}?>                  
									</td>
								</tr>
								<tr>
									<td class="encabezado2Horizontal" colspan="2">*</td>
									<td class="encabezado2Horizontal">PANT.</td>
									<td class="encabezado2Horizontal">ITEM </td>
									<td class="encabezado2Horizontal">T. DATO</td>
									<td class="encabezado2Horizontal">LIM. INF.</td>
									<td class="encabezado2Horizontal">LIM. SUP.</td>
									<td class="encabezado2Horizontal">LONG.</td>
									<td class="encabezado2Horizontal">TIPO CONTROL</td>
									<td class="encabezado2Horizontal">ANCHO</td>
									<td class="encabezado2Horizontal">ALTO</td>
									<td class="encabezado2Horizontal">OBL.</td>
									<td class="encabezado2Horizontal">LS</td>
									<td class="encabezado2Horizontal">CF</td>                           
									<td class="encabezado2Horizontal" colspan="3">&nbsp;</td>  
								</tr>
								<?
								$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and estado='AC'
								order by Pantalla,Orden,Id_Item";			
								$res=ExQuery($cons,$conex);
								while($fila=ExFetchArray($res))
								{
									if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
									else{$BG="";$Fondo=1;}
								?>
									<tr bgcolor="<?echo $BG?>" align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='<?echo $BG?>'">
									  <? 
										if($fila['titulo']=='1')
										{?>
											<td><a href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Subir=1">
										  <img src="/Imgs/up.gif" border="0" style="width:16px;"></a> 
										  </td>
										  <td>
											  <a name="<? echo $fila['id_item']?>" href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Bajar=1">
											  <img src="/Imgs/down.gif" border="0" style="width:16px;"></a>
											</td>  
											
											<td colspan="12" bgcolor="#E4E4E4"><strong><? echo $fila['item']?></strong></td>
											
										<?	if($fila['item']!='Diagnostico'){?>
												<td><a href="SubTitulo.php?DatNameSID=<? echo $DatNameSID?>&Modificar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>">
														<img src="/Imgs/b_edit.png" border="0" title="Editar" style="cursor:hand">
													</a>
												</td>
												<td></td>
												<td><img src="/Imgs/b_drop.png" border="0" title="Desabilitar" style="cursor:hand" 
													onClick="if(confirm('Desea deshabilitar este elemento?')){location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>&Itm=<? echo $fila['item']?>'}">                                
												</td>                          
									  <?	} 
											else{?>
												<td><a href="DxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>">
														<img src="/Imgs/b_edit.png" border="0">
													</a>
												</td>
												<td></td>
												<td><img src="/Imgs/b_drop_gray.png"></td>
										<?	}
										}
										elseif($fila['subformato']=='1')
										 {
										 //PENDIETE DESPLAZAMIENTO?>
											<td><a href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Subir=1">
										  <img src="/Imgs/up.gif" border="0" style="width:16px;"></a><? //echo $fila['id_item']." - ".$fila['orden']." - ".$fila['pantalla']?> 
										  </td>
										  <td>
											  <a name="<? echo $fila['id_item']?>" href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Bajar=1">
											  <img src="/Imgs/down.gif" border="0" style="width:16px;"></a>
										  </td> 
										  
											<td colspan="12" bgcolor="#E4E4E4"><strong>SUBFORMATO : <? echo $fila['item']?></strong></td>
											<td><a href="SubFormato.php?DatNameSID=<? echo $DatNameSID?>&Modificar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>"><img src="/Imgs/b_edit.png" border="0"></a></td>
											<td>&nbsp;</td>
											<td><a href="SubFormato.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>"onClick="if(confirm('Eliminar?')){location.href='SubTitulo.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>'}"><img src="/Imgs/b_drop.png" border="0"></a></td>
									  <? }
										else
										{?>       
										  <td><a href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Subir=1">
										  <img src="/Imgs/up.gif" border="0" style="width:16px;"></a> 
										  </td>
										  <td>
											  <a name="<? echo $fila['id_item']?>" href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&IdItem=<? echo $fila['id_item']?>&Orden=<? echo $fila['orden']?>&Pantalla=<? echo $fila['pantalla']?>&Bajar=1">
											  <img src="/Imgs/down.gif" border="0" style="width:16px;"></a>
										  </td>      
										  <td><? echo $fila['pantalla']?></td>
										  <td><? echo $fila['item']?></td>
										  <td><? echo $fila['tipodato']?></td>
										  <td><? echo $fila['liminf']?></td>
										  <td><? echo $fila['limsup']?></td>
										  <td><? echo $fila['longitud']?></td>
										  <td><? echo $fila['tipocontrol']?></td>
										  <td><? echo $fila['ancho']?></td>
										  <td><? echo $fila['alto']?></td>
										  <td><? if($fila['obligatorio']=='1'){echo "Si";}else{ echo "No";}?></td>
										  <td><? if($fila['lineasola']=='1'){echo "Si";}else{ echo "No";}?></td>
										  <td><? if($fila['cierrafila']=='1'){echo "Si";}else{ echo "No";}?></td>
										  
										  <td><a href="NuevoItem.php?DatNameSID=<? echo $DatNameSID?>&Modificar=1&NewFormato=<? echo $NewFormato?>&IdItem=<?echo $fila['id_item']?>&TF=<? echo $TF?>"><img src="/Imgs/b_edit.png" border="0" title="Editar" style="cursor:hand"></a></td>
										  <td><a href="#" onClick="Dependencias('<? echo $NewFormato?>','<? echo $fila['id_item']?>','<? echo $fila['item']?>','<? echo $TF?>');"><img src="/Imgs/b_insrow.png" border="0" title="Dependencia Historia Clinica" style="cursor:hand"></a></td>
										<td><img src="/Imgs/b_drop.png" border="0" title="Desabilitar" style="cursor:hand" 
												onClick="if(confirm('Desea deshabilitar este elemento?')){location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['id_item']?>&TF=<? echo $TF?>'}"></td>
									  </tr>
								<?   }
								}?>
								  <tr>
									<td colspan="13" style="text-align:center;">
									<input type="Button" class="boton2Envio" value="Nuevo Registro" onClick="location.href='NuevoItem.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
									<input type="Button" class="boton2Envio" value="Incluir Subtitulo" onClick="location.href='SubTitulo.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
									<input type="Button" class="boton2Envio" value="Sub-Formato" onClick="location.href='SubFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
									<input type="button" class="boton2Envio" value="Diagnosticos" onClick="location.href='DxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<?echo $NewFormato?>&TF=<? echo $TF?>'">
								<?	$consNPFormato="select nopos from historiaclinica.formatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
									//echo $consNPFormato;
									$resNPFormato=ExQuery($consNPFormato);
									$filaNPFormato=ExFetch($resNPFormato);	
									$consYaNP="select item,estado from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' 
									and (item ilike '%Medicamento No POS%' or item ilike '%CUP No POS%')";
									//echo $consYaNP;
									$resYaNP=ExQuery($consYaNP);
									$filaYaNP=ExFetch($resYaNP);
									
									if($filaNPFormato[0]=="Medicamentos No POS"){?>     
										<input type="submit" value="Item Medicamento No POS" name="MedNoPos" <? if($filaYaNP[1]=='AC'){?> disabled <? }?>>	
								<?	}				
									if($filaNPFormato[0]=="CUPS No POS"){?>     
										<input type="submit" class="boton2Envio" value="Item CUPS No POS" name="CUPNoPos" <? if($filaYaNP[1]=='AC'){?> disabled <? }?>>
								<?	}?>                  
									</td>
								</tr>
							 </table>
							</td>
						</tr>
					  </table>

					  <input type="Hidden" name="IdItem" value="<?echo $IdItem?>">
					  <input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
					  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					  <input type="Hidden" name="TF" value="<? echo $TF?>">
					</form>
					<br>
				<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
			</div>
		</body>
	</html>	

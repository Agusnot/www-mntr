		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			$ND=getdate();
			if($Retirar){
				if($IdItem){
					$cons="delete from historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and estado='AC'";
					$res=ExQuery($cons);
					$cons="update HistoriaClinica.ItemsxFormatos set estado='AN' 
					where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and item='Diagnostico' and id_item=$IdItem";
					$res=ExQuery($cons);
					//echo "<br>$cons";			
					$cons="select orden,pantalla from HistoriaClinica.ItemsxFormatos 
					where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and item='Diagnostico' and id_item=$IdItem";						
					$res=ExQuery($cons); $fila=ExFetch($res);		
					$Orden=$fila[0];
					$Ptlla=$fila[1];
					if($Orden){
						$cons2="select orden from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and orden>$Orden and formato='$NewFormato' and tipoformato='$TF' and pantalla=$Ptlla";
						$res2=ExQuery($cons2);
						//echo "<br>$cons2";
						while($fila2=ExFetch($res2)){
							$NewOrden=$fila2[0]-1;
							$cons="update HistoriaClinica.ItemsxFormatos  set orden='$NewOrden' where compania='$Compania[0]' and orden=$fila2[0] and formato='$NewFormato' and tipoformato='$TF'
							and pantalla=$Ptlla";
							//echo "<br>$cons";
							$res=ExQuery($cons);
						}		
					}
				}		

				?><script language="javascript">
					parent.document.FORMA.submit();			
					location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'
				</script><?
			}
			if($Guardar){
				if(!$Edit){
					$cons="Select Id_Item from HistoriaClinica.ItemsxFormatos where compania='$Compania[0]' and Formato='$NewFormato' and tipoformato='$TF' Group By Id_Item Order By Id_Item Desc";
					$res=ExQuery($cons,$conex);
					$fila=ExFetch($res);
					$IdItem=$fila[0]+1;
					
					$cons="Select orden from HistoriaClinica.ItemsxFormatos 
					where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
					$res=ExQuery($cons,$conex);
					$fila=ExFetch($res);
					$Orden=$fila[0]+1;
					
					if($Obligatorio==""){$Obligatorio=0;}   if($LineaSola==""){$LineaSola=0;}   if($CierraFila==""){$CierraFila=0;}						
					if($LimInf==''){$LimInf="0";} if($LimSup==''){$LimSup="0";} if($Longitud==''){$Longitud="0";} if($Ancho==''){$Ancho="0";} if($Alto==''){$Alto="0";}  
					
					$cons2="select orden from HistoriaClinica.ItemsxFormatos 
					where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and item='Diagnostico' and estado='AN'";						
					$res2=ExQuery($cons2); 
					//echo $cons2;
					if(ExNumRows($res2)>0){				
						$cons="update HistoriaClinica.ItemsxFormatos  set estado='AC',orden=$Orden,pantalla='$Pantalla'
						where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and item='Diagnostico'";
						$res=ExQuery($cons); echo ExError();				
					}
					else
					{
						$cons="Insert into HistoriaClinica.ItemsxFormatos (Formato,Id_Item,Item,Pantalla,TipoFormato,compania,titulo,orden)
						values ('$NewFormato',$IdItem,'Diagnostico','$Pantalla','$TF','$Compania[0]',1,$Orden)";
						$res=ExQuery($cons); echo ExError();				
					}
					//echo $cons;			
					$cons="insert into historiaclinica.dxformatos (compania,usuario,fecha,id,detalle,tipo,formato,tipoformato,estado,pantalla,iditem,cie10,tagxml,etiquetaxml) values
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
					,1,'$DetalleDxppal','Principal','$NewFormato','$TF','AC',$Pantalla,$IdItem,'1','$TagXML1','$EtiqXML1')";
					//echo "<br>$cons";
					$res=ExQuery($cons); echo ExError();
					if($Dx!=NULL){
						$cont=2;
						while( list($cad,$val) = each($Dx))
						{
							if($cad && $val)
							{							
								$cons="insert into historiaclinica.dxformatos (compania,usuario,fecha,id,detalle,tipo,formato,tipoformato,estado,pantalla,iditem,cie10,tagxml,etiquetaxml) values
								('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$cont,'$DetalleDx[$cad]','Relacionado','$NewFormato','$TF','AC',
								$Pantalla,$IdItem,'$CIE[$cad]','".$_POST["Tag$cont"]."','".$_POST["Etiq$cont"]."')";
								//echo "<br>$cons ---- $cad --- $cont";
								$res=ExQuery($cons);
								$cont++;
							}
						}
					}			
				}		
				else{
					$cons="delete from historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and estado='AC'";
					$res=ExQuery($cons);	
					$cons="insert into historiaclinica.dxformatos (compania,usuario,fecha,id,detalle,tipo,formato,tipoformato,estado,pantalla,iditem,cie10,tagxml,etiquetaxml) values
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',1,'$DetalleDxppal','Principal','$NewFormato','$TF','AC',$Pantalla,$IdItem,1,'$TagXML1','$EtiqXML1')";
					$res=ExQuery($cons); echo ExError();
					$cont=2;
					for($i=2;$i<6;$i++){
						if($Dx[$i]!=NULL){
							$cons="insert into historiaclinica.dxformatos (compania,usuario,fecha,id,detalle,tipo,formato,tipoformato,estado,pantalla,iditem,cie10,tagxml,etiquetaxml) values
							('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$cont,'$DetalleDx[$i]','Relacionado','$NewFormato','$TF','AC'
							,$Pantalla,$IdItem,'$CIE[$i]','".$_POST["Tag$cont"]."','".$_POST["Etiq$cont"]."')";
							$res=ExQuery($cons);
							//echo "<br>$cons";
							$cont++;
						}
					}		
				}
				?><script language="javascript">
					parent.document.FORMA.submit();			
					location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'
				</script><?
			}
			$cons="select formatoxml from historiaclinica.formatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FormatoXML=$fila[0];
			$cons="select id,detalle,pantalla,iditem,tagxml,etiquetaxml from historiaclinica.dxformatos 
			where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and estado='AC' and tipo='Principal'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){$Edit=1; $fila=ExFetch($res);$TagXML1=$fila[4];$EtiqXML1=$fila[5]; $AuxEtiqXML1=$fila[5];}
			
		?>
		<html>
			<head>
					<?php echo $codificacionMentor; ?>
					<?php echo $autorMentor; ?>
					<?php echo $titleMentor; ?>
					<?php echo $iconMentor; ?>
					<?php echo $shortcutIconMentor; ?>
					<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
			
					<script language="javascript">
					function Habilita(N,I)
					{
						if(document.getElementById(N).disabled){
							document.getElementById(N).disabled=false;		
							document.getElementById("CIE"+N).disabled=false;
							if(document.FORMA.FormatoXML.value!=""){
								if(I==2){document.getElementById("Tag2").disabled=false;document.getElementById("Etiq2").disabled=false;}
								if(I==3){document.getElementById("Tag3").disabled=false;document.getElementById("Etiq3").disabled=false;}
								if(I==4){document.getElementById("Tag4").disabled=false;document.getElementById("Etiq4").disabled=false;}
								if(I==5){document.getElementById("Tag5").disabled=false;document.getElementById("Etiq5").disabled=false;}
							}
						}
							
						else{
							document.getElementById(N).value="";
							document.getElementById(N).disabled=true;
							document.getElementById("CIE"+N).checked=false;
							document.getElementById("CIE"+N).disabled=true;
							if(document.FORMA.FormatoXML.value!=""){
								if(I==2){
									document.getElementById("Tag2").disabled=true;document.getElementById("Etiq2").disabled=true;
									document.getElementById("Tag2").value="";document.getElementById("Etiq2").value="";
								}
								if(I==3){
									document.getElementById("Tag3").disabled=true;document.getElementById("Etiq3").disabled=true;
									document.getElementById("Tag3").value="";document.getElementById("Etiq3").value="";
								}
								if(I==4){
									document.getElementById("Tag4").disabled=true;document.getElementById("Etiq4").disabled=true;
									document.getElementById("Tag4").value="";document.getElementById("Etiq4").value="";
								}
								if(I==5){
									document.getElementById("Tag5").disabled=true;document.getElementById("Etiq5").disabled=true;
									document.getElementById("Tag5").value="";document.getElementById("Etiq5").value="";
								}
							}
						}
					}
					function Validar(){
						var ban;
						ban=0;
						if(document.FORMA.Pantalla.value==""){
							alert("Debe digitar la pantalla!!!"); ban=1;
						}
						if(document.getElementById("DetalleDxppal").value==""&&ban==0){
							alert("Debe digitar el detalle!!!"); ban=1;
						}
						if(document.getElementById("A2").checked&&document.getElementById("B2").value==""&&ban==0){
							alert("Debe digitar el detallelle"); ban=1;
						}
						if(document.getElementById("A3").checked&&document.getElementById("B3").value==""&&ban==0){
							alert("Debe digitar el detallelle"); ban=1;
						}
						if(document.getElementById("A4").checked&&document.getElementById("B4").value==""&&ban==0){
							alert("Debe digitar el detallelle"); ban=1;
						}
						if(document.getElementById("A5").checked&&document.getElementById("B5").value==""&&ban==0){
							alert("Debe digitar el detallelle"); ban=1;
						}
						if(ban==0){
							document.FORMA.Guardar.value=1;
							document.FORMA.submit();
						}
							
					}
					</script>
					<script language="javascript" src="/Funciones.js"></script>
			</head>


			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >    
							<input type="hidden" name="IdItem" value="<? echo $fila[3]?>">
						<?	$consCup="select cup from historiaclinica.cupsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
							$resCup=ExQuery($consCup);
							//echo $consCup;
							if(ExNumRows($resCup)==0){$HayCup=0;}else{$HayCup=1;}?>    
							<tr>
								<td class="encabezado2Horizontal" colspan="8">PANTALLA
									<input type="text" maxlength="3" name="Pantalla" style="width:30px;text-align:center;" value="<?echo $fila[2]?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
								</td>
							</tr>
							<tr>
								<td class="encabezado2HorizontalInvertido">ID DX</td>
								<td class="encabezado2HorizontalInvertido">INCLUIR</td>
								<td class="encabezado2HorizontalInvertido">DETALLE</td>
								<td class="encabezado2HorizontalInvertido">TIPO</td>
								<td class="encabezado2HorizontalInvertido">TIPO DX</td>
								<? if($FormatoXML){
									?><td class="encabezado2HorizontalInvertido">TAG XML</td>
									<td class="encabezado2HorizontalInvertido">ETIQUETA XML</td><?
								}?>
							</tr>         
							<tr>
								<td style="text-align:center;font-weight:bold;">1</td>
								<td style="text-align:center;"><input type="checkbox" name="Dxppal" checked disabled value="1"></td>
								<td style="text-align:center;"><input type="text" name="DetalleDxppal" id="DetalleDxppal" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $fila[1]?>"></td>
								<td style="text-align:center;">CIE 10</td>
								<td style="text-align:center;"><? echo "Principal"?></td>
									<input type="hidden" name="FormatoXML"  value="<? echo $FormatoXML?>">
										<? 	if($FormatoXML){
											$consXML="select tag from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$FormatoXML order by orden";
											$resXML=ExQuery($consXML);?>
									<td>
										<select name="TagXML1" id="TagXML1" onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&BuscaEtiqXML2=1&Formato=<? echo $NewFormato?>&TipoFomarto=<? echo $TF?>&FormatoXML='+FormatoXML.value+'&TagXML='+this.value+'&EtiqXML='+AuxEtiqXML1.value+'&NomSelecEtiqXML=<? echo "EtiqXML1"?>';">
											<option value="">&nbsp;</option>
												<?	while($filaXML=ExFetch($resXML)){
													if($TagXML1==$filaXML[0]){
														echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";
													}
													else{ 
														echo "<option value='$filaXML[0]'>$filaXML[0]</option>";
													}
												}?>
										</select>
									</td>           	
									<td>
									<?	$consXML="select etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
										etiquetaxml!='$AuxEtiqXML1' and tagxml='$TagXML1'";				
										$resXML=ExQuery($consXML);			
										$consXML2="select etiqxml from  historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
										etiqxml!='$AuxEtiqXML1' and tagxml='$TagXML1'";
										$resXML2=ExQuery($consXML2);		
										$consXML="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$FormatoXML and tag='$TagXML1'
										and etiqueta not in ('0'";
											while($filaNoxml=ExFetch($resXML)){$consXML=$consXML.",'$filaNoxml[0]'";}
											while($filaNoxml2=ExFetch($resXML2)){$consXML=$consXML.",'$filaNoxml2[0]'";}
										$consXML=$consXML.") order by orden";	
										$resXML=ExQuery($consXML);?>
										
										<select name="EtiqXML1" id="EtiqXML1">
											<option></option>
										<?	while($filaXML=ExFetch($resXML))
											{
												if($EtiqXML1==$filaXML[0]){echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";}
												else{ echo "<option value='$filaXML[0]'>$filaXML[0]</option>";}
											}?>
										</select>
									</td>
									<input type="hidden" name="AuxEtiqXML1" id="AuxEtiqXML1" value="<? echo $AuxEtiqXML1?>">
							<?	}?>
							</tr>
									
						<?	$Nom="A";
							for($i=2;$i<6;$i++)
							{
								$fila='';
								$Nom="A";
								$Nomb="B";
								$NomC="Tag";
								$NomD="Etiq";
								$NomE="Aux";		
								$Nom=$Nom.$i;
								$Nomb=$Nomb.$i;
								$NomC=$NomC.$i;
								$NomD=$NomD.$i;
								$NomE=$NomE.$i;
								//echo "<br>$Nom";
								
								$cons="select id,detalle,cie10,tagxml,etiquetaxml from historiaclinica.dxformatos 
								where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and estado='AC' and tipo='Relacionado' and id=$i";
								$res=ExQuery($cons);
								if(ExNumRows($res)>0){$Edit=1; $fila=ExFetch($res);}		?>
								
								<tr>
									<td style="text-align:center;font-weight:bold;"><? echo $i?></td>
									<td style="text-align:center;"><input type="checkbox" name="Dx[<? echo $i?>]" id="<? echo $Nom?>" onClick="Habilita('<? echo $Nomb?>','<? echo $i?>')" 
										<? if($fila[1]!=''){?> checked value="<? echo $i?>"<? }?>>
									</td>
									<td style="text-align:center;"><input type="text" name="DetalleDx[<? echo $i?>]" id="<? echo $Nomb?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" 
										<? if($fila[1]==''){?>disabled <? }?> value="<? echo $fila[1]?>">
									</td>           	
									<td style="text-align:center;"><select name="CIE[<? echo $i?>]" id="CIE<? echo $Nomb?>" <? if($fila[1]==''){?> disabled <? }?>>
											<option value="1" <?  if($fila[2]=='1'){?> selected <? }?>>CIE 10</option>
											<option value="0" <?  if($fila[2]=='0'){?> selected <? }?>>Abierto</option>			
										</select>                    
									</td>
									<td><? echo "Relacionado"?></td>
								<? 	if($FormatoXML){
										$consXML="select tag from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$FormatoXML order by orden";
										$resXML=ExQuery($consXML);?>
										
										<td style="text-align:center;">
											<select name="<? echo $NomC?>" id="<? echo $NomC?>" <? if($fila[1]==''){?> disabled <? }?>
											onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&BuscaEtiqXML2=1&Formato=<? echo $NewFormato?>&TipoFomarto=<? echo $TF?>&FormatoXML='+FormatoXML.value+'&TagXML='+this.value+'&EtiqXML='+<? echo $NomE?>.value+'&NomSelecEtiqXML='+<? echo $NomD?>.name;">
												<option></option>
											<?	while($filaXML=ExFetch($resXML))
												{
													if($fila[3]==$filaXML[0]){echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";}
													else{ echo "<option value='$filaXML[0]'>$filaXML[0]</option>";}
												}?>
											</select>
										</td>
									  
									<?	$consXML="select etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
										etiquetaxml!='$NomD' and etiquetaxml!='' and tagxml='$NomC'";
										$resXML=ExQuery($consXML);		
										$consXML2="select etiqxml from  historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
										etiqxml!='$EtiqXML' and tagxml='$TagXML'";
										$resXML2=ExQuery($consXML2);	
										$consXML="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$FormatoXML and tag='$fila[3]'
										and etiqueta not in ('0'";
										while($filaNoxml=ExFetch($resXML)){$consXML=$consXML.",'$filaNoxml[0]'";}
										while($filaNoxml2=ExFetch($resXML2)){$consXML=$consXML.",'$filaNoxml2[0]'";}
										$consXML=$consXML.") order by orden";	
										$resXML=ExQuery($consXML);      ?>
								
										<td>
											<select name="<? echo $NomD?>" id="<? echo $NomD?>" <? if($fila[1]==''){?> disabled <? }?>>
												<option></option>
											<?	while($filaXML=ExFetch($resXML))
												{
													if($fila[4]==$filaXML[0]){echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";}
													else{ echo "<option value='$filaXML[0]'>$filaXML[0]</option>";}
												}?>
											</select>
										</td>                
										<input type="hidden" name="<? echo $NomE?>" id="<? echo $NomE ?>" value="<? echo $fila[4]?>">
								<?	}?>
								</tr>        
						<?		
							}?>
							<tr>
								<td  colspan="7" style="text-align:center;">
									<input type="button" value="Guardar" class="boton2Envio" onClick="Validar()">
								<?	if($HayCup==1){?>
										<input type="button" value="Retirar" class="boton2Envio" disabled>
								<?	}
									else{?>
										<input type="submit" value="Retirar" class="boton2Envio" name="Retirar">
								<?	}?>
									<input type="button" value="Cancelar" class="boton2Envio" onClick="location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'"></td>
							</tr>
						</table> 
						<input type="hidden" name="Guardar">
						<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
						<input type="Hidden" name="TF" value="<? echo $TF?>">
						<input type="hidden" name="Edit" value="<? echo $Edit?>">
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					</form>
					<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
				</div>
			</body>
		</html>

		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar)
			{
				if(!$Item){$Item=-1;}		
				$Fecha=date("Y-m-d H:i:s");
				$Indicador=strtoupper(trim($Indicador));
				$insertar="INSERT INTO historiaclinica.indicadoresxhc(compania, tipoformato, formato, tablaformato, item, vritem, indicador,
				 fechacrea, usuariocrea) VALUES ('$Compania[0]', '$TF', '$NewFormato', '$TablaFormato', $Item, '$VrItem', '$Indicador', '$Fecha', 
				 '$usuario[0]')";
				//echo $insertar;
				$res=ExQuery($insertar);
				//exit;
				echo"<script languaje='javascript'> location.href='IndicadoresxFormato.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF&TablaFormato=$TablaFormato';</script>";
			}	
			
			$consulta="select * from HistoriaClinica.CUPSxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and cargo='General' and vritem='General'
			and compania='$Compania[0]'";
		//echo $consulta;
			$res=ExQuery($consulta,$conex);
			if(ExNumRows($res)>0){$Block=1;}
			
			
			$consulta="select * from HistoriaClinica.CUPSxFormatos,HistoriaClinica.itemsxformatos where CUPSxFormatos.Formato='$NewFormato' 
			and CUPSxFormatos.TipoFormato='$TF' and CUPSxFormatos.compania='$Compania[0]' and  itemsxformatos.compania='$Compania[0]' 
			and id_item=CUPSxFormatos.item order by CUPSxFormatos.item desc";
			
			$consulta="select nombre,CUPSxFormatos.tipoformato,CUPSxFormatos.formato,itemsxformatos.item,cargo,codigo,cup,vritem,fechacre,
			itemsxformatos.id_item
			from HistoriaClinica.CUPSxFormatos,contratacionsalud.cups,historiaclinica.itemsxformatos
			where CUPSxFormatos.Formato='$NewFormato' and CUPSxFormatos.TipoFormato='$TF' and cups.codigo=CUPSxFormatos.cup 
			and itemsxformatos.id_item=CUPSxFormatos.item
			and itemsxformatos.TipoFormato='$TF' and itemsxformatos.Formato='$NewFormato' and CUPSxFormatos.compania='$Compania[0]' 
			and cups.compania='$Compania[0]'
			and itemsxformatos.compania='$Compania[0]'";
			//echo $consulta;	
				
			$res=ExQuery($consulta,$conex);
		?>
		
		<html>
			<head>
				<?php echo $codificacionMentor; ?>
				<?php echo $autorMentor; ?>
				<?php echo $titleMentor; ?>
				<?php echo $iconMentor; ?>
				<?php echo $shortcutIconMentor; ?>
				<link rel="stylesheet" type="text/css" href="../../General/Estilos/estilos.css">
				<script language='javascript' src="/Funciones.js"></script>
				<script language="javascript">
					function ValidaForma()
					{		
						//if(document.form1.Item.value==""){	alert("Debe seleccionar un Item!!!");	return false;}		
						if(document.FORMA.VrItem!=null){				
							if(document.FORMA.VrItem.value==""){
								alert("Debe seleccionar un Valor del Item!!!");	return false;
							}
						}
						if(document.FORMA.Indicador.value==""){alert("Por Favor ingrese el nombre del Indicador!!!");return false;}
						
					}	
				</script>
			</head>	
			
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" onSubmit="return ValidaForma()">       
							<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
							<input type="Hidden" name="TF" value="<? echo $TF?>">
							<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							<input type="hidden" name="TablaFormato" value="<? echo $TablaFormato?>" />
							<input type="hidden" name="Ya" value="<? echo $Ya?>" />
							
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >  
							 <tr>
								<td class="encabezado2Horizontal" colspan="2">NUEVO INDICADOR</td>
							 </tr>   
							 <tr>
								<td class="encabezado2VerticalInvertido">ITEM</td>
								<td>										<?
										$consx="select Item from HistoriaClinica.IndicadoresxHC where Compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and TablaFormato='$TablaFormato'";
										$resx=ExQuery($consx);
										if(ExNumRows($resx)>0){$sub=1;$QuitaNA=1; $PartCons4="and  id_item in(select Item from HistoriaClinica.IndicadoresxHC where Compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and TablaFormato='$TablaFormato')";}
										//$cons4="select item,id_item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'    and titulo is null and estado='AC'and id_item>0 $PartCons4 order by item";
										$cons4 = "SELECT item,id_item FROM HistoriaClinica.ItemsxFormatos WHERE Formato='$NewFormato' AND TipoFormato='$TF' AND Compania='$Compania[0]' AND (titulo IS NULL or titulo = 0) AND estado='AC'AND id_item > 0 ";
										$res4=ExQuery($cons4);?>
										<select name="Item" onChange="document.FORMA.submit()">
										<?
										if(!$QuitaNA){?><option value="">NA</option><? }?>
									<?	while($fila4=ExFetch($res4)){
											if($Item==$fila4[1]){
												echo "<option value='$fila4[1]' selected>$fila4[0]</option>";
											}
											else{
												echo "<option value='$fila4[1]'>$fila4[0]</option>";
											}
										}?>
										</select>								
								</td>
							 </tr>         
							<?	
							if(!$Item){$Item=-1;}
							$cons5="select tipocontrol from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and id_item='$Item'";
							$res5=ExQuery($cons5);  
							$fila5=ExFetch($res5);
							if($fila5[0]=='Lista Opciones'){
								$cons6="Select Parametro from HistoriaClinica.ItemsxFormatos 
								where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and TipoControl='Lista Opciones' and id_item='$Item'";
								$res6=ExQuery($cons6); 
								$fila6=ExFetch($res6);
								$Vrs=explode(";",$fila6[0]);
								$consxxx="select VrItem from HistoriaClinica.IndicadoresxHC where Compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and TablaFormato='$TablaFormato' and item=$Item";
								$resxxx=ExQuery($consxxx);
								while($filaxxx=ExFetch($resxxx))
								{
									$Valores[$filaxxx[0]]=$filaxxx[0];	
								}	
								?>
								<tr> 
									<td class="encabezado2VerticalInvertido" >VALOR ITEM</td>
									<td>         
										<select name="VrItem" onChange="document.FORMA.submit()"><option></option>
											<?	foreach($Vrs as $Parametros){
													if(!$Valores[$Parametros])
													{
														if($VrItem==$Parametros){
															echo "<option value='$Parametros' selected>$Parametros</option>";
														}
														else{
															echo "<option value='$Parametros'>$Parametros</option>";
														}
													}
												}?>
										</select>         
									</td>
								</tr>
							<?		if($VrItem==''){$VrItem='999999';}
							}
							else{
								$VrItem=NULL;
							}
							?>
							 <tr>
							<?	if($VrItem){$VrI="and vritem='$VrItem'";}
							$cons7="select item from historiaclinica.IndicadoresxHC where compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and item='$Item' $VrI";
							//echo $cons7;
							$res7=ExQuery($cons7);
							if(ExNumRows($res7)>0)
							{
								?>
								<td colspan="2" class="mensaje1">Ya ha sido registrado un indicador para el item</td>	   
							<?	
							}
							else
							{
								if(!$Item){$Item='-1';}
								//if(!$VrItem){$VrItem='-1';}?>
								<td class="encabezado2VerticalInvertido">INDICADOR</td>
								<td>   
								<input type="text" name="Indicador" value="<? echo $Indicador?>" onKeyDown="xLetra(this)" onKeyUp="xletra(this)" />    
								</td>
							<?	
							}?>
							</tr>            
							</table>
							<center>
							<input type="submit" class="boton2Envio" name="Guardar" value="Guardar" />
							<input type="button" class="boton2Envio" name="Cancelar" value="Cancelar" onClick="location.href='IndicadoresxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&TablaFormato=<? echo $TablaFormato?>'">  	
							</center>
							<?
							if($sub&&!$Ya)
							{?><script language="javascript">document.FORMA.Ya.value=1;document.FORMA.submit();</script><? }?>
					</form>
				</div>	
			</body>
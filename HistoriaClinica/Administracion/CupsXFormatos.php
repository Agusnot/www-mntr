		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
			if($Guardar)
			{		
				$Fecha=date("Y-m-d H:i:s");
				$insertar="Insert into HistoriaClinica.CUPSxFormatos (UsuarioCre,FechaCre,TipoFormato,Formato,Cargo,Item,VrItem,CUP,Compania)
				values('$usuario[0]','$Fecha','$TF','$NewFormato','$Cargo','$Item','$VrItem','$Cup','$Compania[0]')";
				//echo $insertar;
				$res=ExQuery($insertar);
				echo"<script languaje='javascript'> location.href='CupsXFormatos.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF';</script>";
			}
			if($Eliminar)
			{
				$eliminar="delete from HistoriaClinica.CUPSxFormatos where Item='$Item' and Cup='$Cup' and Compania='$Compania[0]' and CUPSxFormatos.TipoFormato='$TF'
				and CUPSxFormatos.Formato='$NewFormato' and fechacre='$Fecha'";
				//echo $eliminar;
				$reselim=ExQuery($eliminar);
				echo"<script languaje='javascript'> location.href='CupsXFormatos.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF';</script>";
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
						//if(document.form1.Cargo.value==""){	alert("Debe seleccionar un Cargo!!!");	return false;}
						if(document.form1.Item.value==""){	alert("Debe seleccionar un Item!!!");	return false;}
						if(document.form1.Cup.value==""){ alert("Debe seleccionar un CUP!!!");return false;}
						if(document.form1.VrItem!=null){				
							if(document.form1.VrItem.value==""){
								alert("Debe seleccionar un Valor del Item!!!");	return false;
							}
						}
						
					}
					function BuscarCup(Cod,Nom,Cargo,Item,Valor,Formato,TipoFormato)
					{
						frames.FrameOpener.location.href="BuscarCup.php?DatNameSID=<? echo $DatNameSID?>&Codigo="+Cod+"&Nombre="+Nom+"&Cargo="+Cargo+"&Item="+Item+"&Valor="+Valor+"&Formato="+Formato+"&TipoFormato="+TipoFormato;
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top=130;
						document.getElementById('FrameOpener').style.left=10;
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='98%';
						document.getElementById('FrameOpener').style.height='70%';
					}
				</script>
			</head>
			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<? if (!$Nuevo){?>
							<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>        
								<tr>							
									<td class="encabezado2Horizontal">CARGO</td>
									<td class="encabezado2Horizontal">ITEM</td>
									<td class="encabezado2Horizontal">VALOR</td>
									<td class="encabezado2Horizontal" colspan="2">CUP</td>
								 </tr>
								  <? while ($fila=ExFetchArray($res)){
										$valor=$fila['cup'];
										//$cons2="Select * from ContratacionSalud.CUPS where Codigo='$valor'";
										//$rescons2=ExQuery($cons2,$conex);
										//$fila2=ExFetch($rescons2);
										?>
									  <tr >
										<td><? echo $fila['cargo']?>&nbsp;</td>
										<td><? echo $fila['item']?>&nbsp;</td>
										<td><? echo $fila['vritem']?>&nbsp;</td>
										<td><? echo $fila['codigo']."-".$fila['nombre'] ?></td>
										<td><a href='CupsXFormatos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Fecha=<? echo $fila['fechacre']?>&Item=<? echo $fila['id_item']?>&Cup=<? echo $fila['codigo']?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'><img src='/Imgs/b_drop.png' border=0></a></td>
									  </tr>
								 <? }
								if($Block!=1){?>
									<tr bgcolor="#ffffff">
										<td colspan="5" style="text-align:center"> 
											<input type="button" name="Nuevo" class="boton2Envio" value="Nuevo"  
											onClick="location.href='CupsXFormatos.php?DatNameSID=<? echo $DatNameSID?>&Nuevo=1&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">	        	
									</tr>
								<?	}?>    
							</table>
					<? }
					   else {
							?>
							<form name="form1" action="" onSubmit="return ValidaForma()">       
							<table width="100%" class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							  <tr>
								<td class="encabezado2Vertical">CARGO</td>        
								<td colspan="3">	<? 	
										$cons3="Select cargos from Salud.Cargos,historiaclinica.permisosxformato where cargos.Asistencial=1 and cargos.compania='$Compania[0]' and 
										permisosxformato.compania='$Compania[0]' and permisosxformato.permiso='Escritura' and perfil=cargos and formato='$NewFormato' and tipoformato='$TF'";				
										$rescons3=ExQuery($cons3,$conex);
											?>
										   <select name="Cargo" onChange="document.form1.submit()">
										   <option value="">Cualquiera</option>
										  <? while($fila3=ExFetch($rescons3))
											{
												if($Cargo==$fila3[0]){echo "<option selected value='$fila3[0]'>$fila3[0]</option>";}
												else{echo "<option value='$fila3[0]'>$fila3[0]</option>";}
											
											}?> 
										   </select>       	
								</td>
							  </tr>
							 <tr>
								<td class="encabezado2Vertical">ITEM</td>
								<td  colspan="3">
										<? /*<select name="Item" onChange="BuscaItems.location.href='BuscaItems.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Item='+this.value">
										<option>
										<?
											$cons4="Select Item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and TipoControl='Lista Opciones'";
											$res4=ExQuery($cons4);
											while($fila4=ExFetch($res4))
											{
												echo "<option value='$fila4[0]'>$fila4[0]</option>";			
											}
										?>
										</select>*/
										$cons4="select item,id_item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'  and (titulo!=1 or titulo is null) and estado='AC'
										order by item";
										$res4=ExQuery($cons4);?>
										<select name="Item" onChange="document.form1.submit()">
										<option></option>
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
						 <?	if(!$Item){$Item=-1;}
							$cons5="select tipocontrol from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and id_item='$Item'";
							$res5=ExQuery($cons5);  
							$fila5=ExFetch($res5);
							if($fila5[0]=='Lista Opciones'){
								$cons6="Select Parametro from HistoriaClinica.ItemsxFormatos 
								where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]' and TipoControl='Lista Opciones' and id_item='$Item'";
								$res6=ExQuery($cons6); 
								$fila6=ExFetch($res6);
								$Vrs=explode(";",$fila6[0]);?>
								<tr align="center"> 
									<td class="encabezado2Vertical">VALOR ITEM</td>
									<td colspan="3">         
										<select name="VrItem" onChange="document.form1.submit()"><option></option>
											<?	foreach($Vrs as $Parametros){
												if($VrItem==$Parametros){
													echo "<option value='$Parametros' selected>$Parametros</option>";
												}
												else{
													echo "<option value='$Parametros'>$Parametros</option>";
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
									
									$cons7="select item from historiaclinica.cupsxformatos where compania='$Compania[0]' and Formato='$NewFormato' and TipoFormato='$TF' and cargo='$Cargo' $VrI
									 and item='$Item'";
									//echo $cons7;
									$res7=ExQuery($cons7);
									if(ExNumRows($res7)>0){
											?>
											<td colspan="5" class="mensaje1">Ya ha sido registrado un CUP para el item</td>	
										</tr>
										<tr>
											<td bgcolor="#ffffff" colspan="5" align="center"  style="color:white; font-weight:bold" bgcolor="<? echo $Estilo[1]?>" width="100%">
										<?			
									}
									else{
											if(!$Item){$Item='-1';}
											//if(!$VrItem){$VrItem='-1';}?>
											<td class="encabezado2Vertical">C&Oacute;DIGO CUP</td>
											<td width="14%">   
												<input name="Cup" id="Cup" readonly onFocus="BuscarCup(this.value,NomCup.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')" onKeyDown="xLetra(this);BuscarCup(this.value,NomCup.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')"  onKeyUp="xLetra(this);BuscarCup(this.value,NomCup.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')" <? if($Cup!=''){?> value="<? echo $Cup?>"<? }?> />            
											</td>
											<td class="encabezado2Vertical">NOMBRE CUP</td>
											<td width="55%">
												<input type="text" name="NomCup" id="NomCup" readonly style=" width:550px" onFocus="BuscarCup(Cup.value,this.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')" onKeyDown="xLetra(this);BuscarCup(Cup.value,this.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')" 	onKeyUp="xLetra(this);BuscarCup(Cup.value,this.value,Cargo.value,'<? echo $Item?>','<? echo $VrItem?>','<? echo $NewFormato?>','<? echo $TF?>')" value="<? echo $NomCup?>">
										</tr>
										<tr>
										<td colspan="5" width="100%" style="text-align:center;">
											<input type="submit" name="Guardar" id="Guardar" value="Guardar" class="boton2Envio">									
										<?	
									}?>
									<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar"  onClick="location.href='CupsXFormatos.php?DatNameSID=<? echo $DatNameSID?>&Nuevo=0&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'"></td>
								</tr>
							</table>  	
					<? }?>
					<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
					<input type="Hidden" name="TF" value="<? echo $TF?>">
					<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
					<input type="hidden" name="Nuevo" value="<? echo $Nuevo?>">

					<iframe name="BuscaItems"  id="BuscaItems" src="BuscaItems.php?DatNameSID=<? echo $DatNameSID?>" width="100%" style="" frameborder="0"></iframe>
					</form>
					<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
				</div>
			</body>
	</html>		
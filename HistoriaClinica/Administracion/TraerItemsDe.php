		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Copiar){
				$cons="delete from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
				$res=ExQuery($cons);

				$cons="select item,orden,pantalla,tipodato,liminf,limsup,longitud,tipocontrol,ancho,alto,defecto,parametro,obligatorio,traerde,lineasola,cierrafila,titulo,imagen,tip,mensaje
				,tftraerde,campotraerde,subformato,fechaconsul,finalidad,causaext,estado,id_item from historiaclinica.itemsxformatos 
				where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato' order by pantalla,orden";
				//echo $cons;
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					if($fila[4]==''){$fila[4]="0";}if($fila[5]==''){$fila[5]="0";}if($fila[6]==''){$fila[6]="0";}if($fila[8]==''){$fila[8]="0";}if($fila[9]==''){$fila[9]="0";}			
					if($fila[14]==''){$fila[14]="0";}if($fila[15]==''){$fila[15]="0";}if($fila[16]==''){$fila[16]="0";}if($fila[22]==''){$fila[22]="0";}
					if($fila[23]!=''){$FechCons=",fechaconsul";$FechCons2=",'$fila[23]'";}
					$cons2="insert into historiaclinica.itemsxformatos
					(item,orden,pantalla,tipodato,liminf,limsup,longitud,tipocontrol,ancho,alto,defecto,parametro,obligatorio,traerde,lineasola,cierrafila,titulo,imagen,tip,mensaje
					,tftraerde,campotraerde,subformato,finalidad,causaext,estado,formato,tipoformato,id_item,compania $FechCons) values										
					('$fila[0]',$fila[1],$fila[2],'$fila[3]',$fila[4],$fila[5],$fila[6],'$fila[7]',$fila[8],$fila[9],'$fila[10]','$fila[11]','$fila[12]','$fila[13]',$fila[14],$fila[15],'$fila[16]'			,'$fila[17]','$fila[18]','$fila[19]','$fila[20]','$fila[21]','$fila[22]','$fila[24]','$fila[25]','$fila[26]','$NewFormato','$TF',$fila[27],'$Compania[0]' $FechCons2)";
					$res2=ExQuery($cons2);
				}?>
				<script language="javascript">
					alert("Items copiados correctamente");
					location.href="ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>";
				</script>	
		<?	}
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
					function Validar()
					{
						if(document.FORMA.TipoFormato.value==""){alert("Debe seleccionar el Tipo de Formato!!!");return false;}
						if(document.FORMA.Formato.value==""){alert("Debe seleccionar el Formato!!!");return false;}
						if(confirm("Al realizar la copia de los items a este formato, se eliminaran los items registrados hasta el momento\n¿Esta seguro de continuar con la operacion?")){
						}
						else{
							return false;
						}
					}
				</script>
			</head>

			<body <?php echo $backgroundBodyMentor; ?>>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">
					<form name="FORMA" method="post" onsubmit="return Validar()">
						<table class="tabla2"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>	
							<tr>
								<td class="encabezado2Vertical">TIPO FORMATO</td>        
								<td>
									<?	$cons="select tipoformato from historiaclinica.formatos where compania='$Compania[0]' group by tipoformato order by tipoformato";		
										$res=ExQuery($cons);?>
										<select name="TipoFormato" onchange="document.FORMA.submit();"><option></option>
									<?	while($fila=ExFetch($res)){
											if($fila[0]==$TipoFormato){
												echo "<option value='$fila[0]' selected>$fila[0]</option>";
											}
											else{
												echo "<option value='$fila[0]'>$fila[0]</option>";
											}
										}?>
									   </select>
								</td>
							</tr>
							<tr>
								<td  class="encabezado2Vertical">FORMATO</td>        
								<td>
								<?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato!='$NewFormato' group by formato order by formato";
									$res=ExQuery($cons);?>
									<select name="Formato">
								<?	while($fila=ExFetch($res)){
										if($fila[0]==$Formato){
											echo "<option value='$fila[0]' selected>$fila[0]</option>";
										}
										else{
											echo "<option value='$fila[0]'>$fila[0]</option>";
										}
									}?>
									</select>
								</td>
							</tr>
							<tr align="center">
								<td colspan="4">
									<input type="submit" name="Copiar" class="boton2Envio" value="Copiar Items"/>
								</td>
							</tr>
						</table>
						<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
						<input type="hidden" name="TF" value="<? echo $TF?>">
						<input type="hidden" name="NewFormato" value="<? echo $NewFormato?>">
					</form>
				</div>	
			</body>
		</html>

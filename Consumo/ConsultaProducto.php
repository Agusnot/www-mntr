		<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");		
		include_once("General/Configuracion/Configuracion.php");
		$ND = getdate();
		if(!$Anio){$Anio = $ND[year];}
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
						if(document.FORMA.AlmacenPrincipal.value != "")
						{
							document.FORMA.target = "BusquedaProducto";
							document.FORMA.action = "BusquedaProducto.php?Buscar=1&AlmacenPrincipal=<? echo $AlmacenPrincipal?>&DatNameSID=<? echo $DatNameSID?>";
							document.FORMA.submit();
						}
					}
					function ValidarS()
					{
						document.FORMA.target = "";
						document.FORMA.action = "";
						document.FORMA.submit();
					}
					function Enter(e)
					{
						if(e.keyCode == 13){Validar();}
					}
				</script>
			</head>	
			<body <?php echo $backgroundBodyMentor; ?>>
				<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "FICHA PRODUCTO";										
					
					mostrarRutaNavegacionEstatica($rutaarchivo);					
				
				?>
				<div <?php echo $alignDiv2Mentor; ?> class="div2">		
					<form name="FORMA" method="post">
						<table class="tabla2"    <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="6" class="encabezado2Horizontal"> FICHA PRODUCTO </td>
							</tr>
							<!---<tr>
								
								<td colspan="6" class="encabezadoGrisaceo" style="text-align:right;padding-right:15px;">A&Ntilde;O : 
									<select name="Anio" onChange="FORMA.submit()" />
										<?
											/*$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
											$res = ExQuery($cons);
											while($fila = ExFetch($res))
											{
												if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else {echo "<option value='$fila[0]'>$fila[0]</option>";}
											}*/
										?>
									</select>
								</td>
							</tr>-->
							<?
							if($Anio){
								?>
								<input type="Hidden" name="b" value="<? echo $b?>">
								<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
								<tr>
									<td class="encabezado2VerticalInvertido"> A&Ntilde;O</td>
									<td> 
										<select name="Anio" onChange="FORMA.submit()" />
											<?
												$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
												$res = ExQuery($cons);
												while($fila = ExFetch($res)){
													if($Anio==$fila[0]){
														echo "<option selected value='$fila[0]'>$fila[0]</option>";
													}
													else {
														echo "<option value='$fila[0]'>$fila[0]</option>";
													}
												}
											?>
										</select>
									</td>
									
									<td class="encabezado2VerticalInvertido">
										ALMAC&Eacute;N
									</td>
									<td colspan="3">
										<select name="AlmacenPrincipal" style="width:100%;" onChange="FORMA.b.value=1;ValidarS();">
											<?
											$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
											$res = ExQuery($cons);
											while($fila = ExFetch($res)){
												if(!$c){$AlmacenGrupo=$fila[0];$c=1;}
												if($AlmacenPrincipal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
												else{echo "<option value='$fila[0]'>$fila[0]</option>";}
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">C&Oacute;DIGO 1 </td>
									<td>
										<input type="text" name="Codigo" maxlength="12" size="12" onKeyUp="xNumero(this);Enter(event);" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" />
									</td>
									<td class="encabezado2VerticalInvertido">C&Oacute;DIGO 2 </td>
									<td>
										<input type="text" name="Codigo2" maxlength="12" size="12" 	onKeyUp="xLetra(this);Enter(event);" onKeyDown="xLetra(this)"/>
									</td>
									<td class="encabezado2VerticalInvertido">C&Oacute;DIGO 3 </td>
									<td>
										<input type="text" name="Codigo3" maxlength="12" size="12" onKeyUp="xLetra(this);Enter(event);" onKeyDown="xLetra(this)"/>
									</td>
								</tr>
								<tr>
									<td class="encabezado2VerticalInvertido">NOMBRE </td>
									<td  colspan="2">
										<input type="text" name="NomPro" maxlength="100" style="width:100%;" value="<? echo $NomPro ?>" onKeyUp="xLetra(this);Enter(event);" onKeyDown="xLetra(this)"/>
									</td>
									<td class="encabezado2VerticalInvertido">GRUPO </td>										
									<td  colspan="2">
										<select name="Grupo" style="width:100%;">
											<option value="">Grupo</option>
												<?
												if(!$b)	{
													$cons = "Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenGrupo' and Compania='$Compania[0]' and Anio=$Anio
													order by Grupo asc";
												}
												else {
													$cons = "Select Grupo from Consumo.Grupos where AlmacenPpal='$AlmacenPrincipal' and Compania='$Compania[0]' and Anio=$Anio
													order by Grupo asc";
												}
												$res = ExQuery($cons);
												while($fila = ExFetch($res)) {
													if($Grupo==$fila[0]){
														echo "<option selected value='$fila[0]'>$fila[0]</option>";
													}
													else{
														echo "<option value='$fila[0]'>$fila[0]</option>";
													}
												}
												?>
										</select>
										
									</td>
								</tr>
								
							</table>
							<? echo "<input type='button' name='Buscar' class='boton2Envio' value='Buscar' onclick='Validar()' />";	?>
						</form> 
					<? } ?>
				</div>	
			</body>
		</html>	

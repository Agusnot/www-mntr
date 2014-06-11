		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			$ND = getdate();
			$Anio = $ND[year];
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			if($Guardar){
				if($NewFechaIni>=$NewFechaFin){
					echo "<span class='mensaje1'>La Fecha inicial de la vigencia no puede ser mayor o igual a la Fecha final</span>";	
				}
				else{
					if($PorcInc){
						$cons="Select AutoId from Consumo.CodProductos Where Compania='$Compania[0]' and Anio=$Anio";
						$res=ExQuery($cons);
						echo ExError();
						while($fila=ExFetch($res))
						{
							$cons1="Select ValorVenta,FechaIni,FechaFin from Consumo.TarifasxProducto 
							where AutoId='$fila[0]' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tarifario='$Tarifario' 
							order by FechaIni Desc";	
							$res1=ExQuery($cons1);
							$fila1=ExFetch($res1);
							if($Tipo=='Incremento')
							{
								$Valor=$fila1[0]+($fila1[0]*$PorcInc/100);
							}
							else
							{
								$Valor=$fila1[0]-($fila1[0]*$PorcInc/100);
							}
							if($Valor > 0)
							{
								$cons2="Insert into Consumo.TarifasxProducto (Compania,AlmacenPpal,Tarifario,AutoId,FechaIni,FechaFin,ValorVenta,Anio)
								values ('$Compania[0]','$AlmacenPpal','$Tarifario','$fila[0]','$NewFechaIni','$NewFechaFin','$Valor',$Anio)";
								$res2=ExQuery($cons2);
							}
							if($fila1[2]=='0000-00-00')
							{
								$cons3="Update Consumo.TarifasxProducto set FechaFin='$NewFechaIni' 
								where AutoId='$fila[0]' and Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Tarifario='$Tarifario'
								and FechaIni='$fila1[1]'";
								$res3=ExQuery($cons3);
							}
						}
					}
					else{
						?><script language="javascript">
						alert("No se hara Incremento en el precio");
						</script> <?	
					}
					?>
						<script language="javascript">
							location.href="ActuaTarifas.php?DatNameSID=<? echo $DatNameSID?>&AlmacenPpal=<? echo $AlmacenPpal?>&Tarifario=<? echo $Tarifario?>";
						</script>
					<?
				}
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
			<script language='javascript' src="/calendario/popcalendar.js"></script><script language="javascript">
				function Validar(x)	{
					if(x==0){
						
					}
					if(x==1){
						//alert("Pasa 1");
						document.FORMA.action= "ActuaTarifas.php";
						document.FORMA.submit();
					}
				}
			</script>
		</head>	
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "ALMAC&Eacute;N";
					$rutaarchivo[1] = "ACTUALIZACI&Oacute;N DE TARIFAS";
					$rutaarchivo[2] = "AJUSTE PORCENTUAL";
					mostrarRutaNavegacionEstatica($rutaarchivo);
				?>
			
			<div <?php echo $alignDiv2Mentor; ?> class="div2">
				<form name="FORMA" method="post" onSubmit="return Validar(0)">
					<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
					<input type="hidden" style="text-align:center; border-style:solid; background-color:#e5e5e5; font-weight:bold" name="AlmacenPpal" value="<? echo $AlmacenPpal;?>" />
					<input type="hidden" style="text-align:center; border-style:solid; background-color:#e5e5e5; font-weight:bold" name="Tarifario" value="<? echo $Tarifario;?>" />
							
						<table class="tabla2" width="500px"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
							<tr>
								<td colspan="4" class="encabezado2Horizontal"> AJUSTE PORCENTUAL </td>
							</tr>
							<tr>
								<td colspan="2" class="encabezado2VerticalInvertido">ALMAC&Eacute;N </td>
								<td colspan="3"> <?php  echo strtoupper($AlmacenPpal); ?></td>
							</tr>
							<tr>
								<td colspan="2" class="encabezado2VerticalInvertido">TARIFARIO</td>
								<td colspan="3"> <?php  echo strtoupper($Tarifario); ?></td>
							</tr>
							
								
							
							<tr>
								<td  class="encabezado2VerticalInvertido">PORCENTAJE </td>
								<td>
									<input type="text" name="PorcInc" size="5" maxlength="5" style="text-align:left;" 	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"/>
									%
								</td>
								<td  class="encabezado2VerticalInvertido">TIPO</td>
								<td>
									<select name="Tipo">
										<option value="Incremento">Incremento</option>
										<option value="Reduccion">Reduccion</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4" class="encabezadoGrisaceo" style="text-align:center;">VIGENCIA</td>
							</tr>
							<tr>
								<td colspan="2" class="encabezado2HorizontalInvertido" >
									DESDE<input type="text" name="NewFechaIni" style="width:100%;" onclick="popUpCalendar(this, FORMA.NewFechaIni, 'yyyy-mm-dd')"  value="<? echo $NewFechaIni; ?>" readonly="yes"  />
								</td>
								<td colspan="2" class="encabezado2HorizontalInvertido">
									HASTA<input type="text" name="NewFechaFin" style="width:100%;" 	onclick="popUpCalendar(this, FORMA.NewFechaFin, 'yyyy-mm-dd')"  value="<? echo $NewFechaFin; ?>" readonly="yes"  />
								</td>
							</tr>
						</table>
						<input type="submit" name="Guardar" class="boton2Envio" value="Guardar" />
						<input type="button" name="Cancelar" class="boton2Envio" value="Cancelar" onClick="Validar(1)" />
				</form>
			</div>
		</body>
	</html>	
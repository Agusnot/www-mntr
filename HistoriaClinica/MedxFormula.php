	
	
	<?php
		session_start();
		if($DatNameSID){session_name("$DatNameSID");}
		
		include_once("Funciones.php");
	
	// Inicia definicion de funciones
		
		
		function insertarRegistro($formato,$tipoformato,$idhistoria, $cedula, $compania,$usuario, $iditem, $orden, $codmedicamento, $nombre_medicamento, $unidad_medida, $presentacion, $via_suministro, $cantidad, $detalle){
			$cons = "INSERT INTO HistoriaClinica.MedxFormula (formato, tipo_formato, id_historia, cedula, compania, usuario, id_item, orden, cod_medicamento, nombre_medicamento, unidad_medida, presentacion, via_suministro, cantidad, detalle) VALUES
			('$formato','$tipoformato','$idhistoria', '$cedula', '$compania', '$usuario', $iditem, $orden, '$codmedicamento', '$nombre_medicamento', '$unidad_medida', '$presentacion', '$via_suministro', '$cantidad', '$detalle')";
			ExQuery($cons);
		}
		
				
		
		
		function consultarOrden($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT MAX(orden) AS maximo FROM HistoriaClinica.MedxFormula WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item= $iditem  ";
			$res = ExQuery($cons);
			$fila = ExFetchArray($res);
			$orden = $fila["maximo"];
				if(!isset($orden)){
					$orden = 0;
				}
			$orden = $orden + 1;
			
			return $orden;
		
		}
		
		function eliminarRegistro($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem, $orden){
			$cons = "DELETE FROM HistoriaClinica.MedxFormula  WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem' AND orden = '$orden'";
			ExQuery($cons);
		}
		
		function seleccionarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT * FROM HistoriaClinica.MedxFormula WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem' ORDER BY orden ASC";
			$res = ExQuery($cons);
			return $res;
		}
		
		function contarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT COUNT(*) AS conteo FROM HistoriaClinica.MedxFormula WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem'";
			$res = ExQuery($cons);
			$fila = ExFetchArray($res);
			$conteo = $fila["conteo"];
				if(!isset($conteo)){
					$conteo = 0;
				}
			return $conteo;	
		}
		
		
		
		
		
		
		function mostrarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$listadoRegistros = seleccionarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem);
			
				while ($registro = ExFetchArray($listadoRegistros)){
					
					echo "<tr>";
					echo "<td> <input  type='text' value='$registro[nombre_medicamento]' size='20' style='border:1px;border-style:outset;border-color:#333;' disabled > </td>";
					echo "<td> <input  type='text' value='$registro[unidad_medida]' size='15' style='border:1px;border-style:outset;border-color:#333;' disabled > </td>";
					echo "<td> <input type='text'  value='$registro[presentacion]' size='20' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					echo "<td> <input type='text'  value='$registro[via_suministro]'  size='20' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					echo "<td> <input type='text'  value='$registro[cantidad]'  size='10' style='border:1px;border-style:outset;border-color:#333;text-align:center;' disabled> </td>";
					echo "<td> <input type='text'  value='$registro[detalle]'  size='20' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					?>
						<td> <button type="button" onclick="javascript:location.href='MedxFormula.php?tipoformato=<?php echo $_GET["tipoformato"];?>&formato=<?php echo $_GET["formato"];?>&idhistoria=<?php echo $_GET["idhistoria"];?>&cedula=<?php echo $_GET["cedula"];?>&compania=<?php echo $_GET["compania"];?>&usuario=<?php echo $_GET["usuario"];?>&iditem=<?php echo $_GET["iditem"];?>&insercion=<?php echo $_GET["insercion"];?>&accion=eliminar&orden=<?php echo $registro['orden'];?>';" style="color:#FF0000;font-size:11px;background-color:#DDD;"> Eliminar </button> </td>
					<?php
					echo "</tr>";	
				}
		}
		
		
		function consultarViasSuministro($compania){
			$cons = "SELECT * FROM Salud.ViadeSuministro WHERE compania = '$compania' ORDER BY prioridad ASC ";
			$res = ExQuery($cons);
			return $res;		
		} 
		
		function listarViasSuministro($compania,$id,$tabindex){
			$resultado = consultarViasSuministro($compania);
			
			if (ExNumRows($resultado) > 0){
				
				?>
				<select name="via_suministro" id="via_suministro<?php echo $id;?>"  style="border:1px;border-style:outset;border-color:#333;width:100%;text-align-center;" tabindex="<?php echo $tabindex; ?>">
					<option value="">&nbsp;</option>
					<?php
					while ($registro = ExFetchArray($resultado)){
						?><option value="<?php echo $registro['nombrevia']; ?>"><?php echo $registro['nombrevia']; ?> </option><?php
					}
					?>
				</select>
				<?php
			}
			else {
				?>
				<input type="text" name="via_suministro<?php echo $id;?>" id="via_suministro<?php echo $id;?>"  size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="4">
				<?php
			}
		
		}
		
		
		function definirAlmacenFarmaceutico($compania){
			$cons = "SELECT almacenppal FROM Consumo.Almacenesppales WHERE compania = '$compania' AND ssfarmaceutico = '1'";
			$res = ExQuery($cons);
			$fila = ExFetchArray($res);
			$almacen = $fila['almacenppal'];
			
			return $almacen;		
		} 
		
		
		function consultarMedicamentos($compania){
			$almacen = definirAlmacenFarmaceutico($compania);
			$cons = "SELECT autoid, codigo1, nombreprod1, unidadmedida, presentacion FROM Consumo.CodProductos WHERE compania = '$compania' AND almacenppal = '$almacen' ORDER BY nombreprod1 ASC";
			$res = ExQuery($cons);
			return $res;		
		}
		
		function listarMedicamentos($compania){
			$resultado = consultarMedicamentos($compania);
			
			if (ExNumRows($resultado) > 0){
				
				?>
				<select name="codmedicamento2" id="codmedicamento2"  style="border:1px;border-style:outset;border-color:#333;font-size:11px;width:100%;text-align-center;" tabindex="7">
					<option value="">&nbsp;</option>
					<?php
					while ($registro = ExFetchArray($resultado)){
						?><option value="<?php echo $registro['autoid']; ?>"><?php echo strtoupper($registro['nombreprod1'])." ".strtoupper($registro['unidadmedida'])." ".strtoupper($registro['presentacion']); ?> </option><?php
					}
					?>
				</select>
				<?php
			}
			else {
				?>
				<input type="text" name="via_suministro" id="via_suministro"  size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="4">
				<?php
			}
		
		}
		
		function caracteristicasMedicamento($compania, $autoid){
			$almacen = definirAlmacenFarmaceutico($compania);
			// La consulta se realiza con base en la composicion de la llave primaria
			$cons = "SELECT * FROM Consumo.CodProductos WHERE compania = '$compania' AND almacenppal = '$almacen' AND autoid = '$autoid' AND anio = DATE_PART('year',now())";
			$res = ExQuery($cons);
			if (ExNumRows($res)>0){
				$fila = ExFetchArray($res);
			}
			
			return $fila;
		}
		
		
	
	//Termina definicion de funciones
	
	
				
		
			if (isset($_GET["accion"])){
				if($_GET["accion"]=="guardar"){
					// Consulta el orden que se le debe asignar al registro
					
					if ($_GET['form'] == 1){
							$orden = consultarOrden($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"], $_GET["iditem"]);
							
							$codmedicamento = 'NULL';
							
							$nombremedicamento = $_GET["nombremedicamento"];
							$nombremedicamento = normalizarCampoRegistro($nombremedicamento);
							
							$unidadmedida = $_GET["unidadmedida"];
							$unidadmedida = normalizarCampoRegistro($unidadmedida);
							
							$presentacion = $_GET["presentacion"];
							$presentacion = normalizarCampoRegistro($presentacion);
							
							$viasuministro = $_GET["viasuministro"];
							$viasuministro = normalizarCampoRegistro($viasuministro);
							
							$cantidad = $_GET["cantidad"];
							$cantidad = normalizarCampoRegistro($cantidad);
							
							$detalle = $_GET["detalle"];
							$detalle = normalizarCampoRegistro($detalle);
							
						
							insertarRegistro($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"],$_GET["usuario"],$_GET["iditem"], $orden, $codmedicamento,$nombremedicamento, $unidadmedida , $presentacion, $viasuministro,$cantidad, $detalle  );
					}
					elseif ($_GET['form'] == 2){
							$orden = consultarOrden($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"], $_GET["iditem"]);
							
							$codmedicamento = $_GET["codmedicamento"];
							
							$caracteristicas = caracteristicasMedicamento($_GET['compania'], $codmedicamento); // Consulta las caracteriticas del medicamento con base en el codigo
							
							if (isset($caracteristicas)){
								$nombremedicamento = $caracteristicas['nombreprod1'];
								$nombremedicamento = normalizarCampoRegistro($nombremedicamento);
								
								$unidadmedida = $caracteristicas['unidadmedida'];
								$unidadmedida = normalizarCampoRegistro($unidadmedida);
								
								$presentacion = $caracteristicas['presentacion'];
								$presentacion = normalizarCampoRegistro($presentacion);	
							}
							else {
								$nombremedicamento = "undefined";
								$unidadmedida = "undefined";
								$presentacion = "undefined";								
							}
							
							
							
							$viasuministro = $_GET["viasuministro"];
							$viasuministro = normalizarCampoRegistro($viasuministro);
							
							$cantidad = $_GET["cantidad"];
							$cantidad = normalizarCampoRegistro($cantidad);
							
							$detalle = $_GET["detalle"];
							$detalle = normalizarCampoRegistro($detalle);
							
						
							insertarRegistro($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"],$_GET["usuario"],$_GET["iditem"], $orden, $codmedicamento,$nombremedicamento, $unidadmedida , $presentacion, $viasuministro,$cantidad, $detalle  );
					}
				}
				
				if($_GET["accion"]=="eliminar"){
					
					eliminarRegistro($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"], $_GET["iditem"], $_GET["orden"]);
				
				}
			
			}
		
	
	?>
	<html>
		<head>
			<meta  charset="UTF-8" />
			<script language="javascript" src="/Funciones.js"></script>
			<script language="javascript">
				function guardarMed1(){
					if(document.getElementById('nombre_medicamento1').value == ''){
						document.getElementById('msjNombreMed1').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjNombreMed1').innerHTML= ''
					} 
					
					if(document.getElementById('unidad_medida1').value == ''){
						document.getElementById('msjUnidadMedida1').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjUnidadMedida1').innerHTML= ''
					} 
					
					if(document.getElementById('presentacion1').value == ''){
						document.getElementById('msjPresentacion1').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjPresentacion1').innerHTML= ''
					} 
					
					if(document.getElementById('via_suministro1').value == ''){
						document.getElementById('msjViaSuministro1').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjViaSuministro1').innerHTML= ''
					} 
					
					
					
					if(document.getElementById('cantidad1').value == ''){
						document.getElementById('msjCantidad1').innerHTML= 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjCantidad1').innerHTML= ''
					} 
					
					if(document.getElementById('detalle1').value == ''){
						document.getElementById('msjDetalle1').innerHTML = 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjDetalle1').innerHTML= ''
					} 
					
					//var codmedicamento = document.getElementById('codmedicamento1').value;
					var nombremedicamento = document.getElementById('nombre_medicamento1').value;
					var unidadmedida = document.getElementById('unidad_medida1').value;
					var presentacion = document.getElementById('presentacion1').value;
					var viasuministro = document.getElementById('via_suministro1').value;
					var cantidad = document.getElementById('cantidad1').value;
					var detalle = document.getElementById('detalle1').value;					
									
					window.location.href='MedxFormula.php?tipoformato=<?php echo $_GET["tipoformato"];?>&formato=<?php echo $_GET["formato"];?>&idhistoria=<?php echo $_GET["idhistoria"];?>&cedula=<?php echo $_GET["cedula"];?>&compania=<?php echo $_GET["compania"];?>&usuario=<?php echo $_GET["usuario"];?>&iditem=<?php echo $_GET["iditem"];?>&insercion=<?php echo $_GET["insercion"];?>&accion=guardar&form=1&nombremedicamento='+nombremedicamento+'&unidadmedida='+unidadmedida+'&presentacion='+presentacion+'&viasuministro='+viasuministro+'&cantidad='+cantidad+'&detalle='+detalle;
				}
				
				
				function guardarMed2(){
					if(document.getElementById('codmedicamento2').value == ''){
						document.getElementById('msjCodMedicamento2').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjCodMedicamento2').innerHTML= ''
					} 
					
										
					if(document.getElementById('via_suministro2').value == ''){
						document.getElementById('msjViaSuministro2').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjViaSuministro2').innerHTML= ''
					} 
					
					
					
					if(document.getElementById('cantidad2').value == ''){
						document.getElementById('msjCantidad2').innerHTML= 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjCantidad2').innerHTML= ''
					} 
					
					if(document.getElementById('detalle2').value == ''){
						document.getElementById('msjDetalle2').innerHTML = 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjDetalle2').innerHTML= ''
					} 
					
					var codmedicamento = document.getElementById('codmedicamento2').value;
					var viasuministro = document.getElementById('via_suministro2').value;
					var cantidad = document.getElementById('cantidad2').value;
					var detalle = document.getElementById('detalle2').value;					
									
					window.location.href='MedxFormula.php?tipoformato=<?php echo $_GET["tipoformato"];?>&formato=<?php echo $_GET["formato"];?>&idhistoria=<?php echo $_GET["idhistoria"];?>&cedula=<?php echo $_GET["cedula"];?>&compania=<?php echo $_GET["compania"];?>&usuario=<?php echo $_GET["usuario"];?>&iditem=<?php echo $_GET["iditem"];?>&insercion=<?php echo $_GET["insercion"];?>&accion=guardar&form=2&codmedicamento='+codmedicamento+'&viasuministro='+viasuministro+'&cantidad='+cantidad+'&detalle='+detalle;
				}
			</script>
		</head>
			<?php
				if (strtoupper($_GET["insercion"])=="MULTILINEA"){
					$eventoOnLoad = 'onLoad="document.getElementById'."('nombre_medicamento1')".'.focus();"';
				} else {
					$eventoOnLoad = "";
				}
			?>
		<body <?php echo $eventoOnLoad;?>>
			<form name="formMeds" id="formMeds" method="post" action="" >
				<table  width="100%"  cellspacing="0" cellpadding="5"  style="border:0px;margin-top:10px;text-align:center;" >
					<tr>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> MEDICAMENTO</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> UNIDAD DE MEDIDA</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> PRESENTACI&Oacute;N</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> VIA DE SUMINISTRO</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> CANTIDAD</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> DETALLE</p> </td>
						<td> &nbsp; </td>
					</tr>
					
					<?php 
						$conteo = contarRegistros($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"], $_GET["cedula"], $_GET["compania"], $_GET["iditem"]);
						if ($conteo > 0){
							
							mostrarRegistros($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"], $_GET["cedula"], $_GET["compania"], $_GET["iditem"]);
							$botonGuardar = 1;
								// Verifica si la insercion es unilinea o multilinea
								if(strtoupper($_GET["insercion"])=="MULTILINEA"){
									$agregarLinea = 1;
								}
								
								if(strtoupper($_GET["insercion"])=="UNILINEA"){
									$agregarLinea = 0;
									$botonGuardar = 1;
								}
							
						} else {
							
							$botonGuardar = 0;							
							$agregarLinea = 1;
						}
						
					?>
					
					<?php 
						if ($agregarLinea == 1) {
							?>
							<tr>
								<td> <input  type="text"  name="nombre_medicamento1" id="nombre_medicamento1" size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="1"> </td>
								<td> <input  type="text"  name="unidad_medida1" id="unidad_medida1" size="15" style="border:1px;border-style:outset;border-color:#333;" tabindex="2"> </td>
								<td> <input type="text" name="presentacion1" id="presentacion1"  size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="3"> </td>
								<td> <?php  listarViasSuministro($_GET['compania'], 1, 4);?>	</td>
								<td> <input type="text" name="cantidad1" id="cantidad1"  size="10" onKeyUp=xNumero(this) onKeyDown=xNumero(this) onBlur=campoNumero(this) style="border:1px;border-style:outset;border-color:#333;text-align: center;" tabindex="5"> </td>
								<td> <input type="text" name="detalle1" id="detalle1"  size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="6"> 	</td>
								<td> <button type="button" onclick="javascript:guardarMed1();" style="color:#0068D4;font-size:11px;background-color:#DDD;"> Guardar</button> </td>
								
							</tr>
							
							<tr> 
								<td id="msjNombreMed1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjUnidadMedida1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjPresentacion1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjViaSuministro1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjCantidad1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjDetalle1" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>								
								
							</tr>
							
							
							<tr>
								<td colspan="3"> <?php listarMedicamentos($_GET['compania']);?>  </td>								
								<td> <?php  listarViasSuministro($_GET['compania'],2,8);?>	</td>
								<td> <input type="text" name="cantidad2" id="cantidad2"  size="10" onKeyUp=xNumero(this) onKeyDown=xNumero(this) onBlur=campoNumero(this) style="border:1px;border-style:outset;border-color:#333;text-align: center;" tabindex="9"> </td>
								<td> <input type="text" name="detalle2" id="detalle2"  size="20" style="border:1px;border-style:outset;border-color:#333;" tabindex="10"> </td>
								<td> <button type="button" onclick="javascript:guardarMed2();" style="color:#0068D4;font-size:11px;background-color:#DDD;"> Guardar</button> </td>
								
							</tr>
							
							<tr> 
								<td colspan = "3" id="msjCodMedicamento2" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>								
								<td id="msjViaSuministro2" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjCantidad2" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjDetalle2" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<input type="hidden" name="formulario" value="1">
								
								
							</tr>
							
							<?php
						}	
							
							
						?>	
						
							
							
					
				</table>
			</form>
		</body>
	</html>
	
	

	
	
	<?php
		session_start();
		if($DatNameSID){session_name("$DatNameSID");}
		
		include_once("Funciones.php");
	
	// Inicia definicion de funciones
		
		
		function insertarRegistro($formato,$tipoformato,$idhistoria, $cedula, $compania,$usuario, $iditem, $orden, $codmedicamento, $nombre_medicamento, $presentacion, $posologia, $tiempo_tratamiento){
			$cons = "INSERT INTO HistoriaClinica.Medsxformato (formato, tipo_formato, id_historia, cedula, compania, usuario, id_item, orden, cod_medicamento, nombre_medicamento, presentacion, posologia, tiempo_tratamiento) VALUES
			('$formato','$tipoformato','$idhistoria', '$cedula', '$compania', '$usuario', $iditem, $orden, '$codmedicamento', '$nombre_medicamento', '$presentacion', '$posologia', '$tiempo_tratamiento')";
			ExQuery($cons);
		}
		
				
		
		
		function consultarOrden($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT MAX(orden) AS maximo FROM HistoriaClinica.MedsxFormato WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item= $iditem  ";
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
			$cons = "DELETE FROM HistoriaClinica.Medsxformato  WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem' AND orden = '$orden'";
			ExQuery($cons);
		}
		
		function seleccionarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT * FROM HistoriaClinica.Medsxformato WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem' ORDER BY orden ASC";
			$res = ExQuery($cons);
			return $res;
		}
		
		function contarRegistros($formato,$tipoformato,$idhistoria, $cedula, $compania, $iditem){
			$cons = "SELECT COUNT(*) AS conteo FROM HistoriaClinica.Medsxformato WHERE formato = '$formato' AND tipo_formato = '$tipoformato' AND id_historia = '$idhistoria' AND cedula = '$cedula' AND compania = '$compania' AND id_item = '$iditem'";
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
					echo "<td> <input  type='text'  name='nombre_medicamento$registro[orden]' id='nombre_medicamento$registro[orden]' value='$registro[nombre_medicamento]' size='30' style='border:1px;border-style:outset;border-color:#333;' disabled > </td>";
					echo "<td> <input type='text' name='presentacion$registro[orden]' id='presentacion$registro[orden]' size='30' value='$registro[presentacion]' size='30' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					echo "<td> <input type='text' name='posologia$registro[orden]' id='posologia$registro[orden]' value='$registro[posologia]' size='30' size='30' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					echo "<td> <input type='text' name='tiempo_tratamiento$registro[orden]' id='tiempo_tratamiento$registro[orden]' value='$registro[tiempo_tratamiento]' size='30' size='30' style='border:1px;border-style:outset;border-color:#333;' disabled> </td>";
					?>
						<td> <button type="button" onclick="javascript:location.href='MedsxFormato.php?tipoformato=<?php echo $_GET["tipoformato"];?>&formato=<?php echo $_GET["formato"];?>&idhistoria=<?php echo $_GET["idhistoria"];?>&cedula=<?php echo $_GET["cedula"];?>&compania=<?php echo $_GET["compania"];?>&usuario=<?php echo $_GET["usuario"];?>&iditem=<?php echo $_GET["iditem"];?>&insercion=<?php echo $_GET["insercion"];?>&accion=eliminar&orden=<?php echo $registro['orden'];?>';" style="color:#FF0000;font-size:11px;background-color:#DDD;"> Eliminar </button> </td>
					<?php
					echo "</tr>";	
				}
		}
		
		
	
	//Termina definicion de funciones
	
	
				
		
			if (isset($_GET["accion"])){
				if($_GET["accion"]=="guardar"){
					// Consulta el orden que se le debe asignar al registro
					$orden = consultarOrden($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"], $_GET["iditem"]);
					
					$nombremedicamento = $_GET["nombremedicamento"];
					$nombremedicamento = normalizarCampoRegistro($nombremedicamento);
					
					$presentacion = $_GET["presentacion"];
					$presentacion = normalizarCampoRegistro($presentacion);
					
					$posologia = $_GET["posologia"];
					$posologia = normalizarCampoRegistro($posologia);
					
					$tiempotratamiento = $_GET["tiempotratamiento"];
					$tiempotratamiento = normalizarCampoRegistro($tiempotratamiento);
					
				
					insertarRegistro($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"],$_GET["usuario"],$_GET["iditem"], $orden, 'NULL',$nombremedicamento, $presentacion,$posologia , $tiempotratamiento);
				
				}
				
				if($_GET["accion"]=="eliminar"){
					
					eliminarRegistro($_GET["formato"],$_GET["tipoformato"],$_GET["idhistoria"],$_GET["cedula"],$_GET["compania"], $_GET["iditem"], $_GET["orden"]);
				
				}
			
			}
		
		
		
		
		

		
	
	
	

	?>
	<html>
		<head>
			<meta  charset="UTF-8" />
			<script language="javascript">
				function guardarMed(){
					if(document.getElementById('nombre_medicamento').value == ''){
						document.getElementById('msjNombreMed').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjNombreMed').innerHTML= ''
					} 
					
					if(document.getElementById('presentacion').value == ''){
						document.getElementById('msjPresentacion').innerHTML= 'Campo Requerido'; 
						return false;
					} else {
						document.getElementById('msjPresentacion').innerHTML= ''
					} 
					
					if(document.getElementById('posologia').value == ''){
						document.getElementById('msjPosologia').innerHTML= 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjPosologia').innerHTML= ''
					} 
					
					if(document.getElementById('tiempo_tratamiento').value == ''){
						document.getElementById('msjTiempoTrat').innerHTML = 'Campo Requerido'; 
						return false;
					} else { 
						document.getElementById('msjTiempoTrat').innerHTML= ''
					} 
					
					var nombremedicamento = document.getElementById('nombre_medicamento').value;
					var presentacion = document.getElementById('presentacion').value;
					var posologia = document.getElementById('posologia').value;
					var tiempotratamiento = document.getElementById('tiempo_tratamiento').value;
					
					window.location.href='MedsxFormato.php?tipoformato=<?php echo $_GET["tipoformato"];?>&formato=<?php echo $_GET["formato"];?>&idhistoria=<?php echo $_GET["idhistoria"];?>&cedula=<?php echo $_GET["cedula"];?>&compania=<?php echo $_GET["compania"];?>&usuario=<?php echo $_GET["usuario"];?>&iditem=<?php echo $_GET["iditem"];?>&insercion=<?php echo $_GET["insercion"];?>&accion=guardar&nombremedicamento='+nombremedicamento+'&presentacion='+presentacion+'&posologia='+posologia+'&tiempotratamiento='+tiempotratamiento;
				}
			</script>
		</head>
			<?php
				if (strtoupper($_GET["insercion"])=="MULTILINEA"){
					$eventoOnLoad = 'onLoad="document.getElementById'."('nombre_medicamento')".'.focus();"';
				} else {
					$eventoOnLoad = "";
				}
			?>
		<body <?php echo $eventoOnLoad;?>>
			<form name="formMeds" id="formMeds" method="post" action="" >
				<table  width="100%"  cellspacing="0" cellpadding="5"  style="border:0px;margin-top:10px;  text-align:center;" >
					<tr>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> NOMBRE GEN&Eacute;RICO</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> PRESENTACI&Oacute;N</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> POSOLOG&Iacute;A</p> </td>
						<td> <p style="font-size:12px;color:#0068D4;font-weight:bold;"> TIEMPO TRATAMIENTO</p> </td>
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
								<td> <input  type="text"  name="nombre_medicamento" id="nombre_medicamento" size="30" style="border:1px;border-style:outset;border-color:#333;" tabindex="1"> </td>
								<td> <input type="text" name="presentacion" id="presentacion" size="30" size="30" style="border:1px;border-style:outset;border-color:#333;" tabindex="2"> </td>
								<td> <input type="text" name="posologia" id="posologia"  size="30" style="border:1px;border-style:outset;border-color:#333;" tabindex="3"> </td>
								<td> <input type="text" name="tiempo_tratamiento" id="tiempo_tratamiento"  size="30" style="border:1px;border-style:outset;border-color:#333;" tabindex="4"> </td>
								<td> <button type="button" onclick="javascript:guardarMed();" style="color:#0068D4;font-size:11px;background-color:#DDD;"> Guardar</button> </td>
								
							</tr>
							
							<tr> 
								<td id="msjNombreMed" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjPresentacion" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjPosologia" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<td id="msjTiempoTrat" style="color:#FF0000;font-size:12px;font-weight:bold;"> &nbsp; </td>
								<input type="hidden" name="formulario" value="1">
								<input type="hidden" name="codmedicamento" value="">
								
							</tr>
							
							<?php
						}	
							
							
						?>	
						
							
							
					
				</table>
			</form>
		</body>
	</html>
	
	

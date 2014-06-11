<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");	
	
	// Inicia definicion de funciones
	
	function eliminarAlertasxCargo($compania, $idalerta){
		$cons = "DELETE FROM Alertas.CargosxAlertas WHERE compania = '$compania' AND idalerta = '$idalerta' ";
		ExQuery($cons);
	}
	
	function insertarAlertaxCargo($compania,$cargo,$idalerta){
		$cons ="INSERT INTO Alertas.CargosxAlertas (compania, cargo, idalerta) VALUES ('$compania', '$cargo', '$idalerta')";
		ExQuery($cons);	
	}
	
	function definirExistenciaRegistro($compania, $cargo, $idalerta){
		$cons = "SELECT * FROM Alertas.CargosxAlertas WHERE compania = '$compania' AND idalerta = '$idalerta' AND cargo = '$cargo'";
		
		$res = ExQuery($cons);
			
			if(ExNumRows($res) > 0){
				$existencia = 1;
			}
			else {
				$existencia = 0;
			}
		
		return $existencia;
	}
	
	function listadoCargos($compania){
		$cons = "SELECT * FROM Salud.Cargos WHERE compania = '$compania' ORDER BY cargos ASC ";
		$res = ExQuery($cons);		
		return $res;
	}
	
	
	function mostrarAlertasxCargo($compania, $idalerta){
		global $borderTabla2Mentor, $bordercolorTabla2Mentor, $cellspacingTabla2Mentor, $cellpaddingTabla2Mentor;
		echo "<div align='center'>";
			?><table  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> width="220px" class="tabla2"   > <?php
				echo "<tr>";
					echo "<td class='encabezado2Horizontal'> CARGO </td>";
					echo "<td><input type='checkbox' name='Marcacion' onClick='Marcar()'/></td>";
				echo "</tr>"	;
				
				$res = listadoCargos($compania);		
				while($fila = ExFetchArray($res)){
					// Define si existe un registro que coincida con los criterios de busqueda (Compania, cargo, idalerta)
					$existencia = definirExistenciaRegistro($compania, $fila['cargos'], $idalerta);
							if ($existencia == 1){
								$checked = "checked";
							} else {
								$checked = "";
							}
							
					?>
					<tr>
						<td>
							<label for="<?php echo $fila['cargos'];?>"><?php echo $fila['cargos'];?> </label>
						</td>
						<td>
							<input type="checkbox"  name="cargos[]" id="<?php echo $fila['cargos'];?>" value="<?php echo $fila['cargos'];?>" <?php echo $checked;?>>								
						</td>
					</tr>	
					<?php
					
				}
				
			?></table><?php
		echo "</table>";
	echo "</div>";
	}
	
	
	function seleccionarMedicosCargo($compania, $cargo){
		$cons = "SELECT * FROM Salud.Medicos WHERE Compania = '$compania' AND  Cargo = '$cargo'";
		$res = ExQuery($cons);
		
		return $res;
	}
	
	
	function eliminarUsuariosxAlertas($compania, $idalerta){
		$cons = "DELETE FROM Alertas.UsuariosxAlertas WHERE compania = '$compania' AND idalerta = '$idalerta'";		
		ExQuery($cons);
	}
	
	function insertarUsuarioxAlerta($compania, $usuario, $idalerta){
		$cons = "INSERT INTO Alertas.UsuariosxAlertas (compania, usuario, idalerta) VALUES ('$compania', '$usuario', '$idalerta')";
		ExQuery($cons);
	}
	
	function asociarUsuariosxAlertas($compania, $cargo, $idalerta){
		$listado = seleccionarMedicosCargo($compania, $cargo);
		while ($fila = ExFetchArray($listado)){
			insertarUsuarioxAlerta($compania, $fila['usuario'], $idalerta);		
		}
	}
	
	// Termina definicion de funciones
	
	if(isset($_POST['CargosxAlerta'])){
		$vectorCargos = $_POST['cargos'];
		eliminarAlertasxCargo($Compania[0], $_GET['idalerta']);
		eliminarUsuariosxAlertas($Compania[0], $_GET['idalerta']);
		if (count($vectorCargos) > 0){
			foreach($_POST['cargos'] as $cargo){ 
				insertarAlertaxCargo($Compania[0],$cargo,$_GET['idalerta'])	;
				asociarUsuariosxAlertas($Compania[0], $cargo, $_GET['idalerta']);
			}			
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
			
			</style>
			
			<script language="JavaScript">
				function MarcarTodo(){
					for (i=0;i<document.getElementById('formCargosxAlerta').elements.length;i++) {
						if(document.getElementById('formCargosxAlerta').elements[i].type == "checkbox") {
							document.getElementById('formCargosxAlerta').elements[i].checked=1;
						}
					}					
				}
				
				function QuitarTodo(){
					for (i=0;i<document.getElementById('formCargosxAlerta').elements.length;i++) {
						if(document.getElementById('formCargosxAlerta').elements[i].type == "checkbox") {
							document.getElementById('formCargosxAlerta').elements[i].checked=0;
						}
					}
				}
				
				function Marcar(){
					if(document.getElementById('formCargosxAlerta').Marcacion.checked==1){
						MarcarTodo();
					}
					else{
						QuitarTodo();
					}
				}				
			</script>	
		</head>
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php
					$rutaarchivo[0] = "ADMINISTRADOR";
					$rutaarchivo[1] = "ALERTAS";				
					$rutaarchivo[2] = "CARGOS X ALERTA";
					
					mostrarRutaNavegacionEstatica($rutaarchivo);
					
					global $borderTabla2Mentor, $bordercolorTabla2Mentor, $cellspacingTabla2Mentor, $cellpaddingTabla2Mentor;
					$borderTabla2Mentor = $borderTabla2Mentor;
					$bordercolorTabla2Mentor = $bordercolorTabla2Mentor;
					$cellspacingTabla2Mentor = $cellspacingTabla2Mentor;
					$cellpaddingTabla2Mentor = $cellpaddingTabla2Mentor;
			?>
			<form name="formCargosxAlerta" id="formCargosxAlerta" method="post">
				
				<?php mostrarAlertasxCargo($_GET['compania'], $_GET['idalerta']); ?>
				<input type="hidden" name="CargosxAlerta" value = "1">
				<div align="center" style="margin-top:15px; margin-bottom:15px;">
					<input type='submit' value='Guardar cambios' class='boton2Envio'>
				</div>	
			</form>			
		</body>
	</html>

		<?	
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			include("Funciones.php");
			
			function listadoUsuariosxConf($compania){
				$cons = "SELECT * FROM Central.Usuarios WHERE  Usuario NOT IN (SELECT DISTINCT(Usuario) FROM Salud.Medicos WHERE Compania = '$compania') ORDER BY Usuario ASC";
				$res = ExQuery($cons);
				
				return $res;
			}
			
			function seleccionarMedico($compania, $usuario){
				$cons = "SELECT * FROM Salud.Medicos WHERE compania = '$compania' AND usuario = '$usuario' ";
				$res = ExQuery($cons);
				
				return $res;
			}
			
			function insertarMedico($usuario,$rm, $cargo,$compania, $direccion, $telefono, $especialidad, $consultorio, $estadomed, $intervaloagenda, $numpacientes ){
				
				$cons = "INSERT INTO Salud.Medicos(usuario,rm,Cargo,Compania,direccion,telefono,especialidad,Consultorio,estadomed, intervaloagenda, numpacientes) VALUES ('$usuario','$rm', '$cargo', '$compania', '$direccion', '$telefono', '$especialidad', '$consultorio', '$estadomed', '$intervaloagenda', '$numpacientes')";
				$cons = str_replace( "'NULL'","NULL",$cons  );
				ExQuery($cons);
			}
			
			
			function actualizarMedico($compania, $usuario, $rm, $cargo, $direccion, $telefono, $especialidad, $consultorio, $estadomed, $intervaloagenda, $numpacientes){
				
				$cons = "UPDATE Salud.Medicos SET rm = '$rm', cargo = '$cargo', direccion = '$direccion', telefono = '$telefono', especialidad = '$especialidad', consultorio = '$consultorio', estadomed = '$estadomed', intervaloagenda = '$intervaloagenda', numpacientes = '$numpacientes' WHERE compania = '$compania' AND usuario = '$usuario'";
				$cons = str_replace( "'NULL'","NULL",$cons  );
				ExQuery($cons);
				
			}
			
			function mostrarListadoUsuariosxConf($compania){
				$listado = listadoUsuariosxConf($compania);
				echo "<select name='NomUsu'>";
					echo "<option value=''>&nbsp; </option>";
					while ($fila = ExFetchArray($listado)){
						?>
						
						<option value="<? echo $fila['usuario']?>">
							<? echo $fila['usuario'];?>
						</option>	
						<?php					
					}
				echo "</select>";
			}
			
			function mostrarUsuario($usuario){
				?>
				<select name='NomUsu'>
					<option value="<?php echo $usuario;?>"> 
						<?php echo $usuario; ?>
					</option>	
				</select>
				<?php
			}
			
			
			
			function mostrarListadoCargos($compania,$cargomedico){
				$cons = "SELECT * FROM Salud.Cargos WHERE compania = '$compania' ORDER BY cargos ASC";
				$res = ExQuery($cons);
					echo "<select name='cargo'>";
						echo "<option value=''> &nbsp; </option>";
						while ($fila = ExFetchArray($res)){						
												
							if($fila['cargos'] == $cargomedico){
								?>
								<option value="<? echo $fila['cargos']?>" selected><? echo $fila['cargos']?></option>
								<? 
							}
							else {
								?>
								<option value="<? echo $fila['cargos']?>"><? echo $fila['cargos']?></option>
								<? 
							}
						}
					echo "</select>";	
			}
			
			
			function mostrarListadoEspecialidades($compania,$especialidadMedico){
				$cons = "SELECT * FROM Salud.Especialidades WHERE compania = '$compania' ORDER BY especialidad ASC";
				$res = ExQuery($cons);
					echo "<select name='especialidad'>";
						echo "<option value=''> &nbsp; </option>";
						while ($fila = ExFetchArray($res)){						
												
							if($fila['especialidad'] == $especialidadMedico){
								?>
								<option value="<? echo $fila['especialidad']?>" selected><? echo $fila['especialidad']?></option>
								<? 
							}
							else {
								?>
								<option value="<? echo $fila['especialidad']?>"><? echo $fila['especialidad']?></option>
								<? 
							}
						}
					echo "</select>";	
			}
			
			
			
			function mostrarListadoEstados($estadoMedico){
				?>
				<select name="Estado">
					<option  value=''> &nbsp; </option>
					<option value="Inactivo" <?	if($estadoMedico=='Inactivo'){?> selected<? }?>>Inactivo</option>
					<option value="Activo" <?	if($estadoMedico=='Activo'){?> selected<? }?>>Activo</option>
				</select>
				<?php
			}
			
			function seleccionarAlertasCargo($compania, $cargo){
				$cons = "SELECT * FROM Alertas.CargosxAlertas WHERE compania = '$compania' AND cargo = '$cargo'";
				$res = ExQuery($cons);
				
				return $res;
			}
			
			
			function insertarUsuarioxAlerta($compania, $usuario, $idalerta){
				$cons = "INSERT INTO Alertas.UsuariosxAlertas (compania, usuario, idalerta) VALUES ('$compania', '$usuario', '$idalerta')";
				ExQuery($cons);
			}
			
			function eliminarAlertasUsuario($compania, $usuario){
				$cons = "DELETE FROM Alertas.UsuariosxAlertas WHERE compania = '$compania' AND usuario = '$usuario'";
				ExQuery($cons);
			}
			
			function asociarAlertasUsuarioxCargo($compania,$usuario, $cargo){
				eliminarAlertasUsuario($compania, $usuario);
				$listado = seleccionarAlertasCargo($compania, $cargo);
					while ($fila = ExFetchArray($listado)){
						insertarUsuarioxAlerta($compania, $usuario, $fila['idalerta']);
					}
			}
		
			
			
			
		?>
				
		<html>
		
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<script language="javascript">
				
					function salir(){
						 location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>#<? echo $Usuario?>';
					}
					
					function Validar()	{
						if(document.FORMA.usuario.value==""||document.FORMA.rm.value==""||document.FORMA.direccion.value==""||document.FORMA.telefono.value==""||document.FORMA.Especialidad.value==""||document.FORMA.Estado.value==""||document.FORMA.Consultorio.value==""||document.FORMA.IntervaloAgenda.value=="" ||document.FORMA.numpacientes.value==""){
							alert("No deben quedar espacios en blanco!!!");
							return false;
						}
					}
				</script>
				<script language='javascript' src="/Funciones.js"></script>
				
				<style type="text/css">
					.encabezadoTabla {
						font-family: Times New Roman, Verdana;
						font-size: 16px;
						font-weight: bold;
						color: #002147;
						background-color: #E5E5E5;
					}
					
					
				</style>
			</head>
		
			<body background="/Imgs/Fondo.jpg">
				<?php
					if (isset($_POST['formulario'])){
				
				
					$compania = $Compania[0];
					$nombreusuario = $_POST['NomUsu'];
					$rm = $_POST['rm'];
					$cargo = $_POST['cargo'];
					$direccion = $_POST['direccion'];
					$telefono = $_POST['telefono'];
					$especialidad = $_POST['especialidad'];
					$estadomed = $_POST['Estado'];
					$consultorio = $_POST['Consultorio'];
					$intervaloagenda = $_POST['IntervaloAgenda'];
						if ($intervaloagenda == ""){
							$intervaloagenda = 'NULL'						;
						}
						
					$numpacientes = $_POST['numpacientes'];
						if ($numpacientes == ""){
							$numpacientes = 'NULL';
						}
					
					if(strtoupper($_GET['accion'])== "CREAR"){
						insertarMedico($nombreusuario,$rm, $cargo,$compania, $direccion, $telefono, $especialidad, $consultorio, $estadomed, $intervaloagenda, $numpacientes)	;
						asociarAlertasUsuarioxCargo($compania,$nombreusuario, $cargo);
						?>
						<script language="javascript">
							salir();
						</script>
						<?php
					}
					
					if(strtoupper($_GET['accion'])== "EDITAR"){
						actualizarMedico($compania, $nombreusuario, $rm, $cargo, $direccion, $telefono, $especialidad, $consultorio, $estadomed, $intervaloagenda, $numpacientes);
						asociarAlertasUsuarioxCargo($compania,$nombreusuario, $cargo);
						?>
						<script language="javascript">
							salir();
						</script>
						<?php
					}
					
				
				}
				
				
					if(isset($_GET['accion'])){
							if (strtoupper($_GET['accion']) == "EDITAR"){
								$resultado = seleccionarMedico($Compania[0], $_GET['usuario']);
								$vectorMedico = ExFetchArray($resultado);
							}
						
						?>
						
							<div style="margin-left:50px">
								<form name="FORMA" id="FORMA" method="post" onSubmit="return Validar()">

									<table  border="1" bordercolor="#e5e5e5" cellpadding="4">
										<tr>
											<td class="encabezadoTabla" >Nombre</td>
											<td>								
												<?php
													if(strtoupper($_GET['accion']) == "CREAR"){
														mostrarListadoUsuariosxConf($Compania[0]);
													}
													elseif(strtoupper($_GET['accion']) == "EDITAR"){
														mostrarUsuario($_GET['usuario']);
													}
												?>	
											</td>
										</tr> 
										
										<tr>
											<td class="encabezadoTabla">Registro Medico	</td>
											<td>
												<?php
													if (strtoupper($_GET['accion']) == "CREAR") {
														?>
														<input type="text" name="rm" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
														<?php
													}
													elseif (strtoupper($_GET['accion']) == "EDITAR") {
														?>
														<input type="text" name="rm" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['rm']?>">
														<?php
													}
												?>
												
												
												
											</td>
										</tr>
										
										<tr>
											<td class="encabezadoTabla">Cargo</td>
											<td>
												<?php
													if(strtoupper($_GET['accion']) == "CREAR"){
														mostrarListadoCargos($Compania[0],'empty');
													}
													elseif(strtoupper($_GET['accion']) == "EDITAR"){
														mostrarListadoCargos($Compania[0],$vectorMedico['cargo']);
													}
												?>
												
											</td>						
										</tr>
										
										<tr>
											<td class="encabezadoTabla"> Direcci&oacute;n	</td>
											<td>
												<?php
													if (strtoupper($_GET['accion']) == "CREAR") {
														?>
														<input type="text" name="direccion" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
														<?php
													}
													elseif (strtoupper($_GET['accion']) == "EDITAR") {
														?>
														<input type="text" name="direccion" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['direccion']?>">
														<?php
													}
												?>	
											</td>
										</tr>
										
										<tr>
											<td class="encabezadoTabla"> Tel&eacute;fono</td>
											<td>
												<?php
													if (strtoupper($_GET['accion']) == "CREAR") {
														?>
														<input type="text" name="telefono" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)">
														<?php
													}
													elseif (strtoupper($_GET['accion']) == "EDITAR") {
														?>
														<input type="text" name="telefono" maxlength="30" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['telefono']?>">
														<?php
													}
												?>	
											</td>
										</tr>
										
										<tr>
											<td class="encabezadoTabla">Especialidad</td>
											<td>
												<?php
													if(strtoupper($_GET['accion']) == "CREAR"){
														mostrarListadoEspecialidades($Compania[0],'empty');
													}
													elseif(strtoupper($_GET['accion']) == "EDITAR"){
														mostrarListadoEspecialidades($Compania[0],$vectorMedico['especialidad']);
													}
												?>
												
											</td>
										</tr>
										
										<tr>
											<td class="encabezadoTabla">Estado</td>
											<td> 
												<?php
													if(strtoupper($_GET['accion']) == "CREAR"){
														mostrarListadoEstados('empty');
													}
													elseif(strtoupper($_GET['accion']) == "EDITAR"){
														mostrarListadoEstados($vectorMedico['estadomed']);
													}
												?>
											</td>
										</tr>
										<tr>
											<td class="encabezadoTabla">Consultorio</td>
											<td>
												<?php
													if (strtoupper($_GET['accion'])== "CREAR") {
														?>
														<input type="text" name="Consultorio"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" width="300px">
														<?php
													}
													elseif (strtoupper($_GET['accion']) == "EDITAR") {
														?>
														<input type="text" name="Consultorio"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['consultorio']?>"  width="300px">
														<?php
													}
												?>											
											</td>
										</tr>
										<tr>
											<td class="encabezadoTabla">Intervalo Agenda</td>
											<td>
												<?php
													if (strtoupper($_GET['accion']) == "CREAR") {
														?>
														<input type="text" name="IntervaloAgenda"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" width="30px" maxlength="3">
														<?php
													}
													elseif (strtoupper($_GET['accion'])=="EDITAR") {
														?>
														<input type="text" name="IntervaloAgenda"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['intervaloagenda']?>"  width="30px" maxlength="3">
														<?php
													}
												?>										
											</td>
										</tr>
										<tr>
											<td class="encabezadoTabla">N&uacute;mero de Pacientes</td>
											<td>
												<?php
													if (strtoupper($_GET['accion'])== "CREAR") {
														?>
														<input type="text" name="numpacientes"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" width="30px" maxlength="3">
														<?php
													}
													elseif (strtoupper($_GET['accion'])=="EDITAR") {
														?>
														<input type="text" name="numpacientes"  onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $vectorMedico['numpacientes']?>"  width="30px" maxlength="3">
														<?php
													}
												?>										
											</td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<input type="submit" value="Guardar" name="Guardar"> <input type="button" value="Cancelar" onClick="salir()">
											</td>
										</tr>
										
									</table>
									
									<input type="hidden" name="formulario" value="1"/>
									<input type="hidden" name="accion" value="<? echo $_GET['accion']?>" />
									<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>" />                 
								</form>
							</div>
						<?php
					}
					?>
			</body>
		</html>

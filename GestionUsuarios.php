		<?
			if($DatNameSID){session_name("$DatNameSID");}
			session_start();
			
			include("Funciones.php");
			include_once("General/Configuracion/Configuracion.php");
			
				function seleccionarUsuariosPadre($compania){
					$cons = "SELECT DISTINCT(usuariopadre) AS usuariopadre FROM Central.usuarios  ORDER BY usuariopadre ASC";
					$res = ExQuery($cons);
					
					return $res;
				}
				
				
				
				
				
				
				
				function validarExistenciaUsuario($usuario){
					$cons = "SELECT * FROM Central.Usuarios WHERE usuario = '$usuario'";
					$res = ExQuery($cons);
					$numreg= ExNumRows($res);
						if($numreg >= 1){
							$existencia = 1;
						} 
						else {
							$existencia = 0;
						}
					
					return 	$existencia;
						
				}
				
				function seleccionarCargos($compania){
					$cons = "SELECT * FROM Salud.Cargos WHERE Compania = '$compania' ORDER BY cargos ASC";
					$res = ExQuery($cons);
					
					return $res;
				}
				
				function listarCargos($compania){
					echo "<select name='cargosBusq'>";
						echo "<option value =''>&nbsp;</option>";
						
						$listado = seleccionarCargos($compania);
						while ($fila = ExFetchArray($listado)){							
							if($fila['cargos'] == $_POST['cargosBusq']){
								?>
								<option value="<? echo $fila['cargos'];?>" selected><? echo $fila['cargos'];?></option>
								<? 
							}
							else {
								?>
								<option value="<? echo $fila['cargos'];?>"><? echo $fila['cargos'];?></option>
								<? 
							}
						}
					echo "</select>";
				}
				
				
				function seleccionarEspecialidades($compania){
					$cons = "SELECT * FROM Salud.Especialidades WHERE Compania = '$compania' ORDER BY especialidad ASC";
					$res = ExQuery($cons);
					
					return $res;
				}
				
				function listarEspecialidades($compania){
					echo "<select name='especialidadBusq'>";
						echo "<option value =''>&nbsp;</option>";
						
						$listado = seleccionarEspecialidades($compania);
						while ($fila = ExFetchArray($listado)){							
							if($fila['especialidad'] == $_POST['especialidadBusq']){
								?>
								<option value="<? echo $fila['especialidad'];?>" selected><? echo $fila['especialidad'];?></option>
								<? 
							}
							else {
								?>
								<option value="<? echo $fila['especialidad'];?>"><? echo $fila['especialidad'];?></option>
								<? 
							}
						}
				
				}
				
				function generarConsultaUsuarios(){
					$consPrincipal_1 = "SELECT Usuarios.Usuario,Usuarios.Cedula,Nombre,FechaCaducidad,FechaUltimoAcceso FROM Central.Usuarios ";  
					$consPrincipal_3 = " WHERE '1' = '1' ";
					$consPrincipal_5 = " ORDER  BY Usuario ASC, nombre ASC";
					
					
					
					if (!empty($_POST['usuarioBusq'])){
						$usuarioBusqueda = $_POST['usuarioBusq'];
						$cond1 =" AND (Usuarios.usuario ILIKE '%$usuarioBusqueda%' or nombre ILIKE '%$usuarioBusqueda%') ";
					} else {
						$cond1 = "";
					}
					
					
					$cond2 = " ";
					
					
					if (!empty($_POST['idBusq'])){
						$IdBusqueda = $_POST['idBusq'];
						$cond3 =" AND cedula = '$IdBusqueda'";
					} else {
						$cond3 = "";
					}
					
					if (!empty($_POST['cargosBusq'])){
						$cargoBusqueda = $_POST['cargosBusq'];
						$consPrincipal_2 =" , Salud.Medicos ";
						$consPrincipal_4 = " AND Usuarios.Usuario = Medicos.Usuario ";
						$cond4 = " AND Medicos.Cargo = '$cargoBusqueda' ";
					} else {
						$cond4 = "";
					}
					
					if (!empty($_POST['especialidadBusq'])){
						$especialidadBusqueda = $_POST['especialidadBusq'];
						$consPrincipal_2 =" , Salud.Medicos ";
						$consPrincipal_4 = " AND Usuarios.Usuario = Medicos.Usuario ";
						$cond5 = " AND Medicos.Especialidad = '$especialidadBusqueda' ";
					} else {
						$cond5 = "";
					}
					
					$consultaFinal = $consPrincipal_1." ".$consPrincipal_2." ".$consPrincipal_3." ".$consPrincipal_4." ".$cond1." ".$cond2." ".$cond3." ".$cond4." ".$cond5." ".$consPrincipal_5;
									
					return 	$consultaFinal;
						
				}
			
				if($Editar){
						$Nombre = strtoupper($Nombre);
												
						
						if(!$Caducidad){
							$Caducidad="NULL";
						}
						else{
							$Caducidad="'$Caducidad'";
						}
						
						$cons="UPDATE Central.Usuarios SET Nombre='$Nombre',FechaCaducidad=$Caducidad,Cedula='$Cedula' where Usuario='$Usuario'";

						$res=ExQuery($cons);
						echo ExError($res);
						if($res==1){
							echo "<div align='center' style = 'margin-top:5px; margin-bottom:5px;'>";
								echo "<p style='color:#0068D4;font-size:14px;font-weight:bold;'>";
										echo "Se ha actualizado el usuario  $NewUsuario";
								echo "</p>";
							echo "</div>";
						}
				}
				
				if($Elimina){
					$cons="Delete from Central.Usuarios where Usuario='$Usuario'";
					$res=ExQuery($cons);

					$cons="Delete from Central.UsuariosxModulos where Usuario='$Usuario'";
					$res=ExQuery($cons);

					echo ExError($res);
					if($res==1){
						echo "<div align='center' style = 'margin-top:5px; margin-bottom:5px;'>";
							echo "<p style='color:#0068D4;font-size:14px;font-weight:bold;'>";
								echo "<span class='mensaje1'>Se ha eliminado el usuario $NewUsuario</span>";
							echo "</p>";
						echo "</div>";
					}
				}
				
				if($Nuevo){
					$NewNombre = strtoupper($NewNombre);
					
					
					
					$Clave=md5("userdef");					
					if(!$NewCaducidad){
						$NewCaducidad="NULL";
					}else{
						$NewCaducidad="'$NewCaducidad'";
					}
					
					$existencia = validarExistenciaUsuario($NewUsuario);
						if($existencia == 0){
							$cons="INSERT INTO Central.Usuarios (Usuario,Nombre,Cedula,Clave,FechaCaducidad) values('$NewUsuario','$NewNombre','$NewCedula','$Clave',$NewCaducidad )";
							$res=ExQuery($cons);
							echo ExError($res);
							if($res==1){
								echo "<div align='center' style = 'margin-top:5px; margin-bottom:5px;'>";
									echo "<p style='color:#0068D4;font-size:14px;font-weight:bold;'>";
											echo "Se ha creado el usuario $NewUsuario";
									echo "</p>";
								echo "</div>";
							}
						} 
						else {
							echo "<div align='center' style = 'margin-top:5px; margin-bottom:5px;'>";
								echo "<p style='color:#FF0000;font-size:14px;'>";
										echo "En este momento existe el usuario $NewUsuario";
								echo "</p>";
							echo "</div>";
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
			<script language="javascript" src="/Funciones.js"></script>			
			<script language='javascript' src="/calendario/popcalendar.js"></script>
			
			<script language="javascript">
				function Validar()
				{
					if(document.FORMANew.NewUsuario.value==""){alert("Ingrese el usuario");return false;}
					if(document.FORMANew.NewNombre.value==""){alert("Ingrese el nombre usuario del usuario");return false;}
					if(document.FORMANew.NewCedula.value==""){alert("Ingrese la cedula del usuario");return false;}
				}
			</script>
			
			<style type="text/css">
				
				
				
				
			</style>
		</head>
		
		<body <?php echo $backgroundBodyMentor; ?>>
			<?php mostrarRutaNavegacion($_SERVER['PHP_SELF']);	?>
			<!--Inicia el formulario de busqueda -->
			<div align="center" style="margin-top:10px;margin-bottom:15px;">
				<form name="formBusq" method="post">
					<table class="Tabla2" width="95%" style="text-align:center;"  <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >
						
						<tr>
							<td class="encabezado2HorizontalInvertido"> USUARIO</td>							
							<td class="encabezado2HorizontalInvertido"> IDENTIFICACI&Oacute;N </td>
							<td class="encabezado2HorizontalInvertido"> CARGO </td>
							<td class="encabezado2HorizontalInvertido"> ESPECIALIDAD </td>
						</tr>
						
						<tr>
						
							<td width="300px;"> <input type="text" name="usuarioBusq" style="width:100%;" <?php if (isset($_POST['usuarioBusq'])) { ?> value="<?php echo $_POST['usuarioBusq'];?>"<?}?>/></td>
							<td width="100px;"> <input type="text" name="idBusq" style="width:100%;" <?php if (isset($_POST['idBusq'])) { ?> value="<?php echo $_POST['idBusq'];?>"<?}?> /> </td>
							<td> <?php listarCargos($Compania[0]);?> </td>
							<td> <?php listarEspecialidades($Compania[0]);?> </td>
						</tr>
						<tr>
							<td colspan="5" style="text-align:center">
								<input type="submit" value="Buscar usuario" class="boton2Envio"/>
							</td>
							
						</tr>
					</table>
					<input type="hidden" name="formularioBusq" value="1"/>
				</form>	
			</div>	
					
			<!--Termina el formulario de busqueda -->
			
			
			<!-- Inicia formulario creacion nuevo usuario -->
			<div align="center" style="margin-top:50px;margin-bottom;50px;">
				
					<form name="FORMANew" onSubmit="return Validar()" method="post">
						
							<table class="Tabla2" width="95%" style="text-align:center;" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?>>
								
								<tr>
									<td  class="encabezado2HorizontalInvertido"> USUARIO</td>
									<td  class="encabezado2HorizontalInvertido"> IDENTIFICACI&Oacute;N</td>
									<td  class="encabezado2HorizontalInvertido">NOMBRE</td>
									<td  class="encabezado2HorizontalInvertido">CADUCIDAD</td>
									<td  class="encabezado2HorizontalInvertido"> &nbsp; </td>
								</tr>					
							
								<tr>
									<td width="300px;">
										<input border="0" type="Text" name="NewUsuario" style="width:100%;" maxlength="120" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)">
									</td>
									
									<td width="100px;">
										<input type="Text"  name="NewCedula" style="width:100%;" maxlength="80" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)">
									</td>
									<td width="250px;">
										<input type="Text"  name="NewNombre" style="width:100%;" maxlength="180" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)">
									</td>
									<td>
										<input type="text" name="NewCaducidad" readonly style="width:100%;" onClick="popUpCalendar(this, FORMANew.NewCaducidad, 'yyyy-mm-dd');">
									</td>
								
									<td align="center" colspan="5">
										<button type="submit" name="Nuevo" class="boton2Envio">Crear usuario </button>
									</td>
								</tr>
							</table>	
							<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
							<input type="hidden" name="usuarioBusq" value="<? echo $_POST['usuarioBusq']?>">
							<input type="hidden" name="usuarioPadreBusq" value="<? echo $_POST['usuarioPadreBusq']?>">
							<input type="hidden" name="idBusq" value="<? echo $_POST['idBusq']?>">
							<input type="hidden" name="cargosBusq" value="<? echo $_POST['cargosBusq']?>">
							<input type="hidden" name="especialidadBusq" value="<? echo $_POST['especialidadBusq']?>">
							<?php 
								if (isset($_POST['formularioBusq'])){
									?>
									<input type="hidden" name="formularioBusq" value="<? echo $_POST['formularioBusq']?>">
									<?php
								}
							?>	
					</form>
				
			</div>	
			<!-- Termina formulario creacion nuevo usuario -->
			
			

			<?php 
				if(isset($_POST['formularioBusq'])){
					$cons = generarConsultaUsuarios();
					//echo $cons; 
					$res = ExQuery($cons);
					if (ExNumRows($res) > 0){
							?>
							<!-- Inicia la visualizacion de todos los usuarios -->	
							<div <?php echo $alignDiv2Mentor; ?> class="div2">
								<table class="tabla2" width="95%" style="text-align:center" <?php echo $borderTabla2Mentor; echo $bordercolorTabla2Mentor; echo $cellspacingTabla2Mentor; echo $cellpaddingTabla2Mentor; ?> >

									<tr>
										<td class="encabezado2Horizontal">USUARIO</td>
										<td class="encabezado2Horizontal">IDENTIFICACI&Oacute;N</td>
										<td class="encabezado2Horizontal">NOMBRE</td>
										<td class="encabezado2Horizontal">CADUCIDAD</td>
										<td class="encabezado2Horizontal">&Uacute;LTIMO ACCESO</td>
										<td class="encabezado2Horizontal" colspan="4">CONFIGURACI&Oacute;N</td>
									</tr>
									<?
									$cons = generarConsultaUsuarios();
										
									$res=ExQuery($cons);
									while($fila=ExFetchArray($res)){
										$i++;
										
										?>
										
										<form name="FORMA<? echo $i?>" method="post">
											<tr>
												<td>
													<input border="0" readonly="yes"  type="Text" name="Usuario" maxlength="120" style="width:100%;font-size:11px;" value="<? echo $fila['usuario']?>">
												</td>
												
												<td>
													<input type="Text"  name="Cedula" style="width:100%; font-size:11px;" maxlength="80" value="<? echo $fila[1]?>">
												</td>
												<td>
													<input type="Text"  name="Nombre" style="width:100%;font-size:11px;" maxlength="200" value="<? echo $fila[2]?>">
												</td>
												<td>
													<input type="text" name="Caducidad" readonly style="width:100%; font-size:11px;" value="<? echo $fila[3]?>" onClick="popUpCalendar(this, FORMA<? echo $i?>.Caducidad, 'yyyy-mm-dd');">
												</td>
												<td  style="font-size:11px;word-break:normal;">
													<? if (!empty($fila[4])){
															echo "<p>".$fila[4]."</p>";
														}
														else {
															echo "&nbsp;";
														}
														?>
													
												</td>
												<td>
													<button type="submit" name="Editar"><img src="/Imgs/vobo.png" width="16px" height="16px"></button>
												</td>
												
												<td>												
													<button onClick="open('BlanquearClave.php?DatNameSID=<? echo $DatNameSID?>&Usuario=<? echo $fila[0]?>','','width=300,height=200')"><img src="/Imgs/s_rights.png"></button>
												</td>
												<td>
													<button onClick="open('UsuariosxModulos.php?DatNameSID=<? echo $DatNameSID?>&Usuario=<? echo $fila[0]?>','','width=400,height=600,scrollbars=yes')"><img src="/Imgs/s_process.png"></button>
												</td>
												
												<td>
													<button onClick="if(confirm('Dese eliminar Usuario?\n NOTA: si el usuario tiene movimientos no podra retirarse')){location.href='GestionUsuarios.php?DatNameSID=<? echo $DatNameSID?>&Elimina=1&Usuario=<? echo $fila[0]?>';}"><img src="/Imgs/b_drop.png"></button>
												</td>
													<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
													<input type="hidden" name="usuarioBusq" value="<? echo $_POST['usuarioBusq']?>">
													<input type="hidden" name="usuarioPadreBusq" value="<? echo $_POST['usuarioPadreBusq']?>">
													<input type="hidden" name="idBusq" value="<? echo $_POST['idBusq']?>">
													<input type="hidden" name="cargosBusq" value="<? echo $_POST['cargosBusq']?>">
													<input type="hidden" name="especialidadBusq" value="<? echo $_POST['especialidadBusq']?>">
													<input type="hidden" name="formularioBusq" value="<? echo $_POST['formularioBusq']?>">
											</tr>
										</form>
										<?php
									}	
									?>					
										
								</table>
							</div>	
								<!-- Termina la visualizacion de todos los usuarios -->
								<?php
					}
					else {
						echo "<div align='center' style = 'margin-top:5px; margin-bottom:5px;'>";
							echo "<p style='color:#0068D4;font-size:14px;'>";
								echo "<span class='mensaje1'> No existen registros que coincidan con los criterios de b&uacute;squeda </span>";
							echo "</p>";
						echo "</div>";
					
					}
				}	
				?>
				
				
			
			

		</body>
	</html>	
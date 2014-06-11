	
	<html>
		<head>
			<title> Migracion Central.Usuarios </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
	
		<body>


				<?php
					
					session_start();
					include_once '../../Conexiones/conexion.php';
					include_once('../../General/funciones/funciones.php');
					

					// Inicia la definicion de funciones 
						
						
					
						
						function contarRegistrosMySQL() {
							$cnx = conectar_mysql("Salud");
							$cons = "SELECT COUNT(*) AS conteoMySQL FROM Salud.Usuarios";
							$res =  mysql_query($cons);
							$fila = mysql_fetch_array($res);
							$res = $fila['conteoMySQL'];
							return $res; 	
						
						}
						
						
						function contarRegistrosPostgresql() {
							$cnx= conectar_postgres();
							$cons = "SELECT COUNT(*) AS conteo FROM Central.Usuarios";
							$res =  pg_query($cnx, $cons);
							$fila = pg_fetch_array($res);
							$res = $fila['conteo'];
							return $res; 	
						
						}
						
						function contarRegistrosPostgresqlErrores() {
							$cnx= conectar_postgres();
							$cons = "SELECT COUNT(*) AS conteo FROM Central.UsuariosMigracion";
							$res =  pg_query($cnx, $cons);
							$fila = pg_fetch_array($res);
							$res = $fila['conteo'];
							return $res; 	
						
						}
						
						function llamarRegistrosMySQL() {
							// El limite inicial y final se usan para una inmensa cantidad de registros
							global $res;
							$cnx = conectar_mysql("Salud");
							$cons = "SELECT *  FROM Salud.Usuarios ORDER BY Cedula ASC ";
							$res =  mysql_query($cons);
							return $res; 
						
						}
						
						function insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad) {
						//Realiza la insercion en Postgresql con base en los parametros
							
							$cnx = 	conectar_postgres();
							$cons = "INSERT INTO Salud.Medicos ( usuario, rm, cargo, compania, estadomed, especialidad ) VALUES ('$usuario', '$rm', '$cargo', '$compania', '$estadomed', '$especialidad')"	;
									 
							$cons = str_replace( "'NULL'","NULL",$cons  );	
							$res = @pg_query($cnx, $cons);
								if (!$res) {
									$consUTF8 = utf8_encode($cons);
									$resUTF8 = @pg_query($cnx, $consUTF8);					
										if (!$resUTF8) {
											$fp = fopen("ReporteUsuarios.html", "a+");	
											$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
											$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
											fputs($fp, $errorEjecucion);
											fputs($fp, $consulta);
											fclose($fp);
										
										}
								
								}

						}
						
						
						function eliminarMedicos(){
							$cnx = conectar_postgres();
							$cons = "TRUNCATE Salud.Medicos CASCADE";
							$res = @pg_query($cnx, $cons);
								if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}
							
						}
						
						function eliminarPKUsuarios(){
							$cnx = conectar_postgres();
							$cons = 'ALTER TABLE central.usuarios DROP CONSTRAINT "PkCentUsuarios" CASCADE';
							$res = @pg_query($cnx, $cons);
								/*if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}*/
							
						}
						
						function eliminarUniqueUsuarios(){
							$cnx = conectar_postgres();
							$cons = 'ALTER TABLE central.usuarios DROP CONSTRAINT "UnkCentralUsuario" CASCADE';
							$res = @pg_query($cnx, $cons);
								/*if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";		
									
								} */
							
						}
						
						function insertarUsuarioxHC($usuario,$modulo, $madre){
							$cnx = conectar_postgres();
							$cons = "INSERT INTO Salud.UsuariosxHC(usuario, modulo, madre) VALUES ('$usuario','$modulo', '$madre')";
							$cons = str_replace( "'NULL'","NULL",$cons  );
							$res = @pg_query($cnx, $cons);
								if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}
							
						}
						
						function insertarUsusxOrdMeds($usuario,$modulo){
							$cnx = conectar_postgres();
							$cons = "INSERT INTO Salud.UsusxOrdMeds(usuario, modulo) VALUES ('$usuario','$modulo')";
							$cons = str_replace( "'NULL'","NULL",$cons);
							$res = @pg_query($cnx, $cons);
								if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}
							
						}
						
						
						function insertarUsuariosxAlmacenes($usuario,$almacenppal,$compania ){
							$cnx = conectar_postgres();
							$cons = "INSERT INTO Consumo.UsuariosxAlmacenes(usuario, almacenppal, compania) VALUES ('$usuario','$almacenppal', '$compania')";
							$cons = str_replace( "'NULL'","NULL",$cons  );
							$res = @pg_query($cnx, $cons);
								if (!$res) {				
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}
							
						}
						
						function insertarAutorizaUsuxSolicitudes($usuario, $almacenppal,$compania) {
								$cnx = 	conectar_postgres();
								$cons = "INSERT INTO Consumo.AutorizaUsuxSolicitudes (usuario, almacenppal, compania) VALUES ('$usuario', '$almacenppal','$compania')";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
						
						
						function insertarPermisosCSV($tabla, $archivo) {
						
								$cnx = conectar_postgres();
								$ruta = $_SERVER['DOCUMENT_ROOT'];
								$rutacompleta = $ruta."/Migraciones/Central/Usuarios/".$archivo;
								$cons= "COPY $tabla FROM '$rutacompleta' WITH DELIMITER ';' CSV HEADER;";
								
								$res =  @pg_query($cons);
									if (!$res) {
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
											
									}
							
						}
						
						
						function actualizarPermisos($tabla, $usuario) {
						
								$cnx = conectar_postgres();
								
								$cons= "UPDATE $tabla SET usuario = '$usuario' WHERE Usuario = 'USUARIOTMP'";
								$res =  @pg_query($cons);
									if (!$res) {
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
											
									}
							
						}
						
						
						
						
						function crearUsuariosPerfiles($usuario,$nombre, $cedula, $clave, $agregarCargo){
								
								global  $trabajo_social , $administrador , $estadistica , $facturacion , $medico_general , $psiquiatra , $psicologo , $enfermeria , $medico_rural , $director , $terapia_ocupacional , $terapia_ocupacional , $odontologia , $farmacia , $direccion_general , $jefe_enfermeria , $nutricionista , $sistemas , $roperia , $auditoria , $comunidad , $costos , $siau , $interno , $residente , $practicante_psicologia , $auxiliar_terapia , $contabilidad , $psiquiatra_adj , $talento_humano , $suministros , $pedagogo_reeducador , $clinica_adicciones , $comunicaciones , $educador_fisico , $infraestructura , $financiero , $tesoreria , $direccion_cientifica , $servicios_generales , $calidad , $paciente_seguro , $quimico_farmaceuta , $porteria , $gestor_area , $gestor_alimentos , $neuropsicologo , $revisor , $cartera , $medico_sexologo;
								
								$compania = $_SESSION["compania"];
								$usuariopadre = $usuario; 
								$direccion_administrativa == 0; // Se deja por defecto este valor en cero, ya que es un perfil que no se usa en este momento
								$contratacion_salud = 0; // Se deja por defecto este valor en cero, ya que es un perfil que no se usa en este momento
								
									if(strtoupper($usuario) == 'LILIANA ANDREA RAMOS' or strtoupper($usuario) == 'ADMINISTRADOR' ) {
										$direccion_administrativa = 1;
										$contratacion_salud = 1;
										$agregarCargo = 1;
									}
									
									if(strtoupper($usuario) == 'PATRICIA PINEDA' or  strtoupper($usuario) == 'ADMINISTRADOR' ) {
										$contratacion_salud = 1;
										$agregarCargo = 1;
									}
									
									if(strtoupper($usuario) == 'ADMINISTRADOR' ) {
										$facturacion = 1;
										$agregarCargo = 1;
									}
								
								//Inicia la validacion por cada perfil		
								
								// Perfil "Direccion Administrativa"
								if ($direccion_administrativa == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Direccion Administrativa)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "DIRECCION ADMINISTRATIVA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosDireccionAdministrativa.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								//Inicia la validacion por cada perfil		
								
								// Perfil "Contratacion salud"
								if ($contratacion_salud == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Contratacion Salud)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "CONTRATACION SALUD";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosContratacionSalud.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Trabajo Social
								if ($trabajo_social == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){
																	
											$usuario = $usuariopadre." (Trabajo Social)";
										}	
									$rm = "NA"; // Se deja este valor como NA porque no tiene registro medico
									$cargo = "TRABAJO SOCIAL";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosTrabajoSocial.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");								
														
									insertarUsusxOrdMeds($usuario,"Notas");						
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Administrador
								if ($administrador == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){
																	
											$usuario = $usuariopadre." (Administrador)";
										}	
									$rm = "NA"; // Se deja este valor como NA porque no tiene registro medico
									$cargo = "ADMINISTRADOR";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosAdministrador.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');					
									insertarUsuarioxHC($usuario,"AUTORIZA SERVICIOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LIQUIDACION","UTILIDADES");
									insertarUsuarioxHC($usuario,"DESCUENTOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"HOJA DE MEDICAMENTOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');					
														
									insertarUsusxOrdMeds($usuario,"Comedores");
									insertarUsusxOrdMeds($usuario,"Dietas");
									insertarUsusxOrdMeds($usuario,"Egreso");
									insertarUsusxOrdMeds($usuario,"Ingresar paciente");
									insertarUsusxOrdMeds($usuario,"Interprogramas");
									insertarUsusxOrdMeds($usuario,"Medicamentos Programados");
									insertarUsusxOrdMeds($usuario,"Medicamentos No Programados");
									insertarUsusxOrdMeds($usuario,"Notas");
									insertarUsusxOrdMeds($usuario,"Procedimientos");
									insertarUsusxOrdMeds($usuario,"Traslado de Unidad");
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarUsuariosxAlmacenes($usuario,"FARMACIA",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarAutorizaUsuxSolicitudes($usuario, "FARMACIA",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Estadistica
								if ($estadistica == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){
																	
											$usuario = $usuariopadre." (Estadistica)";
										}	
									$rm = "NA"; // Se deja este valor como NA porque no tiene registro medico
									$cargo = "ESTADISTICA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosEstadistica.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
														
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Facturacion
								if ($facturacion == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){
																	
											$usuario = $usuariopadre." (Facturacion)";
										}	
									$rm = "NA"; // Se deja este valor como NA porque no tiene registro medico
									$cargo = "FACTURACION";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosFacturacion.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
														
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Medico General
								if (($medico_general == 1) or ($medico_rural == 1)){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){
																	
											$usuario = $usuariopadre." (Medicina General)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "MEDICO GENERAL";
									$estadomed = "Activo";
									$especialidad = "MEDICINA GENERAL"; 
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosMedico.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsusxOrdMeds($usuario,"Comedores");
									insertarUsusxOrdMeds($usuario,"Dietas");
									insertarUsusxOrdMeds($usuario,"Egreso");
									insertarUsusxOrdMeds($usuario,"Ingresar paciente");
									insertarUsusxOrdMeds($usuario,"Interprogramas");
									insertarUsusxOrdMeds($usuario,"Medicamentos Programados");
									insertarUsusxOrdMeds($usuario,"Medicamentos No Programados");
									insertarUsusxOrdMeds($usuario,"Notas");
									insertarUsusxOrdMeds($usuario,"Procedimientos");
									insertarUsusxOrdMeds($usuario,"Traslado de Unidad");
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Psiquiatra
								if (($psiquiatra == 1) or ($psiquiatra_adj == 1)){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Psiquiatra)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PSIQUIATRA";
									$estadomed = "Activo";
									$especialidad = "PSIQUIATRIA"; 
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosPsiquiatra.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsusxOrdMeds($usuario,"Comedores");
									insertarUsusxOrdMeds($usuario,"Dietas");
									insertarUsusxOrdMeds($usuario,"Egreso");
									insertarUsusxOrdMeds($usuario,"Ingresar paciente");
									insertarUsusxOrdMeds($usuario,"Interprogramas");
									insertarUsusxOrdMeds($usuario,"Medicamentos Programados");
									insertarUsusxOrdMeds($usuario,"Medicamentos No Programados");
									insertarUsusxOrdMeds($usuario,"Notas");
									insertarUsusxOrdMeds($usuario,"Procedimientos");
									insertarUsusxOrdMeds($usuario,"Traslado de Unidad");					
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Psicologo
								if ($psicologo == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Psicologia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PSICOLOGO";
									$estadomed = "Activo";
									$especialidad = "PSICOLOGIA"; 
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosPsicologia.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");
									
														
									
									insertarUsusxOrdMeds($usuario,"Interprogramas");					
									insertarUsusxOrdMeds($usuario,"Notas");			
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
								}
								
								
								// Perfil Enfermeria
								if ($enfermeria == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Auxiliar Enfermeria)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "AUXILIAR DE ENFERMERIA";
									$estadomed = "Activo";
									$especialidad = "AUXILIAR DE ENFERMERIA"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosAuxiliarEnfermeria.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');					
									insertarUsuarioxHC($usuario,"HOJA DE MEDICAMENTOS","UTILIDADES");
									
									
								}
								
								
								
								// Perfil Direccion
								/*if ($director == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Direccion)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "DIRECCION";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO"; // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}*/
								
								
								// Perfil Terapia Ocupacional
								if ($terapia_ocupacional == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Terapia Ocupacional)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "TERAPEUTA OCUPACIONAL";
									$estadomed = "Activo";
									$especialidad = "TERAPIA OCUPACIONAL"; 
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosTerapiaOcupacional.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");								
														
									insertarUsusxOrdMeds($usuario,"Notas");						
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Odontologia
								if ($odontologia == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Odontologia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "ODONTOLOGIA";
									$estadomed = "Activo";
									$especialidad = "ODONTOLOGIA"; 
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Farmacia
								if ($farmacia == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Auxiliar Farmacia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "AUXILIAR DE FARMACIA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosAuxiliarFarmacia.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
								
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarUsuariosxAlmacenes($usuario,"FARMACIA",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarAutorizaUsuxSolicitudes($usuario, "FARMACIA",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Direccion General
								if ($direccion_general == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Direccion General)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "DIRECCION GENERAL";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  // Se deja este valor como "NO APLICA" porque el usuario no es un medico, por lo tanto no tiene Especialidad
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosDireccionGeneral.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);		
									
													
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
								}
								
								
								// Perfil Jefe de Enfermeria
								if ($jefe_enfermeria == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Jefe Enfermeria)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "JEFE DE ENFERMERIA";
									$estadomed = "Activo";
									$especialidad = "JEFE DE ENFERMERIA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosJefeEnfermeria.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');					
									insertarUsuarioxHC($usuario,"HOJA DE MEDICAMENTOS","UTILIDADES");				
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');					
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarUsuariosxAlmacenes($usuario,"FARMACIA",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarAutorizaUsuxSolicitudes($usuario, "FARMACIA",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
								}
								
								
								// Perfil Nutricionista
								if ($nutricionista == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Nutricionista)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "NUTRICIONISTA";
									$estadomed = "Activo";
									$especialidad = "NUTRICIONISTA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosTerapiaOcupacional.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");								
														
									insertarUsusxOrdMeds($usuario,"Notas");						
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
									
								}
								
								// Perfil Medico Sexologo
								if ($medico_sexologo == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Medico sexologo)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "MEDICO SEXOLOGO";
									$estadomed = "Activo";
									$especialidad = "SEXOLOGIA CLINICA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosMedicoSexologo.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");								
														
									insertarUsusxOrdMeds($usuario,"Notas");						
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
									
								}
								
								
								
								// Perfil Sistemas
								if ($sistemas == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor de sistemas)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE SISTEMAS";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								
								// Perfil Roperia
								if ($roperia == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Roperia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "ROPERIA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Auditoria
								if ($auditoria == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Auditoria)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "AUDITORIA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosAuditoria.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Comunidad Hermanos Hospitalarios
								if ($comunidad == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Hermano Hospitalario)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "HERMANO HOSPITALARIO";
									$estadomed = "Activo";
									$especialidad = "COMUNIDAD HERMANOS";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosComunidad.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Costos
								if ($costos == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Costos)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "COSTOS";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil SIAU
								if ($siau == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (SIAU)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "SIAU";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosSIAU.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');
									insertarUsuarioxHC($usuario,"AUTORIZA SERVICIOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LIQUIDACION","UTILIDADES");
									insertarUsuarioxHC($usuario,"DESCUENTOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
									
									
								}
								
								
								// Perfil "Interno"
								if ($interno == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Interno)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "INTERNO";
									$estadomed = "Activo";
									$especialidad = "INTERNO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosInterno.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil "Residente"
								if ($residente == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Residente)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "RESIDENTE";
									$estadomed = "Activo";
									$especialidad = "RESIDENTE";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosResidente.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');
									
									insertarUsusxOrdMeds($usuario,"Notas");				
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
								}
								
								
								// Perfil "Practicante Psicologia"
								if ($practicante_psicologia == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Practicante Psicologia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PRACTICANTE PSICOLOGIA";
									$estadomed = "Activo";
									$especialidad = "PRACTICANTE PSICOLOGIA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								
								// Perfil Auxiliar Terapia
								if ($auxiliar_terapia == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Auxiliar Terapia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "AUXILIAR DE TERAPIA";
									$estadomed = "Activo";
									$especialidad = "AUXILIAR DE TERAPIA OCUPACIONAL";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Contabilidad
								if ($contabilidad == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Contabilidad)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "CONTADOR";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosContador.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									insertarUsuarioxHC($usuario,"UTILIDADES",'');					
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Talento Humano
								if ($talento_humano == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor Talento Humano)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE TALENTO HUMANO";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosTalentoHumano.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
									
								}
								
								
								
								// Perfil Suministros
								if ($suministros == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Suministros)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "AUXILIAR DE SUMINISTROS";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosSuministros.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								// Perfil Pedagogo
								if ($pedagogo_reeducador == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Pedagogo)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PEDAGOGO";
									$estadomed = "Activo";
									$especialidad = "PEDAGOGO REEDUCADOR";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosPedagogo.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");								
									
									insertarUsusxOrdMeds($usuario,"Notas");			
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Clinica de Adicciones
								if ($clinica_adicciones == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Clinica Adicciones)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "CLINICA ADICCIONES";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosClinicaAdicciones.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Comunicaciones
								if ($comunicaciones == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Comunicaciones)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "COMUNICACIONES";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Educador Fisico
								if ($educador_fisico == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Educador fisico)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "EDUCADOR FISICO";
									$estadomed = "Activo";
									$especialidad = "EDUCADOR FISICO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Infraestructura
								if ($infraestructura == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Infraestructura)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE AMBIENTE FISICO";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								
								// Perfil Financiero
								if ($financiero == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor Financiero)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR FINANCIERO";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosGestorFinanciero.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Tesoreria
								if ($tesoreria == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Tesoreria)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "TESORERO";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosTesoreria.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Direccion Cientifica
								if ($direccion_cientifica == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Direccion Cientifica)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "DIRECCION CIENTIFICA";
									$estadomed = "Activo";
									$especialidad = "DIRECCION CIENTIFICA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosDireccionCientifica.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');
									insertarUsuarioxHC($usuario,"GUARDAR FICHA DE IDENTIFICACI&Oacute;N",'');					
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									
									insertarUsusxOrdMeds($usuario,"Notas");
													
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Servicios Generales
								if ($servicios_generales == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Servicios Generales)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "SERVICIOS GENERALES";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								
								
								// Perfil Calidad
								if ($calidad == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor Calidad)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE CALIDAD";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosGestorCalidad.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Paciente Seguro
								if ($paciente_seguro == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Paciente Seguro)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PACIENTE SEGURO";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Gestor de Farmacia
								if ($quimico_farmaceuta == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor Farmacia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE FARMACIA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosGestorFarmacia.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
														
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );
									insertarUsuariosxAlmacenes($usuario,"FARMACIA",$compania );
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									insertarAutorizaUsuxSolicitudes($usuario, "FARMACIA",$compania);
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Porteria
								if ($porteria == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Porteria)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "PORTERIA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								// Perfil Gestor de area
								if ($gestor_area == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor area)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR DE AREA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosGestorArea.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								// Perfil Gestor de alimentos
								if ($gestor_alimentos == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Gestor Alimentos)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "GESTOR  DE ALIMENTOS";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosGestorAlimentos.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);				
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil NeuroPsicologia
								if ($neuropsicologo == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Neuropsicologia)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "NEUROPSICOLOGA";
									$estadomed = "Activo";
									$especialidad = "NEUROPSICOLOGIA";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosNeuropsicologia.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									insertarUsuarioxHC($usuario,"UTILIDADES",'');	
									insertarUsuarioxHC($usuario,"ORDENES MEDICAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ALERTAS","UTILIDADES");
									insertarUsuarioxHC($usuario,"LABORATORIOS EXTERNOS","UTILIDADES");
									insertarUsuarioxHC($usuario,"ANEXOS",'');
									insertarUsuarioxHC($usuario,"AYUDAS DX","ANEXOS");
									insertarUsuarioxHC($usuario,"DOCUMENTOS ANEXOS","ANEXOS");					
														
									
									insertarUsusxOrdMeds($usuario,"Interprogramas");					
									insertarUsusxOrdMeds($usuario,"Notas");			
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);
									
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
								
								// Perfil Revisor
								if ($revisor == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Revisor)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "REVISOR";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
								}
								
								
								
								// Perfil Cartera
								if ($cartera == 1){
									$nombre = $usuariopadre;
										if ($agregarCargo == 1){													
											$usuario = $usuariopadre." (Cartera)";
										}	
									$rm = "NA"; // Se deja en el momento como NA, luego se actualiza en la Migracion de Medicos
									$cargo = "CARTERA";
									$estadomed = "Activo";
									$especialidad = "ADMINISTRATIVO";  
									
									crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre);
									insertarMedicos($usuario, $rm, $cargo, $compania, $estadomed, $especialidad);
									
									insertarPermisosCSV("Central.UsuariosxModulos","permisosCartera.csv");
									actualizarPermisos("Central.UsuariosxModulos",$usuario);
									
									
									
									insertarUsuariosxAlmacenes($usuario,"SUMINISTROS",$compania );					
									insertarAutorizaUsuxSolicitudes($usuario, "SUMINISTROS",$compania);					
									insertarPermisosCSV("Consumo.Usuariosxcc","usuariosxcc.csv");
									actualizarPermisos("Consumo.UsuariosxCC",$usuario);
								}
								
								
						}
						
						
						
						
						
							
						
						function  normalizarCodificacionUsuarios($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Central.Usuarios SET usuario = replace( usuario,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nombre = replace( nombre,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), cedula = replace( cedula,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						function  normalizarCodificacionUsuariosxModulos($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Central.Usuariosxmodulos SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), modulo = replace( modulo,'$cadenaBusqueda','$cadenaReemplazo'), madre = replace( madre,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						function  normalizarCodificacionUsuariosxHC($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Salud.UsuariosxHC SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), modulo = replace( modulo,'$cadenaBusqueda','$cadenaReemplazo'), madre = replace( madre,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						
						function  normalizarCodificacionUsusxOrdMeds($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Salud.UsusxOrdMeds SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), modulo = replace( modulo,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						function  normalizarCodificacionAutorizaUsuxSolicitudes($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Consumo.AutorizaUsuxSolicitudes SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						function  normalizarCodificacionUsuariosxAlmacenes($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Consumo.UsuariosxAlmacenes SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo') , compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						function  normalizarCodificacionUsuariosxCC($cadenaBusqueda,$cadenaReemplazo)  {
							$cnx= conectar_postgres();
							$cons = "UPDATE Consumo.UsuariosxCC SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
							$res = pg_query($cnx , $cons);
								if (!$res)  {
									echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
								}

						}
						
						
							
						
					
						
						
						function eliminarTablaMigracion() {
							// Elimina la tabla Contabilidad.movimientoMigracion 
							$cnx= conectar_postgres();
							$cons = "DROP TABLE  IF EXISTS Central.UsuariosMigracion";
							$res =  pg_query($cons);
							
						}
						
						
						function crearTablaMigracion() {
						// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
							$cnx= conectar_postgres();
							$cons = "CREATE TABLE central.usuariosMigracion(  usuario character varying(100) ,  nombre character varying(200),  cedula character varying(200),  clave character varying(200),  cambioclave date,  fechaultimoacceso date,  fechacaducidad date,  super integer , usuariopadre character varying,  error text)WITH (  OIDS=FALSE)";
							
							$res = @pg_query($cnx, $cons);
								if (!$res) {
								
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";			
									
								}
							
						}
						
						
						
						function insertarRegistroMigracion($usuario, $nombre, $cedula, $clave,$usuariopadre,  $error) {
							
							$cnx = 	conectar_postgres();
							$cons = "INSERT INTO Central.UsuariosMigracion (usuario, nombre , cedula, clave, usuariopadre,  error ) VALUES ('$usuario','$nombre','$cedula','$clave','$usuariopadre','$error')"	;
									 
							$cons = str_replace( "'NULL'","NULL",$cons  );	
							$res = @pg_query($cnx, $cons);
								if(!$res) {
									$consUTF8 = utf8_encode($cons);
									$resUTF8 = @pg_query($cnx, $consUTF8);
										if (!$resUTF8) {
											
											$fp = fopen("ReporteUsuarios.html", "a+");	
											$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
											$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
											fputs($fp, $errorEjecucion);
											fputs($fp, $consulta);
											fclose($fp);
											
										}
								}	
						}	
						
						function crearArchivo() {
							$fp = fopen("ReporteUsuarios.html", "w+");
							$encabezado = "<html> <head> <title> Reporte errores Central.Usuarios </title> 
							<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
							fputs($fp, $encabezado);
							fclose($fp);
						}	
						
						
						function crearCampoUsuarioPadre(){
							$cnx = 	conectar_postgres();
							$cons = "ALTER TABLE central.usuarios   ADD COLUMN   usuariopadre character varying;";
							$res = @pg_query($cnx, $cons);			
						}
						
						
						
						function crearUsuario($usuario, $nombre, $cedula, $clave, $usuariopadre) {
							
							$cnx = 	conectar_postgres();
							$cons = "INSERT INTO Central.Usuarios (usuario, nombre , cedula, clave, usuariopadre ) VALUES ('$usuario','$nombre','$cedula','$clave', '$usuariopadre')"	;
							$cons = str_replace( "'NULL'","NULL",$cons  );	
							$res = @pg_query($cnx, $cons);
								if (!$res) {
									$consUTF8 = utf8_encode($cons);
									$resUTF8 = @pg_query($cnx, $consUTF8);					
										if (!$resUTF8) {
											$error = pg_last_error();
											insertarRegistroMigracion($usuario, $nombre, $cedula, $clave, $usuariopadre,  $error);
										}
								
								}
					
						}
						
						
						function  llenarMatriz(){
							
							unset($matriz); 
							unset($vectorPerfiles);
							global  $matriz;	
							$res = llamarRegistrosMySQL();
							$posicion=0;
							
								
								while ($fila = mysql_fetch_array($res))
								{	
									
									$matriz["usuario"][$posicion] = $fila["Usuario"];					
									$matriz["clave"][$posicion] = $fila["Clave"];
									$matriz["cedula"][$posicion] = $fila["Cedula"];
									
									$matriz["trabajo_social"][$posicion] = $fila["trabajo_social"];
									$matriz["administrador"][$posicion] = $fila["Administrador"];
									$matriz["estadistica"][$posicion] = $fila["Estadistica"];
									$matriz["facturacion"][$posicion] = $fila["Facturacion"];
									$matriz["medico_general"][$posicion] = $fila["Medico_General"];
									$matriz["psiquiatra"][$posicion] = $fila["Psiquiatra"];
									$matriz["psicologo"][$posicion] = $fila["Psicologo"];
									$matriz["enfermeria"][$posicion] = $fila["Enfermeria"];
									$matriz["medico_rural"][$posicion] = $fila["Medico_rural"];
									$matriz["director"][$posicion] = $fila["Director"];
									$matriz["terapia_ocupacional"][$posicion] = $fila["Terapia_Ocupacional"];
									$matriz["odontologia"][$posicion] = $fila["Odontologia"];
									$matriz["farmacia"][$posicion] = $fila["Farmacia"];
									$matriz["direccion_general"][$posicion] = $fila["Direccion_General"];
									$matriz["jefe_enfermeria"][$posicion] = $fila["Jefe_Enfermeria"];
									$matriz["nutricionista"][$posicion] = $fila["Nutricionista"];
									$matriz["sistemas"][$posicion] = $fila["Sistemas"];
									$matriz["roperia"][$posicion] = $fila["Roperia"];
									$matriz["auditoria"][$posicion] = $fila["Auditoria"];
									$matriz["comunidad"][$posicion] = $fila["Comunidad"];
									$matriz["costos"][$posicion] = $fila["Costos"];
									$matriz["siau"][$posicion] = $fila["SIAU"];
									$matriz["interno"][$posicion] = $fila["Interno"];
									$matriz["residente"][$posicion] = $fila["Residente"];
									$matriz["practicante_psicologia"][$posicion] = $fila["Practicante_psicologia"];
									$matriz["auxiliar_terapia"][$posicion] = $fila["Auxiliar_Terapia"];
									$matriz["contabilidad"][$posicion] = $fila["Contabilidad"];
									$matriz["psiquiatra_adj"][$posicion] = $fila["Psiquiatra_Adj"];
									$matriz["talento_humano"][$posicion] = $fila["Talento_Humano"];
									$matriz["suministros"][$posicion] = $fila["Suministros"];
									$matriz["pedagogo_reeducador"][$posicion] = $fila["Pedagogo_reeducador"];
									$matriz["clinica_adicciones"][$posicion] = $fila["Clinica_adicciones"];
									$matriz["comunicaciones"][$posicion] = $fila["Informacion_y_Comunicaciones"];
									$matriz["educador_fisico"][$posicion] = $fila["Educador_fisico"];
									$matriz["infraestructura"][$posicion] = $fila["Infraestructura"];
									$matriz["financiero"][$posicion] = $fila["Financiero"];
									$matriz["tesoreria"][$posicion] = $fila["Tesoreria"];
									$matriz["direccion_cientifica"][$posicion] = $fila["Direccion_Cientifica"];
									$matriz["servicios_generales"][$posicion] = $fila["Servicios_Generales"];
									$matriz["calidad"][$posicion] = $fila["Calidad"];
									$matriz["paciente_seguro"][$posicion] = $fila["Paciente_Seguro"];
									$matriz["quimico_farmaceuta"][$posicion] = $fila["Quimico_Farmaceuta"];
									$matriz["porteria"][$posicion] = $fila["Porteria"];
									$matriz["gestor_area"][$posicion] = $fila["Gestor_area"];
									$matriz["gestor_alimentos"][$posicion] = $fila["Gestor_Servicio_de_Alimentos"];
									$matriz["neuropsicologo"][$posicion] = $fila["NeuroPsicologo"];
									$matriz["revisor"][$posicion] = $fila["Revisor"];
									$matriz["cartera"][$posicion] = $fila["Cartera"];
									$matriz["medico_sexologo"][$posicion] = $fila["medico_sexologo"];
									

									$posicion++;				
								}
											
								
							}
							

								
						
						
						
							
							function insertarTabla()  {
							
								global $res,$matriz, $trabajo_social;
									for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

										global  $trabajo_social , $administrador , $estadistica , $facturacion , $medico_general , $psiquiatra , $psicologo , $enfermeria , $medico_rural , $director , $terapia_ocupacional , $terapia_ocupacional , $odontologia , $farmacia , $direccion_general , $jefe_enfermeria , $nutricionista , $sistemas , $roperia , $auditoria , $comunidad , $costos , $siau , $interno , $residente , $practicante_psicologia , $auxiliar_terapia , $contabilidad , $psiquiatra_adj , $talento_humano , $suministros , $pedagogo_reeducador , $clinica_adicciones , $comunicaciones , $educador_fisico , $infraestructura , $financiero , $tesoreria , $direccion_cientifica , $servicios_generales , $calidad , $paciente_seguro , $quimico_farmaceuta , $porteria , $gestor_area , $gestor_alimentos , $neuropsicologo , $revisor , $cartera , $medico_sexologo;
							
										$usuario= $matriz["usuario"][$pos] ;
										$usuario= eliminarCaracteresEspeciales($usuario);
										$nombre = $usuario;
										$cedula= $matriz["cedula"][$pos] ;
										$cedula= eliminarCaracteresEspeciales($cedula);
										$clave= $matriz["clave"][$pos] ;
										
										$trabajo_social= $matriz["trabajo_social"][$pos] ;
										$administrador= $matriz["administrador"][$pos] ;
										$estadistica= $matriz["estadistica"][$pos] ;
										$facturacion= $matriz["facturacion"][$pos] ;
										$medico_general= $matriz["medico_general"][$pos] ;
										$psiquiatra= $matriz["psiquiatra"][$pos] ;
										$psicologo= $matriz["psicologo"][$pos] ;
										$enfermeria= $matriz["enfermeria"][$pos] ;
										$medico_rural= $matriz["medico_rural"][$pos] ;
										$director= $matriz["director"][$pos] ;
										$terapia_ocupacional= $matriz["terapia_ocupacional"][$pos] ;
										$odontologia= $matriz["odontologia"][$pos] ;
										$farmacia= $matriz["farmacia"][$pos] ;
										$direccion_general= $matriz["direccion_general"][$pos] ;
										$jefe_enfermeria= $matriz["jefe_enfermeria"][$pos] ;
										$nutricionista = $matriz["nutricionista"][$pos] ;
										$sistemas = $matriz["sistemas"][$pos] ;
										$roperia = $matriz["roperia"][$pos] ;
										$auditoria = $matriz["auditoria"][$pos] ;
										$comunidad = $matriz["comunidad"][$pos] ;
										$costos = $matriz["costos"][$pos] ;
										$siau = $matriz["siau"][$pos] ;
										$interno = $matriz["interno"][$pos] ;
										$residente = $matriz["residente"][$pos] ;
										$practicante_psicologia = $matriz["practicante_psicologia"][$pos] ;
										$auxiliar_terapia = $matriz["auxiliar_terapia"][$pos] ;
										$contabilidad = $matriz["contabilidad"][$pos] ;
										$psiquiatra_adj = $matriz["psiquiatra_adj"][$pos] ;
										$talento_humano = $matriz["talento_humano"][$pos] ;
										$suministros = $matriz["suministros"][$pos] ;
										$pedagogo_reeducador = $matriz["pedagogo_reeducador"][$pos] ;
										$clinica_adicciones = $matriz["clinica_adicciones"][$pos] ;
										$comunicaciones = $matriz["comunicaciones"][$pos] ;
										$educador_fisico = $matriz["educador_fisico"][$pos] ;
										$infraestructura = $matriz["infraestructura"][$pos] ;
										$financiero = $matriz["financiero"][$pos] ;
										$tesoreria = $matriz["tesoreria"][$pos] ;
										$direccion_cientifica = $matriz["direccion_cientifica"][$pos] ;
										$servicios_generales = $matriz["servicios_generales"][$pos] ;
										$calidad = $matriz["calidad"][$pos] ;
										$paciente_seguro = $matriz["paciente_seguro"][$pos] ;
										$quimico_farmaceuta = $matriz["quimico_farmaceuta"][$pos] ;
										$porteria = $matriz["porteria"][$pos] ;
										$gestor_area = $matriz["gestor_area"][$pos] ;
										$gestor_alimentos = $matriz["gestor_alimentos"][$pos] ;
										$neuropsicologo = $matriz["neuropsicologo"][$pos] ;
										$revisor = $matriz["revisor"][$pos] ;
										$cartera = $matriz["cartera"][$pos] ;
										$medico_sexologo = $matriz["medico_sexologo"][$pos] ;
									
														
										// Define si se debe crear solo un usuario o varios (Uno por cada perfil)
										
										$sumatoriaPerfiles =  $trabajo_social + $administrador + $estadistica + $facturacion + $medico_general + $psiquiatra + $psicologo + $enfermeria + $medico_rural + $director + $terapia_ocupacional + $odontologia + $farmacia + $direccion_general + $jefe_enfermeria + $nutricionista + $sistemas + $roperia + $auditoria + $comunidad + $costos + $siau + $interno + $residente + $practicante_psicologia + $auxiliar_terapia + $contabilidad + $psiquiatra_adj + $talento_humano + $suministros + $pedagogo_reeducador + $clinica_adicciones + $comunicaciones + $educador_fisico + $infraestructura + $financiero + $tesoreria + $direccion_cientifica + $servicios_generales + $calidad + $paciente_seguro + $quimico_farmaceuta + $porteria + $gestor_area + $gestor_alimentos + $neuropsicologo + $revisor + $cartera + $medico_sexologo;
										
										
										if($sumatoriaPerfiles > 1){
											$agregarCargo = 1; 
										} else {
											$agregarCargo = 0; 
										}
										
										crearUsuariosPerfiles($usuario,$nombre, $cedula, $clave, $agregarCargo);
										
										
									}

							}
							
							function eliminarUsuarios() {
								$cnx = 	conectar_postgres();
								$cons = "TRUNCATE Central.usuarios CASCADE";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							function eliminarUsuariosxModulos() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Central.UsuariosxModulos";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							function eliminarAutorizaUsuxSolicitudes() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Consumo.AutorizaUsuxSolicitudes";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							
							function eliminarUsuariosxCC() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Consumo.UsuariosxCC";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							
							function eliminarUsuariosxAlmacenes() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Consumo.UsuariosxAlmacenes";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							function eliminarUsusxOrdMeds() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Salud.UsusxOrdMeds";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							function eliminarUsuariosxHC() {
								$cnx = 	conectar_postgres();
								$cons = "DELETE FROM Salud.UsuariosxHC";
								$res= @pg_query($cnx, $cons);
									if (!$res) {			
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
										echo "<br><br>";					
									}

							}
							
							
							function actualizarUsuarioAdmin() {
							$cnx= conectar_postgres();
							$cons = "UPDATE Central.usuarios SET Usuario = 'Admin' WHERE usuario='ADMINISTRADOR'";
							$res =  pg_query($cnx, $cons);
									if (!$res) {			
									echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
									echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
									echo "<br><br>";					
									}
						
							}
							
							function cambiarAmbito() {
						
							$cnx = conectar_postgres();
							
							$cons= "UPDATE Central.AccesoxModulos SET perfil = 'PROCESOS' WHERE  modulogr = 'CONTRATACION EN SALUD' AND UPPER(Perfil) = 'AMBITOS'  ";
							$res =  @pg_query($cons);
								if (!$res) {
											echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
											echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
											
										}
							
							}
							
							function permisosAdministrador() {
						
								$cnx = conectar_postgres();
								$ruta = $_SERVER['DOCUMENT_ROOT'];
								$cons= "COPY Central.UsuariosxModulos FROM '$ruta/Migraciones/Central/Usuarios/permisosAdministrador.csv' WITH DELIMITER ';' CSV HEADER;";
								
								$res =  @pg_query($cons);
									if (!$res) {
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
											
									}
							
							
							}
							
							function eliminarPermisosAdministrador() {
						
								$cnx = conectar_postgres();
								
								$cons= "DELETE FROM Central.UsuariosxModulos WHERE Usuario = 'ADMINISTRADOR (Administrador)'";
								$res =  @pg_query($cons);
									if (!$res) {
										echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
											
									}
							
							
							}
						
							
							
							
						function actualizarClaveAdministrador() {
						
						$cnx = conectar_postgres();
						$cons = "UPDATE Central.usuarios SET clave = '0192023a7bbd73250516f069df18b500' WHERE usuariopadre = 'ADMINISTRADOR'";
						$res = pg_query($cnx, $cons);
						
						
						}	
						
						function normalizarAccesoxModulos() {
						
						$cnx = conectar_postgres();
						$cons = "UPDATE Central.accesoxmodulos SET madre = upper(madre), perfil= upper(perfil), modulogr=upper(modulogr)";
						$res = pg_query($cnx, $cons);
						
						
						}		

						/* Finaliza la definicion de funciones*/	
						
						
						
						
						/* Inicia la ejecucion de la migracion */
							
						if($_GET['tabla']="Usuarios") {
						
							echo "<fieldset>";			
							echo "<legend> Migracion tabla Central.Usuarios </legend>";
							echo "<br>";
							echo "<span align='left'> <a href='../../index.php?migracion=MIG005' class = 'link1'> Panel de Administracion </a> </span>";
							
							
							eliminarPKUsuarios();
							eliminarUniqueUsuarios();
							crearCampoUsuarioPadre();
							cambiarAmbito();
							eliminarTablaMigracion();
							crearTablaMigracion();
							crearArchivo();
							eliminarMedicos();
							generarNotaAclaratoria(1,"Se ha ejecutado la sentencia TRUNCATE en la Tabla 'Salud.Medicos', eso afecta los formatos autonomos");
							eliminarUsuariosxModulos();
							eliminarUsuariosxHC();
							eliminarUsusxOrdMeds();
							eliminarUsuariosxAlmacenes();
							eliminarUsuariosxCC();
							eliminarAutorizaUsuxSolicitudes();
							eliminarUsuarios();
							generarNotaAclaratoria(2,"Se ha ejecutado la sentencia TRUNCATE en la Tabla 'Central.Usuarios'");
							crearUsuario("USUARIOTMP", "USUARIOTMP", "000", "000", "USUARIOTMP");
							normalizarAccesoxModulos();
							
							

										
							llenarMatriz();
							insertarTabla();

									   
							// Tabla Central.Usuarios 
							normalizarCodificacionUsuarios('&Aacute;',utf8_encode("Á"));			
							normalizarCodificacionUsuarios('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsuarios('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsuarios('&Oacute;' , utf8_encode("Ó"));
							normalizarCodificacionUsuarios('&Uacute;' , utf8_encode("Ú"));
							normalizarCodificacionUsuarios('&Ntilde;', utf8_encode("Ñ"));
							
							
							
							// Tabla UsuariosxModulos
							normalizarCodificacionUsuariosxModulos('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionUsuariosxModulos('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsuariosxModulos('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsuariosxModulos('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionUsuariosxModulos('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionUsuariosxModulos('&Ntilde;',utf8_encode("Ñ"));
							
							// Tabla UsuariosxHC
							normalizarCodificacionUsuariosxHC('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionUsuariosxHC('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsuariosxHC('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsuariosxHC('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionUsuariosxHC('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionUsuariosxHC('&Ntilde;',utf8_encode("Ñ"));
							
							
							
							// Tabla UsusxOrdMeds
							
							normalizarCodificacionUsusxOrdMeds('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionUsusxOrdMeds('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsusxOrdMeds('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsusxOrdMeds('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionUsusxOrdMeds('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionUsusxOrdMeds('&Ntilde;',utf8_encode("Ñ"));
							
							
							
							
							// Tabla AutorizaUsuxSolicitudes
							
							normalizarCodificacionAutorizaUsuxSolicitudes('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionAutorizaUsuxSolicitudes('&Eacute;', utf8_encode("É"));
							normalizarCodificacionAutorizaUsuxSolicitudes('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionAutorizaUsuxSolicitudes('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionAutorizaUsuxSolicitudes('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionAutorizaUsuxSolicitudes('&Ntilde;',utf8_encode("Ñ"));
							
							// Tabla UsuariosxAlmacenes
							
							normalizarCodificacionUsuariosxAlmacenes('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionUsuariosxAlmacenes('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsuariosxAlmacenes('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsuariosxAlmacenes('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionUsuariosxAlmacenes('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionUsuariosxAlmacenes('&Ntilde;',utf8_encode("Ñ"));
							
							
							// Tabla UsuariosxCC
							
							normalizarCodificacionUsuariosxCC('&Aacute;', utf8_encode("Á"));			
							normalizarCodificacionUsuariosxCC('&Eacute;', utf8_encode("É"));
							normalizarCodificacionUsuariosxCC('&Iacute;', utf8_encode("Í"));
							normalizarCodificacionUsuariosxCC('&Oacute;', utf8_encode("Ó"));
							normalizarCodificacionUsuariosxCC('&Uacute;',utf8_encode("Ú"));
							normalizarCodificacionUsuariosxCC('&Ntilde;',utf8_encode("Ñ"));
							
							
						
						
						
							
							
							//actualizarUsuarioAdmin();
							actualizarClaveAdministrador();
							generarNotaAclaratoria(3,"La clave del usuario ADMINISTRADOR es: admin123");
							
							
							
							
							
										
							echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Central.Usuarios</p> </div>";
						
									   
							
								
								$totalMySQL = contarRegistrosMySQL();
								$totalPostgresql =  contarRegistrosPostgresql();
								$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
								
								echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
								echo  $totalMySQL."<br/>";
								echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
								echo  $totalPostgresql."<br/>";
								echo "<p class= 'error1'> Total errores generados(Tabla Central.Usuarios):</p>";
								echo  $totalPostgresqlErrores."<br/>";
								
								echo "<p> <a href='ReporteUsuarios.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p> <br/>";
								
								echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Usuarios </a> </span>";
								
								
													

								
								
							echo "</fieldset>";
							
						}
							

				?>
				
		</body>
	</html>	
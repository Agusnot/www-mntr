	<html>
		<head>
			<title> Migracion Central.Terceros </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
		
		<body>


			<?php
				session_start();
				include_once '../../Conexiones/conexion.php';
				include_once('../../General/funciones/funciones.php');

				/* Inicia la definicion de funciones */
				
				
					
					function contarRegistrosMySQL() {
						$cnx = conectar_mysql("Contabilidad");
						$cons = "SELECT COUNT(*) AS conteoMySQL FROM Contabilidad.Terceros";
						$res =  mysql_query($cons);
						$fila = mysql_fetch_array($res);
						$res = $fila['conteoMySQL'];
						return $res; 	
					
					}
					
					
					function contarRegistrosPostgresql() {
						$cnx= conectar_postgres();
						$cons = "SELECT COUNT(*) AS conteo FROM Central.Terceros";
						$res =  pg_query($cnx, $cons);
						$fila = pg_fetch_array($res);
						$res = $fila['conteo'];
						return $res; 	
					
					}
					
					function contarRegistrosPostgresqlErrores() {
						$cnx= conectar_postgres();
						$cons = "SELECT COUNT(*) AS conteo FROM Central.TercerosMigracion";
						$res =  pg_query($cnx, $cons);
						$fila = pg_fetch_array($res);
						$res = $fila['conteo'];
						return $res; 	
					
					}
					
					
					
					function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
						$cnx= conectar_postgres();
						$cons = "UPDATE Central.TiposTercero SET tipo = replace( tipo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
						
						$res = pg_query($cnx , $cons);
							if (!$res)  {
								echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
							}

					}
					
					function  reemplazarTextoTabla2($cadenaBusqueda,$cadenaReemplazo)  {
						$cnx= conectar_postgres();
						$cons = "UPDATE Central.tipospersonas SET tipo = replace( tipo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
						
						$res = pg_query($cnx , $cons);
							if (!$res)  {
								echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
							}

					}
					
						
					
					function  reemplazarTextoTabla3($cadenaBusqueda,$cadenaReemplazo)  {
						$cnx= conectar_postgres();
						$cons = "UPDATE Central.Terceros SET identificacion = replace( identificacion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), primape = replace( primape,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), segape = replace( segape,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , primnom = replace( primnom,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , segnom  = replace( segnom,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , replegal = replace( replegal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , direccion = replace( direccion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , telefono = replace( telefono,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , pais = replace( pais,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), departamento = replace( departamento,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , municipio = replace( municipio,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , tipo = replace( tipo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , regimen = replace( regimen,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , autoretefte = replace( autoretefte,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , autoreteiva = replace( autoreteiva,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), email = replace( email,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".") , compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
						$res = pg_query($cnx , $cons);
							if (!$res)  {
								echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
							}

					}
					
					
						
					
				
					function llamarRegistrosMySQL() {
						
						global $res;
						$cnx = conectar_mysql("Contabilidad");
						$cons = "SELECT *  FROM Contabilidad.Terceros ORDER BY Identificacion ASC ";
						$res =  mysql_query($cons);
						return $res; 
					
					}
					
					function eliminarTablaMigracion() {
						// Elimina la tabla Contabilidad.movimientoMigracion 
						$cnx= conectar_postgres();
						$cons = "DROP TABLE  IF EXISTS Central.TercerosMigracion";
						$res =  pg_query($cons);
						
					}
					
					
					function crearTablaMigracion() {
					// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
						$cnx= conectar_postgres();
						$cons = "CREATE TABLE central.tercerosMigracion(  identificacion character varying(100) ,  primape character varying(200),  segape character varying(200),  primnom character varying(200),  segnom character varying(200),  replegal character varying(200),  direccion text,  telefono text,  pais character varying(50),  departamento character varying(50),  municipio character varying(50),  tipo character varying(200),  regimen character varying(200),  autoretefte character varying(10),  autoreteiva character varying(10),  email character varying(100),  compania character varying(200) ,  tipopersona character varying(50),  tipodoc character varying(50),  lugarexp character varying(50),  numha character varying(15),  tiposangre character varying(50),  fecnac date,  sexo character varying(50),  ecivil character varying(15),  eps character varying(30),  tipousu character varying(50),  nivelusu character varying(15),  usuariocreador character varying(50),  fechacreacion date,  naturalde character varying(50),  zonares character varying(50), escolaridad character varying(50),  religion character varying(50),  ocupacion character varying(600),  vivecon character varying(100),  nocarnet character varying(30),  triage character varying(30),  usuariomod character varying(50),  codigosgsss character varying(50),  nomcontacto character varying(100),  telcontacto character varying(50),  notas text,  tipoasegurador character varying(80),  copago integer,  cuotamoderadora integer,  vereda character varying(200),  ultactuliza date,  acompanante character varying(200),  celular character varying(20),  cargonomina character varying(100),  estadopaciente character varying(15),  procedentede character varying(100),  asistenciapaciente character varying(12),  institucionalidad character varying(2),  dircontacto character varying(100),  ciudcontacto character varying(100),  parentcontacto character varying(100),  digitoverificacion character varying(10),  entidadurg character varying(100),  contratourg character varying(100),  nocontratourg character varying(50),  tiposusurips character varying(2),  comedor character varying(255),  docacompanante character varying(50),  expcedacomp character varying(100),  diracompanante character varying(120),  telacompanante character varying(100),  parentescoacomp character varying(50),  error text)WITH (  OIDS=FALSE)";
						
						$res = @pg_query($cnx, $cons);
							if (!$res) {
							
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
								echo "<br><br>";			
								
							}
						
					}
					
					
					
					function insertarRegistroMigracion($identificacion, $primape ,$segape , $primnom , $segnom , $replegal ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$regimen , $tipopersona, $autoretefte, $autoreteiva, $email, $compania, $error) {
						
						$cnx = 	conectar_postgres();
						$cons = "INSERT INTO Central.TercerosMigracion (identificacion, primape ,segape , primnom , segnom , replegal ,direccion ,telefono ,pais ,departamento ,municipio ,tipo ,regimen , tipopersona, autoretefte, autoreteiva, email, compania, error) VALUES ('$identificacion','$primape','$segape','$primnom','$segnom','$replegal','$direccion','$telefono','$pais','$departamento','$municipio','$tipo','$regimen','$tipopersona','$autoretefte','$autoreteiva','$email','$compania' ,'$error')"	;
								 
						$cons = str_replace( "'NULL'","NULL",$cons  );	
						$res = @pg_query($cnx, $cons);
							if(!$res) {
								$consUTF8 = utf8_encode($cons);
								$resUTF8 = @pg_query($cnx, $consUTF8);
									if (!$resUTF8) {
										
										$fp = fopen("ReporteTerceros.html", "a+");	
										$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
										$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
										fputs($fp, $errorEjecucion);
										fputs($fp, $consulta);
										fclose($fp);
										
									}
							}	
					}	
					
					function crearArchivo() {
						$fp = fopen("ReporteTerceros.html", "w+");
						$encabezado = "<html> <head> <title> Reporte errores Central.Terceros </title> 
						<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
						fputs($fp, $encabezado);
						fclose($fp);
					}	
					
					
					
					
					
					function insertarRegistroPostgresql($identificacion, $primape ,$segape , $primnom , $segnom , $replegal ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo,$regimen ,$tipopersona, $autoretefte, $autoreteiva, $email, $compania) {
						
						$cnx = 	conectar_postgres();
						
						$cons = "INSERT INTO Central.Terceros (identificacion, primape ,segape , primnom , segnom , replegal ,direccion ,telefono ,pais ,departamento ,municipio ,tipo ,regimen , tipopersona, autoretefte, autoreteiva, email, compania) VALUES ('$identificacion','$primape','$segape','$primnom','$segnom','$replegal','$direccion','$telefono','$pais','$departamento','$municipio','$tipo','$regimen','$tipopersona','$autoretefte','$autoreteiva','$email','$compania')"	;
						$cons = str_replace( "'NULL'","NULL",$cons  );	
						$res = @pg_query($cnx, $cons);
							if (!$res) {
								$consUTF8 = utf8_encode($cons);
								$resUTF8 = @pg_query($cnx, $consUTF8);					
									if (!$resUTF8) {
										$error = pg_last_error();
										insertarRegistroMigracion($identificacion, $primape ,$segape , $primnom , $segnom , $replegal ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$regimen , $tipopersona, $autoretefte, $autoreteiva, $email, $compania, $error); 
									}
							
							}

							
					}
					
					
					function  llenarMatriz(){
						
						unset($matriz); 
						global  $matriz;	
						$res = llamarRegistrosMySQL();
						$posicion=0;
							
							while ($fila = mysql_fetch_array($res))
							{	
								
								$matriz["identificacion"][$posicion] = $fila["Identificacion"];
								$matriz["primape"][$posicion] = $fila["PrimApe"];
								$matriz["segape"][$posicion] = $fila["SegApe"];
								$matriz["primnom"][$posicion] = $fila["PrimNom"];
								$matriz["segnom"][$posicion] = $fila["SegNom"];
								$matriz["replegal"][$posicion] = $fila["RepLegal"];
								$matriz["direccion"][$posicion] = $fila["Direccion"];
								$matriz["telefono"][$posicion] = $fila["Telefono"];
								$matriz["pais"][$posicion] = $fila["Pais"];
								$matriz["departamento"][$posicion] = $fila["Departamento"];
								$matriz["municipio"][$posicion] = $fila["Municipio"];
								$matriz["tipo"][$posicion] = $fila["Tipo"];
								$matriz["autoretefte"][$posicion] = $fila["AutoReteFte"];
								$matriz["autoreteiva"][$posicion] = $fila["AutoReteIVA"];
								$matriz["email"][$posicion] = $fila["Email"];
								$matriz["regimen"][$posicion] = $fila["Regimen"];
								$matriz["tipopersona"][$posicion] = $fila["PersonaNatural"];
								
								
								
								$posicion++;				
							}
										
							
						}
						
						function eliminarActividadEconomica() {
						$cnx = conectar_postgres();
						$cons = "ALTER TABLE central.terceros DROP COLUMN   codactividadeconomica ";
						$res = @pg_query($cnx, $cons);
							if (!$res){
								//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
							
							}
						
						}
						
						
						function crearCampoActividadEconomica() {
						$cnx = conectar_postgres();
						$cons = "ALTER TABLE central.terceros ADD COLUMN   codactividadeconomica character varying(15)";
						$res = @pg_query($cnx, $cons);
							if (!$res){
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
							
							}
						
						}
						
						

							
					
					
					
						
						function insertarTabla()  {
						
							global $res,$matriz;
								for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

								

								$identificacion=	 $matriz["identificacion"][$pos] ;
								$identificacion= eliminarCaracteresEspeciales($identificacion);
								$primape=	 $matriz["primape"][$pos] ;
								$primape= eliminarCaracteresEspeciales($primape);
								$segape=	 $matriz["segape"][$pos] ;
								$segape= eliminarCaracteresEspeciales($segape);
								$primnom=	 $matriz["primnom"][$pos] ;
								$primnom= eliminarCaracteresEspeciales($primnom);
								$segnom=	 $matriz["segnom"][$pos] ;
								$segnom= eliminarCaracteresEspeciales($segnom);
								$replegal=	 $matriz["replegal"][$pos] ;
								$replegal= eliminarCaracteresEspeciales($replegal);
								$direccion=	 $matriz["direccion"][$pos] ;
								$direccion= eliminarCaracteresEspeciales($direccion);
								$telefono=	 $matriz["telefono"][$pos] ;
								$telefono= eliminarCaracteresEspeciales($telefono);
								$pais=	 $matriz["pais"][$pos] ;
								$pais= eliminarCaracteresEspeciales($pais);
								$departamento=	 $matriz["departamento"][$pos] ;
								$departamento= eliminarCaracteresEspeciales($departamento);
								$municipio=	 $matriz["municipio"][$pos] ;
								$municipio= eliminarCaracteresEspeciales($municipio);
								$tipo=	 $matriz["tipo"][$pos] ;
								//$tipo= eliminarCaracteresEspeciales($tipo);
								$regimen=	 $matriz["regimen"][$pos] ;
								$regimen = strtoupper($regimen);
								$regimen = trim($regimen);
								
									if ($regimen == "SIMPLIFICADO") {
										$regimen = "Simplificado";						
									}
									
									if ($regimen == "PERSONA NATURAL") {
										$regimen = "Persona Natural";						
									}
									
									if ($regimen == "COMÚN") {
										$regimen = "Comun";						
									}
									
								$tipopersona = 	 $matriz["tipopersona"][$pos];
								$tipopersona = strtoupper($tipopersona);
								$tipopersona = trim($tipopersona);
									if ($tipopersona=="VERDADERO") {
										$tipopersona = "Persona Natural";
									}
									
									if ($tipopersona=="FALSO") {
										$tipopersona = "Persona Juridica";
									}
									
									
									if (trim($tipopersona)=="") {
										if ($regimen == "Persona Natural"){
											$tipopersona = "Persona Natural";
										} 
										elseif ($regimen != "Persona Natural") {
											if (trim($primnom)=="" and trim($segnom)=="" and  trim($segape)==""  ) {
												$tipopersona = "Persona Juridica";
											}
											if (trim($primnom)!="" or trim($segnom)!="" or  trim($segape)!= ""  ) {
												$tipopersona = "Persona Natural";
											}
										}	
									}
									
									
								$autoretefte=	 $matriz["autoretefte"][$pos] ;
								$autoretefte= eliminarCaracteresEspeciales($autoretefte);
								$autoreteiva=	 $matriz["autoreteiva"][$pos] ;
								$autoreteiva= eliminarCaracteresEspeciales($autoreteiva);
								$email=	 $matriz["email"][$pos] ;
								$email= eliminarCaracteresEspeciales($email);
								$compania=	 $_SESSION["compania"];;
								

								insertarRegistroPostgresql($identificacion, $primape ,$segape , $primnom , $segnom , $replegal ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$regimen , $tipopersona, $autoretefte, $autoreteiva, $email, $compania);				

								}

						}
						
						function eliminarTerceros() {
							$cnx = 	conectar_postgres();
							$cons = "TRUNCATE Central.Terceros CASCADE";
							$res= pg_query($cnx, $cons);
						
						}
						
						
						function actualizarTercerosEPS() {
							$cnx = conectar_postgres();
							$cons = "UPDATE   Central.Terceros SET TipoDoc = 'Nit', Tipo = 'Asegurador' WHERE Identificacion IN (SELECT DISTINCT(NIT) FROM Central.EPS)";
							$res = @pg_query($cnx, $cons);
								if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
								
								}
								
						
						}
						
						
						
						function eliminarConstraint($nombreConstraint) {
							$cnx = 	conectar_postgres();
							$cons= 'ALTER TABLE Central.Terceros DROP CONSTRAINT IF EXISTS"'.$nombreConstraint.'"';
							$res = @pg_query($cnx, $cons);
							
						
						}
						
						
						function agregarConstraint() {
							$cnx = 	conectar_postgres();
							$cons= 	'ALTER TABLE Central.Terceros ADD CONSTRAINT  "FkTercerosxTipoPer" FOREIGN KEY (tipopersona) REFERENCES Central.TiposPersonas (tipo) ON UPDATE CASCADE ON DELETE RESTRICT';
							$res = @pg_query($cnx, $cons);
							
						
						}
						
							

					/* Finaliza la definicion de funciones*/	
					
					
					
					
					/* Inicia la ejecucion de la migracion */
						
					if($_GET['tabla']="Terceros") {
					
						echo "<fieldset>";			
						echo "<legend> Migracion tabla Central.Terceros </legend>";
						echo "<br>";
						echo "<span align='left'> <a href='../../index.php?migracion=MIG004' class = 'link1'> Panel de Administracion </a> </span>";
						
						
						eliminarTablaMigracion();
						crearTablaMigracion();
						eliminarConstraint("FkTercerosxTipoPer");
						agregarConstraint();
						crearArchivo();
						eliminarTerceros();
						
						// Tabla Contabilidad.Comprobantes 
						reemplazarTextoTabla1(utf8_encode("Á"),'&Aacute;');			
						reemplazarTextoTabla1(utf8_encode("É"),'&Eacute;');
						reemplazarTextoTabla1(utf8_encode("Í"),'&Iacute;');
						reemplazarTextoTabla1(utf8_encode("Ó"),'&Oacute;');
						reemplazarTextoTabla1(utf8_encode("Ú"),'&Uacute;');
						reemplazarTextoTabla1(utf8_encode("Ñ"),'&Ntilde;');
						
						// Tabla Contabilidad.FormasPago 
						reemplazarTextoTabla2(utf8_encode("Á"),'&Aacute;');			
						reemplazarTextoTabla2(utf8_encode("É"),'&Eacute;');
						reemplazarTextoTabla2(utf8_encode("Í"),'&Iacute;');
						reemplazarTextoTabla2(utf8_encode("Ó"),'&Oacute;');
						reemplazarTextoTabla2(utf8_encode("Ú"),'&Uacute;');
						reemplazarTextoTabla2(utf8_encode("Ñ"),'&Ntilde;');
						
						// Tabla Contabilidad.TiposPago 
						reemplazarTextoTabla3(utf8_encode("Á"),'&Aacute;');			
						reemplazarTextoTabla3(utf8_encode("É"),'&Eacute;');
						reemplazarTextoTabla3(utf8_encode("Í"),'&Iacute;');
						reemplazarTextoTabla3(utf8_encode("Ó"),'&Oacute;');
						reemplazarTextoTabla3(utf8_encode("Ú"),'&Uacute;');
						reemplazarTextoTabla3(utf8_encode("Ñ"),'&Ntilde;');

						

									
						llenarMatriz();
						insertarTabla();

								   
						//Tabla Contabilidad.Comprobantes 
						reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
						reemplazarTextoTabla1('&Eacute;', utf8_encode("É"));
						reemplazarTextoTabla1('&Iacute;', utf8_encode("Í"));
						reemplazarTextoTabla1('&Oacute;' , utf8_encode("Ó"));
						reemplazarTextoTabla1('&Uacute;' , utf8_encode("Ú"));
						reemplazarTextoTabla1('&Ntilde;', utf8_encode("Ñ"));
						
						// Tabla Contabilidad.FormasPago 
						reemplazarTextoTabla2('&Aacute;', utf8_encode("Á"));			
						reemplazarTextoTabla2('&Eacute;', utf8_encode("É"));
						reemplazarTextoTabla2('&Iacute;', utf8_encode("Í"));
						reemplazarTextoTabla2('&Oacute;', utf8_encode("Ó"));
						reemplazarTextoTabla2('&Uacute;', utf8_encode("Ú"));
						reemplazarTextoTabla2('&Ntilde;', utf8_encode("Ñ"));
						
						// Tabla Contabilidad.TiposPago 
						reemplazarTextoTabla3('&Aacute;', utf8_encode("Á"));			
						reemplazarTextoTabla3('&Eacute;', utf8_encode("É"));
						reemplazarTextoTabla3('&Iacute;', utf8_encode("Í"));
						reemplazarTextoTabla3('&Oacute;', utf8_encode("Ó"));
						reemplazarTextoTabla3('&Uacute;', utf8_encode("Ú"));
						reemplazarTextoTabla3('&Ntilde;', utf8_encode("Ñ"));
						
						actualizarTercerosEPS();
						eliminarActividadEconomica();
						crearCampoActividadEconomica();
						
						
						echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Central.Terceros</p> </div>";
					
								   
						
							
							$totalMySQL = contarRegistrosMySQL();
							$totalPostgresql =  contarRegistrosPostgresql();
							$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
							
							echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
							echo  $totalMySQL."<br/>";
							echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
							echo  $totalPostgresql."<br/>";
							echo "<p class= 'error1'> Total errores generados(Tabla Contabilidad.TercerosMigracion):</p>";
							echo  $totalPostgresqlErrores."<br/>";
							
							echo "<p> <a href='reporteTerceros.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p><br/>";
							
							echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Central.Terceros  </a> </span>";
							
							
							
							
												

							
							
						echo "</fieldset>";
						
					}


				?>
		</body>		
	
</html>	
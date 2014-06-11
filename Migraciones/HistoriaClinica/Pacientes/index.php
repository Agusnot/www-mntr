	<html>
		<head>
			<title> Migracion Pacientes </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php
	session_start();
	include_once '../../Conexiones/conexion.php';
	include_once('../../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
	
	
		
		function contarRegistrosMySQLPacientes() {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Salud.Admision";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresqlPacientes() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Central.Terceros WHERE Tipo = 'Paciente'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlPacientesErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Central.PacientesMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function seleccionarDepartamento($codigo) {
			$cnx= conectar_postgres();
			$cons = "SELECT departamento FROM Central.Departamentos WHERE codigo = '$codigo'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['departamento'];
			return $res; 	
		
		}
		
		function seleccionarEPS($nombre) {
			$cnx= conectar_postgres();
			$nombre = strtoupper($nombre);
			$cons = "SELECT nit FROM Central.EPS WHERE UPPER(eps) LIKE '$nombre'";
			$res =  @pg_query($cnx, $cons);
			$fila = @pg_fetch_array($res);
			
			$res = $fila['nit'];
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
		
		
			
		
	
		function llamarRegistrosMySQLPacientes() {
			
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.Admision ORDER BY NumCed ASC";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function eliminarTablaMigracionPacientes() {
			// Elimina la tabla Contabilidad.movimientoMigracion 
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.PacientesMigracion";
			$res =  pg_query($cons);
			
		}
		
		function crearArchivoErroresPacientes() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("../Errores/Pacientes.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Esquema Historia Clinica </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		function crearTablaMigracionPacientes() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.PacientesMigracion(  identificacion character varying(100) ,  primape character varying(200),  segape character varying(200),  primnom character varying(200),  segnom character varying(200),  replegal character varying(200),  direccion text,  telefono text,  pais character varying(50),  departamento character varying(50),  municipio character varying(50),  tipo character varying(200),  regimen character varying(200),  autoretefte character varying(10),  autoreteiva character varying(10),  email character varying(100),  compania character varying(200) ,  tipopersona character varying(50),  tipodoc character varying(50),  lugarexp character varying(50),  numha character varying(15),  tiposangre character varying(50),  fecnac date,  sexo character varying(50),  ecivil character varying(15),  eps character varying(30),  tipousu character varying(50),  nivelusu character varying(15),  usuariocreador character varying(50),  fechacreacion date,  naturalde character varying(50),  zonares character varying(50), escolaridad character varying(50),  religion character varying(50),  ocupacion character varying(600),  vivecon character varying(100),  nocarnet character varying(30),  triage character varying(30),  usuariomod character varying(50),  codigosgsss character varying(50),  nomcontacto character varying(100),  telcontacto character varying(50),  notas text,  tipoasegurador character varying(80),  copago integer,  cuotamoderadora integer,  vereda character varying(200),  ultactuliza date,  acompanante character varying(200),  celular character varying(20),  cargonomina character varying(100),  estadopaciente character varying(15),  procedentede character varying(100),  asistenciapaciente character varying(12),  institucionalidad character varying(2),  dircontacto character varying(100),  ciudcontacto character varying(100),  parentcontacto character varying(100),  digitoverificacion character varying(10),  entidadurg character varying(100),  contratourg character varying(100),  nocontratourg character varying(50),  tiposusurips character varying(2),  comedor character varying(255),  docacompanante character varying(50),  expcedacomp character varying(100),  diracompanante character varying(120),  telacompanante character varying(100),  parentescoacomp character varying(50), estrato integer,  error text)WITH (  OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		
		function insertarRegistroPacientesMigracion($identificacion, $primape ,$segape , $primnom , $segnom  ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$compania , $tipopersona, $tipodoc, $lugarexp, $numha, $tiposangre,   $fecnac, $sexo, $ecivil, $eps, $tipousu, $usuariocreador, $fechacreacion, $estrato, $escolaridad, $ocupacion, $usuariomod, $acompanante, $estadopaciente, $asistenciapaciente, $institucionalidad, $docacompanante, $expcedacomp, $diracompanante, $telacompanante, $parentescoacomp, $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.Pacientesmigracion (identificacion, primape ,segape , primnom , segnom  ,direccion ,telefono ,pais ,departamento ,municipio ,tipo ,compania , tipopersona, tipodoc, lugarexp, numha, tiposangre,   fecnac, sexo, ecivil, eps, tipousu, usuariocreador, fechacreacion, estrato, escolaridad, ocupacion, usuariomod, acompanante, estadopaciente, asistenciapaciente, institucionalidad, docacompanante, expcedacomp, diracompanante, telacompanante, parentescoacomp, error) VALUES ('$identificacion', '$primape' ,'$segape' , '$primnom' , '$segnom' ,'$direccion' ,'$telefono' ,'$pais', '$departamento' ,'$municipio' , '$tipo' ,'$compania' , '$tipopersona' , '$tipodoc' , '$lugarexp' , '$numha', '$tiposangre',   '$fecnac' , '$sexo' , '$ecivil' , '$eps' , '$tipousu' , '$usuariocreador' , '$fechacreacion' , $estrato, '$escolaridad' , '$ocupacion' , '$usuariomod' , '$acompanante' , '$estadopaciente', '$asistenciapaciente', '$institucionalidad' , '$docacompanante' , '$expcedacomp' , '$diracompanante' , '$telacompanante' , '$parentescoacomp', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("../Errores/Pacientes.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		
		
		
		
		
		function insertarRegistroPostgresqlPacientes($identificacion, $primape ,$segape , $primnom , $segnom  ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$compania , $tipopersona, $tipodoc, $lugarexp, $numha, $tiposangre,   $fecnac, $sexo, $ecivil, $eps, $tipousu, $usuariocreador, $fechacreacion, $estrato, $escolaridad, $ocupacion, $usuariomod, $acompanante, $estadopaciente, $asistenciapaciente, $institucionalidad, $docacompanante, $expcedacomp, $diracompanante, $telacompanante, $parentescoacomp) {
			
			$cnx = 	conectar_postgres();
			
			$cons = "INSERT INTO Central.Terceros (identificacion, primape ,segape , primnom , segnom  ,direccion ,telefono ,pais ,departamento ,municipio ,tipo ,compania , tipopersona, tipodoc, lugarexp, numha, tiposangre,   fecnac, sexo, ecivil, eps, tipousu, usuariocreador, fechacreacion, estrato, escolaridad, ocupacion, usuariomod, acompanante, estadopaciente, asistenciapaciente, institucionalidad, docacompanante, expcedacomp, diracompanante, telacompanante, parentescoacomp) VALUES ('$identificacion', '$primape' ,'$segape' , '$primnom' , '$segnom' ,'$direccion' ,'$telefono' ,'$pais', '$departamento' ,'$municipio' , '$tipo' ,'$compania' , '$tipopersona' , '$tipodoc' , '$lugarexp' , '$numha', '$tiposangre',   '$fecnac' , '$sexo' , '$ecivil' , '$eps' , '$tipousu' , '$usuariocreador' , '$fechacreacion' , $estrato, '$escolaridad' , '$ocupacion' , '$usuariomod' , '$acompanante' , '$estadopaciente', '$asistenciapaciente', '$institucionalidad' , '$docacompanante' , '$expcedacomp' , '$diracompanante' , '$telacompanante' , '$parentescoacomp')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroPacientesMigracion($identificacion, $primape ,$segape , $primnom , $segnom  ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$compania , $tipopersona, $tipodoc, $lugarexp, $numha, $tiposangre,   $fecnac, $sexo, $ecivil, $eps, $tipousu, $usuariocreador, $fechacreacion, $estrato, $escolaridad, $ocupacion, $usuariomod, $acompanante, $estadopaciente, $asistenciapaciente, $institucionalidad, $docacompanante, $expcedacomp, $diracompanante, $telacompanante, $parentescoacomp, $error); 
						}
				
				}

				
		}
		
		
		function  llenarMatrizPacientes(){
			
			unset($matriz); 
			global  $matriz, $res;	
			$res = llamarRegistrosMySQLPacientes();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["identificacion"][$posicion] = $fila["NumCed"];
					$matriz["primape"][$posicion] = $fila["PrimApe"];
					$matriz["segape"][$posicion] = $fila["SegApe"];
					$matriz["primnom"][$posicion] = $fila["PrimNom"];
					$matriz["segnom"][$posicion] = $fila["SegNom"];
					$matriz["direccion"][$posicion] = $fila["direccion"];
					$matriz["telefono"][$posicion] = $fila["Telefono"];
					$matriz["departamento"][$posicion] = $fila["Depto"];
					$matriz["municipio"][$posicion] = $fila["Mpo"];
					$matriz["tipodoc"][$posicion] = $fila["TipoDoc"];
					$matriz["lugarexp"][$posicion] = $fila["LugarExp"];
					$matriz["numha"][$posicion] = $fila["NumHa"];
					$matriz["tiposangre"][$posicion] = $fila["TipoSangre"];
					$matriz["fecnac"][$posicion] = $fila["FecNac"];
					$matriz["sexo"][$posicion] = $fila["Sexo"];
					$matriz["ecivil"][$posicion] = $fila["ECivil"];
					$matriz["eps"][$posicion] = $fila["NomEnt"];
					$matriz["tipousu"][$posicion] = $fila["TipoReg"];
					$matriz["usuariocreador"][$posicion] = $fila["UsuarioCreador"];
					$matriz["fechacreacion"][$posicion] = $fila["FechaCreacion"];
					$matriz["estrato"][$posicion] = $fila["Estrato"];
					$matriz["escolaridad"][$posicion] = $fila["Escolaridad"];
					$matriz["ocupacion"][$posicion] = $fila["Ocupacion"];
					$matriz["usuariomod"][$posicion] = $fila["UsuarioMod"];
					$matriz["acompanante"][$posicion] = $fila["NombreAcudiente1"];
					$matriz["estadopaciente"][$posicion] = $fila["TipoEstado"];
					$matriz["asistenciapaciente"][$posicion] = $fila["Asistencia"];
					$matriz["institucionalidad"][$posicion] = $fila["Institucionalizar"];
					$matriz["docacompanante"][$posicion] = $fila["CCAcudiente1"];
					$matriz["expcedacomp"][$posicion] = $fila["LugarExpAcd1"];
					$matriz["diracompanante"][$posicion] = $fila["DirAcudiente1"];
					$matriz["telacompanante"][$posicion] = $fila["TelefAcudiente1"];
					$matriz["parentescoacomp"][$posicion] = $fila["Parentesco1"];
					
					
					
					$posicion++;				
				}
							
				
			}
			
		
			
			function insertarTablaPacientes()  {
			
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
					$direccion=	 $matriz["direccion"][$pos] ;
					$direccion= eliminarCaracteresEspeciales($direccion);
					$telefono=	 $matriz["telefono"][$pos] ;
					$telefono= eliminarCaracteresEspeciales($telefono);
					$departamento=	 $matriz["departamento"][$pos] ;
					$departamento = seleccionarDepartamento($departamento);
					$departamento= eliminarCaracteresEspeciales($departamento);
						if (isset($departamento)) {
							$pais = "Colombia"	;
						}
					$municipio=	 $matriz["municipio"][$pos] ;
					$municipio= eliminarCaracteresEspeciales($municipio);
					$tipo=	"Paciente";
					$tipopersona = 	 "Persona Natural";
					$tipodoc= $matriz["tipodoc"][$pos] ;
					$lugarexp=	 $matriz["lugarexp"][$pos] ;
					$numha=	 $matriz["numha"][$pos] ;					
					$tiposangre= $matriz["tiposangre"][$pos] ;
					$tiposangre = strtoupper($tiposangre);
					$fecnac= $matriz["fecnac"][$pos] ;
						if ($fecnac == "0000-00-00"){
							$fecnac = "1900-01-01"	;
						}	
						
						if ($fecnac == ""){
							$fecnac = 'NULL'	;
						}	
										
					$sexo= $matriz["sexo"][$pos] ;	
					$sexo = strtoupper($sexo) ;
					$ecivil= $matriz["ecivil"][$pos] ;	
					$ecivil= eliminarCaracteresEspeciales($ecivil);
						if ($ecivil == "SOLTERO"){
							$ecivil = "SOLTERO(A)";
						}
										
					$eps= $matriz["eps"][$pos] ;	
					$eps = seleccionarEPS($eps)	;
					$tipousu= $matriz["tipousu"][$pos] ;					
					$usuariocreador= $matriz["usuariocreador"][$pos] ;
					$usuariocreador= eliminarCaracteresEspeciales($usuariocreador);
					$fechacreacion= $matriz["fechacreacion"][$pos] ;
						if ($fechacreacion == "0000-00-00"){
							$fechacreacion = "1900-01-01"	;
						}
						if ($fechacreacion == ""){
							$fechacreacion = 'NULL'	;
						}	
					$estrato= $matriz["estrato"][$pos] ;
					$escolaridad= $matriz["escolaridad"][$pos] ;
					$ocupacion= $matriz["ocupacion"][$pos] ;																				
					$usuariomod= $matriz["usuariomod"][$pos] ;
					$usuariomod= eliminarCaracteresEspeciales($usuariomod);
					$acompanante= $matriz["acompanante"][$pos] ;
					$acompanante= eliminarCaracteresEspeciales($acompanante);
					$estadopaciente= $matriz["estadopaciente"][$pos] ;
					$asistenciapaciente= $matriz["asistenciapaciente"][$pos] ;
						if ($asistenciapaciente== "Primera vez"){
							$asistenciapaciente = "Primera Vez";
						}
					$institucionalidad= $matriz["institucionalidad"][$pos] ;
					$docacompanante= $matriz["docacompanante"][$pos] ;
					$expcedacomp= $matriz["expcedacomp"][$pos] ;
					$expcedacomp= eliminarCaracteresEspeciales($expcedacomp);
					$diracompanante= $matriz["diracompanante"][$pos] ;
					$diracompanante= eliminarCaracteresEspeciales($diracompanante);	
					$telacompanante= $matriz["telacompanante"][$pos] ;
					$parentescoacomp= $matriz["parentescoacomp"][$pos] ;
					$compania=	 $_SESSION["compania"];
					

					insertarRegistroPostgresqlPacientes($identificacion, $primape ,$segape , $primnom , $segnom  ,$direccion ,$telefono ,$pais ,$departamento ,$municipio ,$tipo ,$compania , $tipopersona, $tipodoc, $lugarexp, $numha, $tiposangre,  $fecnac, $sexo, $ecivil, $eps, $tipousu, $usuariocreador, $fechacreacion, $estrato, $escolaridad, $ocupacion, $usuariomod, $acompanante, $estadopaciente, $asistenciapaciente, $institucionalidad, $docacompanante, $expcedacomp, $diracompanante, $telacompanante, $parentescoacomp);				

					}

			}
			
			
			
			function eliminarTercerosPacientes() {
								
				$resMySQL = listadoPacientesMySQL();
				
					while ($fila = mysql_fetch_array($resMySQL)){
						$cnx = 	conectar_postgres();	
						$identificacion = $fila["NumCed"];
						$cons = "DELETE FROM Central.terceros WHERE Identificacion = '$identificacion' AND tipo <> 'Asegurador'";
						$res= @pg_query($cnx, $cons);
							if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
							}
						$pos++;	
						
					}	
			
			}
			
			
			function eliminarCampoEstrato() {
			$cnx = 	conectar_postgres();
			$cons = "ALTER TABLE central.terceros DROP COLUMN estrato";
			$res= @pg_query($cnx, $cons);
					if (!$res) {
						//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
			}
			
			function agregarCampoEstrato() {
			$cnx = 	conectar_postgres();
			$cons = "ALTER TABLE central.terceros ADD COLUMN estrato integer";
			$res= @pg_query($cnx, $cons);
					if (!$res) {
						//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
			}
			
			
			
			function listadoPacientesMySQL() {
				
				$cnx = conectar_mysql("Salud");
				$cons = "SELECT DISTINCT(NumCed) FROM Salud.Admision";
				$res = @mysql_query($cons);
					if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
				return $res;
					
			
			}
			
			
			
			
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		if($_GET['tabla']="Terceros") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla Central.Terceros </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG014' class = 'link1'> Panel de Administracion </a> </span>";
			
			eliminarTablaMigracionPacientes();
			crearTablaMigracionPacientes();
			eliminarTercerosPacientes();
			eliminarCampoEstrato();
			agregarCampoEstrato();
			crearArchivoErroresPacientes();
			
			
			
			// Tabla Central.TiposTercero
			/*reemplazarTextoTabla1(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla1(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla1(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla1(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla1(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla1(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Central.TiposPersonas 
			reemplazarTextoTabla2(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla2(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla2(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla2(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla2(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla2(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Central.Terceros 
			reemplazarTextoTabla3(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla3(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla3(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla3(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla3(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla3(utf8_encode("Ñ"),'&Ntilde;');*/

			

						
			llenarMatrizPacientes();
			insertarTablaPacientes();

					   
			// Tabla Central.TiposTercero
			/*reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;' , utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;' , utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;', utf8_encode("Ñ"));
			
			// Tabla Central.TiposPersonas  
			reemplazarTextoTabla2('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla2('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla2('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla2('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla2('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla2('&Ntilde;', utf8_encode("Ñ"));*/
			
			// Tabla Central.Terceros
			reemplazarTextoTabla3('&Aacute;', utf8_encode("Á"));			
			reemplazarTextoTabla3('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla3('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla3('&Oacute;', utf8_encode("Ó"));
			reemplazarTextoTabla3('&Uacute;', utf8_encode("Ú"));
			reemplazarTextoTabla3('&Ntilde;', utf8_encode("Ñ"));
			
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de Pacientes </p> </div>";
		
					   
			
				
				$totalMySQL = contarRegistrosMySQLPacientes();
				$totalPostgresql = contarRegistrosPostgresqlPacientes();
				$totalPostgresqlErrores = contarRegistrosPostgresqlPacientesErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Contabilidad.TercerosMigracion):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='../Errores/Pacientes.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p><br/>";
				
				echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Central.Terceros  </a> </span>";
				
				
				
				
									

				
				
			echo "</fieldset>";
			
		}
			
	

		
		
		
		

	
	




?>
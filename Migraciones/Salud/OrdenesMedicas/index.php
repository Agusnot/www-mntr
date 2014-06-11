	<html>	
		<head>
			<title> Migracion Salud.OrdenesMedicas </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once '../../General/funciones/funciones.php';
		include_once '../../Conexiones/conexion.php';
		
		
		
		
		
		/* Inicia definicion de funciones */
		
			
		function  normalizarCodificacionOrdenes($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Salud.OrdenesMedicas SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'),  cedula = replace( cedula,'$cadenaBusqueda','$cadenaReemplazo') ,  detalle = replace( detalle,'$cadenaBusqueda','$cadenaReemplazo'),  revisadopor = replace( revisadopor,'$cadenaBusqueda','$cadenaReemplazo'),  jefeenfermeria = replace( jefeenfermeria,'$cadenaBusqueda','$cadenaReemplazo'),  tipoorden = replace( tipoorden,'$cadenaBusqueda','$cadenaReemplazo')  ,  estado = replace( estado,'$cadenaBusqueda','$cadenaReemplazo') ,  viasumin = replace( viasumin,'$cadenaBusqueda','$cadenaReemplazo') ";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo"<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		
		
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.OrdenesMedicas";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.OrdenesMedicasMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		function contarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql('salud');
			
			$cons = "SELECT COUNT(*) AS conteo FROM Salud.OrdenesMedicas";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$numregistros = $fila["conteo"];
			
			return $numregistros; 
		
		}		
				
	
		function llamarRegistrosMySQLOrdenes($limiteinicial, $limitefinal) {
			// Selecciona los registros MySQL (Origen)
			//global $res;
			$cnx = conectar_mysql("salud");
			
			$cons = "SELECT CONCAT(Fecha,' ', Hora) AS fechahora, OrdenesMedicas.* FROM Salud.OrdenesMedicas ORDER BY noorden ASC LIMIT $limiteinicial, $limitefinal ";
			echo "<br>";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		
		function crearOrdenesMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS salud.ordenesmedicasMigracion(  compania character varying(80) ,  fecha timestamp without time zone ,  cedula character varying(15) ,  numservicio integer ,  detalle text ,  idescritura integer ,  numorden integer ,  revisadopor character varying(100),  fecharevisado timestamp without time zone,  jefeenfermeria character varying(100),  fechajefe timestamp without time zone,  usuario character varying(100),  tipoorden character varying(100),  estado character varying(2) ,  acarreo integer,  posologia character varying(300),  dosisunica integer,  viasumin character varying(50),  fechareprog timestamp without time zone,  consistenciadieta character varying(50),  observaciondieta character varying(600),  error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			
		}
		
		
		
		function eliminarOrdenesMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DELETE FROM  salud.OrdenesMedicasMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
	
		
		function insertarOrdenesMigracion( $compania,$fecha,$cedula,$numservicio,$detalle,$idescritura,$numorden,$revisadopor,$fecharevisado,$jefeenfermeria,$fechajefe,$usuario,$tipoorden,$estado,  $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.OrdenesMedicasMigracion (compania,fecha,cedula,numservicio,detalle,idescritura,numorden,revisadopor,fecharevisado,jefeenfermeria,fechajefe,usuario,tipoorden,estado,  error) VALUES ('$compania','$fecha','$cedula',$numservicio,'$detalle',$idescritura,$numorden,'$revisadopor','$fecharevisado','$jefeenfermeria','$fechajefe','$usuario','$tipoorden','$estado', '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("ErroresOrdenesMedicas.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("ErroresOrdenesMedicas", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Salud.OrdenesMedicas </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		
		

		
		function insertarOrdenes($compania,$fecha,$cedula,$numservicio,$detalle,$idescritura,$numorden,$revisadopor,$fecharevisado,$jefeenfermeria,$fechajefe,$usuario,$tipoorden,$estado) {
		//Realiza la insercion en Postgresql con base en los parametros
		
		
		
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.OrdenesMedicas (compania,fecha,cedula,numservicio,detalle,idescritura,numorden,revisadopor,fecharevisado,jefeenfermeria,fechajefe,usuario,tipoorden,estado) VALUES ('$compania','$fecha','$cedula',$numservicio,'$detalle',$idescritura,$numorden,'$revisadopor','$fecharevisado','$jefeenfermeria','$fechajefe','$usuario','$tipoorden','$estado')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarOrdenesMigracion( $compania,$fecha,$cedula,$numservicio,$detalle,$idescritura,$numorden,$revisadopor,$fecharevisado,$jefeenfermeria,$fechajefe,$usuario,$tipoorden,$estado,  $error);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizOrdenes($limiteinicial, $limitefinal){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $res, $matriz;	
			$res = llamarRegistrosMySQLOrdenes($limiteinicial, $limitefinal);
			
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["fecha"][$posicion] = $fila["fechahora"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];
					$matriz["detalle"][$posicion] = $fila["Detalle"];
					$matriz["idescritura"][$posicion] = $fila["IdEscritura"];
					$matriz["numorden"][$posicion] = $fila["NoOrden"];					
					$matriz["revisadopor"][$posicion] = $fila["RevisadoPor"];
					$matriz["fecharevisado"][$posicion] = $fila["FechaRevision"];
					$matriz["jefeenfermeria"][$posicion] = $fila["VistoBueno"];	
					$matriz["fechajefe"][$posicion] = $fila["FechaVoBo"];
					$matriz["usuario"][$posicion] = $fila["Usuario"];
					$matriz["tipoorden"][$posicion] = $fila["TipoOrden"];
					$matriz["idhospitalizacionmysql"][$posicion] = $fila["IdHospitalizacion"];
					
															
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizOrdenes($inicial)  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					
					
					
					for($pos=0;$pos <= mysql_num_rows($res); $pos++)  {
					
					$compania = $_SESSION["compania"];				
					$fecha = $matriz["fecha"][$pos] ;						
					$cedula= $matriz["cedula"][$pos] ;
					$detalle= $matriz["detalle"][$pos] ;
					$idescritura= $matriz["idescritura"][$pos] ;
					$numorden= $matriz["numorden"][$pos] ;
					$numorden= $matriz["numorden"][$pos] ;
					$revisadopor= $matriz["revisadopor"][$pos] ;
					$fecharevisado= $matriz["fecharevisado"][$pos] ;
						if (trim($fecharevisado)== "") {
							$fecharevisado = 'NULL';
						}
					$jefeenfermeria= $matriz["jefeenfermeria"][$pos] ;
					$fechajefe = $matriz["fechajefe"][$pos] ;
						if (trim($fechajefe)== "") {
							$fechajefe = 'NULL';
						}
					$usuario= $matriz["usuario"][$pos] ;
					$tipoorden= $matriz["tipoorden"][$pos] ;
					$idhospitalizacionmysql= $matriz["idhospitalizacionmysql"][$pos] ;
					$numservicio = definirServicio($cedula, $idhospitalizacionmysql);
					$estado = 'AN';
					
					
					insertarOrdenes($compania,$fecha,$cedula,$numservicio,$detalle,$idescritura,$numorden,$revisadopor,$fecharevisado,$jefeenfermeria,$fechajefe,$usuario,$tipoorden,$estado) ;
					
									
				}
			
			}
			
			function eliminarOrdenes() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Salud.OrdenesMedicas";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
		
		function migrarServicios() {
			
			
			
			
			crearOrdenesMigracion();
			eliminarOrdenesMigracion();			
			crearArchivoErrores();
			eliminarOrdenes();
			
			
			$total = contarRegistrosMySQL();
			$fragmento = 8192;
//$total = 100;
//$fragmento = 100;			
			
				if ($total >= $fragmento ) {

				
					$numCiclos = $total / $fragmento;
					$numCiclos = ceil($numCiclos);
										
					$limiteIni = 1;
					
						for ($i = 1; $i <= $numCiclos; $i++){
						
							llenarMatrizOrdenes($limiteIni,$fragmento);
							recorrerMatrizOrdenes($limiteIni);
								if ($i == $numCiclos) {
									echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Salud.OrdenesMedicas</p> </div>";
									break;
								}	
							$limiteIni = $limiteIni + $fragmento;							
							
					   } 
				}	   
			
			
			/*$numRegistros = contarRegistrosMySQL();
			$ciclo = $numRegistros/2 ;
			
			$ciclo = ceil($ciclo);
			
			
			
			llenarMatrizOrdenes(1, $ciclo);
			recorrerMatrizOrdenes(1);*/
			/*$posicionInicial = $ciclo +1;
			llenarMatrizOrdenes($posicionInicial, $ciclo);
			recorrerMatrizOrdenes($posicionInicial);*/
			
			
			
			//Tabla Salud.OrdenesMedicas
			normalizarCodificacionOrdenes('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionOrdenes('&Eacute;', utf8_encode("É"));
			normalizarCodificacionOrdenes('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionOrdenes('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionOrdenes('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionOrdenes('&Ntilde;',utf8_encode("Ñ"));
			
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Salud.OrdenesMedicas </p> </div>";
			
	
		}
		
		
		if($_GET['tabla']="Ordenes_Medicas") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG068' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			migrarServicios();
			
			$totalMySQL = contarRegistrosMySQL();
			$totalPostgresql =  contarRegistrosPostgresql();
			$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
			
			echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
			echo  $totalMySQL."<br/>";
			echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
			echo  $totalPostgresql."<br/>";
			echo "<p class= 'error1'> Total errores genereados(Tabla Salud.ServiciosMigracion):</p>";
			echo  $totalPostgresqlErrores."<br/>";
			
			echo "<br/> <br/>";
			echo "<span align='right'> <a href='ErroresOrdenesMedicas.html' target='_blank' class = 'link1'> Ver Reporte de Errores</a> </span>";			
			echo "<br/> <br/>";
			
			echo "</fieldset>";
			
		}
			
		
		
		
	
	
	
	?>

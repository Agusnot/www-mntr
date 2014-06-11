	<html>	
		<head>
			<title> Migracion Consumo.Movimiento </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		session_start();
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		include_once('../TmpMovimiento/procedimiento.php');		
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Suministros.ProductosxSolicitud";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.solicitudconsumo";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.solicitudConsumoMigracion";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.CentrosCosto SET centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function generarNota($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		function  cambiarcampo()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.solicitudconsumo   ALTER COLUMN centrocostos TYPE character varying(100)";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		function  cambiarcampo1()  {
			$cnx= conectar_postgres();
			$cons = "ALTER TABLE consumo.solicitudconsumo   ALTER COLUMN observaciones TYPE text";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}

		}
		
		
		
		
		
		
		function consProductosSolicitud($idsolicitud) {
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT * FROM Suministros.ProductosxSolicitud WHERE IdSolicitud = '$idsolicitud'";
			$res =  @mysql_query($cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}

			return $res; 	
		
		}
		
		
		function consultarCentroCosto($centrocosto) {
			$cnx= conectar_postgres();
			$cons = "SELECT codigo  FROM Central.CentrosCosto WHERE centrocostos = '$centrocosto'";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$res =  @pg_query($cnx, $consUTF8);	
						if (!$res) {
							$fp = fopen("ReporteSolicitudConsumo.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);			
					
						}
								
			}
				
				
			
			
			if (pg_num_rows($res) == 0){
				return $centrocosto;
			}
			
			if (pg_num_rows($res) > 0){
				$res = pg_fetch_array($res);
				$resultado = $res['codigo'];
				return $resultado;
			}
			
				
			
		} 
		
		
		
		/* Inicia definicion de funciones */
		
		
		function funcion0001() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.solicitudConsumoMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0002() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE consumo.solicitudconsumoMigracion(  compania character varying(60) ,  idsolicitud integer,  almacenppal character varying(30) ,  fecha timestamp without time zone,  usuario character varying(50),  autoid integer ,  cantidad double precision,  cantaprobada double precision,  estado character varying(12) DEFAULT 'Solicitado'::character varying, aprobadox character varying(50),  fechaaprob timestamp without time zone,  despachadox character varying(50),  fechadespacho timestamp without time zone,  nosalida integer,  observaciones character varying(200),  cedula character varying(30),  cantdespachada double precision,  saldo double precision,  centrocostos character varying(100),  anio integer ,  usuarioreversion character varying(500), error text )WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0003() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("ReporteSolicitudConsumo.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.SolicitudesConsumo </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0004($compania, $idsolicitud,$almacenppal, $fecha,$usuario, $autoid, $cantidad, $estado, $observaciones, $centrocostos, $anio, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.solicitudConsumoMigracion(compania, idsolicitud,almacenppal, fecha,usuario, autoid, cantidad, estado, observaciones, centrocostos, anio, error) VALUES ('$compania', $idsolicitud,'$almacenppal', '$fecha', '$usuario',  $autoid, $cantidad, '$estado', '$observaciones', '$centrocostos', $anio, '$error')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteSolicitudConsumo.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	



		function  funcion0005($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.solicitudConsumo SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'), observaciones = replace( observaciones,'$cadenaBusqueda','$cadenaReemplazo'), centrocostos = replace( centrocostos,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}

		
		
		
		
		
		function funcion0009() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Consumo.solicitudconsumo";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
		
		
		
		
				
	
		function funcion0010() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT CONCAT(Fecha,' ', Hora) AS fechaComp, YEAR(Fecha) AS anio, Solicitudes.* FROM  Suministros.Solicitudes";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0011($compania, $idsolicitud,$almacenppal, $fecha, $usuario,  $autoid, $cantidad, $estado, $observaciones, $centrocostos, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.solicitudConsumo(compania, idsolicitud,almacenppal, fecha, usuario,  autoid, cantidad, estado, observaciones, centrocostos, anio) VALUES ('$compania', $idsolicitud,'$almacenppal', '$fecha', '$usuario', $autoid, $cantidad, '$estado', '$observaciones', '$centrocostos', $anio)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							
							funcion0004($compania, $idsolicitud,$almacenppal, $fecha, $usuario,  $autoid, $cantidad, $estado, $observaciones, $centrocostos, $anio, $error);
							
						}
				
				}

				
		}
		
		
		function  funcion0012(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0010();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["idsolicitud"][$posicion] = $fila["IdSolicitud"];
					$matriz["fecha"][$posicion] = $fila["fechaComp"];
					$matriz["usuario"][$posicion] = $fila["Nombre"];
					$matriz["estado"][$posicion] = $fila["Estado"];
					$matriz["observaciones"][$posicion] = $fila["Observaciones"];
					$matriz["centrocostos"][$posicion] = $fila["CentroCost"];
					$matriz["anio"][$posicion] = $fila["anio"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0013()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
				for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					

					$compania= $_SESSION["compania"];
						if(!$compania){
							$compania= "CLINICA SAN JUAN DE DIOS";
						}
					$idsolicitud = $matriz["idsolicitud"][$pos] ;	
					$almacenppal = "SUMINISTROS";
					$fecha= $matriz["fecha"][$pos] ;					
					$estado= $matriz["estado"][$pos] ;
					$estado = eliminarCaracteresEspeciales($estado);
					$observaciones= $matriz["observaciones"][$pos] ;
					$observaciones = eliminarCaracteresEspeciales($observaciones);
					$centrocostos= $matriz["centrocostos"][$pos] ;
					$centrocostos = eliminarCaracteresEspeciales($centrocostos);
					$centrocostos = consultarCentroCosto($centrocostos);
					
					$anio= $matriz["anio"][$pos] ;
					
					
					
					
					
					$resultado = consProductosSolicitud($idsolicitud);
						if (mysql_num_rows($resultado) > 0){
							while ($fila = mysql_fetch_array($resultado))	{
								
								$autoid = $fila["Codigo"];
								$cantidad =  $fila["Cantidad"];
								$estado =  $fila["Estado"];
								funcion0011($compania, $idsolicitud,$almacenppal, $fecha, $usuario,  $autoid, $cantidad, $estado, $observaciones, $centrocostos, $anio);
							}
						}	
					
							
					}
			
			}
			
			
		
		function migrarSolicitudesConsumo() {
			
			eliminarTmpMovimiento(1);
			cambiarcampo();
			cambiarcampo1();

			
			// Normaliza codificacion Tabla Central.CentrosCosto
			reemplazarTextoTabla1(utf8_encode("Á"),'&Aacute;');			
			reemplazarTextoTabla1(utf8_encode("É"),'&Eacute;');
			reemplazarTextoTabla1(utf8_encode("Í"),'&Iacute;');
			reemplazarTextoTabla1(utf8_encode("Ó"),'&Oacute;');
			reemplazarTextoTabla1(utf8_encode("Ú"),'&Uacute;');
			reemplazarTextoTabla1(utf8_encode("Ñ"),'&Ntilde;');
			
			funcion0009(); // Elimina los registros de la tabla de destino
			funcion0001(); // Elimina la tabla de Migracion
			funcion0002(); // Crea la tabla de Migracion
			funcion0003(); // Crea el archivo de reporte de errores de la migracion
			funcion0010(); // Selecciona los registros MySQl (Origen)
			funcion0012(); // Llena una matriz con base en el resultado de la consulta MySQL
			funcion0013(); // Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
		
		    // Tabla Consumo.SolicitudConsumo
			funcion0005('&Aacute;', utf8_encode("Á"));			
			funcion0005('&Eacute;', utf8_encode("É"));
			funcion0005('&Iacute;', utf8_encode("Í"));
			funcion0005('&Oacute;', utf8_encode("Ó"));
			funcion0005('&Uacute;',utf8_encode("Ú"));
			funcion0005('&Ntilde;',utf8_encode("Ñ"));

			//Tabla Central.CentrosCosto
			reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;' , utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;' , utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;', utf8_encode("Ñ"));
			
		
			
	
		}
		
		
		if($_GET['tabla']="SolicitudConsumo") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla Consumo.SolicitudConsumo </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG061' class = 'link1'> Panel de Administracion </a> </span>";
			migrarSolicitudesConsumo();
			$nota = "Validar los Centros de Costo del esquema Central con respecto a los diferentes Centros de Costo de las Solicitudes";
			generarNota(1,$nota);
			
			$nota = "Modificar el tamaño del campo 'CentroCosto' de la tabla Consumo.SolicitudConsumo a Character Varyng(20) ";
			generarNota(2,$nota);
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Consumo.SolicitudConsumo</p> </div>";
		
					   
			
				
				$totalMySQL = contarRegistrosMySQL();
				$totalPostgresql =  contarRegistrosPostgresql();
				$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Consumo.SolicitudConsumoMigracion):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='reporteSolicitudConsumo.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p><br/>";
				
				echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Consumo.SolicitudConsumo </a> </span>";
				

		
		
		
		}	
		
		
		
		
		
	
	
	
	?>

	<html>	
		<head>
			<title> Migracion Contabilidad.SaldosConciliacion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		
		/* Inicia definicion de funciones */
		
			
		
			
			
			
		function  normalizarCodificacionSaldosConc($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.SaldosConciliacion SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), cuenta = replace( cuenta,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
				}

		}
		
				
	
		function llamarRegistrosMySQLSaldosConc() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.SaldosConciliacion";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function crearTablaMigracionSaldosConc() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS contabilidad.saldosconciliacionMigracion(  compania character varying(200) ,  cuenta character varying(255) ,  anio integer  ,  mes integer  ,  saldoextracto double precision,  error text)WITH (  OIDS=FALSE)";	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function insertarSaldosConcMigracion($compania, $cuenta, $anio, $mes , $saldoextracto, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.SaldosConciliacionMigracion (compania,  cuenta, anio, mes , saldoextracto, error) VALUES ('$compania', '$cuenta', $anio, $mes , $saldoextracto, '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("Errores/ReporteContabilidad.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		

		
		function insertarSaldosConc($compania, $cuenta, $anio, $mes , $saldoextracto) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.SaldosConciliacion (compania,  cuenta, anio, mes , saldoextracto) VALUES ('$compania', '$cuenta', $anio, $mes , $saldoextracto)"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error= pg_last_error();
							insertarSaldosConcMigracion($compania, $cuenta, $anio, $mes , $saldoextracto, $error);
							
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizSaldosConc(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLSaldosConc();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];
					$matriz["anio"][$posicion] = $fila["Anio"];
					$matriz["mes"][$posicion] = $fila["Mes"];
					$matriz["saldoextracto"][$posicion] = $fila["SaldoExtracto"];
																			
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizSaldosConc()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					
					
					$cuenta= $matriz["cuenta"][$pos] ;
					$cuenta = eliminarCaracteresEspeciales($cuenta);
					
					$anio= $matriz["anio"][$pos] ;
										
					$mes= $matriz["mes"][$pos] ;	
					
					$saldoextracto= $matriz["saldoextracto"][$pos] ;	
					
					$compania= $_SESSION["compania"];
					
					
					insertarSaldosConc($compania, $cuenta, $anio, $mes , $saldoextracto);
					
									
					}
			
			}
			
			function eliminarSaldosConc() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.SaldosConciliacion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			function eliminarSaldosConcMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.SaldosConciliacionMigracion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarSaldosConc($paso) {
		
			crearTablaMigracionSaldosConc();
			eliminarSaldosConcMigracion();
			eliminarSaldosConc();
			
			
			llamarRegistrosMySQLSaldosConc();
			llenarMatrizSaldosConc();
			recorrerMatrizSaldosConc();
			
			
			// Tabla Contabilidad.ConceptosPagoxcc			
			normalizarCodificacionSaldosConc('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionSaldosConc('&Eacute;', utf8_encode("É"));
			normalizarCodificacionSaldosConc('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionSaldosConc('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionSaldosConc('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionSaldosConc('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.SaldosConciliacion </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>

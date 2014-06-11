	<html>	
		<head>
			<title> Migracion Central.EntidadesBancarias </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		function funcion0042() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Central.entidadesbancariasMigracion";
			$res =  pg_query($cons);
			
		}
		
		function funcion0043() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE central.entidadesbancariasMigracion(  codigo character varying(20) ,  nombre character varying(200) , error text) WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		function funcion0044() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("Errores/ReporteEntidadesBancarias.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Central.EntidadesBancarias </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0045( $codigo, $nombre, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.departamentosMigracion (codigo, nombre, error ) VALUES ('".$codigo."','".$nombre."','".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("Errores/ReporteEntidadesBancarias.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	


		
		
		
		function  funcion0046($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.entidadesBancarias SET codigo = replace( codigo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nombre = replace( nombre,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function funcion0047() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Nomina");
			$cons = "SELECT *  FROM Nomina.Entidades";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0048($codigo, $nombre) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.entidadesbancarias (codigo, nombre ) VALUES ('".$codigo."','".$nombre."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							funcion0045($codigo, $nombre,  $error);
							
						}
				
				}

				
		}
		
		
		function  funcion0049(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = funcion0047();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["codigo"][$posicion] = $fila["Nit"];
					$matriz["nombre"][$posicion] = $fila["Nombre"];
					
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0050()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$codigo= 	 $matriz["codigo"][$pos] ;
					$codigo = eliminarCaracteresEspeciales($codigo);
					$nombre= 	 $matriz["nombre"][$pos] ;
					$nombre = eliminarCaracteresEspeciales($nombre);
									
					funcion0048($codigo, $nombre);
					
						
					}
			
	
			}
			
			function funcion0051() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Central.entidadesbancarias";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
		
		
		function migrarEntidadesBancarias($paso) {
		
			eliminarMunicipios();
			funcion0042();
			funcion0043();
			funcion0044();
			funcion0051();
			funcion0047();
			funcion0049();
			funcion0050();
			funcion0046('&Aacute;', utf8_encode("Á"));			
			funcion0046('&Eacute;', utf8_encode("É"));
			funcion0046('&Iacute;', utf8_encode("Í"));
			funcion0046('&Oacute;', utf8_encode("Ó"));
			funcion0046('&Uacute;',utf8_encode("Ú"));
			funcion0046('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Central.EntidadesBancarias </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>

	<html>	
		<head>
			<title> Migracion Facturacion.DetalleDetalleLiquidacion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');	
		
		
		/* Inicia definicion de funciones */			
	

		
		function eliminarDetalleLiquidacion() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Facturacion.DetalleLiquidacion";
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		function insertarDetalleLiquidacionMigracion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$noliquidacion,$generico,$presentacion,$forma,$almacenppal,$cum, $nofacturable,  $error ) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.DetalleLiquidacionMigracion(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,generico,presentacion,forma,almacenppal,cum, nofacturable,  error ) VALUES ('$compania','$usuario','$fechacrea','$grupo','$tipo','$codigo','$nombre','$cantidad','$vrunidad','$vrtotal',
			$noliquidacion,'$generico','$presentacion','$forma','$almacenppal','$cum' , '$nofacturable', '$error')";
			
			
			$cons = str_replace( "'NULL'","NULL",$cons  ); 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							// Se asigna esa ruta por que esta funcion se llama desde el archivo Facturacion/FacturasCredito
							$fp = fopen("../DetalleLiquidacion/ReporteDetalleLiquidacion.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
						
						}
				
				}

		}
		
		
		function insertarDetalleLiquidacion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$noliquidacion,$generico,$presentacion,$forma,$almacenppal,$cum, $nofacturable) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
				$cons = "INSERT INTO Facturacion.DetalleLiquidacion(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,generico,presentacion,forma,almacenppal,cum, nofacturable ) VALUES ('$compania','$usuario','$fechacrea','$grupo','$tipo','$codigo','$nombre','$cantidad','$vrunidad','$vrtotal',
			$noliquidacion,'$generico','$presentacion','$forma','$almacenppal','$cum' , '$nofacturable')";
			
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarDetalleLiquidacionMigracion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$noliquidacion,$generico,$presentacion,$forma,$almacenppal,$cum, $nofacturable,  $error );
							
						}
				
				}

		}
		
		
			function crearTablaMigracionDetalleLiquidacion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS facturacion.detalleliquidacionMigracion(  compania character varying(80) ,  usuario character varying(100),  fechacrea timestamp without time zone,  grupo character varying(100),  tipo character varying(50),  codigo character varying(30) ,  nombre text,  cantidad double precision,  vrunidad double precision,  vrtotal double precision,  noliquidacion integer ,  generico character varying(100),  presentacion character varying(140),  forma character varying(40),  almacenppal character varying(100),  fechainterpret timestamp without time zone,  finalidad character varying(5),  causaext character varying(5),  dxppal character varying(6),  dxrel1 character varying(6),  dxrel2 character varying(6),  dxrel3 character varying(6),  tipodxppal character varying(1),
  dxrel4 character varying(6),  ambito character varying(150),  formarealizacion character varying(1),  nofacturable integer,  autoid serial ,  phpcrea character varying(100),  usucreadet character varying(100),  fecharealizacionservicio date,  cum character varying(200),  rip character varying(200),  error text)WITH (  OIDS=FALSE);
";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";*/		
					
				}
			
		}
		
		
		function eliminarTablaMigracionDetalleLiquidacion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DROP TABLE Facturacion.DetalleLiquidacionMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			*/
					
				}
			
		}
		
		
		function crearArchivoDetalleLiquidacion() {
			// Se asigna esa ruta por que esta funcion se llama desde el archivo Facturacion/FacturasCredito
			$fp = fopen("../DetalleLiquidacion/ReporteDetalleLiquidacion.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Facturacion.DetalleLiquidacion </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		function migrarDetalleLiquidacion($paso) {	
			

			 eliminarDetalleLiquidacion();
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de  la tabla Facturacion.DetalleLiquidacion </p> ";
	
		}
		
		
		
		
		
		
		
		
		
	
	
	
	?>

	<html>	
		<head>
			<title> Migracion Suministros </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
	
	
	<?php
		
		session_start();
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Suministros.Codproductos";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.CodProductos WHERE almacenppal= 'SUMINISTROS'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.CodproductosMigracion1";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		
		/* Inicia definicion de funciones */
		
		
		function funcion0001() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.codproductosMigracion1";
			$res =  pg_query($cons);
			
		}
		
		function funcion0002() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE consumo.codproductosMigracion1(  compania character varying(60) ,  almacenppal character varying(30) ,  autoid integer ,  codigo1 character varying(12),  codigo2 character varying(30),  codigo3 character varying(12),  nombreprod1 character varying(100),  nombreprod2 character varying(100),  unidadmedida character varying(150),  presentacion character varying(140),  tipoproducto character varying(40),  grupo character varying(150),  bodega character varying(40),  estante character varying(10),  nivel character varying(10),  usuariocre character varying(50),  fechacre timestamp without time zone,  usuariomod character varying(50),  fechaultmod timestamp without time zone,  estado character varying(2),  max integer,  min integer,  vriva double precision,  actualizaventa double precision,  anio integer ,  clasificacion character varying(800),  cum character varying(30),  control character varying(10),  somatico character varying(10),  riesgo character varying(20),  reginvima character varying(200),  pos integer,  codsecretaria character varying(13),error text)WITH (  OIDS=FALSE)";	
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
			$fp = fopen("ReporteSuministros.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.CodProductos (Suministros) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function funcion0004( $bodega, $nombreprod1,$presentacion, $unidadmedida, $min, $max, $autoid, $codigo1, $grupo, $estante, $nivel, $estado, $compania, $almacenppal, $anio,$clasificacion, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.codproductosMigracion1 ( bodega, nombreprod1,presentacion, unidadmedida, min, max, autoid, codigo1, grupo, estante, nivel, estado, compania, almacenppal, anio,clasificacion, error ) VALUES ('".$bodega."','".$nombreprod1."','".$presentacion."','".$unidadmedida."',".$min.",".$max.",".$autoid.",'".$codigo1."','".$grupo."','".$estante."','".$nivel."','".$estado."','".$compania."','".$almacenppal."',".$anio.",'$clasificacion', '".$error."')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteSuministros.html", "a+");	
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
			$cons = "UPDATE Consumo.codproductos SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),nombreprod1 = replace( nombreprod1,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),presentacion = replace( presentacion,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),unidadmedida = replace( unidadmedida,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),codigo1 = replace( codigo1,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , grupo = replace( grupo,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , estante = replace( estante ,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),nivel = replace( nivel,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'),estado = replace( estado,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')
			,compania = replace( compania,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."') , almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		
		
		function  funcion0006($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.almacenesppales SET almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  funcion0007($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.bodegas SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  funcion0008($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposproducto SET tipoproducto = replace( tipoproducto,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function funcion0009() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Consumo.codproductos";
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
			$cons = "SELECT *  FROM Suministros.codproductos";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function funcion0011($bodega, $nombreprod1,$presentacion, $unidadmedida, $min, $max, $autoid, $codigo1, $grupo, $estante, $nivel, $estado, $compania, $almacenppal, $anio, $clasificacion) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.codproductos ( bodega, nombreprod1,presentacion, unidadmedida, min, max, autoid, codigo1, grupo, estante, nivel, estado, compania, almacenppal, anio, clasificacion ) VALUES ('".$bodega."','".$nombreprod1."','".$presentacion."','".$unidadmedida."',".$min.",".$max.",".$autoid.",'".$codigo1."','".$grupo."','".$estante."','".$nivel."','".$estado."','".$compania."','".$almacenppal."',".$anio.", '$clasificacion')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							
							funcion0004( $bodega, $nombreprod1,$presentacion, $unidadmedida, $min, $max, $autoid, $codigo1, $grupo, $estante, $nivel, $estado, $compania, $almacenppal, $anio, $clasificacion, $error);
							
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
					
					$matriz["bodega"][$posicion] = $fila["Bodega"];
					$matriz["nombre"][$posicion] = $fila["Nombre"];
					$matriz["presentacion"][$posicion] = $fila["Presentacion"];
					$matriz["unidadmedida"][$posicion] = $fila["UnidadMedida"];
					$matriz["min"][$posicion] = $fila["Minimo"];
					$matriz["max"][$posicion] = $fila["Maximo"];
					$matriz["codigo1"][$posicion] = $fila["Codigo"];
					$matriz["grupo"][$posicion] = $fila["Agrupacion"];
					$matriz["estante"][$posicion] = $fila["Estante"];
					$matriz["nivel"][$posicion] = $fila["Nivel"];
					$matriz["estado"][$posicion] = $fila["Estado"];
					$posicion++;				
				}
							
				
			}
			

			
			function funcion0013()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					
					$autoid= $pos +1;
					$compania= $_SESSION["compania"];
						if(!$compania){
							$compania= "CLINICA SAN JUAN DE DIOS";
						}
					$almacenppal = "SUMINISTROS";
					$anio = consultarAnio();
					$bodega= $matriz["bodega"][$pos] ;
					$bodega = eliminarCaracteresEspeciales($bodega);
					$nombreprod1= $matriz["nombre"][$pos] ;
					$nombreprod1 = eliminarCaracteresEspeciales($nombreprod1);
					$presentacion= $matriz["presentacion"][$pos] ;
					$presentacion = eliminarCaracteresEspeciales($presentacion);
					$unidadmedida= $matriz["unidadmedida"][$pos] ;
					$unidadmedida = eliminarCaracteresEspeciales($unidadmedida);
					$min= $matriz["min"][$pos] ;
					$min = eliminarCaracteresEspeciales($min);
					$max= $matriz["max"][$pos] ;
					$max = eliminarCaracteresEspeciales($max);
					$codigo1= $matriz["codigo1"][$pos] ;
					$codigo1 = eliminarCaracteresEspeciales($codigo1);
					$grupo= $matriz["grupo"][$pos] ;
					$grupo = eliminarCaracteresEspeciales($grupo);
					$estante= $matriz["estante"][$pos] ;
					$estante = eliminarCaracteresEspeciales($estante);
					$nivel= $matriz["nivel"][$pos] ;
					$nivel = eliminarCaracteresEspeciales($nivel);
					$estado= $matriz["estado"][$pos] ;
					$estado = eliminarCaracteresEspeciales($estado);
					$clasificacion = "POR VALIDAR";  // Se define como "POR VALIDAR" porque dicha informacion no se encuentra en la base de datos de Origen
					
					funcion0011($bodega, $nombreprod1,$presentacion, $unidadmedida, $min, $max, $autoid, $codigo1, $grupo, $estante, $nivel, $estado, $compania, $almacenppal, $anio, $clasificacion);
									
					}
			
			}
			
			
			function eliminarConstraint($nombreConstraint) {
				$cnx = 	conectar_postgres();
				$cons= 'ALTER TABLE Consumo.codproductos DROP CONSTRAINT "'.$nombreConstraint.'"';
				$res = @pg_query($cnx, $cons);
				
			
			}
			
			function agregarConstraint1() {
				$cnx = 	conectar_postgres();
				$cons= 	'ALTER TABLE consumo.codproductos ADD CONSTRAINT "FkConsCodProdxAlmac" FOREIGN KEY (almacenppal, compania) REFERENCES consumo.almacenesppales (almacenppal, compania) ON UPDATE CASCADE ON DELETE RESTRICT';
				$res = @pg_query($cnx, $cons);
				
			
			}
			
			
			function agregarConstraint2() {
			$cnx = 	conectar_postgres();
			$cons= 	'ALTER TABLE consumo.codproductos ADD CONSTRAINT "FkConsCodProdxBod" FOREIGN KEY (bodega, almacenppal, compania) REFERENCES consumo.bodegas (bodega, almacenppal, compania) ON UPDATE CASCADE ON DELETE RESTRICT';
			$res = @pg_query($cnx, $cons);
	
			}
			
			
			function agregarConstraint3() {
			$cnx = 	conectar_postgres();
			$cons= 	'ALTER TABLE consumo.codproductos ADD CONSTRAINT "PkConsCodProdxTiposProd" FOREIGN KEY (tipoproducto, almacenppal, compania) REFERENCES consumo.tiposproducto (tipoproducto, almacenppal, compania) ON UPDATE CASCADE ON DELETE RESTRICT';
			$res = @pg_query($cnx, $cons);
	
			}
			
			
			
			
			
		
		
		function migrarSuministros() {
		
			funcion0009();
			eliminarConstraint("FkConsCodProdxAlmac");
			eliminarConstraint("FkConsCodProdxBod");
			eliminarConstraint("PkConsCodProdxTiposProd");
			
			agregarConstraint1();
			agregarConstraint2();
			agregarConstraint3();
			
			
			// Tabla Consumo.almacenesppales
			funcion0006(utf8_encode("Á"),'&Aacute;');			
			funcion0006(utf8_encode("É"),'&Eacute;');
			funcion0006(utf8_encode("Í"),'&Iacute;');
			funcion0006(utf8_encode("Ó"),'&Oacute;');
			funcion0006(utf8_encode("Ú"),'&Uacute;');
			funcion0006(utf8_encode("Ñ"),'&Ntilde;');
			
			
			// Tabla Consumo.bodegas
			funcion0007(utf8_encode("Á"),'&Aacute;');			
			funcion0007(utf8_encode("É"),'&Eacute;');
			funcion0007(utf8_encode("Í"),'&Iacute;');
			funcion0007(utf8_encode("Ó"),'&Oacute;');
			funcion0007(utf8_encode("Ú"),'&Uacute;');
			funcion0007(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Consumo.TiposProducto
			funcion0008(utf8_encode("Á"),'&Aacute;');			
			funcion0008(utf8_encode("É"),'&Eacute;');
			funcion0008(utf8_encode("Í"),'&Iacute;');
			funcion0008(utf8_encode("Ó"),'&Oacute;');
			funcion0008(utf8_encode("Ú"),'&Uacute;');
			funcion0008(utf8_encode("Ñ"),'&Ntilde;');
			
			
			funcion0001();
			funcion0002();
			funcion0003();
			funcion0010();
			funcion0012();
			funcion0013();
			
			

			
			
			// Tabla Consumo.almacenesppales
			funcion0006('&Aacute;', utf8_encode("Á"));			
			funcion0006('&Eacute;', utf8_encode("É"));
			funcion0006('&Iacute;', utf8_encode("Í"));
			funcion0006('&Oacute;', utf8_encode("Ó"));
			funcion0006('&Uacute;',utf8_encode("Ú"));
			funcion0006('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.bodegas
			funcion0007('&Aacute;', utf8_encode("Á"));			
			funcion0007('&Eacute;', utf8_encode("É"));
			funcion0007('&Iacute;', utf8_encode("Í"));
			funcion0007('&Oacute;', utf8_encode("Ó"));
			funcion0007('&Uacute;',utf8_encode("Ú"));
			funcion0007('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.tiposproducto
			funcion0008('&Aacute;', utf8_encode("Á"));			
			funcion0008('&Eacute;', utf8_encode("É"));
			funcion0008('&Iacute;', utf8_encode("Í"));
			funcion0008('&Oacute;', utf8_encode("Ó"));
			funcion0008('&Uacute;',utf8_encode("Ú"));
			funcion0008('&Ntilde;',utf8_encode("Ñ"));
			
			
			
		    // Tabla Consumo.codproductos
			funcion0005('&Aacute;', utf8_encode("Á"));			
			funcion0005('&Eacute;', utf8_encode("É"));
			funcion0005('&Iacute;', utf8_encode("Í"));
			funcion0005('&Oacute;', utf8_encode("Ó"));
			funcion0005('&Uacute;',utf8_encode("Ú"));
			funcion0005('&Ntilde;',utf8_encode("Ñ"));
			
		
			
	
		}
		
		
		if($_GET['tabla']="Suministros") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla Consumo.CodProductos (Suministros) </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG006' class = 'link1'> Panel de Administracion </a> </span>";
			migrarSuministros();
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de Suministros</p> </div>";
		
					   
			
				
				$totalMySQL = contarRegistrosMySQL();
				$totalPostgresql =  contarRegistrosPostgresql();
				$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Consumo.CodProductosMigracion2):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='reporteSuministros.html' class = 'link1' target='_blank'> Ver Reporte de errores de la migracion </a> </p><br/>";
				
				echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Suministros </a> </span>";
				

		
		
		
		}	
		
		
		
		
		
	
	
	
	?>

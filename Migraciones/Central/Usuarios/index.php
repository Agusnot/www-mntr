	<html>
		<head>
			<title> Migracion Central.Usuarios </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="ISO-8859-1">
		</head>
	</html>	
	


<?php

	include_once '../../Conexiones/conexion.php';
	include_once('../../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
	
	
		
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
		
		
		
		
		
			
		
		function  reemplazarTextoTabla1($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.Usuarios SET usuario = replace( usuario,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), nombre = replace( nombre,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'"."), cedula = replace( cedula,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."'".")";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		function  reemplazarTextoTabla2($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.Usuariosxmodulos SET usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), modulo = replace( modulo,'$cadenaBusqueda','$cadenaReemplazo'), madre = replace( madre,'$cadenaBusqueda','$cadenaReemplazo'), compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo')";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
			
		
	
		function llamarRegistrosMySQL() {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.Usuarios ORDER BY Cedula ASC ";
			$res =  mysql_query($cons);
			return $res; 
		
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
			$cons = "CREATE TABLE central.usuariosMigracion(  usuario character varying(100) ,  nombre character varying(200),  cedula character varying(200),  clave character varying(200),  cambioclave date,  fechaultimoacceso date,  fechacaducidad date,  super integer , error text)WITH (  OIDS=FALSE)";
	 		
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			
		}
		
		
		
		function insertarRegistroMigracion($usuario, $nombre, $cedula, $clave, $error) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.UsuariosMigracion (usuario, nombre , cedula, clave, error ) VALUES ('".$usuario."','".$nombre."','".$cedula."','".$clave."','".$error."')"	;
					 
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
		
		
		
		
		
		function insertarRegistroPostgresql($usuario, $nombre, $cedula, $clave) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Central.Usuarios (usuario, nombre , cedula, clave ) VALUES ('".$usuario."','".$nombre."','".$cedula."','".$clave."')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			//echo "CONSULTA: ".$cons."<br>";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarRegistroMigracion($usuario, $nombre, $cedula, $clave, $error);
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
					
					$matriz["usuario"][$posicion] = $fila["Usuario"];					
					$matriz["clave"][$posicion] = $fila["Clave"];
					$matriz["cedula"][$posicion] = $fila["Cedula"];

					$posicion++;				
				}
							
				
			}
			

				
		
		
		
			
			function insertarTabla()  {
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					

					$usuario=	 $matriz["usuario"][$pos] ;
					$usuario= eliminarCaracteresEspeciales($usuario);
					$nombre = $usuario;
					$cedula=	 $matriz["cedula"][$pos] ;
					$cedula= eliminarCaracteresEspeciales($cedula);
					$clave=	 $matriz["clave"][$pos] ;
					
					
					insertarRegistroPostgresql($usuario, $nombre, $cedula, $clave);
					}

			}
			
			function eliminarUsuarios() {
				$cnx = 	conectar_postgres();
				$cons = "TRUNCATE Central.usuarios CASCADE";
				$res= pg_query($cnx, $cons);

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
				$cons= "COPY Central.UsuariosxModulos FROM '$ruta/Migraciones/Central/Usuarios/permisosAdmin.csv' WITH DELIMITER ';' CSV HEADER;";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
			
			}
		
			
			
			
		function actualizarClaveAdmin() {
		
		$cnx = conectar_postgres();
		$cons = "UPDATE Central.usuarios SET clave = '0192023a7bbd73250516f069df18b500' WHERE UPPER(usuario) = 'ADMIN'";
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
			
			
			cambiarAmbito();
			eliminarTablaMigracion();
			crearTablaMigracion();
			crearArchivo();
			eliminarUsuarios();
			normalizarAccesoxModulos();
			
			

						
			llenarMatriz();
			insertarTabla();

					   
			/* Tabla Central.Usuarios */
			reemplazarTextoTabla1('&Aacute;',utf8_encode("Á"));			
			reemplazarTextoTabla1('&Eacute;', utf8_encode("É"));
			reemplazarTextoTabla1('&Iacute;', utf8_encode("Í"));
			reemplazarTextoTabla1('&Oacute;' , utf8_encode("Ó"));
			reemplazarTextoTabla1('&Uacute;' , utf8_encode("Ú"));
			reemplazarTextoTabla1('&Ntilde;', utf8_encode("Ñ"));
			
			
			permisosAdministrador();
			reemplazarTextoTabla2('&AACUTE&', utf8_encode("Á"));			
			reemplazarTextoTabla2('&EACUTE&', utf8_encode("É"));
			reemplazarTextoTabla2('&IACUTE&', utf8_encode("Í"));
			reemplazarTextoTabla2('&OACUTE&', utf8_encode("Ó"));
			reemplazarTextoTabla2('&UACUTE&',utf8_encode("Ú"));
			reemplazarTextoTabla2('&NTILDE&',utf8_encode("Ñ"));
			actualizarUsuarioAdmin();
			actualizarClaveAdmin();
			
			
			
			
			
						
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
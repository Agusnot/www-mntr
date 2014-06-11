	<html>	
		<head>
			<title> Migracion Contabilidad.PlanCuentas </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');

		
		
		
		
		/* Inicia defincion de funciones */
		
			
		function  normalizarCodificacionPlanCuentas($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.EstructuraPUC SET detalle = replace( detalle,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
		function llamarRegistrosMySQLPlanCuentas() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT *  FROM Contabilidad.PlanCuentas";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function crearTablaMigracionPlanCuentas() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS contabilidad.plancuentasMigracion(  anio integer  ,  compania character varying(100) ,  cuenta character varying(20) ,  nombre text,  naturaleza character varying(20),  tipo character varying(20),  centrocostos character varying(10),  corriente character varying(10),  banco integer ,  diferido character varying(2),  afectacionpresup character varying(200),  tercero integer,  nombanco character varying(200),
  numcuenta character varying(200),  destinacion character varying(200),  ftefinanciacion character varying(200),  cod1001 character varying(20),  cod1002 character varying(20),  vigafectacionpptal character varying(20) ,  clasvigafectacionpptal character varying(40),  documentos integer,  error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function insertarPUCMigracion($anio, $compania, $cuenta, $nombre, $naturaleza, $tipo, $centrocostos, $corriente, $banco, $diferido, $tercero, $nombanco, $numcuenta, $destinacion, $ftefinanciacion, $cod1001, $cod1002, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.PlanCuentasMigracion (anio, compania, cuenta, nombre, naturaleza, tipo, centrocostos, corriente, banco, diferido, tercero, nombanco, numcuenta, destinacion, ftefinanciacion, cod1001, cod1002, error ) VALUES ($anio, '$compania', '$cuenta', '$nombre', '$naturaleza', '$tipo', '$centrocostos', '$corriente', $banco, '$diferido', $tercero, '$nombanco', '$numcuenta', '$destinacion', '$ftefinanciacion', '$cod1001', '$cod1002', '$error')"	;
			
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
		
		

		
		function insertarPUC($anio, $compania, $cuenta, $nombre, $naturaleza, $tipo, $centrocostos, $corriente, $banco, $diferido, $tercero, $nombanco, $numcuenta, $destinacion, $ftefinanciacion, $cod1001, $cod1002) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Contabilidad.PlanCuentas (anio, compania, cuenta, nombre, naturaleza, tipo, centrocostos, corriente, banco, diferido, tercero, nombanco, numcuenta, destinacion, ftefinanciacion, cod1001, cod1002) VALUES ($anio, '$compania', '$cuenta', '$nombre', '$naturaleza', '$tipo', '$centrocostos', '$corriente', $banco, '$diferido', $tercero, '$nombanco', '$numcuenta', '$destinacion', '$ftefinanciacion', '$cod1001', '$cod1002')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
						$error = pg_last_error();
							insertarPUCMigracion($anio, $compania, $cuenta, $nombre, $naturaleza, $tipo, $centrocostos, $corriente, $banco, $diferido, $tercero, $nombanco, $numcuenta, $destinacion, $ftefinanciacion, $cod1001, $cod1002, $error);	
							
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatrizPlanCuentas(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLPlanCuentas();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["anio"][$posicion] = $fila["Anio"];
					$matriz["cuenta"][$posicion] = $fila["Cuenta"];
					$matriz["nombre"][$posicion] = $fila["Nombre"];										
					$matriz["naturaleza"][$posicion] = $fila["Naturaleza"];										
					$matriz["tipo"][$posicion] = $fila["Tipo"];										
					$matriz["centrocostos"][$posicion] = $fila["CentroCostos"];	
					$matriz["corriente"][$posicion] = $fila["Corriente"];	
					$matriz["banco"][$posicion] = $fila["Banco"];	
					$matriz["diferido"][$posicion] = $fila["Diferido"];	
					$matriz["tercero"][$posicion] = $fila["Tercero"];																																														
					$matriz["nombanco"][$posicion] = $fila["NomBanco"];										
					$matriz["numcuenta"][$posicion] = $fila["NumCuenta"];										
					$matriz["destinacion"][$posicion] = $fila["Destinacion"];										
					$matriz["ftefinanciacion"][$posicion] = $fila["FteFinanciacion"];	
					$matriz["cod1001"][$posicion] = $fila["Cod1001"];
					$matriz["cod1002"][$posicion] = $fila["Cod1002"];																													
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatrizPlanCuentas()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$anio= $matriz["anio"][$pos] ;
					$compania= $_SESSION["compania"];
					$cuenta= $matriz["cuenta"][$pos] ;
					$cuenta = eliminarCaracteresEspeciales($cuenta);
					$nombre= $matriz["nombre"][$pos] ;
					$nombre = eliminarCaracteresEspeciales($nombre);
					$nombre = str_replace("?","&Ntilde;",$nombre);
					
					$naturaleza = $matriz["naturaleza"][$pos] ;
					$naturaleza = eliminarCaracteresEspeciales($naturaleza);
						if ($naturaleza == "CREDITO") {
							$naturaleza = "Credito";
						}
						elseif ($naturaleza == "DEBITO") {
							$naturaleza = "Debito";
						}
						
					$tipo= $matriz["tipo"][$pos] ;
					$tipo = eliminarCaracteresEspeciales($tipo);
						if ($tipo == "TITULO"){
							$tipo = "Titulo";
						}
						elseif ($tipo == "DETALLE"){
							$tipo = "Detalle";
						}
					
					$centrocostos= $matriz["centrocostos"][$pos] ;
					$centrocostos = eliminarCaracteresEspeciales($centrocostos);
						if ($centrocostos== "ON"){
							$centrocostos= "on";
						}
						elseif ($centrocostos== "OFF"){
							$centrocostos= "off";
						}
						
					$corriente= $matriz["corriente"][$pos] ;
					$corriente = eliminarCaracteresEspeciales($corriente);
						if ($corriente== "ON"){
							$corriente= "on";
						}
						elseif ($corriente== "OFF"){
							$corriente= "off";
						}
					
					$banco= $matriz["banco"][$pos] ;
					
					$diferido= $matriz["diferido"][$pos] ;
					$diferido = eliminarCaracteresEspeciales($diferido);
						if ($diferido== "NO"){
							$diferido= "no";
						}
						elseif ($diferido== "SI"){
							$diferido= "si";
						}
					
					$tercero= $matriz["tercero"][$pos] ;
					$nombanco= $matriz["nombanco"][$pos] ;
					$numcuenta= $matriz["numcuenta"][$pos] ;
					$numcuenta = eliminarCaracteresEspeciales($numcuenta);
					$destinacion= $matriz["destinacion"][$pos] ;
					$destinacion = eliminarCaracteresEspeciales($destinacion);
					$ftefinanciacion= $matriz["ftefinanciacion"][$pos] ;					
					$ftefinanciacion = eliminarCaracteresEspeciales($ftefinanciacion);
						if ($ftefinanciacion=="") {
							$ftefinanciacion = 'NULL';
						}
					
					$cod1001= $matriz["cod1001"][$pos] ;
					$cod1001 = eliminarCaracteresEspeciales($cod1001);
					$cod1002= $matriz["cod1002"][$pos] ;
					$cod1002 = eliminarCaracteresEspeciales($cod1002);
					
					insertarPUC($anio, $compania, $cuenta, $nombre, $naturaleza, $tipo, $centrocostos, $corriente, $banco, $diferido, $tercero, $nombanco, $numcuenta, $destinacion, $ftefinanciacion, $cod1001, $cod1002);
					
									
					}
			
			}
			
			function eliminarPlanCuentas() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.planCuentas";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			function eliminarPlanCuentasMigracion() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Contabilidad.planCuentasMigracion";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarPlanCuentas($paso) {
			
			crearTablaMigracionPlanCuentas();
			eliminarPlanCuentasMigracion();
			eliminarPlanCuentas();
			llamarRegistrosMySQLPlanCuentas();
			llenarMatrizPlanCuentas();
			recorrerMatrizPlanCuentas();
			normalizarCodificacionPlanCuentas('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionPlanCuentas('&Eacute;', utf8_encode("É"));
			normalizarCodificacionPlanCuentas('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionPlanCuentas('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionPlanCuentas('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionPlanCuentas('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Contabilidad.PlanCuentas </p> ";
	
		}
		
		
		
		
		
	
	
	
	?>

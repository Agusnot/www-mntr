	<html>	
		<head>
			<title> Migracion Consumo.Grupos </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		/* Inicia definicion de funciones */ 
		
		function eliminarGrupos() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Consumo.grupos";
					 
			
			$res = @pg_query($cnx, $cons);
				if (!$res) {

							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  

				}
		}
		
		
		function consultarGrupos() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Suministros");
			$cons = "SELECT DISTINCT(Agrupacion) FROM Suministros.Movimientos";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function  llenarMatrizGrupos(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = consultarGrupos();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["grupo"][$posicion] = $fila["Agrupacion"];
					$posicion++;	
				}				
		}
		
		
		function insertarGrupo($grupo, $almacenppal, $compania, $anio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.grupos (grupo, almacenppal, compania, anio) VALUES ('$grupo', '$almacenppal', '$compania', $anio)";
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							
							
						}
				
				}

				
		}
		
		function insertarGruposFarmacia(){
		
			$almacenppal = "FARMACIA";
			$compania = $_SESSION["compania"];
			
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2009);
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2010);
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2011);
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2012);
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2013);
			insertarGrupo("MEDICAMENTOS", $almacenppal, $compania, 2014);
			
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2009);
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2010);
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2011);
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2012);
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2013);
			insertarGrupo("MEDICAMENTOS NO POS", $almacenppal, $compania, 2014);
			
			
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2009);
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2010);
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2011);
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2012);
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2013);
			insertarGrupo("MATERIAL QUIRURGICO", $almacenppal, $compania, 2014);
		
		
		}
		
		
		function recorrerMatrizGrupos()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
						$grupo= $matriz["grupo"][$pos] ;
						$grupo = eliminarCaracteresEspeciales($grupo);
						$almacenppal= "SUMINISTROS";
						$compania = $_SESSION["compania"];
							if(!$compania) {
								$compania = "CLINICA SAN JUAN DE DIOS";
							}
					
							insertarGrupo($grupo, $almacenppal, $compania, 2007);
							insertarGrupo($grupo, $almacenppal, $compania, 2008);
							insertarGrupo($grupo, $almacenppal, $compania, 2009);
							insertarGrupo($grupo, $almacenppal, $compania, 2010);
							insertarGrupo($grupo, $almacenppal, $compania, 2011);
							insertarGrupo($grupo, $almacenppal, $compania, 2012);
							insertarGrupo($grupo, $almacenppal, $compania, 2013);							
																				
					}
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2007);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2008);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2009);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2010);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2011);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2012);
					insertarGrupo("POR VALIDAR", $almacenppal, $compania, 2013);
			
			}
			
			
		function  normalizarCodificacionGrupos($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.grupos SET grupo = replace( grupo,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'),compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo') ";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		
		
		
		
		
		function migrarGrupos($paso){
			eliminarGrupos();
			consultarGrupos();
			llenarMatrizGrupos();
			recorrerMatrizGrupos();
			insertarGruposFarmacia();
			normalizarCodificacionGrupos('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacionGrupos('&Eacute;', utf8_encode("É"));
			normalizarCodificacionGrupos('&Iacute;', utf8_encode("Í"));
			normalizarCodificacionGrupos('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacionGrupos('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacionGrupos('&Ntilde;',utf8_encode("Ñ"));
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se ha migrado la tabla Consumo.Grupos </p> ";		
		
		}
		
		
		/* Termina definicion de funciones */	
		
		
		

		
		
		
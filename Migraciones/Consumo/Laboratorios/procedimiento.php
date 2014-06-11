	<html>	
		<head>
			<title> Migracion Consumo.Laboratorios </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */
		
		function  normalizarCodificacionLaboratorios($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.Laboratorios  SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), laboratorio = replace( laboratorio,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
				
	
	
		
		function eliminarLaboratorios()		{
			$cnx = conectar_postgres();
			$cons= "DELETE FROM  Consumo.Laboratorios";
			$res = @pg_query($cnx, $cons);
							
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		function llamarRegistrosMySQLLaboratorios() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT DISTINCT(proveedor)  FROM Salud.EntradasFarmacia";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		function insertarLaboratorios($compania, $laboratorio) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.Laboratorios (compania, laboratorio ) VALUES ('$compania', '$laboratorio')"	;
					 
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
		
		
		function  llenarMatrizLaboratorios(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQLLaboratorios();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["laboratorio"][$posicion] = $fila["proveedor"];
					$posicion++;				
				}
							
				
			}
			
			
			function recorrerMatrizLaboratorios()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

						$laboratorio= $matriz["laboratorio"][$pos] ;
						$laboratorio = eliminarCaracteresEspeciales($laboratorio);					
						$compania= $_SESSION["compania"];
						
						insertarLaboratorios($compania, $laboratorio);
					}
					
					insertarLaboratorios($compania,"POR VALIDAR");
			}	
		
		
		
		function migrarLaboratorios(){
			eliminarLaboratorios()	;
			llenarMatrizLaboratorios();
			recorrerMatrizLaboratorios();
		}
		
		
		
		
		
		/* Termina definicion de funciones */
		
		
		
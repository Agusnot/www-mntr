	<html>	
		<head>
			<title> Migracion Consumo.CumsxProducto </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		
		
		
		/* Inicia definicion de funciones */  
		
		
		function eliminarCumsxProducto() {
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Consumo.cumsxproducto";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function insertarCumsxProducto($compania, $almacenppal,$autoid, $laboratorio,  $cum, $presentacion, $reginvima){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.cumsxproducto( compania, almacenppal,autoid, laboratorio,  cum, presentacion, reginvima) VALUES ('$compania', '$almacenppal','$autoid', '$laboratorio', '$cum', '$presentacion', '$reginvima')"	;
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("../Farmacia/ReporteFarmacia.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		function  normalizarCUMSxProducto($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.cumsxproducto SET compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), almacenppal = replace( almacenppal,'$cadenaBusqueda','$cadenaReemplazo'),  presentacion = replace( presentacion,'$cadenaBusqueda','$cadenaReemplazo') ";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}



		
		
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		
		
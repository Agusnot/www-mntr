	<html>	
		<head>
			<title> Migracion Consumo.Lotes </title>
			
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		
		
		
		
		// Inicia defincion de funciones 
		
		
		
		
		function eliminarLotes(){
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Lotes";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		
		function insertarLotes($compania, $almacenppal, $autoid, $cantidad, $lote, $vence, $tipo, $cerrado, $numero, $laboratorio, $reginvima, $presentacion, $salidas, $temperatura) {
			$cnx = conectar_postgres();

			$cons= "INSERT INTO Consumo.Lotes(compania, almacenppal, autoid, cantidad, lote, vence, tipo, cerrado, numero, laboratorio, reginvima, presentacion, salidas, temperatura) VALUES ('$compania', '$almacenppal', '$autoid', '$cantidad', '$lote', '$vence', '$tipo', '$cerrado', '$numero', '$laboratorio', '$reginvima', '$presentacion', $salidas, '$temperatura')  ";
			$res = @pg_query($cnx, $cons);
				
				
				if (!$res) {
					$fp = fopen("../Lotes/ErroresLotes.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
				}
		
		}
		
		function cambiarCampoNumeroLotes() {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE Consumo.Lotes  ALTER COLUMN numero TYPE character varying(30)";
			$res =  @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
			
		}
		
		function crearArchivoErroresLotes() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("../Lotes/ErroresLotes.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.Lotes</title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		function definirListadoMedicamentos(){
			$cnx = conectar_postgres();
			$cons= "SELECT DISTINCT(Autoid) FROM Consumo.CodProductos WHERE Almacenppal = 'FARMACIA' ORDER BY AutoId ASC";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
			return $res;
		}
		
		
		function ultimoLote($autoid){
			$cnx = conectar_postgres();
			$cons= "SELECT * FROM  Consumo.Lotes  WHERE autoid = '$autoid' AND cerrado = '1' ORDER BY CAST(Numero AS int) DESC LIMIT 1";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
			$fila = @pg_fetch_array($res);
			return $fila;
			
		}
		
		
		function actualizarUltimoLote($autoid,$cerrado,$salidas, $numero){
			$cnx = conectar_postgres();
			$anioActual = consultarAnio();
			$cons= "UPDATE Consumo.Lotes SET cerrado = '$cerrado', salidas = '$salidas',Tipo = 'Saldo Inicial' WHERE autoid = '$autoid' AND cerrado = '1' AND numero = '$numero' ";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		}
		
		function reiniciarLotes(){
			$cnx = conectar_postgres();
			$cons= "UPDATE Consumo.Lotes SET cerrado = '1', salidas = cantidad ";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		}
		
		function configurarLotes(){
			
			reiniciarLotes();
			$listadoMedicamentos = definirListadoMedicamentos();
			$anioActual = consultarAnio();
			while ($medicamento = pg_fetch_array($listadoMedicamentos)){
				$autoid = $medicamento["autoid"];
				$existencias = definirExistenciasFarmacia($autoid, $anioActual);
				
				
				$saldo = $existencias;
							
				if($existencias > 0){
					while ($saldo > 0){
						$filaLote = ultimoLote($autoid) ;
						$cantidadLote = $filaLote["cantidad"];
						$numeroLote = $filaLote["numero"];
							if (!isset($cantidadLote)){
								echo "Verificar los lotes del producto: ".$autoid."<br><br>";
								$saldo = 0;
							}
							
							if($saldo >= $cantidadLote){							 
								actualizarUltimoLote($autoid,0,0,$numeroLote);
								
							}
							
							if($saldo < $cantidadLote){
								$salidasLote = $cantidadLote - $saldo;
								actualizarUltimoLote($autoid,0,$salidasLote,$numeroLote);
								
							}						
						$saldo = $saldo - $cantidadLote;
							
					}
				}	
			}
		
		}
		
		
		// Termina definicion de funciones


		
		
		
		
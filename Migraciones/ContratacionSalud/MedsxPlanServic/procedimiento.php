	<html>	
		<head>
			<title> Migracion ContratacionSalud.MedsxPlanServic </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');
		
		
		
		
		
		/* Inicia defincion de funciones */
		
		function eliminarMedsxPlanServic() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM ContratacionSalud.MedsxPlanServic";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
					}
			
		}
		
		function llamarMedicamentos(){
			$cnx= conectar_postgres();
			$cons= "SELECT *  FROM Consumo.CodProductos WHERE UPPER(Almacenppal) = 'FARMACIA'";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
				}
			return $res;		
		}
		
		function insertarMedicamento($autoidplan, $codigomedicamento,$compania, $reqvobo, $facturable, $minimos, $maximos, $almacenppal){
			$cnx = conectar_postgres();
			$cons= "INSERT INTO ContratacionSalud.MedsxPlanServic(autoid, codigo, compania, reqvobo, facturable, minimos, maximos, almacenppal) VALUES ($autoidplan, '$codigomedicamento', '$compania', $reqvobo, $facturable, $minimos, $maximos, '$almacenppal')";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
								
				}
			
		
		}
		
		
		
		function insertarMedsxPlanServic(){
			//Definiciones Generales
			$autoidplan = 111;// Se define este numero porque el CSV de los Contratos esta relacionado con ese numero
			$compania = $_SESSION["compania"];
			$reqvobo = 0;
			$facturable = 1;
			$minimos = 1;
			$maximos = 10;
			$almacenppal = "FARMACIA";
			$resMed= llamarMedicamentos();
			
				while ($filaMed = pg_fetch_array($resMed)){
					$codigomedicamento = $filaMed['autoid'];
					insertarMedicamento($autoidplan, $codigomedicamento ,$compania, $reqvobo, $facturable, $minimos, $maximos, $almacenppal);	
				}
						
		}
		
		function migrarMedsxPlanServic($paso) {
			eliminarMedsxPlanServic();
			insertarMedsxPlanServic();
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han actualizado los registros de la tabla ContratacionSalud.MedsxPlanServic </p> ";
		
		
		}
		
		
		
		/* Termina definicion de funciones */
		
		
			
		
			
			
			
			
			
			
			
		
		
		
		
		
		
		
		
	
	
	
	?>

	<html>	
		<head>
			<title> Migracion Consumo.Lotes </title>
			
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		include_once('procedimiento.php');
		
		
		
		
		
		if($_GET['accion']="ConfigurarLotes") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG011' class = 'link1'> Panel de Administracion </a> </span>";
				
				configurarLotes();
				
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la configuracion de los lotes</p> </div>";
			
			echo "</fieldset>";
			
		}
		
		
		
		
		


		
		
		
		
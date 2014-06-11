	<html>	
		<head>
			<title> Migracion Esquema Consumo</title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		
		include('../Conexiones/conexion.php');
		include('../General/funciones/funciones.php');
		include('Bodegas/procedimiento.php');
		include ('Clasificaciones/procedimiento.php');
		include ('Codsecretaria/procedimiento.php');
		include ('Inventarios/procedimiento.php');
		include ('Laboratorios/procedimiento.php');
		//include ('Lotes/procedimiento.php');
		include ('TiposComprobante/procedimiento.php');
		include ('Comprobantes/procedimiento.php');
		include ('Grupos/procedimiento.php');
		include ('PresentacionProductos/procedimiento.php');
		include ('PresentacionLabs/procedimiento.php');
		include ('Riesgos/procedimiento.php');
		include ('TipoCausalida/procedimiento.php');
		include ('TmpMovimiento/procedimiento.php');
		include ('TmpSolicitudConsumo/procedimiento.php');
		include ('UnidadMedida/procedimiento.php');
		include ('Usuariosxalmacen/procedimiento.php');
		include ('Usuariosxcc/procedimiento.php');
		include ('Autorizausuxsolicitudes/procedimiento.php');
		include ('TarifariosVenta/procedimiento.php');
		
		
		
		
		
		/* Inicia definicion de funciones */
		
		function eliminarCompania($paso) {
		
			$cnx = conectar_postgres();
			$cons = "TRUNCATE central.compania CASCADE";
				if ($_GET['verConsultas'] == 'true') {
					echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
					echo $cons;
				}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han eliminado los registros de la tabla Central.Compania </p> ";
			
			
		}
		
		
		function crearCompania($paso) {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO central.compania (nombre, nit, direccion, estilo, telefonos, codsgsss, departamento, municipio, dependencia, pac, cambioclave) VALUES('CLINICA SAN JUAN DE DIOS','890801495-9','CALLE 72 # 28- 20','Estandar','(6)8870448','1700100593','17','001','Hermanos Hospitalarios de San Juan de Dios','AUTOMATICO','90');";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span> Se ha creado la Compa&ntilde;ia Clinica San Juan de Dios </p> ";
			
		}
		
		function generarNota($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		
		
		function crearUnidosis($paso) {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO consumo.tiposproducto VALUES ('UNIDOSIS','FARMACIA','CLINICA SAN JUAN DE DIOS')";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso 6: </span>  Se ha creado 'Unidosis' en los tipos de producto  </p> ";
			
		}
		
		function cambiarCampoCodigo1($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE consumo.codproductos  ALTER COLUMN codigo1 TYPE character varying(30)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo Consumo.codigo1 a character varying(30)  </p> ";
			
		}
		
		function cambiarCampoCodigo2($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE consumo.codproductos  ALTER COLUMN codigo2 TYPE character varying(30)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo Consumo.codigo2 a character varying(30)  </p> ";
			
		}
		
		
		function cambiarCampoCodigo3($paso) {
		
			$cnx = conectar_postgres();
			$cons = "ALTER TABLE consumo.codproductos  ALTER COLUMN codigo3 TYPE character varying(30)";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso 9 : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha modificado el campo Consumo.codigo3 a character varying(30)  </p> ";
			
		}
		

		function insertarAnios($paso) {
			$compania = 'CLINICA SAN JUAN DE DIOS';
			$anioInicial =  1900;
			$fechaActual = getdate();
			$anioActual = $fechaActual['year'];
			$anioFinal= $anioActual + 1;
			$cnx = conectar_postgres();
				if ($_GET['verConsultas'] == 'true') {
				echo "<p class='subtitulo1'>Consultas Paso $paso : </p>";
				}
				
				for($i= $anioInicial; $i <= $anioFinal; $i++  ) {
					$cons= "INSERT INTO Central.anios(anio, compania) VALUES (".$i.",'".$compania."')";
						if ($_GET['verConsultas'] == 'true') {							
							echo "<p>$cons </p>";
						}
					pg_query($cons);		
				
				}

		}
		
		
		
		
		
		
		function crearAlmacenFarmacia($paso) {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO consumo.almacenesppales VALUES('FARMACIA','CLINICA SAN JUAN DE DIOS','1','1','FormulasControl.php','1','')";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha insertado el almacen 'FARMACIA'  </p> ";
			
		}
		
		
		function crearAlmacenSuministros($paso) {
		
			$cnx = conectar_postgres();
			$cons = "INSERT INTO consumo.almacenesppales(almacenppal, compania) VALUES('SUMINISTROS','CLINICA SAN JUAN DE DIOS')";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha insertado el almacen 'SUMINISTROS'  </p> ";
			
		}
		
		
		
		
		function crearBodegaFarmacia($paso) {
		
			$cnx = conectar_postgres();
			$cons = "INSERT  INTO consumo.bodegas VALUES ('PRINCIPAL','FARMACIA','CLINICA SAN JUAN DE DIOS')";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se ha insertado la  bodega 'FARMACIA'  </p> ";
			
		}

		function permisosAdmin($paso) {
			$cnx = conectar_postgres();
			$cons = "INSERT INTO  Consumo.UsuariosxAlmacenes (Usuario,AlmacenPpal,Compania) VALUES ('Administrador OH Mentor','FARMACIA','CLINICA SAN JUAN DE DIOS')";
				if ($_GET['verConsultas'] == 'true') {
						echo "<p class='subtitulo1'>Consulta Paso $paso : </p>";
						echo $cons;
					}
			$res =  pg_query($cons);
			echo "<p class='mensajeEjecucion'><span class = 'subtitulo1'>Paso $paso: </span>  Se le ha asignado permisos al usuario 'Admin'  para el almacen 'FARMACIA' </p> ";
			
		}
		
		function eliminarLotesConsumo(){
			$cnx = conectar_postgres();
			$cons= "DELETE FROM Consumo.Lotes";
			$res = @pg_query($cnx, $cons);
				
				if (!$res) {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 	
				}
		
		}
		
		/* Termina definicion de funciones */
		
		
		
		/* Inicia la ejecucion de funciones */
		
		if($_GET['esquema']=="Consumo") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Esquema Consumo </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../index.php?migracion=MIG002' class = 'link1'> Panel de Administracion </a> </span>";

			
			eliminarCompania(1);
			crearCompania(2);
			
			//permisosAdministrador(3);
			crearAlmacenFarmacia(4);
			crearAlmacenSuministros(5);
			migrarBodegas(6);
			crearBodegaFarmacia(7);
			crearUnidosis(8);
			cambiarCampoCodigo1(9);
			cambiarCampoCodigo2(10);
			cambiarCampoCodigo3(11);
			//permisosAdmin(10);
   	 		insertarAnios(0);
			migrarClasificaciones(12);
			eliminarCodsecretaria(13);
			eliminarInventarios(14);
			migrarLaboratorios();
			$nota ="Validar los laboratorios con la Sra Gloria Isabel Acevedo";
			generarNota(1,$nota) ;
			eliminarLotesConsumo();
			//$nota ="Validar los lotes con la Sra Gloria Isabel Acevedo";
			generarNota(2,$nota) ;
			actualizarTiposComprobante(17);
			actualizarComprobantes(18);
			$nota ="Validar la configuracion de los Comprobantes de Suministros";
			generarNota(3,$nota) ;
			migrarGrupos(19);
			migrarPresentacionProductos(20);
			migrarPresentacionLabs(21);
			migrarRiesgos(22);
			eliminarTiposCausaSalida(23);
			eliminarTmpMovimiento(24);
			eliminarTmpSolicitudConsumo(25);
			migrarUnidadMedida(26);
			eliminarUsuariosxAlmacen(27);
			eliminarUsuariosxcc(28);
			eliminarAutorizaUsu(29);
			MigrarTarifariosVenta($_SESSION["compania"],"FARMACIA");
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion del esquema Consumo </p> </div>";
			
			
			echo "<br>";
			echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Esquema Consumo </a> </span>";
			
			
			echo "</fieldset>";
			
		}
			
			
		
		/* Termina  la ejecucion de funciones */
		
		
		
		
	
	
	
	?>

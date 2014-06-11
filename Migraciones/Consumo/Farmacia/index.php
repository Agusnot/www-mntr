	<html>	
		<head>
			<title> Migracion Farmacia </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
	
		session_start();
		include_once '../../Conexiones/conexion.php';
		include_once('../../General/funciones/funciones.php');
		include_once('../TarifariosVenta/procedimiento.php');
		include_once('../CumsxProducto/procedimiento.php');
		include_once('../TarifasxProducto/procedimiento.php');
		include_once('../SaldosInicialesxAnio/procedimiento.php');
		include_once('../Lotes/procedimiento.php');
		include_once('CodMedicamentos2010.php');
		include_once('CodMedicamentos2011.php');
		
		
		
		
		
		
		
		/* Inicia definicion de funciones */
		
		function contarRegistrosMySQL() {
			$cnx = conectar_mysql("Contabilidad");
			$cons = "SELECT COUNT(*) AS conteoMySQL FROM Salud.Codmedicamentos";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila['conteoMySQL'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresql() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.CodProductos WHERE almacenppal= 'FARMACIA'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function contarRegistrosPostgresqlErrores() {
			$cnx= conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteo FROM Consumo.CodproductosMigracion2";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['conteo'];
			return $res; 	
		
		}
		
		
		function eliminarRegistrosFarmacia() {
			$cnx= conectar_postgres();
			$cons = "DELETE FROM Consumo.CodProductos WHERE almacenppal= 'FARMACIA'";
			$res =  pg_query($cnx, $cons);
			
		
		}
		
		
		
		
		function definirLote($codigo) {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT * FROM Salud.EntradasFarmacia WHERE Codigo = '$codigo' ORDER BY Fecha DESC LIMIT 1";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
					
			return $fila; 	
		
		}
		
		function actualizarMaximoMedicamento($autoid,$maximo){
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.CodProductos SET max = '$maximo' WHERE almacenppal = 'FARMACIA' AND autoid = '$autoid'";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					
				}
		
		}
		
		
		function actualizarMinimoMedicamento($autoid,$minimo){
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.CodProductos SET min = '$minimo' WHERE almacenppal = 'FARMACIA' AND autoid = '$autoid'";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					
				}
		
		}
		
		
	
		
		function definirEntradas($codigo, $anio) {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT SUM(Cantidad) AS entradas FROM  Salud.EntradasFarmacia WHERE  Codigo = '$codigo' AND YEAR(fecha) = '$anio'";
			
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$entradas = $fila["entradas"];
			
			return $entradas; 	
		
		}
		
		function definirSalidas($codigo, $anio) {
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT SUM(Cantidad) AS salidas FROM Salud.salidamedicamentos  WHERE  Codigo = '$codigo' AND YEAR(fechaDespacho) = '$anio' ";
			$res =  mysql_query($cons);
			$salidas = mysql_fetch_array($res);
			$salidas = $fila["salidas"];
			
			return $salidas; 	
		
		}
		
	
		
		function eliminarTablaMigracion() {
			// Elimina la tabla de migracion
			$cnx= conectar_postgres();
			$cons = "DROP TABLE  IF EXISTS Consumo.codproductosMigracion2";
			$res =  pg_query($cons);
			
		}
		
		
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE consumo.codproductosMigracion2(  compania character varying(60) ,  almacenppal character varying(30) ,  autoid integer ,  codigo1 character varying(12),  codigo2 character varying(30),  codigo3 character varying(12),  nombreprod1 character varying(100),  nombreprod2 character varying(100),  unidadmedida character varying(150),  presentacion character varying(140),  tipoproducto character varying(40),  grupo character varying(150),  bodega character varying(40),  estante character varying(10),  nivel character varying(10),  usuariocre character varying(50),  fechacre timestamp without time zone,  usuariomod character varying(50),  fechaultmod timestamp without time zone,  estado character varying(2),  max integer,  min integer,  vriva double precision,  actualizaventa double precision,  anio integer ,  clasificacion character varying(800),  cum character varying(30),  control character varying(10),  somatico character varying(10),  riesgo character varying(20),  reginvima character varying(200),  pos integer,  codsecretaria character varying(13),error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
		
		
		function actualizarNoPos($autoid){
			actualizarMedNoPos($autoid);
			actualizarGrupoLote($autoid);
			actualizarGrupoMovimiento($autoid);
		
		}
		
		
		function actualizarMedNoPos($autoid) {
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Consumo.CodProductos SET pos = '0', grupo = 'MEDICAMENTOS NO POS' WHERE UPPER(almacenppal) = 'FARMACIA' AND autoid = '$autoid'";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
		}
		
		function actualizarGrupoLote($autoid){
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Consumo.Lotes SET tipo = 'MEDICAMENTOS NO POS' WHERE UPPER(almacenppal) = 'FARMACIA' AND autoid = '$autoid'";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			
		}
		
		
		function actualizarGrupoMovimiento($autoid){
			$cnx = 	conectar_postgres();
			$cons = "UPDATE Consumo.Movimiento SET grupo = 'MEDICAMENTOS NO POS' WHERE UPPER(almacenppal) = 'FARMACIA' AND autoid = '$autoid'";
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			
		}
		
		function crearArchivoErrores() {
		// Crea un archivo HTML donde se documentaran los registros que no se insertaron en la tabla de migraciones
			$fp = fopen("ReporteFarmacia.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Consumo.CodProductos (Farmacia) </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		function insertarRegistroMigracion($compania, $almacenppal,$autoid, $codigo1, $nombreprod1, $unidadmedida, $presentacion, $tipoproducto, $grupo, $bodega, $estante, $nivel, $usuariocre, $fechacre, $estado, $maximo, $minimo, $anio, $cum, $pos, $noregsan, $clasificacion, $error){
		// Inserta en la tabla de migraciones para documentar los errores
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.codproductosMigracion2( compania, almacenppal,autoid, codigo1, nombreprod1, unidadmedida, presentacion, tipoproducto, grupo, bodega, estante, nivel, usuariocre, fechacre, estado, maximo, minimo, anio, cum, pos, reginvima, clasificacion,  error) VALUES ('$compania','$almacenppal','$autoid','$codigo1','$nombreprod1','$unidadmedida','$presentacion','$tipoproducto','$grupo','$bodega','$estante','$nivel','$usuariocre','$fechacre','$estado',$max,$min,$anio, '$cum', '$pos', '$noregsan', '$clasificacion',  '$error')"	;
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if(!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);
						if (!$resUTF8) {
							
							$fp = fopen("ReporteFarmacia.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
						}
				}	
		}	
		
		
		
		
				
		
		
		function  actualizarCodificacionProductos($cadenaBusqueda,$cadenaReemplazo)  {
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
		
		function  actualizarCodificacionAlmacenes($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.almacenesppales SET almacenppal = replace( almacenppal,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  actualizarCodificacionBodegas($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.bodegas SET bodega = replace( bodega,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
		function  actualizarCodificacionTiposProducto($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Consumo.tiposproducto SET tipoproducto = replace( tipoproducto,'". $cadenaBusqueda."'".",'". $cadenaReemplazo."')";
					
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p> <span class='subtitulo1'> Consulta SQL : </span>".$cons."</p> <br>";
				}

		}
		
				
	
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *  FROM Salud.codmedicamentos";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		

		
		function insertarRegistros($compania, $almacenppal, $autoid, $codigo1, $nombreprod1, $unidadmedida, $presentacion, $tipoproducto, $grupo, $bodega, $estante, $nivel, $usuariocre, $fechacre, $estado, $maximo, $minimo,$anio, $cum, $pos, $noregsan, $clasificacion) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Consumo.codproductos( compania, almacenppal,autoid, codigo1, nombreprod1, unidadmedida, presentacion, tipoproducto, grupo, bodega, estante, nivel, usuariocre, fechacre, estado, max, min, anio, cum, pos, reginvima, clasificacion) VALUES ('$compania','$almacenppal','$autoid','$codigo1','$nombreprod1','$unidadmedida','$presentacion','$tipoproducto','$grupo','$bodega','$estante','$nivel','$usuariocre','$fechacre','$estado',$maximo,$minimo,$anio, '$cum', $pos, '$noregsan', '$clasificacion')"	;
			//echo "<br> <br>";		 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							
							insertarRegistroMigracion($compania, $almacenppal,$autoid, $codigo1, $nombreprod1, $unidadmedida, $presentacion, $tipoproducto, $grupo, $bodega, $estante, $nivel, $usuariocre, $fechacre, $estado, $maximo, $minimo, $anio,$cum, $pos, $noregsan, $clasificacion,  $error);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $matriz;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["autoid"][$posicion] = $fila["AutoId"];
					$matriz["codigo"][$posicion] = $fila["CodPos"];
					$matriz["nombreprod1"][$posicion] = $fila["Generico"];
					$matriz["unidadmedida"][$posicion] = $fila["Presentacion"];
					$matriz["presentacion"][$posicion] = $fila["FormaFarma"];
					$matriz["grupo"][$posicion] = $fila["TipoMd"];
					$matriz["usuariocre"][$posicion] = $fila["usuario"];
					$matriz["fechacre"][$posicion] = $fila["Fecha"];

					$mes = 1; // Se define el mes 1 porque nos indica con que saldo inicia Diciembre
					$saldoMes = "SaldoIni".$mes;
					$costoMes = "PCosto".$mes;					
					$matriz["cantidad"][$posicion] = $fila["$saldoMes"];
					$matriz["vrunidad"][$posicion] = $fila["$costoMes"];
					$matriz["cum"][$posicion] = $fila["CUM"];
					$matriz["farmaprecios"][$posicion] = $fila["Farmaprecios"];
					$matriz["codesca"][$posicion] = $fila["Codesca"];
					$matriz["saludtotal"][$posicion] = $fila["SaludTotal"];					
					$matriz["epsifarma"][$posicion] = $fila["EPSifarma"];
					$matriz["nuevaeps"][$posicion] = $fila["NuevaEPS"];		
					$matriz["institucional"][$posicion] = $fila["Institucional"];
					$matriz["gruposanitas"][$posicion] = $fila[61]; // Se toma como referencia el numero de campo porque el nombre del campo puede generar Confusiones 	
					$matriz["institucional2014"][$posicion] = $fila["Institucional2014"];
																							
					
					$posicion++;				
				}
							
				
			}
			

			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$insertarlote = "true"; // Por defecto intenta realizar la insercion del lote
					$autoid= $matriz["autoid"][$pos] ;
					$compania= $_SESSION["compania"];
					$almacenppal = "FARMACIA";
					$anio = consultarAnio();
					/*$codigo1= $matriz["codigo"][$pos] ;
					$codigo1 = eliminarCaracteresEspeciales($codigo1);*/					
					$codigo1 = $autoid;
					
					//Inicia informacion Lote
					$fila = definirLote($codigo1);
						if (count($fila) <= 0){
							$insertarlote = "false"; // Si no encuentra informacion del lote se cambia el valor de la variable para que omita la insercion en la tabla Consumo.Lotes
						}
						 
					$fechalote = $fila["Fecha"];
					$proveedor = $fila["Proveedor"];
					$proveedor = eliminarCaracteresEspeciales($proveedor);
						if (trim($proveedor) == ""){
							$proveedor = 'POR VALIDAR';
						}
					$codigolote = $fila["Codigo"];
					$cantidadlote = $fila["Cantidad"] ;
					$tipomd = $fila["TipoMd"];
					$tipomd = eliminarCaracteresEspeciales($tipomd);
						if ($tipomd = "MEDICAMENTO"){
							$tipomd = "MEDICAMENTOS";
						}
					$lote = $fila["Lote"] ;
					$lote = eliminarCaracteresEspeciales($lote);
					$noregsan = $fila["NoRegSan"];
					$noregsan = eliminarCaracteresEspeciales($noregsan);
					
						if (trim($noregsan)== "") {
							$noregsan = "POR VALIDAR";
						}
					$fechavenc = $fila["FecVenc"];
						if ($fechavenc == "0000-00-00" or $fechavenc == "0000-00-00 00:00"){
							$fechavenc = "1900-01-01";
						}
						
					$temperatura = 30; // Temperatura ambiente
					$numero = $fila["Factura"];
					
					
					//Termina Informacion Lote
					
					// Inicia Calculo de existencias
					$cantidad = $matriz["cantidad"][$pos] ;
						if (!(isset($cantidad))) {
							$cantidad = 0;
						}
						
					/*$entradas = definirEntradas($codigo1, $anio);
					
						if (trim($entradas)== "") {
							$entradas = 0;
						}
					$salidas = definirSalidas($codigo1, $anio);
					
						if (trim($salidas)== "") {
							$salidas = 0;
						}
					$existencias = ($cantidad + $entradas ) -  $salidas;*/
					
					$existencias = $cantidad;
					$salidas = 0;
					
					// Termina Calculo de existencias
					
					$laboratorio = $proveedor;
						if ($existencias==0){
							$cerrado = 1;
						}
						if ($existencias > 0){
							$cerrado = 0;
						}
					
						
					
					$nombreprod1= $matriz["nombreprod1"][$pos] ;
					$nombreprod1 = eliminarCaracteresEspeciales($nombreprod1);
					$unidadmedida= $matriz["unidadmedida"][$pos] ;
					$unidadmedida = eliminarCaracteresEspeciales($unidadmedida);
					$presentacion= $matriz["presentacion"][$pos] ;
					$presentacion = eliminarCaracteresEspeciales($presentacion);
					$tipoproducto= "UNIDOSIS"; 
					$grupo= $matriz["grupo"][$pos] ;
					$grupo = eliminarCaracteresEspeciales($grupo);
					$grupo = normalizarGruposConsumo($grupo);
					$grupo = eliminarCaracteresEspeciales($grupo);
					$grupo = normalizarGruposConsumo($grupo);
					$bodega = "PRINCIPAL";
					$estante = "A";
					$nivel= $pos + 1;
					$usuariocre= $matriz["usuariocre"][$pos] ;
					$usuariocre = eliminarCaracteresEspeciales($usuariocre);
					$fechacre= $matriz["fechacre"][$pos] ;
					$fechacre = eliminarCaracteresEspeciales($fechacre);
						if($fechacre == "0000-00-00 00:00:00" or $fechacre == "0000-00-00") {
							$fechacre= "NULL";
						}
					$estado = "AC";
					$maximo = 10;
					$minimo= 1;
					
					$vrunidad = $matriz["vrunidad"][$pos] ;
						if (!(isset($vrunidad))) {
							$vrunidad = 0;
						}
					$vrtotal =  $cantidad * $vrunidad;
					
					$farmaprecios = $matriz["farmaprecios"][$pos] ;
					$codesca = $matriz["codesca"][$pos] ;					
					$saludtotal = $matriz["saludtotal"][$pos] ;										
					$epsifarma = $matriz["epsifarma"][$pos] ;
					$nuevaeps = $matriz["nuevaeps"][$pos] ;
					$institucional = $matriz["institucional"][$pos] ;
					$gruposanitas = $matriz["gruposanitas"][$pos] ;
					$institucional2014 = $matriz["institucional2014"][$pos] ;
					$cum= $matriz["codigo"][$pos] ;
					$cum = eliminarCaracteresEspeciales($cum);
					$clasificacion  = "POR VALIDAR"; // Se define como "POR VALIDAR" porque dicha informacion no se encuentra en la base de datos de Origen

					
					$fechaini= $anio."-01-01";
					$fechafin = $anio."-12-31";
					
					$planobligatoriosalud = 1; // Se deja por defecto 1 ya que hay mas medicamentos pos que no pos. De igual manera mas adelante se hace la actualizacion de los Medicamentos No POS
					
					insertarCumsxProducto($compania, $almacenppal,$autoid,$laboratorio,  $cum, $presentacion, $noregsan);
					insertarRegistros($compania, $almacenppal, $autoid, $codigo1, $nombreprod1, $unidadmedida, $presentacion, $tipoproducto, $grupo, $bodega, $estante, $nivel, $usuariocre, $fechacre, $estado, $maximo, $minimo,$anio, $cum, $planobligatoriosalud, $noregsan, $clasificacion);			
					insertarTarifasxProducto($compania, $almacenppal, "FARMAPRECIOS", $autoid, $fechaini, $fechafin, $farmaprecios, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "CODESCA", $autoid, $fechaini, $fechafin, $codesca, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "SALUDTOTAL", $autoid, $fechaini, $fechafin, $saludtotal, $anio);					
					insertarTarifasxProducto($compania, $almacenppal, "EPSIFARMA", $autoid, $fechaini, $fechafin, $epsifarma, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "NUEVA EPS", $autoid, $fechaini, $fechafin, $nuevaeps, $anio);	
					insertarTarifasxProducto($compania, $almacenppal, "INSTITUCIONAL", $autoid, $fechaini, $fechafin, $institucional, $anio);										
					insertarTarifasxProducto($compania, $almacenppal, "GRUPO SANITAS", $autoid, $fechaini, $fechafin, $gruposanitas, $anio);
					insertarTarifasxProducto($compania, $almacenppal, "INSTITUCIONAL 2014", $autoid, $fechaini, $fechafin, $institucional2014, $anio);
					insertarSaldosInicialesxAnio($compania, $almacenppal, $autoid, $anio, $cantidad, $vrunidad, $vrtotal);	
						
					
					}
					
			
			}
			
			
						
			
			
		
		
		function migrarFarmacia() {
			
			crearArchivoErroresLotes();
			eliminarSaldosInicialesxAnio();
			eliminarTarifasxProducto();				
			eliminarTarifariosVenta();
			eliminarCumsxProducto();
			
			eliminarRegistrosFarmacia();
			
					
					
			insertarTarifariosVenta($_SESSION["compania"], "FARMACIA");
			
			// Tabla Consumo.almacenesppales
			actualizarCodificacionAlmacenes(utf8_encode("Á"),'&Aacute;');			
			actualizarCodificacionAlmacenes(utf8_encode("É"),'&Eacute;');
			actualizarCodificacionAlmacenes(utf8_encode("Í"),'&Iacute;');
			actualizarCodificacionAlmacenes(utf8_encode("Ó"),'&Oacute;');
			actualizarCodificacionAlmacenes(utf8_encode("Ú"),'&Uacute;');
			actualizarCodificacionAlmacenes(utf8_encode("Ñ"),'&Ntilde;');
			
			
			// Tabla Consumo.bodegas
			actualizarCodificacionBodegas(utf8_encode("Á"),'&Aacute;');			
			actualizarCodificacionBodegas(utf8_encode("É"),'&Eacute;');
			actualizarCodificacionBodegas(utf8_encode("Í"),'&Iacute;');
			actualizarCodificacionBodegas(utf8_encode("Ó"),'&Oacute;');
			actualizarCodificacionBodegas(utf8_encode("Ú"),'&Uacute;');
			actualizarCodificacionBodegas(utf8_encode("Ñ"),'&Ntilde;');
			
			// Tabla Consumo.TiposProducto
			actualizarCodificacionTiposProducto(utf8_encode("Á"),'&Aacute;');			
			actualizarCodificacionTiposProducto(utf8_encode("É"),'&Eacute;');
			actualizarCodificacionTiposProducto(utf8_encode("Í"),'&Iacute;');
			actualizarCodificacionTiposProducto(utf8_encode("Ó"),'&Oacute;');
			actualizarCodificacionTiposProducto(utf8_encode("Ú"),'&Uacute;');
			actualizarCodificacionTiposProducto(utf8_encode("Ñ"),'&Ntilde;');
			
			
			eliminarTablaMigracion();
			crearTablaMigracion();
			crearArchivoErrores();
			llamarRegistrosMySQL();
			llenarMatriz();
			recorrerMatriz();
			
			

			
			
			// Tabla Consumo.almacenesppales
			actualizarCodificacionAlmacenes('&Aacute;', utf8_encode("Á"));			
			actualizarCodificacionAlmacenes('&Eacute;', utf8_encode("É"));
			actualizarCodificacionAlmacenes('&Iacute;', utf8_encode("Í"));
			actualizarCodificacionAlmacenes('&Oacute;', utf8_encode("Ó"));
			actualizarCodificacionAlmacenes('&Uacute;',utf8_encode("Ú"));
			actualizarCodificacionAlmacenes('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.bodegas
			actualizarCodificacionBodegas('&Aacute;', utf8_encode("Á"));			
			actualizarCodificacionBodegas('&Eacute;', utf8_encode("É"));
			actualizarCodificacionBodegas('&Iacute;', utf8_encode("Í"));
			actualizarCodificacionBodegas('&Oacute;', utf8_encode("Ó"));
			actualizarCodificacionBodegas('&Uacute;',utf8_encode("Ú"));
			actualizarCodificacionBodegas('&Ntilde;',utf8_encode("Ñ"));
			
			// Tabla Consumo.tiposproducto
			actualizarCodificacionTiposProducto('&Aacute;', utf8_encode("Á"));			
			actualizarCodificacionTiposProducto('&Eacute;', utf8_encode("É"));
			actualizarCodificacionTiposProducto('&Iacute;', utf8_encode("Í"));
			actualizarCodificacionTiposProducto('&Oacute;', utf8_encode("Ó"));
			actualizarCodificacionTiposProducto('&Uacute;',utf8_encode("Ú"));
			actualizarCodificacionTiposProducto('&Ntilde;',utf8_encode("Ñ"));
			
			
			
		    // Tabla Consumo.codproductos
			actualizarCodificacionProductos('&Aacute;', utf8_encode("Á"));			
			actualizarCodificacionProductos('&Eacute;', utf8_encode("É"));
			actualizarCodificacionProductos('&Iacute;', utf8_encode("Í"));
			actualizarCodificacionProductos('&Oacute;', utf8_encode("Ó"));
			actualizarCodificacionProductos('&Uacute;',utf8_encode("Ú"));
			actualizarCodificacionProductos('&Ntilde;',utf8_encode("Ñ"));
			
			//Tabla CUMSXProducto
			
			normalizarCUMSxProducto('&Aacute;', utf8_encode("Á"));			
			normalizarCUMSxProducto('&Eacute;', utf8_encode("É"));
			normalizarCUMSxProducto('&Iacute;', utf8_encode("Í"));
			normalizarCUMSxProducto('&Oacute;', utf8_encode("Ó"));
			normalizarCUMSxProducto('&Uacute;',utf8_encode("Ú"));
			normalizarCUMSxProducto('&Ntilde;',utf8_encode("Ñ"));
			
			
			migrarCodMed2010();
			migrarCodMed2011();
			
			// Se actualizan los medicamentos que no son POS
			actualizarNoPos('614');
			actualizarNoPos('3');
			actualizarNoPos('600');
			actualizarNoPos('335');
			actualizarNoPos('16');
			actualizarNoPos('36');
			actualizarNoPos('493');
			actualizarNoPos('426');
			actualizarNoPos('65');
			actualizarNoPos('446');
			actualizarNoPos('484');
			actualizarNoPos('480');
			actualizarNoPos('67');
			actualizarNoPos('68');
			actualizarNoPos('476');
			actualizarNoPos('431');
			actualizarNoPos('77');
			actualizarNoPos('522');
			actualizarNoPos('83');
			actualizarNoPos('456');
			actualizarNoPos('89');
			actualizarNoPos('90');
			actualizarNoPos('473');
			actualizarNoPos('93');
			actualizarNoPos('461');
			actualizarNoPos('470');
			actualizarNoPos('469');
			actualizarNoPos('416');
			actualizarNoPos('408');
			actualizarNoPos('559');
			actualizarNoPos('96');
			actualizarNoPos('95');
			actualizarNoPos('343');
			actualizarNoPos('111');
			actualizarNoPos('112');
			actualizarNoPos('114');
			actualizarNoPos('117');
			actualizarNoPos('458');
			actualizarNoPos('454');
			actualizarNoPos('511');
			actualizarNoPos('514');
			actualizarNoPos('128');
			actualizarNoPos('135');
			actualizarNoPos('136');
			actualizarNoPos('138');
			actualizarNoPos('140');
			actualizarNoPos('404');
			actualizarNoPos('405');
			actualizarNoPos('113');
			actualizarNoPos('488');
			actualizarNoPos('591');
			actualizarNoPos('613');
			actualizarNoPos('572');
			actualizarNoPos('145');
			actualizarNoPos('399');
			actualizarNoPos('434');
			actualizarNoPos('468');
			actualizarNoPos('394');
			actualizarNoPos('452');
			actualizarNoPos('396');
			actualizarNoPos('103');
			actualizarNoPos('208');
			actualizarNoPos('607');
			actualizarNoPos('161');
			actualizarNoPos('419');
			actualizarNoPos('523');
			actualizarNoPos('166');
			actualizarNoPos('482');
			actualizarNoPos('450');
			actualizarNoPos('170');
			actualizarNoPos('438');
			actualizarNoPos('177');
			actualizarNoPos('410');
			actualizarNoPos('180');
			actualizarNoPos('501');
			actualizarNoPos('402');
			actualizarNoPos('186');
			actualizarNoPos('474');
			actualizarNoPos('497');
			actualizarNoPos('197');
			actualizarNoPos('479');
			actualizarNoPos('206');
			actualizarNoPos('207');
			actualizarNoPos('443');
			actualizarNoPos('235');
			actualizarNoPos('215');
			actualizarNoPos('223');
			actualizarNoPos('398');
			actualizarNoPos('224');
			actualizarNoPos('582');
			actualizarNoPos('518');
			actualizarNoPos('521');
			actualizarNoPos('549');
			actualizarNoPos('586');
			actualizarNoPos('532');
			actualizarNoPos('428');
			actualizarNoPos('229');
			actualizarNoPos('242');
			actualizarNoPos('241');
			actualizarNoPos('483');
			actualizarNoPos('510');
			actualizarNoPos('38');
			actualizarNoPos('509');
			actualizarNoPos('249');
			actualizarNoPos('269');
			actualizarNoPos('529');
			actualizarNoPos('478');
			actualizarNoPos('489');
			actualizarNoPos('505');
			actualizarNoPos('276');
			actualizarNoPos('544');
			actualizarNoPos('543');
			actualizarNoPos('281');
			actualizarNoPos('383');
			actualizarNoPos('412');
			actualizarNoPos('507');
			actualizarNoPos('528');
			actualizarNoPos('421');
			actualizarNoPos('556');
			actualizarNoPos('531');
			actualizarNoPos('462');
			actualizarNoPos('536');
			actualizarNoPos('612');
			actualizarNoPos('555');
			actualizarNoPos('578');
			actualizarNoPos('498');
			actualizarNoPos('512');
			actualizarNoPos('411');
			actualizarNoPos('539');
			actualizarNoPos('403');
			actualizarNoPos('447');
			actualizarNoPos('553');
			actualizarNoPos('290');
			actualizarNoPos('291');
			actualizarNoPos('593');
			actualizarNoPos('533');
			actualizarNoPos('596');
			actualizarNoPos('299');
			actualizarNoPos('302');
			actualizarNoPos('307');
			actualizarNoPos('406');
			actualizarNoPos('471');
			actualizarNoPos('463');
			actualizarNoPos('515');
			actualizarNoPos('592');
			actualizarNoPos('508');
			actualizarNoPos('527');
			actualizarNoPos('502');
			actualizarNoPos('494');
			actualizarNoPos('491');
			actualizarNoPos('407');
			actualizarNoPos('437');
			actualizarNoPos('420');
			actualizarNoPos('496');
			actualizarNoPos('441');
			actualizarNoPos('314');
			actualizarNoPos('499');
			actualizarNoPos('495');
			actualizarNoPos('477');
			actualizarNoPos('32');
			actualizarNoPos('318');
			actualizarNoPos('551');
			actualizarNoPos('602');
			actualizarNoPos('339');
			actualizarNoPos('340');
			actualizarNoPos('432');
			actualizarNoPos('550');
			actualizarNoPos('439');
			actualizarNoPos('353');
			actualizarNoPos('354');
			actualizarNoPos('435');
			actualizarNoPos('357');
			actualizarNoPos('526');
			actualizarNoPos('603');
			actualizarNoPos('552');
			actualizarNoPos('436');
			actualizarNoPos('504');
			actualizarNoPos('386');
			actualizarNoPos('583');
			actualizarNoPos('445');
			actualizarNoPos('376');
			actualizarNoPos('377');
			actualizarNoPos('378');
			actualizarNoPos('409');
			actualizarNoPos('492');
			
			
			actualizarMaximoMedicamento('6' , '5');
			actualizarMaximoMedicamento('29' , '5');
			actualizarMaximoMedicamento('442' , '5');
			actualizarMaximoMedicamento('25' , '5');
			actualizarMaximoMedicamento('22' , '5');
			actualizarMaximoMedicamento('19' , '5');
			actualizarMaximoMedicamento('23' , '5');
			actualizarMaximoMedicamento('24' , '5');
			actualizarMaximoMedicamento('20' , '5');
			actualizarMaximoMedicamento('21' , '5');
			actualizarMaximoMedicamento('26' , '20');
			actualizarMaximoMedicamento('27' , '20');
			actualizarMaximoMedicamento('28' , '20');
			actualizarMaximoMedicamento('33' , '5');
			actualizarMaximoMedicamento('560' , '5');
			actualizarMaximoMedicamento('50' , '500');
			actualizarMaximoMedicamento('516' , '100');
			actualizarMaximoMedicamento('52' , '500');
			actualizarMaximoMedicamento('61' , '5');
			actualizarMaximoMedicamento('561' , '5');
			actualizarMaximoMedicamento('63' , '5');
			actualizarMaximoMedicamento('400' , '5');
			actualizarMaximoMedicamento('66' , '30');
			actualizarMaximoMedicamento('53' , '5');
			actualizarMaximoMedicamento('567' , '20');
			actualizarMaximoMedicamento('562' , '20');
			actualizarMaximoMedicamento('563' , '20');
			actualizarMaximoMedicamento('564' , '20');
			actualizarMaximoMedicamento('453' , '20');
			actualizarMaximoMedicamento('565' , '5');
			actualizarMaximoMedicamento('69' , '20');
			actualizarMaximoMedicamento('70' , '20');
			actualizarMaximoMedicamento('566' , '20');
			actualizarMaximoMedicamento('71' , '20');
			actualizarMaximoMedicamento('80' , '20');
			actualizarMaximoMedicamento('571' , '5');
			actualizarMaximoMedicamento('87' , '5');
			actualizarMaximoMedicamento('183' , '10');
			actualizarMaximoMedicamento('146' , '5');
			actualizarMaximoMedicamento('147' , '80');
			actualizarMaximoMedicamento('573' , '5');
			actualizarMaximoMedicamento('148' , '10');
			actualizarMaximoMedicamento('429' , '10');
			actualizarMaximoMedicamento('149' , '10');
			actualizarMaximoMedicamento('152' , '30');
			actualizarMaximoMedicamento('153' , '10');
			actualizarMaximoMedicamento('579' , '5');
			actualizarMaximoMedicamento('395' , '100');
			actualizarMaximoMedicamento('171' , '5');
			actualizarMaximoMedicamento('390' , '5');
			actualizarMaximoMedicamento('182' , '15');
			actualizarMaximoMedicamento('460' , '50');
			actualizarMaximoMedicamento('459' , '40');
			actualizarMaximoMedicamento('425' , '5');
			actualizarMaximoMedicamento('199' , '5');
			actualizarMaximoMedicamento('200' , '5');
			actualizarMaximoMedicamento('201' , '40');
			actualizarMaximoMedicamento('202' , '5');
			actualizarMaximoMedicamento('568' , '38');
			actualizarMaximoMedicamento('609' , '5');
			actualizarMaximoMedicamento('610' , '5');
			actualizarMaximoMedicamento('211' , '5');
			actualizarMaximoMedicamento('216' , '100');
			actualizarMaximoMedicamento('217' , '200');
			actualizarMaximoMedicamento('219' , '200');
			actualizarMaximoMedicamento('220' , '200');
			actualizarMaximoMedicamento('221' , '200');
			actualizarMaximoMedicamento('218' , '5');
			actualizarMaximoMedicamento('481' , '5');
			actualizarMaximoMedicamento('595' , '6');
			actualizarMaximoMedicamento('594' , '6');
			actualizarMaximoMedicamento('225' , '5');
			actualizarMaximoMedicamento('81' , '12');
			actualizarMaximoMedicamento('587' , '5');
			actualizarMaximoMedicamento('546' , '5');
			actualizarMaximoMedicamento('574' , '5');
			actualizarMaximoMedicamento('575' , '5');
			actualizarMaximoMedicamento('545' , '11');
			actualizarMaximoMedicamento('433' , '10');
			actualizarMaximoMedicamento('601' , '20');
			actualizarMaximoMedicamento('243' , '5');
			actualizarMaximoMedicamento('244' , '5');
			actualizarMaximoMedicamento('570' , '5');
			actualizarMaximoMedicamento('597' , '5');
			actualizarMaximoMedicamento('569' , '20');
			actualizarMaximoMedicamento('554' , '50');
			actualizarMaximoMedicamento('248' , '5');
			actualizarMaximoMedicamento('266' , '50');
			actualizarMaximoMedicamento('267' , '50');
			actualizarMaximoMedicamento('284' , '20');
			actualizarMaximoMedicamento('296' , '5');
			actualizarMaximoMedicamento('285' , '5');
			actualizarMaximoMedicamento('418' , '20');
			actualizarMaximoMedicamento('304' , '5');
			actualizarMaximoMedicamento('305' , '5');
			actualizarMaximoMedicamento('306' , '5');
			actualizarMaximoMedicamento('310' , '5');
			actualizarMaximoMedicamento('576' , '5');
			actualizarMaximoMedicamento('311' , '5');
			actualizarMaximoMedicamento('313' , '5');
			actualizarMaximoMedicamento('341' , '10');
			actualizarMaximoMedicamento('327' , '20');
			actualizarMaximoMedicamento('328' , '20');
			actualizarMaximoMedicamento('542' , '20');
			actualizarMaximoMedicamento('323' , '20');
			actualizarMaximoMedicamento('325' , '5');
			actualizarMaximoMedicamento('324' , '20');
			actualizarMaximoMedicamento('321' , '20');
			actualizarMaximoMedicamento('322' , '5');
			actualizarMaximoMedicamento('329' , '20');
			actualizarMaximoMedicamento('330' , '5');
			actualizarMaximoMedicamento('466' , '20');
			actualizarMaximoMedicamento('331' , '5');
			actualizarMaximoMedicamento('336' , '5');
			actualizarMaximoMedicamento('342' , '5');
			actualizarMaximoMedicamento('577' , '5');
			actualizarMaximoMedicamento('351' , '5');
			actualizarMaximoMedicamento('455' , '5');
			actualizarMaximoMedicamento('517' , '100');
			actualizarMaximoMedicamento('590' , '10');
			actualizarMaximoMedicamento('449' , '20');
			actualizarMaximoMedicamento('275' , '20');
			actualizarMaximoMedicamento('364' , '20');
			actualizarMaximoMedicamento('363' , '20');
			actualizarMaximoMedicamento('397' , '20');
			actualizarMaximoMedicamento('457' , '20');
			actualizarMaximoMedicamento('365' , '20');
			actualizarMaximoMedicamento('392' , '20');
			actualizarMaximoMedicamento('366' , '5');
			actualizarMaximoMedicamento('391' , '20');
			actualizarMaximoMedicamento('393' , '20');
			actualizarMaximoMedicamento('367' , '20');
			actualizarMaximoMedicamento('368' , '5');
			actualizarMaximoMedicamento('369' , '5');
			actualizarMaximoMedicamento('371' , '100');
			actualizarMaximoMedicamento('372' , '100');
			actualizarMaximoMedicamento('373' , '5');
			actualizarMaximoMedicamento('370' , '100');
			actualizarMaximoMedicamento('39' , '5');
			actualizarMaximoMedicamento('538' , '5');
			actualizarMaximoMedicamento('2' , '5');
			actualizarMaximoMedicamento('1' , '3000');
			actualizarMaximoMedicamento('614' , '5');
			actualizarMaximoMedicamento('3' , '30');
			actualizarMaximoMedicamento('506' , '5');
			actualizarMaximoMedicamento('4' , '5');
			actualizarMaximoMedicamento('5' , '5');
			actualizarMaximoMedicamento('503' , '5');
			actualizarMaximoMedicamento('7' , '5');
			actualizarMaximoMedicamento('10' , '1000');
			actualizarMaximoMedicamento('8' , '300');
			actualizarMaximoMedicamento('9' , '5');
			actualizarMaximoMedicamento('11' , '300');
			actualizarMaximoMedicamento('12' , '600');
			actualizarMaximoMedicamento('600' , '5');
			actualizarMaximoMedicamento('588' , '5');
			actualizarMaximoMedicamento('388' , '5');
			actualizarMaximoMedicamento('13' , '40000');
			actualizarMaximoMedicamento('14' , '20');
			actualizarMaximoMedicamento('335' , '5');
			actualizarMaximoMedicamento('15' , '10');
			actualizarMaximoMedicamento('16' , '5');
			actualizarMaximoMedicamento('17' , '5');
			actualizarMaximoMedicamento('18' , '200');
			actualizarMaximoMedicamento('30' , '300');
			actualizarMaximoMedicamento('31' , '5');
			actualizarMaximoMedicamento('423' , '5');
			actualizarMaximoMedicamento('65' , '30');
			actualizarMaximoMedicamento('34' , '300');
			actualizarMaximoMedicamento('35' , '300');
			actualizarMaximoMedicamento('36' , '5');
			actualizarMaximoMedicamento('520' , '90');
			actualizarMaximoMedicamento('40' , '5');
			actualizarMaximoMedicamento('41' , '5');
			actualizarMaximoMedicamento('382' , '5');
			actualizarMaximoMedicamento('42' , '25');
			actualizarMaximoMedicamento('43' , '300');
			actualizarMaximoMedicamento('422' , '53');
			actualizarMaximoMedicamento('45' , '5');
			actualizarMaximoMedicamento('44' , '300');
			actualizarMaximoMedicamento('46' , '30');
			actualizarMaximoMedicamento('47' , '300');
			actualizarMaximoMedicamento('48' , '30');
			actualizarMaximoMedicamento('49' , '5');
			actualizarMaximoMedicamento('493' , '300');
			actualizarMaximoMedicamento('426' , '5');
			actualizarMaximoMedicamento('585' , '600');
			actualizarMaximoMedicamento('51' , '25');
			actualizarMaximoMedicamento('467' , '60');
			actualizarMaximoMedicamento('530' , '5');
			actualizarMaximoMedicamento('54' , '10');
			actualizarMaximoMedicamento('55' , '10');
			actualizarMaximoMedicamento('56' , '10');
			actualizarMaximoMedicamento('464' , '10');
			actualizarMaximoMedicamento('57' , '5');
			actualizarMaximoMedicamento('380' , '20');
			actualizarMaximoMedicamento('58' , '600');
			actualizarMaximoMedicamento('59' , '5');
			actualizarMaximoMedicamento('60' , '600');
			actualizarMaximoMedicamento('64' , '5');
			actualizarMaximoMedicamento('389' , '30');
			actualizarMaximoMedicamento('446' , '30');
			actualizarMaximoMedicamento('484' , '30');
			actualizarMaximoMedicamento('480' , '30');
			actualizarMaximoMedicamento('67' , '5');
			actualizarMaximoMedicamento('68' , '5');
			actualizarMaximoMedicamento('619' , '32');
			actualizarMaximoMedicamento('72' , '800');
			actualizarMaximoMedicamento('73' , '5');
			actualizarMaximoMedicamento('74' , '20');
			actualizarMaximoMedicamento('75' , '6000');
			actualizarMaximoMedicamento('476' , '5');
			actualizarMaximoMedicamento('431' , '5');
			actualizarMaximoMedicamento('76' , '300');
			actualizarMaximoMedicamento('77' , '5');
			actualizarMaximoMedicamento('78' , '600');
			actualizarMaximoMedicamento('79' , '6000');
			actualizarMaximoMedicamento('194' , '30');
			actualizarMaximoMedicamento('522' , '5');
			actualizarMaximoMedicamento('82' , '300');
			actualizarMaximoMedicamento('581' , '5');
			actualizarMaximoMedicamento('611' , '10');
			actualizarMaximoMedicamento('85' , '5');
			actualizarMaximoMedicamento('83' , '5');
			actualizarMaximoMedicamento('84' , '300');
			actualizarMaximoMedicamento('86' , '20');
			actualizarMaximoMedicamento('456' , '5');
			actualizarMaximoMedicamento('88' , '10');
			actualizarMaximoMedicamento('89' , '5');
			actualizarMaximoMedicamento('90' , '5');
			actualizarMaximoMedicamento('472' , '5');
			actualizarMaximoMedicamento('92' , '5');
			actualizarMaximoMedicamento('91' , '300');
			actualizarMaximoMedicamento('473' , '5');
			actualizarMaximoMedicamento('93' , '5');
			actualizarMaximoMedicamento('94' , '5');
			actualizarMaximoMedicamento('608' , '5');
			actualizarMaximoMedicamento('547' , '30');
			actualizarMaximoMedicamento('461' , '5');
			actualizarMaximoMedicamento('470' , '5');
			actualizarMaximoMedicamento('469' , '5');
			actualizarMaximoMedicamento('416' , '5');
			actualizarMaximoMedicamento('408' , '200');
			actualizarMaximoMedicamento('559' , '5');
			actualizarMaximoMedicamento('96' , '5');
			actualizarMaximoMedicamento('95' , '5');
			actualizarMaximoMedicamento('97' , '3000');
			actualizarMaximoMedicamento('98' , '3000');
			actualizarMaximoMedicamento('99' , '30');
			actualizarMaximoMedicamento('100' , '300');
			actualizarMaximoMedicamento('584' , '30');
			actualizarMaximoMedicamento('343' , '30');
			actualizarMaximoMedicamento('104' , '10');
			actualizarMaximoMedicamento('105' , '10');
			actualizarMaximoMedicamento('107' , '5');
			actualizarMaximoMedicamento('108' , '90');
			actualizarMaximoMedicamento('106' , '10');
			actualizarMaximoMedicamento('110' , '9000');
			actualizarMaximoMedicamento('109' , '6000');
			actualizarMaximoMedicamento('580' , '60');
			actualizarMaximoMedicamento('524' , '16');
			actualizarMaximoMedicamento('537' , '5');
			actualizarMaximoMedicamento('166' , '5');
			actualizarMaximoMedicamento('111' , '5');
			actualizarMaximoMedicamento('112' , '5');
			actualizarMaximoMedicamento('114' , '5');
			actualizarMaximoMedicamento('115' , '5');
			actualizarMaximoMedicamento('191' , '5');
			actualizarMaximoMedicamento('117' , '5');
			actualizarMaximoMedicamento('116' , '5');
			actualizarMaximoMedicamento('458' , '5');
			actualizarMaximoMedicamento('511' , '5');
			actualizarMaximoMedicamento('120' , '60');
			actualizarMaximoMedicamento('119' , '5');
			actualizarMaximoMedicamento('121' , '30');
			actualizarMaximoMedicamento('122' , '30');
			actualizarMaximoMedicamento('123' , '30');
			actualizarMaximoMedicamento('125' , '5');
			actualizarMaximoMedicamento('124' , '50');
			actualizarMaximoMedicamento('514' , '5');
			actualizarMaximoMedicamento('126' , '60');
			actualizarMaximoMedicamento('127' , '90');
			actualizarMaximoMedicamento('128' , '5');
			actualizarMaximoMedicamento('130' , '5');
			actualizarMaximoMedicamento('129' , '600');
			actualizarMaximoMedicamento('618' , '5');
			actualizarMaximoMedicamento('132' , '20');
			actualizarMaximoMedicamento('131' , '1200');
			actualizarMaximoMedicamento('118' , '5');
			actualizarMaximoMedicamento('415' , '5');
			actualizarMaximoMedicamento('133' , '5');
			actualizarMaximoMedicamento('135' , '5');
			actualizarMaximoMedicamento('136' , '30');
			actualizarMaximoMedicamento('137' , '5');
			actualizarMaximoMedicamento('138' , '5');
			actualizarMaximoMedicamento('139' , '60');
			actualizarMaximoMedicamento('140' , '5');
			actualizarMaximoMedicamento('404' , '5');
			actualizarMaximoMedicamento('405' , '60');
			actualizarMaximoMedicamento('113' , '60');
			actualizarMaximoMedicamento('488' , '60');
			actualizarMaximoMedicamento('141' , '90');
			actualizarMaximoMedicamento('591' , '60');
			actualizarMaximoMedicamento('613' , '60');
			actualizarMaximoMedicamento('572' , '300');
			actualizarMaximoMedicamento('142' , '600');
			actualizarMaximoMedicamento('144' , '700');
			actualizarMaximoMedicamento('145' , '5');
			actualizarMaximoMedicamento('399' , '5');
			actualizarMaximoMedicamento('434' , '5');
			actualizarMaximoMedicamento('468' , '5');
			actualizarMaximoMedicamento('440' , '600');
			actualizarMaximoMedicamento('150' , '5');
			actualizarMaximoMedicamento('151' , '5');
			actualizarMaximoMedicamento('394' , '300');
			actualizarMaximoMedicamento('452' , '300');
			actualizarMaximoMedicamento('326' , '90');
			actualizarMaximoMedicamento('557' , '5');
			actualizarMaximoMedicamento('155' , '120');
			actualizarMaximoMedicamento('154' , '120');
			actualizarMaximoMedicamento('157' , '5');
			actualizarMaximoMedicamento('427' , '5');
			actualizarMaximoMedicamento('396' , '20');
			actualizarMaximoMedicamento('103' , '10');
			actualizarMaximoMedicamento('208' , '60');
			actualizarMaximoMedicamento('558' , '5');
			actualizarMaximoMedicamento('158' , '5');
			actualizarMaximoMedicamento('159' , '20');
			actualizarMaximoMedicamento('430' , '5');
			actualizarMaximoMedicamento('160' , '300');
			actualizarMaximoMedicamento('607' , '30');
			actualizarMaximoMedicamento('161' , '5');
			actualizarMaximoMedicamento('162' , '20');
			actualizarMaximoMedicamento('163' , '300');
			actualizarMaximoMedicamento('419' , '5');
			actualizarMaximoMedicamento('523' , '5');
			actualizarMaximoMedicamento('165' , '6000');
			actualizarMaximoMedicamento('384' , '30');
			actualizarMaximoMedicamento('482' , '30');
			actualizarMaximoMedicamento('450' , '5');
			actualizarMaximoMedicamento('167' , '5');
			actualizarMaximoMedicamento('168' , '300');
			actualizarMaximoMedicamento('169' , '110');
			actualizarMaximoMedicamento('170' , '60');
			actualizarMaximoMedicamento('438' , '60');
			actualizarMaximoMedicamento('172' , '600');
			actualizarMaximoMedicamento('178' , '5');
			actualizarMaximoMedicamento('175' , '5');
			actualizarMaximoMedicamento('173' , '5');
			actualizarMaximoMedicamento('174' , '5');
			actualizarMaximoMedicamento('176' , '5');
			actualizarMaximoMedicamento('177' , '5');
			actualizarMaximoMedicamento('410' , '5');
			actualizarMaximoMedicamento('179' , '300');
			actualizarMaximoMedicamento('180' , '5');
			actualizarMaximoMedicamento('181' , '5');
			actualizarMaximoMedicamento('500' , '5');
			actualizarMaximoMedicamento('501' , '5');
			actualizarMaximoMedicamento('402' , '5');
			actualizarMaximoMedicamento('187' , '900');
			actualizarMaximoMedicamento('184' , '30');
			actualizarMaximoMedicamento('188' , '1500');
			actualizarMaximoMedicamento('185' , '200');
			actualizarMaximoMedicamento('186' , '5');
			actualizarMaximoMedicamento('189' , '5');
			actualizarMaximoMedicamento('475' , '5');
			actualizarMaximoMedicamento('474' , '5');
			actualizarMaximoMedicamento('497' , '5');
			actualizarMaximoMedicamento('190' , '5');
			actualizarMaximoMedicamento('192' , '241');
			actualizarMaximoMedicamento('193' , '5');
			actualizarMaximoMedicamento('195' , '10');
			actualizarMaximoMedicamento('196' , '5');
			actualizarMaximoMedicamento('197' , '5');
			actualizarMaximoMedicamento('37' , '10');
			actualizarMaximoMedicamento('198' , '600');
			actualizarMaximoMedicamento('479' , '10');
			actualizarMaximoMedicamento('203' , '1200');
			actualizarMaximoMedicamento('205' , '60');
			actualizarMaximoMedicamento('204' , '300');
			actualizarMaximoMedicamento('206' , '5');
			actualizarMaximoMedicamento('207' , '5');
			actualizarMaximoMedicamento('598' , '10');
			actualizarMaximoMedicamento('599' , '10');
			actualizarMaximoMedicamento('209' , '10');
			actualizarMaximoMedicamento('210' , '10');
			actualizarMaximoMedicamento('443' , '5');
			actualizarMaximoMedicamento('235' , '20');
			actualizarMaximoMedicamento('213' , '30');
			actualizarMaximoMedicamento('214' , '60');
			actualizarMaximoMedicamento('212' , '60');
			actualizarMaximoMedicamento('215' , '20');
			actualizarMaximoMedicamento('222' , '5');
			actualizarMaximoMedicamento('223' , '5');
			actualizarMaximoMedicamento('519' , '5');
			actualizarMaximoMedicamento('534' , '5');
			actualizarMaximoMedicamento('398' , '5');
			actualizarMaximoMedicamento('224' , '300');
			actualizarMaximoMedicamento('582' , '5');
			actualizarMaximoMedicamento('518' , '90');
			actualizarMaximoMedicamento('521' , '90');
			actualizarMaximoMedicamento('549' , '5');
			actualizarMaximoMedicamento('586' , '5');
			actualizarMaximoMedicamento('532' , '5');
			actualizarMaximoMedicamento('428' , '300');
			actualizarMaximoMedicamento('226' , '300');
			actualizarMaximoMedicamento('227' , '300');
			actualizarMaximoMedicamento('417' , '10');
			actualizarMaximoMedicamento('228' , '600');
			actualizarMaximoMedicamento('233' , '5');
			actualizarMaximoMedicamento('231' , '5');
			actualizarMaximoMedicamento('232' , '5');
			actualizarMaximoMedicamento('229' , '5');
			actualizarMaximoMedicamento('230' , '5');
			actualizarMaximoMedicamento('606' , '5');
			actualizarMaximoMedicamento('234' , '300');
			actualizarMaximoMedicamento('236' , '300');
			actualizarMaximoMedicamento('237' , '5');
			actualizarMaximoMedicamento('238' , '3000');
			actualizarMaximoMedicamento('239' , '3000');
			actualizarMaximoMedicamento('490' , '600');
			actualizarMaximoMedicamento('387' , '600');
			actualizarMaximoMedicamento('240' , '5');
			actualizarMaximoMedicamento('242' , '5');
			actualizarMaximoMedicamento('241' , '5');
			actualizarMaximoMedicamento('246' , '60');
			actualizarMaximoMedicamento('247' , '5');
			actualizarMaximoMedicamento('483' , '5');
			actualizarMaximoMedicamento('510' , '30');
			actualizarMaximoMedicamento('38' , '30');
			actualizarMaximoMedicamento('509' , '5');
			actualizarMaximoMedicamento('101' , '5');
			actualizarMaximoMedicamento('513' , '5');
			actualizarMaximoMedicamento('249' , '5');
			actualizarMaximoMedicamento('250' , '600');
			actualizarMaximoMedicamento('251' , '5');
			actualizarMaximoMedicamento('252' , '10');
			actualizarMaximoMedicamento('253' , '300');
			actualizarMaximoMedicamento('255' , '60');
			actualizarMaximoMedicamento('257' , '90');
			actualizarMaximoMedicamento('258' , '300');
			actualizarMaximoMedicamento('259' , '30');
			actualizarMaximoMedicamento('260' , '300');
			actualizarMaximoMedicamento('261' , '8');
			actualizarMaximoMedicamento('444' , '5');
			actualizarMaximoMedicamento('265' , '5');
			actualizarMaximoMedicamento('264' , '60');
			actualizarMaximoMedicamento('262' , '300');
			actualizarMaximoMedicamento('268' , '5');
			actualizarMaximoMedicamento('270' , '300');
			actualizarMaximoMedicamento('269' , '5');
			actualizarMaximoMedicamento('529' , '5');
			actualizarMaximoMedicamento('478' , '30');
			actualizarMaximoMedicamento('489' , '5');
			actualizarMaximoMedicamento('102' , '5');
			actualizarMaximoMedicamento('535' , '5');
			actualizarMaximoMedicamento('505' , '5');
			actualizarMaximoMedicamento('271' , '600');
			actualizarMaximoMedicamento('272' , '100');
			actualizarMaximoMedicamento('273' , '5');
			actualizarMaximoMedicamento('274' , '60');
			actualizarMaximoMedicamento('621' , '10');
			actualizarMaximoMedicamento('276' , '5');
			actualizarMaximoMedicamento('424' , '100');
			actualizarMaximoMedicamento('544' , '30');
			actualizarMaximoMedicamento('277' , '5');
			actualizarMaximoMedicamento('278' , '30');
			actualizarMaximoMedicamento('605' , '5');
			actualizarMaximoMedicamento('279' , '5');
			actualizarMaximoMedicamento('280' , '30');
			actualizarMaximoMedicamento('543' , '40');
			actualizarMaximoMedicamento('281' , '1000');
			actualizarMaximoMedicamento('507' , '30');
			actualizarMaximoMedicamento('383' , '1000');
			actualizarMaximoMedicamento('412' , '5');
			actualizarMaximoMedicamento('282' , '12000');
			actualizarMaximoMedicamento('401' , '5');
			actualizarMaximoMedicamento('421' , '5');
			actualizarMaximoMedicamento('528' , '5');
			actualizarMaximoMedicamento('556' , '5');
			actualizarMaximoMedicamento('531' , '5');
			actualizarMaximoMedicamento('462' , '5');
			actualizarMaximoMedicamento('536' , '5');
			actualizarMaximoMedicamento('616' , '20');
			actualizarMaximoMedicamento('617' , '5');
			actualizarMaximoMedicamento('548' , '15');
			actualizarMaximoMedicamento('385' , '5');
			actualizarMaximoMedicamento('612' , '5');
			actualizarMaximoMedicamento('555' , '5');
			actualizarMaximoMedicamento('578' , '30');
			actualizarMaximoMedicamento('498' , '30');
			actualizarMaximoMedicamento('512' , '30');
			actualizarMaximoMedicamento('541' , '60');
			actualizarMaximoMedicamento('283' , '5');
			actualizarMaximoMedicamento('411' , '60');
			actualizarMaximoMedicamento('539' , '5');
			actualizarMaximoMedicamento('403' , '5');
			actualizarMaximoMedicamento('447' , '15');
			actualizarMaximoMedicamento('286' , '30');
			actualizarMaximoMedicamento('287' , '30');
			actualizarMaximoMedicamento('288' , '5');
			actualizarMaximoMedicamento('540' , '5');
			actualizarMaximoMedicamento('289' , '5');
			actualizarMaximoMedicamento('553' , '10');
			actualizarMaximoMedicamento('290' , '5');
			actualizarMaximoMedicamento('291' , '5');
			actualizarMaximoMedicamento('292' , '5');
			actualizarMaximoMedicamento('293' , '60');
			actualizarMaximoMedicamento('294' , '5');
			actualizarMaximoMedicamento('295' , '5');
			actualizarMaximoMedicamento('297' , '300');
			actualizarMaximoMedicamento('298' , '600');
			actualizarMaximoMedicamento('593' , '30');
			actualizarMaximoMedicamento('533' , '30');
			actualizarMaximoMedicamento('596' , '5');
			actualizarMaximoMedicamento('299' , '5');
			actualizarMaximoMedicamento('302' , '30');
			actualizarMaximoMedicamento('300' , '600');
			actualizarMaximoMedicamento('301' , '5');
			actualizarMaximoMedicamento('303' , '5');
			actualizarMaximoMedicamento('307' , '10');
			actualizarMaximoMedicamento('406' , '400');
			actualizarMaximoMedicamento('471' , '400');
			actualizarMaximoMedicamento('463' , '500');
			actualizarMaximoMedicamento('515' , '300');
			actualizarMaximoMedicamento('592' , '120');
			actualizarMaximoMedicamento('508' , '120');
			actualizarMaximoMedicamento('527' , '120');
			actualizarMaximoMedicamento('502' , '120');
			actualizarMaximoMedicamento('494' , '120');
			actualizarMaximoMedicamento('491' , '5');
			actualizarMaximoMedicamento('308' , '60');
			actualizarMaximoMedicamento('309' , '300');
			actualizarMaximoMedicamento('604' , '5');
			actualizarMaximoMedicamento('312' , '10');
			actualizarMaximoMedicamento('407' , '1500');
			actualizarMaximoMedicamento('437' , '120');
			actualizarMaximoMedicamento('420' , '2000');
			actualizarMaximoMedicamento('496' , '5');
			actualizarMaximoMedicamento('441' , '10');
			actualizarMaximoMedicamento('314' , '300');
			actualizarMaximoMedicamento('499' , '10');
			actualizarMaximoMedicamento('495' , '5');
			actualizarMaximoMedicamento('477' , '5');
			actualizarMaximoMedicamento('62' , '5');
			actualizarMaximoMedicamento('414' , '20');
			actualizarMaximoMedicamento('32' , '20');
			actualizarMaximoMedicamento('315' , '5');
			actualizarMaximoMedicamento('316' , '10');
			actualizarMaximoMedicamento('317' , '60');
			actualizarMaximoMedicamento('318' , '5');
			actualizarMaximoMedicamento('381' , '5');
			actualizarMaximoMedicamento('485' , '3000');
			actualizarMaximoMedicamento('551' , '5');
			actualizarMaximoMedicamento('602' , '30');
			actualizarMaximoMedicamento('448' , '5');
			actualizarMaximoMedicamento('320' , '10');
			actualizarMaximoMedicamento('319' , '120');
			actualizarMaximoMedicamento('620' , '5');
			actualizarMaximoMedicamento('332' , '300');
			actualizarMaximoMedicamento('451' , '5');
			actualizarMaximoMedicamento('486' , '5');
			actualizarMaximoMedicamento('333' , '5');
			actualizarMaximoMedicamento('465' , '30');
			actualizarMaximoMedicamento('334' , '5');
			actualizarMaximoMedicamento('337' , '10');
			actualizarMaximoMedicamento('338' , '5');
			actualizarMaximoMedicamento('339' , '5');
			actualizarMaximoMedicamento('340' , '5');
			actualizarMaximoMedicamento('454' , '5');
			actualizarMaximoMedicamento('432' , '5');
			actualizarMaximoMedicamento('550' , '5');
			actualizarMaximoMedicamento('439' , '5');
			actualizarMaximoMedicamento('344' , '120');
			actualizarMaximoMedicamento('345' , '5');
			actualizarMaximoMedicamento('615' , '10');
			actualizarMaximoMedicamento('346' , '10');
			actualizarMaximoMedicamento('347' , '5');
			actualizarMaximoMedicamento('348' , '300');
			actualizarMaximoMedicamento('349' , '5');
			actualizarMaximoMedicamento('350' , '5');
			actualizarMaximoMedicamento('352' , '5');
			actualizarMaximoMedicamento('353' , '5');
			actualizarMaximoMedicamento('354' , '5');
			actualizarMaximoMedicamento('356' , '5');
			actualizarMaximoMedicamento('355' , '5');
			actualizarMaximoMedicamento('435' , '30');
			actualizarMaximoMedicamento('357' , '5');
			actualizarMaximoMedicamento('526' , '5');
			actualizarMaximoMedicamento('358' , '5');
			actualizarMaximoMedicamento('525' , '5');
			actualizarMaximoMedicamento('603' , '6');
			actualizarMaximoMedicamento('359' , '1500');
			actualizarMaximoMedicamento('552' , '5');
			actualizarMaximoMedicamento('436' , '5');
			actualizarMaximoMedicamento('360' , '300');
			actualizarMaximoMedicamento('362' , '5');
			actualizarMaximoMedicamento('361' , '5');
			actualizarMaximoMedicamento('504' , '5');
			actualizarMaximoMedicamento('386' , '30');
			actualizarMaximoMedicamento('583' , '30');
			actualizarMaximoMedicamento('487' , '5');
			actualizarMaximoMedicamento('374' , '600');
			actualizarMaximoMedicamento('375' , '5');
			actualizarMaximoMedicamento('445' , '5');
			actualizarMaximoMedicamento('376' , '5');
			actualizarMaximoMedicamento('589' , '5');
			actualizarMaximoMedicamento('377' , '5');
			actualizarMaximoMedicamento('378' , '5');
			actualizarMaximoMedicamento('379' , '120');
			actualizarMaximoMedicamento('409' , '5');
			actualizarMaximoMedicamento('492' , '60');

			actualizarMinimoMedicamento('6' ,'0');
			actualizarMinimoMedicamento('29' ,'0');
			actualizarMinimoMedicamento('442' ,'0');
			actualizarMinimoMedicamento('25' ,'0');
			actualizarMinimoMedicamento('22' ,'0');
			actualizarMinimoMedicamento('19' ,'0');
			actualizarMinimoMedicamento('23' ,'0');
			actualizarMinimoMedicamento('24' ,'0');
			actualizarMinimoMedicamento('20' ,'0');
			actualizarMinimoMedicamento('21' ,'0');
			actualizarMinimoMedicamento('26' ,'2');
			actualizarMinimoMedicamento('27' ,'2');
			actualizarMinimoMedicamento('28' ,'2');
			actualizarMinimoMedicamento('33' ,'0');
			actualizarMinimoMedicamento('560' ,'0');
			actualizarMinimoMedicamento('50' ,'50');
			actualizarMinimoMedicamento('516' ,'5');
			actualizarMinimoMedicamento('52' ,'50');
			actualizarMinimoMedicamento('61' ,'0');
			actualizarMinimoMedicamento('561' ,'0');
			actualizarMinimoMedicamento('63' ,'0');
			actualizarMinimoMedicamento('400' ,'0');
			actualizarMinimoMedicamento('66' ,'5');
			actualizarMinimoMedicamento('53' ,'0');
			actualizarMinimoMedicamento('567' ,'2');
			actualizarMinimoMedicamento('562' ,'5');
			actualizarMinimoMedicamento('563' ,'5');
			actualizarMinimoMedicamento('564' ,'5');
			actualizarMinimoMedicamento('453' ,'5');
			actualizarMinimoMedicamento('565' ,'0');
			actualizarMinimoMedicamento('69' ,'5');
			actualizarMinimoMedicamento('70' ,'2');
			actualizarMinimoMedicamento('566' ,'2');
			actualizarMinimoMedicamento('71' ,'3');
			actualizarMinimoMedicamento('80' ,'10');
			actualizarMinimoMedicamento('571' ,'0');
			actualizarMinimoMedicamento('87' ,'0');
			actualizarMinimoMedicamento('183' ,'3');
			actualizarMinimoMedicamento('146' ,'1');
			actualizarMinimoMedicamento('147' ,'10');
			actualizarMinimoMedicamento('573' ,'0');
			actualizarMinimoMedicamento('148' ,'2');
			actualizarMinimoMedicamento('429' ,'0');
			actualizarMinimoMedicamento('149' ,'1');
			actualizarMinimoMedicamento('152' ,'1');
			actualizarMinimoMedicamento('153' ,'1');
			actualizarMinimoMedicamento('579' ,'0');
			actualizarMinimoMedicamento('395' ,'10');
			actualizarMinimoMedicamento('171' ,'0');
			actualizarMinimoMedicamento('390' ,'1');
			actualizarMinimoMedicamento('182' ,'2');
			actualizarMinimoMedicamento('460' ,'1');
			actualizarMinimoMedicamento('459' ,'1');
			actualizarMinimoMedicamento('425' ,'0');
			actualizarMinimoMedicamento('199' ,'0');
			actualizarMinimoMedicamento('200' ,'0');
			actualizarMinimoMedicamento('201' ,'10');
			actualizarMinimoMedicamento('202' ,'0');
			actualizarMinimoMedicamento('568' ,'5');
			actualizarMinimoMedicamento('609' ,'1');
			actualizarMinimoMedicamento('610' ,'1');
			actualizarMinimoMedicamento('211' ,'0');
			actualizarMinimoMedicamento('216' ,'10');
			actualizarMinimoMedicamento('217' ,'10');
			actualizarMinimoMedicamento('219' ,'10');
			actualizarMinimoMedicamento('220' ,'10');
			actualizarMinimoMedicamento('221' ,'10');
			actualizarMinimoMedicamento('218' ,'0');
			actualizarMinimoMedicamento('481' ,'0');
			actualizarMinimoMedicamento('595' ,'2');
			actualizarMinimoMedicamento('594' ,'2');
			actualizarMinimoMedicamento('225' ,'0');
			actualizarMinimoMedicamento('81' ,'2');
			actualizarMinimoMedicamento('587' ,'2');
			actualizarMinimoMedicamento('546' ,'2');
			actualizarMinimoMedicamento('574' ,'0');
			actualizarMinimoMedicamento('575' ,'0');
			actualizarMinimoMedicamento('545' ,'3');
			actualizarMinimoMedicamento('433' ,'1');
			actualizarMinimoMedicamento('601' ,'2');
			actualizarMinimoMedicamento('243' ,'0');
			actualizarMinimoMedicamento('244' ,'0');
			actualizarMinimoMedicamento('570' ,'0');
			actualizarMinimoMedicamento('597' ,'0');
			actualizarMinimoMedicamento('569' ,'2');
			actualizarMinimoMedicamento('554' ,'5');
			actualizarMinimoMedicamento('248' ,'0');
			actualizarMinimoMedicamento('266' ,'5');
			actualizarMinimoMedicamento('267' ,'5');
			actualizarMinimoMedicamento('284' ,'2');
			actualizarMinimoMedicamento('296' ,'0');
			actualizarMinimoMedicamento('285' ,'0');
			actualizarMinimoMedicamento('418' ,'1');
			actualizarMinimoMedicamento('304' ,'0');
			actualizarMinimoMedicamento('305' ,'0');
			actualizarMinimoMedicamento('306' ,'0');
			actualizarMinimoMedicamento('310' ,'0');
			actualizarMinimoMedicamento('576' ,'0');
			actualizarMinimoMedicamento('311' ,'0');
			actualizarMinimoMedicamento('313' ,'0');
			actualizarMinimoMedicamento('341' ,'3');
			actualizarMinimoMedicamento('327' ,'2');
			actualizarMinimoMedicamento('328' ,'2');
			actualizarMinimoMedicamento('542' ,'2');
			actualizarMinimoMedicamento('323' ,'2');
			actualizarMinimoMedicamento('325' ,'0');
			actualizarMinimoMedicamento('324' ,'2');
			actualizarMinimoMedicamento('321' ,'2');
			actualizarMinimoMedicamento('322' ,'0');
			actualizarMinimoMedicamento('329' ,'2');
			actualizarMinimoMedicamento('330' ,'0');
			actualizarMinimoMedicamento('466' ,'2');
			actualizarMinimoMedicamento('331' ,'0');
			actualizarMinimoMedicamento('336' ,'0');
			actualizarMinimoMedicamento('342' ,'0');
			actualizarMinimoMedicamento('577' ,'0');
			actualizarMinimoMedicamento('351' ,'0');
			actualizarMinimoMedicamento('455' ,'0');
			actualizarMinimoMedicamento('517' ,'2');
			actualizarMinimoMedicamento('590' ,'2');
			actualizarMinimoMedicamento('449' ,'1');
			actualizarMinimoMedicamento('275' ,'1');
			actualizarMinimoMedicamento('364' ,'1');
			actualizarMinimoMedicamento('363' ,'1');
			actualizarMinimoMedicamento('397' ,'1');
			actualizarMinimoMedicamento('457' ,'1');
			actualizarMinimoMedicamento('365' ,'1');
			actualizarMinimoMedicamento('392' ,'5');
			actualizarMinimoMedicamento('366' ,'0');
			actualizarMinimoMedicamento('391' ,'1');
			actualizarMinimoMedicamento('393' ,'1');
			actualizarMinimoMedicamento('367' ,'1');
			actualizarMinimoMedicamento('368' ,'0');
			actualizarMinimoMedicamento('369' ,'0');
			actualizarMinimoMedicamento('371' ,'10');
			actualizarMinimoMedicamento('372' ,'10');
			actualizarMinimoMedicamento('373' ,'0');
			actualizarMinimoMedicamento('370' ,'10');
			actualizarMinimoMedicamento('39' ,'0');
			actualizarMinimoMedicamento('538' ,'0');
			actualizarMinimoMedicamento('2' ,'0');
			actualizarMinimoMedicamento('1' ,'100');
			actualizarMinimoMedicamento('614' ,'0');
			actualizarMinimoMedicamento('3' ,'0');
			actualizarMinimoMedicamento('506' ,'0');
			actualizarMinimoMedicamento('4' ,'0');
			actualizarMinimoMedicamento('5' ,'0');
			actualizarMinimoMedicamento('503' ,'0');
			actualizarMinimoMedicamento('7' ,'0');
			actualizarMinimoMedicamento('10' ,'60');
			actualizarMinimoMedicamento('8' ,'0');
			actualizarMinimoMedicamento('9' ,'0');
			actualizarMinimoMedicamento('11' ,'0');
			actualizarMinimoMedicamento('12' ,'30');
			actualizarMinimoMedicamento('600' ,'0');
			actualizarMinimoMedicamento('588' ,'0');
			actualizarMinimoMedicamento('388' ,'0');
			actualizarMinimoMedicamento('13' ,'5000');
			actualizarMinimoMedicamento('14' ,'3');
			actualizarMinimoMedicamento('335' ,'2');
			actualizarMinimoMedicamento('15' ,'7');
			actualizarMinimoMedicamento('16' ,'0');
			actualizarMinimoMedicamento('17' ,'0');
			actualizarMinimoMedicamento('18' ,'10');
			actualizarMinimoMedicamento('30' ,'0');
			actualizarMinimoMedicamento('31' ,'0');
			actualizarMinimoMedicamento('423' ,'0');
			actualizarMinimoMedicamento('65' ,'0');
			actualizarMinimoMedicamento('34' ,'30');
			actualizarMinimoMedicamento('35' ,'30');
			actualizarMinimoMedicamento('36' ,'0');
			actualizarMinimoMedicamento('520' ,'0');
			actualizarMinimoMedicamento('40' ,'0');
			actualizarMinimoMedicamento('41' ,'2');
			actualizarMinimoMedicamento('382' ,'0');
			actualizarMinimoMedicamento('42' ,'10');
			actualizarMinimoMedicamento('43' ,'10');
			actualizarMinimoMedicamento('422' ,'53');
			actualizarMinimoMedicamento('45' ,'0');
			actualizarMinimoMedicamento('44' ,'30');
			actualizarMinimoMedicamento('46' ,'2');
			actualizarMinimoMedicamento('47' ,'30');
			actualizarMinimoMedicamento('48' ,'2');
			actualizarMinimoMedicamento('49' ,'0');
			actualizarMinimoMedicamento('493' ,'0');
			actualizarMinimoMedicamento('426' ,'0');
			actualizarMinimoMedicamento('585' ,'30');
			actualizarMinimoMedicamento('51' ,'6');
			actualizarMinimoMedicamento('467' ,'0');
			actualizarMinimoMedicamento('530' ,'0');
			actualizarMinimoMedicamento('54' ,'2');
			actualizarMinimoMedicamento('55' ,'2');
			actualizarMinimoMedicamento('56' ,'2');
			actualizarMinimoMedicamento('464' ,'2');
			actualizarMinimoMedicamento('57' ,'0');
			actualizarMinimoMedicamento('380' ,'2');
			actualizarMinimoMedicamento('58' ,'30');
			actualizarMinimoMedicamento('59' ,'0');
			actualizarMinimoMedicamento('60' ,'30');
			actualizarMinimoMedicamento('64' ,'0');
			actualizarMinimoMedicamento('389' ,'0');
			actualizarMinimoMedicamento('446' ,'0');
			actualizarMinimoMedicamento('484' ,'0');
			actualizarMinimoMedicamento('480' ,'0');
			actualizarMinimoMedicamento('67' ,'0');
			actualizarMinimoMedicamento('68' ,'0');
			actualizarMinimoMedicamento('619' ,'0');
			actualizarMinimoMedicamento('72' ,'30');
			actualizarMinimoMedicamento('73' ,'0');
			actualizarMinimoMedicamento('74' ,'3');
			actualizarMinimoMedicamento('75' ,'200');
			actualizarMinimoMedicamento('476' ,'0');
			actualizarMinimoMedicamento('431' ,'0');
			actualizarMinimoMedicamento('76' ,'30');
			actualizarMinimoMedicamento('77' ,'0');
			actualizarMinimoMedicamento('78' ,'30');
			actualizarMinimoMedicamento('79' ,'200');
			actualizarMinimoMedicamento('194' ,'0');
			actualizarMinimoMedicamento('522' ,'0');
			actualizarMinimoMedicamento('82' ,'30');
			actualizarMinimoMedicamento('581' ,'0');
			actualizarMinimoMedicamento('611' ,'0');
			actualizarMinimoMedicamento('85' ,'0');
			actualizarMinimoMedicamento('83' ,'0');
			actualizarMinimoMedicamento('84' ,'30');
			actualizarMinimoMedicamento('86' ,'0');
			actualizarMinimoMedicamento('456' ,'0');
			actualizarMinimoMedicamento('88' ,'10');
			actualizarMinimoMedicamento('89' ,'0');
			actualizarMinimoMedicamento('90' ,'0');
			actualizarMinimoMedicamento('472' ,'0');
			actualizarMinimoMedicamento('92' ,'0');
			actualizarMinimoMedicamento('91' ,'30');
			actualizarMinimoMedicamento('473' ,'0');
			actualizarMinimoMedicamento('93' ,'0');
			actualizarMinimoMedicamento('94' ,'1');
			actualizarMinimoMedicamento('608' ,'0');
			actualizarMinimoMedicamento('547' ,'0');
			actualizarMinimoMedicamento('461' ,'0');
			actualizarMinimoMedicamento('470' ,'0');
			actualizarMinimoMedicamento('469' ,'0');
			actualizarMinimoMedicamento('416' ,'0');
			actualizarMinimoMedicamento('408' ,'30');
			actualizarMinimoMedicamento('559' ,'0');
			actualizarMinimoMedicamento('96' ,'0');
			actualizarMinimoMedicamento('95' ,'0');
			actualizarMinimoMedicamento('97' ,'300');
			actualizarMinimoMedicamento('98' ,'300');
			actualizarMinimoMedicamento('99' ,'3');
			actualizarMinimoMedicamento('100' ,'30');
			actualizarMinimoMedicamento('584' ,'0');
			actualizarMinimoMedicamento('343' ,'0');
			actualizarMinimoMedicamento('104' ,'5');
			actualizarMinimoMedicamento('105' ,'1');
			actualizarMinimoMedicamento('107' ,'0');
			actualizarMinimoMedicamento('108' ,'20');
			actualizarMinimoMedicamento('106' ,'1');
			actualizarMinimoMedicamento('110' ,'1500');
			actualizarMinimoMedicamento('109' ,'900');
			actualizarMinimoMedicamento('580' ,'0');
			actualizarMinimoMedicamento('524' ,'0');
			actualizarMinimoMedicamento('537' ,'0');
			actualizarMinimoMedicamento('166' ,'2');
			actualizarMinimoMedicamento('111' ,'0');
			actualizarMinimoMedicamento('112' ,'0');
			actualizarMinimoMedicamento('114' ,'0');
			actualizarMinimoMedicamento('115' ,'0');
			actualizarMinimoMedicamento('191' ,'0');
			actualizarMinimoMedicamento('117' ,'0');
			actualizarMinimoMedicamento('116' ,'0');
			actualizarMinimoMedicamento('458' ,'0');
			actualizarMinimoMedicamento('511' ,'0');
			actualizarMinimoMedicamento('120' ,'5');
			actualizarMinimoMedicamento('119' ,'0');
			actualizarMinimoMedicamento('121' ,'5');
			actualizarMinimoMedicamento('122' ,'5');
			actualizarMinimoMedicamento('123' ,'5');
			actualizarMinimoMedicamento('125' ,'0');
			actualizarMinimoMedicamento('124' ,'5');
			actualizarMinimoMedicamento('514' ,'0');
			actualizarMinimoMedicamento('126' ,'0');
			actualizarMinimoMedicamento('127' ,'5');
			actualizarMinimoMedicamento('128' ,'0');
			actualizarMinimoMedicamento('130' ,'0');
			actualizarMinimoMedicamento('129' ,'30');
			actualizarMinimoMedicamento('618' ,'0');
			actualizarMinimoMedicamento('132' ,'4');
			actualizarMinimoMedicamento('131' ,'200');
			actualizarMinimoMedicamento('118' ,'0');
			actualizarMinimoMedicamento('415' ,'0');
			actualizarMinimoMedicamento('133' ,'0');
			actualizarMinimoMedicamento('135' ,'0');
			actualizarMinimoMedicamento('136' ,'0');
			actualizarMinimoMedicamento('137' ,'0');
			actualizarMinimoMedicamento('138' ,'0');
			actualizarMinimoMedicamento('139' ,'0');
			actualizarMinimoMedicamento('140' ,'0');
			actualizarMinimoMedicamento('404' ,'0');
			actualizarMinimoMedicamento('405' ,'0');
			actualizarMinimoMedicamento('113' ,'0');
			actualizarMinimoMedicamento('488' ,'0');
			actualizarMinimoMedicamento('141' ,'0');
			actualizarMinimoMedicamento('591' ,'0');
			actualizarMinimoMedicamento('613' ,'0');
			actualizarMinimoMedicamento('572' ,'50');
			actualizarMinimoMedicamento('142' ,'30');
			actualizarMinimoMedicamento('144' ,'60');
			actualizarMinimoMedicamento('145' ,'0');
			actualizarMinimoMedicamento('399' ,'0');
			actualizarMinimoMedicamento('434' ,'0');
			actualizarMinimoMedicamento('468' ,'0');
			actualizarMinimoMedicamento('440' ,'90');
			actualizarMinimoMedicamento('150' ,'0');
			actualizarMinimoMedicamento('151' ,'0');
			actualizarMinimoMedicamento('394' ,'30');
			actualizarMinimoMedicamento('452' ,'30');
			actualizarMinimoMedicamento('326' ,'0');
			actualizarMinimoMedicamento('557' ,'0');
			actualizarMinimoMedicamento('155' ,'0');
			actualizarMinimoMedicamento('154' ,'0');
			actualizarMinimoMedicamento('157' ,'0');
			actualizarMinimoMedicamento('427' ,'0');
			actualizarMinimoMedicamento('396' ,'4');
			actualizarMinimoMedicamento('103' ,'3');
			actualizarMinimoMedicamento('208' ,'0');
			actualizarMinimoMedicamento('558' ,'0');
			actualizarMinimoMedicamento('158' ,'0');
			actualizarMinimoMedicamento('159' ,'6');
			actualizarMinimoMedicamento('430' ,'0');
			actualizarMinimoMedicamento('160' ,'30');
			actualizarMinimoMedicamento('607' ,'0');
			actualizarMinimoMedicamento('161' ,'0');
			actualizarMinimoMedicamento('162' ,'0');
			actualizarMinimoMedicamento('163' ,'0');
			actualizarMinimoMedicamento('419' ,'5');
			actualizarMinimoMedicamento('523' ,'0');
			actualizarMinimoMedicamento('165' ,'200');
			actualizarMinimoMedicamento('384' ,'5');
			actualizarMinimoMedicamento('482' ,'0');
			actualizarMinimoMedicamento('450' ,'0');
			actualizarMinimoMedicamento('167' ,'0');
			actualizarMinimoMedicamento('168' ,'30');
			actualizarMinimoMedicamento('169' ,'5');
			actualizarMinimoMedicamento('170' ,'0');
			actualizarMinimoMedicamento('438' ,'0');
			actualizarMinimoMedicamento('172' ,'60');
			actualizarMinimoMedicamento('178' ,'0');
			actualizarMinimoMedicamento('175' ,'0');
			actualizarMinimoMedicamento('173' ,'0');
			actualizarMinimoMedicamento('174' ,'0');
			actualizarMinimoMedicamento('176' ,'0');
			actualizarMinimoMedicamento('177' ,'0');
			actualizarMinimoMedicamento('410' ,'0');
			actualizarMinimoMedicamento('179' ,'60');
			actualizarMinimoMedicamento('180' ,'0');
			actualizarMinimoMedicamento('181' ,'0');
			actualizarMinimoMedicamento('500' ,'0');
			actualizarMinimoMedicamento('501' ,'0');
			actualizarMinimoMedicamento('402' ,'4');
			actualizarMinimoMedicamento('187' ,'200');
			actualizarMinimoMedicamento('184' ,'5');
			actualizarMinimoMedicamento('188' ,'200');
			actualizarMinimoMedicamento('185' ,'10');
			actualizarMinimoMedicamento('186' ,'0');
			actualizarMinimoMedicamento('189' ,'0');
			actualizarMinimoMedicamento('475' ,'0');
			actualizarMinimoMedicamento('474' ,'0');
			actualizarMinimoMedicamento('497' ,'0');
			actualizarMinimoMedicamento('190' ,'0');
			actualizarMinimoMedicamento('192' ,'241');
			actualizarMinimoMedicamento('193' ,'3');
			actualizarMinimoMedicamento('195' ,'10');
			actualizarMinimoMedicamento('196' ,'0');
			actualizarMinimoMedicamento('197' ,'0');
			actualizarMinimoMedicamento('37' ,'2');
			actualizarMinimoMedicamento('198' ,'30');
			actualizarMinimoMedicamento('479' ,'1');
			actualizarMinimoMedicamento('203' ,'100');
			actualizarMinimoMedicamento('205' ,'10');
			actualizarMinimoMedicamento('204' ,'30');
			actualizarMinimoMedicamento('206' ,'0');
			actualizarMinimoMedicamento('207' ,'0');
			actualizarMinimoMedicamento('598' ,'1');
			actualizarMinimoMedicamento('599' ,'1');
			actualizarMinimoMedicamento('209' ,'1');
			actualizarMinimoMedicamento('210' ,'2');
			actualizarMinimoMedicamento('443' ,'0');
			actualizarMinimoMedicamento('235' ,'9');
			actualizarMinimoMedicamento('213' ,'0');
			actualizarMinimoMedicamento('214' ,'10');
			actualizarMinimoMedicamento('212' ,'10');
			actualizarMinimoMedicamento('215' ,'3');
			actualizarMinimoMedicamento('222' ,'0');
			actualizarMinimoMedicamento('223' ,'0');
			actualizarMinimoMedicamento('519' ,'0');
			actualizarMinimoMedicamento('534' ,'0');
			actualizarMinimoMedicamento('398' ,'0');
			actualizarMinimoMedicamento('224' ,'30');
			actualizarMinimoMedicamento('582' ,'0');
			actualizarMinimoMedicamento('518' ,'0');
			actualizarMinimoMedicamento('521' ,'0');
			actualizarMinimoMedicamento('549' ,'0');
			actualizarMinimoMedicamento('586' ,'0');
			actualizarMinimoMedicamento('532' ,'0');
			actualizarMinimoMedicamento('428' ,'30');
			actualizarMinimoMedicamento('226' ,'30');
			actualizarMinimoMedicamento('227' ,'30');
			actualizarMinimoMedicamento('417' ,'3');
			actualizarMinimoMedicamento('228' ,'30');
			actualizarMinimoMedicamento('233' ,'0');
			actualizarMinimoMedicamento('231' ,'0');
			actualizarMinimoMedicamento('232' ,'0');
			actualizarMinimoMedicamento('229' ,'0');
			actualizarMinimoMedicamento('230' ,'0');
			actualizarMinimoMedicamento('606' ,'0');
			actualizarMinimoMedicamento('234' ,'10');
			actualizarMinimoMedicamento('236' ,'30');
			actualizarMinimoMedicamento('237' ,'0');
			actualizarMinimoMedicamento('238' ,'300');
			actualizarMinimoMedicamento('239' ,'300');
			actualizarMinimoMedicamento('490' ,'60');
			actualizarMinimoMedicamento('387' ,'60');
			actualizarMinimoMedicamento('240' ,'0');
			actualizarMinimoMedicamento('242' ,'0');
			actualizarMinimoMedicamento('241' ,'0');
			actualizarMinimoMedicamento('246' ,'0');
			actualizarMinimoMedicamento('247' ,'0');
			actualizarMinimoMedicamento('483' ,'0');
			actualizarMinimoMedicamento('510' ,'0');
			actualizarMinimoMedicamento('38' ,'0');
			actualizarMinimoMedicamento('509' ,'0');
			actualizarMinimoMedicamento('101' ,'0');
			actualizarMinimoMedicamento('513' ,'0');
			actualizarMinimoMedicamento('249' ,'0');
			actualizarMinimoMedicamento('250' ,'60');
			actualizarMinimoMedicamento('251' ,'0');
			actualizarMinimoMedicamento('252' ,'0');
			actualizarMinimoMedicamento('253' ,'30');
			actualizarMinimoMedicamento('255' ,'0');
			actualizarMinimoMedicamento('257' ,'0');
			actualizarMinimoMedicamento('258' ,'10');
			actualizarMinimoMedicamento('259' ,'3');
			actualizarMinimoMedicamento('260' ,'60');
			actualizarMinimoMedicamento('261' ,'5');
			actualizarMinimoMedicamento('444' ,'0');
			actualizarMinimoMedicamento('265' ,'0');
			actualizarMinimoMedicamento('264' ,'5');
			actualizarMinimoMedicamento('262' ,'30');
			actualizarMinimoMedicamento('268' ,'0');
			actualizarMinimoMedicamento('270' ,'60');
			actualizarMinimoMedicamento('269' ,'0');
			actualizarMinimoMedicamento('529' ,'0');
			actualizarMinimoMedicamento('478' ,'0');
			actualizarMinimoMedicamento('489' ,'0');
			actualizarMinimoMedicamento('102' ,'0');
			actualizarMinimoMedicamento('535' ,'0');
			actualizarMinimoMedicamento('505' ,'0');
			actualizarMinimoMedicamento('271' ,'30');
			actualizarMinimoMedicamento('272' ,'10');
			actualizarMinimoMedicamento('273' ,'1');
			actualizarMinimoMedicamento('274' ,'10');
			actualizarMinimoMedicamento('621' ,'5');
			actualizarMinimoMedicamento('276' ,'0');
			actualizarMinimoMedicamento('424' ,'0');
			actualizarMinimoMedicamento('544' ,'0');
			actualizarMinimoMedicamento('277' ,'0');
			actualizarMinimoMedicamento('278' ,'0');
			actualizarMinimoMedicamento('605' ,'0');
			actualizarMinimoMedicamento('279' ,'1');
			actualizarMinimoMedicamento('280' ,'0');
			actualizarMinimoMedicamento('543' ,'10');
			actualizarMinimoMedicamento('281' ,'100');
			actualizarMinimoMedicamento('507' ,'0');
			actualizarMinimoMedicamento('383' ,'100');
			actualizarMinimoMedicamento('412' ,'0');
			actualizarMinimoMedicamento('282' ,'600');
			actualizarMinimoMedicamento('401' ,'0');
			actualizarMinimoMedicamento('421' ,'0');
			actualizarMinimoMedicamento('528' ,'0');
			actualizarMinimoMedicamento('556' ,'0');
			actualizarMinimoMedicamento('531' ,'0');
			actualizarMinimoMedicamento('462' ,'0');
			actualizarMinimoMedicamento('536' ,'0');
			actualizarMinimoMedicamento('616' ,'8');
			actualizarMinimoMedicamento('617' ,'1');
			actualizarMinimoMedicamento('548' ,'5');
			actualizarMinimoMedicamento('385' ,'0');
			actualizarMinimoMedicamento('612' ,'0');
			actualizarMinimoMedicamento('555' ,'0');
			actualizarMinimoMedicamento('578' ,'0');
			actualizarMinimoMedicamento('498' ,'0');
			actualizarMinimoMedicamento('512' ,'0');
			actualizarMinimoMedicamento('541' ,'0');
			actualizarMinimoMedicamento('283' ,'0');
			actualizarMinimoMedicamento('411' ,'3');
			actualizarMinimoMedicamento('539' ,'0');
			actualizarMinimoMedicamento('403' ,'0');
			actualizarMinimoMedicamento('447' ,'5');
			actualizarMinimoMedicamento('286' ,'3');
			actualizarMinimoMedicamento('287' ,'3');
			actualizarMinimoMedicamento('288' ,'0');
			actualizarMinimoMedicamento('540' ,'0');
			actualizarMinimoMedicamento('289' ,'0');
			actualizarMinimoMedicamento('553' ,'0');
			actualizarMinimoMedicamento('290' ,'0');
			actualizarMinimoMedicamento('291' ,'0');
			actualizarMinimoMedicamento('292' ,'0');
			actualizarMinimoMedicamento('293' ,'10');
			actualizarMinimoMedicamento('294' ,'0');
			actualizarMinimoMedicamento('295' ,'0');
			actualizarMinimoMedicamento('297' ,'30');
			actualizarMinimoMedicamento('298' ,'30');
			actualizarMinimoMedicamento('593' ,'0');
			actualizarMinimoMedicamento('533' ,'0');
			actualizarMinimoMedicamento('596' ,'0');
			actualizarMinimoMedicamento('299' ,'0');
			actualizarMinimoMedicamento('302' ,'10');
			actualizarMinimoMedicamento('300' ,'30');
			actualizarMinimoMedicamento('301' ,'0');
			actualizarMinimoMedicamento('303' ,'0');
			actualizarMinimoMedicamento('307' ,'5');
			actualizarMinimoMedicamento('406' ,'30');
			actualizarMinimoMedicamento('471' ,'30');
			actualizarMinimoMedicamento('463' ,'30');
			actualizarMinimoMedicamento('515' ,'30');
			actualizarMinimoMedicamento('592' ,'0');
			actualizarMinimoMedicamento('508' ,'20');
			actualizarMinimoMedicamento('527' ,'20');
			actualizarMinimoMedicamento('502' ,'20');
			actualizarMinimoMedicamento('494' ,'20');
			actualizarMinimoMedicamento('491' ,'0');
			actualizarMinimoMedicamento('308' ,'10');
			actualizarMinimoMedicamento('309' ,'100');
			actualizarMinimoMedicamento('604' ,'0');
			actualizarMinimoMedicamento('312' ,'2');
			actualizarMinimoMedicamento('407' ,'200');
			actualizarMinimoMedicamento('437' ,'0');
			actualizarMinimoMedicamento('420' ,'300');
			actualizarMinimoMedicamento('496' ,'0');
			actualizarMinimoMedicamento('441' ,'3');
			actualizarMinimoMedicamento('314' ,'30');
			actualizarMinimoMedicamento('499' ,'3');
			actualizarMinimoMedicamento('495' ,'0');
			actualizarMinimoMedicamento('477' ,'0');
			actualizarMinimoMedicamento('62' ,'0');
			actualizarMinimoMedicamento('414' ,'0');
			actualizarMinimoMedicamento('32' ,'5');
			actualizarMinimoMedicamento('315' ,'0');
			actualizarMinimoMedicamento('316' ,'2');
			actualizarMinimoMedicamento('317' ,'5');
			actualizarMinimoMedicamento('318' ,'0');
			actualizarMinimoMedicamento('381' ,'0');
			actualizarMinimoMedicamento('485' ,'200');
			actualizarMinimoMedicamento('551' ,'0');
			actualizarMinimoMedicamento('602' ,'0');
			actualizarMinimoMedicamento('448' ,'0');
			actualizarMinimoMedicamento('320' ,'0');
			actualizarMinimoMedicamento('319' ,'20');
			actualizarMinimoMedicamento('620' ,'0');
			actualizarMinimoMedicamento('332' ,'30');
			actualizarMinimoMedicamento('451' ,'0');
			actualizarMinimoMedicamento('486' ,'0');
			actualizarMinimoMedicamento('333' ,'0');
			actualizarMinimoMedicamento('465' ,'0');
			actualizarMinimoMedicamento('334' ,'0');
			actualizarMinimoMedicamento('337' ,'3');
			actualizarMinimoMedicamento('338' ,'0');
			actualizarMinimoMedicamento('339' ,'0');
			actualizarMinimoMedicamento('340' ,'0');
			actualizarMinimoMedicamento('454' ,'0');
			actualizarMinimoMedicamento('432' ,'0');
			actualizarMinimoMedicamento('550' ,'0');
			actualizarMinimoMedicamento('439' ,'0');
			actualizarMinimoMedicamento('344' ,'0');
			actualizarMinimoMedicamento('345' ,'0');
			actualizarMinimoMedicamento('615' ,'0');
			actualizarMinimoMedicamento('346' ,'2');
			actualizarMinimoMedicamento('347' ,'0');
			actualizarMinimoMedicamento('348' ,'30');
			actualizarMinimoMedicamento('349' ,'0');
			actualizarMinimoMedicamento('350' ,'0');
			actualizarMinimoMedicamento('352' ,'1');
			actualizarMinimoMedicamento('353' ,'0');
			actualizarMinimoMedicamento('354' ,'0');
			actualizarMinimoMedicamento('356' ,'0');
			actualizarMinimoMedicamento('355' ,'0');
			actualizarMinimoMedicamento('435' ,'0');
			actualizarMinimoMedicamento('357' ,'0');
			actualizarMinimoMedicamento('526' ,'0');
			actualizarMinimoMedicamento('358' ,'0');
			actualizarMinimoMedicamento('525' ,'0');
			actualizarMinimoMedicamento('603' ,'6');
			actualizarMinimoMedicamento('359' ,'300');
			actualizarMinimoMedicamento('552' ,'0');
			actualizarMinimoMedicamento('436' ,'0');
			actualizarMinimoMedicamento('360' ,'30');
			actualizarMinimoMedicamento('362' ,'0');
			actualizarMinimoMedicamento('361' ,'0');
			actualizarMinimoMedicamento('504' ,'0');
			actualizarMinimoMedicamento('386' ,'0');
			actualizarMinimoMedicamento('583' ,'0');
			actualizarMinimoMedicamento('487' ,'0');
			actualizarMinimoMedicamento('374' ,'30');
			actualizarMinimoMedicamento('375' ,'0');
			actualizarMinimoMedicamento('445' ,'0');
			actualizarMinimoMedicamento('376' ,'0');
			actualizarMinimoMedicamento('589' ,'0');
			actualizarMinimoMedicamento('377' ,'0');
			actualizarMinimoMedicamento('378' ,'0');
			actualizarMinimoMedicamento('379' ,'10');
			actualizarMinimoMedicamento('409' ,'0');
			actualizarMinimoMedicamento('492' ,'0');

			
			
			
			
			
	
			
	
		}
		
		
		
		if($_GET['tabla']="Suministros") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla Consumo.CodProductos (Farmacia) </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG007' class = 'link1'> Panel de Administracion </a> </span>";
			migrarFarmacia();
			
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de Farmacia</p> </div>";
		
					   
			
				
				$totalMySQL = contarRegistrosMySQL();
				$totalPostgresql =  contarRegistrosPostgresql();
				$totalPostgresqlErrores =  contarRegistrosPostgresqlErrores();
				
				echo "<p class= 'subtitulo1'> Total registros MySQL:</p>";
				echo  $totalMySQL."<br/>";
				echo "<p class= 'subtitulo1'> Total registros Postgresql migrados:</p>";
				echo  $totalPostgresql."<br/>";
				echo "<p class= 'error1'> Total errores generados(Tabla Consumo.CodproductosMigracion2):</p>";
				echo  $totalPostgresqlErrores."<br/>";
				
				echo "<p> <a href='reporteFarmacia.html' class = 'link1' target='_blank'> Ver Reporte de errores  Farmacia </a> </p>";
				
				echo "<p> <a href='../Lotes/ErroresLotes.html' class = 'link1' target='_blank'> Ver Reporte de errores Lotes </a> </p><br/>";
				
				echo "<span align='right'> <a href='revertir.php?accion=revertirMigracion' class = 'link1'> Revertir Migracion Farmacia </a> </span>";
				

		}	
		
		
		
	
	
	
	?>

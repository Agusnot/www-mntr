	<html>	
		<head>
			<title> Migracion Contabilidad.FacturasCredito </title>
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../../General/funciones/funciones.php');
		include_once('../../Conexiones/conexion.php');
		include_once('../DetalleLiquidacion/procedimiento.php');
		
		
		
		
		/* Inicia definicion de funciones */
		
			
		function  normalizarCodificacion($cadenaBusqueda,$cadenaReemplazo)  {
		// Busca y reemplaza ocurrencias en una tabla
			$cnx= conectar_postgres();
			$cons = "UPDATE Facturacion.DetalleFactura SET Compania = replace( compania,'$cadenaBusqueda','$cadenaReemplazo'), usuario = replace( usuario,'$cadenaBusqueda','$cadenaReemplazo'), nombre = replace( nombre,'$cadenaBusqueda','$cadenaReemplazo')";
			
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
				}

		}
		
		
		function  definirFechaCreacion($nofactura)  {
		// Busca la fecha de creacion en la tabla Facturacion.	FacturasCredito
			$cnx= conectar_postgres();
			$cons = "SELECT fechacrea FROM Facturacion.FacturasCredito WHERE nofactura = '$nofactura'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			$fila = @pg_fetch_array($res);			
			$fechacrea = $fila["fechacrea"];
			
			return $fechacrea;

		}
		
		
		function  definirUsuarioCreacion($nofactura)  {
		// Busca la fecha de creacion en la tabla Facturacion.	FacturasCredito
			$cnx= conectar_postgres();
			$cons = "SELECT usucrea FROM Facturacion.FacturasCredito WHERE nofactura = '$nofactura'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
			$fila = @pg_fetch_array($res);			
			$usucrea = $fila["usucrea"];
			
			return $usucrea;

		}
		
		

		
		
		
		
				
	
		function llamarRegistrosMySQL() {
			// Selecciona los registros MySQL (Origen)
			global $res;
			$cnx = conectar_mysql("Facturacion");
			//$cons = "SELECT *  FROM Facturacion.DetalleFactura  ORDER BY NoFactura ";			
			$cons ="SELECT * FROM Facturacion.DetalleFactura WHERE NoFactura IN (SELECT DISTINCT(NoFactura) FROM Facturacion.FacturasCredito WHERE NombreEntidad LIKE '%COSMITET%')";
			$res =  mysql_query($cons);
			return $res; 
		
		}
		
		
		function eliminarTablaMigracion() {
		
			$cnx= conectar_postgres();
			$cons = "DROP  TABLE facturacion.detallefacturaMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					
					
				}
			
		}
		
		
		
		
		function crearTablaMigracion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE facturacion.detallefacturaMigracion (  compania character varying(80) ,  usuario character varying(100),  fechacrea timestamp without time zone,  grupo character varying(100),  tipo character varying(50),  codigo character varying(30) ,  nombre text ,  cantidad double precision,  vrunidad double precision,  vrtotal double precision,  nofactura integer,  generico character varying(100),  presentacion character varying(140),  forma character varying(40),  almacenppal character varying(100),  autoid serial ,  phpcreadetfac character varying(100),  cum character varying(100) , error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					//echo "<br><br>";			
					
				}
			
		}
		
		function insertarDetalleFacturaMigracion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$nofactura,$generico,$presentacion,$forma,$almacenppal,$autoid,  $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.DetalleFacturaMigracion (Compania,Usuario,FechaCrea,Grupo,Tipo,Codigo,Nombre,Cantidad,VrUnidad,VrTotal,Nofactura,Generico,Presentacion,Forma,Almacenppal,Autoid, error ) VALUES ('$compania','$usuario','$fechacrea','$grupo','$tipo','$codigo','$nombre','$cantidad','$vrunidad','$vrtotal','$nofactura','$generico','$presentacion','$forma','$almacenppal','$autoid',  '$error')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$fp = fopen("ReporteDetalleFactura.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

				
		}
		
		
		function crearArchivo() {
			$fp = fopen("ReporteDetalleFactura.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Facturacion.FacturasCredito </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}	
		
		
		
		

		
		function insertarDetalleFactura($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$nofactura,$generico,$presentacion,$forma,$almacenppal,$autoid ) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.DetalleFactura  (Compania,Usuario,FechaCrea,Grupo,Tipo,Codigo,Nombre,Cantidad,VrUnidad,VrTotal,Nofactura,Generico,Presentacion,Forma,Almacenppal,Autoid ) VALUES ('$compania','$usuario','$fechacrea','$grupo','$tipo','$codigo','$nombre','$cantidad','$vrunidad','$vrtotal','$nofactura','$generico','$presentacion','$forma','$almacenppal','$autoid')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarDetalleFacturaMigracion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$nofactura,$generico,$presentacion,$forma,$almacenppal,$autoid, $error);
							
							
						}
				
				}

				
		}
		
		
		function  llenarMatriz(){
		// Llena una matriz con el resultado de la consulta MySQL
			
			unset($matriz); 
			global  $res, $matriz;	
			$res = llamarRegistrosMySQL();
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{	
					
					$matriz["tipo"][$posicion] = $fila["TipoServicio"];
					$matriz["codigo"][$posicion] = $fila["CUP"];
					$matriz["nombre"][$posicion] = $fila["Nombre"];															
					$matriz["cantidad"][$posicion] = $fila["Cantidad"];
					$matriz["vrunidad"][$posicion] = $fila["VrUnidad"];	
					$matriz["vrtotal"][$posicion] = $fila["VrTotal"];					
					$matriz["nofactura"][$posicion] = $fila["NoFactura"];	
					$matriz["presentacion"][$posicion] = $fila["Concentracion"];
					
											
					$posicion++;				
				}
				
				
			}
			

			
			function recorrerMatriz()  {
			// Recorre la matriz con los valores MySQL  inserta los valores en Postgresql
			
				global $res,$matriz;
					
									
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					
					$compania= $_SESSION["compania"];
					
					$nofactura = $matriz["nofactura"][$pos] ;	
					
					$noliquidacion = $nofactura;
					
					$fechacrea = definirFechaCreacion($nofactura);
						if (trim($fechacrea) == ''){
							$fechacrea = 'NULL';
						}
					
					$usuario = definirUsuarioCreacion($nofactura);
					
					$grupo = "";
					
					
					$tipo = $matriz["tipo"][$pos] ;	
					$codigo = $matriz["codigo"][$pos] ;	
					$cantidad = $matriz["cantidad"][$pos] ;	
					$vrunidad = $matriz["vrunidad"][$pos] ;					
					$vrtotal = $matriz["vrtotal"][$pos] ;
					$nombre  = $matriz["nombre"][$pos] ;
					$nombre = eliminarcaracteresEspeciales($nombre);
					$presentacion  = $matriz["presentacion"][$pos] ;
					$nofacturable = 0;
					
					$forma  = $matriz["forma"][$pos] ;
					
					$almacenppal =  "FARMACIA";
					$autoid = $pos + 1;
					
					// Realiza una inserción en la tabla Facturacion.DetalleLiquidacion con base en la informacion de la factura
					insertarDetalleLiquidacion($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$noliquidacion,$generico,$presentacion,$forma,$almacenppal,$cum, $nofacturable);
					
					insertarDetalleFactura($compania,$usuario,$fechacrea,$grupo,$tipo,$codigo,$nombre,$cantidad,$vrunidad,$vrtotal,$nofactura,$generico,$presentacion,$forma,$almacenppal,$autoid );
					
									
					}
							
			}
			
			function eliminarDetalleFactura() {
				$cnx= conectar_postgres();
				$cons= "DELETE FROM Facturacion.DetalleFactura";
				$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
						}
					
			}
			
			
			
			
		
		
		function migrarFacturasCredito() {
			
			crearArchivo();
			crearArchivoDetalleLiquidacion();
			eliminarTablaMigracion();			
			crearTablaMigracion();
			eliminarTablaMigracionDetalleLiquidacion();
			crearTablaMigracionDetalleLiquidacion();
			eliminarDetalleFactura();
			eliminarDetalleLiquidacion();
			llamarRegistrosMySQL();
			llenarMatriz();
			recorrerMatriz();
			normalizarCodificacion('&Aacute;', utf8_encode("Á"));			
			normalizarCodificacion('&Eacute;', utf8_encode("É"));
			normalizarCodificacion('&Iacute;', utf8_encode("Í"));
			normalizarCodificacion('&Oacute;', utf8_encode("Ó"));
			normalizarCodificacion('&Uacute;',utf8_encode("Ú"));
			normalizarCodificacion('&Ntilde;',utf8_encode("Ñ"));
			echo "<div align='center'> <p class='mensajeFinalizacion'>Ha terminado la migracion de la tabla Facturacion.DetalleFactura </p> </div>";
	
		}
		
		
		if($_GET['tabla']=="DetalleFactura") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion Tabla Facturacion.DetalleFactura </legend>";
			echo "<br>";
			echo "<span align='left'> <a href='../../index.php?migracion=MIG067' class = 'link1'> Panel de Administracion </a> </span>";
			echo "<br>";
			
			echo "<br/> <br/>  ";
			echo "<span align='right'> <a href='ReporteDetalleFactura.html' target='_blank' class = 'link1'> Ver Reporte de Errores Facturacion.FacturasCredito</a> </span>";			
			
			
			
			echo "<br/> <br/> ";
			echo "<span align='right'> <a href='../DetalleLiquidacion/DetalleLiquidacion.html' target='_blank' class = 'link1'> Ver Reporte de Errores Liquidacion</a> </span>";			
			echo "<br/> <br/>";	
			
			migrarFacturasCredito();
			
			echo "</fieldset>";
			
		}
		
		
		
		
		
	
	
	
	?>

	<html>	
		<head>
			<title> Migracion Facturacion.Liquidacion </title>
			<link rel="stylesheet" type="text/css" href="../General/estilos/estilos.css">
		</head>
	
	
	<?php
		session_start();
		include_once('../General/funciones/funciones.php');	
		
		
		/* Inicia definicion de funciones */			
	

		
		function eliminarLiquidacion() {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "DELETE FROM Facturacion.Liquidacion";
					 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
							echo  "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				}

		}
		
		
		function insertarLiquidacionMigracion($compania,$fechacrea,$usuario,$fechaini,$fechafin,$contrato, $nocontrato,$ambito,$subtotal,$valorcopago,$valordescuento,$total,$nofactura,$cedula,$numservicio,$medicotte,$nocarnet,$tipousu,$nivelusu,$autorizac1,$autorizac2,$autorizac3,$noliquidacion,$porsentajecopago,$porsentajedesc,$tipocopago,$clasecopago,$pagador,$recaudo,$compcontablerecaudo,$motivonocopago,$tipofactura,$formatofurips,$parto,$phpcrealiq,$phpmodifliq,$estado,$usumod,$fechamod, $error) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.LiquidacionMigracion(compania,fechacrea,usuario,fechaini,fechafin, contrato,nocontrato,ambito,subtotal,valorcopago,valordescuento,total,nofactura,cedula,numservicio,medicotte,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,noliquidacion,porsentajecopago,porsentajedesc,tipocopago,clasecopago,pagador,recaudo,compcontablerecaudo,motivonocopago,tipofactura,formatofurips,parto,phpcrealiq,phpmodifliq,estado,usumod,fechamod, error) VALUES ('$compania' , '$fechacrea' , '$usuario' , '$fechaini' , '$fechafin'  ,'$contrato' , '$nocontrato' , '$ambito' , $subtotal , $valorcopago , $valordescuento , $total , $nofactura , '$cedula' , $numservicio , '$medicotte' , '$nocarnet' , '$tipousu' , '$nivelusu' , '$autorizac1' , '$autorizac2' , '$autorizac3' , $noliquidacion , $porsentajecopago , $porsentajedesc , '$tipocopago' , '$clasecopago' , '$pagador' , '$recaudo' , '$compcontablerecaudo' , '$motivonocopago' , '$tipofactura' , '$formatofurips' , $parto , '$phpcrealiq' ,
			 '$phpmodifliq' , '$estado' , '$usumod' , '$fechamod' , '$error')";
			
			
			$cons = str_replace( "'NULL'","NULL",$cons  ); 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							// Se asigna esa ruta por que esta funcion se llama desde el archivo Facturacion/FacturasCredito
							$fp = fopen("../Liquidacion/ReporteLiquidacion.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);
							
							
						}
				
				}

		}
		
		
		function insertarLiquidacion($compania,$fechacrea,$usuario,$fechaini,$fechafin,$contrato, $nocontrato,$ambito,$subtotal,$valorcopago,$valordescuento,$total,$nofactura,$cedula,$numservicio,$medicotte,$nocarnet,$tipousu,$nivelusu,$autorizac1,$autorizac2,$autorizac3,$noliquidacion,$porsentajecopago,$porsentajedesc,$tipocopago,$clasecopago,$pagador,$recaudo,$compcontablerecaudo,$motivonocopago,$tipofactura,$formatofurips,$parto,$phpcrealiq,$phpmodifliq,$estado,$usumod,$fechamod	) {
		//Realiza la insercion en Postgresql con base en los parametros
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Facturacion.Liquidacion(compania,fechacrea,usuario,fechaini,fechafin, contrato,nocontrato,ambito,subtotal,valorcopago,valordescuento,total,nofactura,cedula,numservicio,medicotte,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,noliquidacion,porsentajecopago,porsentajedesc,tipocopago,clasecopago,pagador,recaudo,compcontablerecaudo,motivonocopago,tipofactura,formatofurips,parto,phpcrealiq,phpmodifliq,estado,usumod,fechamod) VALUES ('$compania' , '$fechacrea' , '$usuario' , '$fechaini' , '$fechafin'  ,'$contrato' , '$nocontrato' , '$ambito' , $subtotal , $valorcopago , $valordescuento , $total , $nofactura , '$cedula' , $numservicio , '$medicotte' , '$nocarnet' , '$tipousu' , '$nivelusu' , '$autorizac1' , '$autorizac2' , '$autorizac3' , $noliquidacion , $porsentajecopago , $porsentajedesc , '$tipocopago' , '$clasecopago' , '$pagador' , '$recaudo' , '$compcontablerecaudo' , '$motivonocopago' , '$tipofactura' , '$formatofurips' , $parto , '$phpcrealiq' ,
			 '$phpmodifliq' , '$estado' , '$usumod' , '$fechamod')";
			
			$cons = str_replace( "'NULL'","NULL",$cons  );	 
			$res = @pg_query($cnx, $cons);
				if (!$res) {
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							$error = pg_last_error();
							insertarLiquidacionMigracion($compania,$fechacrea,$usuario,$fechaini,$fechafin, $contrato,$nocontrato,$ambito,$subtotal,$valorcopago,$valordescuento,$total,$nofactura,$cedula,$numservicio,$medicotte,$nocarnet,$tipousu,$nivelusu,$autorizac1,$autorizac2,$autorizac3,$noliquidacion,$porsentajecopago,$porsentajedesc,$tipocopago,$clasecopago,$pagador,$recaudo,$compcontablerecaudo,$motivonocopago,$tipofactura,$formatofurips,$parto,$phpcrealiq,$phpmodifliq,$estado,$usumod,$fechamod, $error);
							
						}
				
				}

		}
		
		
			function crearTablaMigracionLiquidacion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "CREATE TABLE IF NOT EXISTS facturacion.liquidacionMigracion(  compania character varying(80) ,  usuario character varying(100),  fechacrea timestamp without time zone,  ambito character varying(150),  medicotte character varying(200),  fechafin date,  fechaini date,  nocarnet character varying(150),  tipousu character varying(150),  nivelusu character varying(50),  autorizac1 character varying(500),  autorizac2 character varying(50),  autorizac3 character varying(50),  pagador character varying(200),
  contrato character varying(150),  nocontrato character varying(150),  noliquidacion integer ,  numservicio integer,  valorcopago double precision,  porsentajecopago double precision,  valordescuento double precision,  porsentajedesc double precision,  subtotal double precision,  total double precision,  tipocopago character varying(80),  clasecopago character varying(25),  cedula character varying(15),  usumod character varying(100),  fechamod timestamp without time zone,  estado character varying(2) ,  nofactura integer,  recaudo integer,  compcontablerecaudo character varying(50),
  motivonocopago text,  tipofactura character varying(300),  formatofurips character varying(1),  parto integer,  phpcrealiq character varying(200),  phpmodifliq character varying(200),  error text)WITH (  OIDS=FALSE)";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";*/		
					
				}
			
		}
		
		
		function eliminarTablaMigracionLiquidacion() {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "DROP TABLE Facturacion.LiquidacionMigracion";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					/*echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			*/
					
				}
			
		}
		
		
		function crearArchivoLiquidacion() {
			// Se asigna esa ruta por que esta funcion se llama desde el archivo Facturacion/FacturasCredito
			$fp = fopen("../Liquidacion/ReporteLiquidacion.html", "w+");
			$encabezado = "<html> <head> <title> Reporte errores Facturacion.Liquidacion </title> 
			<link rel='stylesheet' type='text/css' href='../../General/estilos/estilos.css'> </head>";
			fputs($fp, $encabezado);
			fclose($fp);
		}
		
		
		
		
		
		
		
		
		
		
		
		
	
	
	
	?>

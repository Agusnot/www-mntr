	
	<?php
	
	include_once('../../General/funciones/funciones.php');
	include_once('../../Conexiones/conexion.php');
	
	
	// Inician funciones de  modificacion de estructura
	
		
			
			
						
		function eliminarTablaFormato($tabla){
				$cnx = conectar_postgres();
				$cons= "DROP TABLE IF EXISTS HistoClinicaFrms.$tabla";	
	  			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}		
	
	
		/*function crearTablaFormato($tabla){
				$cnx = conectar_postgres();
				
				$cons= "CREATE TABLE histoclinicafrms.$tabla(  formato character varying(150) NOT NULL,  tipoformato character varying(150) NOT NULL,  id_historia integer NOT NULL DEFAULT 0,  usuario character varying(150) NOT NULL,  cargo character varying(80) NOT NULL,  fecha date,  hora time without time zone,  cedula character varying(15) NOT NULL,  ambito character varying(150),  unidadhosp character varying(150),  numservicio integer,  compania character varying(60) NOT NULL,  cerrado integer,  noliquidacion integer DEFAULT 0,  finalidadconsult character varying(5),  causaexterna character varying(5),  dx1 character varying,  dx2 character varying,  dx3 character varying,  dx4 character varying,  dx5 character varying,  tipodx character varying(1),   numproced integer,   CONSTRAINT pkhctbl$tabla PRIMARY KEY (formato , tipoformato , id_historia , cedula , compania ),  CONSTRAINT fkamb$tabla FOREIGN KEY (ambito, compania)      REFERENCES salud.ambitos (ambito, compania) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT,     CONSTRAINT fkpabx$tabla FOREIGN KEY (unidadhosp, compania, ambito)      REFERENCES salud.pabellones (pabellon, compania, ambito) MATCH SIMPLE      ON UPDATE CASCADE ON DELETE RESTRICT)WITH (  OIDS=FALSE) ";	
	  			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}*/	
		
		
			function eliminarColumna($tabla, $columna){
				$cnx = conectar_postgres();

				$cons= "ALTER TABLE histoclinicafrms.$tabla DROP COLUMN IF EXISTS $columna ";	
					
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
			
		function agregarColumna($tabla, $columna,$tipo, $tamano, $notnull, $default){
				$cnx = conectar_postgres();

				$cons= "ALTER TABLE histoclinicafrms.$tabla ADD COLUMN $columna $tipo $tamano";	
					if ($notnull == "TRUE"){
						$cons = $cons." NOT NULL ";
					} 
					
					if ($default != "FALSE"){
						$cons = $cons." DEFAULT '$default'";
					} 
					
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
		// Terminan funciones de  modificacion de estructura
		
		
		
		
		
		
		// Inicia definicion de funciones de modificacion de registros
				
		
		
		function insertarRegistroTablaFormatos($formato, $ajuste, $tipoformato, $usuariocre, $alineacion, $estado, $agruparxhospi, $cierrevoluntario, $compania, $tblformat, $formatoesterno, $paguinacion,  $seleccionable, $epicrisis, $formatoxml,  $ambitoformato, $cierraformatonopos, $pacienteseguro){
				$cnx = conectar_postgres();
				$cons= "INSERT INTO HistoriaClinica.formatos(formato, ajuste, tipoformato, usuariocre, alineacion, estado, agruparxhospi, cierrevoluntario, compania, tblformat, formatoesterno, paguinacion, seleccionable, epicrisis, formatoxml,  ambitoformato, cierraformatonopos, pacienteseguro) VALUES ('$formato', '$ajuste', '$tipoformato', '$usuariocre', '$alineacion', '$estado', '$agruparxhospi', '$cierrevoluntario', '$compania', '$tblformat', '$formatoesterno', $paguinacion, '$seleccionable', '$epicrisis', $formatoxml,  '$ambitoformato', '$cierraformatonopos', '$pacienteseguro')";	
	  			$cons = str_replace( "'NULL'","NULL",$cons  );
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}	
		
		
		function eliminarFormatos($tipoformato, $formato) {
		
				$cnx = conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.Formatos WHERE tipoformato = '$tipoformato' AND formato = '$formato'";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
		}
		
		function eliminarItemsxFormatos($tipoformato, $formato) {
		
				$cnx = conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.ItemsxFormatos WHERE tipoformato = '$tipoformato' AND formato = '$formato'";
				$res =  @pg_query($cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					}
			
		}
		
		
		
		

		
		function eliminarRegistroTablaFormato($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = "DELETE FROM HistoriaClinica.Formatos WHERE tipoformato = '$tipoformato'  AND formato = '$formato'";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function eliminarItemsxFormatosEspecifico($tipoformato, $formato){
				$cnx = conectar_postgres();
				$cons= "DELETE FROM HistoriaClinica.ItemsXFormatos WHERE tipoformato = '$tipoformato'  AND formato = '$formato'";	
				$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
			
		}
		
		
		
		function agregarItemxFormato($formato, $id_item, $item , $tipoformato, $pantalla, $tipodato, $liminf, $limsup, $longitud, $tipocontrol, $ancho, $alto, $obligatorio, $lineasola , $cierrafila,   $titulo, $compania , $subformato, $estado, $orden , $formatoxml, $etiqxml, $cargoxitem){
			$cnx = conectar_postgres();
			$cons = "INSERT INTO HistoriaClinica.ItemsxFormatos (formato, id_item, item , tipoformato,  pantalla, tipodato, liminf, limsup, longitud , tipocontrol, ancho, alto, obligatorio, lineasola, cierrafila,   titulo, compania, subformato, estado, orden, formatoxml, etiqxml, cargoxitem) VALUES ('$formato', $id_item, '$item' , '$tipoformato',   $pantalla ,'$tipodato', $liminf, $limsup, $longitud, '$tipocontrol', $ancho, $alto, $obligatorio, $lineasola, $cierrafila, '$titulo', '$compania' , '$subformato', '$estado', $orden, $formatoxml, '$etiqxml', '$cargoxitem')";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
		}
		
		function agregarParametrosItem($formato, $tipoformato, $id_item, $parametros){
			// Esta funcion agrega parametros a un item. Esto aplica para items que son lista de Opciones
			$cnx = conectar_postgres();
			$cons = "UPDATE HistoriaClinica.ItemsxFormatos SET parametro = '$parametros' WHERE formato = '$formato' AND tipoformato = '$tipoformato' AND id_item = '$id_item'";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
		}
		
		
		
		
		function insertarPermisosxFormatoSQL($formato, $perfil, $permiso, $tipoformato, $compania){
			$cnx = conectar_postgres();
			$cons = "INSERT INTO HistoriaClinica.PermisosxFormato (formato, perfil, permiso, tipoformato, compania) VALUES ('$formato' , '$perfil' , '$permiso' , '$tipoformato' , '$compania')";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function eliminarPermisosxFormato($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.PermisosxFormato WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		
		function eliminarAmbitosxFormato($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.AmbitosxFormato WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function insertarAmbitosxFormatoSQL($usuariocre, $fechacre, $tipoformato, $formato, $ambito, $disponible, $compania){
			$cnx = conectar_postgres();
			$cons = "INSERT INTO HistoriaClinica.AmbitosxFormato (usuariocre, fechacre, tipoformato, formato, ambito, disponible, compania)  VALUES ('$usuariocre', '$fechacre', '$tipoformato', '$formato', '$ambito', '$disponible', '$compania')";
			$cons = str_replace( "'NULL'","NULL",$cons);			
			
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		
		function eliminarAjustePermanente($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.AjustePermanente WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function insertarAjustePermanenteSQL($formato, $perfil, $permiso, $tipoformato, $compania){
			$cnx = conectar_postgres();
			$cons = " INSERT INTO HistoriaClinica.AjustePermanente(formato, perfil, permiso, tipoformato, compania) VALUES ('$formato', '$perfil', '$permiso', '$tipoformato', '$compania') ";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function seleccionarDiagnostico($diagnostico){
			$cnx = conectar_postgres();
			$cons = "SELECT  codigo FROM Salud.CIE WHERE diagnostico ILIKE '%$diagnostico%'";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {	
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);	
						if (!$resUTF8)	{			
							echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/>";
						}
				}
				if ($res){
					if (pg_num_rows($res) >= 0){
						$fila = pg_fetch_array($res);
						if (trim($fila["codigo"]) != "" ){
							$diagnostico = 	$fila["codigo"];
						}
					}
				
				}
			
			return $diagnostico; 
		}
		
		
		
		
		function eliminarDxFormatos($formato, $tipoformato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.DxFormatos WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function insertarDxFormatosSQL($compania, $usuario, $fecha, $id, $detalle, $tipo, $formato, $tipoformato, $estado, $pantalla, $iditem, $cie10, $tagxml, $etiquetaxml){
			$cnx = conectar_postgres();
			$cons = " INSERT INTO HistoriaClinica.DxFormatos(compania, usuario, fecha, id, detalle, tipo, formato, tipoformato, estado, pantalla, iditem, cie10, tagxml, etiquetaxml) VALUES ('$compania', '$usuario', '$fecha', $id, '$detalle', '$tipo', '$formato', '$tipoformato', '$estado', $pantalla, $iditem , '$cie10', '$tagxml', '$etiquetaxml') ";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function eliminarVoBoxFormatos($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.VoBoxFormatos WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		function insertarVoBoxFormatosSQL($usuariocre, $fechacre, $tipoformato, $formato, $cargo, $compania){
			$cnx = conectar_postgres();
			$cons = " INSERT INTO HistoriaClinica.VoBoxFormatos(usuariocre, fechacre, tipoformato, formato, cargo, compania) VALUES ('$usuariocre', '$fechacre', '$tipoformato', '$formato', '$cargo', '$compania') ";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarRegistroVoBoxFormatos($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.RegistroVoBoxFormatos WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function insertarRegistroVoBoxFormatos($usuario, $fechavobo, $tipoformato, $formato, $cargo, $compania, $idhistoria){
			$cnx = conectar_postgres();
			$cons = " INSERT INTO HistoriaClinica.RegistroVoBoxFormatos(usuario, fechavobo, tipoformato, formato, cargo, compania, idhistoria) VALUES ('$usuario', '$fechavobo', '$tipoformato', '$formato', '$cargo', '$compania', '$idhistoria') ";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
					$consUTF8 = utf8_encode($cons);
					$resUTF8 = @pg_query($cnx, $consUTF8);					
						if (!$resUTF8) {
							
							$fp = fopen("../Errores/HistoriaClinicaFrms.html", "a+");	
							$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
							$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$consUTF8."<br/> <br/> <br/>";  
							fputs($fp, $errorEjecucion);
							fputs($fp, $consulta);
							fclose($fp);							
						}
				}
		}
		
		function eliminarCupsxFormatos($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE  FROM  HistoriaClinica.CupsxFormatos WHERE tipoformato = '$tipoformato' AND formato = '$formato'";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {	
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
							
					
				}
	
		}
		
		
		
		function insertarCupsxFormatosSQL($usuariocre, $fechacre, $tipoformato, $formato, $cargo, $vritem, $cup, $compania, $item){
			$cnx = conectar_postgres();
			$cons = " INSERT INTO HistoriaClinica.CupsxFormatos(usuariocre, fechacre, tipoformato, formato, cargo, vritem, cup, compania, item) VALUES ('$usuariocre', '$fechacre', '$tipoformato', '$formato', '$cargo', '$vritem', '$cup', '$compania', '$item') ";
			$cons = str_replace( "'NULL'","NULL",$cons  );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function actualizarMedico($esquema, $tabla , $campo, $valorBusqueda, $valorReemplazo){
			$cnx = conectar_postgres();
			$cons = "UPDATE $esquema.$$tabla SET $campo = '$valorBusqueda' WHERE $campo = '$valorReemplazo' ";
			$cons = str_replace( "'NULL'","NULL",$cons );
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function actualizarRequiereServicio($formato, $tipoformato){
			$cnx = conectar_postgres();
			$cons = "UPDATE HistoriaClinica.Formatos SET reqAmbito = 'Si' WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
				echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		function eliminarIndicadoresxHC($tipoformato, $formato){
			$cnx = conectar_postgres();
			$cons = " DELETE FROM HistoriaClinica.IndicadoresxHC WHERE formato = '$formato' AND tipoformato = '$tipoformato' ";
			$res = @pg_query($cnx,$cons);		
				if (!$res) {							
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
	
				}
	
		}
		
		
		
		function seleccionarPabellon($cedula, $fecha) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "SELECT pabellon FROM Salud.pacientesxpabellones WHERE Cedula = '$cedula' AND  fechaI <= '$fecha' AND fechaE >= '$fecha'";	
			$res = @pg_query($cnx, $cons);
			
				if (!$res) {
				
					//echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					//echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					//echo "<br><br>";			
					//echo "<br><br>";
				}
				if ($res){
				
					$fila = @pg_fetch_array($res);
					$pabellon = $fila["pabellon"];
				}
			
			return $pabellon;		
			
		}
		
		
		function seleccionarIdHistoria($identificacion) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "SELECT numha FROM Central.Terceros WHERE Identificacion = '$identificacion'";	
			$res = @pg_query($cnx, $cons);
			
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
				if ($res){
				
					$fila = @pg_fetch_array($res);
					$idhistoria = $fila["numha"];
				}
			
			return $idhistoria;		
			
		}
		
		
		function seleccionarAmbito($pabellon) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = "SELECT ambito FROM Salud.Pabellones WHERE Pabellon = '$pabellon'";	
			$res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					
				}
				
				if ($res){
				
					$fila = @pg_fetch_array($res);
					$ambito = $fila["ambito"];
				}
			
			return $ambito;	
			
		}
		
		
		function seleccionarServicio($cedula, $fecha) {
		// Esta funcion crea una tabla con estructura similar a la tabla Postgresql con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			
			$cnx= conectar_postgres();
			$cons = "SELECT * FROM Salud.Servicios WHERE Cedula = '$cedula' AND  fechaing <= '$fecha' AND fechaegr >= '$fecha' ";	
			
			$res = @pg_query($cnx, $cons);
			
				if (!$res) {
						global $tablafrm;
						$archivo = "Errores".$tablafrm.".html";
						$fp = fopen("$archivo", "a+");	
						$errorEjecucion= "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						$consulta= "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
						fputs($fp, $errorEjecucion);
						fputs($fp, $consulta);
						fclose($fp);
				
					
				}
				if ($res){
				
					$fila = @pg_fetch_array($res);
					$numservicio = $fila["numservicio"];
				}
			
			return $numservicio;		
			
		}
		
		
		
		function eliminarCaracteresEspFormatos($cadena)
		
		{	$cadena = strtoupper($cadena);
			$cadena= str_replace( ";","", $cadena);
			$cadena= str_replace("Á","&Aacute;",$cadena);
			$cadena= str_replace("À","&Aacute;",$cadena);
			$cadena= str_replace("Â","A",$cadena);
			$cadena= str_replace("Ã","A",$cadena);
			$cadena= str_replace("Ä","A",$cadena);
			$cadena= str_replace("A³","A", $cadena);
			$cadena= str_replace("Ã³","o", $cadena);
			$cadena= str_replace("Ã­","o", $cadena);
			$cadena= str_replace("A‘","", $cadena);
			$cadena= str_replace("á","&aacute;",$cadena);
			$cadena= str_replace("à","&aacute;",$cadena);
			$cadena= str_replace("ã","a",$cadena);
			$cadena= str_replace("â","a",$cadena);
			$cadena= str_replace("É","&Eacute;",$cadena);
			$cadena= str_replace("È","&Eacute;",$cadena);
			$cadena= str_replace("é","&eacute;",$cadena);
			$cadena= str_replace("è","&eacute;",$cadena);
			$cadena= str_replace("Í","&Iacute;",$cadena);
			$cadena= str_replace("Ì","&Iacute;",$cadena);
			$cadena= str_replace("í","&iacute;",$cadena);
			$cadena= str_replace("ì","&iacute;",$cadena);
			$cadena= str_replace("Ó","&Oacute;",$cadena);
			$cadena= str_replace("Ò","&Oacute;",$cadena);
			$cadena= str_replace("ó","&oacute;",$cadena);
			$cadena= str_replace("ò","&oacute;",$cadena);
			$cadena= str_replace("Ú","&Uacute;",$cadena);
			$cadena= str_replace("Ù","&Uacute;",$cadena);
			$cadena= str_replace("ú","&uacute;",$cadena);
			$cadena= str_replace("ù","&uacute;",$cadena);
			$cadena= str_replace("ñ","&ntilde;",$cadena);
			$cadena= str_replace("Ñ","&Ntilde;",$cadena);
			$cadena= str_replace("¿","&iquest;",$cadena);
			//$cadena= str_replace("?","",$cadena);
			$cadena= str_replace("A¡","a",$cadena);
			//$cadena= str_replace("/","",$cadena);
			$cadena= str_replace( "ç","", $cadena);			
			$cadena= str_replace( "ß","", $cadena);
			//$cadena= str_replace( "‘","", $cadena);
			//$cadena= str_replace( ",","", $cadena);
			$cadena= str_replace( "'",'"', $cadena);
			//$cadena= str_replace( '"',"", $cadena);
			$cadena = trim($cadena);
			$cadena = stripslashes($cadena);
			return $cadena;
		
		}
		
		
		function configurarRM($usuario, $rm, $cargo){
			
			if (!empty($rm)){
				if ($cargo == "PSIQUIATRA" or $cargo == "MEDICO RESIDENTE" or $cargo == "RESIDENTE" or $cargo == "MEDICO GENERAL" or  $cargo == "MEDICO RURAL" ){
					$usuario_rm = $usuario." Reg. MD: ".$rm;
				}
				else {
					$usuario_rm = $usuario." Cod: ".$rm;
				}
			} 
			else {
				$usuario_rm = $usuario;
			}
			
				
			return $usuario_rm;
		
		}
		
		
		function consultarRegMedMySQL($usuario){
			$usuario = strtoupper($usuario);
			//$cnx = conectar_mysql("Salud");
			$cons = "SELECT RM FROM Salud.DatosMedicos WHERE UPPER(Nombre) = '$usuario'";
			$res =  @mysql_query($cons);
			if (!$res)  {
				echo "<p class='error1'> Error  SQL </p>".mysql_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando  </p> <br>".$cons."<br/>";
			}
			$fila = @mysql_fetch_array($res);
			$rm = $fila["RM"];
			
			return $rm;
		}
		
		
		function consultarCargoMedMySQL($usuario){
			$usuario = strtoupper($usuario);
			
			//$cnx = conectar_mysql("Salud");
			$cons = "SELECT cargo FROM Salud.DatosMedicos WHERE UPPER(Nombre) = '$usuario'";	
			
			$res =  @mysql_query($cons);
			if (!$res)  {
				echo "<p class='error1'> Error  SQL </p>".mysql_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando  </p> <br>".$cons."<br/>";
			}
			$fila = @mysql_fetch_array($res);
			$cargo = $fila["cargo"];
			
			return $cargo;
		}
		
		
		function consultarDetFormatoPOS($autoid, $cedula){
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT * FROM Salud.DetFormatoPOS WHERE autoid = '$autoid' AND cedula = '$cedula'";
			$res =  @mysql_query($cons);
			if (!$res)  {
				echo "<p class='error1'> Error  SQL </p>".mysql_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando  </p> <br>".$cons."<br/>";
			}
			
			return $res;		
		}
		
		
		function consultarDetFormatoNoPOS($autoid, $cedula){
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT * FROM Salud.DetFormatoNoPOS WHERE autoid = '$autoid' AND cedula = '$cedula'";				
			$res =  @mysql_query($cons);
			if (!$res)  {
				echo "<p class='error1'> Error  SQL </p>".mysql_error()."<br>";
				echo "<p class= 'subtitulo1'>Comando  </p> <br>".$cons."<br/>";
			}
			
			return $res;
		
		
		}
		
		function normalizarValorCheckbox($valor){
			if($valor == 0){
				$valor = "No";
			}
			if($valor == 1){
				$valor = "Si";
			}
			
			return $valor;
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Termina definicion de funciones de modificacion de registros
		



		?>


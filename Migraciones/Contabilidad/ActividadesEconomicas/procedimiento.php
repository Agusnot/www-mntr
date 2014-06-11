	<html>
		<head>
			<title> Migracion Contabilidad.Actividades Economicas </title> 
			<link rel="stylesheet" type="text/css" href="../../General/estilos/estilos.css">
			<meta charset="UTF-8">
		</head>
	</html>	
	


<?php


	include_once('../General/funciones/funciones.php');

	/* Inicia la definicion de funciones */
		
		
		function eliminarConstraint($nombreConstraint) {
				$cnx = 	conectar_postgres();
				$cons= 'ALTER TABLE Central.Terceros DROP CONSTRAINT IF EXISTS"'.$nombreConstraint.'"';
				$res = @pg_query($cnx, $cons);
				
				
		}	

		
	
		function crearFKactividadEconomica() {
			$cnx = conectar_postgres();
			$cons = 'ALTER TABLE central.terceros ADD CONSTRAINT fk_actividadeseconomicas FOREIGN KEY (compania, codactividadeconomica) REFERENCES contabilidad.actividadeseconomicas (compania, codigo) ON UPDATE CASCADE ON DELETE RESTRICT;
';
			$res = @pg_query($cnx, $cons);
				if (!$res){
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";  
				
				}
			
		}
	
		
		function crearTablaActividadesEconomicas() {
		// Esta funcion crea una tabla con estructura similar a la tabla Contabilidad.movimiento, con la diferencia que carece de llave primaria y omite la restriccion NOT NULL,          ademas  crea un nuevo campo llamado error en el cual se almacena la descripcion del error generado en la consulta inicial.
			$cnx= conectar_postgres();
			$cons = 'CREATE TABLE IF NOT EXISTS  contabilidad.actividadeseconomicas(  compania character varying NOT NULL,  codigo character varying NOT NULL,  descripcion text NOT NULL,  tarifaretencion double precision NOT NULL,  CONSTRAINT "pk_ActividadesEconomicas" PRIMARY KEY (compania, codigo ))WITH (  OIDS=FALSE)';	
			 $res = @pg_query($cnx, $cons);
				if (!$res) {
				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
					//echo "<br><br>";
				}
			
		}
	

			
			
	
			
		function insertarActividadesEconomicas() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "COPY Contabilidad.ActividadesEconomicas  FROM '$ruta/Migraciones/Contabilidad/ActividadesEconomicas/ActividadesEconomicas.csv' WITH DELIMITER ';' CSV HEADER;";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
		function eliminarActividadesEconomicas() {
		
			$cnx = conectar_postgres();
			$ruta = $_SERVER['DOCUMENT_ROOT'];
			$cons= "DELETE FROM Contabilidad.ActividadesEconomicas";
			$res =  @pg_query($cons);
				if (!$res) {
								echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
								echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}
		}
		
			
			
		function  normalizarActividadesEconomicas($cadenaBusqueda,$cadenaReemplazo)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Contabilidad.ActividadesEconomicas SET descripcion = replace( descripcion,'$cadenaBusqueda','$cadenaReemplazo')";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error en la normalizacion de la codificacion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}	
		
		
		
		function  actualizarActividadEconomica($identificacion,$actividad)  {
			$cnx= conectar_postgres();
			$cons = "UPDATE Central.Terceros SET codactividadeconomica = '$actividad' WHERE Identificacion = '$identificacion'";
			$res = pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de Ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/>";
				}

		}	
			
		
	

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
		function migrarActividadesEconomicas($paso) {
			
			crearTablaActividadesEconomicas();
			eliminarActividadesEconomicas();
			insertarActividadesEconomicas();
			eliminarConstraint("fk_actividadeseconomicas");
			crearFKactividadEconomica();

			normalizarActividadesEconomicas('&Aacute&', utf8_encode("Á"));			
			normalizarActividadesEconomicas('&Eacute&', utf8_encode("É"));
			normalizarActividadesEconomicas('&Iacute&', utf8_encode("Í"));
			normalizarActividadesEconomicas('&Oacute&', utf8_encode("Ó"));
			normalizarActividadesEconomicas('&Uacute&',utf8_encode("Ú"));
			normalizarActividadesEconomicas('&Ntilde&',utf8_encode("Ñ"));
			actualizarActividadEconomica("900176990-7" ,"7310");
			actualizarActividadEconomica("42105245-2" ,"3290");
			actualizarActividadEconomica("830139989-8" ,"2100");
			actualizarActividadEconomica("10240675-7" ,"7110");
			actualizarActividadEconomica("10271281-1" ,"4741");
			actualizarActividadEconomica("900262313-9" ,"8699");
			actualizarActividadEconomica("10258807-1" ,"9522");
			actualizarActividadEconomica("890806999-1" ,"4663");
			actualizarActividadEconomica("816001182-7" ,"4645");
			actualizarActividadEconomica("890100577-6" ,"5111");
			actualizarActividadEconomica("810001366-3" ,"8121");
			actualizarActividadEconomica("830014222-0" ,"4659");
			actualizarActividadEconomica("10288932-2" ,"7310");
			actualizarActividadEconomica("800236101-1" ,"4649");
			actualizarActividadEconomica("800186656-1" ,"1392");
			actualizarActividadEconomica("10231760-7" ,"6910");
			actualizarActividadEconomica("75078903-4" ,"2599");
			actualizarActividadEconomica("51846440-4" ,"4759");
			actualizarActividadEconomica("900367626-0" ,"4631");
			actualizarActividadEconomica("900342297-2" ,"4771");
			actualizarActividadEconomica("75065498-6" ,"4754");
			actualizarActividadEconomica("900273686-8" ,"4711");
			actualizarActividadEconomica("900309720-8" ,"7110");
			actualizarActividadEconomica("10227058-9" ,"4773");
			actualizarActividadEconomica("30271373-3" ,"1410");
			actualizarActividadEconomica("800150640-9" ,"7020");
			actualizarActividadEconomica("24340681-1" ,"5619");
			actualizarActividadEconomica("800136505-4" ,"4659");
			actualizarActividadEconomica("75085154-3" ,"4719");
			actualizarActividadEconomica("816007789-4" ,"4649");
			actualizarActividadEconomica("800182800-8" ,"4645");
			actualizarActividadEconomica("16079229-8" ,"4774");
			actualizarActividadEconomica("900526319-7" ,"7310");
			actualizarActividadEconomica("890800234-9" ,"1811");
			actualizarActividadEconomica("816004007-1" ,"4690");
			actualizarActividadEconomica("900530917-7" ,"7020");
			actualizarActividadEconomica("10233543-4" ,"4759");
			actualizarActividadEconomica("891409291-7" ,"4645");
			actualizarActividadEconomica("30332442-6" ,"8020");
			actualizarActividadEconomica("24325367-0" ,"4663");
			actualizarActividadEconomica("860007538-2" ,"1061");
			actualizarActividadEconomica("890806646-7" ,"1020");
			actualizarActividadEconomica("10247233-7" ,"1812");
			actualizarActividadEconomica("4470014-3" ,"1051");
			actualizarActividadEconomica("30398476-1" ,"8219");
			actualizarActividadEconomica("811038881-9" ,"2100");
			actualizarActividadEconomica("810004858-9" ,"7310");
			actualizarActividadEconomica("830510574-5" ,"4741");
			actualizarActividadEconomica("800244270-1" ,"3250");
			actualizarActividadEconomica("900489515-5" ,"7110");
			actualizarActividadEconomica("900221029-6" ,"4759");
			actualizarActividadEconomica("900488526-1" ,"4645");
			actualizarActividadEconomica("800018165-8" ,"6621");
			actualizarActividadEconomica("1295112-8" ,"7110");
			actualizarActividadEconomica("900096245-4" ,"4741");
			actualizarActividadEconomica("900213759-0" ,"4610");
			actualizarActividadEconomica("24327272-9" ,"4723");
			actualizarActividadEconomica("860074358-9" ,"2100");
			actualizarActividadEconomica("900561068-1" ,"8691");
			actualizarActividadEconomica("860005114-4" ,"2011");
			actualizarActividadEconomica("10226376-1" ,"161");
			actualizarActividadEconomica("900411384-1" ,"1410");
			actualizarActividadEconomica("30338415" ,"7020");
			actualizarActividadEconomica("811031212-1" ,"4645");
			actualizarActividadEconomica("890806968-3" ,"4761");
			actualizarActividadEconomica("800093391-5" ,"2100");
			actualizarActividadEconomica("10272670-8" ,"4761");
			actualizarActividadEconomica("75084745-1" ,"9511");
			actualizarActividadEconomica("1053779638-6" ,"1081");
			actualizarActividadEconomica("16051508-6" ,"4774");
			actualizarActividadEconomica("900222571-1" ,"4741");
			actualizarActividadEconomica("890903939-5" ,"1104");
			actualizarActividadEconomica("900053473-2" ,"7020");
			actualizarActividadEconomica("800247814-1" ,"6910");
			actualizarActividadEconomica("800003038-5" ,"2599");
			actualizarActividadEconomica("810004032-2" ,"4690");
			actualizarActividadEconomica("30330904-8" ,"4772");
			actualizarActividadEconomica("830117370-5" ,"7120");
			actualizarActividadEconomica("900275938-8" ,"8130");
			actualizarActividadEconomica("10231404-1" ,"7310");
			actualizarActividadEconomica("10232903-8" ,"3110");
			actualizarActividadEconomica("900261675-5" ,"8121");
			actualizarActividadEconomica("830067397-8" ,"4774");
			actualizarActividadEconomica("800242106-2" ,"4774");
			actualizarActividadEconomica("900317363-5" ,"8699");
			actualizarActividadEconomica("75099364-4" ,"3313");
			actualizarActividadEconomica("900058196-1" ,"4645");
			actualizarActividadEconomica("810005477-0" ,"4921");
			actualizarActividadEconomica("10269603-3" ,"4752");
			actualizarActividadEconomica("900006787-1" ,"9601");
			actualizarActividadEconomica("805001538-5" ,"3822");
			actualizarActividadEconomica("890300466-5" ,"4645");
			actualizarActividadEconomica("900480656-4" ,"4644");
			actualizarActividadEconomica("79744762-4" ,"4649");
			actualizarActividadEconomica("4324815-1" ,"4664");
			actualizarActividadEconomica("890301054-9" ,"1062");
			actualizarActividadEconomica("30236933-1" ,"4721");
			actualizarActividadEconomica("810006503-9" ,"7911");
			actualizarActividadEconomica("830133093-7" ,"8129");
			actualizarActividadEconomica("30312572-1" ,"4773");
			actualizarActividadEconomica("890801201-0" ,"8610");
			actualizarActividadEconomica("900340663-6" ,"5621");
			actualizarActividadEconomica("860007839-4" ,"2813");
			actualizarActividadEconomica("830041702-9" ,"4759");
			actualizarActividadEconomica("1088240706-1" ,"3312");
			actualizarActividadEconomica("1053778265-8" ,"9007");
			actualizarActividadEconomica("10231024-4" ,"7420");
			actualizarActividadEconomica("900062860-8" ,"7120");
			actualizarActividadEconomica("28816472-8" ,"7310");
			actualizarActividadEconomica("10211555-8" ,"130");
			actualizarActividadEconomica("10280024-3" ,"4759");
			actualizarActividadEconomica("810000248-8" ,"4330");
			actualizarActividadEconomica("10286743-8" ,"9601");
			actualizarActividadEconomica("800059823-1" ,"9499");
			actualizarActividadEconomica("810003158-7" ,"3110");
			actualizarActividadEconomica("52158265-3" ,"2640");
			actualizarActividadEconomica("800058709-5" ,"7110");
			actualizarActividadEconomica("10231182-1" ,"4330");
			actualizarActividadEconomica("75072701-6" ,"3312");
			actualizarActividadEconomica("16646705-5" ,"8219");
			actualizarActividadEconomica("17347172-0" ,"8219");
			actualizarActividadEconomica("890800788-7" ,"4663");
			actualizarActividadEconomica("900553402-5" ,"4652");
			actualizarActividadEconomica("10234009-7" ,"7110");
			actualizarActividadEconomica("24643640-6" ,"4751");
			actualizarActividadEconomica("800183978-4" ,"4719");
			actualizarActividadEconomica("816008610-1" ,"4669");
			actualizarActividadEconomica("24330432-1" ,"7310");
			actualizarActividadEconomica("800234616-3" ,"4645");
			actualizarActividadEconomica("830123806-9" ,"4645");
			actualizarActividadEconomica("10253605-8" ,"4755");
			actualizarActividadEconomica("900217639-3" ,"6202");
			actualizarActividadEconomica("811005246-1" ,"7020");
			actualizarActividadEconomica("24328832-8" ,"5613");
			actualizarActividadEconomica("10272547-1" ,"5619");
			actualizarActividadEconomica("810006693-1" ,"5511");
			actualizarActividadEconomica("4312404-6" ,"3311");
			actualizarActividadEconomica("860007839-4" ,"2813");
			
			echo "<p class='mensajeEjecucion'> <span class = 'subtitulo1'>Paso $paso: </span> Se han creado la tabla  Contabilidad.ActividadesEconomicas para la parametrizacion del impuesto CREE </p> ";
	
		}	
		
			
			

						
			
?>
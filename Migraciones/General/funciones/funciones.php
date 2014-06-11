	
	<?php
	

	
		function eliminarCaracteresEspeciales($cadena)
		
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
			$cadena= str_replace("?","",$cadena);
			$cadena= str_replace("A¡","a",$cadena);
			$cadena= str_replace("/","",$cadena);
			$cadena= str_replace( "ç","", $cadena);			
			$cadena= str_replace( "ß","", $cadena);
			$cadena= str_replace( "‘","", $cadena);
			$cadena= str_replace( ",","", $cadena);
			$cadena= str_replace( "'","", $cadena);
			$cadena= str_replace( '"',"", $cadena);
			$cadena = trim($cadena);
			$cadena = stripslashes($cadena);
			return $cadena;
		
		}
		
		
		/*function eliminarCaracteresEspeciales2($cadena)	{
			
			$cadena = strtoupper($cadena);
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
			$cadena= str_replace("A¡","a",$cadena);
			$cadena= str_replace("¿","&iquest;",$cadena);
			$cadena= str_replace("?","",$cadena);
			
			$cadena= str_replace("/","",$cadena);
			$cadena= str_replace( "ç","", $cadena);			
			$cadena= str_replace( "ß","", $cadena);
			$cadena= str_replace( "‘","", $cadena);
			$cadena= str_replace( ",","", $cadena);
			$cadena= str_replace( "'","", $cadena);
			$cadena= str_replace( '"',"", $cadena);
			$cadena = trim($cadena);
			$cadena = stripslashes($cadena);
			return $cadena;
		
		}*/
		
		
	
		
		
		
		function consultaConteoMySQL($bd, $tabla) {
			$cnx = conectar_mysql($bd);
			$cons = "SELECT COUNT(*) AS conteomysql FROM $bd.$tabla";
			$res =  mysql_query($cons);
			$fila = mysql_fetch_array($res);
			$res = $fila["conteomysql"];
			return $res; 
		}
		
		
		function  consultaConteoPostgresql($esquema, $tabla) {
			$cnx = conectar_postgres();
			$cons = "SELECT COUNT(*) AS conteopostgres FROM $esquema.$tabla " ;
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila["conteopostgres"];
			return $res; 
			
		}
		
		function generarNotaAclaratoria($numero,$nota) {
			echo "<p class='mensajeEjecucion'><span class = 'nota1'> Nota $numero : </span> $nota </p> ";
		}
		
		
		function FechaActual(){
				$cnx = conectar_postgres();
				$cons= "SELECT NOW() AS fechaactual";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
				$fila = pg_fetch_array($res);
				$res = $fila["fechaactual"];
				return $res;
			
			
		}
		
		
		
		
		function cambiarCompania($esquema,$tabla, $compania){
				$cnx = conectar_postgres();
				$cons= "UPDATE $esquema.$tabla SET compania = '$compania'";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
		}
		
		
		function eliminarRegistrosTabla($esquema,$tabla){
				$cnx = conectar_postgres();
				$cons= "DELETE FROM $esquema.$tabla WHERE UPPER(compania) = 'CLINICA SAN JOSE'";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
		}
		
		function eliminarRegistrosTabla2($esquema,$tabla){
				$cnx = conectar_postgres();
				$cons= "DELETE FROM $esquema.$tabla ";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
		}
		
		
		
		
		
		function truncarTabla($esquema,$tabla){
				$cnx = conectar_postgres();
				$cons= "TRUNCATE $esquema.$tabla CASCADE";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
			
		}
		
		function normalizarDepartamentos($departamento){
					
					$cargo = strtoupper($departamento);
					$cargo = trim($departamento);
					
					if ($departamento == "CORDOVA" or $departamento == "CORDOVA" or $cargo == "CORDOBA" ) {
					$departamento = "CORDOBA";
					}
					
					return $departamento;

		
		}
		
		
		function normalizarPabellones($pabellon){
					
					$pabellon = strtoupper($pabellon);
					$pabellon = trim($pabellon);
					
					
					if ($pabellon == "ADICCION HOMBRES" or $pabellon == "ADICCIONES") {
					$pabellon = "ADICCIONES";
					}
					elseif ($pabellon == "CRONICAS MUJERES" or $pabellon == "FEMENINO CRONICAS") {
						$pabellon = "CRONICAS MUJERES";
					}
					elseif ($pabellon == "CRONICOS HOMBRES" or $pabellon == "SALA GENERAL") {
						$pabellon = "CRONICOS HOMBRES";
					}
					elseif ($pabellon == "PENSIÓN HOMBRES"  or $pabellon == "PISO HOMBRES" or $pabellon == "PENSION HOMBRES") {
						$pabellon = "PISO HOMBRES";
					}
					elseif ( $pabellon == "PISO MUJERES" or $pabellon == "PENSIÓN MUJERES" or $pabellon == "PENSION MUJERES" ) {
						$pabellon = "PISO MUJERES";
					}
					elseif ($pabellon == "INIMPUTABLES" ) {
						$pabellon = "INIMPUTABLES";
					}
					elseif ($pabellon == "INTERMEDIOS HOMBRES" or $pabellon == "AGUDOS HOMBRES" ) {
						$pabellon = "INTERMEDIOS HOMBRES";
					}
					
					elseif ($pabellon == "JUAN CIUDAD" or $pabellon == "JUANITO" or  $pabellon == "UCA JUAN CIUDAD" or $pabellon == "UCE JUAN CIUDAD") {
						$pabellon = "JUAN CIUDAD";
					}
					elseif ($pabellon == "INTERMEDIOS MUJERES" or  $pabellon == "AGUDAS MUJERES" or $pabellon == "FEMENINO AGUDAS" ) {
						$pabellon = "INTERMEDIOS MUJERES";
					}
					elseif ($pabellon == "OBSERVACION"   or $pabellon == "UCE - SALA DE OBSERVACIÓN" or $pabellon == "UCE - SALA DE OBSERVACION" ) {
						$pabellon = "OBSERVACION";
					}
					elseif ($pabellon == "SERVICIO TEMPORAL" ) {
						$pabellon = "SERVICIO TEMPORAL";
					}
					elseif ($pabellon == "UCA" or $pabellon == "CUIDADOS ESPECIALES" or $pabellon == "CUIDADOS ESPECIALES 2" ) {
						$pabellon = "UCA";
					}
					elseif ($pabellon == "UCI-P" ) {
						$pabellon = "UCI-P";
					}
					
					return $pabellon;

		
		}
		
		
		function normalizarMedicos($medico){
					
					$medico = strtoupper($medico);
					$medico = str_replace( "&AACUTE;","&Aacute;", $medico);
					$medico = str_replace( "&EACUTE;","&Eacute;", $medico);
					$medico = str_replace( "&IACUTE;","&Iacute;", $medico);
					$medico = str_replace( "&OACUTE;","&Oacute;", $medico);
					$medico = str_replace( "&UACUTE;","&Uacute;", $medico);
					$medico = str_replace( "&NTILDE;","&Ntilde;", $medico);
					$medico = str_replace( "&IQUEST;","&iquest;", $medico);
					$medico = trim($medico);
					
					
					if ($medico == "FREDDY VILLA" ) {
					$medico = "FREDDY VILLA CARMONA";
					}
					if ($medico == "MAURICIO ANDRES RIVERA") {
						$medico = "MAURICIO ANDRES RIVERA V";
					}
					if ($medico == "JUAN CARLOS CASTRO NAVARRO" or $medico == "JUAN CASTRO") {
						$medico = "JUAN CASTRO NAVARRO";
					}
					
					
					if ($medico == "JULIAN MONGUI"  ) {
						$medico = "JULIAN MONGUI OLAYA";
					}
					if ($medico == "MARTIN FERNANDO ALDANA HURTADO" or $medico == "MARTIN FERNANDO ALDANA"  ) {
						$medico = "MARTIN FERNANDO ALDANA HURTADO";
					}
					
					
					if ($medico == "ADONILSO JULIO DE LA ROSA" or  $medico == "ADONILSO JULIO" ) {
						$medico = "ADONILSO JULIO DE LA ROSA";
					}
					if ($medico == "JULIANA ANDREA RAMIREZ G"  ) {
						$medico = "JULIANA RAMIREZ GOMEZ";
					}
					
					if ($medico == "MAURICIO CASTANO R" or $medico == "MAURICIO CASTAÑO R" or  $medico == "MAURICIO CASTA&NTILDE;O R" or $medico == "MAURICIO CASTA&Ntilde;O R" ) {
						$medico = "MAURICIO CASTA&Ntilde;O RAMIREZ";
					}
					if ($medico == "GERMAN ANDRES VALENCIA" or $medico == "GERMÁN ANDRÉS VALENCIA" or $medico == "GERM&AACUTE;N ANDR&EACUTE;S VALENCIA" or $medico == "GERM&Aacute;N ANDR&Eacute;S VALENCIA" ) {
						$medico = "GERMAN ANDRES VALENCIA";
					}
					if ($medico == "JUAN PABLO LONDOÑO" or $medico == "JUAN PABLO LONDO&NTILDE;O" or $medico == "JUAN PABLO LONDO&Ntilde;O" or $medico == "JUAN PABLO LONDONO" ) {
						$medico = "JUAN PABLO LONDO&Ntilde;O";
					}
					if ($medico == "DULCINEA OSORIO M" or $medico == "DULCINEA OSORIO MONTOYA"  ) {
						$medico = "DULCINEA OSORIO MONTOYA";
					}
					if ($medico == "MARTHA LUCIA LLANOS" or $medico == "MARTHA LUCIA LLANOS G"  ) {
						$medico = "MARTHA LUCIA LLANOS GOMEZ";
					}
					
										
					if ($medico == "EDWIN ALEXANDER DUQUE" or $medico == "EDWIN ALEXANDER DUQUE CORREA"  ) {
						$medico = "EDWIN ALEXANDER DUQUE CORREA";
					}
					
					if ($medico == "JULIAN A. ESPITIA" or $medico == "JULIAN ANDRES ESPITIA"  ) {
						$medico = "JULIAN ANDRES ESPITIA";
					}
					
					if ($medico == "ANA MARIA DUQUE" or $medico == "ANA MARIA DUQUE DUSSAN" or $medico == "ANA MARIA DUQUE D." or $medico == "ANA MARIA DUQUE R."  ) {
						$medico = "ANA MARIA DUQUE DUSSAN";
					}
					
					if ($medico == "MARIA YAQUELINE URBINA BELTRAN" or $medico == "MARIA YAKELINE URBINA" or $medico == "MARIA YAQUELINE URBINA B" ) {
						$medico = "MARIA YAQUELINE URBINA BELTRAN";
					}
					
					if ($medico == "LAURA BARON" or $medico == "LAURA MARCELA BARON" ) {
						$medico = "LAURA MARCELA BARON";
					}
					
					if ($medico == "MARIA DEL PILAR VELASQUEZ" or $medico == "MARIA DEL PILAR VELASQUEZ D" ) {
						$medico = "MARIA DEL PILAR VELASQUEZ DUQUE";
					}
					
					if ($medico == "DIANA CAROLINA OCAMPO MUÑOZ" or $medico == "DIANA CAROLINA OCAMPO MU&NTILDE;OZ" or $medico == "DIANA CAROLINA OCAMPO MU&Ntilde;OZ" or $medico == "DIANA OCAMPO")  {
						$medico = "DIANA CAROLINA OCAMPO MUÑOZ";
					}
					
					if ($medico == "ANA PAOLA CETINA MEDINA" or $medico == "ANA CETINA" ) {
						$medico = "ANA PAOLA CETINA MEDINA";
					}
					
					if ($medico == "JUAN CARLOS VALENCIA" or $medico == "JUAN CARLOS VALENCIA ALZATE" ) {
						$medico = "JUAN CARLOS VALENCIA ALZATE";
					}
					
					if ($medico == "MARIO F. LOPEZ" or $medico == "MARIO FERNANDO LOPEZ" or $medico == "MARIO LOPEZ BUITRAGO" ) {
						$medico = "MARIO FERNANDO LOPEZ BUITRAGO";
					}
					if ($medico == "ANGELA MARIA GALVIS" or $medico == "ANGELA MARIA GALVIZ"  ) {
						$medico = "ANGELA MARIA GALVIS";
					}
					if ($medico == "CRISTIAN CAMILO GIRALDO RAMIREZ" or $medico == "CRISTIAN CAMILO GIRALDO"  ) {
						$medico = "CRISTIAN CAMILO GIRALDO RAMIREZ";
					}
									
					
					return $medico;

		
		}	
		
		
		
		function normalizarEspecialidades($cargo){
					
					$cargo = strtoupper($medico);
					$cargo = trim($medico);
					
					if ($cargo == "DIRECCION CIENTIFICA" ) {
					$especialidad = "DIRECCION CIENTIFICA";
					}
					elseif ($cargo == "MEDICO GENERAL") {
						$especialidad = "MEDICO GENERAL";
					}
					elseif ($cargo == "PSICOLOGO") {
						$especialidad = "PSICOLOGIA";
					}
					elseif ($cargo == "PSIQUIATRA") {
						$especialidad = "PSIQUIATRIA";
					}
					elseif ($cargo == "RESIDENTE") {
						$especialidad = "RESIDENTE";
					}
					
					if (trim($especialidad = "")){
						$especialidad = "POR VALIDAR";
					}
					
					return $especialidad;

		
		}	
		
		
		function normalizarCargos($cargo){
					
					$cargo = strtoupper($cargo);
					$cargo = trim($cargo);
					
					if ($cargo == "PSIQUIATRA" or $cargo == "PSIQUIATRA ADJ" or $cargo == "PSIQUITRA"  ) {
					$cargo = "PSIQUIATRA";
					}
					elseif ($cargo == "MEDICO SEXOLOGO" or $cargo == "MÉDICO SÉXOLOGO") {
						$cargo = "MEDICO SEXOLOGO";
					}
					elseif ($cargo == "MEDICO RURAL" or $cargo == "MEDICO GENERAL" or $cargo == "MEDICO GENERAL S.S.O." or $cargo == "MÉDICO GENERAL" or $cargo == "MEDGENERAL") {
						$cargo = "MEDICO GENERAL";
					}
					elseif ($cargo == "RESIDENTE" or  $cargo == "MEDICO RESIDENTE") {
						$cargo = "RESIDENTE";
					}
					elseif ($cargo == "JEFEENFER"   or  $cargo == "JEFE ENFERMERIA") {
						$cargo = "JEFE DE ENFERMERIA";
					}
					elseif ($cargo == "GSOCIAL"   ) {
						$cargo = "TRABAJO SOCIAL";
					}
					
					
					
					
					return $cargo;

		
		}	
		
		
		
			function comprobarMedico($medico, $cargo, $registromedico){
				$cnx = conectar_postgres();
				$cons= "SELECT *  FROM  Salud.Medicos WHERE usuario = '$medico' ";
				$res = @pg_query($cnx, $cons);
					if (!$res) {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
					}
					
				$vector["medico"] = $medico;
				$vector["cargo"] = $cargo;
				
								if (pg_num_rows($res) > 0){
									$fila = pg_fetch_array($res);
									//$vector["cargo"]  = $fila["cargo"];	
								
								}
								
								if (pg_num_rows($res) <= 0 and trim($registromedico) != ""){
										$vector["medico"] = "";
										$cnx = conectar_postgres();
										$cons2= "SELECT *  FROM  Salud.Medicos WHERE rm = '$registromedico' ";
										$res2 = @pg_query($cnx, $cons2);
											if (!$res2) {
												echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
												echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br/> <br/> <br/>";
											}
											
											if ($res2){
												if(pg_num_rows($res2) > 0)	{
													$fila2 = pg_fetch_array($res2);
													$vector["medico"] =  $fila2["usuario"];	
													//$vector["cargo"]  = $fila2["cargo"];	
													
													
												}
												elseif(pg_num_rows($res2) <= 0)	{													
													$vector["medico"] = "";
												}								
											
											}
								}	
									
										
																
								
								
					
					
					if (trim($vector["medico"]) == "") {
									$cargo = strtoupper($cargo);
									$cargo = trim($cargo);
									if ($cargo == "MEDICO GENERAL") {
											
										$vector["medico"] = 'MEDICO GENERAL';
										$vector["cargo"] = "MEDICO GENERAL" ;
									}
									elseif ($cargo == "RESIDENTE") {
										$vector["medico"] = 'RESIDENTE';
										$vector["cargo"] = "RESIDENTE" ;
									}
									
									elseif ($cargo == "INTERNO") {
										$vector["medico"] = 'INTERNO';
										$vector["cargo"] = "INTERNO" ;
									}
									
									elseif ($cargo == "PSIQUIATRA") {
										$vector["medico"] = 'PSIQUITRA';
										$vector["cargo"] = "PSIQUITRA" ;
									}
									
									elseif ($cargo == "PSICOLOGO") {
										$vector["medico"] = 'PSICOLOGO';
										$vector["cargo"] = "PSICOLOGO" ;
									}
									elseif ($cargo == "NUTRICIONISTA") {
										$vector["medico"] = 'NUTRICIONISTA';
										$vector["cargo"] = "NUTRICIONISTA" ;
									}
									elseif ($cargo == "ENFERMERIA") {
										$vector["medico"] = 'ENFERMERIA';
										$vector["cargo"] = "ENFERMERIA" ;
									}	
									elseif ($cargo == "GERENCIA") {
										$vector["medico"] = 'GERENCIA';
										$vector["cargo"] = "GERENCIA" ;
									}	
									elseif ($cargo == "PEDAGOGO REEDUCADOR") {
										$vector["medico"] = 'PEDAGOGO REEDUCADOR';
										$vector["cargo"] = "PEDAGOGO" ;
									}
									elseif ($cargo == "EDUCADOR FISICO") {
										$vector["medico"] = 'EDUCADOR FISICO';
										$vector["cargo"] = "EDUCADOR FISICO" ;
									}
									elseif ($cargo == "NEUROPSICOLOGO") {
										$vector["medico"] = 'NEUROPSICOLOGO';
										$vector["cargo"] = "NEUROPSICOLOGA" ;
									}
									elseif ($cargo == "TRABAJO SOCIAL") {
										$vector["medico"] = 'TRABAJO SOCIAL';
										$vector["cargo"] = "TRABAJO SOCIAL" ;
									}
									elseif ($cargo == "DIRECCION CIENTIFICA") {
										$vector["medico"] = 'DIRECCION CIENTIFICA';
										$vector["cargo"] = "DIRECCION CIENTIFICA" ;
									}
									elseif ($cargo == "JEFE ENFERMERIA") {
										$vector["medico"] = 'JEFE ENFERMERIA';
										$vector["cargo"] = "JEFE DE ENFERMERIA" ;
									}
									elseif ($cargo == "MEDICO SEXOLOGO") {
										$vector["medico"] = 'MEDICO SEXOLOGO';
										$vector["cargo"] = "MEDICO SEXOLOGO" ;
									}
					}
					
															
					
						
				return $vector; 	
			
		}
		
		
		
		
		function consultarMes() {
			$cnx= conectar_postgres();
			$cons = "SELECT DATE_PART('MONTH',NOW()) AS mes";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['mes'];
			return $res; 	
		
		}
		
		
		function consultarAnio() {
			$cnx= conectar_postgres();
			$cons = "SELECT DATE_PART('YEAR',NOW()) AS anio";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['anio'];
			return $res; 	
		
		}
		
		
		function consultarEntidad($nombreentidad) {
			$cnx= conectar_postgres();
			$cons = "SELECT identificacion FROM Central.Terceros WHERE TRIM(CONCAT(primape, ' ' , segape, '' , primnom, ' ', segnom)) =  '$nombreentidad'";
			$res =  pg_query($cnx, $cons);
			$fila = pg_fetch_array($res);
			$res = $fila['identificacion'];
			return $res; 
		
		
		
		}
		
		
		
		function normalizarAmbitos($ambito){
					
					$ambito = strtoupper($ambito);
					$ambito = trim($ambito);
					
					if ($ambito == "URGENCIAS" ) {
						$ambito = "Urgencias";
					}
					elseif ($ambito == "CONSULTA EXTERNA") {
						$ambito = "Consulta Externa";
					}
					elseif ($ambito == "HOSPITALIZACION") {
						$ambito = "Hospitalizacion";
					}
					
					
					
					return $ambito;

		
		}	
		
	
	
		function  consultarNombreEntidad($identificacion)  {
		// Busca y reemplaza ocurrencias en una tabla
			
			$cnx= conectar_postgres();
			$cons = " SELECT  primape FROM Central.Terceros WHERE identificacion = '$identificacion' ";
			
			$res = @pg_query($cnx , $cons);
				if (!$res) {				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
			$fila = @pg_fetch_array($res)	;
			$nombre = $fila["primape"];
			$nombre = trim($nombre);
			return $nombre;	

		}
		
		
		function  consultarNitEntidad($nombre)  {
		// Busca y reemplaza ocurrencias en una tabla
			
			$cnx= conectar_postgres();
			$cons = " SELECT  identificacion FROM Central.Terceros WHERE primape = '$nombre' ";
			
			$res = @pg_query($cnx , $cons);
				if (!$res) {				
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
				
				$fila = pg_fetch_array($res);
				$identificacion = $fila["identificacion"];
			return $identificacion;	

		}
		
		
		function  consultarNitEPSMySQL($nombre)  {
		// Busca y reemplaza ocurrencias en una tabla
			
			$cnx= conectar_mysql("Salud");
			$cons = " SELECT  nit FROM Salud.EPS WHERE nombre = '$nombre' ";
			
			$res = @mysql_query($cons);
				if (!$res) {				
					echo "<p class='error1'> Error de ejecucion </p>".mysql_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
				
				$fila = mysql_fetch_array($res);
				$nit = $fila["nit"];
			return $nit;	

		}
		
		
		
		
		function  consultarContrato($entidad, $nombreentidad)  {
		// Busca y reemplaza ocurrencias en una tabla
			$nombreentidad = strtoupper($nombreentidad);
			$nombreentidad = trim($nombreentidad);
			
			
			$cnx= conectar_postgres();
			$cons = " SELECT  * FROM ContratacionSalud.Contratos WHERE entidad = '$entidad' ";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
				
				if (pg_num_rows($res) > 1){
					// Incluye en la clausula del nombre de la entidad para ser mas restrictivo 
					$cons = " SELECT  * FROM ContratacionSalud.Contratos WHERE entidad = '$entidad' AND contrato ILIKE '%$nombreentidad' ";
					$res = @pg_query($cnx , $cons);
					
					// Verifica si hay ambigüedad en el contrato y numero de contrato
					
					if (pg_num_rows($res) > 1){
						if ($entidad == "800114312-5"){
							$vectorcontrato["contrato"] = "DIRECCION TERRITORIAL DE SALUD DE CALDAS.";
							$vectorcontrato["nocontrato"] = $entidad;
						} 
						else{						
							 // Se define el contrato y numero de contrato con los valores por defecto
							$vectorcontrato["contrato"] = "CONTRATO ".$nombreentidad;
							$vectorcontrato["nocontrato"] = $entidad;
							echo "<p class='error1'> Posible de error de ejecucion </p> Ambig&uuml;edad en la definicion del Contrato y NoContrato<br>";
							echo "<p class= 'subtitulo1'> Entidad:</p> <br>".$entidad."<br/>";
							echo "<p class= 'subtitulo1'> Nombre de Entidad:</p> <br>".$nombreentidad."<br/> <br/> <br/>";
						}
					}

				}
				
				if (pg_num_rows($res) == 1){
					while($fila = pg_fetch_array($res)){
						
						$vectorcontrato["contrato"] = $fila["contrato"];
						$vectorcontrato["nocontrato"] = $fila["numero"];
					}
				}
				
				
				// Si no encuentra registros asociados 
				if (pg_num_rows($res) <= 0)	{
					// Se define el contrato y numero de contrato con los valores por defecto
					$vectorcontrato["contrato"] = "CONTRATO ".$nombreentidad;
					$vectorcontrato["nocontrato"] = $entidad;
				}
				
				
				
			return $vectorcontrato;	

		}
		
		
		
		
		
		function normalizarEntidades($nombreentidad){
					
					$nombreentidad = strtoupper($nombreentidad);
					$nombreentidad = trim($nombreentidad);
					
					if ($nombreentidad == "SOLSALUD" ) {
						$nombreentidad = "SOLSALUD EPS";
					}
					
					if ($nombreentidad == "CAFE SALUD MEDICINA PREPAGADA" ) {
						$nombreentidad = "CAFESALUD";
					}
					
					if ($nombreentidad == "CAFE SALUD MEDICINA PREPAGADA" ) {
						$nombreentidad = "CAFESALUD";
					}
					
					if ($nombreentidad == "UNISALUD" ) {
						$nombreentidad = "UNIVERSIDAD NACIONAL DE COLOMBIA (UNISALUD)";
					}	
					
					
					if ($nombreentidad == "SURAMERICANA SALUD" ) {
						$nombreentidad = "SEGUROS DE VIDA SURAMERICANA S.A.";
					}
					
					if ($nombreentidad == "RECLUCION DE MUJERES DE MANIZALES" ) {
						$nombreentidad = "RECLUSI&Oacute;N DE MUJERES DE MANIZALES";
					}
					
					if ($nombreentidad == "CAPRECOM ARS" ) {
					$nombreentidad = "CAPRECOM";
					}
					
					if ($nombreentidad == "SALUD VIDA ARS" ) {
					$nombreentidad = "SALUD VIDA EPS";
					}
					
					if ($nombreentidad == "COLMEDICA S.A." ) {
					$nombreentidad = "COLMEDICA MEDICINA PREPAGADA";
					}
					
					if ($nombreentidad == "ECOPETROL" ) {
					$nombreentidad = "ECOPETROL S.A.";
					}
					
					if ($nombreentidad == "CAFESALUD MP" ) {
					$nombreentidad = "CAFESALUD";
					}
					
					
					return $nombreentidad;

		
		}
		
		function agregarPuntoFinal($cadena){

			$cadena = trim($cadena);
			$longitudcadena = strlen($cadena);
			$penultimaposicion = $longitudcadena - 1;
			$subcadena =  substr($cadena, $penultimaposicion , $longitudcadena);
			
				// Verifica que no haya un punto alfinal de la cadena
				if ($subcadena != "." ){
					$cadena = $cadena.".";
				}
			return $cadena; 
		
		}
		
		
		
		function  consultarCUM($codigo)  {
		// Consulta el CUM con base en el autoid(Codigo del medicamento)
			
			$cnx= conectar_postgres();
			$cons = " SELECT  cum FROM Consumo.CUMSxProducto WHERE autoid = '$codigo' ";
			
			$res = @pg_query($cons);
				if (!$res) {				
					echo "<p class='error1'> Error de ejecucion </p>".pg_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";			
				}
				
				if (pg_num_rows($res) > 0) {
					$fila = pg_fetch_array($res);
					$cum = $cum["nit"];
				}
				elseif(pg_num_rows($res) == 0){
					$cum = 'NULL';
				}
			
			return $cum;
		}
		
		
		function definirServicio($cedula, $idhospitalizacionmysql) {
			$cnx= conectar_postgres();
			$cons = " SELECT  *  FROM Salud.Servicios WHERE cedula = '$cedula' AND idhospitalizacionmysql = '$idhospitalizacionmysql' ";
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
				
				if (@pg_num_rows($res) > 1){
					echo "<p class='error1'> Posible error de ambiguedad </p>";
					echo "<p class= 'subtitulo1'>Identificacion: </p> <br>".$cedula."<br>"; 				
					echo "<p class= 'subtitulo1'>IdHospitalizacionMySQL: </p> <br>".$idhospitalizacionmysql."<br>"; 				
					echo "<br><br>";
						
						$pos = 0;
						while ($fila = @pg_fetch_array($res)) {
							$numservicio = $fila["numservicio"];
							$pos++;
						
						}
				}
				
				if (@pg_num_rows($res) == 1){
					$pos = 0;
						while ($fila = @pg_fetch_array($res)) {
							$numservicio = $fila["numservicio"];
							$pos++;						
						}
				}
				
				if (@pg_num_rows($res) == 0){
					$numservicio = 0;
				}
				
			return $numservicio;
		}
		
		function consultarCodDx($diagnostico){
			$diagnostico = strtoupper($diagnostico);
			$diagnostico = trim($diagnostico);
			$cnx = conectar_postgres();
			$cons = "SELECT codigo FROM Salud.CIE WHERE TRIM(UPPER(diagnostico)) = '$diagnostico'";
			$res = @pg_query($cnx, $cons);
				if (!$res){
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";
				}
				
				if (@pg_num_rows($res) == 0) {
					$coddx = $diagnostico;
				}
				
				if (@pg_num_rows($res) > 0) {
					
					$fila = @pg_fetch_array($res);
					$coddx = $fila["codigo"];
				}
			
			return $coddx;
		
		}
		
		
		function consultarCUMProducto($autoid){
			$cnx= conectar_postgres();
			$cons = " SELECT  CUM FROM Consumo.CodProductos WHERE almacenppal = 'FARMACIA' AND AutoId = '$autoid'";
			
			$res = @pg_query($cnx , $cons);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
			
			$fila = pg_fetch_array($res);
			$cum = $fila["cum"];
			return $cum;
		
		}
		
		
		function seleccionarMaxMovimientoConsumo(){
				$cnx = conectar_postgres();
				$cons = "SELECT MAX(numero)as maximo FROM Consumo.Movimiento";
				$res = pg_query($cnx,$cons);
				$fila = pg_fetch_array($res);
				$maximo = $fila["maximo"];
					if ((!$maximo) or ($maximo== NULL)){
						$maximo = 0;
					}
				$maximo = $maximo + 1;	
				
				return $maximo;
		}
		
		
		function normalizarGruposConsumo($grupo){
			if (strtoupper($grupo)== "MEDICAMENTO"){
				$grupo = "MEDICAMENTOS";
			}
			return $grupo;
		}
		
		
		function definirEntradasFarmacia($autoid, $anio){
			$cnx = conectar_postgres();
			$cons = "SELECT SUM(Cantidad) AS entradas FROM Consumo.Movimiento WHERE UPPER(TipoComprobante) = 'ENTRADAS' AND Autoid = '$autoid' AND DATE_PART('year', fecha) = '$anio'";
			$res = pg_query($cnx,$cons);
			$fila = pg_fetch_array($res);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
				
			$entradas = $fila["entradas"];
			
			return $entradas;
		}
		
		function definirSalidasFarmacia($autoid, $anio){
			$cnx = conectar_postgres();
			$cons = "SELECT SUM(Cantidad) AS salidas FROM Consumo.Movimiento WHERE UPPER(TipoComprobante) = 'SALIDAS' AND Autoid = '$autoid' AND DATE_PART('year', fecha) = '$anio'";
			$res = pg_query($cnx,$cons);
			$fila = pg_fetch_array($res);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
				
			$salidas = $fila["salidas"];
			
			return $salidas;
		}
		
		function definirDevolucionesFarmacia($autoid, $anio){
			$cnx = conectar_postgres();
			$cons = "SELECT SUM(Cantidad) AS devoluciones FROM Consumo.Movimiento WHERE UPPER(TipoComprobante) = 'DEVOLUCIONES' AND Autoid = '$autoid' AND DATE_PART('year', fecha) = '$anio'";
			$res = pg_query($cnx,$cons);
			$fila = pg_fetch_array($res);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
			$devoluciones = $fila["devoluciones"];
			
			return $devoluciones;	
		}
		
		function definirSaldoInicialFarmacia($autoid, $anio){
			$cnx = conectar_postgres();
			$cons = "SELECT cantidad FROM Consumo.SaldosInicialesxAnio WHERE almacenppal = 'FARMACIA' AND Autoid = '$autoid' AND anio = '$anio'";
			$res = pg_query($cnx,$cons);
			$fila = pg_fetch_array($res);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
			$saldoinicial = $fila["cantidad"];
			
			return $saldoinicial;	
		}
		
		
		function definirExistenciasFarmacia($autoid, $anio){
			$entradas = definirEntradasFarmacia($autoid, $anio);
			$salidas = definirSalidasFarmacia($autoid, $anio);
			$devoluciones = definirDevolucionesFarmacia($autoid, $anio);
			$saldoinicial = definirSaldoInicialFarmacia($autoid, $anio);
			
			$existencias = $saldoinicial + $entradas - ($salidas - $devoluciones);
			
				if(!isset($existencias)){
					$existencias = 0;				
				}
			return $existencias;
		
		} 
		
		
		function definirPresentacionProducto($autoid){
			
			if (isset($autoid)){
				$cnx = conectar_postgres();
				$cons = "SELECT presentacion FROM Consumo.Codproductos WHERE almacenppal = 'FARMACIA' AND autoid = '$autoid'";
				$res = @pg_query($cnx,$cons);
				$fila = @pg_fetch_array($res);
					if (!$res)  {
						echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
						echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
						echo "<br><br>";	
					}
				$presentacion = $fila["presentacion"];
			} 
			else {
				$presentacion = "POR VALIDAR";
			}
			
			
			return $presentacion;	
		
		}
		
		function consultarMaximoLote(){
			$cnx = conectar_postgres();
			$cons = "SELECT MAX(CAST(Numero AS int)) AS maximo  FROM Consumo.Lotes";
			$res = pg_query($cnx,$cons);
			$fila = pg_fetch_array($res);
				if (!$res)  {
					echo "<p class='error1'> Error de ejecucion </p>".pg_last_error()."<br>";
					echo "<p class= 'subtitulo1'>Comando SQL </p> <br>".$cons."<br>"; 				
					echo "<br><br>";	
				}
				
			$max = $fila["maximo"];
			
				if (!isset($max)){
					$max = 0;
				}
				
			$maximo = (int)$max;
			$maximo = $maximo + 1;
			return $maximo;
		}
		
		function normalizarComprobantesContabilidad($comprobante){
			$comprobante = strtoupper($comprobante);
			$comprobante = trim($comprobante);
			
			if ($comprobante == "NÓMINA" or $comprobante == "N&OACUTEMINA"){
				$comprobante = "NOMINA";
			}
			elseif ($comprobante == "NOTAS CRÉDITO" or $comprobante == "NOTAS CR&EACUTE;DITO"){
				$comprobante = "NOTAS CREDITO";
			}
			elseif ($comprobante == "NOTAS DÉBITO" or $comprobante == "NOTAS D&EACUTE;BITO"){
				$comprobante = "NOTAS DEBITO";
			}
			elseif ($comprobante == "CIERRE DE AÑO" or $comprobante == "CIERRE DE A&NTILDE;O"){
				$comprobante = "CIERRE ANUAL";
			}
			elseif ($comprobante == "FACTURACIÓN" or $comprobante == "FACTURACI&OACUTE;N"){
				$comprobante = "FACTURACION";
			}
			
			return $comprobante;
			
		
		
		}
		
		
		
?>
		
		
		
		
		
	
	
	
	

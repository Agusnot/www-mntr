<?php

	include('../Conexiones/conexion.php');

	/* Inicia la definicion de funciones */
	
		function eliminarCaracteresEspeciales($cadena)
		{
			$cadena= str_replace("á","a",$cadena);
			$cadena= str_replace("é","e",$cadena);
			$cadena= str_replace("í","i",$cadena);
			$cadena= str_replace("ó","o",$cadena);
			$cadena= str_replace("ú","u",$cadena);
			$cadena= str_replace("Á","A",$cadena);
			$cadena= str_replace("É","E",$cadena);
			$cadena= str_replace("Í","I",$cadena);
			$cadena= str_replace("Ó","O",$cadena);
			$cadena= str_replace("Ú","U",$cadena);
			$cadena= str_replace("ñ","n",$cadena);
			$cadena= str_replace("Ñ","N",$cadena);
			$cadena= str_replace("/","",$cadena);
			$cadena= str_replace( "'","", $cadena);
			$cadena= str_replace( "ò","o", $cadena);
			$cadena = stripslashes($cadena);
			return $cadena;
		
		}
		
		
	
		function llamarSaludUsuariosMySQL($limiteIni, $limiteFin) {
			// El limite inicial y final se usan para una inmensa cantidad de registros
			
			$cnx = conectar_mysql("Salud");
			$cons = "SELECT *, HOUR(HoraInicio) AS HourInicio, MINUTE(HoraInicio) AS  MinInicio,  HOUR(HoraFin) AS  HourFin, MINUTE(HoraFin) AS  MinFin   FROM Salud.agenda ORDER BY cedula ASC LIMIT ".$limiteIni. " , ".$limiteFin;
			$res =  mysql_query( $cons);
			return $res; 
		
		}
		
		function insertarRegistroPostgresql($compania, $cedula,$id,  $entidad, $estado, $medico,  $fecha, $fechaCr, $fechaSol, $horaInicio, $minInicio, $horaFin, $minFin, $motivoCanc, $tiempo, 	                                           $quienCancela, $usuario) {
			
			$cnx = 	conectar_postgres();
			$cons = "INSERT INTO Salud.Agenda (compania,cedula,id, entidad, estado, medico, fecha, fechacrea, fechasolicita, hrsini, minsini, hrsfin, minsfin, motivocancel,       	                     tiempocons, usucancel, usucrea) VALUES ('".$compania."','".$cedula."','".$id."','".$entidad."','". $estado."','". $medico."','".$fecha."','".$fechaCr."','".$fechaSol.                     "','".$horaInicio."','".                     $minInicio."','".$horaFin."','".$minFin."','".$motivoCanc."','".$tiempo."','".$quienCancela."','".$usuario."')"	;
					 
			$cons = str_replace( "'NULL'","NULL",$cons  );	
			$cons = $cons." ;";
			$res = @pg_query($cnx, $cons);
			//echo $cons; 
			//echo "<br><br>";

			//echo "Ha finalizado la insercion";
			
		}
		
		
		function  llenarMatriz($limiteIni, $limiteFin){
		
			 global $res, $matriz;	
			 $res = llamarSaludUsuariosMySQL($limiteIni, $limiteFin);
			$posicion=0;
				
				while ($fila = mysql_fetch_array($res))
				{
					$cedula = eliminarCaracteresEspeciales($fila['Cedula']);
					$matriz['Cedula'][$posicion] = $cedula;
					
					$entidad = eliminarCaracteresEspeciales($fila['Entidad']);
					$matriz['Entidad'][$posicion] = $entidad;
					
					$matriz['Estado'][$posicion] = $fila['Estado'];					
					
					$medico = eliminarCaracteresEspeciales($fila['Medico']);
					$matriz['Medico'][$posicion] = $medico;

					$matriz['Fecha'][$posicion] = $fila['Fecha'];
					$matriz['FechaHoraCr'][$posicion] = $fila['FecHoraCr'];
					$matriz['FecSolicitud'][$posicion] = $fila['FecSolicitud'];
					$matriz['HourInicio'][$posicion] = $fila['HourInicio'];
					$matriz['MinInicio'][$posicion] = $fila['MinInicio'];
					$matriz['HourFin'][$posicion] = $fila['HourFin'];
					$matriz['MinFin'][$posicion] = $fila['MinFin'];	
					
					$motivoCanc = eliminarCaracteresEspeciales($fila['MotivoCanc']);				
					$matriz['MotivoCanc'][$posicion] = $motivoCanc;
					
					$matriz['Tiempo'][$posicion] = $fila['Tiempo'];
					
					$quienCancela = eliminarCaracteresEspeciales($fila['QuienCancela']);
					$matriz['QuienCancela'][$posicion] =  $quienCancela;
					
					$usuario = eliminarCaracteresEspeciales($fila['Usuario']);
					$matriz['Usuario'][$posicion] = $usuario;				
					$posicion++;				
				}
			}
				
		
		
		function visualizarMatriz() {	
				echo "<table border='1'>";
				echo "<tr>";
				echo "<td> Posicion </td>";
				echo "<td> Cedula</td>";
				echo "<td> Entidad</td>";	
				echo "<td> Estado</td>";
				echo "<td> Medico </td>";
				echo "<td> Fecha</td>";	
				echo "<td> FechaHorCr</td>";
				echo "<td> FecSolictud</td>";	
				echo "<td> HoraInicio</td>";
				echo "<td> MinInicio</td>";				
				echo "<td> HoraFin</td>";
				echo "<td> MinFin </td>";
				echo "<td> Motivo Canc</td>";
				echo "<td> Tiempo</td>";
				echo "<td> QuienCancela</td>";
				echo "<td> Usuario</td>";
				echo "</tr>";
				global $res, $matriz;
				for($pos=0;$pos < mysql_num_rows($res); $pos++)  {
					echo "<tr>";
					echo "<td> <font color='red'>".$pos." </font></td>";
					echo "<td>".$matriz['Cedula'][$pos]."</td>";
					echo "<td>". $matriz['Entidad'][$pos]."</td>";
					echo "<td>".$matriz['Estado'][$pos]."</td>";
					echo "<td>".$matriz['Medico'][$pos]."</td>";
					echo "<td>".$matriz['Fecha'][$pos]."</td>";
					echo "<td>".$matriz['FechaHoraCr'][$pos]."</td>";
					echo "<td>".$matriz['FecSolicitud'][$pos]."</td>";
					echo "<td>".$matriz['HourInicio'][$pos]."</td>";
					echo "<td>".$matriz['MinInicio'][$pos]."</td>";
					echo "<td>".$matriz['HourFin'][$pos]."</td>";
					echo "<td>".$matriz['MinFin'][$pos]."</td>";
					echo "<td>".$matriz['MotivoCanc'][$pos]."</td>";
					echo "<td>".$matriz['Tiempo'][$pos]."</td>";
					echo "<td>".$matriz['QuienCancela'][$pos]."</td>";
					echo "<td>".$matriz['Usuario'][$pos]."</td>";
					echo "</tr>";
				}

			}	
			
			
			
			function insertarTabla($puntero)  {
			
				global $res, $matriz;
					for($pos=0;$pos < mysql_num_rows($res); $pos++)  {

					$cedula=	 $matriz['Cedula'][$pos] ;
					$entidad= 	 $matriz['Entidad'][$pos] ;
					$estado=	 $matriz['Estado'][$pos] ;
					$medico=	 $matriz['Medico'][$pos] ;
					$fecha=   	 $matriz['Fecha'][$pos] ;
						if($fecha == "0000-00-00 00:00:00")  {
						$fecha = '1900-01-01';						
						}
					$fechaCr=    $matriz['FechaHoraCr'][$pos] ;
						if($fechaCr == "0000-00-00 00:00:00")  {
							$fechaCr = 'NULL';						
						}
					$fechaSol=   $matriz['FecSolicitud'][$pos] ;
					if($fechaSol == "0000-00-00")  {
							$fechaSol = 'NULL';						
						}
					$horaInicio= $matriz['HourInicio'][$pos] ;
					$minInicio=  $matriz['MinInicio'][$pos] ;
					$horaFin=    $matriz['HourFin'][$pos] ;
					$minFin=     $matriz['MinFin'][$pos] ;
					$motivoCanc= $matriz['MotivoCanc'][$pos] ;
					$tiempo=     $matriz['Tiempo'][$pos] ;
					$quienCancela=$matriz['QuienCancela'][$pos] ;
					$usuario=	$matriz['Usuario'][$pos] ;
					$compania = "Clinica San Juan de Dios";
					
					$id = $puntero + $pos;
						
					
					insertarRegistroPostgresql($compania, $cedula,$id, $entidad, $estado, $medico,  $fecha, $fechaCr, $fechaSol, $horaInicio, $minInicio, $horaFin, $minFin, $motivoCanc, $tiempo,  $quienCancela, $usuario);	
					
					
					
					//echo "<br>";	
						
					}
			
	
			}
				

		/* Finaliza la definicion de funciones*/	
		
		
		
		
		/* Inicia la ejecucion de la migracion */
			
		if($_GET['tabla']="Salud_agenda") {
		
			echo "<fieldset>";			
			echo "<legend> Migracion tabla MySQL </legend>";
		
		
			
		
			$fragmento = 50000;
						
			llenarMatriz(1,$fragmento);
	
			
			insertarTabla(1);
			
			
			llenarMatriz(50001,$fragmento);
			
			insertarTabla(50001);
			
			llenarMatriz(100001,$fragmento);
			
			insertarTabla(100001);
			
	
			llenarMatriz(150001,$fragmento);
			
			insertarTabla(150001);
			
			llenarMatriz(200001,$fragmento);
			
			insertarTabla(200001);
		
			echo "</fieldset>";
			
		}
			
	

		
		
		
		

	
	




?>
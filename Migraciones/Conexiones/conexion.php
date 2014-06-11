<?php

	


	 	function conectar_postgres() {
	 	global  $usuarioPostgresql,  $passPostgresql, $bdPostgresql, $puertoPostgresql, $hostPostgresql;
		$usuarioPostgresql = 'postgres';
		$passPostgresql = 'Server*1982';
		$bdPostgresql = 'sistema';
		$puertoPostgresql = 5432;
		$hostPostgresql = 'localhost';
		$strCnx = "host=$hostPostgresql port= $puertoPostgresql dbname=$bdPostgresql  user=$usuarioPostgresql password=$passPostgresql";
		$cnx = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
		return $cnx;
	}


	    function  conectar_mysql($basedatos) {
		global $usuarioMySQL, $passMySQL, $bdMySQL, $puertoMySQL, $hostMySQL;
		$usuarioMySQL = 'root';
		$passMySQL = '';
		$bdMySQL = $basedatos ;
		$puertoMySQL = 3306 ;
		$hostMySQL = 'localhost';
		
		$cnx =  mysql_connect($hostMySQL, $usuarioMySQL, $passMySQL, $bdMySQL) ;
		//$cnx =  mysql_connect($hostMySQL, $usuarioMySQL, $passMySQL);
		
		
		return $cnx;




	}




?>

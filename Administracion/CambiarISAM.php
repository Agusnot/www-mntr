<?
	session_start();
	include("Funciones.php");
	$dbname="HistoriaClinica";
	$result = mysql_listtables ("$dbname");echo mysql_error();
	while($fila=ExFetch($result))
	{
		$cons2="ALTER TABLE $fila[0] TYPE = MYISAM";
		$res2=mysql_query($cons2);
		if(mysql_error()){echo $fila[0]."<br>";}
	}

?>
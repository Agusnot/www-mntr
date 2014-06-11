<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select medico,numservicio from salud.agenda where numservicio is not null ";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="update salud.servicos set medicotte='$fila[0]' where compania='$Compania[0]' and numservicio='$fila[1]'";
		echo $cons2."<br>";
		//$res2=Exquery($cons2);
	}
?>

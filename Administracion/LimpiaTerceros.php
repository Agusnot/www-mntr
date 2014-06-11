<?
	session_start();
	include("Funciones.php");
	$cons="Select Identificacion from Central.Terceros";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons2="Delete from Central.Terceros where Identificacion='$fila[0]'";

		$res2=ExQuery($cons2);
		echo $cons2.$res2."<br>";
	}

?>
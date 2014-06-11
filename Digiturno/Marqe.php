<?
	include("Funciones.php");
	$cons="Select Msj from digitmensajes Order By Id";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Msj=$Msj.$fila[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

?>
<body bgcolor="#000066">
<marquee scrolldelay="10">
<font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:80px;color:#F90">
<? echo $Msj?></font></marquee>
</body>
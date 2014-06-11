<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
	include("Funciones.php");
	$cons="select identificacion,primape,segape,primnom,segnom,eps from central.terceros 
	where terceros.compania='$Compania[0]' $Serv1 and tipo ='Paciente' and identificacion='$Cedula'
	group by PrimApe,SegApe,PrimNom,SegNom,identificacion,eps Order By PrimApe,SegApe,PrimNom,SegNom";		
	//echo $cons."<br>";
	$resultado = ExQuery($cons,$conex);
	$fila=ExFetch($resultado);
	$Paciente[1]=$fila[0];
	//echo $fila[0]."<br>";
	$n=1;
	for($i=1;$i<=ExNumFields($resultado);$i++)
	{
		$n++;
		$Paciente[$n]=$fila[$i];
		//echo $fila[$i]."<br>";
	}
	//echo $Paciente[1];
?>
<script language='JavaScript'>
	parent.parent.location.href='HistoriaClinica.php?DatNameSID=<? echo $DatNameSID?>';
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
</body>
</html>
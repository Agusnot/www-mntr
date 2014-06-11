<?
	session_start();
?>
<body background="/Imgs/Fondo.jpg">
<?
	$db = dbase_open('/aplresolucion5544/Iaent.dbf', 0);
	$numero_registros = dbase_numrecords($db);
	for($i=1;$i<=$numero_registros;$i++)
	{
		$row = dbase_get_record($db, $i);
		$AnioDBASE=trim($row[1]);
		$TrimestreDBASE=trim($row[2]);
		$CodDBASE=trim($row[3]);
		if($AnioDBASE==$Anio && $TrimestreDBASE==$Trimestre && $Codigo==$CodDBASE)
		{
			$Permite=1;
		}
		else
		{
			if(!$Permite){$Permite=0;}
		}
	}


	$db = dbase_open("/aplresolucion5544/$Tabla.dbf", 0);
	$numero_registros = dbase_numrecords($db);
	for($i=1;$i<=$numero_registros;$i++)
	{
		$row = dbase_get_record($db, $i);
		$AnioDBASE=$row[0];
		$TrimestreDBASE=$row[1];
		if($AnioDBASE==$Anio && $TrimestreDBASE==$Trimestre)
		{
			$Permite=2;
		}
	}


	if($Permite==0)
	{
		echo "<br><center><strong><em>No puede registrar informacion en SIDEF, periodos no establecidos!!!</em>";
	}
	elseif($Permite==2)
	{
		echo "<br><center><em><strong>Ya se registró información para este periodo, retire los registros antes de volver a transferir!!!</em>";
	}
	else
	{
		$db = dbase_open("/aplresolucion5544/$Tabla.dbf", 2);
		for($w=1;$w<=count($DatosSIDEF);$w++)
		{
			dbase_add_record($db, $DatosSIDEF[$w]);
		}
		dbase_pack($db);
		dbase_close($db);
	}

?>
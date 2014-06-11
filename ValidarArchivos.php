<?
	function ValidarArchivos()
	{
		$cons98="Select * from central.revesquemas where tipo=2";
		$res98=ExQuery($cons98);
		while($fila98=ExFetch($res98))
		{
			$DBEs[$fila98[1]]=$fila98[1];
		}
	
		$Directorio[1]="Contabilidad";
		$Directorio[2]="Presupuesto";
		$Directorio[3]="";
		$Directorio[4]="Informes/Contabilidad/Reportes";
		$Directorio[5]="Informes/Presupuesto/Reportes";
		$Directorio[6]="Consumo";
		$Directorio[7]="Contratacion";
		$Directorio[8]="HistoriaClinica";
		$Directorio[9]="HistoriaClinica/Administracion";
		$Directorio[10]="HistoriaClinica/Operacion";
		$Directorio[11]="HistoriaClinica/Formatos_Fijos";
		$Directorio[12]="Informes/Almacen/Reportes";
		$Directorio[13]="Imgs";
		$Directorio[14]="Predial";

		foreach($DBEs as $i)
		{

			$cons="Select Ruta,Archivo,Vigencia,ValVigencia from central.validaarchivos where Ruta=$i";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				$ArchivoEvaluar=$fila[1];$FechaRegistro=$fila[2];
				if($Directorio[$i]){$DirBusq=$Directorio[$i]."/".$ArchivoEvaluar;}
				else{$DirBusq=$ArchivoEvaluar;}
				if(is_file($DirBusq))
				{
					if($fila[3]==1)
					{
						$FechaArchivo=date("Ymd", filemtime($DirBusq));
						if($FechaRegistro!=$FechaArchivo)
						{
							$m++;
							$ArchivosDesactualizados[$m]=$DirBusq."(".$FechaArchivo.")";
						}
					}
				}
				else
				{
					$j++;
					$ArchivosNoExisten[$j]=$DirBusq;
				}
			}
		}

		if($ArchivosDesactualizados || $ArchivosNoExisten)
		echo "<font size='2' color='blue'><em>Se ha detectado variaci&oacute;n en la estructura de archivos:";

		if($ArchivosDesactualizados)
		{
			echo "<li><strong>Achivos No Vigentes:</strong> ";
			for($x=1;$x<=count($ArchivosDesactualizados);$x++)
			{
				echo "$ArchivosDesactualizados[$x]- ";
			}
		}
		
		if($ArchivosNoExisten)
		{
			echo "<li><strong>Achivos NO EXISTENTES:</strong> ";
			for($x=1;$x<=count($ArchivosNoExisten);$x++)
			{
				echo "$ArchivosNoExisten[$x]- ";
			}
		}
	}
?>
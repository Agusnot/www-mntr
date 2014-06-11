<?
	include("Funciones.php");
	$ND=getdate();
	$cons="Select Tipo from Modulos where IdModulo=$Modulo";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Tipo=$fila[0];
	if($Tipo=="Derecha"){$condAdc=" and ModAsignado=$Modulo";}
	else{$condAdc=" and ModAsignado =0 ";}
	
	if($Pasar)
	{
		$cons2="Update Digiturno set HoraE='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where Turno=$SigTurno and Fecha='$ND[year]-$ND[mon]-$ND[mday]' $condAdc";
		$res2=ExQuery($cons2);

		if($Pasar!="No Remitir")
		{
			$Pasar=explode("-",$Pasar);
			$Pasar=$Pasar[0];
			$cons="Insert into Digiturno (fecha,Turno,modasignado)
			values('$ND[year]-$ND[mon]-$ND[mday]',$SigTurno,$Pasar)";
			$res=ExQuery($cons);
			echo ExError();
		}
	}

	if(!$Modulo){exit;}
	if($Atender)
	{
		
		$cons="Select Turno from Digiturno where Modulo IS NULL and Fecha='$ND[year]-$ND[mon]-$ND[mday]' $condAdc Order By Turno Asc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$SigTurno=$fila[0];
		if($SigTurno)
		{
			$cons2="Update Digiturno set Modulo=$Modulo,HoraI='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where Turno=$SigTurno and Fecha='$ND[year]-$ND[mon]-$ND[mday]' $condAdc";
			$res2=ExQuery($cons2);
			$Cambio=1;
		}
		else
		{
			echo "No se encontraron turnos pendientes";
			$Tipo=9999;$Atender=0;
		}
	
		if(!$Tipo)
		{
			echo "<form name='FORMA2'>";
			$cons="Select NombreMod,Definicion,IdModulo from Modulos where Tipo='Derecha'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				echo "<button type='submit' style='width:300px;height:50px;' name='Pasar' value=$fila[2]>$fila[2] - $fila[0]<br>$fila[1]</button>";
			}
				echo "<button type='submit' style='width:300px;height:50px;' name='Pasar' value='-1'>No Remitir</button>";
			echo "<input type='hidden' name='SigTurno' value='$SigTurno' />";
			echo "<input type='hidden' name='Modulo' value='$Modulo' />";
			echo "</form>";
		}
	}
?>
<body bgcolor="#6699FF">
<form name="FORMA">
<input type="hidden" name="Modulo" value="<? echo $Modulo?>" />
<?	if(!$Atender || $Tipo)
	{?>
<input type="submit" name="Esperar" style='width:300px;height:50px;' value="Poner en espera">
<input type="submit" name="Atender" style='width:300px;height:50px;' value="Llamar Siguiente Turno">
<?	}?>

</form>
</body>
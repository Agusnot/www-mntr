<?
	include("Funciones.php");
	$ND=getdate();
	if($Tipo=="Derecha"){$condAdc=" and ModAsignado=$Modulo";}
	else{$condAdc=" and ModAsignado = 0 ";}
	$cons="Select Turno from Digiturno where Modulo=$Modulo and Fecha='$ND[year]-$ND[mon]-$ND[mday]' $condAdc Order By Turno Desc";

	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TurnoAct=$fila[0];
	if($TurnoAct)
	{
		$TurnoAct=substr("00",1,2-strlen($TurnoAct)).$TurnoAct;
		if($Tipo=="Derecha"){$Size="85";}else{$Size="113";}
		echo "<center><font style='font-size:$Size px;color:yellow'>$TurnoAct</font></center>";
		$cons="Update Digiturno set Valida=1 where Modulo=$Modulo and Turno=$TurnoAct $condAdc";
		$res=ExQuery($cons);echo ExError();
	}
exit;
?>
<html>
<body>
<embed src="Bell1.mp3" autostart="true" style="visibility:hidden; width:1px; height:1px;">
</body>
</html>
<?
	include("Funciones.php");
	include("CalcularSaldos.php");
	ini_set("memory_limit","512M");
	$ND=getdate();
	$NoEditar=0;
?>
<style>
	font{
	font-family:Tahoma;
	font-size:16;
	}
</style>
<title>Compuconta Software</title>
<body background="/Imgs/Fondo.jpg">
<?
	$cons="Select sum(Credito),sum(ContraCredito),TipoComprobant,Fecha,date_part('month',Fecha),date_part('year',Fecha) 
	from Presupuesto.Movimiento,Presupuesto.Comprobantes where 
	Movimiento.Comprobante=Comprobantes.Comprobante and lower(Movimiento.Comprobante)=lower('$Comprobante') and Numero='$Numero' 
	and Comprobantes.Compania='$Compania[0]'
	and Estado='AC' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Group By TipoComprobant,Fecha";

	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Mes=$fila[4];$Anio=$fila[5];
	if($fila[0]){$Valor=$fila[0];}else{$Valor=$fila[1];}

	$CompOriginal=$Comprobante;$NumOriginal=$Numero;$Fecha=$fila[3];

	echo "<div style='position:absolute;left:-30px;'><ul><font size=4 style=''>$Comprobante No. $Numero Vr. " . number_format($Valor,2)." ($Fecha)<br><br>";

	$cons1="Select Comprobante,Numero,sum(Credito),sum(ContraCredito),Fecha 
	from Presupuesto.Movimiento where lower(CompAfectado)=lower('$Comprobante') and DocSoporte='$Numero' and Estado='AC' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Group By Comprobante,Numero,Fecha";

	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($fila1[2]){$Valor=$fila1[2];}else{$Valor=$fila1[3];}
		$Comprobante=$fila1[0];$Numero=$fila1[1];
		if($Comprobante){
		$i++;
		echo "<ul><img style='width:20px;' src='/Imgs/Flecha.jpg'><font size=4 style=''>$fila1[0] No. $fila1[1] Vr. ".number_format($Valor,2)." ($fila1[4])<br><br></font>";}	
		$cons10="Select Comprobante,Numero,sum(Credito),sum(ContraCredito),Fecha 
		from Presupuesto.Movimiento where lower(CompAfectado)=lower('$fila1[0]') and DocSoporte='$fila1[1]' and Estado='AC' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Group By Comprobante,Numero,Fecha";
		$res10=ExQuery($cons10);
		while($fila10=ExFetch($res10))
		{
			if($fila10[2]){$Valor=$fila10[2];}else{$Valor=$fila10[3];}
			$i++;
			echo "<ul><img style='width:20px;' src='/Imgs/Flecha.jpg'><font size=4 style=''>$fila10[0] No. $fila10[1] Vr. ".number_format($Valor,2)." ($fila10[4])<br><br></font>";
			$cons11="Select Comprobante,Numero,sum(Credito),sum(ContraCredito),Fecha 
			from Presupuesto.Movimiento where lower(CompAfectado)=lower('$fila10[0]') and DocSoporte='$fila10[1]' and Estado='AC' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Group By Comprobante,Numero,Fecha";
			$res11=ExQuery($cons11);
			while($fila11=ExFetch($res11))
			{
				if($fila11[2]){$Valor=$fila11[2];}else{$Valor=$fila11[3];}
				$i++;
				echo "<ul><img style='width:20px;' src='/Imgs/Flecha.jpg'><font size=4 style=''>$fila11[0] No. $fila11[1] Vr. ".number_format($Valor,2)." ($fila11[4])<br><br></font>";
				echo "</ul>";
			}
			echo "</ul>";
		}
		echo "</ul>";
	}
	echo "</ul></div>";
	for($n=1;$n<=$i;$n++)
	{
		echo "<br><br>";
	}
	if(!$Anio){$Anio=$ND[year];}if(!$Mes){$Mes=$ND[mon];}
	$cons2="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Modulo='Presupuesto' and Mes=$Mes and Anio=$Anio";
	$res2=ExQuery($cons2);echo ExError();
	if(ExNumRows($res2)>=1){$NoEditar=2;}
	$cons2="Select * from Presupuesto.Movimiento where Comprobante='$CompOriginal' and Numero='$NumOriginal' and Estado='AC'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==0){$NoEditar=2;}
	ObtieneValoresxDoc("$Anio-01-01","$ND[year]-$ND[mon]-$ND[mday]");
	$Saldo=CalcularSaldoxDoc($NumOriginal,$CompOriginal,"$ND[year]-01-01","$ND[year]-$ND[mon]-$ND[mday]",$Vigencia,$ClaseVigencia);
	if($Saldo==0){$Bloquear=1;}
	else{$Bloquear=0;}
	
?>
<br><br>
<div>
<hr>
<em>
Saldo documento <? echo number_format($Saldo,0)?></em><br>
<?	if($NoEditar==0){?>
<button value="Editar" onClick="opener.parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Comprobante=<? echo $CompOriginal?>&Numero=<? echo $NumOriginal?>&Tipo=<? echo $Tipo?>&Bloquear=<?echo $Bloquear?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>';window.close();"><img src="/Imgs/b_edit.png"> Editar Registro</button>
<?}?>
</div>
</body>
<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	if($PonerSIA)
	{
		$cons="Update Presupuesto.PlanCuentas set SIA='$PonerSIA' where Anio='$Anio' and Compania='$Compania[0]' 
		and Cuenta='$Cuenta' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);
		$SIA=$PonerSIA;
	}
?>
<title>Compuconta Software</title>
<style>body{background:<?echo $Estilo[1]?>;color:<?echo $Estilo[2]?>;font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>}</style>
<style>
	a{color:white;text-decoration:none;}
	a:hover{color:yellow;text-decoration:underline;}
</style>
<body>
<div style="width:900px;">
<img src="/Imgs/home.gif">&nbsp;&nbsp;Codigo SIA <?echo $Anio?><br>
<?
	$cons="Select SIA from Presupuesto.PlanCuentas where Compania='$Compania[0]' and Cuenta='$Cuenta' and Anio='$Anio' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$SIA=$fila[0];

	if(substr($Cuenta,0,1)==1){$Clase="Ingresos";}
	else{$Clase="Gastos";}
	$cons="Select Codigo,Detalle,Tipo from Presupuesto.CodigosSIA where Clase='$Clase' Order By Codigo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$NumCar=strlen($fila[0]);
		for($i=0;$i<=$NumCar;$i++){echo "&nbsp;&nbsp;";}
		if($fila[2]=="Titulo"){echo "<img src='/Imgs/menost.gif'><img src='/Imgs/carpabiertat.gif'>&nbsp;";}
		else{echo "<img src='/Imgs/puntosut.gif'><img src='/Imgs/doct.gif'>&nbsp;";}
		if($fila[2]=="Detalle"){echo "<a name='$fila[0]' href='AsignaSIA.php?DatNameSID=$DatNameSID&Cuenta=$Cuenta&Anio=$Anio&PonerSIA=$fila[0]&Vigencia=$Vigencia&ClaseVigencia=$ClaseVigencia'>";}
		
		if($SIA==$fila[0]){echo "<font color='yellow'><strong>";}
		echo "$fila[0] $fila[1]";
		echo "</font></strong>";
		echo "<br></a>";
	}
?>
</div>
<br><br>
<input type="Button" value="Regresar" onClick="window.close();opener.location.href=opener.location.href;">
</body>
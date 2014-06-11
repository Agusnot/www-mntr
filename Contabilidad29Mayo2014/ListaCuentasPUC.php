<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($AnioCamb){$AnioAc=$AnioCamb;}echo $Compania[0];
?>
<style>body{color:<?echo $Estilo[2]?>;font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>}</style>
<style>
	a{color:#0098d8;text-decoration:none;}
	a:hover{font-weight:bold;text-decoration:underline;}
</style>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg">
<div style="width:900px;">
<a href='DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Nuevo=1&Mayores=1&Cuenta=&Seccion=0&Tipo=Titulo&CtaBuscar=<?echo $CtaBuscar?>' target='Derecha'><img border="0" src="/Imgs/home.gif">&nbsp;&nbsp;Plan de Cuentas (<? echo $AnioAc?>)</a><br>
<?
	if($CtaBuscar){$CondAdc=" and Cuenta ilike '$CtaBuscar%'";}
	$cons="Select Cuenta,Nombre,Tipo from Contabilidad.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioAc $CondAdc Order By Cuenta";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$NumCar=strlen($fila[0]);
		for($i=0;$i<=$NumCar;$i++){echo "&nbsp;&nbsp;";}
		if($fila[2]=="Titulo"){echo "<img src='/Imgs/menost.gif'><img src='/Imgs/carpabiertat.gif'>&nbsp;";}
		else{echo "<img src='/Imgs/puntosut.gif'><img src='/Imgs/doct.gif'>&nbsp;";}
		echo "<a name='$fila[0]' href='DetalleCuenta.php?DatNameSID=$DatNameSID&Cuenta=$fila[0]&Seccion=0&CtaBuscar=$CtaBuscar' target='Derecha'>$fila[0] $fila[1]<br></a>";
	}
?>
</div>
</body>

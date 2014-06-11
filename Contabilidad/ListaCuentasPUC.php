	<?
		if($DatNameSID){session_name("$DatNameSID");}
		session_start();
		include("Funciones.php");
		$ND=getdate();
		if($AnioCamb){$AnioAc=$AnioCamb;}echo $Compania[0];
	?>

	<html>
		<head>
			<?php echo $codificacionMentor; ?>
			<?php echo $autorMentor; ?>
			<?php echo $titleMentor; ?>
			<?php echo $iconMentor; ?>
			<?php echo $shortcutIconMentor; ?>
			<link rel="stylesheet" type="text/css" href="../General/Estilos/estilos.css">
			<style>
				a{color:#0098d8;text-decoration:none;}
				a:hover{font-weight:bold;text-decoration:underline;}
			</style>
		</head>
		<body>
		<div style="width:900px;">
			<a href='DetalleCuenta.php?DatNameSID=<? echo $DatNameSID?>&Nuevo=1&Mayores=1&Cuenta=&Seccion=0&Tipo=Titulo&CtaBuscar=<?echo $CtaBuscar?>' target='Derecha'>
				<img border="0" src="/Imgs/home.gif">&nbsp;&nbsp;Plan de Cuentas (<? echo $AnioAc?>)</a><br>
				<?
					if($CtaBuscar){$CondAdc=" and Cuenta ilike '$CtaBuscar%'";}
					$cons="Select Cuenta,Nombre,Tipo from Contabilidad.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioAc $CondAdc Order By Cuenta";
					$res=ExQuery($cons);
					while($fila=ExFetch($res))
					{
						$NumCar=strlen($fila[0]);
						for($i=0;$i<=$NumCar;$i++){echo "&nbsp;&nbsp;";}
						if($fila[2]=="Titulo"){echo "<img src='/Imgs/menost.gif'><img src='/Imgs/carpabiertat_.gif'>&nbsp;";}
						else{echo "<img src='/Imgs/puntosut.gif'><img src='/Imgs/doct.gif'>&nbsp;";}
						echo "<a name='$fila[0]' href='DetalleCuenta.php?DatNameSID=$DatNameSID&Cuenta=$fila[0]&Seccion=0&CtaBuscar=$CtaBuscar' target='Derecha'>$fila[0] $fila[1]<br></a>";
					}
				?>
		</div>
		</body>

<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include_once("General/Configuracion/Configuracion.php");
	$ND=getdate();
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
					a{color:black;text-decoration:none;}
					a:hover{color:blue;text-decoration:underline;}
					body {
						font-size:12px;
					}
				</style>
			</head>
		
			<body>
					<div style="width:900px;">
					<a onClick="parent.frames.Detalle.location.href='ConfEncxCC.php?DatNameSID=<? echo $DatNameSID?>&Madre=1&Anio=<? echo $Anio?>&Nuevo=1'" href="#"><img border="0" src="/Imgs/home.gif">&nbsp;&nbsp;Centros de Costo</a><br>
					<?
						$cons="Select Codigo,CentroCostos,Tipo from Central.CentrosCosto where Compania='$Compania[0]' and Anio=$Anio Order By Codigo";
						$res=ExQuery($cons);
						while($fila=ExFetch($res))
						{
							$NumCar=strlen($fila[0]);
							for($i=0;$i<=$NumCar;$i++){echo "&nbsp;&nbsp;";}

							if($fila[2]=="Titulo"){?><a style="cursor:hand" onClick="parent.frames.Detalle.location.href='ConfEncxCC.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>&Anio=<? echo $Anio?>&Nuevo=1'"><img border="0" src="/Imgs/menost.gif"><img border="0" src="/Imgs/carpabiertat.gif"></a>&nbsp;<? }
							else{echo "<img src='/Imgs/puntosut.gif'><img src='/Imgs/doct.gif'>&nbsp;";}?>
							<a name="<? echo $fila[0]?>" href="#<? echo $fila[0]?>" onClick="parent.frames.Movimiento.location.href='ConfMovxCC.php?DatNameSID=<? echo $DatNameSID?>&Cuenta=<? echo $fila[0]?>&Anio=<? echo $Anio?>';parent.frames.Detalle.location.href='ConfEncxCC.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $fila[0]?>&Anio=<? echo $Anio?>&Edit=1'"><? echo "$fila[0] $fila[1]"; ?><br></a>
					<?	}
					?>
					</div>
			</body>
		</html>	

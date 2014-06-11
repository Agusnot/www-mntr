<?php
	header("Content-type: image/jpeg");
	session_start();
	mysql_select_db("salud", $conex);
	$Cedula=$Paciente[1];
	$cons="SELECT count( CodConsulta ),Cedula FROM `notasevolucion` group by cedula having cedula=$Cedula";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Total=$fila[0];

	$ancho=500;
	$alto  = 160;  // de la imagen que se genera

		$imagen = imagecreate($ancho,$alto);
		$blanco = imagecolorallocate($imagen, 255, 255, 255);
		$verde = imagecolorallocate($imagen, 0, 255, 0);
		$negro  = imagecolorallocate($imagen,0, 0, 0);
		$rojo=imagecolorallocate($imagen,255, 0, 0);
		$azul=imagecolorallocate($imagen,0, 0, 255);
		$amarillo=imagecolorallocate($imagen,216, 254, 0);
		$colorlindash=imagecolorallocate($imagen, 203, 202, 207);

	
	imagerectangle($imagen,1,2,499,156,$negro);
	
	function DibujaBarras($CodConsulta,$Altura,$Cedula,$Color,$imagen,$Total,$Grosor,$Msj)
	{
		$cons = "SELECT count(CodConsulta),CodConsulta,Cedula FROM `notasevolucion` group by CodConsulta,Cedula having Cedula=$Cedula and CodConsulta=$CodConsulta";
		$resultado=ExQuery($cons);
		$fila=ExFetch($resultado);
		imagestring($imagen,1,15,$Altura+($Grosor/2), "$Msj" , $Color);
		$Ubicac=$fila[0]*100/$Total;
		imagerectangle($imagen,100,$Altura,($Ubicac*2.8)+100,$Altura+$Grosor,$Color);
		if($fila[0]>0){$Inc=101;}
		else{$Inc=100;}
		imagefill($imagen,$Inc,$Altura+1,$Color);
		imagedashedline($imagen,1,$Altura,750,$Altura,$Color);
	}
	$Altura=12;
	DibujaBarras(39131,$Altura,$Cedula,$azul,$imagen,$Total,35,'Medicina General');$Altura=$Altura+36;
	DibujaBarras(39143,$Altura,$Cedula,$verde,$imagen,$Total,35,'Psiquiatria');$Altura=$Altura+36;
	DibujaBarras(35102,$Altura,$Cedula,$rojo,$imagen,$Total,35,'Psicologia');$Altura=$Altura+36;
	DibujaBarras(37601,$Altura,$Cedula,$azul,$imagen,$Total,35,'Nutrición');$Altura=$Altura+36;
	imagejpeg($imagen);
	imagedestroy($imagen);

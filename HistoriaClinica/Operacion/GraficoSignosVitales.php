<?php
	header("Content-type: image/jpeg");
	session_start();
	mysql_select_db("salud", $conex);
	$cons = "Select * From signosvitales Where Cedula='$Paciente[1]' Order By Fecha";
	$resultado=ExQuery($cons,$conex);
	$num_records=mysql_num_rows($resultado);
	
	if($num_records<10)
	{
		$ancho=500;
	}
	else
	{
		$ancho = $num_records*40;  // de la imagen que se genera
	}
	$alto  = 550;  // de la imagen que se genera

	$imagen = imagecreate($ancho,$alto);

//	$blanco = imagecolorallocate($imagen, 193, 202, 247);
	$blanco = imagecolorallocate($imagen, 255, 255, 255);
	$verde = imagecolorallocate($imagen, 0, 255, 0);
	$negro  = imagecolorallocate($imagen,0, 0, 0);
	$rojo=imagecolorallocate($imagen,255, 0, 0);
	$azul=imagecolorallocate($imagen,0, 0, 255);
	$amarillo=imagecolorallocate($imagen,216, 254, 0);
	$colorlindash=imagecolorallocate($imagen, 203, 202, 207);
	
	//ImageLine(Imagen,PuntoFinalX,PuntoFinalY,PuntoInicialX,PuntoInicialY,Color);
	//Toda esta parte es para dibujar los encabezados...
	ImageLine($imagen,10,$alto-10,10,10,$azul);
	ImageLine($imagen,60,$alto-10,60,50,$azul);
	ImageLine($imagen,110,$alto-10,110,10,$azul);
	ImageLine($imagen,$ancho-10,$alto-10,$ancho-10,10,$azul);


	ImageLine($imagen,10,77,$ancho-10,77,$azul);
	ImageLine($imagen,10,65,$ancho-10,65,$azul);
	ImageLine($imagen,10,50,$ancho-10,50,$azul);
	ImageLine($imagen,10,10,$ancho-10,10,$azul);
	ImageLine($imagen,10,540,$ancho-10,540,$azul);

	
	imagestring($imagen,1,15,67, "P. Arter" , $azul);

	imagestring($imagen,1,65,67, "Respirac." , $azul);
	imagestring($imagen,1,70,155, "Pulso" , $azul);
	imagestring($imagen,1,72,342, "Temp." , $azul);

	imagestring($imagen,2,45,25, "Fecha" , $azul);
	imagestring($imagen,2,80,50, "Hora" , $azul);	
	
	//Desde aqui dibujo los ejes en y para presion arterial
	
	for($i=250;$i>=30;$i=$i-10)
	{
		imagestring($imagen,2,25,580-($i*2), $i , $azul);
		imagedashedline($imagen,10,580-($i*2)+15,$ancho-10,580-($i*2)+15,$colorlindash);
	}

	//Desde aqui dibujo los ejes en y para respiracion
	
	$BaseAltura=160;
	for($i=40;$i>=10;$i=$i-10)
	{
		
		imagestring($imagen,2,75,$BaseAltura-(2*$i), $i , $azul);
	}

	//Desde aqui dibujo los ejes en y para Pulso

	$BaseAltura=420;
	for($i=130;$i>=60;$i=$i-10)
	{
		imagestring($imagen,2,75,$BaseAltura-(2*$i), $i , $azul);
	}

	//Desde aqui dibujo los ejes en y para Temperatura

	$BaseAltura=1150;
	for($i=42;$i>=34;$i=$i-1)
	{
		imagestring($imagen,2,75,$BaseAltura-(20*$i)+50, $i , $azul);
		imagestring($imagen,1,87,$BaseAltura-(20*$i)+47, "o" , $azul);
	}

//Aqui empiezo a graficar los datos...
//1) Debo ubicar los ejes en x con las fechas y las horas -> Estos salen de la base de datos

	$i=1;
	$n=1;
	$fecha_act="0000-00-00";
	$ubicacion=100;
	while($fila=ExFetch($resultado))
	{
		if($fecha_act!=$fila[0])
		{
			$fecha_act=$fila[0];

			$cons1 = "Select * From signosvitales Where Cedula='$Paciente[1]' And Fecha='$fila[0]'";
			$resultado1=ExQuery($cons1,$conex);

			$num_registros=mysql_num_rows($resultado1);
			$i=$i+$num_registros/8;
			$recactual=0;
			
			$mitadrec=($num_registros/2);
			$mitadrec = str_replace(".5", "", $mitadrec);
			if($mitadrec==0){$mitadrec=1;}
			while($fila1=ExFetch($resultado1))
			{
				$recactual++;
				if($mitadrec==$recactual)
				{
					if($num_registros==1)
					{
						imagestring($imagen,1,93+($n*28),15,substr($fila[0],2,2), $azul);//Escribe el año
						imagestring($imagen,1,93+($n*28),25,substr($fila[0],5,2), $azul);//Escribe el año
						imagestring($imagen,1,93+($n*28),35,substr($fila[0],8,2), $azul);//Escribe el año
					}
					else
					{
						imagestring($imagen,2,100+($n*28),20,substr($fila[0],0,4), $azul);//Escribe el año
						imagestring($imagen,2,96+($n*28),30,substr($fila[0],5,5), $azul);//Escribe el año
					}
				}
				imagedashedline($imagen,110+($n*28),$alto-10,110+($n*28),53,$colorlindash);
				$n++;imagestring($imagen,1,57+($n*28),54, $fila1[1], $azul);

				//Simultaneamente se crean los ejes y las lineas empezamos a dibujar las lineas del grafico
				//Primero para la temperatura-> Esto sale de la base de datos...

				$Temperatura=$fila1[4];
				if($Temperatura!=0)
				{
	//				imagestring($imagen,1,75+($n*20),(-1*$Temperatura*20)+1201, $Temperatura, $azul);
					imagestring($imagen,1,65+($n*28),(-1*$Temperatura*20)+1201, 'o', $rojo);
					$xinic=66+($n*28);
					$yinic=(-1*$Temperatura*20)+1205;
					if(!$xfinal){$xfinal=66+($n*28);}
					if(!$yfinal){$yfinal=(-1*$Temperatura*20)+1205;}
					imageline($imagen,$xfinal,$yfinal,$xinic,$yinic,$rojo);
					$xfinal=66+($n*28);
					$yfinal=(-1*$Temperatura*20)+1205;
				}
				else
				{
					$xinic=0;
					$yinic=0;
					$xfinal=0;
					$yfinal=0;
				}

				
				//Simultaneamente se crean los ejes y las lineas empezamos a dibujar las lineas del grafico
				//Siguiente la respiracion-> Esto sale de la base de datos...

				$Respiracion=$fila1[5];
				if($Respiracion!=0)
				{
	//				imagestring($imagen,1,75+($n*20),(-1*$Respiracion*2)+162, $Respiracion, $azul);
					imagestring($imagen,1,65+($n*28),(-1*$Respiracion*2)+162, 'o', $azul);
					$xinic1=66+($n*28);
					$yinic1=(-1*$Respiracion*2)+166;
					if(!$xfinal1){$xfinal1=66+($n*28);}
					if(!$yfinal1){$yfinal1=(-1*$Respiracion*2)+166;}
					imageline($imagen,$xfinal1,$yfinal1,$xinic1,$yinic1,$azul);
					$xfinal1=66+($n*28);
					$yfinal1=(-1*$Respiracion*2)+166;
				}
				else
				{
					$xinic1=0;
					$yinic1=0;
					$xfinal1=0;
					$yfinal1=0;
				}


				//Siguiente el pulso-> Esto sale de la base de datos...
				
				$Pulso=$fila1[6];
				if($Pulso!=0)
				{
	//				imagestring($imagen,1,75+($n*20),(-1*$Pulso*2)+420, $Pulso, $azul);
					imagestring($imagen,1,65+($n*28),(-1*$Pulso*2)+420, 'o', $verde);
					$xinic2=66+($n*28);
					$yinic2=(-1*$Pulso*2)+424;
					if(!$xfinal2){$xfinal2=66+($n*28);}
					if(!$yfinal2){$yfinal2=(-1*$Pulso*2)+424;}
					imageline($imagen,$xfinal2,$yfinal2,$xinic2,$yinic2,$verde);
					$xfinal2=66+($n*28);
					$yfinal2=(-1*$Pulso*2)+424;
				}
				else
				{
					$xinic2=0;
					$yinic2=0;
					$xfinal2=0;
					$yfinal2=0;
				}

				//Finalmente la presion aterial, dos valores-> Esto sale de la base de datos...
				
				$PI1=$fila1[7];
				$PI2=$fila1[8];

//				imagestring($imagen,1,75+($n*20),(-1*$PI1*2)+582, $PI1, $azul);
				$yinic4=(-1*$PI1*2)+590;
				$yfinal4=(-1*$PI2*2)+583;
				imagestring($imagen,1,65+($n*28),(-1*$PI1*2)+582, 'o', $negro);
				imagestring($imagen,1,65+($n*28),(-1*$PI2*2)+580, 'o', $negro);
				imageline($imagen,66+($n*28),$yfinal4,66+($n*28),$yinic4,$negro);
			}
			imageline($imagen,82+($n*28),$alto-10,82+($n*28),10,$azul);
		}
		$i++;imagestring($imagen,1,$ubicacion,12, $limite, $azul);//Escribe el año
	}
	
	
	imagejpeg($imagen);
	imagedestroy($imagen);

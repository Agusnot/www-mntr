<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta></head>
<?
	function QuitaSignos($Contenido)
	{
		$Contenido=str_replace("Ñ","N",$Contenido);
		$Contenido=str_replace("ñ","n",$Contenido);
		$Contenido=str_replace("á","a",$Contenido);
		$Contenido=str_replace("é","e",$Contenido);
		$Contenido=str_replace("í","i",$Contenido);
		$Contenido=str_replace("ó","o",$Contenido);
		$Contenido=str_replace("ú","u",$Contenido);
		$Contenido=str_replace("°","o",$Contenido);
		$Contenido=str_replace("º","o",$Contenido);
		$Contenido=str_replace("ª","a",$Contenido);
		$Contenido=str_replace("Á","A",$Contenido);
		$Contenido=str_replace("É","E",$Contenido);
		$Contenido=str_replace("Í","I",$Contenido);
		$Contenido=str_replace("Ó","O",$Contenido);
		$Contenido=str_replace("Ú","U",$Contenido);
		$Contenido=str_replace("Ü","U",$Contenido);
		$Contenido=str_replace("ü","u",$Contenido);
		$Contenido=str_replace("Ð","N",$Contenido);
		$Contenido=str_replace("¾","O",$Contenido);
		$Contenido=str_replace("±","N",$Contenido);
		$Contenido=str_replace("à","a",$Contenido);
		$Contenido=str_replace("è","e",$Contenido);
		$Contenido=str_replace("ì","i",$Contenido);
		$Contenido=str_replace("ò","o",$Contenido);
		$Contenido=str_replace("ù","u",$Contenido);

		$Contenido=str_replace("À","A",$Contenido);
		$Contenido=str_replace("È","E",$Contenido);
		$Contenido=str_replace("Ì","I",$Contenido);
		$Contenido=str_replace("Ò","O",$Contenido);
		$Contenido=str_replace("Ù","U",$Contenido);

		$Contenido=str_replace("½","1/2",$Contenido);
		$Contenido=str_replace("¾","3/4",$Contenido);
		$Contenido=str_replace("´",".",$Contenido);
		$Contenido=str_replace("–"," ",$Contenido);
		$Contenido=str_replace("ô","o",$Contenido);
		$Contenido=str_replace("¨"," ",$Contenido);
		$Contenido=str_replace("-","",$Contenido);
		$Contenido=str_replace("/"," ",$Contenido);
		$Contenido=str_replace("ò","o",$Contenido);
		$Contenido=str_replace("à","a",$Contenido);
		$Contenido=str_replace("Ç","a",$Contenido);
		$Contenido=str_replace("!","a",$Contenido);
		$Contenido=str_replace("¡","a",$Contenido);
		$Contenido=str_replace("ö","o",$Contenido);
		$Contenido=str_replace("ï","i",$Contenido);
		$Contenido=str_replace("•","*",$Contenido);
		$Contenido=str_replace("\" ","",$Contenido);
		$Contenido=str_replace("\\","",$Contenido);

		return $Contenido;
	}
	$conex2 = mysql_connect("10.18.176.103", "root", '');
	$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');

//////////Movimientos
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,
			UsuarioCre,FechaCre,Modificadox,FechaMod,Cerrado,FormaPago,NoCheque,Banco,DiasVencimiento,DetConcepto,Estado,DocDestino,ConceptoRte,PorcRetenido,
			TipoPago,ClasePago,BancoRecRec,FechaDocumento,NULL,NULL
			from Contabilidad.movimientoNomina";
	$res=mysql_query($cons);echo mysql_error();
	while($fila=mysql_fetch_row($res))
	{
		$AutoId++;
		$fila[12]=QuitaSignos($fila[12]);
		$fila[13]=QuitaSignos($fila[13]);

		if($fila[31]=="0000-00-00"){$fila[31]="NULL";}else{$fila[31]="'$fila[31]'";}
		$Anio=substr($fila[1],0,4);
		if(!$fila[18]){$fila[18]="NULL";}else{$fila[18]="'$fila[18]'";}
		if(!$fila[28]){$fila[28]="NULL";}else{$fila[28]="'$fila[28]'";}
		if(!$fila[27]){$fila[27]="NULL";}else{$fila[27]="'$fila[27]'";}
		if(!$fila[25]){$fila[25]="NULL";}else{$fila[25]="'$fila[25]'";}
		if(!$fila[26]){$fila[26]="0";}
		$fila[5]=QuitaSignos($fila[5]);
		$fila[22]=QuitaSignos($fila[22]);
		$fila[20]=QuitaSignos($fila[20]);
		$fila[25]=QuitaSignos($fila[25]);
		$fila[28]=QuitaSignos($fila[28]);
		$fila[27]=QuitaSignos($fila[27]);
		$fila[26]=QuitaSignos($fila[26]);
		$fila[2]=QuitaSignos($fila[2]);
		$fila[2]=ucfirst(strtolower($fila[2]));

		$cons2="INSERT INTO contabilidad.movimiento(
			Autoid, fecha, comprobante, numero, identificacion, detalle, 
            cuenta, debe, haber, cc, docsoporte, basegravable, compania, 
            usuariocre, fechacre, modificadox, fechamod, cerrado, formapago, 
            nocheque, banco, diasvencimiento, estado, docdestino, 
            conceptorte, porcretenido, tipopago, clasepago, bancorecrec, 
            fechadocumento, anio) 
			values ($AutoId,'$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]','$fila[6]',$fila[7],$fila[8],'000',
			'$fila[10]',$fila[11],'$fila[12]','$fila[13]','$fila[14]',NULL,NULL,'$fila[17]',$fila[18],'$fila[19]',
			'$fila[20]','$fila[21]','$fila[23]','$fila[24]',$fila[25],$fila[26],$fila[27],$fila[28],'$fila[29]',
			'$fila[1]',$Anio)";		
	//		echo $fila[2].$fila[3]." id: ".$fila[0]."<br>";
		$res2=pg_query($cons2);
		
	}

	echo "Proceso Finalizado, copiados $AutoId";
?>
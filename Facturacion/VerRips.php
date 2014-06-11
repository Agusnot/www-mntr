<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	$cons2="select departamento,codigo from central.departamentos";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2)){
		$Departamentos[$fila2[0]]=$fila2[1];
	}	
	$cons2="select codmpo,municipio,departamento from central.municipios";// where departamento='$fila[8]' and municipio='$fila[9]'	
	$res2=ExQuery($cons2); 	
	while($fila2=ExFetch($res2)){
		$Municipios[$fila2[1]][$fila2[2]]=$fila2[0];
	}	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="1" align="center">
<?
$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and identificacion='$Entidad'";
$res=ExQuery($cons);
$fila=ExFetch($res);
?>
<tr><td align="center"><strong><? echo "RIPS $fila[0]";?></strong></td></tr>
<?	if($Contrato!=''){$Contra="and facturascredito.contrato='$Contrato'";}
	if($NoContrato!=''){$NoContra="and facturascredito.nocontrato='$NoContrato'";}
	
	//US: Archivo de usuarios de los servicios de salud
	$cons="select tipodoc,codigo from central.tiposdocumentos";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$TipsDocs[$fila[0]]=$fila[1];
	}
	$cons="select tiposdocumentos.codigo,cedula,primape,segape,primnom,segnom,fecnac,sexo,departamento,municipio,zonares
	from facturacion.facturascredito,facturacion.liquidacion,central.terceros,central.tiposdocumentos
	where facturascredito.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]'
	and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and facturascredito.nofactura=liquidacion.nofactura 
	and liquidacion.nofactura is not null and facturascredito.estado='AC'
	and facturascredito.entidad='$Entidad' and terceros.identificacion=cedula and tiposdocumentos.tipodoc=terceros.tipodoc 
	and facturascredito.contrato=liquidacion.contrato $Contra $NoContra
	group by tiposdocumentos.codigo,cedula,primape,segape,primnom,segnom,fecnac,sexo,departamento,municipio,zonares";
	
	$cons="select tipodoc,cedula,primape,segape,primnom,segnom,fecnac,sexo,departamento,municipio,zonares
	from facturacion.facturascredito,facturacion.liquidacion,central.terceros
	where facturascredito.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]'
	and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and facturascredito.nofactura=liquidacion.nofactura 
	and liquidacion.nofactura is not null and facturascredito.estado='AC'
	and facturascredito.entidad='$Entidad' and terceros.identificacion=cedula and cedula!='800246953-2' 
	and facturascredito.contrato=liquidacion.contrato $Contra $NoContra
	group by tipodoc,cedula,primape,segape,primnom,segnom,fecnac,sexo,departamento,municipio,zonares
	order by cedula";	
	
	//echo $cons;
	$res=ExQuery($cons);
	$ContUS=ExNumRows($res);
	if($ContUS>0){
	 	$Archivo="";
		while($fila=ExFetch($res)){
			$cons2="select codigosgsss,tiposaseguramiento.codigo from central.terceros,central.tiposaseguramiento where compania='$Compania[0]' and identificacion='$Entidad'
			and tiposaseguramiento.tipo=tipoasegurador"; //echo $cons2;
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); $codsgsss=$fila2[0]; $tipoaseg=$fila2[1];		
			
			//echo $Departamentos[$fila[8]]." ";
			$fila[8]=$Departamentos[$fila[8]];
			//echo $fila[8]." ";	
			$muncip=$Municipios[$fila[9]][$fila[8]];
			$fechanac=explode("-",$fila[6]);
			//echo $fila[6];
			$Años=$ND[year]-$fechanac[0];
			$Meses=$ND[mon]-$fechanac[1];
			$Dias=$ND[mday]-$fechanac[2];
			if($fila[10]="Urbana"){ $fila[10]="U";}elseif($fila[10]="Rural"){$fila[10]="R";}
			if($Meses<0){
				$Años--;			
			}
			else{
				if($Dias<0&&$Meses==0){
					$Años--;
				}
			}
		
			if($Años>=2){
				$Edad=$Años;
				$MedEdad=1;
			}
			else{
				if($Años>=1){
					$AuxMeses=12;
					$MedEdad=2;
					$Edad=$AuxMeses+$Meses;
					if($Dias>0){
						$Edad--;
					}
				}
				else{				
					if($Meses>0){
						$MedEdad=2;
						$Edad=$Meses;
						if($Dias>0){
							$Edad--;
						}
					}
					if($Meses<1){
						$MedEdad=3;									
						$Edad=$Dias;
					}
				}
			}	
			$municip=substr($muncip,0,3);   
			$TDoc=$TipsDocs[$fila[0]];
			if(!$TDoc)
			{
				if($Edad>18){$TDoc="CC";}elseif($Edad>12){$TDoc="TI";}else{$TDoc="RC";}
			}
			if(!$fila[8]){if($Compania[0]=="Hospital Ricaurte E.S.E."||$Compania[0]=="Hospital San Rafael de Pasto"){$fila[8]="52";}}
			if(!$municip){if($Compania[0]=="Hospital Ricaurte E.S.E."){$municip="612";}}
 			$Archivo=$Archivo.$TDoc.",".$fila[1].",".$codsgsss.",".$tipoaseg.",".$fila[2].",".$fila[3].",".$fila[4].",".$fila[5].",".$Edad.",".$MedEdad.",".$fila[7].",".$fila[8].",".$municip.",".$fila[10]."\r\n"; 
		}
		//$Archivo = str_replace("<br>","\r\n",$Archivo); 		
	    $Fichero = fopen("US.TXT", "w+") or die('Error de apertura');
	    fwrite($Fichero, $Archivo);
    	fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='US.TXT'><br>US: Archivo de Usuarios<br></a></td></tr>";
	}
?>
<?	//AC: Archivo de consulta
	$cons="select codsgsss from central.compania where nombre='$Compania[0]'";	
	//echo $cons;
	$res=ExQuery($cons);
	$fila=ExFetch($res); 
	 $Compania[7]=$fila[0];	//con numero completo
	//$Compania[7]=substr($fila[0],0,10);	//sin numero completo para validar
	
	$cons="select facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,detalleliquidacion.fechacrea,liquidacion.autorizac1,detalleliquidacion.codigo, 	
	detalleliquidacion.finalidad,detalleliquidacion.causaext,servicios.dxserv,detalleliquidacion.dxrel1,detalleliquidacion.dxrel2,detalleliquidacion.dxrel3, 	
	detalleliquidacion.tipodxppal,detalleliquidacion.vrunidad,liquidacion.noliquidacion,facturascredito.subtotal,facturascredito.copago,facturascredito.total,detalleliquidacion.dxppal
	,sum(cantidad),servicios.fechaing,liquidacion.fechacrea,detalleliquidacion.vrtotal
	from facturacion.facturascredito,central.terceros,facturacion.liquidacion,central.tiposdocumentos,facturacion.detalleliquidacion,salud.servicios,contratacionsalud.tiposservicio
	where facturascredito.compania='$Compania[0]' and  facturascredito.fechacrea>='$FechaIni 00:00:00' and detalleliquidacion.compania='$Compania[0]' and servicios.compania='$Compania[0]'
	and facturascredito.fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and tiposdocumentos.tipodoc=terceros.tipodoc and terceros.identificacion=liquidacion.cedula
	and liquidacion.compania='$Compania[0]' and liquidacion.nofactura=facturascredito.nofactura and detalleliquidacion.noliquidacion=liquidacion.noliquidacion 
	and servicios.numservicio=liquidacion.numservicio and servicios.cedula=liquidacion.cedula and tiposservicio.compania='$Compania[0]' and tiposservicio.tipo='Consulta'
	and tiposservicio.codigo=detalleliquidacion.tipo and facturascredito.entidad='$Entidad' 
	and facturascredito.estado='AC' and facturascredito.contrato=liquidacion.contrato $NoContra $Contra 
	group by facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,detalleliquidacion.fechacrea,liquidacion.autorizac1,detalleliquidacion.codigo, 	
	detalleliquidacion.finalidad,detalleliquidacion.causaext,servicios.dxserv,detalleliquidacion.dxrel1,detalleliquidacion.dxrel2,detalleliquidacion.dxrel3, 	
	detalleliquidacion.tipodxppal,detalleliquidacion.vrunidad,liquidacion.noliquidacion,facturascredito.subtotal,facturascredito.copago,facturascredito.total
	,detalleliquidacion.dxppal,servicios.fechaing,liquidacion.fechacrea,detalleliquidacion.vrtotal
	order by facturascredito.nofactura";
	//salud.plantillaprocedimientos.fechaini
	$cons="select facturascredito.nofactura,tipodoc,liquidacion.cedula,fechainterpret,liquidacion.autorizac1,detalleliquidacion.codigo, 	
	detalleliquidacion.finalidad,detalleliquidacion.causaext,servicios.dxserv,detalleliquidacion.dxrel1,detalleliquidacion.dxrel2,detalleliquidacion.dxrel3, 	
	detalleliquidacion.tipodxppal,detalleliquidacion.vrunidad,liquidacion.noliquidacion,facturascredito.subtotal,facturascredito.copago,facturascredito.total
	,detalleliquidacion.dxppal,sum(cantidad),servicios.fechaing,liquidacion.fechacrea,detalleliquidacion.vrtotal,fechainterpret
	
	FROM facturacion.facturascredito,central.terceros,facturacion.liquidacion,facturacion.detalleliquidacion,salud.servicios,contratacionsalud.tiposservicio
	,salud.plantillaprocedimientos
	
	WHERE --salud.plantillaprocedimientos.cedula=servicios.cedula and salud.plantillaprocedimientos.numservicio=servicios.numservicio and
	
	
	facturascredito.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and servicios.compania='$Compania[0]'
	and terceros.compania='$Compania[0]' and liquidacion.compania='$Compania[0]'and tiposservicio.compania='$Compania[0]'
	and liquidacion.fechacrea >='$FechaIni 00:00:00' and fechainterpret>='$FechaIni 00:00:00'
	and fechainterpret<='$FechaFin 23:59:59' 
	and terceros.identificacion=liquidacion.cedula and liquidacion.nofactura=facturascredito.nofactura 
	and detalleliquidacion.noliquidacion=liquidacion.noliquidacion and servicios.numservicio=liquidacion.numservicio and servicios.cedula=liquidacion.cedula 
	and tiposservicio.tipo='Consulta'
	and tiposservicio.codigo=detalleliquidacion.tipo and facturascredito.entidad='$Entidad' 
	and facturascredito.estado='AC' and facturascredito.contrato=liquidacion.contrato $NoContra $Contra 
	group by facturascredito.nofactura,tipodoc,liquidacion.cedula,fechainterpret,liquidacion.autorizac1,detalleliquidacion.codigo, 	
	detalleliquidacion.finalidad,detalleliquidacion.causaext,servicios.dxserv,detalleliquidacion.dxrel1,detalleliquidacion.dxrel2,detalleliquidacion.dxrel3, 	
	detalleliquidacion.tipodxppal,detalleliquidacion.vrunidad,liquidacion.noliquidacion,facturascredito.subtotal,facturascredito.copago,facturascredito.total
	,detalleliquidacion.dxppal,servicios.fechaing,liquidacion.fechacrea,detalleliquidacion.vrtotal,fechainterpret
	order by facturascredito.nofactura, fechainterpret asc";
	//echo $cons;
	$res=ExQuery($cons); 
	$ContAC=ExNumRows($res);
	if($ContAC>0){
		$Archivo="";
		$TotAC=0;
		while($fila=ExFetch($res)){		
			$fechacrea=explode(" ",$fila[/*23*//*21*/23]);
			$FC=explode("-",$fechacrea[0]);
			$porcent=($fila[16]/$fila[15])*$fila[13];
			$porcent=$porcent*$fila[19];			
			$fila[13]=$fila[13]*$fila[19];			
			$tot=$fila[13]-$porcent;		
			if($fila[22]=="0"){$tot=0;}
			if(!$fila[8]){
				if(!$fila[18]){$fila[8]="F200";}
				else{$fila[8]=$fila[18];}
			}
			if($Compania[0]=="Hospital San Rafael de Pasto"){if($fila[6]=="01"){$fila[6]="10";}}
			
			$TDoc=$TipsDocs[$fila[1]];
			if(!$TDoc)
			{
				//if($Edad>18){$TDoc="CC";}elseif($Edad>12){$TDoc="TI";}else{$TDoc="RC";}
			}
			
			if(!$fila[6]){$fila[6]="10";} if(!$fila[7]){$fila[7]="13";} if(!$fila[12]){$fila[12]="1";}
			//$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$TDoc/*$fila[1]*/.",".$fila[2]./*",$FC[2]/$FC[1]/$FC[0],"*/",".$fechacrea[0].",".$fila[4].",".$fila[5].",".$fila[6].",".$fila[7].",".$fila[8].",".$fila[9].",".$fila[10].",".$fila[13].",".$fila[12].",".number_format($fila[21],0,".","").",";
			 $Archivo=$Archivo.$fila[0].",".$Compania[7].",".$TDoc/*$fila[1]*/.",".$fila[2]./*",$FC[2]/$FC[1]/$FC[0],"*/",".$fechacrea[0].",".$fila[4].",".$fila[5].",".$fila[6].",".$fila[7].",".$fila[8].",".$fila[9].",".$fila[10].",".$fila[13].",".$fila[12].",".number_format($fila[22],0,".","").",";
			if($porcent==0){$Archivo=$Archivo."0,";}else{$Archivo=$Archivo.number_format($porcent,0,".","").",";}
			$Archivo=$Archivo.number_format($tot,0,".","")."\r\n";
			$TotAC=$TotAC+$fila[13]; 
		}				
		$Fichero = fopen("AC.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AC.TXT'><br>AC: Archivo de Consulta<br></a></td></tr>";
	}
?>

<?	//AP: Archivo de Procedimientos
	$cons="select codigo,quirurgico from contratacionsalud.cups where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Cups[$fila[0]]=$fila[1];	
	}
    $FI=explode("-",$FechaIni);$FF=explode("-",$FechaFin);	
	$FI=explode("-",$FechaIni);//detalleliquidacion.fechacrea
	$cons="select facturascredito.nofactura,tipodoc,liquidacion.cedula,facturascredito.fechacrea,liquidacion.autorizac1,detalleliquidacion.codigo		
	,ambitos.codigo,detalleliquidacion.finalidad,detalleliquidacion.dxppal,detalleliquidacion.dxrel1,detalleliquidacion.dxrel2,detalleliquidacion.vrunidad
	,cantidad,detalleliquidacion.vrtotal,liquidacion.fechacrea,fechainterpret
	FROM facturacion.facturascredito,central.terceros,facturacion.liquidacion,facturacion.detalleliquidacion,salud.servicios
	,contratacionsalud.tiposservicio,salud.ambitos
	where facturascredito.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and servicios.compania='$Compania[0]'
	and terceros.compania='$Compania[0]' and tiposservicio.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and ambitos.compania='$Compania[0]'
	and facturascredito.fechacrea>='".$FI[0]."-".($FI[1])."-".$FI[2]." 00:00:00' and  facturascredito.fechacrea<='".$FF[0]."-".($FF[1])."-".$FF[2]." 23:59:59' and terceros.identificacion=liquidacion.cedula
	and fechainterpret<='$FechaFin 23:59:59' and liquidacion.nofactura=facturascredito.nofactura and detalleliquidacion.noliquidacion=liquidacion.noliquidacion 
	and servicios.numservicio=liquidacion.numservicio and servicios.cedula=liquidacion.cedula  and tiposservicio.tipo='Procedimiento'
	and tiposservicio.codigo=detalleliquidacion.tipo and ambitos.ambito=servicios.tiposervicio and facturascredito.entidad='$Entidad'
	and facturascredito.estado='AC' $NoContra $Contra
	order by nofactura";
	//echo $cons;
	$res=ExQuery($cons); 
	$ContAP=ExNumRows($res);
	if($ContAP>0){
		$TotAP=0;
		$Archivo="";
		while($fila=ExFetch($res)){			
			$fechacrea=explode(" ",$fila[15]);
			$FC=explode("-",$fechacrea[0]);    			
			if(!$fila[6]){$fila[6]="2";} if(!$fila[7])$fila[7]="1";if($fila[7]=="10")$fila[7]="1";
			if($Cups[$fila[5]]!=1){$fila[8]="";}
			if($fila[13]=="0"){$fila[11]="0";}
			$TDoc=$TipsDocs[$fila[1]];
			if(!$TDoc)
			{
				//if($Edad>18){$TDoc="CC";}elseif($Edad>12){$TDoc="TI";}else{$TDoc="RC";}
			}
			if($fila[12]==1){ 
				$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$TDoc.",".$fila[2].",$FC[2]/$FC[1]/$FC[0],".$fila[4].",".$fila[5].",".$fila[6].",".$fila[7].",,".$fila[8].",".
				$fila[9].",".$fila[10].",,".number_format($fila[11],0,".","")."\r\n";
				$TotAP=$TotAP+$fila[13]; 				
				
			}
			else{//echo "<br>$fila[12] =>";
				for($i=0;$i<$fila[12];$i++)
				{
					//echo $fila[12]." ";
					$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$TDoc.",".$fila[2]./*",$FC[2]/$FC[1]/$FC[0],"*/",".$fechacrea[0].",".$fila[4].",".$fila[5].",".$fila[6].",".$fila[7].",,".$fila[8].",".
					$fila[9].",".$fila[10].",,".number_format($fila[11],0,".","")."\r\n";
					if($fila[13]!="0"){
						$TotAP=$TotAP+$fila[11]; 
					}					
					else{
						$TotAP=$TotAp+0;	
					}
					//echo "Vrund = $fila[11] VrTot = $TotAP ";
				}
			}
		}	
		chmod("AP.TXT", 755);
		$Fichero = fopen("AP.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AP.TXT'><br>AP: Archivo de Procedimientos<br></a></td></tr>";	    
	}	
	//AH: Archivo de Hospitalizacion
	$cons="select 	
	facturascredito.nofactura,tipodoc,liquidacion.cedula,servicios.viaingreso,servicios.fechaing,servicios.autorizac1,servicios.causaexterna
	,servicios.estadosalida,servicios.fechaegr,servicios.numservicio,liquidacion.autorizac1,dxserv
	FROM facturacion.facturascredito,facturacion.liquidacion,salud.ambitos,central.terceros,salud.servicios
	WHERE facturascredito.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and ambitos.compania='$Compania[0]'
	and facturascredito.nofactura=liquidacion.nofactura and liquidacion.ambito=ambitos.ambito and ambitos.hospitalizacion=1 
	and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and liquidacion.cedula!='800246953-2' 
	and servicios.numservicio=liquidacion.numservicio and terceros.identificacion=liquidacion.cedula and terceros.compania='$Compania[0]' 
	and servicios.compania='$Compania[0]' and facturascredito.entidad='$Entidad' and (servicios.tiposervicio!='Consulta Externa' or servicios.tiposervicio!='1') 
	and facturascredito.estado='AC' and facturascredito.contrato=liquidacion.contrato $NoContra $Contra
	order by facturascredito.nofactura	";
	//echo $cons;
	$res=ExQuery($cons); 
	$ContAH=ExNumRows($res);
	if($ContAH>0){
		$Archivo="";
		while($fila=ExFetch($res)){		
			if(!$fila[3]){$fila[3]="2";}
			if(!$fila[5]){$fila[5]=$fila[11];}
			$fila[5]=$fila[10];	
			//if($fila[3]=='2'){$EstSalida=1;}else{$EstSalida=2;}echo $fila[3];			
			
			$fechaing=explode(" ",$fila[4]);
			$FI=explode("-",$fechaing[0]);
			if($FecCortes=="on"){
				$DiaIniCorte=explode("-",$FechaIni);
				$DiaIC=mktime(0,0,0,$DiaIniCorte[1],$DiaIniCorte[2],$DiaIniCorte[0]);
				$Diaing=mktime(0,0,0,$FI[1],$FI[2],$FI[0]);			
				if($Diaing<$DiaIC){
					//echo "$fechaing[0] xxx $FechaIni<br>";
					$FI[2]=$DiaIniCorte[2]; $FI[1]=$DiaIniCorte[1]; $FI[0]=$DiaIniCorte[0];
					$HI[0]="12"; $HI[1]="00";
				}
			}
			else{
				$HI=explode(":",$fechaing[1]); 
				if($HI[0]=='0'){$HI[0]=rand(5,18); if($HI[0]<10){$HI[0]="0".$HI[0];}}
				if($HI[1]=='0'){$HI[1]=rand(5,18); if($HI[1]<10){$HI[1]="0".$HI[1];}}
			}
			if(!$fila[6]){$fila[6]="15";}
			$cons2="select diagnostico from salud.diagnosticos where compania='$Compania[0]' and numservicio='$fila[9]' and clasedx='Ingreso' order by iddx";
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2);                                                       
			if(!$fila2[0]){
				if($fila[11]){$fila2[0]=$fila[11];}
				else{$fila2[0]="F200";}
			}
			$DxIng=$fila2[0];
			
			$TDoc=$TipsDocs[$fila[1]];
			if(!$TDoc)
			{
				//if($Edad>18){$TDoc="CC";}elseif($Edad>12){$TDoc="TI";}else{$TDoc="RC";}
			}
			$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$TDoc.",".$fila[2].",".$fila[3].",$FI[2]/$FI[1]/$FI[0],$HI[0]:$HI[1],".$fila[5].",".$fila[6].",".$fila2[0];
			$cons2="select diagnostico from salud.diagnosticos where compania='$Compania[0]' and numservicio='$fila[9]' and clasedx='Egreso' order by iddx";
			$res2=ExQuery($cons2); echo ExError(); 
			$banEgr="";
			
			if(ExNumRows($res2)>0){				
				$contDxE=0;
				while($fila2=ExFetch($res2))
				{					
					$contDxE++;					
					$Archivo=$Archivo.",".$fila2[0];
				}  
				for($i=0;$i<(5-$contDxE);$i++)
				{
					$Archivo=$Archivo.",";	
				}
			}
			else{
				if($FecCortes=="on"){
					if(!$fila2[0]){$fila2[0]=$DxIng;}
				}
				$Archivo=$Archivo.",".$fila2[0].",,,,";
			}		
					
			if($fila[8]!=''){				
				$fechaegr=explode(" ",$fila[8]); 				
				$FE=explode("-",$fechaegr[0]); 
				$DiaFinCorte=explode("-",$FechaFin);
				$DiaFC=mktime(0,0,0,$DiaFinCorte[1],$DiaFinCorte[2],$DiaFinCorte[0]);
				$DiaEgr=mktime(0,0,0,$FE[1],$FE[2],$FE[0]);		
				if($FecCortes=="on"){
					if($DiaEgr<$DiaFC){										
						$HE=explode(":",$fechaegr[1]);
						if($HE[0]=="00"){$HE[0]=rand(8,18);if($HE[0]<10){$HE[0]="0".$HE[0];}}
						if($HE[1]=="00"){$HE[1]=rand(8,18);if($HE[1]<10){$HE[1]="0".$HE[1];}}
						$FecE="$FE[2]/$FE[1]/$FE[0]";$HoE="$HE[0]:$HE[1]";                   
					}
					else{					
						$FecFin=explode("-",$FechaFin);
						$FecE="$FecFin[2]/$FecFin[1]/$FecFin[0]"; 
						$HoE="12:00";	
					}
				}
				else{
					$HE=explode(":",$fechaegr[1]);
						if($HE[0]=="00"){$HE[0]=rand(8,18);if($HE[0]<10){$HE[0]="0".$HE[0];}}
						if($HE[1]=="00"){$HE[1]=rand(8,18);if($HE[1]<10){$HE[1]="0".$HE[1];}}
						$FecE="$FE[2]/$FE[1]/$FE[0]";$HoE="$HE[0]:$HE[1]";   
				}
			}
			else{	
				if($FecCortes=="on"){
					$FecFin=explode("-",$FechaFin);
					$FecE="$FecFin[2]/$FecFin[1]/$FecFin[0]"; $HoE="12:00"; 
				}
				else{ $FecE=""; $HoE="";}
			}
			$cons2="select diagnostico from salud.diagnosticos where compania='$Compania[0]' and numservicio='$fila[9]' and clasedx='Muerte' order by iddx";
			$res2=ExQuery($cons2); 
			
			if(ExNumRows($res2)>0){
				$fila2=ExFetch($res2);
				$fila[7]=2;
			}
			else{		
				$fila2[0]="";
				$fila[7]=1;				
			}
			//if($fila[7]=="2"){$fila[7]=1;}

			
			 if($FecCortes=="on"){
			 $consT="SELECT pacientesxpabellones.fechae, pacientesxpabellones.horae FROM salud.pacientesxpabellones, central.terceros
                     WHERE pacientesxpabellones.cedula = terceros.identificacion and pacientesxpabellones.compania = '$Compania[0]'
					 AND fechai >= '$FechaIni' AND fechai <= '$FechaFin' and fechae >= '$FechaIni' AND fechae <= '$FechaFin'
                     AND pacientesxpabellones.lugtraslado LIKE '%Remision y Evasion%' AND terceros.identificacion='$fila[2]' ORDER BY pacientesxpabellones.cedula";
             $resT=ExQuery($consT); 
             $filaT=ExFetch($resT);if(ExNumRows($resT)>0){$F=explode("-",$filaT[0]);$FecE="$F[2]/$F[1]/$F[0]"; $HoE=$filaT[1];}

             $consE="SELECT salud.servicios.fechaegr FROM salud.servicios
                     INNER JOIN central.terceros on salud.servicios.cedula=central.terceros.identificacion
                     INNER JOIN salud.ordenesmedicas on salud.servicios.numservicio=salud.ordenesmedicas.numservicio
                     INNER JOIN central.usuarios on salud.servicios.usuegreso=central.usuarios.usuario
                     WHERE salud.servicios.fechaegr  BETWEEN '$FechaIni 00:00:00' and '$FechaFin 23:59:59' AND salud.servicios.tiposervicio = 'Hospitalizacion'
                     AND salud.ordenesmedicas.tipoorden='Orden Egreso' AND terceros.identificacion='$fila[2]' ORDER BY salud.servicios.fechaegr";
             $resE=ExQuery($consE);
             $filaE=ExFetch($resE);if(ExNumRows($resE)>0){$FF=explode(" ",$filaE[0]);$F=explode("-",$FF[0]);$FecE="$F[2]/$F[1]/$F[0]";}	
			 }
			 if($HoE=="12:00")$HoE="23:59";
			
			$Archivo=$Archivo.",".$fila[7].",".$fila2[0].",".$FecE.",".$HoE."\r\n";
		}		
		$Fichero = fopen("AH.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AH.TXT'><br>AH: Archivo de Hospitalizacion<br></a></td></tr>";		
	}
	//AU: Archivo de Urgencias

	$cons="select liquidacion.nofactura,tipodoc,liquidacion.cedula,fechaing,liquidacion.autorizac1,servicios.autorizac1,fechaegr,servicios.numservicio,dxserv
	from facturacion.liquidacion,facturacion.facturascredito,central.terceros,salud.servicios,salud.ambitos
	where liquidacion.nofactura=facturascredito.nofactura and facturascredito.estado='AC'
	and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaIni 23:59:59' 
	and liquidacion.cedula=identificacion and servicios.numservicio=liquidacion.numservicio and liquidacion.ambito=ambitos.ambito and urgencias=1
	and hospitalizacion!=1 and liquidacion.cedula=servicios.cedula
	order by liquidacion.nofactura";
	//echo $cons;
	$res=ExQuery($cons);
	$ContAU=ExNumRows($res);
	if($ContAU>0)
	{
		$Archivo="";
		while($fila=ExFetch($res))
		{
			$TDoc=$TipsDocs[$fila[1]];
			if(!$TDoc)
			{
				//if($Edad>18){$TDoc="CC";}elseif($Edad>12){$TDoc="TI";}else{$TDoc="RC";}
			}
			$FecIng=explode(" ",$fila[3]);
			$FechaIng=explode("-",$FecIng[0]);
			$FIng="$FechaIng[2]/$FechaIng[1]/$FechaIng[0]";
			$HoIng=explode(":",$FecIng[1]);
			$HIng="$HoIng[0]:$HoIng[1]";
			if($fila[5]&&$fila[5]!="0"){$Autoriza=$fila[5];}else{$Autoriza=$fila[4];}
			
			$cons2="select causaexterna,cmp00055,dx1 from histoclinicafrms.tbl00005 where numservicio=$fila[7] and cedula='$fila[2]'";
			$res2=ExQuery($cons2); $fila2=ExFetch($res2); $CausaExtUrg=$fila2[0]; $Dx=$fila2[1];
			$Destion=$fila2[1];		
			if($Destino=="Hospitalizacion"){$Dest="3";}if($Destino=="Remision"||$Destino=="Remision."){$Dest="2";}
			$cons3="select causaexterna,dx1 from histoclinicafrms.tbl00042 where numservicio=$fila[7] and cedula='$fila[2]'";
			$res3=ExQuery($cons3); $fila3=ExFetch($res3); $CausaExtUrg2=$fila3[0];	$Dx2=$fila3[1];
			if(!$CausaExtUrg){$CausaExtUrg=$CausaExtUrg2;} if(!$CausaExtUrg){$CausaExtUrg="13";}
			
			$Archivo=$Archivo.$fila[0].",".$Compania[7].",$TDoc,".$fila[2].",".$FIng.",".$HIng.",".$Autoriza.",".$CausaExtUrg."";
			
			$cons2="select diagnostico from salud.diagnosticos where compania='$Compania[0]' and numservicio='$fila[7]' and clasedx='Egreso' order by iddx";
			$res2=ExQuery($cons2); echo ExError(); 
			$banEgr="";			
			if(ExNumRows($res2)>0){	
				$contDxE=0;
				while($fila2=ExFetch($res2))
				{					
					$contDxE++;					
					$Archivo=$Archivo.",".$fila2[0];
				}  
				for($i=0;$i<(5-$contDxE);$i++)
				{
					$Archivo=$Archivo.",";	
				}
				
			}
			else{
				if($fila[8]){$DxSalida=$fila[8];}
				elseif($Dx){$DxSalida=$Dx;}
				elseif($Dx2){$DxSalida=$Dx2;}
				$Archivo=$Archivo.",$DxSalida,,,";
			}
			
			$cons2="select cedula from histoclinicafrms.tbl00007 where numservicio=$fila[7] and cedula='$fila[2]'";
			$res2=ExQuery($cons2); if(ExNumRows($res2)>0){$Dest="2";}
			if(!$Dest){$Dest=1;}
			
			$cons2="select diagnostico from salud.diagnosticos where compania='$Compania[0]' and numservicio='$fila[7]' and clasedx='Muerte' order by iddx";
			$res2=ExQuery($cons2); 
			
			if(ExNumRows($res2)>0){
				$fila2=ExFetch($res2);
				$EstadoSalida="2";
				$DxMuerte=$fila2[0];
			}
			else{
				$EstadoSalida="1";
				$DxMuerte="";
			}
			//
			if($fila[6]){
				$FecEgr=explode(" ",$fila[6]);
				$FecEgreso=explode("-",$FecEgr[0]);
				$FEgr="$FecEgreso[2]/$FecEgreso[1]/$FecEgreso[0]";
				$HoraEgr=explode(":",$FecEgr[1]);
				$HEgr="$HoraEgr[0]:$HoraEgr[1]";
				if($HoraEgr=="00"){$HEgr="$HoIng[0]:".($HoIng[1]+3);}
			}
			else{
				$FEgr=$FIng;
				$HEgr="$HoIng[0]:".($HoIng[1]+3);
			}
			$Archivo=$Archivo.",$Dest,$EstadoSalida,$DxMuerte,".$FEgr.",".$HEgr."\r\n";
			
			//nofac,sgsss,tipoid,id,fecing,horaing,autoriac,causaext,dxsalida,dxsal1,dxsal2,dxsal3,destino,estadosalida,causamuerte,fecsalida,horasalida		
		}	
		$Fichero = fopen("AU.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AU.TXT'><br>AU: Archivo de Urgencias<br></a></td></tr>";
	}
	//AM: Archivo de Medicamentos
	$TotAM=0;
	$consam1="select numservicio from salud.pagadorxservicios where entidad='$Entidad' order by numservicio;";
	$resam1=ExQuery($consam1);
	while($filaam1=ExFetch($resam1)){
		$consam2="select cedula,nocarnet,autorizac1 from salud.servicios where numservicio='$filaam1[0]' order by cedula;";
		$resam2=ExQuery($consam2);
		while($filaam2=ExFetch($resam2)){
			$consam3="select facturacion.facturascredito.nofactura from facturacion.liquidacion,facturacion.facturascredito where cedula='$filaam2[0]' and facturacion.liquidacion.nofactura=facturacion.facturascredito.nofactura and facturacion.facturascredito.fechacrea between '$FechaIni 00:00:00' and '$FechaFin 23:59:59' and facturacion.liquidacion.numservicio='$filaam1[0]' order by facturacion.facturascredito.nofactura asc";
			$resam3=ExQuery($consam3);
			while($filaam3=ExFetch($resam3)){
				$consam4="select nombre,cantidad,vrunidad,vrtotal,forma,presentacion from facturacion.detallefactura where nofactura='$filaam3[0]' and grupo='03' and Tipo like '%Medicamentos%'";
				$resam4=ExQuery($consam4);
				while($filaam4=ExFetch($resam4)){
					$consam5="select codigo2,pos,presentacion,unidadmedida from consumo.codproductos where nombreprod1='$filaam4[0]' and almacenppal='FARMACIA' and anio='$ND[year]' and presentacion='$filaam4[4]' and unidadmedida='$filaam4[5]'";
					$resam5=ExQuery($consam5);
					while($filaam5=ExFetch($resam5)){
						$consam6="select codsgsss from central.compania where nombre='$Compania[0]' limit 1";
						$resam6=ExQuery($consam6);
						while($filaam6=ExFetch($resam6)){
							$consam7="select tipodoc from central.terceros where identificacion='$filaam2[0]' limit 1";
							$resam7=ExQuery($consam7);
							while($filaam7=ExFetch($resam7)){
								$consam8="select codigo from central.tiposdocumentos where tipodoc='$filaam7[0]' limit 1";
								$resam8=ExQuery($consam8);
								while($filaam8=ExFetch($resam8)){
									if($filaam5[1]==0){
										$filaam5[1]=2;
									}
									
									$ArchivoAM=$ArchivoAM."$filaam3[0],$filaam6[0],$filaam8[0],$filaam2[0],$filaam2[2],$filaam5[0],$filaam5[1],$filaam4[0],$filaam5[2],$filaam5[3],$filaam5[3],$filaam4[1],$filaam4[2],$filaam4[3]\r\n";
									//$ArchivoAM=$ArchivoAM."$filaam3[0],$filaam6[0],$filaam8[0],$filaam2[0],$filaam2[1],$filaam2[2],$filaam5[0],$filaam5[1],$filaam4[0],$filaam5[2],$filaam5[3],$filaam5[3],$filaam4[1],$filaam4[2],$filaam4[3]\r\n";
									$TotAM=$TotAM+$filaam4[3];
								}
							}
						}
					}
				}				
			}
		}
	}
		chmod("AM.TXT", 755);
		$Fichero = fopen("AM.TXT", "w+") or die('Error de apertura');
	    fwrite($Fichero, $ArchivoAM);
    	fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AM.TXT'><br>AM: Archivo de Medicamentos<br></a></td></tr>";		
 
	//AF:Archivo de Transacciones
	$Archivo="";
	$cons="select facturascredito.nofactura,facturascredito.fechacrea,codigosgsss,primape,segape,primnom,segnom,facturascredito.nocontrato,facturascredito.copago
	,facturascredito.descuento,facturascredito.total,facturascredito.contrato,facturascredito.nocontrato
	from facturacion.facturascredito,central.terceros,facturacion.liquidacion
	where facturascredito.compania='$Compania[0]' and liquidacion.compania='$Compania[0]'
	and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and 
	facturascredito.entidad='$Entidad'  and facturascredito.estado='AC' and facturascredito.contrato=liquidacion.contrato 
	and facturascredito.entidad=terceros.identificacion and terceros.compania='$Compania[0]' and facturascredito.contrato=liquidacion.contrato $NoContra $Contra
	group by facturascredito.nofactura,facturascredito.fechacrea,entidad,primape,segape,primnom,segnom,facturascredito.nocontrato,facturascredito.copago
	,facturascredito.descuento,facturascredito.total,codigosgsss
	,facturascredito.contrato,facturascredito.nocontrato order by facturascredito.nofactura";
	//echo $cons;
	$res=ExQuery($cons);
	$ContAF=ExNumRows($res);
	$Comp=explode(" ",$Compania[1]);
	if($Comp[0]="NIT"){$Comp[0]="NI";}
	if($ContAF>0){	
		$Archivo="";
		$TotAF=0;
		$TotFac=0;		
		while($fila=ExFetch($res)){			
			$FechaCreaComp=explode(" ",$fila[1]);// echo  "$FechaCreaComp[0]<br>";
			$cons2="select pagadorxservicios.fechaini,pagadorxservicios.fechafin,tiposervicio 
			from salud.pagadorxservicios,salud.servicios,facturacion.liquidacion,facturacion.facturascredito
			where pagadorxservicios.compania='$Compania[0]' and servicios.compania='$Compania[0]' and facturascredito.compania='$Compania[0]' and liquidacion.compania='$Compania[0]'
			and servicios.numservicio=pagadorxservicios.numservicio and liquidacion.numservicio=servicios.numservicio and facturascredito.nofactura=liquidacion.nofactura and 		
			facturascredito.nofactura=$fila[0] and pagadorxservicios.entidad='$Entidad' and pagadorxservicios.contrato='$fila[11]' and pagadorxservicios.nocontrato='$fila[12]'";		
			$res2=ExQuery($cons2);$fila2=ExFetch($res2);	
			$FechaIngreso=$fila2[0];$FechaEgreso=$fila2[1]; $Ambito=$fila2[2];		$FI=$FechaIngreso; $FF=$FechaEgreso;
			
			if(!$FechaEgreso){$FechaEgreso=$FechaFin;}
			//echo "$fila[0] Fecha Ing : $FechaIngreso  --> Fecha Egr: $FechaEgreso<br>";			
			if($FechaIngreso>=$FechaIni){$FI=$FechaIngreso;}
			if($FechaIngreso<$FechaIni){$FI=$FechaIni;}					
			if($FechaEgreso<$FechaFin){$FF=$FechaEgreso;}			
			if($FechaEgreso>$FechaFin){$FF=$FechaFin;}

			//if(!$Fac[15]){$Fac[15]=$FechaPeriodoIni;}					
			//if($FechaIngreso<$Fac[15]){$Fac;}
			if(!$FF){$FF=$FechaFin;}
			$Ambito=$Fac[4];				
			$FC=explode("-",$FechaCreaComp[0]);
			$FI=explode("-",$FI);
			$FF=explode("-",$FF);		
			//echo "$fila[0] FechaIngreso=$FechaIngreso FechaEgreso=$FechaEgreso FI : $FI  --> FF: $FF  FechaFin=$FechaFin<br>";
			/*$cons2="select sum(cantidad),vrunidad,codigo from facturacion.liquidacion,facturacion.facturascredito,facturacion.detallefactura
			where liquidacion.compania='$Compania[0]' and facturascredito.compania='$Compania[0]' and detallefactura='$Compania[0]'
			and facturascredito.nofactura=liquidacion.nofactura and liquidacion.noliquidacion=
			";*/
			//echo "<br>";
			$cons2="select sum(cantidad),vrunidad,codigo,tipo,nombre from facturacion.facturascredito,facturacion.detallefactura
			where facturascredito.compania='$Compania[0]' and detallefactura.compania='$Compania[0]'
			and facturascredito.nofactura=detallefactura.nofactura and facturascredito.nofactura=$fila[0] group by vrunidad,codigo,tipo,nombre";
			$TotFac=0;
			$cons2="select cantidad,vrunidad,codigo,tipo,nombre,total from facturacion.facturascredito,facturacion.detallefactura
			where facturascredito.compania='$Compania[0]' and detallefactura.compania='$Compania[0]'
			and facturascredito.nofactura=detallefactura.nofactura and facturascredito.nofactura=$fila[0]";
			$res2=ExQuery($cons2);//echo $cons2."<br>";			
			while($fila2=ExFetch($res2)){
				/*if(($Entidad=='800198972-6'||$Entidad=='890399029-5'||$Entidad=='891580016-8'||$Entidad=='I891280001-0'||$Entidad=='800103913-4')&&$fila2[3]=="Medicamentos"){
					$TotFac=$TotFac;	
				}	
				else{*/
					//$TotFac=$TotFac+((round($fila2[0])*round($fila2[1])));
					$TotFac=round($fila2[5]);
				//}				
				//echo "$fila2[4]  $TotFac<br>";
			}
			$fila[10]=round($TotFac); 		
			//echo "TOTAL DE ESTA FACTURA($fila[0]) =$fila[10]<br>";
			$cons2="select nopoliza from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$fila[2]' and numero='$fila[7]'";		
			$res2=ExQuery($cons2); $fila2=ExFetch($res2); 
			if(!$fila[9]){$fila[9]="0";}if(!$fila[8]){$fila[8]="0";}if(!$fila2[0]){$fila2[0]="0";}if(!$fila[7]){$fila[7]="0";}
			if($fila[4]!=''){$fila[4]=" ".$fila[4];}if($fila[5]!=''){$fila[5]=" ".$fila[5];}if($fila[6]!=''){$fila[6]=" ".$fila[6];}
			$Archivo=$Archivo.$Compania[7].",".$Compania[0].",".$Comp[0].",".$Comp[1].",".$fila[0].",$FC[2]/$FC[1]/$FC[0],$FI[2]/$FI[1]/$FI[0],$FF[2]/$FF[1]/$FF[0],".$fila[2].",". trim(substr("$fila[3] $fila[4] $fila[5] $fila[6]",0,30)).",".substr($fila[7],0,15).",0,".$fila2[0].",".$fila[8].",0,".$fila[9].",".$fila[10]."\r\n";		
			$TotAF=$TotAF+$TotFac; 
			$TotCop=$TotCop+$fila[8];
			$fila[10]="";$fila[8]="";
			$TotFac=0;
		}
		$Fichero = fopen("AF.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AF.TXT'><br>AF: Archivo de Transacciones<br></a></td></tr>";
	}
	
	//AT:Otros Servicios
	$Archivo="";
	$cons="select facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,liquidacion.autorizac1,servicios.autorizac1,detalleliquidacion.grupo
	,detalleliquidacion.codigo,detalleliquidacion.nombre,sum(detalleliquidacion.cantidad),detalleliquidacion.vrunidad,sum(detalleliquidacion.vrtotal)
	from facturacion.facturascredito,facturacion.liquidacion,facturacion.detalleliquidacion,central.terceros,central.tiposdocumentos,salud.servicios
	,contratacionsalud.gruposservicio 
	where facturascredito.compania='$Compania[0]' and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' 
	and gruposservicio.compania='$Compania[0]' and detalleliquidacion.grupo=gruposservicio.codigo
	and facturascredito.entidad='$Entidad' and liquidacion.compania='$Compania[0]' and liquidacion.nofactura=facturascredito.nofactura 
	and detalleliquidacion.compania='$Compania[0]' and detalleliquidacion.noliquidacion=liquidacion.noliquidacion  and tiposdocumentos.tipodoc=terceros.tipodoc
	and terceros.identificacion=liquidacion.cedula and servicios.compania='$Compania[0]' and servicios.numservicio=liquidacion.numservicio
	and (detalleliquidacion.grupo='34' or detalleliquidacion.tipo='00001' or detalleliquidacion.tipo='012' or gruposservicio.grupomeds='MedicoQuirurgico') and facturascredito.estado='AC'
	and facturascredito.contrato=liquidacion.contrato $NoContra $Contra
	group by facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,liquidacion.autorizac1,servicios.autorizac1,detalleliquidacion.grupo
	,detalleliquidacion.codigo,detalleliquidacion.nombre,detalleliquidacion.vrunidad order by facturascredito.nofactura";
	//echo $cons;	
	$res=ExQuery($cons);
	$ContAT=ExNumRows($res);
	if($ContAT>0){
		$Archivo="";
		$TotAT=0;
		while($fila=ExFetch($res))
		{
			$fila[7]=str_replace(",","-",$fila[7]);
			if(!$fila[3]){$fila[3]=$fila[4];}
			if($fila[5]=="Dispositivos medicos"||$fila[5]=="Dispositivo medico"){$TipoServ="1";}else{$TipoServ="3";}
			
			$Cant=round($fila[8]);$VrUnd=round($fila[9]);
			/*if(($Entidad=='800198972-6'||$Entidad=='890399029-5'||$Entidad=='891580016-8'||$Entidad=='I891280001-0'||$Entidad=='800103913-4')&&$fila[5]=="Dispositivos medicos"){
				$VrTot=0;	
			}	
			else{*/
				//$VrTot=round($Cant)*round($VrUnd);
				$VrTot=$fila[10];
			//}			
			if(!$fila[3]||$fila[3]=="."){$fila[3]="0";}
			$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$fila[1].",".$fila[2].",".$fila[3].",".$TipoServ.",".$fila[6].",". substr($fila[7],0,60).",".round($Cant).","
			 .number_format(round($VrUnd),0,".","").",".number_format($VrTot,0,".","")."\r\n";		
			 $TotAT=$TotAT+$VrTot;
			 $fila[5]="";
		}
		$Fichero = fopen("AT.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='AT.TXT'><br>AT: Archivo Otros Servicios<br></a></td></tr>";
	}
	
	//DM:Dispositivos Medicos
	$Archivo="";
	$cons="select facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,liquidacion.autorizac1,servicios.autorizac1,detalleliquidacion.grupo
	,detalleliquidacion.codigo,detalleliquidacion.nombre,sum(detalleliquidacion.cantidad),detalleliquidacion.vrunidad,sum(detalleliquidacion.vrtotal)
	from facturacion.facturascredito,facturacion.liquidacion,facturacion.detalleliquidacion,central.terceros,central.tiposdocumentos,salud.servicios
	,contratacionsalud.gruposservicio 
	where facturascredito.compania='$Compania[0]' and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' 
	and gruposservicio.compania='$Compania[0]' and detalleliquidacion.grupo=gruposservicio.codigo
	and facturascredito.entidad='$Entidad' and liquidacion.compania='$Compania[0]' and liquidacion.nofactura=facturascredito.nofactura 
	and detalleliquidacion.compania='$Compania[0]' and detalleliquidacion.noliquidacion=liquidacion.noliquidacion  and tiposdocumentos.tipodoc=terceros.tipodoc
	and terceros.identificacion=liquidacion.cedula and servicios.compania='$Compania[0]' and servicios.numservicio=liquidacion.numservicio
	and (--detalleliquidacion.tipo='00001' or 
	detalleliquidacion.tipo='Dispositivo Medico' 
	--or detalleliquidacion.tipo='012' 
	or detalleliquidacion.tipo='38'
	--or gruposservicio.grupomeds='MedicoQuirurgico' 
	or gruposservicio.grupomeds='DispositivoMedico'
	) and facturascredito.estado='AC'
	and facturascredito.contrato=liquidacion.contrato $NoContra $Contra
	group by facturascredito.nofactura,tiposdocumentos.codigo,liquidacion.cedula,liquidacion.autorizac1,servicios.autorizac1,detalleliquidacion.grupo
	,detalleliquidacion.codigo,detalleliquidacion.nombre,detalleliquidacion.vrunidad order by facturascredito.nofactura";
	//echo $cons;	
	$res=ExQuery($cons);
	$ContAT=ExNumRows($res);
	if($ContAT>0){
		$Archivo="";
		$TotAT=0;
		while($fila=ExFetch($res))
		{
			$fila[7]=str_replace(",","-",$fila[7]);
			if(!$fila[3]){$fila[3]=$fila[4];}
			if($fila[5]=="Dispositivos medicos"||$fila[5]=="Dispositivo medico"){$TipoServ="1";}else{$TipoServ="3";}
			
			$Cant=round($fila[8]);$VrUnd=round($fila[9]);
			/*if(($Entidad=='800198972-6'||$Entidad=='890399029-5'||$Entidad=='891580016-8'||$Entidad=='I891280001-0'||$Entidad=='800103913-4')&&$fila[5]=="Dispositivos medicos"){
				$VrTot=0;	
			}	
			else{*/
				//$VrTot=round($Cant)*round($VrUnd);
				$VrTot=$fila[10];
			//}			
			if(!$fila[3]||$fila[3]=="."){$fila[3]="0";}
			$Archivo=$Archivo.$fila[0].",".$Compania[7].",".$fila[1].",".$fila[2].",".$fila[3].",".$TipoServ.",".$fila[6].",". substr($fila[7],0,60).",".round($Cant).","
			 .number_format(round($VrUnd),0,".","").",".number_format($VrTot,0,".","")."\r\n";		
			 $TotAT=$TotAT+$VrTot;
			 $fila[5]="";
		}
		$Fichero = fopen("DM.TXT", "w+") or die('Error de apertura');
		fwrite($Fichero, $Archivo);
		fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='DM.TXT'><br>DM: Archivo Dispositivos Medicos<br></a></td></tr>";
	}
	
	//CT:Archivo de Control
	$Archivo="";
  		$FFin=explode("-",$FechaFin);
		if($ContUS>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],US,".$ContUS."\r\n";
		}
		if($ContAC>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AC,".$ContAC."\r\n";
		}
		if($ContAP>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AP,".$ContAP."\r\n";
		}
		if($ContAH>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AH,".$ContAH."\r\n";
		}
		if($ContAM>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AM,".$ContAM."\r\n";
		}
		if($ContAF>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AF,".$ContAF."\r\n";
		}
		if($ContAT>0){ 			
			$Archivo=$Archivo.$Compania[7].",$FFin[2]/$FFin[1]/$FFin[0],AT,".$ContAT."\r\n";
		}
		$Fichero = fopen("CT.TXT", "w+") or die('Error de apertura');
	    fwrite($Fichero, $Archivo);
    	fclose($Fichero);
		echo "<tr align='center'><td colspan=30><a target='_PARENT' href='CT.TXT'><br>CT: Archivo de Control<br></a></td></tr>";
		$TotAfminusCop=$TotAF+$TotCop;
	?>    
    <tr align="right"><td><? echo "Total AC=$TotAC";?></td></tr>
    <tr align="right"><td><? echo "Total AP=$TotAP"?></td></tr>
    <tr align="right"><td><? echo "Total AM=$TotAM"?></td></tr>
    <tr align="right"><td><? echo "Total AT=$TotAT"?></td></tr>
    <tr align="right"><td><? echo $TotAC+$TotAP+$TotAM+$TotAT;?>
    <tr align="right"><td><? echo "Total AF=$TotAF"?></td></tr>
    <tr align="right"><td><? echo "Total incluyendo copago copago=".round($TotAfminusCop)?></td></tr>
</table>        
<input type="hidden" name="AP" value="<? echo $AP?>">
<input type="hidden" name="AC" value="<? echo $AC?>">
<input type="hidden" name="US" value="<? echo $US?>">
<input type="hidden" name="AH" value="<? echo $AH?>">
<input type="hidden" name="AM" value="<? echo $AM?>">
<input type="hidden" name="CT" value="<? echo $CT?>">
</form>
</body>
</html>
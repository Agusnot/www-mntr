<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");		
	$CompContable="Cuentas cero";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  	
<?	if(!$Generar){?>
		<tr><td bgcolor="#e5e5e5" style="font-weight:bold">Generacion de Cruce de Cuentas Ceros desde archivo Exel</td></tr>  
    	<tr><td align="center"><input type="submit" value="Generar" name="Generar"></td></tr> 	
<?	}
	else{		
		$cons4="select vigencia,clasevigencia,ctapresupuestal,ctadebe,anio from presupuesto.ctaceroexel";
		$res4=ExQuery($cons4);
		if(ExNumRows($res4)>0){
			while($fila4=ExFetch($res4))
			{
				
				$cons2="delete from presupuesto.crucecuentascero where CtaPresupuestal ilike '$fila4[2]%' and crucecuentascero.anio=$fila4[4] 
				and crucecuentascero.vigencia='$fila4[0]' and crucecuentascero.Clasevigencia='$fila4[1]' and crucecuentascero.compania='$Compania[0]'";		
				//echo $cons2;			
				$res2=ExQuery($cons2);				
				
				$Cta=substr($fila4[2],0,1);    
				if($Cta==1){				
					$cons="select codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codrecaudado,nomrecaudado,naturecaudado
					from presupuesto.ingresos where codaprobado='$fila4[3]'";			
					$res=ExQuery($cons);			
					//echo $cons;			
					$fila=ExFetch($res);
					$Aprobado=$fila[0];
					$xEjecutar=$fila[3];
					$Recaudado=$fila[6];
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$fila4[4]";					
					$res2=ExQuery($cons2);
					//echo $cons2;
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);				
					}			
					$Titulo1=substr($fila[0],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[0],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[0],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					
						
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$fila4[4]";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}	
					$Titulo1=substr($fila[3],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[3],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[3],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
								
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$fila4[4]";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
						//echo $cons3;
						$res3=ExQuery($cons3);
					}			
					$Titulo1=substr($fila[6],0,1);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						//echo "$cons11<br>";
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);							
					}	
					$Titulo2=substr($fila[6],0,2);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}
					$Titulo3=substr($fila[6],0,4);
					$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
					//echo $cons2;
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)<=0)				
					{
						$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
						$res11=ExQuery($cons11); $fila11=ExFetch($res11);
						$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
						($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
						//echo $cons3;
						$res3=ExQuery($cons3);							
					}	 			
						
					$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
					$res3=ExQuery($cons3); $fila3=ExFetch($res3);
					$Autoid=$fila3[0]+1;			
					
					$cons2="select cuenta,nombre from presupuesto.plancuentas 
					where cuenta like '$fila4[2]' and vigencia='$fila4[0]' and clasevigencia='$fila4[1]' and anio=$fila4[4] and tipo='Detalle' group by cuenta,nombre";
					$res2=ExQuery($cons2);	echo ExError();
					//echo $cons2;
					
					//Ingresos---Algoritmo
					$fila2=ExFetch($res2);
			?>     	<tr bgcolor="#666699" style="color:white;font-weight:bold">
						<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
					</tr>
					<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
						<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
					</tr> <?							
					//Apropiacion Inicial--->Aprobado-xEjecutar				
					$cons3="insert into presupuesto.crucecuentascero (
					anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					($fila4[4],'$Compania[0]','$CompContable','Apropiacion inicial','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
					$res3=ExQuery($cons3);
					//echo "$cons3<br>";
				?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
					$Autoid++;
					//Adicion--->Aprobado-xEjecutar
					$cons3="insert into presupuesto.crucecuentascero 
					(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					($fila4[4],'$Compania[0]','$CompContable','Adicion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
					$res3=ExQuery($cons3);
					//echo "$cons3<br>";
				?>	<tr align="center"><td><strong>Adicion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
					$Autoid++;
					//Reduccion--->xEjecutar-Aprobado
					$cons3="insert into presupuesto.crucecuentascero 
					(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					($fila4[4],'$Compania[0]','$CompContable','Reduccion','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
					$res3=ExQuery($cons3);
					$Autoid++;
					//echo "$cons3<br>";
				?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
					//Ingreso presupuestal-->xEjecutar-Aprobado
					$cons3="insert into presupuesto.crucecuentascero 
					(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					($fila4[4],'$Compania[0]','$CompContable','Ingreso presupuestal','$xEjecutar','$Recaudado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
					$res3=ExQuery($cons3);
					//echo "$cons3<br>";
					$Autoid++;
				?>	<tr align="center"><td><strong>Ingreso Presupuestal</strong></td><td><? echo $xEjecutar?></td><td><? echo $Recaudado?></td></tr><?
					//Diminucion de Ingreso Presupuestal-->Recaudado-xEjecutar
					$cons3="insert into presupuesto.crucecuentascero 
					(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
					($fila4[4],'$Compania[0]','$CompContable','Disminucion a ingreso presupuestal','$Recaudado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
					$res3=ExQuery($cons3);
					//echo "$cons3<br>";
				?>	<tr align="center"><td><strong>Disminucion a Ingreso Presupuestal</strong></td><td><? echo $Recaudado?></td><td><? echo $xEjecutar?></td></tr><?
					$Autoid++;				
				
				}
				else{
					if($fila4[1]=="Reservas")
					{				
						$cons="select 
						codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codobligaciones,nomobligaciones,natuobligaciones,codpagado,nompagado,natupagado
						from presupuesto.reservas where codaprobado='$fila4[3]'";										
						$res=ExQuery($cons);			
						//echo $cons;			
						$fila=ExFetch($res);
						$Aprobado=$fila[0];
						$xEjecutar=$fila[3];
						$Obligaciones=$fila[6];
						$Pagado=$fila[9];
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";							
							$res3=ExQuery($cons3);				
							echo $cons3;
						}
						$Titulo1=substr($fila[0],0,1);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							//echo "$cons11<br>";
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo "$cons3<br>";
							$res3=ExQuery($cons3);							
						}	
						$Titulo2=substr($fila[0],0,2);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						$Titulo3=substr($fila[0],0,4);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}						
				
							
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$fila4[4]";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
							//echo $cons3;
							$res3=ExQuery($cons3);
						}
						$Titulo1=substr($fila[3],0,1);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							//echo "$cons11<br>";
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo "$cons3<br>";
							$res3=ExQuery($cons3);							
						}	
						$Titulo2=substr($fila[3],0,2);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						$Titulo3=substr($fila[3],0,4);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						
							
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$fila4[4]";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
							//echo $cons3;
							$res3=ExQuery($cons3);
						}
						$Titulo1=substr($fila[6],0,1);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							//echo "$cons11<br>";
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo "$cons3<br>";
							$res3=ExQuery($cons3);							
						}	
						$Titulo2=substr($fila[6],0,2);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						$Titulo3=substr($fila[6],0,4);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						
						
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[9]' and Compania='$Compania[0]' and anio=$fila4[4]";
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila[9]','$fila[10]','$fila[11]','Detalle')";
							//echo $cons3;
							$res3=ExQuery($cons3);
						}		
						$Titulo1=substr($fila[9],0,1);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							//echo "$cons11<br>";
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo "$cons3<br>";
							$res3=ExQuery($cons3);							
						}	
						$Titulo2=substr($fila[9],0,2);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						$Titulo3=substr($fila[9],0,4);
						$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
						//echo $cons2;
						$res2=ExQuery($cons2);
						if(ExNumRows($res2)<=0)				
						{
							$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
							$res11=ExQuery($cons11); $fila11=ExFetch($res11);
							$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
							($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
							//echo $cons3;
							$res3=ExQuery($cons3);							
						}
						 				
				
						$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
						$res3=ExQuery($cons3); $fila3=ExFetch($res3);
						$Autoid=$fila3[0]+1;		
							
						$cons2="select cuenta,nombre from presupuesto.plancuentas 
						where cuenta like '$fila4[2]' and vigencia='$fila4[0]' and clasevigencia='$fila4[1]' and anio=$fila4[4] and tipo='Detalle' group by cuenta,nombre";
						$res2=ExQuery($cons2);	echo ExError();
						
						
						//Reservas---Algoritmo
						$fila2=ExFetch($res2);
		?>  	   		<tr bgcolor="#666699" style="color:white;font-weight:bold">
							<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
						</tr>
						<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
							<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
						</tr> <?
						//Apropiacion Inicial--->Aprobado-xEjecutar
						$cons3="insert into presupuesto.crucecuentascero 		
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						($fila4[4],'$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";					
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
						//Reduccion--->xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values						
						($fila4[4],'$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
						$Autoid++;
			?>			<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
						//Obligacion presupuestal--->Obligaciones-xEjecutar
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						($fila4[4],'$Compania[0]','$CompContable','Obligacion presupuestal','$Obligaciones','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Obligacion Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $xEjecutar?></td></tr><?
						//Disminucion a obligacion presupuestal-->xEjecutar-Aprobado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						($fila4[4],'$Compania[0]','$CompContable','Disminucion a obligacion presupuestal','$xEjecutar','$Obligaciones','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Disminucion a Obligacion Presupuestal</strong></td><td><? echo $xEjecutar?></td><td><? echo $Obligaciones?></td></tr><?
						//Egreso Presupuestal-->Pagado-Obligaciones
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						($fila4[4],'$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$Obligaciones','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
						$Autoid++;
					?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $Obligaciones?></td></tr><?
						//Disminucion a Egreso Presupuestal--->Obligaciones-Pagado
						$cons3="insert into presupuesto.crucecuentascero 
						(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
						($fila4[4],'$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$Obligaciones','$Pagado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
						//echo "$cons3<br>";
						$res3=ExQuery($cons3);
							$Autoid++;
					?>	<tr align="center"><td><strong>Disminucion a Egreso Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Pagado?></td></tr><?
						
					}
					else{			
						if($fila4[1]=="CxP")
						{
							$cons="select 	
							codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codpagado,nompagado,natupagado
							from presupuesto.ctasxpagar where codaprobado='$fila4[3]'";			
							$res=ExQuery($cons);			
							//echo $cons;			
							$fila=ExFetch($res);
							$Aprobado=$fila[0];
							$xEjecutar=$fila[3];					
							$Pagado=$fila[6];
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);				
							}
							$Titulo1=substr($fila[0],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[0],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[0],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
						
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}							
							$Titulo1=substr($fila[3],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[3],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[3],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
						
						
						
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}		
							$Titulo1=substr($fila[6],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[6],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[6],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}							
														 			
								
							$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
							$res3=ExQuery($cons3); $fila3=ExFetch($res3);
							$Autoid=$fila3[0]+1;			
							$cons2="select cuenta,nombre from presupuesto.plancuentas 
							where cuenta like'$fila4[2]' and vigencia='$fila4[0]' and clasevigencia='$fila4[1]' and anio=$fila4[4] and tipo='Detalle' group by cuenta,nombre";
							$res2=ExQuery($cons2);	echo ExError();
							
							
							//Ctas x Pagar
							$fila2=ExFetch($res2);
						?> 	<tr bgcolor="#666699" style="color:white;font-weight:bold">
								<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
							</tr>
							<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
								<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
							</tr> <?						
							//Apropiacion Inicial--->xEjecutar-Aprobado
							$cons3="insert into presupuesto.crucecuentascero 						
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							//echo "$cons3<br>";
							$res3=ExQuery($cons3);
							$Autoid++;
						?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
							//Reduccion--->Aprobado-xEjecutar
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";						
							//echo "$cons3<br>";	
							$res3=ExQuery($cons3);
							$Autoid++;
						?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
							//Egreso presupuestal--->Pagado-xEjecutar	
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values	
							($fila4[4],'$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";										
						?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $xEjecutar?></td></tr><?
							//Diminucion a egreso presupuestal-->Recaudado-xEjecutar						
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$xEjecutar','$Pagado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Diminucion a Egreso Presupuestall</strong></td><td><? echo $xEjecutar?></td><td><? echo $Pagado?></td></tr><?
					
						}
						else{					
							$cons="select codaprobado,nomaprobado,natuaprobado,codxejecutar,nomxejecutar,natuxejecutar,codcomprometido,nomcomprometido,natucomprometido,
							codpagado,nompagado,natupagado,codobligaciones,nomobligaciones,natuobligaciones
							from presupuesto.vigencia where codaprobado='$fila4[3]'";			
							$res=ExQuery($cons);			
							//echo $cons;			
							$fila=ExFetch($res);
							$Aprobado=$fila[0];
							$xEjecutar=$fila[3];					
							$Comprometido=$fila[6];
							$Pagado=$fila[12];
							$Obligaciones=$fila[9];
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[0]' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[0]','$fila[1]','$fila[2]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);				
							}
							$Titulo1=substr($fila[0],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[0],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[0],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
						
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[3]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[3]','$fila[4]','$fila[5]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}
							$Titulo1=substr($fila[3],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[3],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[3],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
						
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[6]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[6]','$fila[7]','$fila[8]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}	
							$Titulo1=substr($fila[6],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[6],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[6],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
															 			
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[9]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[9]','$fila[10]','$fila[11]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}	
							$Titulo1=substr($fila[9],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[9],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[9],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
							
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$fila[12]' and Compania='$Compania[0]' and anio=$fila4[4]";
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila[12]','$fila[13]','$fila[14]','Detalle')";
								//echo $cons3;
								$res3=ExQuery($cons3);
							}	
							$Titulo1=substr($fila[12],0,1);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo1' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo1'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								//echo "$cons11<br>";
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo "$cons3<br>";
								$res3=ExQuery($cons3);							
							}	
							$Titulo2=substr($fila[12],0,2);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo2' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo2'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							$Titulo3=substr($fila[12],0,4);
							$cons2="select cuenta from contabilidad.plancuentas where cuenta='$Titulo3' and Compania='$Compania[0]' and anio=$fila4[4]";
							//echo $cons2;
							$res2=ExQuery($cons2);
							if(ExNumRows($res2)<=0)				
							{
								$cons11="select cuenta,nombre,naturaleza,tipo from presupuesto.plancuentascero where cuenta='$Titulo3'";
								$res11=ExQuery($cons11); $fila11=ExFetch($res11);
								$cons3="insert into contabilidad.plancuentas (anio,compania,cuenta,nombre,naturaleza,tipo) values 
								($fila4[4],'$Compania[0]','$fila11[0]','$fila11[1]','$fila11[2]','$fila11[3]')";
								//echo $cons3;
								$res3=ExQuery($cons3);							
							}
							
								
							$cons3="select autoid from presupuesto.crucecuentascero where compania='$Compania[0]' order by autoid desc";
							$res3=ExQuery($cons3); $fila3=ExFetch($res3);
							$Autoid=$fila3[0]+1;			
							$cons2="select cuenta,nombre from presupuesto.plancuentas 
							where cuenta like'$fila4[2]' and vigencia='$fila4[0]' and clasevigencia='$fila4[1]' and anio=$fila4[4] and tipo='Detalle' group by cuenta,nombre";
							$res2=ExQuery($cons2);	echo ExError();
				
				
							//Vigencia
							$fila2=ExFetch($res2);
						?> 	<tr bgcolor="#666699" style="color:white;font-weight:bold">
								<td align="center" colspan="3">Nuevo Amarre <? echo $fila2[0]?> - <? echo $fila2[1]?></td>
							</tr>
							<tr bgcolor="#666699" style="color:white;font-weight:bold" align="center">
								<td>Comprobante</td><td>Cuenta DB</td><td>Cuenta HB</td>
							</tr> <?						
							//Apropiacion Inicial--->xEjecutar-Aprobado
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Apropiacion inicial','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Apropicacion Incial</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
							//Adicion ---> xEjecutar-Aprobado
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Adicion','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Adicion</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
							//Reduccion--->Aprobado-xEjecutar
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Reduccion','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";						
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";		
						?>	<tr align="center"><td><strong>Reduccion</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
							//Compromiso presupuestal--->
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values	
							($fila4[4],'$Compania[0]','$CompContable','Compromiso presupuestal','$Comprometido','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";	
						?>	<tr align="center"><td><strong>Compromiso Presupuestal</strong></td><td><? echo $Comprometido?></td><td><? echo $xEjecutar?></td></tr><?
							//Disminucion a compromiso--->xEjecutar-Comprometido
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Disminucion a compromiso','$xEjecutar','$Comprometido','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";	
						?>	<tr align="center"><td><strong>Disminucion a compromiso</strong></td><td><? echo $xEjecutar?></td><td><? echo $Comprometido?></td></tr><?
							//Obligacion presupuestal--->Obligaciones-Comprometido
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Obligacion presupuestal','$Obligaciones','$Comprometido','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";																					
						?>	<tr align="center"><td><strong>Obligacion Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Comprometido?></td></tr><?
							//Disminucion a obligacion Presupuestal-->Comprometido-Obligaciones						
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Disminucion a obligacion presupuestal','$Comprometido','$Obligaciones','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Disminucion a Obligacion Presupuestal</strong></td><td><? echo $Comprometido?></td><td><? echo $Obligaciones?></td></tr><?
							//Egreso presupuestal-->Pagado-Obligaciones						
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Egreso presupuestal','$Pagado','$Obligaciones','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Egreso Presupuestal</strong></td><td><? echo $Pagado?></td><td><? echo $Obligaciones?></td></tr><?
							//Disminucion a egreso presupuestal-->Comprometido-Obligaciones						
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Disminucion a egreso presupuestal','$Obligaciones','$Pagado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','0')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Disminucion a Egreso Presupuestal</strong></td><td><? echo $Obligaciones?></td><td><? echo $Pagado?></td></tr><?
							//Traslados (Contra Credito)-->Aprobado-xEjecutar
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Traslado','$Aprobado','$xEjecutar','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','Contra Credito')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Traslado (Contra Credito)</strong></td><td><? echo $Aprobado?></td><td><? echo $xEjecutar?></td></tr><?
							//Traslados (Credito)--->xEjecutar-Aprobado
							$cons3="insert into presupuesto.crucecuentascero 
							(anio,compania,compcontable,tipocomppresupuestal,ctadebe,ctahaber,ctapresupuestal,autoid,vigencia,clasevigencia,referencia) values
							($fila4[4],'$Compania[0]','$CompContable','Traslado','$xEjecutar','$Aprobado','$fila2[0]',$Autoid,'$fila4[0]','$fila4[1]','Credito')";
							$res3=ExQuery($cons3);
							$Autoid++;
							//echo "$cons3<br>";
						?>	<tr align="center"><td><strong>Traslado (Credito)</strong></td><td><? echo $xEjecutar?></td><td><? echo $Aprobado?></td></tr><?
						
						}
					}
				}				
			}
		}
		else{?>
			<tr><td bgcolor="#e5e5e5" style="font-weight:bold">No hay datos provenientes de Exel</td></tr>  
<?		}	
	}?>        
</table> 
</form>    
</body>
</html>

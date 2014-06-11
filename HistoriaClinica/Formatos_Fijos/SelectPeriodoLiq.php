<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($CorrigePxS){
		$cons="select numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre from salud.pagadorxservicios where compania='$Compania[0]' 
		order by numservicio";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Pagadores["$fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]"]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8]);
		}
		$cons="delete from salud.pagadorxservicios where compania='$Compania[0]'";
		$res=ExQuery($cons);
		foreach($Pagadores as $Paga){
			if($Paga[6]){$FF=",fechafin";$FF2=",'$Paga[6]'";}
			if($Paga[8]){$FC=",fechacre";$FC2=",'$Paga[8]'";}
			$cons="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre $FF $FC) values
			($Paga[0],'$Paga[1]','$Paga[2]','$Paga[3]','$Paga[4]','$Paga[5]','$Paga[7]' $FF2 $FC2)";
			//echo $cons."<br>";
			$res=ExQuery($cons);
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.FechaIni.value==""){
			alert("Debe seleccionar la fecha inicial!!!");return false;
		}		
		if(document.FORMA.FechaFin.value==""){
				alert("Debe seleccionar la fecha final!!!");return false;
		}			
		if(document.FORMA.FechaFin.value<document.FORMA.FechaIni.value){
			alert("La fecha inicial debe ser menor a la final!!!");return false;
		}	
	}
</script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<!-- <input type="submit" name="CorrigePxS" value="CorrigePxS"> -->
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr>	
    <? 	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}else{$C1="";}			
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		?>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Inicio</td>
    	<td><input type="Text" name="FechaIni"  readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>"></td>       
        
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>
        <td><input type="Text" name="FechaFin"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>"></td>
        <td><input type="submit" value="Ver" name="Ver" /></td>        
   	</tr>
    <tr align="center">
	    <td colspan="8">
    		<input type="button" value="Cancelar" onClick="location.href='VerLiquidaciones.php?DatNameSID=<? echo $DatNameSID?>'"/>
       	</td>
    </tr>
</table>
<br> 
<?
if($Ver){
	$FechaFinAux=explode("-",$FechaFin);
	$first_of_month = mktime (0,0,0, $FechaFinAux[1], 1, $FechaFinAux[0]); 
	$Dias = date('t', $first_of_month); 	
	if(strcmp($Dias,$FechaFinAux[2])==0){
		if($FechaFinAux[1]==12){
			$FechaFinAux[0]++; $FechaFinAux[1]=1; $FechaFinAux[2]=1;
		}
		else{
			$FechaFinAux[1]++; $FechaFinAux[2]=1;
		}
	}
	else{
		$FechaFinAux[2]++;
	}
	
	//echo "$FechaFinAux[0]-$FechaFinAux[1]-$FechaFinAux[2]";
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,estado from salud.servicios 
	where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' order by numservicio";
	$res=ExQuery($cons); 
	
	$ban2==0;
	$FecIni=explode("-",$FechaIni);			
	$FI = mktime (0,0,0,$FecIni[1],$FecIni[2],$FecIni[0]);	
	$FecFin=explode("-",$FechaFin);			
	$FF = mktime (0,0,0,$FecFin[1],$FecFin[2],$FecFin[0]); ?>	
    
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">   	    	
      	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
			<td>No. Servicio</td><td>Proceso</td><td>Pagador</td><td>Contrato</td><td>No. Contrato</td><td>Fecha Inicial</td><td>Fecha Final</td>
       	</tr> 
	<?	while($fila=ExFetch($res)){
			$ban=0;
			$fil=explode(" ",$fila[2]); 
			$Fec1 = explode("-",$fil[0]);			
			$F1 = mktime (0,0,0,$Fec1[1],$Fec1[2],$Fec1[0]);	
			if($fila[3]!=''){
				$fil2=explode(" ",$fila[3]);
				$Fec2 = explode("-",$fil2[0]);			
				$F2 = mktime (0,0,0,$Fec2[1],$Fec2[2],$Fec2[0]);	
			}
			
			if($FI<=$F1){  
				if($fila[3]==''){
					if($F1<=$FF){$ban=1;}
				}
				else{
					
					if($F2>=$FF){
						if($FF>=$F1){$ban=1; }
					}
					else{
						$ban=1; 
					}
				}
			}
			else{ 
				if($fila[3]==""){					
					//echo "F1=$F1 FF=$FF<br>\n";
					if($F1<=$FF){$ban=1;}
				}
				else{
					if($F2>=$FF){
						$ban=1;
					}
					else{
						if($F2>$FI){$ban=1;}
					}
				}
			}				
			if($ban==1){ 
				$ban2=1;
				$cons2="select (primape || segape || primnom || segnom) as nom,fechaini,fechafin,contrato,nocontrato,entidad from salud.pagadorxservicios,central.terceros 
				where terceros.compania='$Compania[0]' and numservicio=$fila[0] and identificacion=entidad and pagadorxservicios.compania='$Compania[0]'";
				//echo $cons2."<br>";
				$res2=ExQuery($cons2);
				if(ExNumRows($res2)>0){                                    
	        	  	while($fila2=ExFetch($res2)){ 
						$FecIniPaga="";
						$fechaIniFila2 = explode("-",$fila2[1]);			
						$FIPaga = mktime (0,0,0,$fechaIniFila2[1],$fechaIniFila2[2],$fechaIniFila2[0]);	
						if($fila2[2]!=''){							
							$fechaFinFila2 = explode("-",$fila2[2]);			
							$FFPaga = mktime (0,0,0,$fechaFinFila2[1],$fechaFinFila2[2],$fechaFinFila2[0]);	
						}				
						//echo "$fila2[1]XXXX$fila2[2]<br>";
						$Ban3="";		
						if($FIPaga<=$FI){							
							$FecIniPaga=$FechaIni;
							if($fila2[2]==''){
								$Ban3=1;								
								$FecFinPaga=$FechaFin;	 //caso1
							}
							else{	
								if($FFPaga>=$FF){
									$Ban3=1;
									$FecFinPaga=$FechaFin; //caso2
								}
								else{
									if($FFPaga>=$FI){
										$Ban3=1;
										$FecFinPaga=$fila2[2];//caso3
									}
								}								
							}							
						}
						else{																				
							$FecIniPaga=$fila2[1];
							if($fila2[2]==''){
								if($FIPaga<=$FF){
									//echo "entra";
									$Ban3=1;
									$FecFinPaga=$FechaFin;//caso4
								}
							}		
							else{
								if($FIPaga<=$FF){
									$Ban3=1;
									if($FFPaga>=$FF){
										$FecFinPaga=$FechaFin;//caso 5
									}
									else{
										$FecFinPaga=$fila2[2];
									}
								}		
							}					
						}
						if($Ban3){
							$FechaFinAux=explode("-",$FecFinPaga);
							$first_of_month = mktime (0,0,0, $FechaFinAux[1], 1, $FechaFinAux[0]); 
							$Dias = date('t', $first_of_month); 	
							if(strcmp($Dias,$FechaFinAux[2])==0){
								if($FechaFinAux[1]==12){
									$FechaFinAux[0]++; $FechaFinAux[1]=1; $FechaFinAux[2]=1;
								}
								else{
									$FechaFinAux[1]++; $FechaFinAux[2]=1;
								}
							}
							else{
								$FechaFinAux[2]++;
							}?>
	                    	<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Liquidar" align="center"
    	                	onClick="location.href='ValidaPeriodoLiq.php?DatNameSID=<? echo $DatNameSID?>&NumServ=<? echo $fila[0]?>&FecIniLiq=<? echo $FecIniPaga?>&FecFinLiq=<? echo "$FechaFinAux[0]-$FechaFinAux[1]-$FechaFinAux[2]"?>&FecFinLiq2=<? echo $FecFinPaga?>&Paga=<? echo $fila2[5]?>&PagaCont=<? echo $fila2[3]?>&PagaNocont=<? echo $fila2[4]?>'">        	                	<td><? echo $fila[0]?></td><td><? echo $fila[1]?>&nbsp;</td><td><? echo $fila2[0]?></td><td><? echo $fila2[3]?></td><td><? echo $fila2[4]?></td>
                                <td><? echo $FecIniPaga?></td><td><? echo $FecFinPaga?>&nbsp;</td>
            	          	</tr>
				<?		}
					}?>                        		                	
    <?			}
			}
			$fila[3]==''; $fil2='';
		}
		if($ban2==0){?>
        	<tr align="center">
            	<td style="font-weight:bold" colspan="10">Noy elementos para ser liquidados durantes este periodo</td>
            </tr>	
	<?	}?>        	
	</table><?
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

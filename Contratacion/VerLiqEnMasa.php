<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	global $ND;
	$ND=getdate();	
	global $CupsMeds;
	global $ban2;
	global $Simula;
	
	function diferenciaDias($inicio, $fin){    
		$inicio = strtotime($inicio);    
		$fin = strtotime($fin);    
		$dif = $fin - $inicio;    
		$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);    
		return ceil($diasFalt);
	}
	
	function LiqCupsoMeds($FechaIni,$FechaFin,$Cedula,$NumServ,$filaPlan0,$filaPlan1,$FechaFinEstancia,$Entidad,$NomEnt,$NomPac,$Contrato,$NoContrato,$Ambito,$filaPMes1,$PrimDia,$UltDia,$Simula,$Medicotte,$Autorizac1,$Autorizac2,$Autorizac3,$Tipousu,$Nivelusu,$Nocarnet)
	{	
		
		//echo $Medicotte;
		global $ban2;								
		global $Compania;				
		global $ND;		
		global $usuario;		
		
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Cedula' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[0]==1||$filaAmb[2]==1||$filaAmb[3]==1){
			$Ambito="1";
		}
		if($filaAmb[1]=="1"){
			$Ambito="2";
		}
		if($filaAmb[4]=="1"){
			$Ambito="3";
		}
		
		$FecIL=explode("-",$FechaIni);	
		$FIL = mktime (0,0,0,$FecIL[1],$FecIL[2],$FecIL[0]);	
		$FecFL=explode("-",$FechaFinEstancia);		
		$FFL = mktime (0,0,0,$FecFL[1],$FecFL[2],$FecFL[0]);		
			
		$cons="select fechaini,fechafin,noliquidacion from facturacion.liquidacion where compania='$Compania[0]' and cedula='$Cedula' and estado='AC' and numservicio=$NumServ 
		and pagador='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato'";	
		$res=ExQuery($cons); echo ExError();
		//echo "$cons<br>\n";	
			
		while($fila=ExFetch($res)){//Ciclo para verificar si el periodo ya ha sido liquidado
			$FecICons=explode("-",$fila[0]);		//echo $fila[0];	
			$FIC = mktime (0,0,0,$FecICons[1],$FecICons[2],$FecICons[0]);	
			$FecFCons=explode("-",$fila[1]);			//echo " $fila[1] ";
			$FFC = mktime (0,0,0,$FecFCons[1],$FecFCons[2],$FecFCons[0]);
			//echo "FIL=$FechaIni FFL=$FechaFinEstancia FIC=$fila[0] FFC=$fila[1]<br>\n";		
			if($FIL<=$FIC){  		
				if($FFL>=$FFC){
					$Rep=1; 
					$NoLiq=$fila[2];
				}
				else{
					if($FFL>=$FCI){
						$Rep=1;		
						$NoLiq=$fila[2];			
					}
				}			
			}
			else{ 
				if($FFC>=$FFL){
					$Rep=1;
					$NoLiq=$fila[2];
				}	
				else{
					if($FIL<=$FFC){
						$Rep=1;					
						$NoLiq=$fila[2];
					}	
				}					
			}				
		}	//Si la variable $Rep es igual a 1 entonces el periodo ya ha sido liquidado						
		
		//Encontramos todos los formatos
		$cons="select tblformat,formato,tipoformato,laboratorio from historiaclinica.formatos where estado='AC' and compania='$Compania[0]'";
		$res=ExQuery($cons);		
		if(ExNumRows($res)>0){
			while($fila=ExFetch($res)){	
				//Encontramos los cups				
				$cons6="select cup,fecha,dx1,dx2,dx3,dx4,dx5,tipodx,finalidadconsult,causaexterna,hora,$fila[0].id_historia,cups.nombre,formarealizacion
				from histoclinicafrms.$fila[0],histoclinicafrms.cupsxfrms,contratacionsalud.cups
				where $fila[0].compania='$Compania[0]' and  $fila[0].fecha>='$FechaIni' and $fila[0].fecha<='$FechaFin' and $fila[0].cedula='$Cedula'
				and $fila[0].numservicio=$NumServ and $fila[0].noliquidacion=0 and cupsxfrms.compania='$Compania[0]' and cupsxfrms.id_historia=$fila[0].id_historia
				and $fila[0].formato='$fila[1]' and $fila[0].tipoformato='$fila[2]' and $fila[0].formato=cupsxfrms.formato and $fila[0].tipoformato=cupsxfrms.tipoformato
				and cups.compania='$Compania[0]' and cups.codigo=cupsxfrms.cup and cupsxfrms.cedula='$Cedula'";	
										
				$res6=ExQuery($cons6);						
				//echo "<br>".$cons6."<br>";				
				if(ExNumRows($res6)){					
					while($fila6=ExFetch($res6)){																
						//Encotramos el valor del segun los planes tarifafios
						$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila6[0]' and autoid=$filaPlan1";												
						$resVr=ExQuery($consVr);
						$filaVr=ExFetch($resVr);
						//echo $consVr."<br>"; 
						
						//Encotramos el grupo del segun los planes de servicios 
						$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
						where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila6[0]' and cups.compania='$Compania[0]' and cups.codigo=cup and autoid=$filaPlan0";
						$res3=ExQuery($cons3); 
						$fila3=ExFetch($res3);						
						//echo $cons3."<br>";
						if($fila3[3]==1){$NoFacturable="0";}else{$NoFacturable="1";$filaVr[0]="0";}
						if($fila3[0]==''){$filaVr[0]="0";}							
						$vT=$filaVr[0];
						if($vT==''){$vT="0";}
						if($filaVr[0]==''){$filaVr[0]="0";}
						if($fila3[1]==''){$fila3[1]="012";}
						
						//PENDIENTE VERIFICACION DE FACTURABLE O NO
						if($fila[3]){//Si es de tipo laboratorio se verifica si ha sido interpretado o no
							$consADx="select interpretacion from histoclinicafrms.ayudaxformatos where compania='$Compania[0]' and formato='$fila[1]' and tipoformato='$fila[2]'
							and cedula='$Cedula' and numservicio=$NumServ and id_historia=$fila6[11]";
							$resADx=ExQuery($consADx);
							if(ExNumRows($resADx)<=0){
								$NoIntLab="1";
							}
							else{
								$NoIntLab="0";
							}						
						}
						$IdTmp="$fila6[0]$fila3[0]$fila3[1]$fila6[1]$fila6[8]$fila6[9]$fila6[2]$fila6[7]$fila6[3]$fila6[4]$fila6[5]$fila6[6]";
						//IdTmP=>CodCup,Grupo,Tipo,Fecha,Finalidad,CausaExterna,Dx1,TipoDx,Dx2,Dx3,Dx4,Dx5						
						//							
						if($Tmp[$IdTmp][0]==$fila6[0]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][3]==$fila3[1]&&$Tmp[$IdTmp][8]==$fila6[8]&&$Tmp[$IdTmp][15]==$fila6[9]&&$Tmp[$IdTmp][9]==$fila6[2]&&$Tmp[$IdTmp][11]==$fila6[3]&&$Tmp[$IdTmp][12]==$fila6[4]&&$Tmp[$IdTmp][13]==$fila6[5]&&$Tmp[$IdTmp][14]==$fila6[6]&&$Tmp[$IdTmp][10]==$fila6[7]&&$Tmp[$IdTmp][7]==$fila6[1])
						{								
	//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																								
							$Tmp[$IdTmp][4]++;
							$Tmp[$IdTmp][17]=$NoIntLab;
							$ban1=1;	
						}
						else{												
							$Tmp[$IdTmp]=array($fila6[0],$fila6[12],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila6[1],$fila6[8],$fila6[2],$fila6[7],$fila6[3],$fila6[4],$fila6[5],$fila6[6],$fila6[9],$NoIntLab,$NoFacturable,$fila6[13]);
		//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																											
							$ban1=1;	
						}
						//echo "$fila6[0],$fila6[12],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila6[1],$fila6[8],$fila6[9],$fila6[2],$fila6[7],$fila6[3],$fila6[4],$fila6[5],$fila6[6],$fila6[8],$NoIntLab,$NoFacturable<br>";					//echo $Tmp[$IdTmp]."<br>";						
					}
				}
			}	
		}			
		
		//Odontologia----------------------------------------------------------------------------------------------------------------------------		
		$cons="select cup,fecha,nombre,diagnostico1,diagnostico2,diagnostico3,diagnostico4,diagnostico5,finalidadprocedimiento,formarealizacion   
		from odontologia.odontogramaproc,contratacionsalud.cups
		where identificacion='$Cedula' and fecha>='$FechaIni' and fecha<='$FechaFin' and numservicio=$NumServ and odontogramaproc.compania='$Compania[0]' and cups.compania='$Compania[0]'
		and cup=codigo";
		$res=ExQuery($cons);		
		//echo $cons."<br>";
		while($fila=ExFetch($res)){
			$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila[0]' and autoid=$filaPlan1";						
			$resVr=ExQuery($consVr);
			$filaVr=ExFetch($resVr);
			//echo $consVr."<br>";
			$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
			where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila[0]' and cups.compania='$Compania[0]' and cups.codigo=cup and autoid=$filaPlan0";
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);						
			//echo $cons3."<br>";
			if($fila3[3]==1){$NoFacturable="0";}else{$NoFacturable="1";$filaVr[0]="0";}
			
			if($fila3[0]==''){$filaVr[0]="0";}
			$vT=$filaVr[0];			
			if($vT==''){$vT="0";}
			if($filaVr[0]==''){$filaVr[0]="0";}			
			if($fila3[1]==''){$fila3[1]="00005";}
			}
			$IdTmp="$fila[0]$fila3[0]$fila3[1]$fila[3]$fila[4]$fila[5]$fila[6]$fila[7]$fila[8]$fila[9]";
			//En el IdTmp=>CodigoCUP,Grupo,Tipo,dx1,dx2,dx3,dx4,dx5,finalidad,formaRealizacion		
			
			$cons5555="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
			where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'
			and finalidad='$fila[7]' and dxppal='$fila[2]' and dxrel1='$fila[3]' and dxrel2='$fila[4]' and dxrel3='$fila[5]' 
			and dxrel4='$fila[6]' and formarealizacion='$fila[8]'";
			
			$ban1=1;//AQUI--- FATLA ARREGLAR EL TMP Y LAS COMPARACIONES
			if($Tmp[$IdTmp][0]==$fila[0]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][2]==$fila3[1]){
				$Tmp[$IdTmp][4]=$Tmp[$IdTmp][4]++;
			}
			else{
			 $Tmp[$IdTmp]=array($fila[0],$fila[1],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila[1],$fila[8],$fila[3],'',$fila[4],$fila[5],$fila[6],$fila[7],'','',$NoFacturable,$fila[9]);				
				//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																												
			}			
		}
		//Medicamentos 							
		$cons4="select autoid,cantidad,regmedicamento,movimiento.almacenppal,fecha,numservicio from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Cedula' and tipocomprobante='Salidas' and noliquidacion is null and 	almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.numservicio=$NumServ and movimiento.fecha>='$FechaIni' 
		and movimiento.fecha<'$FechaFin'";			
		$res4=ExQuery($cons4);
		//echo "$cons4<br>\n";
		while($fila4=ExFetch($res4)){		
			//filaPMes			
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa 
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes1' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and Codigo1='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";			
			$res3=ExQuery($cons3); echo ExError();
			$fila3=ExFetch($res3);			
			$vT=$fila[1]*$fila3[5];
			if($vT==''){$vT="0";}
			if($fila4[1]==$fila4[2]){$noE="0";}else{$noE="1";}	
			//echo "$cons3<br>\n";
			//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,generico,presentacion,forma,almacenppal,noentregado
			//<td> Cup=$Dato[0], NomCup=$Dato[1], Grupo=$Dato[2], Tipo=$Dato[3], Vr=$Dato[5], Ambito=$Dato[6], fec Interp=$Dato[7], finalidad=$Dato[8], detalle=$Dato[1], causa ext=$Dato[9], dxppal=$Dato[10], tipodx=$Dato[11], Cant=$Dato[4], dxrel1=$Dato[12], dxrel2=$Dato[13], dxrel3=$Dato[14], dxrel4=$Dato[15], Generico=$Dato[16], presentacion=$Dato[17], forma=$Dato[18], almacenppal=$Dato[19], noentregado=$Dato[20]</td>
			
			if($Tmp[$fila3[0]]){				
				if($noE=="1"){
					$Tmp[$fila3[0]][20]=$noE;
				}
				$Tmp[$fila4[0]][4]=$Tmp[$fila4[0]][4]+$fila4[1];
				$ban1=1;
			}
			else{			
				$Tmp[$fila4[0]]=array($fila4[0],$fila3[2],$fila3[0],'Medicamentos',$fila4[1],$fila3[5],'',$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE);										
				$ban1=1;
			}						
		}		
				
		//Estamcia				
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Cedula' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$$filaAmb[2]==1){				
			$cons2="select planbeneficios,plantarifario,primdia,ultdia from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$NoContrato' 
			and compania='$Compania[0]'";	
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1;}
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Cedula' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$Contrato' and nocontrato='$NoContrato' and entidad='$Entidad' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup order by fechai";					
			//echo "$cons<br>\n";			
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res)){
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}			
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$filaPlan0 and cupsxplanes.autoid=$filaPlan1 and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);
				if($fila3[0]==""){$fila3[2]="0";}
					
				$FIinicial1=explode("-",$FechaIni); //echo  "FecIniLiq=$FecIniLiq  ";
				$FIinicial2=explode("-",$fila[1]); //echo $fila[1]."<br>";
				
				$FI1 = mktime (0,0,0,$FIinicial1[1],$FIinicial1[2],$FIinicial1[0]); //echo "FIinicial1= $FIinicial1[0] - $FIinicial1[2] - $FIinicial1[1]  <br>\n";			
				$FI2 = mktime (0,0,0,$FIinicial2[1],$FIinicial2[2],$FIinicial2[0]); //echo "Fila[1]=$FIinicial2[0] - $FIinicial2[2] - $FIinicial2[1] <br>\n";
				
				$FFinal1=explode("-",$FechaFin); 
				$FFinal2=explode("-",$fila[2]);
				
				$FF1 = mktime (0,0,0,$FFinal1[1],$FFinal1[2],$FFinal1[0]);	
				if($fila[2]){$FF2 = mktime (0,0,0,$FFinal2[1],$FFinal2[2],$FFinal2[0]);	}
				
				$FecIniEstancia="";
				$FecFinEstancia="";
				$DiasCobro="";					
				if($FI2<=$FI1){ //Si la fecha Inicial del periodo de la estancia es menor a la fecha inicial Seleccionada					
					$FecIniEstancia=$FechaIni;
					if($fila[2]==''){									
						$FecFinEstancia=$FechaFin; //echo "caso 1 ";
					}					
					else{
						if($FF2>=$FF1){
							$FecFinEstancia=$FechaFin;  //echo "caso 2 ";
						}
						else{
							if($FF2>=$FI1){
								$FecFinEstancia=$fila[2]; //echo "caso 3 ";
							}
						}
					}					
				}
				else{
					$FecIniEstancia=$fila[1];
					if($fila[2]==''){
						if($FI2<=$FF1){							
							$FecFinEstancia=$FechaFin; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FechaFin; //echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				$DiasCobro++;	
							
				if($Num>0){									
					$DiasCobro--;			
				}			
				//echo "  $FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro NumServ=$NumServ Cedula=$Cedula<br>";
				if($fila3[0]==""){$fila3[2]="0";}
				$vT=$fila3[2]*$DiasCobro;
				if($fila3[2]==''){$fila3[2]="0";}
				if($fila3[1]==''){$fila3[1]="00001";}		
				if($fila3[3]==1){$Facturable="";$Facturable1="";$Facturable2="";}else{ $Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1";}
				if($DiasCobro>0)
				{ 
					$ban1=1;
					if($Tmp["$fila[0]$fila3[0]$fila3[1]"][0]==$fila[0]&&$Tmp["$fila[0]$fila3[0]$fila3[1]"][2]==$fila3[0]&&$Tmp["$fila[0]$fila3[0]$fila3[1]"][2]==$fila3[1]){
						$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]=$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]+$DiasCobro;
					}
					else{
						$Tmp["$fila[0]$fila3[0]$fila3[1]"]=array($fila[0],$fila[5],$fila3[0],$fila3[1],$DiasCobro,$fila3[2],$Ambito,$FecFinEstancia);
						//$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
						//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,cant,dx,tipodx
					}					
				}			
			}
			//En caso de no cobrar del primer dia
			if($PrimDia==0){
				if($Tmp["$fila[0]$fila3[0]$fila3[1]"][4]>1){
					$Tmp["$fila[0]$fila3[0]$fila3[1]"]=NULL; $ban1=0;
				}
				else{
					$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]--;
					$ban1=1;
				}				
			}
			//En caso de no cobrar el ultimo dia
			if($NoEgr==0){
				if($UltDia==0){	
					if($Tmp["$fila[0]$fila3[0]$fila3[1]"][4]>1){
						$Tmp["$fila[0]$fila3[0]$fila3[1]"]=NULL;$ban1=0;
					}
					else{
						$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]--;$ban1=1;
					}
					
				}				
			}
		}		
		//$FechaIni,$FechaFin,$Cedula,$NumServ,$filaPlan0,$filaPlan1,$FechaFinEstancia,$Entidad,$NomEnt,$NomPac,$Contrato,$NoContrato,$Ambito,$filaPMes1,$PrimDia,$UltDia
		if($ban1==1){
		?>	
            <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center" >	
                <tr>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">NoServicio</td><td><? echo $NumServ?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Identificacion</td><td><? echo $Cedula?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><? echo $NomPac?></td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Ambito</td><td><? echo $Ambito?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Cod Enitdad</td><td><? echo $Entidad?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Nom Entidad</td><td><? echo $NomEnt?></td>
                </tr>            
                <tr>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td><td><? echo $Contrato?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td><td><? echo $NoContrato?></td>                
                </tr>    
        	</table>       
            <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center" >	
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                <td>Codigo</td>
                <td>Descripcion</td>
                <td>Cantidad</td>
                <td>Vr Unidad</td>
                <td>Vr Total</td>    
  			</tr>
     	<?	$Subtotal=0;
			//Meds
			$cons5="select grupo,almacenppal from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
			$res5=ExQuery($cons5);
			while($fila5=ExFetch($res5)){
				$Sub=0;
				$ban3='';
				foreach($Tmp as $Dato){				
					if($Dato[3]=='Medicamentos'&&$Dato[2]==$fila5[0]){								
				?>		<tr>
   				 <?			$Vrtot=$Dato[5]*$Dato[4];
				 			echo "<td align='center'>$Dato[0]</td><td align='center'>$Dato[16] $Dato[18] $Dato[17]</td><td align='center'>$Dato[4]</td>
				 			<td align='right'>".number_format($Dato[5],2)."</td>
					 		<td align='right'>".number_format($Vrtot,2)."</td>";
							$Sub=$Sub+$Vrtot;?>    
                       	</tr>
			<?			$Subtotal=$Subtotal+$fila4[4];						
						$ban3=1;
					}
				}
				if($ban3){?>
					<tr bgcolor="#ECECEC">
                        <td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>                
                    </tr><?
              	}				
			}			
			//CUPS	
			$cons5="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
			$res5=ExQuery($cons5);  
			while($fila5=ExFetch($res5)){
				$Sub=0;
				$ban3='';
				foreach($Tmp as $Dato){			//echo "$Dato[0] $Cedula<br>";		
					if($Dato[3]!='Medicamentos'&&$Dato[2]==$fila5[1]){			
				?>		<tr> <?						 		
						$Vrtot=$Dato[4]*$Dato[5];
						echo "<tr><td align='center'>$Dato[0]</td><td align='center'>$Dato[1]</td><td align='center'>$Dato[4]</td><td align='right'>".number_format($Dato[5],2)."</td>
						<td align='right'>".number_format($Vrtot,2)."</td></tr>";
						$Sub=$Sub+$Vrtot; 
						$Subtotal=$Subtotal+$Vrtot;
						//echo "$Subtotal<br>";
						$ban3=1;
					}
				}
				if($ban3){?>
					<tr bgcolor="#ECECEC">
						<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>                
					</tr>
			<?	}
			}			
			if($Subtotal!=''){?>
                <tr>
                    <td colspan="4" align="right"><strong>SubTotal General:</strong></td>
                    <td align="right"><? echo number_format($Subtotal,2)?></td>                    
                </tr>
     	<?	}		
			
			$Total=$Subtotal; 
			$consul="select tipoasegurador from central.terceros where identificacion='$Entidad' and compania='$Compania[0]' and Tipo='Asegurador'";
			//echo $consul."<br>\n";
			$result=ExQuery($consul);
			$row=ExFetch($result);		
			
			$consul2="select valor,clase,tipocopago from salud.topescopago where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' 
			and nivelusu='$Nivelusu' and ambito='$Ambito'";		
			//echo "$consul2<br>\n";
			$result2=ExQuery($consul2); $fil=ExFetch($result2);
			$Tipocopago=$fil[2];
			$ClaseCopago=$fil[1];
			if($fil[1]=='Fijo'){
				$Valorcopago=$fil[0]; $Porsentajecopago="0";			
			}
			else{
				$Valorcopago=($fil[0]/100)*$Total; $Porsentajecopago=$fil[0];	
			}				
			$Total=$Total-$Valorcopago;
			if($Total>0){?>
            	<tr>
                    <td colspan="2" align="right"><strong>Porcentaje Copago: </strong></td><td align="center"><? echo $Porsentajecopago?>%</td>
                    <td align="right"><strong>Vr Copago</strong></td><td align="right"><? echo number_format($Valorcopago,2)?></td>            
                </tr>
                <tr>
                    <td colspan="4" align="right"><strong>Total:</strong></td>
                    <td align="right"><? echo number_format($Total,2)?></td>                    
                </tr>
		<?	
				if($Rep){
					//echo "<tr><td>$Cedula</td></tr>"; ?>
					<tr>
						<td colspan="5"><font style="color:#FF0000;font-weight:bold">Paciente Omitido!! El periodo selecionado o parte de este ya ha sido Liquidado</font>
							<br><input type="button" value="Liquidacion No. <? echo $NoLiq?>" 
								onclick="open('../HistoriaClinica/Formatos_Fijos/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiquidacion=<? echo $NoLiq?>&Ced=<? echo $Cedula?>','','width=800,height=600,scrollbars=YES')">
						</td>               	
					</tr>
    	<?		}
				else{
					if($Simula!=1){
						$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
						$res=ExQuery($cons);$fila=ExFetch($res);				
						$AutoIdLiq=$fila[0]+1;	
						
						if($Fechae!=''){$FechaEgreso1=",fechafin"; $FechaEgreso2=",'$Fechae'";}
						if($Entidad!=''){$Pagador1=",pagador,contrato,nocontrato";$Pagador2=",'$Entidad','$Contrato','$NoContrato'";}				
						if($Valorcopago!=''){
							if($Porsentajecopago==''){$Porsentajecopago="0";}
							$Copago1=",valorcopago,porsentajecopago,tipocopago,clasecopago";$Copago2=",$Valorcopago,$Porsentajecopago,'$Tipocopago','$ClaseCopago'";}
						if($Porsentajedesc==''){$Porsentajedesc="0";}
						if($Total!=''){$TyST1=",subtotal,total";$TyST2=",'$Subtotal','$Total'";}
						
						$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,
						noliquidacion,numservicio,cedula,fechafin $Pagador1  $Copago1  $TyST1) values ('$Compania[0]','$usuario[1]',
						'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Ambito','$Medicotte','$FechaIni','$Nocarnet','$Tipousu', 
						'$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3',$AutoIdLiq,$NumServ,'$Cedula','$FechaFinEstancia'  $Pagador2 $Copago2  $TyST2)";				
						//echo " $cons<br>\n";
						$res=ExQuery($cons); echo ExError();
						$NoLiquidacion=$AutoIdLiq;		
						foreach($Tmp as $Dato){				
							$VrT=$Dato[4]*$Dato[5];
							if($Dato[3]!="Medicamentos"){	
								if($fila[11]==''){$fila[11]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}				
								$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
								,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal) 
								values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','$Dato[3]',
								'$Dato[0]','$Dato[1]',$Dato[4],$Dato[5],$VrT,$NoLiquidacion,'$Dato[7]','$Dato[8]','$Dato[9]','$Dato[10]','$Dato[12]','$Dato[13]'
								,'$Dato[14]','$Dato[11]')";
							}
							else{						
								$cons2="insert into facturacion.detalleliquidacion 
								(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion) values 
								('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','Medicamentos','$Dato[0]','$Dato[1]',
								$Dato[4],$Dato[5],$VrT,'$Dato[16]','$Dato[17]','$Dato[18]','$Dato[19]',$NoLiquidacion)";
							//echo $cons;
							}					
							$res2=ExQuery($cons2); echo ExError();
							//echo "<br>\n$cons2";
							//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,generico,presentacion,forma,almacenppal,noentregado
				/*echo "	<tr>
							<td colspan='30'> Cup=$Dato[0], NomCup=$Dato[1], Grupo=$Dato[2], Tipo=$Dato[3], Vr=$Dato[5], Ambito=$Dato[6], fec Interp=$Dato[7], finalidad=$Dato[8], detalle=$Dato[1], causa ext=$Dato[9], dxppal=$Dato[10], tipodx=$Dato[11], Cant=$Dato[4], dxrel1=$Dato[12], dxrel2=$Dato[13], dxrel3=$Dato[14], dxrel4=$Dato[15], Generico=$Dato[16], presentacion=$Dato[17], forma=$Dato[18], almacenppal=$Dato[19], noentregado=$Dato[20]</td>
						</tr>";	*/
						}
					}
				}
			}?>
			</table><br><?		
		}
	}

?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar2()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/> 
<?
if($Ver){
	$FechaFinAux=explode("-",$FechaFin);
	/*$first_of_month = mktime (0,0,0, $FechaFinAux[1], 1, $FechaFinAux[0]); 
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
	}*/
	if($Ambito){$TipoServ="and tiposervicio='$Ambito'";}
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,cedula,primape,segape,primnom,segnom,medicotte,autorizac1,autorizac2,autorizac3,servicios.tipousu,servicios.nivelusu,
	servicios.nocarnet
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ order by numservicio";
	$res=ExQuery($cons); echo ExError();
	//echo $cons;
	$ban2==0;
	$FecIni=explode("-",$FechaIni);			
	$FI = mktime (0,0,0,$FecIni[1],$FecIni[2],$FecIni[0]);	
	$FecFin=explode("-",$FechaFin);			
	$FF = mktime (0,0,0,$FecFin[1],$FecFin[2],$FecFin[0]); ?>	  	
    	    	
	<?	while($fila=ExFetch($res)){
			$Ambt="$fila[1]";
			$ban=0;			
			$fil=explode(" ",$fila[2]); 
			$Fec1 = explode("-",$fil[0]);			
			$F1 = mktime (0,0,0,$Fec1[1],$Fec1[2],$Fec1[0]);	
			if($fila[3]!=''){//Se verifica cuales servicios entra en el periodo
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
						if($F2>=$FI){$ban=1;}
					}
				}
			}	
			
			if($ban==1){ //solo entran los servicios cuyas fellas de ingreso y egresos conicidan con el periodo seleccionado
				
				$FFAux="$FechaFinAux[0]-$FechaFinAux[1]-$FechaFinAux[2]";				
				/*$ban2=1;
				
				/*?>        
                <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
                    		
    <?	*/		if($Entidad){$Ent=" and entidad='$Entidad'";}
				if($Contrato){$Contra=" and contrato='$Contrato'";}
				if($NoContrato){$Nocontra=" and nocontrato='$NoContrato'";}
				if($TipoAseg){$TAseg=" and tipoasegurador='$TipoAseg'";}
				
				$cons2="select fechaini,fechafin,entidad,contrato,nocontrato,primape,segape,primnom,segnom from salud.pagadorxservicios,central.terceros 
				where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=entidad and numservicio=$fila[0]
				$Ent $Contra $Nocontra $TAseg";
				$res2=ExQuery($cons2); echo ExError();
				//echo "$cons2<br>\n";				
				while($fila2=ExFetch($res2)){					
					$consPlan="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$fila2[2]' and contrato='$fila2[3]' and numero='$fila2[4]' 
					and compania='$Compania[0]'";	
					$resPlan=ExQuery($consPlan);echo ExError(); $filaPlan=ExFetch($resPlan);

					$conPMeds = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos where Numero='$fila2[4]' and Entidad='$fila2[2]' and contrato='$fila2[3]' 
					and Compania='$Compania[0]'";
					$resPMeds=ExQuery($conPMeds);echo ExError(); $filaPMes=ExFetch($resPMeds);
					
					$consUnd="select planbeneficios,plantarifario,primdia,ultdia from contratacionsalud.contratos where entidad='$fila2[2]' and contrato='$fila2[3]' 
					and numero='$fila2[4]' and compania='$Compania[0]'";	
					$resUnd=ExQuery($consUnd);echo ExError(); $filaUnd=ExFetch($resUnd);
					
					$CupsMeds='';
					//echo "$fila[0] XXX $FechaIni--> $FFAux<br>\n XXX";
					//echo "$fila[0] XXX $fila2[0]--> $fila2[1]<br>\n XXX";
					$filB=explode(" ",$fila2[0]); 
					$Fec1B = explode("-",$filB[0]);			
					$F1B = mktime (0,0,0,$Fec1B[1],$Fec1B[2],$Fec1B[0]);	
					if($fila2[1]!=''){ 
						$fil2B=explode(" ",$fila2[1]);
						$Fec2B = explode("-",$fil2B[0]);			
						$F2B = mktime (0,0,0,$Fec2B[1],$Fec2B[2],$Fec2B[0]);
						
						$FilaFinAux=explode("-",$fila2[1]);
						$first_of_month = mktime (0,0,0, $FilaFinAux[1], 1, $FilaFinAux[0]); 
						$Dias = date('t', $first_of_month); 	
						if(strcmp($Dias,$FilaFinAux[2])==0){
							if($FilaFinAux[1]==12){
								$FilaFinAux[0]++; $FilaFinAux[1]=1; $FilaFinAux[2]=1;
							}
							else{
								$FilaFinAux[1]++; $FilaFinAux[2]=1;
							}
						}
						else{
							$FilaFinAux[2]++;
						}
						$FilaFAux="$FilaFinAux[0]-$FilaFinAux[1]-$FilaFinAux[2]";	
					}
					$NomEnt="$fila2[5] $fila2[6] $fila2[7] $fila2[8]";
					$NomPaciente="$fila[5] $fila[6] $fila[7] $fila[8]";
					//Se verifican los periodos para los pagadores x servicio
					if($F1B<=$FI){ 
						if($fila2[1]!=''){
							if($F2B>=$FI){								
								//Buscar meds o cups desde FechaIni (no se sabe hasta donde)								
								if($F2B<=$FF){
										//Buscar meds o cups desde FechaIni hasta fila2[1]										
										LiqCupsoMeds($FechaIni,$fila2[1],$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$fila2[1],$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);
										//echo "<br>\n $fila[0]: $FechaIni -> $fila2[1] de $fila[4]";	
										//BuscarMoC($FechaIini,$fila2[1],);									
								}
								else{									
									//Buscar meds o cups desde FechaIni hasta $FechaFin
									LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);
									//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
								}						
							}
						}
						else{	//echo "$FechaIni hasta $FFAux";						
							//Buscar meds o cups desde FechaIni hasta FechaFin							
							LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);
							//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
						}
					}
					else{ //F1B>FI						
						if($F1B<=$FF){
							//Buscar desde $fila[0](no se sabe hasta donde)
							if($fila2[1]!=''){
								if($F2B<=$FF){//echo "entra";
										//Buscar meds o cups desde FechaIni hasta fila2[1]	
										LiqCupsoMeds($fila2[0],$fila2[1],$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$fila2[1],$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);
										//echo "<br>\n $fila[0]: $fila2[0] -> $fila2[1] de $fila[4]";									
								}
								else{
									//Buscar meds o cups desde FechaIni hasta $FechaFin			
									//LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);				
									//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
								}
							}
							else{
								//echo " ACA ES  ";
								//Buscar desde $fila[0] hasta $FechaFin
								LiqCupsoMeds($fila2[0],$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15]);
								//echo "<br>\n $fila[0]: $fila2[0]->$FechaFin de $fila[4]";
								//LiqCupsoMeds($FechaIni,$FechaFin);
							}
						}
					}
				}
			}
			$fila[3]==''; $fil2='';				
		}
		if($ban2==0){?>
            <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
                <tr align="center">
                    <td style="font-weight:bold" colspan="10">Noy elementos para ser liquidados durantes este periodo</td>
                </tr>	
            </table>
	<?	}     
}?>
</form>    
</body>
</html>

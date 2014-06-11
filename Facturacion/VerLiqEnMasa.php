<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	global $ND;
	$ND=getdate();	
	global $CupsMeds;
	global $ban2;
	global $Simula;
	global $BanLiq;
	$BanLiq=0;
	global $NoLiqConsecIni;
	global $NoLiqConsecFin;
	global $TotalALiq;
	global $SepMedNoPOS;
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	
	function diferenciaDias($inicio, $fin){   
		$diasFalt=0;
		//echo " INICIO=$inicio  FIN=$fin ";
		$inicio = strtotime($inicio);    
		$fin = strtotime($fin);    
		$dif = $fin - $inicio;    
		$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);  				
		//echo "diasFalt=$diasFalt";
		return ceil($diasFalt);
	}
	
	function LiqCupsoMeds($FechaIni,$FechaFin,$Cedula,$NumServ,$filaPlan0,$filaPlan1,$FechaFinEstancia,$Entidad,$NomEnt,$NomPac,$Contrato,$NoContrato,$Ambito,$filaPMes1,$PrimDia,$UltDia,$Simula,$Medicotte,$Autorizac1,$Autorizac2,$Autorizac3,$Tipousu,$Nivelusu,$Nocarnet,$filaPMes0)
	{				
		//echo $Medicotte;	
		//echo "FechaIni=$FechaIni FechaFin=$FechaFin FechaFinEstancia=$FechaFinEstancia PrimDia=$PrimDia UltDia=$UltDia";
		global $ban2;								
		global $Compania;				
		global $ND;		
		global $usuario;		
		global $BanLiq;
		global $NoLiqConsecIni;
		global $NoLiqConsecFin;
		global $DatNameSID;
		global $TotalALiq;
		global $GruposMeds;
		global $SepMedNoPOS;
		
		$AmbitoReal=$Ambito;
		
		$cons="select consumcontra,monto,restriccioncobro from ContratacionSalud.Contratos where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and numero='$NoContrato'";
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res); $ConsumoContra=$fila[0]; $MontoContra=$fila[1]; $RestricCobro=$fila[2];
		if($RestricCobro==1)
		{
			$consRestric="select grupo,mostrar,montofijo,cobrar from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato'";
			$resRestric=ExQuery($consRestric);			
			while($filaRestric=ExFetch($resRestric))
			{
				$Rescric[$filaRestric[0]]=array($filaRestric[1],$filaRestric[2],$filaRestric[3]); //Rescric[grupo] = mostrar,montofijo,cobrar				
			}
		}
		
		$consFac="select sum(total),entidad,contrato,nocontrato from facturacion.facturascredito 
		where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato' and estado='AC' group by entidad,contrato,nocontrato";						
		$resFac=ExQuery($consFac);
		$filaFac=ExFetch($resFac); $EjecucionContra=$filaFac[0];	
			
		$consLiq="select sum(total) from facturacion.liquidacion 
		where compania='$Compania[0]' and pagador='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato' and estado='AC' and nofactura is null";	
		$resLiq=ExQuery($consLiq);
		$filaLiq=ExFetch($resLiq); $xFacturarContra=$filaLiq[0];
		
		$SaldoContra=$MontoContra-$ConsumoContra-$EjecucionContra-$xFacturarContra;
		
		$cons="select tiposervicio from salud.servicios where compania='$Compania[0]' and cedula='$Cedula' and numservicio=$NumServ";	
		//echo $cons."<br>";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$AmbitoReal=$fila[0];
		
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
		
		//De Historia Clinica
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
				if(ExNumRows($res6)){					
					while($fila6=ExFetch($res6)){	
						//echo "<br>".$cons6."<br>";				
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
						if($fila3[0]==''){$filaVr[0]="0";$ban2=0;}else{$ban2=1;}
						if($fila3[3]==1){$NoFacturable="0";$ban2=1;}else{$NoFacturable="1";$filaVr[0]="0";$ban2=0;}													
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
						if($Tmp[$IdTmp][0]==$fila6[0]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][3]==$fila3[1]&&$Tmp[$IdTmp][8]==$fila6[8]&&$Tmp[$IdTmp][15]==$fila6[9]&&$Tmp[$IdTmp][9]==$fila6[2]&&$Tmp[$IdTmp][11]==$fila6[3]&&$Tmp[$IdTmp][12]==$fila6[4]&&$Tmp[$IdTmp][13]==$fila6[5]&&$Tmp[$IdTmp][14]==$fila6[6]&&$Tmp[$IdTmp][10]==$fila6[7]&&$Tmp[$IdTmp][7]==$fila6[1])
						{					//echo "Acutliza";			
	//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																								
							$Tmp[$IdTmp][4]++;
							$Tmp[$IdTmp][17]=$NoIntLab;
							$ban1=1;	
						}
						else{	//echo "lo inserta $fila6[0]<br>";	
							//echo "inserta";
							$Tmp[$IdTmp]=array($fila6[0],$fila6[12],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila6[1],$fila6[8],$fila6[2],$fila6[7],$fila6[3],$fila6[4],$fila6[5],$fila6[6],$fila6[9],$NoIntLab,$NoFacturable,$fila6[13]);
		//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																											
							$ban1=1;	
						}
						
						//echo "$fila6[0],$fila6[12],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila6[1],$fila6[8],$fila6[9],$fila6[2],$fila6[7],$fila6[3],$fila6[4],$fila6[5],$fila6[6],$fila6[8],$NoIntLab,$NoFacturable<br>";					//echo $Tmp[$IdTmp]."<br>";						
					}
				}
			}	
		}					
		//Odontologia
		$cons="select odontogramaproc.cup,fecha,cups.nombre,diagnostico1,diagnostico2,diagnostico3,diagnostico4,diagnostico5,finalidadprocedimiento,formarealizacion   
		from odontologia.odontogramaproc,contratacionsalud.cups,odontologia.procedimientosimgs
		where identificacion='$Cedula' and fecha>='$FechaIni' and fecha<='$FechaFin' and numservicio=$NumServ and odontogramaproc.compania='$Compania[0]' 
		and cups.compania='$Compania[0]' and procedimientosimgs.compania='$Compania[0]' and procedimientosimgs.cup=odontogramaproc.cup
		and odontogramaproc.cup=cups.codigo and diagnostico1!='' and tipoodonto='Seguimiento'";					
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
			if($fila3[0]==''){$filaVr[0]="0";$ban2=0;}else{$ban2=1;}
			if($fila3[3]==1){$NoFacturable="0";$ban2=1;}else{$NoFacturable="1";$filaVr[0]="0";$ban2=0;}			
			$vT=$filaVr[0];			
			if($vT==''){$vT="0";}
			if($filaVr[0]==''){$filaVr[0]="0";}			
			if($fila3[1]==''){$fila3[1]="00005";}
			
			$IdTmp="$fila[0]$fila3[0]$fila3[1]$fila[3]$fila[4]$fila[5]$fila[6]$fila[7]$fila[8]$fila[9]";						
			//En el IdTmp=>CodigoCUP,Grupo,Tipo,dx1,dx2,dx3,dx4,dx5,finalidad,formaRealizacion				
			
			$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
			where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'
			and finalidad='$fila[7]' and dxppal='$fila[2]' and dxrel1='$fila[3]' and dxrel2='$fila[4]' and dxrel3='$fila[5]' 
			and dxrel4='$fila[6]' and formarealizacion='$fila[8]'";			
			
			$ban1=1;		
			if($Tmp[$IdTmp][0]==$fila[0]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][3]==$fila3[1]&&$Tmp[$IdTmp][8]==$fila[8]&&$Tmp[$IdTmp][9]==$fila[3]&&$Tmp[$IdTmp][11]==$fila[4]&&$Tmp[$IdTmp][12]==$fila[5]&&$Tmp[$IdTmp][13]==$fila[6]&&$Tmp[$IdTmp][14]==$fila[7]&&$Tmp[$IdTmp][18]==$fila[9]&&$Tmp[$IdTmp][1]!=""){
				$Tmp[$IdTmp][4]++; 
				//echo "Insert".$Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][4]."<br>";
			}
			else{
			 $Tmp[$IdTmp]=array($fila[0],$fila[2],$fila3[0],$fila3[1],'1',$filaVr[0],$Ambito,$fila[1],$fila[8],$fila[3],'',$fila[4],$fila[5],$fila[6],$fila[7],'','',$NoFacturable,$fila[9]);							
				 //echo "Act".$Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][4]."<br>";
				//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																																
			}			
		}
		//Medicamentos 							
		/*$cons4="select autoid,cantidad,regmedicamento,movimiento.almacenppal,fecha,numservicio from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Cedula' and tipocomprobante='Salidas' and noliquidacion is null and 	almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.numservicio=$NumServ and movimiento.fecha>='$FechaIni' 
		and movimiento.fecha<='$FechaFin'";*/	
		
		$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Cedula' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
		and movimiento.fechadespacho<='$FechaFin' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";
		/*$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
from consumo.movimiento,consumo.almacenesppales 
where movimiento.compania='Clinica San Juan de Dios' and cedula='79149172' and tipocomprobante='Salidas' and noliquidacion is null 
and almacenesppales.compania='Clinica San Juan de Dios' and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' 
and movimiento.fecha>='2012-05-01' and movimiento.fecha<='2012-05-24' 
group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum 
order by autoid";*/
		//echo $cons4;
		$res4=ExQuery($cons4);		
		
		while($fila4=ExFetch($res4)){		
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes1' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);$ATC[$fila4[6]]=$fila3[8];$CUM[$fila4[6]]=$fila4[6];					
			//echo $cons3."<br>";
			if($fila3[6]){
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$filaPMes0' and almacenppal='$fila4[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);			
			if($filaMedsxPlan[0]){	
				if($fila3[0]==""){$fila3[5]=0;$ban2=0;}else{$ban2=1;}
				if($filaMedsxPlan[1]==1){$NoFacturable="0";$ban2=1;}else{$NoFacturable="1"; $fila3[5]=0;$ban2=0;}														

				$vT=round($fila[1])*$fila3[5];
				
				if($vT==''){$vT="0";}
				if($fila4[1]==$fila4[2]){$noE="0";}else{$noE="1";}							
				//echo "$cons3<br>\n";
			    
                $IdTmp="$fila3[6]$fila3[0]$fila4[4]$fila4[3]$fila4[6]";			
				
				$consDev="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
				where movimiento.compania='$Compania[0]' and cedula='$Cedula' and tipocomprobante='Devoluciones' and noliquidacion is null and 	almacenesppales.compania='$Compania[0]'	
				and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
				and movimiento.fechadespacho<='$FechaFin' and numservicio=$NumServ and autoid=$fila4[0] and cum='$fila4[6]'
				group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum order by cum";			
				//echo $consDev."<br>";
				$resDev=ExQuery($consDev);$filaDev=ExFetch($resDev);
				//while($filaDev=ExFetch($resDev)){
					  if(!$filaDev[1])$Dev=0;
					     else $Dev=$filaDev[1];
					  //if($fila4[1]<$Dev)
					     //$Cant=($Dev-$fila4[1]);
						 //else
					     $Cant=($fila4[1]-$Dev);
						 //echo "<br>".$fila4[1]." - ".$Dev;
				//}
				//En IdTmp=>CodigoMed,autoid,autoid,AlmacenPpal	
				if($SepMedNoPOS){
					if($fila3[7]==1){
						if($Tmp[$IdTmp][0]==$fila3[6]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][7]==$fila4[4]&&$Tmp[$IdTmp][19]==$fila4[3]){	
							if($noE=="1"){
								$Tmp[$IdTmp][20]=$noE;
							}					
							$Tmp[$IdTmp][4]=$Tmp[$IdTmp][4]+$fila4[1]; 
							$ban1=1;					
						}
						else{		
							//echo "$fila3[0] ".$GruposMeds[$fila3[0]]."  ";
							if($Cant>0)
					           $Tmp[$IdTmp]=array($fila4[6],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable);										
					   else 
					       if($Cant>0)
						   $Tmp[$IdTmp]=array($fila4[6],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable);										
							$ban1=1;
							//echo "$fila3[2] $fila3[3] $fila3[4]<br>";
							//echo $Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][21]."<br>";
							//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,generico,presentacion,forma,almacenppal,noentregado,NoFacturable					
						}		
					}
					else{
						//$MedNoPos[$IdTmp]	
						if($MedNoPos[$IdTmp][0]==$fila3[6]&&$MedNoPos[$IdTmp][2]==$fila3[0]&&$MedNoPos[$IdTmp][7]==$fila4[4]&&$MedNoPos[$IdTmp][19]==$fila4[3]){	
							if($noE=="1"){
								$MedNoPos[$IdTmp][20]=$noE;
							}					
							$MedNoPos[$IdTmp][4]=$MedNoPos[$IdTmp][4]+$fila4[1]; 						
						}
						else{					
							//echo "$fila3[0] ".$GruposMeds[$fila3[0]]."  ";
							if($Cant>0)
							$MedNoPos[$IdTmp]=array($fila4[6],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable);										
							
							//echo "$fila3[2] $fila3[3] $fila3[4]<br>";
							//echo $MedNoPos[$IdTmp][1]." ".$MedNoPos[$IdTmp][21]."<br>";
							//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,generico,presentacion,forma,almacenppal,noentregado,NoFacturable					
						}
					}
				}
				else
				{
					if($Tmp[$IdTmp][0]==$fila3[6]&&$Tmp[$IdTmp][2]==$fila3[0]&&$Tmp[$IdTmp][7]==$fila4[4]&&$Tmp[$IdTmp][19]==$fila4[3])
					{	
						if($noE=="1"){
							$Tmp[$IdTmp][20]=$noE;
						}					
						$Tmp[$IdTmp][4]=$Tmp[$IdTmp][4]+$fila4[1]; 
						$ban1=1;					
					}
					else{ //echo "<br><br>".$IdTmp." |".$Tmp[$IdTmp]."<- ".$fila3[6]." ".$fila3[2]." ".$GruposMeds[$fila3[0]]." ".'Medicamentos'." ".round($Cant)." ".$fila3[5]." ".$Ambito." ".$fila4[4]." ".$fila3[2]." ".$fila3[3]." ".$fila3[4]." ".$fila4[3]." ".$noE." ".$NoFacturable." ".$fila3[8];					
						//echo "$fila3[0] ".$GruposMeds[$fila3[0]]."  ";
						if($fila3[8])$ATC_CODE_=$fila3[8];
						   else $ATC_CODE_=$filaMedsxPlan[0];//$fila4[6];
						if($Cant>0)
						if($fila3[0]=="Dispositivo Medico")
					      $Tmp[$IdTmp]=array($fila4[0],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable,$ATC_CODE_,$fila3[0]);										
					      else 
					       $Tmp[$IdTmp]=array($fila4[6],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable,$ATC_CODE_,$fila3[0]);										
							
						//$Tmp[$IdTmp]=array($fila4[6],$fila3[2],$GruposMeds[$fila3[0]],'Medicamentos',round($Cant),$fila3[5],$Ambito,$fila4[4],'','','','','','','','',$fila3[2],$fila3[3],$fila3[4],$fila4[3],$noE,$NoFacturable,$ATC_CODE_);										
						$ban1=1;
						//echo "$fila3[2] $fila3[3] $fila3[4]<br>";
						//echo $Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][21]."<br>";
						//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,generico,presentacion,forma,almacenppal,noentregado,NoFacturable					
					}
				}
			}
			}					
		}						
		//Estancia				
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Cedula' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$filaAmb[2]==1){				
			$cons2="select planbeneficios,plantarifario,primdia,ultdia,ajustardias from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$NoContrato' 
			and compania='$Compania[0]'";	
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1;}
			$AjustarDias=$fila2[4];
			
			$cons="select fechaini,fechafin from salud.pagadorxservicios where compania='$Compania[0]' and numservicio=$NumServ
			and entidad='$Entidad' and contrato='".trim(preg_replace('/[^a-zA-Z0-9\s]/',utf8_encode("Ñ"),str_replace("\'","",str_replace("Ã",utf8_encode(""),utf8_encode($Contrato)))))."' and nocontrato='$NoContrato'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FecIniPag=$fila[0]; $FecFinPag=$fila[1];			
			
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Cedula' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$Contrato' and nocontrato='$NoContrato' and entidad='$Entidad' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup order by fechai";					
			//echo "<br>$cons<br>";		
			
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res))
			{
				$DiasCobro=0;
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}			
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor,cupsxplanservic.facturable
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$filaPlan0 and cupsxplanes.autoid=$filaPlan1 and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);				
					
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
				//echo "FI1=$FI1 FI2=$FI2 FF1=$FF1 FF2=$FF2<br> XXX";			
				if($FI2<=$FI1){ //echo "Si la fecha Inicial del periodo de la estancia es menor o igual a la fecha inicial Seleccionada echo $fila[2]";
					$FecIniEstancia=$FechaIni;
					if(empty($fila[2])){									
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
							else{
								$FecIniEstancia=""; //echo " caso 4 ";
							}
						}
					}					
				}
				else{ //echo "Si la fecha Inicial del periodo de la estancia es mayor a la fecha inicial Seleccionada";
					$FecIniEstancia=$fila[1];
					if($fila[2]==''){
						if($FI2<=$FF1){							
							$FecFinEstancia=$FechaFin; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FechaFin;//echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				//$DiasCobro++;	
				//echo " DiasCobro=$DiasCobro";
				//echo " FecIniPag=$FecIniPag FecFinPag=$FecFinPag DiasCobro=diferenciaDias $FecIniEstancia y $FecFinEstancia";			
				if($Num>0){									
					//$DiasCobro--;	echo "entra";		
				}			
				//echo "  $FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro NumServ=$NumServ Cedula=$Cedula<br>";
				if($fila3[3]==1){$NoFacturable="0"; $ban2=1;}else{ $NoFacturable="1"; $ban2=0; $fila3[2]="0";}
				if($fila3[0]==""){$fila3[2]="0";}
				$vT=$fila3[2]*$DiasCobro;
				if($fila3[2]==''){$fila3[2]="0";}
				if($fila3[1]==''){$fila3[1]="00001";}		
				
				if($FecIniEstancia==$FecFinEstancia){
					$DiasCobro=1;
				}			
				
				if($DiasCobro>0)
				{ 	
					
					if($FecIniEstancia<$FecFinEstancia){
						$DiasCobro=$DiasCobro+1;
					}
					//echo "$DiasCobro FecIniEstancia=$FecIniEstancia FechaFinAnt=$FechaFinAnt<BR>";
					if($FechaFinAnt==$FecIniEstancia){
						$DiasCobro--;
					}					
					if($DiasCobro>0){
						if($PrimDia==0){
							if($FecIniPag==$FecIniEstancia){$DiasCobro--;}
						}	
					}
					if($NoEgr==0){
						if($UltDia==0){	
							if($DiasCobro>0)
							{
								if($FecFinPag==$FecFinEstancia){$DiasCobro--;}
							}
						}
					}
					if($DiasCobro==31 && $AjustarDias){$DiasCobro=30;}
					//else $DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);	
					$ban1=1; 					
					if($DiasCobro>0){
					if($Tmp["$fila[0]$fila3[0]$fila3[1]"][0]==$fila[0]&&$Tmp["$fila[0]$fila3[0]$fila3[1]"][2]==$fila3[0]&&$Tmp["$fila[0]$fila3[0]$fila3[1]"][2]==$fila3[1]){
						
						$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]=$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]+$DiasCobro;
						//echo $Tmp["$fila[0]$fila3[0]$fila3[1]"][0]."  ".$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]."<br>";
					}
					else{ 
					   $Tmp["$fila[0]$fila3[0]$fila3[1]"]=array($fila[0],$fila[5],$fila3[0],$fila3[1],$DiasCobro,$fila3[2],$Ambito,$FecFinEstancia,'','','','','','','','','',$NoFacturable);						
		//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion																											
						//echo $Tmp["$fila[0]$fila3[0]$fila3[1]"][0]."  ".$Tmp["$fila[0]$fila3[0]$fila3[1]"][4]."<br>";	
					}}									
				}	
				$FechaIniAnt=$FecIniEstancia; $FechaFinAnt=$FecFinEstancia;			
			}
			//En caso de no cobrar del primer dia
		/*	if($PrimDia==0){
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
			}*/
		}		
		//$FechaIni,$FechaFin,$Cedula,$NumServ,$filaPlan0,$filaPlan1,$FechaFinEstancia,$Entidad,$NomEnt,$NomPac,$Contrato,$NoContrato,$Ambito,$filaPMes1,$PrimDia,$UltDia
		if($ban1==1){
		?>	       
        	<br>
            <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center" >	
                <tr>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">NoServicio</td><td><? echo $NumServ?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Identificacion</td><td><? echo $Cedula?></td>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><? echo $NomPac?></td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td><td><? echo $AmbitoReal?></td>
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
                <td>Codigo/CUM</td>
                <td>Descripcion</td>
                <td>Cantidad</td>
                <td>Vr Unidad</td>
                <td>Vr Total</td>    
  			</tr>
     	<?	
	//foreach($Tmp as $Ver){echo $Ver[0]."<br>";}	
			$Subtotal=0;
						
			$cons5="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
			$res5=ExQuery($cons5);  
			while($fila5=ExFetch($res5)){
				$Sub=0;
				$ban3='';
				$DatosAux=NULL;
				$BanDatos=0;
//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion	
				foreach($Tmp as $Dato){	
					if($Dato[2]==$fila5[1]){
						$BanDatos=1;
						if($DatosAux[$Dato[0]][0]==$Dato[0]){//echo "$Dato[0] incrementa $Dato[4] <br>";
							$DatosAux[$Dato[0]][2]=$DatosAux[$Dato[0]][2]+$Dato[4];
						}
						else{
							
							//echo $Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][21]."<br>";
							if($Dato[3]!="Medicamentos"){
								$DatosAux[$Dato[0]]=array($Dato[0],$Dato[1],$Dato[4],$Dato[5],$Dato[16],$Dato[17]);
								//CodCUP,Nombre,Cantidad,Valor,NoInterpretado,NoFacturable
							}
							else{
								if($DatosAux[$Dato[0]]){
									$DatosAux[$Dato[0]][2]++;
								}
								else{
									$DatosAux[$Dato[0]]=array($Dato[0],$Dato[1],$Dato[4],$Dato[5],$Dato[16],$Dato[21],$Dato[17],$Dato[18],$Dato[2]);									
								}
							}
						}
					}
				}
				if($BanDatos==1){
					foreach($DatosAux as $Dato2){
						if($Dato2[5]!=1){ 
							$Dato2[2]=round($Dato2[2]);?>
							<tr> <?		
							//Rescric[grupo] = mostrar,montofijo,cobrar								
							if($RestricCobro){
								if($Rescric)
								{									
									if($Rescric[$fila5[1]][0]=="Si")
									{
										if($Dato2[6]||$Dato2[7]){
										   if($Dato2[8]==38)	
											  echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7]</td>";
											  else
											      echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7] -ATC ".$ATC[$Dato2[0]]."-CUM ".$CUM[$Dato2[0]]."</td>";
										}
										else{
											echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
										}
										$ban3=1;
									
										if($Rescric[$fila5[1]][1]&&$Rescric[$fila5[1]][1]!="0")
										{
											
											echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format("0",2)."</td>
											<td align='right'>".number_format("0",2)."</td></tr>";
											$ban3=1;
											$Sub=$Rescric[$fila5[1]][1]; 
											$banCobGrup=$Rescric[$fila5[1]][1];
											//$Subtotal=$Subtotal+$Rescric[$fila5[1]][1];
										}
										else
										{ 
											if($Rescric[$fila5[1]][2]=="Si")
											{
												//$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
												$Vrtot=(($Dato2[2])*($Dato2[3]));//DECI
												echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format($Dato2[3],2)."</td>
												<td align='right'>".number_format($Vrtot,2)."</td></tr>";
												$Sub=$Sub+$Vrtot; 
												$Subtotal=$Subtotal+$Vrtot;
												$ban3=1;
											}
											else
											{
												echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format($Dato2[3],2)."</td>
												<td align='right'>".number_format("0",2)."</td></tr>";
												$ban3=1;
											}
										}
									}
								}
								else
								{
									$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
									if($Dato2[6]||$Dato2[7]){	
										echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7]</td>";
									}
									else{
										echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
									}
									echo "<td align='center'>$Dato2[2]</td>
									<td align='right'>".number_format($Dato2[3],2)."</td>
									<td align='right'>".number_format($Vrtot,2)."</td></tr>";
									$Sub=$Sub+$Vrtot; 
									$Subtotal=$Subtotal+$Vrtot;
									//echo "$Subtotal<br>";
									$ban3=1;
								}
							}			 		
							else
							{	
								if($Dato2[5]=="0"){
									$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
									//echo "$Dato2[1] $Dato2[7] $Dato2[8] $Dato2[9]<br>";
									$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
									if($Dato2[6]||$Dato2[7]){	
          							   if($Dato2[8]==38)															 //$Dato2[8]
										  echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7]</td>"; 
										  else 
										      echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7] -ATC ".$ATC[$Dato2[0]]."-CUM ".$CUM[$Dato2[0]]."</td>";
									}
									else{
										echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
									}
									echo "<td align='center'>$Dato2[2]</td>
									<td align='right'>".number_format($Dato2[3],2)."</td>
									<td align='right'>".number_format($Vrtot,2)."</td></tr>";
									$Sub=$Sub+$Vrtot; 
									$Subtotal=$Subtotal+$Vrtot;
									//echo "$Subtotal<br>";
									$ban3=1;
								}
							}
						}
					}
					if($ban3){
						if($RestricCobro){
							if($Rescric[$fila5[1]][0])
							{	if($banCobGrup){$Subtotal=$Subtotal+$banCobGrup; $banCobGrup="";}?>
								<tr bgcolor="#ECECEC"><!--AQUI GRUPO-->
									<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>
								</tr>				
						<?	}
						}
						else
						{?>
							<tr bgcolor="#ECECEC">
								<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>                
							</tr>				
					<?	}
					}
				}
			}			
			if($Subtotal!=''){?>
                <tr>
                    <td colspan="4" align="right"><strong>SubTotal General:</strong></td>
                    <td align="right"><? echo number_format(round($Subtotal),2)?></td>                    
                </tr>
     	<?	}		
			
			$Total=$Subtotal; 
			$consul="select copago from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$NoContrato'";
			$result=ExQuery($consul);
			$row=ExFetch($result);
			$SiCopago=$row[0];
			if($SiCopago=='1'){
				$consul="select tipoasegurador from central.terceros where identificacion='$Entidad' and compania='$Compania[0]' and Tipo='Asegurador'";
				//echo $consul."<br>\n";
				$result=ExQuery($consul);
				$row=ExFetch($result);		
				
				$consul2="select valor,clase,tipocopago,topeanual from salud.topescopago 
				where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' and nivelusu='$Nivelusu' and ambito='$AmbitoReal'";		
				//echo "$consul2<br>\n";
				$result2=ExQuery($consul2); $fil=ExFetch($result2);
				$Tipocopago=$fil[2];
				$ClaseCopago=$fil[1];
				if($fil[1]=='Fijo'){
					$Valorcopago=$fil[0]; $Porsentajecopago="0";			
				}
				else{
					$Valorcopago=($fil[0]/100)*$Total; 
					$consul3="select sum(valorcopago) from facturacion.liquidacion where cedula='$Cedula' and compania='$Compania[0]' 
					and nofactura is not null and porsentajecopago is not null and porsentajecopago!=0 and estado='AC' and fechacrea>='$ND[year]-01-01 00:00:00' 
					and fechacrea<='$ND[year]-12-31 23:59:59' group by cedula";
					$result3=ExQuery($consul3);
					//echo "$consul3 <br>";
					$fil3=ExFetch($result3); $CopAcumulado=$fil3[0]; $Tope=$fil[3];
					//echo "Copago='$Valorcopago' Copago Acumulado='$CopAcumulado' Tope='$Tope'";
					if(!$CopAcumulado){
						if($Tope<$Valorcopago){
							$Valorcopago=$Tope;
							$BanRecal=1;
						}
						else{$BanRecal=0;}
					}
					else{
						if(($Valorcopago+$CopAcumulado)>$Tope){
							$Valorcopago=$Tope-$CopAcumulado;
							$BanRecal=1;
						}
						else{$BanRecal=0;}
					}
					$Porsentajecopago=$fil[0];	
				}				
				$Total=$Total-$Valorcopago;
			}
			if(1>0){?>
            	<tr>
                    <td colspan="2" align="right"><strong>Porcentaje Copago: </strong></td><td align="center"><? echo $Porsentajecopago?>%</td>
                    <td align="right"><strong>Vr Copago</strong></td><td align="right"><? echo number_format(round($Valorcopago),2)?></td>            
                </tr>
                <tr>
                    <td colspan="4" align="right"><strong>Total:</strong></td>
                    <td align="right"><? echo number_format(round($Total),2)?></td>                    
                </tr>               
		<?		$TotalALiq=$TotalALiq+$Total;
				
				if($Total>$SaldoContra){?>
					<tr>
						<td colspan="5"><font style="color:#FF0000;font-weight:bold">Paciente Omitido!! El contrato no tiene saldo</font>
						</td>               	
					</tr>
			<?		
				}
				elseif($Rep){
					//echo "<tr><td>$Cedula</td></tr>"; ?>
					<tr>
						<td colspan="5"><font style="color:#FF0000;font-weight:bold">Paciente Omitido!! El periodo selecionado o parte de este ya ha sido Liquidado</font>
							<br><input type="button" value="Liquidacion No. <? echo $NoLiq?>" 
								onclick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiquidacion=<? echo $NoLiq?>&Ced=<? echo $Cedula?>','','width=800,height=600,scrollbars=YES')">
						</td>               	
					</tr>
    	<?		}
				else{
					if($Simula!=1){
						$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
						$res=ExQuery($cons);$fila=ExFetch($res);				
						$AutoIdLiq=$fila[0]+1;	
						
						if($BanLiq==0){$NoLiqConsecIni=$AutoIdLiq;$BanLiq=1; }
						$NoLiqConsecFin=$AutoIdLiq;
						
						if($Fechae!=''){$FechaEgreso1=",fechafin"; $FechaEgreso2=",'$Fechae'";}
						if($Entidad!=''){$Pagador1=",pagador,contrato,nocontrato";$Pagador2=",'$Entidad','$Contrato','$NoContrato'";}				
						if($Valorcopago!=''){
							if($Porsentajecopago==''){$Porsentajecopago="0";}
							$Copago1=",valorcopago,porsentajecopago,tipocopago,clasecopago";$Copago2=",$Valorcopago,$Porsentajecopago,'$Tipocopago','$ClaseCopago'";}
						if($Porsentajedesc==''){$Porsentajedesc="0";}
						if($Total!=''){$TyST1=",subtotal,total";$TyST2=",'$Subtotal','$Total'";}
						
						$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,
						noliquidacion,numservicio,cedula,fechafin $Pagador1  $Copago1  $TyST1) values ('$Compania[0]','$usuario[1]',
						'$FechaFin $ND[hours]:$ND[minutes]:$ND[seconds]','$AmbitoReal','$Medicotte','$FechaIni','$Nocarnet','$Tipousu', 
						'$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3',$AutoIdLiq,$NumServ,'$Cedula','$FechaFinEstancia'  $Pagador2 $Copago2  $TyST2)";				
						//echo " $cons<br>\n";
						$res=ExQuery($cons); 
						$NoLiquidacion=$AutoIdLiq;		
						$Dato=NULL;
								
						//Rescric[grupo] = mostrar,montofijo,cobrar								
						foreach($Tmp as $Dato){							
							if($Dato[0]){		
								//echo "$Dato[0]<br>";																
								$VrT=(/*floor*/($Dato[4])*/*floor*/($Dato[5]));	
//$Rescric[$filaRestric[0]]=array($filaRestric[1],$filaRestric[2],$filaRestric[3]); //Rescric[grupo] = mostrar,montofijo,cobrar
//$Tmp[$IdTmp]=array($fila4[0],$fila3[2],$GruposMeds[$fila3[0]],										
								if($Dato[3]!="Medicamentos"){		
									$Dato[4]=round($Dato[4]);
			//en el array 0-CodCup,1-NomCup,2-Grupo,3-Tipo,4-Cantidad,5-Valor,6-Ambito,7-fechainterp,8-finalidad,9-dx,10-tipodx,11-dxrel1,12-dxrel2,13-dexrel3,14-dxrel4
			//,15-CausaExterna,16-NoInterpretado,17-NoFacturable,18-FormaRealizacion	
									if($Dato[17]!=1){$Dato[17]="0";}
									if(!$Dato[18]){$Dato[18]="0";}
									if($Dato[7]){
										$FecInterp1=',fechainterpret';$FecInterp2=",'$Dato[7]'";
									}
									if($RestricCobro){	
										if($Rescric)
										{
											if($Rescric[$Dato[2]])
											{
												if($Rescric[$Dato[2]][0]=="Si")
												{
													$NoFacturab="0";}else{$NoFacturab="1";
												}
												
												if($Rescric[$Dato[2]][1]&&$Rescric[$Dato[2]][1]!="0")
												{
													if(!$BanRestric[$Dato[2]]){
														$Dato[5]=$Rescric[$Dato[2]][1];
														$VrT=$Rescric[$Dato[2]][1];
														$BanRestric[$Dato[2]]=1;
													}
												}
												else
												{
													if($Rescric[$Dato[2]][2]=="No")
													{
														$VrT="0";
													}													
												}
												
												
												/*__echo $consD="select sum(cantidad) from consumo.movimiento,consumo.codproductos
												where consumo.movimiento.autoid=consumo.codproductos.autoid and movimiento.compania='$Compania[0]' and cedula='$Cedula' and comprobante='Devoluciones' and tipocomprobante='Devoluciones' and noliquidacion is null	
												and estado='AC' and movimiento.fecha>='$FechaIni' 
												and movimiento.fecha<='$FechaFin' and numservicio=$NumServ and grupo like 'Medicamento%'";
												$resD=ExQuery($consD);  
												while($filaD=ExFetch($resD)){
													  $Cantidad=($Dato[4]-$filaD[0]);
													  }*/
												
										if(!$Dato[22])$ATC_CODE=$Dato[0];
					                       else $ATC_CODE=$Dato[22];		
												//echo "A_";
												$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
										,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,cum,rip,nofacturable $FecInterp1) 
										values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','$Dato[3]',
										'$ATC_CODE','$Dato[1]',$Dato[4],"./*floor*/($Dato[5]).",$VrT,$NoLiquidacion,'$Dato[8]','$Dato[15]','$Dato[9]','$Dato[11]','$Dato[12]','$Dato[13]'
										,'$Dato[14]','$Dato[10]',$Dato[6],'$Dato[18]','$Dato[0]','$Dato[22]',$NoFacturab $FecInterp2)";										
											}
										}
										else
										{   //echo "B_";
											$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
									,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,cum,rip,nofacturable $FecInterp1) 
									values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','$Dato[3]',
									'$Dato[0]','$Dato[1]',$Dato[4],"./*floor*/($Dato[5]).",$VrT,$NoLiquidacion,'$Dato[8]','$Dato[15]','$Dato[9]','$Dato[11]','$Dato[12]','$Dato[13]'
									,'$Dato[14]','$Dato[10]',$Dato[6],'$Dato[18]','$Dato[0]','$Dato[22]',$Dato[17] $FecInterp2)";									
										}
									}
									else
									{//echo "C_";
										$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
									,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,cum,rip,nofacturable $FecInterp1) 
									values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','$Dato[3]',
									'$Dato[0]','$Dato[1]',$Dato[4],"./*floor*/($Dato[5]).",$VrT,$NoLiquidacion,'$Dato[8]','$Dato[15]','$Dato[9]','$Dato[11]','$Dato[12]','$Dato[13]'
									,'$Dato[14]','$Dato[10]',$Dato[6],'$Dato[18]','$Dato[0]','$Dato[22]',$Dato[17] $FecInterp2)";									
									}
								}
								else{
		//Tmp= 0-CodCup,1-NomCup,2-Grupo,3-Tipo,4-Cantidad,5-Valor,6-Ambito,7-fechainterp,8-finalidad,9-dx,10-tipodx,11-dxrel1,12-dxrel2,13-dexrel3,14-dxrel4,15-CausaExterna,16-generico
		//,17-presentacion,18-forma,19-almacenppal,20-noentregado,21-NoFacturable	
									if($RestricCobro){	
										if($Rescric)
										{
											if($Rescric[$Dato[2]])
											{
												if($Rescric[$Dato[2]][0]=="Si")
												{$NoFacturab="0";}else{$NoFacturab="1";}
												if($Rescric[$Dato[2]][1]&&$Rescric[$Dato[2]][1]!="0")
												{													
													if(!$BanRestric[$Dato[2]]){
														$Dato[5]=$Rescric[$Dato[2]][1];
														$VrT=$Rescric[$Dato[2]][1];
														$BanRestric[$Dato[2]]=1;
													}
												}
												else
												{
													if($Rescric[$Dato[2]][2]=="No")
													{
														$VrT="0";
													}													
												}
											}
											$cont++;//echo "D_";
			$consRestric="select grupo from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato'";
			$resRestric=ExQuery($consRestric);			
			while($filaRestric=ExFetch($resRestric))
			{				
											if($GruposMeds[$Dato[23]]==$filaRestric[0]){
											$cons2="insert into facturacion.detalleliquidacion 									(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,cum,rip,noliquidacion,nofacturable) 
									values 
									('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','Medicamentos','$Dato[0]','$Dato[1]',
									$Dato[4],"./*floor*/($Dato[5]).",$VrT,'$Dato[16]','$Dato[17]','$Dato[18]','$Dato[19]','$Dato[0]','$Dato[22]',$NoLiquidacion,$NoFacturab)";
									}}		
										}
										else
										{
											if($Dato[21]!=1){$Dato[21]="0";}//echo "E_";													
											$cons2="insert into facturacion.detalleliquidacion 									(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,cum,rip,noliquidacion,nofacturable) 
									values 
									('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','Medicamentos','$Dato[0]','$Dato[1]',
									$Dato[4],"./*floor*/($Dato[5]).",$VrT,'$Dato[16]','$Dato[17]','$Dato[18]','$Dato[19]','$Dato[0]','$Dato[22]',$NoLiquidacion,$Dato[21])";												
										}
									}
									else
									{
										if($Dato[21]!=1){$Dato[21]="0";}//echo "F_";													
										$cons2="insert into facturacion.detalleliquidacion 
									(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,cum,rip,noliquidacion,nofacturable) 
									values 
									('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Dato[2]','Medicamentos','$Dato[0]','$Dato[1]',
									"./*floor*/($Dato[4]).","./*floor*/($Dato[5]).",$VrT,'$Dato[16]','$Dato[17]','$Dato[18]','$Dato[19]','$Dato[0]','$Dato[22]',$NoLiquidacion,$Dato[21])";											
									}
								}		
								if($cons2){
									$res2=ExQuery($cons2);
									$cons2="";$res2="";
								}
								//echo "<br>$cons2";	
							}		
					
						}
						//echo $MedNoPos[$IdTmp][1]." ".$MedNoPos[$IdTmp][21]."<br>";
						//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,generico,presentacion,forma,almacenppal,noentregado,NoFacturable									
					}
				}
			}?>
			</table><?		
		}
		if($MedNoPos)
		{		
			foreach($MedNoPos as $MNP)
			{				
				//FacturaIndv($MNP);
				//echo "$MNP[0] $MNP[1]<br>";
				echo "<font color='#FF0000'>Liquidacion de Medicamento No POS separada</font>";?>				
                
                <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center" >	
                    <tr>
                        <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">NoServicio</td><td><? echo $NumServ?></td>
                        <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Identificacion</td><td><? echo $Cedula?></td>
                        <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td><td><? echo $NomPac?></td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td><td><? echo $AmbitoReal?></td>
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
                    <td>Codigo/CUM</td>
                    <td>Descripcion</td>
                    <td>Cantidad</td>
                    <td>Vr Unidad</td>
                    <td>Vr Total</td>    
                </tr>
		<?		
				$Subtotal=0;
						
				$cons5="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
				$res5=ExQuery($cons5);  
				while($fila5=ExFetch($res5)){
					$Sub=0;
					$ban3='';
					$DatosAux=NULL;
					$BanDatos=0;
	//en el array CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,NoInterpretado,NoFacturable,FormaRealizacion	

					if($MNP[2]==$fila5[1]){
						$BanDatos=1;
						if($DatosAux[$MNP[0]][0]==$MNP[0]){//echo "$Dato[0] incrementa $Dato[4] <br>";
							$DatosAux[$MNP[0]][2]=$DatosAux[$MNP[0]][2]+$MNP[4];
						}
						else{
							
							//echo $Tmp[$IdTmp][1]." ".$Tmp[$IdTmp][21]."<br>";
							if($MNP[3]!="Medicamentos"){
								$DatosAux[$Dato[0]]=array($MNP[0],$MNP[1],$MNP[4],$MNP[5],$MNP[16],$MNP[17]);
								//CodCUP,Nombre,Cantidad,Valor,NoInterpretado,NoFacturable
							}
							else{
								if($DatosAux[$MNP[0]]){
									$DatosAux[$MNP[0]][2]++;
								}
								else{
									$DatosAux[$MNP[0]]=array($MNP[0],$MNP[1],$MNP[4],$MNP[5],$MNP[16],$MNP[21],$MNP[17],$MNP[18]);									
								}
							}
						}
					}
				
					if($BanDatos==1){
						foreach($DatosAux as $Dato2){
							if($Dato2[5]!=1){ 
								$Dato2[2]=round($Dato2[2]);?>
								<tr> <?		
								//Rescric[grupo] = mostrar,montofijo,cobrar								
								if($RestricCobro){
									if($Rescric)
									{									
										if($Rescric[$fila5[1]][0]=="Si")
										{
											if($Dato2[6]||$Dato2[7]){																			  //$Dato2[8]																												
											   echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7] -ATC ".$ATC[$Dato2[0]]."-CUM ".$CUM[$Dato2[0]]."</td>";
									
											}
											else{
												echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
											}
											$ban3=1;
										
											if($Rescric[$fila5[1]][1]&&$Rescric[$fila5[1]][1]!="0")
											{
												
												echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format("0",2)."</td>
												<td align='right'>".number_format("0",2)."</td></tr>";
												$ban3=1;
												$Sub=$Rescric[$fila5[1]][1]; 
												$banCobGrup=$Rescric[$fila5[1]][1];
												//$Subtotal=$Subtotal+$Rescric[$fila5[1]][1];
											}
											else
											{ 
												if($Rescric[$fila5[1]][2]=="Si")
												{
													$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
													echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format($Dato2[3],2)."</td>
													<td align='right'>".number_format($Vrtot,2)."</td></tr>";
													$Sub=$Sub+$Vrtot; 
													$Subtotal=$Subtotal+$Vrtot;
													$ban3=1;
												}
												else
												{
													echo "<td align='center'>$Dato2[2]</td><td align='right'>".number_format($Dato2[3],2)."</td>
													<td align='right'>".number_format("0",2)."</td></tr>";
													$ban3=1;
												}
											}
										}
									}
									else
									{
										$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
										if($Dato2[6]||$Dato2[7]){	
											echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7]</td>";
										}
										else{
											echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
										}
										echo "<td align='center'>$Dato2[2]</td>
										<td align='right'>".number_format($Dato2[3],2)."</td>
										<td align='right'>".number_format($Vrtot,2)."</td></tr>";
										$Sub=$Sub+$Vrtot; 
										$Subtotal=$Subtotal+$Vrtot;
										//echo "$Subtotal<br>";
										$ban3=1;
									}
								}			 		
								else
								{	
									if($Dato2[5]=="0"){
										$Vrtot=(floor($Dato2[2])*floor($Dato2[3]));
										//echo "$Dato2[1] $Dato2[7] $Dato2[8] $Dato2[9]<br>";
										$Vrtot=$Dato2[2]*$Dato2[3];
										if($Dato2[6]||$Dato2[7]){	
											echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1] $Dato2[6] $Dato2[7]</td>";
										}
										else{
											echo "<tr><td align='center'>$Dato2[0]</td><td align='center'>$Dato2[1]</td>";
										}
										echo "<td align='center'>$Dato2[2]</td>
										<td align='right'>".number_format($Dato2[3],2)."</td>
										<td align='right'>".number_format($Vrtot,2)."</td></tr>";
										$Sub=$Sub+$Vrtot; 
										$Subtotal=$Subtotal+$Vrtot;
										//echo "$Subtotal<br>";
										$ban3=1;
									}
								}
							}
						}
						if($ban3){
							if($RestricCobro){
								if($Rescric[$fila5[1]][0])
								{	if($banCobGrup){$Subtotal=$Subtotal+$banCobGrup; $banCobGrup="";}?>
									<tr bgcolor="#ECECEC">
										<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>
									</tr>				
							<?	}
							}
							else
							{?>
								<tr bgcolor="#ECECEC">
									<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>                
								</tr>				
						<?	}
						}
					}
				}			
				if($Subtotal!=''){?>
					<tr>
						<td colspan="4" align="right"><strong>SubTotal General:</strong></td>
						<td align="right"><? echo number_format(round($Subtotal),2)?></td>                    
					</tr>
			<?	}		
				
				$Total=$Subtotal; 
				$consul="select copago from contratacionsalud.contratos where entidad='$Entidad' and contrato='$Contrato' and numero='$NoContrato'";
				$result=ExQuery($consul);
				$row=ExFetch($result);
				$SiCopago=$row[0];
				if($SiCopago=='1'){
					$consul="select tipoasegurador from central.terceros where identificacion='$Entidad' and compania='$Compania[0]' and Tipo='Asegurador'";
					//echo $consul."<br>\n";
					$result=ExQuery($consul);
					$row=ExFetch($result);		
					
					$consul2="select valor,clase,tipocopago,topeanual from salud.topescopago 
					where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' and nivelusu='$Nivelusu' and ambito='$AmbitoReal'";		
					//echo "$consul2<br>\n";
					$result2=ExQuery($consul2); $fil=ExFetch($result2);
					$Tipocopago=$fil[2];
					$ClaseCopago=$fil[1];
					if($fil[1]=='Fijo'){
						$Valorcopago=$fil[0]; $Porsentajecopago="0";			
					}
					else{
						$Valorcopago=($fil[0]/100)*$Total; 
						$consul3="select sum(valorcopago) from facturacion.liquidacion where cedula='$Cedula' and compania='$Compania[0]' 
						and nofactura is not null and porsentajecopago is not null and porsentajecopago!=0 and estado='AC' and fechacrea>='$ND[year]-01-01 00:00:00' 
						and fechacrea<='$ND[year]-12-31 23:59:59' group by cedula";
						$result3=ExQuery($consul3);
						//echo "$consul3 <br>";
						$fil3=ExFetch($result3); $CopAcumulado=$fil3[0]; $Tope=$fil[3];
						//echo "Copago='$Valorcopago' Copago Acumulado='$CopAcumulado' Tope='$Tope'";
						if(!$CopAcumulado){
							if($Tope<$Valorcopago){
								$Valorcopago=$Tope;
								$BanRecal=1;
							}
							else{$BanRecal=0;}
						}
						else{
							if(($Valorcopago+$CopAcumulado)>$Tope){
								$Valorcopago=$Tope-$CopAcumulado;
								$BanRecal=1;
							}
							else{$BanRecal=0;}
						}
						$Porsentajecopago=$fil[0];	
					}				
					$Total=$Total-$Valorcopago;
				}
				if($Total>0){?>
					<tr>
						<td colspan="2" align="right"><strong>Porcentaje Copago: </strong></td><td align="center"><? echo $Porsentajecopago?>%</td>
						<td align="right"><strong>Vr Copago</strong></td><td align="right"><? echo number_format(round($Valorcopago),2)?></td>            
					</tr>
					<tr>
						<td colspan="4" align="right"><strong>Total:</strong></td>
						<td align="right"><? echo number_format(round($Total),2)?></td>                    
					</tr>               
			<?		$TotalALiq=$TotalALiq+$Total;
				}
			
				if($Total>$SaldoContra){?>
					<tr>
						<td colspan="5"><font style="color:#FF0000;font-weight:bold">Paciente Omitido!! El contrato no tiene saldo</font>
						</td>               	
					</tr>
			<?		
				}
				elseif($Rep){
					//echo "<tr><td>$Cedula</td></tr>"; ?>
					<tr>
						<td colspan="5"><font style="color:#FF0000;font-weight:bold">Paciente Omitido!! El periodo selecionado o parte de este ya ha sido Liquidado</font>
							<br><input type="button" value="Liquidacion No. <? echo $NoLiq?>" 
								onclick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiquidacion=<? echo $NoLiq?>&Ced=<? echo $Cedula?>','','width=800,height=600,scrollbars=YES')">
						</td>               	
					</tr>
		<?		}
				else{
					if($Simula!=1){
						$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
						$res=ExQuery($cons);$fila=ExFetch($res);				
						$AutoIdLiq=$fila[0]+1;	
						
						if($BanLiq==0){$NoLiqConsecIni=$AutoIdLiq;$BanLiq=1; }
						$NoLiqConsecFin=$AutoIdLiq;
						
						if($Fechae!=''){$FechaEgreso1=",fechafin"; $FechaEgreso2=",'$Fechae'";}
						if($Entidad!=''){$Pagador1=",pagador,contrato,nocontrato";$Pagador2=",'$Entidad','$Contrato','$NoContrato'";}				
						if($Valorcopago!=''){
							if($Porsentajecopago==''){$Porsentajecopago="0";}
							$Copago1=",valorcopago,porsentajecopago,tipocopago,clasecopago";$Copago2=",$Valorcopago,$Porsentajecopago,'$Tipocopago','$ClaseCopago'";}
						if($Porsentajedesc==''){$Porsentajedesc="0";}
						if($Total!=''){$TyST1=",subtotal,total";$TyST2=",'$Subtotal','$Total'";}
						
						$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,
						noliquidacion,numservicio,cedula,fechafin $Pagador1  $Copago1  $TyST1) values ('$Compania[0]','$usuario[1]',
						'$FechaFin $ND[hours]:$ND[minutes]:$ND[seconds]','$AmbitoReal','$Medicotte','$FechaIni','$Nocarnet','$Tipousu', 
						'$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3',$AutoIdLiq,$NumServ,'$Cedula','$FechaFinEstancia'  $Pagador2 $Copago2  $TyST2)";				
						//echo " $cons<br>\n";
						$res=ExQuery($cons); 
						$NoLiquidacion=$AutoIdLiq;		
						$Dato=NULL;
								
						//Rescric[grupo] = mostrar,montofijo,cobrar													
						if($MNP[0]){
							//echo "$Dato[0]<br>";																
							$VrT=$MNP[4]*$Dato[5];							
							if($MNP[3]!="Medicamentos"){		
								$MNP[4]=round($Dato[4]);
		//en el array 0-CodCup,1-NomCup,2-Grupo,3-Tipo,4-Cantidad,5-Valor,6-Ambito,7-fechainterp,8-finalidad,9-dx,10-tipodx,11-dxrel1,12-dxrel2,13-dexrel3,14-dxrel4
		//,15-CausaExterna,16-NoInterpretado,17-NoFacturable,18-FormaRealizacion	
								if($MNP[17]!=1){$MNP[17]="0";}
								if(!$MNP[18]){$MNP[18]="0";}
								if($MNP[7]){
									$FecInterp1=',fechainterpret';$FecInterp2=",'$Dato[7]'";
								}
								if($RestricCobro)
								{	
									if($Rescric)
									{		
										if($Rescric[$MNP[2]])
										{
											if($Rescric[$MNP[2]][0]=="Si")
											{
												$NoFacturab="0";}else{$NoFacturab="1";
											}
											
											if($Rescric[$MNP[2]][1]&&$Rescric[$MNP[2]][1]!="0")
											{
												if(!$BanRestric[$Dato[2]]){
													$MNP[5]=$Rescric[$MNP[2]][1];
													$VrT=$Rescric[$MNP[2]][1];
													$BanRestric[$MNP[2]]=1;
												}
											}
											else
											{
												if($Rescric[$MNP[2]][2]=="No")
												{
													$VrT="0";
												}													
											}//echo "G_";
											$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
									,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,nofacturable $FecInterp1) 
									values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','$MNP[3]',
									'$MNP[0]','$MNP[1]',$MNP[4],$MNP[5],$VrT,$NoLiquidacion,'$MNP[8]','$MNP[15]','$MNP[9]','$MNP[11]','$MNP[12]','$MNP[13]'
									,'$MNP[14]','$MNP[10]',$MNP[6],'$MNP[18]',$NoFacturab $FecInterp2)";										
										}
									}
									else
									{//echo "H_";
										$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
								,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,nofacturable $FecInterp1) 
								values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','$MNP[3]',
								'$MNP[0]','$MNP[1]',$MNP[4],$MNP[5],$VrT,$NoLiquidacion,'$MNP[8]','$MNP[15]','$MNP[9]','$MNP[11]','$MNP[12]','$MNP[13]'
								,'$MNP[14]','$MNP[10]',$MNP[6],'$MNP[18]',$MNP[17] $FecInterp2)";									
									}
								}
								else
								{//echo "I_";
									$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
								,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion,nofacturable $FecInterp1) 
								values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','$MNP[3]',
								'$MNP[0]','$MNP[1]',$MNP[4],$MNP[5],$VrT,$NoLiquidacion,'$MNP[8]','$MNP[15]','$MNP[9]','$MNP[11]','$MNP[12]','$MNP[13]'
								,'$MNP[14]','$MNP[10]',$MNP[6],'$MNP[18]',$MNP[17] $FecInterp2)";									
								}
							}
							else{
	//Tmp= 0-CodCup,1-NomCup,2-Grupo,3-Tipo,4-Cantidad,5-Valor,6-Ambito,7-fechainterp,8-finalidad,9-dx,10-tipodx,11-dxrel1,12-dxrel2,13-dexrel3,14-dxrel4,15-CausaExterna,16-generico
	//,17-presentacion,18-forma,19-almacenppal,20-noentregado,21-NoFacturable	
								if($RestricCobro)
								{	
									if($Rescric)
									{
										if($Rescric[$MNP[2]])
										{
											if($Rescric[$MNP[2]][0]=="Si")
											{$NoFacturab="0";}else{$NoFacturab="1";}
											if($Rescric[$MNP[2]][1]&&$Rescric[$MNP[2]][1]!="0")
											{													
												if(!$BanRestric[$MNP[2]]){
													$MNP[5]=$Rescric[$MNP[2]][1];
													$VrT=$Rescric[$MNP[2]][1];
													$BanRestric[$MNP[2]]=1;
												}
											}
											else
											{
												if($Rescric[$MNP[2]][2]=="No")
												{
													$VrT="0";
												}													
											}
										}
										$cont++;//echo "J_";
										$cons2="insert into facturacion.detalleliquidacion 									(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) 
								values 
								('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','Medicamentos','$MNP[0]','$MNP[1]',
								$MNP[4],$MNP[5],$VrT,'$MNP[16]','$MNP[17]','$MNP[18]','$MNP[19]',$NoLiquidacion,$NoFacturab)";
										
									}
									else
									{
										if($MNP[21]!=1){$MNP[21]="0";}//echo "K_";													
										$cons2="insert into facturacion.detalleliquidacion 									(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) 
								values 
								('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','Medicamentos','$MNP[0]','$MNP[1]',
								$MNP[4],$MNP[5],$VrT,'$MNP[16]','$MNP[17]','$MNP[18]','$MNP[19]',$NoLiquidacion,$MNP[21])";												
									}
								}
								else
								{
									
									$VrT=round(($MNP[5]*$MNP[4]),0);
									//echo "$MNP[5] $MNP[6]";
									if($MNP[21]!=1){$MNP[21]="0";}//echo "L_";													
									$cons2="insert into facturacion.detalleliquidacion 
								(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) 
								values 
								('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$MNP[2]','Medicamentos','$MNP[0]','$MNP[1]',
								$MNP[4],$MNP[5],$VrT,'$MNP[16]','$MNP[17]','$MNP[18]','$MNP[19]',$NoLiquidacion,$MNP[21])";											
								}
							}		
							if($cons2){
								//echo "<br>$cons2";	
								$res2=ExQuery($cons2);
								$cons2="";$res2="";
							}
							
						}		
				
					
						//echo $MedNoPos[$IdTmp][1]." ".$MedNoPos[$IdTmp][21]."<br>";
						//en el array  CodCup,NomCup,Grupo,Tipo,Cantidad,Valor,Ambito,fechainterp,finalidad,dx,tipodx,dxrel1,dxrel2,dexrel3,dxrel4,CausaExterna,generico,presentacion,forma,almacenppal,noentregado,NoFacturable									
					}
				}?>
				</table><?	
			}
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
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ 
	order by primape,segape,primnom,segnom";
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
                    		
    <?	*/		
	            if($Entidad){$Ent=" and entidad='$Entidad'";}  
				   if($Contrato){
				      if($Entidad=="PARTICULARES")$Contra=" and contrato='".trim(preg_replace('/[^a-zA-Z0-9\s]/',utf8_encode("Ñ"),str_replace("\'","",str_replace("Ã",utf8_encode(""),utf8_encode($Contrato)))))."'";
				      else $Contra=" and contrato='$Contrato'";}
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
					
					$filaPMes="";
					$conPMeds = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos where Numero='$fila2[4]' and Entidad='$fila2[2]' and contrato='$fila2[3]' 
					and Compania='$Compania[0]'";
					$resPMeds=ExQuery($conPMeds); 
					$filaPMes=ExFetch($resPMeds);
					
										
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
										LiqCupsoMeds($FechaIni,$fila2[1],$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$fila2[1],$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);
										//echo "<br>\n $fila[0]: $FechaIni -> $fila2[1] de $fila[4]";	
										//BuscarMoC($FechaIini,$fila2[1],);									
								}
								else{									
									//Buscar meds o cups desde FechaIni hasta $FechaFin
									LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);
									//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
								}						
							}
						}
						else{	//echo "$FechaIni hasta $FFAux";						
							//Buscar meds o cups desde FechaIni hasta FechaFin	
//echo $FechaIni."<br>".$FFAux."<br>".$fila[4]."<br>".$fila[0]."<br>".$filaPlan[0]."<br>".$filaPlan[1]."<br>".$FechaFin."<br>".$fila2[2]."<br>".$NomEnt."<br>".$NomPaciente."<br>".$fila2[3]."<br>".$fila2[4]."<br>".$Ambt."<br>".$filaPMes[1]."<br>".$filaUnd[2]."<br>".$filaUnd[3]."<br>".$Simula."<br>".$fila[9]."<br>".$fila[10]."<br>".$fila[11]."<br>".$fila[12]."<br>".$fila[13]."<br>".$fila[14]."<br>".$fila[15]."<br>".$filaPMes[0];							
							LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);
							//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
						}
					}
					else{ //F1B>FI						
						if($F1B<=$FF){
							//Buscar desde $fila[0](no se sabe hasta donde)
							if($fila2[1]!=''){
								if($F2B<=$FF){//echo "entra";
										//Buscar meds o cups desde FechaIni hasta fila2[1]	
										LiqCupsoMeds($fila2[0],$fila2[1],$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$fila2[1],$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);
										//echo "<br>\n $fila[0]: $fila2[0] -> $fila2[1] de $fila[4]";									
								}
								else{
									//Buscar meds o cups desde FechaIni hasta $FechaFin			
									LiqCupsoMeds($FechaIni,$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);				
									//echo "<br>\n $fila[0]: $FechaIni -> $FechaFin de $fila[4]";
								}
							}
							else{
								//echo " ACA ES  ";
								//Buscar desde $fila[0] hasta $FechaFin
								LiqCupsoMeds($fila2[0],$FFAux,$fila[4],$fila[0],$filaPlan[0],$filaPlan[1],$FechaFin,$fila2[2],$NomEnt,$NomPaciente,$fila2[3],$fila2[4],$Ambt,$filaPMes[1],$filaUnd[2],$filaUnd[3],$Simula,$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$filaPMes[0]);
								//echo "<br>\n $fila[0]: $fila2[0]->$FechaFin de $fila[4]";
								//LiqCupsoMeds($FechaIni,$FechaFin);
							}
						}
					}
				}
			}
			$fila[3]==''; $fil2='';			
		}?>
         <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
                <tr align="center">
                    <td style="font-weight:bold" colspan="10">Total a Liquidar=<? echo number_format(round($TotalALiq),2)?></td>
                </tr>	
         </table>
<?		if($ban2==0){?>
            <table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
                <tr align="center">
                    <td style="font-weight:bold" colspan="10">Noy elementos para ser liquidados durantes este periodo</td>
                </tr>	
            </table>
	<?	}     
		else{
			if($NoLiqConsecIni){?>
				<script language="javascript">
					open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiqConsecIni=<? echo $NoLiqConsecIni?>&NoLiqConsecFin=<? echo $NoLiqConsecFin?>&Company=<? echo $Compania[0]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','width=800,height=600,scrollbars=YES');
				</script>
		<?	}
		}
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	@require_once ("xajax/xajax_core/xajax.inc.php");  
		
	$ND = getdate();			
	global $ban1;
	global $ban2;
	global $ban3;
	global $today;	
	$ban1=0;
	$ban2=0;
	
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	
	$cons="select consumcontra,monto from ContratacionSalud.Contratos where compania='$Compania[0]' and entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); $ConsumoContra=$fila[0]; $MontoContra=$fila[1];
	
	$consFac="select sum(total),entidad,contrato,nocontrato from facturacion.facturascredito 
	where compania='$Compania[0]' and entidad='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont' and estado='AC' group by entidad,contrato,nocontrato";						
	$resFac=ExQuery($consFac);
	$filaFac=ExFetch($resFac); $EjecucionContra=$filaFac[0];	
		
	$consLiq="select sum(total) from facturacion.liquidacion 
	where compania='$Compania[0]' and pagador='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont' and estado='AC' and nofactura is null";	
	$resLiq=ExQuery($consLiq);
	$filaLiq=ExFetch($resLiq); $xFacturarContra=$filaLiq[0];
	
	$SaldoContra=$MontoContra-$ConsumoContra-$EjecucionContra-$xFacturarContra;
	
	$cons="select tiposervicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$NumServ";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$AmbitoReal=$fila[0];

	//Funcion para limpiar la tabla de temporarles atraves de xajax
	function LimTmp($Comp,$TMP){
		$respuesta = new xajaxResponse(); 	
		//$respuesta->addScript("alert('Respondo!');");
		$cons="delete from facturacion.tmpcupsomeds where compania='$Comp' and tmpcod='$TMP' and cedula='$Paciente[1]'";		
		$res=ExQuery($cons);						
	   	//tenemos que devolver la instanciación del objeto xajaxResponse 
   		return $respuesta->getXML(); 		
	}
	$obj = new xajax(); 
	$obj->registerFunction("LimTmp"); 
	$obj->processRequest(); 
	
	function diferenciaDias($inicio, $fin){    
		$inicio = strtotime($inicio);    
		$fin = strtotime($fin);    
		$dif = $fin - $inicio;    
		$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);    
		return ceil($diasFalt);
	}
	
?>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</iframe>
<?	
	
	//---------------------------------------------------Agreagar un nuevo CUP o Medicamento------------------------------------------------------------
	if($TipoNuevo){	//echo "entra";?>
		<script language="javascript">							
			frames.FrameOpener.location.href="NewLiquidacion.php?NumServ=<? echo $NumServ?>&DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD2?>&TipoNuevo=<? echo $TipoNuevo?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNocont=<? echo $PagaNocont?>&FecIniLiq=<? echo $FecIniLiq?>&FecFinLiq2=<? echo $FecFinLiq2?>";
			document.getElementById('FrameOpener').style.position='absolute';
			document.getElementById('FrameOpener').style.top=30;
			document.getElementById('FrameOpener').style.left=10;
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='100%';
			document.getElementById('FrameOpener').style.height='500px';						
        </script><? 		
	}
	//--------------------------------------------------Para Nuevas Liquidaciones se trabaja con el numero del servicio activo
	if($NumServ==''){			
		$cons="Select tiposervicio,estado,medicotte,fechaing,fechaegr,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,Numservicio
		from Salud.servicios where Cedula='$Paciente[1]' and Compania='$Compania[0]' and estado='AC'";								
		//echo $cons;
		$res=ExQuery($cons);
		$row=ExFetch($res); echo ExError();
		
		$Ambito=$row[0];			
		$Estado=$row[1];
		$Medicotte=$row[2];	
		$Fechaing=$row[3];	
		$Fechae=$row[4];	
		$Nocarnet=$row[5];
		$Tipousu=$row[6];		
		$Nivelusu=$row[7];	
		$Autorizac1=$row[8];
		$Autorizac2=$row[9];
		$Autorizac3=$row[10];
		$NumServ=$row[11];
		$TipoServcio=$row[0];
						
		$Edit=0;										
		$ban3=1;			
	}
	else
	{
		if($NoLiquidacion&&!$Paga){
			$cons="Select tiposervicio,estado,medicotte,fechaing,fechaegr,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,Numservicio
			from Salud.servicios where Cedula='$Paciente[1]' and Compania='$Compania[0]' and estado='AC' and numservicio=$NumServ";		
			//echo $cons;	
			$res=ExQuery($cons);
			$row=ExFetch($res); echo ExError();
			
			$Ambito=$row[0];			
			$Estado=$row[1];
			$Medicotte=$row[2];	
			$Fechaing=$row[3];	
			$Fechae=$row[4];	
			$Nocarnet=$row[5];
			$Tipousu=$row[6];		
			$Nivelusu=$row[7];	
			$Autorizac1=$row[8];
			$Autorizac2=$row[9];
			$Autorizac3=$row[10];
			//$NumServ=$row[11];
			$TipoServcio=$row[0];
						
			$Edit=0;										
			$ban3=1;
			
			$cons="select pagador,contrato,nocontrato,valordescuento,porsentajedesc,fechaini,fechafin from facturacion.liquidacion 
			where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion=$NoLiquidacion";
			//echo $cons;
			$res=ExQuery($cons);
			$row=ExFetch($res);
			$Paga=$row[0]; $PagaCont=$row[1]; $PagaNocont=$row[2];$Valordescuento=$row[3];$Porsentajedesc=$row[4];$FecIniLiq=$row[5];$FecFinLiq2=$row[6];
			
			$cons="select grupo,tipo,codigo,nombre,sum(cantidad),vrunidad,vrtotal,generico,presentacion,forma,almacenppal,fechacrea,cum,rip from facturacion.detalleliquidacion where compania='$Compania[0]'
			and noliquidacion=$NoLiquidacion group by grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,fechacrea,cum,rip ";
			$res=ExQuery($cons);
			//echo $cons;
			if(ExNumRows($res)>0){
				$TMPCOD2=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
				while($fila=ExFetch($res)){
					$codigo_item=$fila[2];
					if($fila[1]=="Medicamentos"){
						$conscod="select autoid from consumo.cumsxproducto where cum='$fila[12]'";
						$rescod=ExQuery($conscod);
						$filacod=ExFetch($rescod);
						$codigo_item=$filacod[0];
					}
				
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];}
					$consns="select tiposervicio from salud.servicios where numservicio='$NumServ'";
					$resns=ExQuery($consns);
					$filans=ExFetch($resns);
					
				 // $cons="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,fecha,noentregado,labnointerp,nofacturable,ambito,cum,atc) values('$Compania[0]','$TMPCOD','$Paciente[1]','$filaGrupo2[0]','$filaGrupo2[1]','$Codigo','$Nombre','$Cantidad','$VrUnidad','$VrTotal','$Nombre','$filaGrupo1[1]','$filaGrupo1[2]','FARMACIA','$Fecha 00:00:00','1','0','0','$newAmbito','$filaCUM2[0]','$filaGrupo1[4]') ";
					
					$cons2="insert into facturacion.tmpcupsomeds (compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenPpal,fecha,noentregado,labnointerp,nofacturable,ambito,cum,atc) values ('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila[0]','$fila[1]','$codigo_item','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[9]','$fila[10]','$fila[11]','1','0','0','$filans[0]','$fila[12]','$fila[13]')";										
					//echo $cons2;
					$res2=ExQuery($cons2); 
				}
			}
			//echo $Paga;							
		}
		else{
			if(!$Paga&&$ban3!=1){
				$cons="Select tiposervicio,estado,medicotte,fechaing,fechaegr,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,Numservicio
				from Salud.servicios where Cedula='$Paciente[1]' and Compania='$Compania[0]' and numservicio=$NumServ";								
				//echo $cons;
				$res=ExQuery($cons);
				$row=ExFetch($res); echo ExError();
		
				$Ambito=$row[0];			
				$Estado=$row[1];
				$Medicotte=$row[2];	
				$Fechaing=$row[3];	
				$Fechae=$row[4];	
				$Nocarnet=$row[5];
				$Tipousu=$row[6];		
				$Nivelusu=$row[7];	
				$Autorizac1=$row[8];
				$Autorizac2=$row[9];
				$Autorizac3=$row[10];
				//$NumServ=$row[11];
				$TipoServcio=$row[0];		
				$Edit=0;				
				$ban3=1;										
			}
		}
	}	
	
	//-------------------------En caso de no digitarse la fecha de inicio del servicio se coloca automaticamente la fecha actual-----
	if(!$Fechaing){		
		$Fechaing = date("Y-m-d");      		
	}
	$Fechaing=substr($Fechaing,0,10);
	$Fechae=substr($Fechae,0,10);
		
	//---------------------------------------------Activa Pagador x Servicios--------------------------------------------------------------------------
	if($VerPagador=='1')
	{					
		if(!$TMPCOD)
		{
			$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			
			if(!$NumServ){
		/*	$cons="select entidad,pagadorxservicios.fechaini,pagadorxservicios.fechafin,pagadorxservicios.contrato,pagadorxservicios.nocontrato
			from salud.pagadorxservicios,central.terceros,salud.servicios where pagadorxservicios.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'  
			and pagadorxservicios.entidad=terceros.identificacion and terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' order by fechaini";		*/
				$cons="select * from salud.servicios where estado='rrr'";
			}
			else{
				$cons="select entidad,pagadorxservicios.fechaini,pagadorxservicios.fechafin,pagadorxservicios.contrato,pagadorxservicios.nocontrato
				from salud.pagadorxservicios,central.terceros,salud.servicios where pagadorxservicios.numservicio=servicios.numservicio and servicios.compania='$Compania[0]' 
				and pagadorxservicios.entidad=terceros.identificacion and terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and servicios.numservicio=$NumServ
				order by fechaini ";			
			}
	
			$res=ExQuery($cons);
			//echo $cons;
			if(ExNumRows($res)>0){
				while($fila=ExFetch($res)){
					if($fila[2]!=''){
						$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,fechafin,contrato,nocontrato) values
						('$Compania[0]','$TMPCOD','$Paciente[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]')";
						$res2=ExQuery($cons2);
					}
					else{
						$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,contrato,nocontrato) values
						('$Compania[0]','$TMPCOD','$Paciente[1]','$fila[0]','$fila[1]','$fila[3]','$fila[4]')";
						$res2=ExQuery($cons2);
					}
				}
			}
		}
	}	
	else{	
		//----------------------------------------Desactiva Pagador x Servicios------------------------------------------------------------------------
		$cons="delete from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);	
		$TMPCOD='';	
	}
		
	//----------------------------------------Agrega un nuevo pagador x servicios-----------------------------------------------------------------------
	if($Insertar)
	{			
		if(!$Hasta){
			$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,contrato,nocontrato) values
			('$Compania[0]','$TMPCOD','$Paciente[1]','$Entidad','$Desde','$Contrato','$Nocontrato')";
		}
		else{
			$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,fechafin,contrato,nocontrato) values
			('$Compania[0]','$TMPCOD','$Paciente[1]','$Entidad','$Desde','$Hasta','$Contrato','$Nocontrato')";
		}
		$res2=ExQuery($cons2);		
	}
	
	//----------------------------------------Cargar Cups o Meds de HC----------------------------------------------------------------------------------
	if($CargarHC&&$BanCargarHC!=1){
		if(!$TMPCOD2){$TMPCOD2=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
		//echo $TMPCOD2;	
		//Encontramos el ambito y queda guardado en la variable $Ambito
		
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;		
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);/*
		if($filaAmb[0]==1||$filaAmb[2]==1||$filaAmb[3]==1){
			$Ambito="1";
		}
		if($filaAmb[1]=="1"){
			$Ambito="2";
		}
		if($filaAmb[4]=="1"){
			$Ambito="3";
		}*/

		//CUPS-----------------------	
		$BanCargarHC=1;			
		//DE HISTORIA CLINICA
		
		//Encontramos el plan de beneficios y el plan tarifiario a partir del contrato		
		$cons2="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont' and compania='$Compania[0]'";			
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);		
		
		//Encontramos todos los formatos
		$cons="select tblformat,formato,tipoformato,laboratorio from historiaclinica.formatos where estado='AC' and compania='$Compania[0]'";
		$res=ExQuery($cons);		
		if(ExNumRows($res)>0){
			while($fila=ExFetch($res)){	
				//Encontramos los cups		
				$cons6="select cup,fecha,dx1,dx2,dx3,dx4,dx5,tipodx,finalidadconsult,causaexterna,hora,$fila[0].id_historia,formarealizacion  
				from histoclinicafrms.$fila[0],histoclinicafrms.cupsxfrms
				where $fila[0].compania='$Compania[0]' and  $fila[0].fecha>='$FecIniLiq' and $fila[0].fecha<='$FecFinLiq2' and $fila[0].cedula='$Paciente[1]'
				and $fila[0].numservicio=$NumServ and $fila[0].noliquidacion=0 and cupsxfrms.compania='$Compania[0]' and cupsxfrms.id_historia=$fila[0].id_historia
				and $fila[0].formato='$fila[1]' and $fila[0].tipoformato='$fila[2]' and $fila[0].formato=cupsxfrms.formato and $fila[0].tipoformato=cupsxfrms.tipoformato
				and cupsxfrms.cedula='$Paciente[1]'";
				//if($fila[0]=="tbl00038"){echo "<br>$cons6<br>";}
				$res6=ExQuery($cons6);						
								
				if(ExNumRows($res6)){					
					while($fila6=ExFetch($res6)){							
						//Encotramos el grupo, el tipo y el valor del segun los planes de servicios y los planes tarifafios
						$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila6[0]' and autoid=$fila2[1]";												
						$resVr=ExQuery($consVr);
						$filaVr=ExFetch($resVr);
						//echo $consVr."<br>";
						$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
						where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila6[0]' and cups.compania='$Compania[0]' and cups.codigo=cup and autoid=$fila2[0]";
						$res3=ExQuery($cons3); 
						$fila3=ExFetch($res3);						
						//echo $cons3."<br>";
						
						if($fila3[3]==1)//Si es facturble
						{
							$Facturable="";$Facturable1="";$Facturable2="";
						}
						else
						{ 
							$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $filaVr[0]="0";
						}
						if($fila3[0]==''){$filaVr[0]="0";}													
						$vT=$filaVr[0];
						if($vT==''){$vT="0";}
						if($filaVr[0]==''){$filaVr[0]="0";}
						if($fila3[1]==''){$fila3[1]="012";}
						if($fila[3]){//Si es de tipo laboratorio se verifica si ha sido interpretado o no
							$consADx="select interpretacion from histoclinicafrms.ayudaxformatos where compania='$Compania[0]' and formato='$fila[1]' and tipoformato='$fila[2]'
							and cedula='$Paciente[1]' and numservicio=$NumServ and id_historia=$fila6[11]";
							$resADx=ExQuery($consADx);
							if(ExNumRows($resADx)<=0){
								$IntLab=",labnointerp=1";
								$IntLab1=",labnointerp";
								$IntLab2=",1";
							}
						}
						if($fila3[0]==''){$filaVr[0]="0";}		
												 
							$cons5="select codigo,cantidad,grupo,tipo from facturacion.tmpcupsomeds 
							where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila6[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'
							and finalidad='$fila6[8]' and causaext='$fila6[9]' and dxppal='$fila6[2]' and dxrel1='$fila6[3]' and dxrel2='$fila6[4]' and dxrel3='$fila6[5]' 
							and dxrel4='$fila6[6]' and tipodxppal='$fila6[7]' and fecha='$fila6[1] 00:00:00'";
							$res5=ExQuery($cons5);
							//echo $cons5."<br>";
							if(ExNumRows($res5)>0){
								$fila5=ExFetch($res5); 
								$Cantidad=$fila5[1]+1;								
								if($filaVr[0]!="0"){$VrTot=$filaVr[0]*$Cantidad;}else{$VrTot=$fila5[4]*$Cantidad;}
								$cons4="update facturacion.tmpcupsomeds set cantidad='$Cantidad',vrtotal=$VrTot $IntLab $Facturable
								where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila6[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'
								and finalidad='$fila6[8]' and causaext='$fila6[9]' and dxppal='$fila6[2]' and dxrel1='$fila6[3]' and dxrel2='$fila6[4]' and dxrel3='$fila6[5]' 
								and dxrel4='$fila6[6]' and tipodxppal='$fila6[7]'";					
								//echo $cons4."<br>";
							}	
							else{								
								if($fila6[12]==""){$fila6[12]="0";}
								$cons4="insert into facturacion.tmpcupsomeds 
								(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito
								,formarealizacion $IntLab1 $Facturable1) values 
								('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila6[0]','$fila3[2]',1,$filaVr[0],$vT,'$fila6[1] $fila6[10]','$fila6[8]'
								,'$fila6[9]','$fila6[2]','$fila6[3]','$fila6[4]','$fila6[5]','$fila6[6]','$fila6[7]','$Ambito','$fila6[12]' $IntLab2 $Facturable2)"; 
								//echo $cons4."<br>";
							}										
							//revisar cantidad y si esta bn lo de pagador x servicios
							//echo "$cons4<br>";
							//si no tiene grupo lo inserta pero no lo lista
							$res4=ExQuery($cons4); 
						//}			
					}
				}
			}			
		}
		
		//Odontologia----------------------------------------------------------------------------------------------------------------------------
		$cons="select odontogramaproc.cup,fecha,diagnostico1,diagnostico2,diagnostico3,diagnostico4,diagnostico5,finalidadprocedimiento,formarealizacion  
		from odontologia.odontogramaproc,odontologia.procedimientosimgs
		where identificacion='$Paciente[1]' and fecha>='$FecIniLiq' and fecha<='$FecFinLiq2' and numservicio=$NumServ and odontogramaproc.compania='$Compania[0]'
		and procedimientosimgs.cup=odontogramaproc.cup and procedimientosimgs.compania='$Compania[0]' and diagnostico1 IS DISTINCT FROM '' and tipoodonto='Seguimiento'";
		$res=ExQuery($cons);
		//echo $cons."<br>";
		while($fila=ExFetch($res)){
			$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila[0]' and autoid=$fila2[1]";						
			$resVr=ExQuery($consVr);
			$filaVr=ExFetch($resVr);
			//echo $consVr."<br>";
			$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
			where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila[0]' and cups.compania='$Compania[0]' and cups.codigo=cup and autoid=$fila2[0]";
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);						
			//echo $cons3."<br>";
			
			if($fila3[3]==1)
			{
				$Facturable="";$Facturable1="";$Facturable2="";
			}
			else
			{ 
				$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $filaVr[0]="0";
			}
			if($fila3[0]==''){$filaVr[0]="0";}
			$vT=$filaVr[0];
			if($vT==''){$vT="0";}
			if($filaVr[0]==''){$filaVr[0]="0";}			
			if($fila3[1]==''){$fila3[1]="00005";}
			$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
			where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'
			and finalidad='$fila[7]' and dxppal='$fila[2]' and dxrel1='$fila[3]' and dxrel2='$fila[4]' and dxrel3='$fila[5]' 
			and dxrel4='$fila[6]' and formarealizacion='$fila[8]'";
			
			$res5=ExQuery($cons5);
			
			if(ExNumRows($res5)>0){
				$fila5=ExFetch($res5); 				
				$Cantidad=$fila5[1]+1;
				if($fila3[0]=""){$filaVr[0]="0";}
				if($fila5[0]!="0"){$VrTot=$filaVr[0]*$Cantidad;}else{$VrTot=$fila5[4]*$Cantidad;}
				$cons4="update facturacion.tmpcupsomeds set cantidad='$Cantidad',vrtotal=$VrTot $Facturable
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]' 
				and fecha='$fila[1] 00:00:00' and finalidad='$fila[7]' and dxppal='$fila[2]' and dxrel1='$fila[3]' and dxrel2='$fila[4]' and dxrel3='$fila[5]' 
				and dxrel4='$fila[6]' and formarealizacion='$fila[8]'";
				//echo $cons4."<br>";
			}	
			else{															
				$cons4="insert into facturacion.tmpcupsomeds 
				(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,ambito,finalidad,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,formarealizacion
				$Facturable1) values 
				('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila[0]','$fila3[2]',1,$filaVr[0],$vT,'$fila[1] 00:00:00','$Ambito',$fila[7],'$fila[2]','$fila[3]'
				,'$fila[4]','$fila[5]','$fila[6]','$fila[8]' $Facturable2)"; 
				//echo $cons4."<br>";
			}
			$res4=ExQuery($cons4);
		}
		
		//Medicamentos---------------------------------------------------------------------------------------------------------------------------
				
	/*$cons="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.numservicio=$NumServ and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,numservicio,cum order by autoid";*/
     $cons="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";		
		$res=ExQuery($cons);
		//echo $cons;
		$fila3[0]=$Paga; $fila3[1]=$PagaCont; $fila3[2]=$PagaNocont;
		$cons1 = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos where Numero='$fila3[2]' and Entidad='$fila3[0]' and contrato='$fila3[1]' 
		and Compania='$Compania[0]'";						
		$res1 = ExQuery($cons1); 
		$fila1=ExFetch($res1);	
		
		while($fila=ExFetch($res)){													
			//echo $cons1."<br>";
			/*$cons3 = "Select Consumo.CodProductos.grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,CodProductos.autoid,consumo.movimiento.cum,consumo.movimiento.cantidad,consumo.movimiento.tipocomprobante
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto,consumo.movimiento 
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]'
			and TarifasxProducto.autoid=CodProductos.autoid
			and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$fila[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila[0]'
			group by Codigo1,NombreProd1,Consumo.CodProductos.grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,CodProductos.autoid,consumo.movimiento.cum,consumo.movimiento.cantidad,consumo.movimiento.tipocomprobante
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";*/
			
			
			/*$cons3 = "Select Consumo.CodProductos.grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,CodProductos.autoid,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto 
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]'
			and TarifasxProducto.autoid=CodProductos.autoid
			and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$fila[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila[0]'
			group by Codigo1,NombreProd1,Consumo.CodProductos.grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,CodProductos.autoid,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";*///--grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			
			$cons3 = "Select Consumo.CodProductos.grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,CodProductos.autoid,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' and TarifasxProducto.Compania='$Compania[0]' 
			and TarifasxProducto.autoid=CodProductos.autoid 
			and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$fila[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila[0]'				
			group by Codigo1,NombreProd1,Consumo.CodProductos.grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,CodProductos.autoid,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";				
			//echo $cons3."<br>";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);$cums[$fila[6]]=$fila[6];$atc[$fila[6]]=$fila3[8];//$cums[$fila3[6]]=$fila[6];$atc[$fila3[6]]=$fila3[8];

			/*$consDev="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio from consumo.movimiento,consumo.almacenesppales
			where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Devoluciones' and noliquidacion is null 
			and almacenesppales.compania='$Compania[0]'	and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 
			and estado='AC' and movimiento.fecha>='$FecIniLiq' and movimiento.fecha<='$FecFinLiq2' and numservicio=$NumServ and autoid=$fila3[6]
			group by autoid,cantidad,regmedicamento,movimiento.almacenppal,autoid,numservicio order by autoid";*/
            $consDev="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
				where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Devoluciones' and noliquidacion is null and 	almacenesppales.compania='$Compania[0]'	
				and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
				and movimiento.fechadespacho<='$FecFinLiq2' and numservicio=$NumServ and autoid=$fila[4]
				and cum='$fila[6]'
				group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum order by cum";						
			//echo $consDev."<br>";
			$resDev=ExQuery($consDev);
			$filaDev=ExFetch($resDev);
			
			if(!$filaDev[1])$Dev=0;
			   else $Dev=$filaDev[1];
					  
					  //if($fila[1]<$Dev)
					     //$Cant[$fila[6]]=($Dev-$fila[1]);
						 //else
					         $Cant[$fila[6]]=($fila[1]-$Dev);
					  
					  //echo "<br>".$fila[1]."-".$Dev;
			//$Cant[$fila3[6]]=($fila[1]-$filaDev[1]);
			
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$fila1[0]' and almacenppal='$fila[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);
			if($filaMedsxPlan[0]){				
				if($filaMedsxPlan[1]==1)
				{
					$Facturable="";$Facturable1="";$Facturable2="";
				}
				else
				{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[5]=0;
				}
				
				$fila3[0]=$GruposMeds[$fila3[0]]; 
				if($fila3[0]==""){$fila3[5]=0;}
				$vT=/*$fila[1]*/$Cant[$fila[6]]*$fila3[5];			
				if($vT==''){$vT="0";}
				if($fila[1]==$fila[2]){$noE=0;}else{$noE=1;}
				
				$cons5="select codigo,cantidad,grupo,tipo from facturacion.tmpcupsomeds 
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila3[6]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' 
				and tipo='Medicamentos' and almacenppal='$fila[3]'";
				$res5=ExQuery($cons5);
				//echo $cons5;
/*				if(ExNumRows($res5)>0){
					$fila5=ExFetch($res5); 
					$Cantidad=$fila5[1]+$fila[1];
					$cons4="update facturacion.tmpcupsomeds set cantidad='$Cantidad' $Facturable
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila3[6]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='Medicamentos'
					and almacenppal='$fila[3]' and cum='".$fila[6]."'";					
					$res4=ExQuery($cons4); 	
				}	
				else{*/	
//					$Cantidad=$fila[1]-$filaDev[1];
/*					if($Cantidad>0)
					{*/ if($Cant[$fila[6]]>0){
						$cons4="insert into facturacion.tmpcupsomeds 
						(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,noentregado,cum,atc,ambito $Facturable1) values 
						('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','Medicamentos','$fila3[6]','$fila3[2]',".$Cant[$fila[6]].",$fila3[5],$vT, 
						'$fila3[2]','$fila3[3]','$fila3[4]','$fila[3]',$noE, '".$fila[6]."','".$fila3[8]."', '$Ambito' $Facturable2)"; 
						$res4=ExQuery($cons4);} 	
/*					}
				}*/
				
				
			}		
		}
		
		//Estancia--------------------------------------------------------------------------------------------------------------------------------
		
		/*$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias,ambito from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$filaAmb[2]==1){ 
			$cons2="select planbeneficios,plantarifario,primdia,ultdia,ajustardias from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont' 
			and compania='$Compania[0]'";					
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1; }
			$AjustarDias=$fila2[4];
			
			$cons="select fechaini,fechafin from salud.pagadorxservicios where compania='$Compania[0]' and numservicio=$NumServ
			and entidad='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FecIniPag=$fila[0]; $FecFinPag=$fila[1];
			//echo $cons
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Paciente[1]' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$PagaCont' and nocontrato='$PagaNocont' and entidad='$Paga' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup order by fechai";					
			
						
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res))
			{$Cont++;
				$DiasCobro=0;
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor,cupsxplanservic.facturable
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);
				//if($fila3[0]==""){$fila3[2]="0";}
					
				$FIinicial1=explode("-",$FecIniLiq); //echo  "FecIniLiq=$FecIniLiq  ";
				$FIinicial2=explode("-",$fila[1]); //echo $fila[1]."<br>";
				
				$FI1 = mktime (0,0,0,$FIinicial1[1],$FIinicial1[2],$FIinicial1[0]); //echo "FIinicial1= $FIinicial1[0] - $FIinicial1[2] - $FIinicial1[1]  <br>\n";			
				$FI2 = mktime (0,0,0,$FIinicial2[1],$FIinicial2[2],$FIinicial2[0]); //echo "Fila[1]=$FIinicial2[0] - $FIinicial2[2] - $FIinicial2[1] <br>\n";
				
				$FFinal1=explode("-",$FecFinLiq2); 
				$FFinal2=explode("-",$fila[2]);
				
				$FF1 = mktime (0,0,0,$FFinal1[1],$FFinal1[2],$FFinal1[0]);			
				if($fila[2]){$FF2 = mktime (0,0,0,$FFinal2[1],$FFinal2[2],$FFinal2[0]);	}
				
				$FecIniEstancia="";
				$FecFinEstancia="";
				$DiasCobro="";
				//echo "FI1=$FI1 FI2=$FI2 FF1=$FF1 FF2=$FF2 <br> XXX";			
				if($FI2<=$FI1){ //Si la fecha Inicial del periodo de la estancia es menor a la fecha inicial Seleccionada					
					$FecIniEstancia=$FecIniLiq;
					if(empty($fila[2])){									
						$FecFinEstancia=$FecFinLiq2; //echo "caso 1 ";
					}					
					else{
						if($FF2>=$FF1){
							$FecFinEstancia=$FecFinLiq2;  //echo "caso 2 ";
						}
						else{
							if($FF2>=$FI1){
								$FecFinEstancia=$fila[2]; //echo "caso 3 ";
							}
							else{
								$FecIniEstancia="";
							}
						}
					}					
				}
				else{
					$FecIniEstancia=$fila[1];
					if($fila[2]==''){
						if($FI2<=$FF1){							
							$FecFinEstancia=$FecFinLiq2; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FecFinLiq2; //echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				//echo "<br>".$FecIniEstancia."|".$FecFinEstancia;
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				//$DiasCobro++;		
				//if($Num>0){$DiasCobro--;}		//Verificar con mas dias de estancia	
				//echo "$FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro<br>";
				if($fila3[3]==1)
				{
					$Facturable="";$Facturable1="";$Facturable2="";
				}
				else{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[2]="0";
				}
				if($fila3[0]==""){$fila3[2]="0";}
				$vT=$fila3[2]*$DiasCobro;
				if($fila3[2]==''){$fila3[2]="0";}
				if($fila3[1]==''){$fila3[1]="00001";}	
				
				if($FecIniEstancia==$FecFinEstancia){
					$DiasCobro=1;
				}	
				
				if($DiasCobro>0){
					if($FecIniEstancia<$FecFinEstancia){
						$DiasCobro=$DiasCobro+1;
					}
				//	echo "$DiasCobro FecIniEstancia=$FecIniEstancia FechaFinAnt=$FechaFinAnt<BR>";
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
					$cons98="Select ";
					if($DiasCobro==31 && $AjustarDias){$DiasCobro=30;}
				    if($DiasCobro>0){
					$PDiasCobro=0;
					$DiasCobro_[]=$DiasCobro;				
                    $resultado=0;
                    foreach($DiasCobro_ as $valor){
                           $resultado=$resultado+$valor;
						   if($resultado==31){$PDiasCobro=30;}
						   }
					$DiasCobro=$PDiasCobro;}				
					if($DiasCobro>0){
					//if($DiasCobro>1){$DiasCobro--;}					
					$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";			
					$res5=ExQuery($cons5); echo ExError();
					//echo $cons2;
					if(ExNumRows($res5)>0){ //echo "acutaliza";
						$fila5=ExFetch($res5); 						
						$Cantidad=$fila5[1]+$DiasCobro;
						$VrTot=$Cantidad*$fila5[4];
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito='$Ambito'
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";					
						//echo "<br>\n$cons4<br>\n";		
					}
					else{	
						$vT=$DiasCobro*$fila3[2];//echo "DiasCobro=$DiasCobro<BR>";
						$cons4="insert into facturacion.tmpcupsomeds 
						(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,finalidad,causaext,dxppal,tipodxppal,ambito $Facturable1) values 
						('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila[0]','$fila[5]',$DiasCobro,$fila3[2],$vT,'$FecFinLiq2','','','','','$Ambito' $Facturable2)"; 
						//echo "<br>\n$cons4<br>\n";		
						//si no tiene grupo lo inserta pero no lo lista
					}
					$res4=ExQuery($cons4); echo ExError();}//
				}
				$FechaIniAnt=$FecIniEstancia; $FechaFinAnt=$FecFinEstancia;
			}
			
			//En caso de no cobrar del primer dia
			if($PrimDia==0){
				$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
				
				$res3=ExQuery($cons3);
				if(ExNumRows($res3)>0){
					$fila3=ExFetch($res3);
					if($fila3[1]==1){
						$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
						and grupo='$fila5[2]' and tipo='$fila5[3]'";						
					}
					else{
						$Cantidad=$fila3[1]-1;
						$VrTot=$fila3[4]*$Cantidad;
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
					}					
					$res4=ExQuery($cons4);
				}
			}
			//En caso de no cobrar el ultimo dia
			if($NoEgr==0){
				if($UltDia==0){			
					$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
					
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0){
						$fila3=ExFetch($res3);
						if($fila3[1]==1){
							$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
							and grupo='$fila5[2]' and tipo='$fila5[3]'";						
						}
						else{
							$Cantidad=$fila3[1]-1;
							$VrTot=$fila3[4]*$Cantidad;
							$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
							where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
						}					
						$res4=ExQuery($cons4);
					}
				}				
			}
		}*/







//Estancia				
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$filaAmb[2]==1){				
			$cons2="select planbeneficios,plantarifario,primdia,ultdia,ajustardias from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont' and compania='$Compania[0]'";	
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1;}
			$AjustarDias=$fila2[4];
			
			$cons="select fechaini,fechafin from salud.pagadorxservicios where compania='$Compania[0]' and numservicio=$NumServ
			and entidad='$Paga'and contrato='".trim(preg_replace('/[^a-zA-Z0-9\s]/',utf8_encode("Ñ"),str_replace("\'","",str_replace("Ã",utf8_encode(""),utf8_encode($PagaCont)))))."' and nocontrato='$PagaNocont'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FecIniPag=$fila[0]; $FecFinPag=$fila[1];
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Paciente[1]' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$PagaCont' and nocontrato='$PagaNocont' and entidad='$Paga' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup  and fechai is distinct from fechae order by fechai";					
			//echo "<br>$cons<br>";		
			
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res))
			{$Cont++;
				$DiasCobro=0;
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}			
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor,cupsxplanservic.facturable
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);				
				
                $FIinicial1=explode("-",$FecIniLiq); //echo  "FecIniLiq=$FecIniLiq  ";
				$FIinicial2=explode("-",$fila[1]); //echo $fila[1]."<br>";
				
				$FI1 = mktime (0,0,0,$FIinicial1[1],$FIinicial1[2],$FIinicial1[0]); //echo "FIinicial1= $FIinicial1[0] - $FIinicial1[2] - $FIinicial1[1]  <br>\n";			
				$FI2 = mktime (0,0,0,$FIinicial2[1],$FIinicial2[2],$FIinicial2[0]); //echo "Fila[1]=$FIinicial2[0] - $FIinicial2[2] - $FIinicial2[1] <br>\n";
				
				$FFinal1=explode("-",$FecFinLiq2); 
				$FFinal2=explode("-",$fila[2]);
				
				$FF1 = mktime (0,0,0,$FFinal1[1],$FFinal1[2],$FFinal1[0]);	
				if($fila[2]){$FF2 = mktime (0,0,0,$FFinal2[1],$FFinal2[2],$FFinal2[0]);	}
				
				$FecIniEstancia="";
				$FecFinEstancia="";
				$DiasCobro="";		
				//echo "FI1=$FI1 FI2=$FI2 FF1=$FF1 FF2=$FF2<br> XXX";			
				if($FI2<=$FI1){ //echo "Si la fecha Inicial del periodo de la estancia es menor o igual a la fecha inicial Seleccionada echo $fila[2]";
					$FecIniEstancia=$FecIniLiq;
					if(empty($fila[2])){									
						$FecFinEstancia=$FecFinLiq2; //echo "caso 1 ";
					}					
					else{
						if($FF2>=$FF1){
							$FecFinEstancia=$FecFinLiq2;  //echo "caso 2 ";
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
							$FecFinEstancia=$FecFinLiq2; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FecFinLiq2;//echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				//echo "<br>".$FecIniEstancia."|".$FecFinEstancia;
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				//echo "$DiasCobro <br>";
				//$DiasCobro++;		
				//if($Num>0){$DiasCobro--;}		//Verificar con mas dias de estancia	
				//echo "$FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro<br>";
				if($fila3[3]==1)
				{
					$Facturable="";$Facturable1="";$Facturable2="";
				}
				else{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[2]="0";
				}
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
						//	if($FecIniPag==$FecIniEstancia){$DiasCobro--;}
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
					$cons98="Select ";
					if($DiasCobro==31 && $AjustarDias){$DiasCobro=30;}	
					if($DiasCobro>0){
					//if($DiasCobro>1){$DiasCobro--;}					
					$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";			
					$res5=ExQuery($cons5); echo ExError();
					//echo $cons2;
					if(ExNumRows($res5)>0){ //echo "acutaliza";
						$fila5=ExFetch($res5); 	
						$Cantidad=$fila5[1]+$DiasCobro;
						$VrTot=$Cantidad*$fila5[4];
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito='$Ambito'
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";					
						//echo "<br>\n$cons4<br>\n";		
					}
					else{
						if(!$fila[2]){
							$DiasCobro--;
						}
						$vT=$DiasCobro*$fila3[2];//echo "DiasCobro=$DiasCobro<BR>";
						$cons4="insert into facturacion.tmpcupsomeds 
						(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,finalidad,causaext,dxppal,tipodxppal,ambito $Facturable1) values 
						('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila[0]','$fila[5]',$DiasCobro,$fila3[2],$vT,'$FecFinLiq2','','','','','$Ambito' $Facturable2)"; 
						//echo "<br>\n$cons4<br>\n";		
						//si no tiene grupo lo inserta pero no lo lista
					}
					$res4=ExQuery($cons4); echo ExError();}//
				}
				$FechaIniAnt=$FecIniEstancia; $FechaFinAnt=$FecFinEstancia;
			}
			
			//En caso de no cobrar del primer dia
			if($PrimDia==0){
				$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
				
				$res3=ExQuery($cons3);
				if(ExNumRows($res3)>0){
					$fila3=ExFetch($res3);
					if($fila3[1]==1){
						$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
						and grupo='$fila5[2]' and tipo='$fila5[3]'";						
					}
					else{
						//$Cantidad=$fila3[1]-1;
						$Cantidad=$fila3[1];
						$VrTot=$fila3[4]*$Cantidad;
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
					}					
					$res4=ExQuery($cons4);
				}
			}
			//En caso de no cobrar el ultimo dia
			if($NoEgr==0){
				if($UltDia==0){			
					$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
					
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0){
						$fila3=ExFetch($res3);
						if($fila3[1]==1){
							$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
							and grupo='$fila5[2]' and tipo='$fila5[3]'";						
						}
						else{
							//$Cantidad=$fila3[1]-1;
							$Cantidad=$fila3[1];
							$VrTot=$fila3[4]*$Cantidad;
							$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
							where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
						}					
						$res4=ExQuery($cons4);
					}
				}				
			}
		}






		
	}
	
	//----------------------------------------elimina un Pagador x Servicios---------------------------------------------------------------------
	if($Eliminar){
		$cons="Delete from salud.tmppagadorxfactura where compania='$Compania[0]' and tmpcod='$TMPCOD' and cedula='$Paciente[1]' and entidad='$EPS' and contrato='$Contra' and 
		nocontrato='$NoContra' and fechaini='$Ini'";
		$res=ExQuery($cons);		
	}
	//----------------------------------------Elimina un Cup o Medicamento---------------------------------------------------------------------
	if($EliminarCoM==1){		
		$cons="Delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$Paciente[1]' and codigo='$CodCoM' and nombre='$NomCoM'	";
		//echo $cons;
		$res=ExQuery($cons);		
	}
	//-----------------------------------------Cancelar-------------------------------------------------------------------------------------------------
	if($Cancelar==1)
	{
		$cons="delete from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);
		//echo $cons;
		$cons="delete from facturacion.tmpcupsomeds where tmpcod='$TMPCOD2' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);
		//echo $cons;
		?>
		<script language="javascript">location.href='VerLiquidaciones.php?DatNameSID=<? echo $DatNameSID?>';</script>
<?	}
	//----------------------------------------------------------Guardar Todo--------------------------------------------------------------------------------
	if($Guardar){
		if($Total<=$SaldoContra){
			//-----------------------------------------------------Actualiza Tabla de servicios
			if($Fechae!=''){$FechaEgreso=",fechaegr='$Fechae'";}
			$cons="Update Salud.servicios set tiposervicio='$Ambito',medicotte='$Medicotte',fechaing='$Fechaing',nocarnet='$Nocarnet',tipousu='$Tipousu', 
			nivelusu='$Nivelusu',autorizac1='$Autorizac1',autorizac2='$Autorizac2',autorizac3='$Autorizac3' $FechaEgreso where cedula='$Paciente[1]' and compania='$Compania[0]' 
			and numservicio='$NumServ'";			
			$res=ExQuery($cons);
			//----------------------------------------------------Actualiza pagador por servicios
			if($TMPCOD!=''){
			/*	$cons3="delete from salud.pagadorxservicios where numservicio=$NumServ and compania='$Compania[0]'";	
				//echo $cons3;
				$res3=ExQuery($cons3);echo ExError();		 
				$cons3="select entidad,tmppagadorxfactura.contrato,tmppagadorxfactura.nocontrato,tmppagadorxfactura.fechaini,tmppagadorxfactura.fechafin
				from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and tmppagadorxfactura.compania='$Compania[0]' and cedula='$Paciente[1]'";	
				$res3=ExQuery($cons3);echo ExError();						
				while($fila3=ExFetch($res3))
				{
					if($fila3[4]!=''){
						$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre) values
						($NumServ,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$fila3[4]','$usuario[1]', 
						'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
					}
					else{
						$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre,fechacre) values
						($NumServ,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
					}
					$res4=ExQuery($cons4);echo ExError();
					//echo $cons4."<br>";
				}*/
			}			
			if($NoLiquidacion){
				//echo $NoLiquidacion;
			//----------------------------------------------Edicion de Liquidacion
				if($Fechae!=''){$FechaFin=",fechafin='$Fechae'";}
				if($PagaNocont!=''){$Pagador=",pagador='$Paga',contrato='$PagaCont',nocontrato='$PagaNocont'";}				
				if($Porsentajecopago==''){$Porsentajecopago="0";}
                if($Valorcopago!=''){$Copago=",valorcopago='$Valorcopago',porsentajecopago='$Porsentajecopago',tipocopago='$Tipocopago',clasecopago='$ClaseCopago'";}
				if($Porsentajedesc==''){$Porsentajedesc="0";}
				if($Valordescuento!=''){$Descuento=",valordescuento='$Valordescuento',porsentajedesc='$Porsentajedesc'";
					$consul3="select usuario from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion=$NoLiquidacion";
					$result3=ExQuery($consul3);
					if(ExNumRows($result3)>0){
						$cons="update facturacion.descuentosliq set noliquidacion=$NoLiquidacion,numservicio=$NumServ where compania='$Compania[0]' and cedula='$Paciente[1]' 
						and noliquidacion=$NoLiquidacion";
						$res=ExQuery($cons);
					}
					else{
						$cons="update facturacion.descuentosliq set noliquidacion=$NoLiquidacion,numservicio=$NumServ where compania='$Compania[0]' and cedula='$Paciente[1]' 
						and noliquidacion is null";
						$res=ExQuery($cons);
					}
				}
				if($Total!=''){$TyST=",subtotal='$Subtotal',total='$Total'";}
				
				$cons="update facturacion.liquidacion set ambito='$Ambito',medicotte='$Medicotte',nocarnet='$Nocarnet',tipousu='$Tipousu', 
				nivelusu='$Nivelusu',autorizac1='$Autorizac1',autorizac2='$Autorizac2',autorizac3='$Autorizac3',
				numservicio=$NumServ,usumod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' $FechaFin $Pagador $Copago 
				$Descuento $TyST where cedula='$Paciente[1]' and noliquidacion=$NoLiquidacion";	
				//echo $cons."<br><br>";	
				$res=ExQuery($cons); 
						
			}
			else{
			
			////----------------------------------------------Nuevo Encabezado Liquidacion				
				$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
				$res=ExQuery($cons);$fila=ExFetch($res);				
				$AutoIdLiq=$fila[0]+1;	
				
				if($Fechae!=''){$FechaEgreso1=",fechafin"; $FechaEgreso2=",'$Fechae'";}
				//if($PagaNocont!=''){$Pagador1=",pagador,contrato,nocontrato";$Pagador2=",'$Paga','$PagaCont','$PagaNocont'";}				
                if($Valorcopago!=''){
					if($Porsentajecopago==''){$Porsentajecopago="0";}
					$Copago1=",valorcopago,porsentajecopago,tipocopago,clasecopago";$Copago2=",$Valorcopago,$Porsentajecopago,'$Tipocopago','$ClaseCopago'";}
				if($Porsentajedesc==''){$Porsentajedesc="0";}
				if($Valordescuento!=''){
					$Descuento1=",valordescuento,porsentajedesc";$Descuento2=",$Valordescuento,$Porsentajedesc";
					$cons="update facturacion.descuentosliq set noliquidacion=$AutoIdLiq,numservicio=$NumServ where compania='$Compania[0]' and cedula='$Paciente[1]' 
					and noliquidacion is null";
					//echo $cons;
					$res=ExQuery($cons);
				}
				if($Total!=''){$TyST1=",subtotal,total";$TyST2=",'$Subtotal','$Total'";}
				
				$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,
				noliquidacion,numservicio,cedula,fechafin,pagador,contrato,nocontrato  $Copago1 $Descuento1 $TyST1) values ('$Compania[0]','$usuario[1]',
				'$FecFinLiq2 $ND[hours]:$ND[minutes]:$ND[seconds]','$Ambito','$Medicotte','$FecIniLiq','$Nocarnet','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2',
				'$Autorizac3',$AutoIdLiq,$NumServ,'$Paciente[1]','$FecFinLiq2','$Paga','$PagaCont','$PagaNocont' $Copago2 $Descuento2 $TyST2)";				
				$res=ExQuery($cons);
				//echo $cons."<br>";
				$NoLiquidacion=$AutoIdLiq;					
			}
			//------------------------------------------------------Nuevos Cups o Medicamentos Liquidacion
			
			
			
			
			
			
			
			
			
			
			
			
			
		/*
		if($Ambito){$TipoServ="and tiposervicio='$Ambito'";}
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,cedula,primape,segape,primnom,segnom,medicotte,autorizac1,autorizac2,autorizac3,servicios.tipousu,servicios.nivelusu,
	servicios.nocarnet
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ 
	order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);
					   if($Entidad){
$Ent=" and entidad='$Entidad'";}
				if($Contrato){$Contra=" and contrato='$Contrato'";}
				if($NoContrato){$Nocontra=" and nocontrato='$NoContrato'";}
				if($TipoAseg){$TAseg=" and tipoasegurador='$TipoAseg'";}
				
				$cons2="select fechaini,fechafin,entidad,contrato,nocontrato,primape,segape,primnom,segnom from salud.pagadorxservicios,central.terceros 
				where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=entidad and numservicio=$NumServ
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
$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";
		$res4=ExQuery($cons4);		
		
		while($fila4=ExFetch($res4)){		
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			while($fila3=ExFetch($res3)){//$fila3=ExFetch($res3);
			
			if($fila3[6]){
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$filaPMes[0]' and almacenppal='$fila4[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);	
			}
			

if($fila3[8])$CUM_CODE=$fila3[8];
						   else $CUM_CODE=$filaMedsxPlan[0];	
			

$consT="select autoid from facturacion.detalleliquidacion 
			where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and tipo='Medicamentos' order by autoid asc";
			//echo $cons."<br>";
			$resT=ExQuery($consT);	
while($filaT=ExFetch($resT)){			
			echo $consC="update facturacion.detalleliquidacion set rip='$CUM_CODE' where compania='$Compania[0]'
			and autoid='$filaT[0]' and tipo='Medicamentos'";
			//echo $cons."<br>";
			$resC=ExQuery($consC);	}   
			}			   
						   
						   }		}
		*/	
			
			
			
			//Verificamos Las Restricciones de Cobro 
			$consRES="select restriccioncobro from ContratacionSalud.Contratos 
			where compania='$Compania[0]' and entidad='$Paga' and 		contrato='$PagaCont' and numero='$PagaNocont'";
			$resRES=ExQuery($consRES);
			$filaRES=ExFetch($resRES); $RestricCobro=$filaRES[0];
			if($RestricCobro==1)
			{
				$consRestric="select grupo,mostrar,montofijo,cobrar from contratacionsalud.restriccionescobro 
				where compania='$Compania[0]' and entidad='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont'";
				$resRestric=ExQuery($consRestric);			
				//echo $consRestric;
				while($filaRestric=ExFetch($resRestric))
				{
					$Rescric[$filaRestric[0]]=array($filaRestric[1],$filaRestric[2],$filaRestric[3]); //Rescric[grupo] = mostrar,montofijo,cobrar				
				}
			}
			
			$cons="select ambitos.codigo from salud.ambitos,facturacion.liquidacion where ambitos.compania='$Compania[0]' and liquidacion.compania='$Compania[0]'
			and noliquidacion=$NoLiquidacion and ambitos.ambito=liquidacion.ambito";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			//echo $cons."<br>";
			$Amb=$fila[0];
			$cons="delete from facturacion.detalleliquidacion where noliquidacion=$NoLiquidacion";
			$res=ExQuery($cons);	
			$cons="select grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,fecha,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal,ambito,cum
			,formarealizacion,nofacturable,rip,atc	from facturacion.tmpcupsomeds where compania='$Compania[0]'
			and tmpcod='$TMPCOD2' and cedula='$Paciente[1]'";
			//echo $cons."<br>";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){	$AUT=0;			
				while($fila=ExFetch($res)){
					if($fila[21]!=1){$fila[21]="0";}
					if(!$fila[19]){$fila[19]="$Amb";}
					if($RestricCobro&&$Rescric)
					{
						if($Rescric[$fila[0]])
						{
							if($Rescric[$fila[0]][0]=="Si")
							{$fila[21]="0";}else{$fila[21]="1";}
							if($Rescric[$fila[0]][1]&&$Rescric[$fila[0]][1]!="0")
							{								
								if($BanRestric[$fila[0]]!=1){
									$fila[5]=$Rescric[$fila[0]][1];
									$fila[6]=$Rescric[$fila[0]][1];
									$BanRestric[$fila[0]]=1;								
									echo "<br>$fila[2] $fila[3] $fila[0]".$Rescric[$fila[0]][1]."<br>" ;
								}
								else{
									$fila[5]="0";
									$fila[6]="0";
								}
							}
							else
							{
								if($Rescric[$fila[0]][2]=="No")
								{
									$fila[6]="0";
								}													
							}							
						}						
					}
					
										/*___$consD="select sum(cantidad) from consumo.movimiento,consumo.codproductos
												where consumo.movimiento.autoid=consumo.codproductos.autoid and movimiento.compania='$Compania[0]' and cedula='$Cedula' and comprobante='Devoluciones' and tipocomprobante='Devoluciones' and noliquidacion is null	
												and estado='AC' and movimiento.fecha>='$FecIniLiq' 
												and movimiento.fecha<='$FecFinLiq2' and numservicio=$NumServ and grupo like 'Medicamento%'";
												$resD=ExQuery($consD);  
												while($filaD=ExFetch($resD)){
													  $Cantidad=($fila[4]-$filaD[0]);
													  }*/
//					if(!$fila[20])$CUM_CODE=$fila[2];
//					   else $CUM_CODE=$fila[20];



					   
/*if($Ambito){$TipoServ="and tiposervicio='$Ambito'";}
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,cedula,primape,segape,primnom,segnom,medicotte,autorizac1,autorizac2,autorizac3,servicios.tipousu,servicios.nivelusu,
	servicios.nocarnet
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ 
	order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);
					   if($Entidad){
$Ent=" and entidad='$Entidad'";}
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
					
					$filaPMes="";
					$conPMeds = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos where Numero='$fila2[4]' and Entidad='$fila2[2]' and contrato='$fila2[3]' 
					and Compania='$Compania[0]'";
					$resPMeds=ExQuery($conPMeds); 
					$filaPMes=ExFetch($resPMeds);					   
$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";
		$res4=ExQuery($cons4);		
		
		while($fila4=ExFetch($res4)){		
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
			
			if($fila3[6]){
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$filaPMes[0]' and almacenppal='$fila4[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);	
			}
			

if($fila3[8])$CUM_CODE=$fila3[8];
						   else $CUM_CODE=$filaMedsxPlan[0];	}		}*/
if(!$fila[20])$CUM_CODE=$fila[2];
else $CUM_CODE=$fila[20];		
			   




/*echo "<br><br>".$Compania[0].",".$usuario[1].",".$ND['year']."-".$ND['mon']."-".$ND['mday']." ".$ND['hours'].":".$ND['minutes'].":".$ND['seconds'].",".$fila[0].",".$fila[1].",
						".$CUM_CODE.",".$fila[3].",".$fila[4].",".$fila[5].",".$fila[6].",".$NoLiquidacion.",
						".$fila[11].",".$fila[12].",".$fila[13].",".$fila[14].",".$fila[15].",".$fila[16].",".$fila[17].",
						".$fila[18].",".$fila[19].",".$fila[20].",".$fila[21].",".$CUM_CODE.",".$fila[2]."";*/
					if($fila[23]=="")$code=$fila[24];else$code=$fila[23];
					if($fila[1]!="Medicamentos"){
						if($fila[11]==''){$fila[11]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}								
						$cons2="insert into facturacion.detalleliquidacion 
						(compania,usuario,fechacrea,grupo,tipo,
						 codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,
						 fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,
						 tipodxppal,ambito,formarealizacion,nofacturable,cum,rip) 
						values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','$fila[1]',
						'$CUM_CODE','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$NoLiquidacion',
						'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$fila[16]','$fila[17]',
						'$fila[18]','$fila[19]',null,$fila[21],'$CUM_CODE','$code')";//$fila[23]						
					}
					else{
						$cons2="insert into facturacion.detalleliquidacion 
						(compania,usuario,fechacrea,grupo,tipo,
						codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,
						forma,almacenPpal,noliquidacion,nofacturable,cum,rip) values 
						('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','Medicamentos',
						'$CUM_CODE','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[8]',
						'$fila[9]','$fila[10]','$NoLiquidacion','$fila[21]','$CUM_CODE','$code')";//$fila[23]	
					}					
					//echo "<br>$cons2";
					$res2=ExQuery($cons2); echo ExError();
					
					
					
					
					/*if($fila[0]=='38'){
					echo $consT="select autoid from facturacion.detalleliquidacion 
			where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and grupo='38' order by autoid asc";
			//echo $cons."<br>";
			$resT=ExQuery($consT);	
while($filaT=ExFetch($resT)){	
			echo $consC="update facturacion.detalleliquidacion set codigo='$fila[2]',cum='$fila[2]'  where compania='$Compania[0]'
			and autoid='".($filaT[0]+$AUT)."' and grupo='38'";
			//echo $cons."<br>";
			$resC=ExQuery($consC);break;} $AUT++;}*/
					
					
				}
			}
			
			
			
			/*if($Ambito){$TipoServ="and tiposervicio='$Ambito'";}
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,cedula,primape,segape,primnom,segnom,medicotte,autorizac1,autorizac2,autorizac3,servicios.tipousu,servicios.nivelusu,
	servicios.nocarnet
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ 
	order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);
					   if($Entidad){
$Ent=" and entidad='$Entidad'";}
				if($Contrato){$Contra=" and contrato='$Contrato'";}
				if($NoContrato){$Nocontra=" and nocontrato='$NoContrato'";}
				if($TipoAseg){$TAseg=" and tipoasegurador='$TipoAseg'";}
				
				$cons2="select fechaini,fechafin,entidad,contrato,nocontrato,primape,segape,primnom,segnom from salud.pagadorxservicios,central.terceros 
				where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=entidad and numservicio=$NumServ
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
$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";
		$res4=ExQuery($cons4);		
		
		while($fila4=ExFetch($res4)){		
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			while($fila3=ExFetch($res3)){//$fila3=ExFetch($res3);
			
			if($fila3[6]){
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$filaPMes[0]' and almacenppal='$fila4[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);	
			}
			

if($fila3[8])$CUM_CODE=$fila3[8];
						   else $CUM_CODE=$filaMedsxPlan[0];	
						   
			echo $consC="update facturacion.tmpcupsomeds set rip='$CUM_CODE' where compania='$Compania[0]'
			and tmpcod='$TMPCOD2' and cedula='$Paciente[1]' and tipo='Medicamentos'";
			//echo $cons."<br>";
			$resC=ExQuery($consC);	   
			}			   
						   
						   }		}*/
			
			
	




if($Ambito){$TipoServ="and tiposervicio='$Ambito'";}
	$cons="select numservicio,tiposervicio,fechaing,fechaegr,cedula,primape,segape,primnom,segnom,medicotte,autorizac1,autorizac2,autorizac3,servicios.tipousu,servicios.nivelusu,
	servicios.nocarnet
	from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula $TipoServ 
	order by primape,segape,primnom,segnom";
	$res=ExQuery($cons);
					   if($Entidad){
$Ent=" and entidad='$Entidad'";}
				if($Contrato){$Contra=" and contrato='$Contrato'";}
				if($NoContrato){$Nocontra=" and nocontrato='$NoContrato'";}
				if($TipoAseg){$TAseg=" and tipoasegurador='$TipoAseg'";}
				
				$cons2="select fechaini,fechafin,entidad,contrato,nocontrato,primape,segape,primnom,segnom from salud.pagadorxservicios,central.terceros 
				where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and terceros.identificacion=entidad and numservicio=$NumServ
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
$cons4="select autoid,sum(cantidad),regmedicamento,movimiento.almacenppal,autoid,numservicio,cum from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FecIniLiq' 
		and movimiento.fechadespacho<='$FecFinLiq2' group by autoid,regmedicamento,movimiento.almacenppal,autoid,numservicio,cum
		order by cum";
		$res4=ExQuery($cons4);		
		$AUT=0;
		while($fila4=ExFetch($res4)){		
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1,pos,codigo2
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$filaPMes[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila4[3]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila4[0]'				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			while($fila3=ExFetch($res3)){//$fila3=ExFetch($res3);
			
			if($fila3[6]){
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$filaPMes[0]' and almacenppal='$fila4[3]' and codigo='$fila3[6]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);
			//echo $consMedsxPlan."<br>";
			$filaMedsxPlan=ExFetch($resMedsxPlan);	
			}
			

if($fila3[8])$CUM_CODE=$fila3[8];
						   else $CUM_CODE=$filaMedsxPlan[0];	
			

$consT="select autoid from facturacion.detalleliquidacion 
			where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and tipo='Medicamentos' order by autoid asc";
			//echo $cons."<br>";
			$resT=ExQuery($consT);	
while($filaT=ExFetch($resT)){	
			$consC="update facturacion.detalleliquidacion set rip='$CUM_CODE' where compania='$Compania[0]'
			and autoid='".($filaT[0]+$AUT)."' and tipo='Medicamentos'";
			//echo $cons."<br>";
			$resC=ExQuery($consC);	break;}   
			
			
			
			
			
			
			
//if($fila[0]=='38'){
					$consTT="select autoid from facturacion.detalleliquidacion 
			where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and grupo='38' order by autoid asc";
			//echo $cons."<br>";
			$resTT=ExQuery($consTT);	
while($filaTT=ExFetch($resTT)){	
			$consC="update facturacion.detalleliquidacion set codigo='$fila4[0]',cum='$fila4[0]'  where compania='$Compania[0]'
			and autoid='".($filaTT[0]+$AUT)."' and grupo='38'";
			//echo $cons."<br>";
			$resC=ExQuery($consC);break;} //}			
			
			
			
			
			
			}			   
						   
						   $AUT++;}		}







	
			$cons="delete from facturacion.tmpcupsomeds where tmpcod='$TMPCOD2' and compania='$Compania[0]' and cedula='$Paciente[1]'";			
			$res=ExQuery($cons);	/*
			if($BanCargarHC==1){ //esta opcion es para el caso q deseemos guardar el numero de liquidacion en cada cup o medicamento
				$cons="update salud.plantillaprocedimientos set noliquidacion=$NoLiquidacion
				where plantillaprocedimientos.compania='$Compania[0]' and cedula='$Paciente[1]' and plantillaprocedimientos.estado='AC' and noliquidacion is null
				and fechainterpretacion>='$FecIniLiq' and fechainterpretacion<='$FecFinLiq' and numservicio=$NumServ";
				$res=ExQuery($cons);
				//echo $cons;
				$cons="select tblformat from historiaclinica.formatos where estado='AC' and compania='$Compania[0]'";
				$res=ExQuery($cons);
				while($fila=ExFetch($res)){
					$cons5=$cons5." update  histoclinicafrms.$fila[0] set noliquidacion=$NoLiquidacion
					where $fila[0].compania='$Compania[0]' and $fila[0].cedula='$Paciente[1]' and $fila[0].cup is not null  and noliquidacion=0
					and numservicio=$NumServ and fecha>='$FecIniLiq' and fecha<='$FecFinLiq'";					
					$res=ExQuery($cons5); echo ExError();
				}	
				$cons="update consumo.movimiento set noliquidacion=$NoLiquidacion where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
				and noliquidacion is null and tipocomprobante='Salidas' and almacenppal in(select almacenppal from consumo.almacenesppales where compania='$Compania[0]'
				and ssfarmaceutico=1) and fecha>='$FecIniLiq' and fecha<='$FecFinLiq' and numservicio=$NumServ";
				//echo $cons;	
				$res=ExQuery($cons);	
			}*/
		?>	<script language="javascript">
			//open('/Facturacion/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $NoLiquidacion?>&Ced=<? echo $Paciente[1]?>','','width=800,height=600,scrollbars=YES');
			  open('/Facturacion/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiquidacion=<? echo $NoLiquidacion?>&Company=<? echo $Compania[0]?>&FechaIni=<? echo $FecIniLiq?>&FechaFin=<? echo $FecFinLiq2?>','','width=800,height=600,scrollbars=YES');
			location.href="VerLiquidaciones.php?DatNameSID=<? echo $DatNameSID?>"</script><?		
		}
		else{?>
			<script language="javascript">alert("No se puede realizar esta liquidacion debido a que el contrato no tiene saldo!!!");</script>	
	<?	}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("/xajax");?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function salir(){		 
		 document.FORMA.Cancelar.value=1;
		 document.FORMA.submit();
	}
	function ElimarCupoMed(C,N)
	{		
		document.FORMA.EliminarCoM.value=1;
		document.FORMA.CodCoM.value=C;
		document.FORMA.NomCoM.value=N;		
		//alert(document.FORMA.PagaNocontCoM.value);
		document.FORMA.NoEnvia.value=1;
		document.FORMA.submit();		
	}
	function EditarCoM(C,N,T)
	{
		frames.FrameOpener.location.href="NewLiquidacion.php?NumServ=<? echo $NumServ?>&FecIniLiq=<? echo $FecIniLiq?>&FecFinLiq2=<? echo $FecFinLiq2?>&DatNameSID=<? echo $DatNameSID?>&Edit=1&CodCoM="+C+"&NomCoM="+N+"&Editar=1&TMPCOD=<? echo $TMPCOD2?>&TipoNuevo="+T;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=30;
		document.getElementById('FrameOpener').style.left=10;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='500px';		 
	}
	function Inserta(){
		if(document.FORMA.Entidad.value==""){
			alert("Debe seleccionar una Entidad!!!");return false;
		}
		if(document.FORMA.Contrato.value==""){
			alert("Debe haber un Contrato!!!");return false;
		}
		if(document.FORMA.Nocontrato.value==""){
			alert("Debe haber un numero de Contrato!!!");return false;
		}
		if(document.FORMA.Desde.value==""){
			alert("Debe seleccionar la fecha inicial !!!");return false;
		}		
		else{
			if(document.FORMA.Desde.value<=document.FORMA.Fin.value){
				alert("La fecha inicial debe ser mayor a la ultima fecha final!!");return false;
			}
			else{			
				if(document.FORMA.Hasta.value!=""){
					if(document.FORMA.Hasta.value<document.FORMA.Desde.value){
						alert("La fecha final debe ser mayor o igual a la fecha inicial !!!");return false;
					}
				}
			}
		}
		document.FORMA.NoEnvia.value=1;
		document.FORMA.Insertar.value=1;
		document.FORMA.submit();
	}
	function Elimina(E,C,N,I)
    {
    	document.FORMA.EPS.value=E;
        document.FORMA.Contra.value=C;
        document.FORMA.NoContra.value=N;
        document.FORMA.Ini.value=I;      
        document.FORMA.Eliminar.value=1;
		document.FORMA.submit();
    }
	function Finalizar(e,Eps,C,N,I,T)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="FinPagador.php?DatNameSID=<? echo $DatNameSID?>&EPS="+Eps+"&Contra="+C+"&NoContra="+N+"&Inicio="+I+"&TMPC="+T;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=x-50;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='310px';
		document.getElementById('FrameOpener').style.height='220px';
	}
	function EditarEPS(e,Eps,C,N,I,T)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="EditarPagador.php?DatNameSID=<? echo $DatNameSID?>&EPS="+Eps+"&Contra="+C+"&NoContra="+N+"&Inicio="+I+"&TMPC="+T;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=100;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='610px';
		document.getElementById('FrameOpener').style.height='140px';
	}
	function NuevoCup(e)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="CupsoMeds.php?NumServ=<? echo $NumServ?>&DatNameSID=<? echo $DatNameSID?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='150px';
		document.getElementById('FrameOpener').style.height='80px';
	}
	function Validar()
	{
		/*if(document.FORMA.Nocarnet.value==""){
			alert("Debe digitar el numero del Carnet");return false;
		}
		if(document.FORMA.Autorizac1.value==""){
			alert("Debe digitar la Autorizacion 1!!!");return false;			
		}		  
		if(document.FORMA.Paga.value==""){
			alert("Debe selecionar un pagador para la liquidacion!!!");return false;			
		}
		if(document.FORMA.PagaCont.value==""){
			alert("Debe selecionar un contrato para la liquidacion!!!");return false;			
		}
		if(document.FORMA.PagaNocont.value==""){
			alert("Debe selecionar un numero de contrato para la liquidacion!!!");return false;			
		}*/
	}
	function Calcular(T)
	{
		var vrd=(document.FORMA.Porsentajedesc.value/100)*T;		
		
		document.FORMA.Valordescuento.value = vrd;
		var Tot=T-document.FORMA.Valordescuento.value;
		//Tot2=Math.round(Tot,10);
		document.FORMA.Total.value=Tot;
	}
	function PreGuarda()
	{
		var Valida;
		Valida=Validar();
		if(Valida!=false){			
			document.FORMA.NoEnvia.value=1;			
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();
		}		
	}
	function Limpiar(Comp,TMP)
	{			
		if(document.FORMA.NoEnvia.value!=1){					
			xajax_LimTmp(Comp,TMP);	
		}		
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg" onUnload="Limpiar('<? echo $Compania[0]?>','<? echo $TMPCOD2?>')">
<form name="FORMA" method="post">
<?
if($NoLiquidacion==''){
	
}
$consS="select tiposervicio,medicotte,fechaing,fechaegr,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3 from salud.servicios 
where compania='$Compania[0]' and numservicio=$NumServ and cedula='$Paciente[1]'";
$resS=ExQuery($consS);
$filaS=ExFetch($resS);
$TipoServcio=$filaS[0]; $Ambito=$TipoServcio; $Medicotte=$filaS[1];
$FecIniServ=explode(" ",$filaS[2]); $Fechaing=$FecIniServ[0];
$FecFinServ=explode(" ",$filaS[3]); $Fechae=$FecFinServ[0];
$Nocarnet=$filaS[4]; $Tipousu=$filaS[5]; $Nivelusu=$filaS[6]; $Autorizac1=$filaS[7]; $Autorizac2=$filaS[8]; $Autorizac3=$filaS[9]; 
?>
<input type="hidden" name="TipoServcio" value="<? echo $TipoServcio?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="Medicotte" value="<? echo $Medicotte?>">
<input type="hidden" name="Fechaing" value="<? echo $Fechaing?>">
<input type="hidden" name="Fechae" value="<? echo $Fechae?>">
<input type="hidden" name="Nocarnet" value="<? echo $Nocarnet?>">
<input type="hidden" name="Tipousu" value="<? echo $Tipousu?>">
<input type="hidden" name="Nivelusu" value="<? echo $Nivelusu?>">
<input type="hidden" name="Autorizac1" value="<? echo $Autorizac1?>">
<input type="hidden" name="Autorizac2" value="<? echo $Autorizac2?>">
<input type="hidden" name="Autorizac3" value="<? echo $Autorizac3?>">


<br>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
<?	
//if($Paga!=''){
	$cons="select (primnom || segnom || primape || segape) as Nombre from central.terceros where identificacion='$Paga' and compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res)?>
     <tr bgcolor="#e5e5e5" style="font-weight:bold">
	    <td colspan="8" align="center">No. Servicio: <? echo $NumServ?> - <? echo $TipoServcio?></td>
  	</tr>    
  	<tr align="center">
    	<td colspan="8" bgcolor="#e5e5e5"><strong>Pagador Actual: </strong><? echo $fila[0]?> 
        <strong>Contrato: </strong><? echo $PagaCont?> <strong>NoContrato: </strong> <? echo $PagaNocont?>
      	<input type="hidden" name="Paga" value="<? echo $Paga?>">
      	<input type="hidden" name="PagaCont" value="<? echo $PagaCont?>">
      	<input type="hidden" name="PagaNocont" value="<? echo $PagaNocont?>"><?	
/*}
else{
	if(!$Paga){
		$cons="select entidad,contrato,nocontrato from salud.pagadorxservicios 
		where pagadorxservicios.compania='$Compania[0]' and
		pagadorxservicios.numservicio=$NumServ	and '$ND[year]-$ND[mon]-$ND[mday]'>=fechaini and '$ND[year]-$ND[mon]-$ND[mday]'<=fechafin";
		$res=ExQuery($cons); $fila=ExFetch($res);
		if(ExNumRows($res)<=0){
			$cons="select entidad,contrato,nocontrato from salud.pagadorxservicios 
			where pagadorxservicios.compania='$Compania[0]' and
			pagadorxservicios.numservicio=$NumServ	and '$ND[year]-$ND[mon]-$ND[mday]'>=fechaini and fechafin is null";
			$res=ExQuery($cons); $fila=ExFetch($res);
		}
		//echo $cons;
		$Paga=$fila[0]; $PagaCont=$fila[1]; $PagaNocont=$fila[2];
	}
	$cons="select (primnom || segnom || primape || segape) as Nombre,entidad from salud.pagadorxservicios,central.terceros,salud.servicios 	
	where pagadorxservicios.numservicio=servicios.numservicio and servicios.compania='$Compania[0]' and pagadorxservicios.entidad=terceros.identificacion and 	
	terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and servicios.numservicio=$NumServ group by primnom,segnom,primape,segape,entidad order by Nombre";
	$res=ExQuery($cons);?>
  	<tr align="center">
    	<td colspan="8"><strong>Pagador Actual</strong>&nbsp;
      		<select name="Paga" onChange="document.FORMA.NoEnvia.value=1;document.FORMA.submit()">
                <option></option>
            <?	while($fila=ExFetch($res))
                {
                    if($Paga==$fila[1]){
                        echo "<option value='$fila[1]' selected>$fila[0]</option>";
                    }
                    else{
                        echo "<option value='$fila[1]'>$fila[0]</option>";
                    }
                }?>
      		</select>
      		&nbsp;<strong>Contrato</strong>
      <? 	$cons2="select pagadorxservicios.contrato,pagadorxservicios.nocontrato from salud.pagadorxservicios where pagadorxservicios.compania='$Compania[0]' 
			and numservicio=$NumServ and entidad='$Paga' order by contrato";
			$res2=ExQuery($cons2);?>
      		<select name="PagaCont" onChange="document.FORMA.NoEnvia.value=1;document.FORMA.submit()">
                <option></option>
            <?	while($fila2=ExFetch($res2))
                {
                    if($PagaCont==$fila2[0]){
                        echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
                    }
                    else{
                        echo "<option value='$fila2[0]'>$fila2[0]</option>";
                    }
                }?>
      		</select>
      <?	$cons3="select pagadorxservicios.nocontrato from  salud.pagadorxservicios where pagadorxservicios.compania='$Compania[0]' 
			and numservicio=$NumServ and entidad='$Paga' and contrato='$PagaCont'";
			$res3=ExQuery($cons3);	?>
          	&nbsp;<strong>NoContrato: </strong>
          	<select name="PagaNocont" onChange="document.FORMA.NoEnvia.value=1;document.FORMA.submit()">
        		<option></option>
        <?		while($fila3=ExFetch($res3))
				{
					if($PagaNocont==$fila3[0]){
						echo "<option value='$fila3[0]' selected>$fila3[0]</option>";
					}
					else{
						echo "<option value='$fila3[0]'>$fila3[0]</option>";
					}
				}?>
     	 	</select>
    	</td>
  	</tr><?	
}*/?>
  <!-- <tr align="center">    
    	<td  colspan="8"><strong>Desde:</strong>
    	<input type="Text" name="FechaingCoM"  readonly onClick="popUpCalendar(this, FORMA.FechaingCoM, 'yyyy-mm-dd')" value="<? echo $FechaingCoM?>">
		<strong>Hasta:</strong><input type="Text" name="FechafinCoM"  readonly onClick="popUpCalendar(this, FORMA.FechafinCoM, 'yyyy-mm-dd')" value="<? echo $FechafinCoM?>">    
    	</td>
   	</tr>-->
    </td>
    <tr bgcolor="#e5e5e5"><? /*
    	<td colspan="8" align="center">
        	<strong>Monto:</strong><? echo number_format($MontoContra,2)?><strong> Consumo:</strong><? echo number_format($ConsumoContra,2)?><strong> Ejecucion:</strong><? echo number_format($EjecucionContra,2)?> <strong> xFactura:</strong><? echo number_format($xFacturarContra,2)?> <strong> Saldo:</strong><? echo number_format($SaldoContra,2)?>
     	</td>*/?>
        <td><strong>Monto:</strong><? echo number_format($MontoContra,2)?></td>
       	<td><strong>Consumo:</strong><? echo number_format($ConsumoContra,2)?></td>
        <td><strong>Ejecucion:</strong><? echo number_format($EjecucionContra,2)?></td> 
        <td><strong> xFacturar:</strong><? echo number_format($xFacturarContra,2)?></td> 
        <td colspan="3"><strong> Saldo:</strong><? echo number_format($SaldoContra,2)?></td>
     	</td>    
    </tr>
	<tr align="center">
    	<td colspan="8"><input type="submit" value="Cargar Historia Clinica" onClick="document.FORMA.NoEnvia.value=1;" name="CargarHC">
	      &nbsp;&nbsp;
    	  <input type="button" value="Nuevo" onClick="NuevoCup(event)" style="width:100px"></td>
  	</tr>
  	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td>Codigo</td>
        <td>Descripcion</td>
        <td>Cantidad</td>
        <td>Vr Unidad</td>
        <td>Vr Total</td>
        <td colspan="2" ></td>
  	</tr>
<?	//Verificamos Las Restricciones de Cobro 
	$consRES="select restriccioncobro from ContratacionSalud.Contratos 
	where compania='$Compania[0]' and entidad='$Paga' and 		contrato='$PagaCont' and numero='$PagaNocont'";
	$resRES=ExQuery($consRES);
	$filaRES=ExFetch($resRES); $RestricCobro=$filaRES[0];
	if($RestricCobro==1)
	{
		$consRestric="select grupo,mostrar,montofijo,cobrar from contratacionsalud.restriccionescobro 
		where compania='$Compania[0]' and entidad='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont'";
		$resRestric=ExQuery($consRestric);			
		//echo $consRestric;
		while($filaRestric=ExFetch($resRestric))
		{
			$Rescric[$filaRestric[0]]=array($filaRestric[1],$filaRestric[2],$filaRestric[3]); //Rescric[grupo] = mostrar,montofijo,cobrar				
		}
	}
	$Subtotal=0;		
	//MEDICAMENTOS
	/*$cons5="select grupo,almacenppal from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
	$res5=ExQuery($cons5);?>
  <?
	while($fila5=ExFetch($res5)){
		$cons4="select codigo,nombre,sum(cantidad),vrund,sum(vrtotal),sum(noentregado) from facturacion.tmpcupsomeds where	compania='$Compania[0]' and cedula='$Paciente[1]' 
		and tmpcod='$TMPCOD2' and tipo='Medicamentos' and grupo='$fila5[0]' and almacenppal='$fila5[1]'
		group by codigo,nombre,vrund";			
		$res4=ExQuery($cons4);		
		if(ExNumRows($res4)>0){	
			//echo $cons4;		
			$Sub=0;		
	  		while($fila4=ExFetch($res4)){?>            	  				
    <?			if(1==0){?>
					<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="Medicamento no entregado aun"><?
					echo "<td align='center' style='color:red'>$fila4[0]</td><td align='center' style='color:red'>$fila4[1]</td><td align='center' style='color:red'>$fila4[2]</td>
					<td align='right' style='color:red'>".number_format($fila4[3],2)."</td><td align='right' style='color:red'>".number_format($fila4[4],2)."</td>";
				}
				else{?>
                	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" ><?
					echo "<td align='center'>$fila4[0]</td><td align='center'>$fila4[1]</td><td align='center'>$fila4[2]</td><td align='right'>".number_format($fila4[3],2)."</td>
			 		<td align='right'>".number_format($fila4[4],2)."</td>";
				}
					$Sub=$Sub+$fila4[4];
				?>
    				<td><img src="/Imgs/b_edit.png" style="cursor:hand" 
        	    	    	onClick="EditarCoM('<? echo $fila4[0]?>','<? echo $fila4[1]?>','Medicamento')" title="Editar"> </td>
    				<td><img style="cursor:hand"  title="Eliminar"
    		        	onClick="if(confirm('Desea eliminar este registro?')){ElimarCupoMed('<? echo $fila4[0]?>','<? echo $fila4[1]?>')}" src="/Imgs/b_drop.png"> </td>
  				</tr>
  <?			$Subtotal=$Subtotal+$fila4[4];
				//echo "$Subtotal<br>";
			}
		?>	<tr bgcolor="#ECECEC">
        		<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong></td><td  align="right"><? echo number_format($Sub,2)?></td>
                <td colspan="2">&nbsp;</td>
			</tr><?
		}
	}*/

	/*//Estancia--------------------------------------------------------------------------------------------------------------------------------
		
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias,ambito from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$filaAmb[2]==1){ 
			$cons2="select planbeneficios,plantarifario,primdia,ultdia,ajustardias from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont' 
			and compania='$Compania[0]'";					
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1; }
			$AjustarDias=$fila2[4];
			
			$cons="select fechaini,fechafin from salud.pagadorxservicios where compania='$Compania[0]' and numservicio=$NumServ
			and entidad='$Paga' and contrato='$PagaCont' and nocontrato='$PagaNocont'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FecIniPag=$fila[0]; $FecFinPag=$fila[1];
			//echo $cons
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Paciente[1]' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$PagaCont' and nocontrato='$PagaNocont' and entidad='$Paga' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup order by fechai";					
			
						
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res))
			{$Cont++;
				$DiasCobro=0;
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor,cupsxplanservic.facturable
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);
				//if($fila3[0]==""){$fila3[2]="0";}
					
				$FIinicial1=explode("-",$FecIniLiq); //echo  "FecIniLiq=$FecIniLiq  ";
				$FIinicial2=explode("-",$fila[1]); //echo $fila[1]."<br>";
				
				$FI1 = mktime (0,0,0,$FIinicial1[1],$FIinicial1[2],$FIinicial1[0]); //echo "FIinicial1= $FIinicial1[0] - $FIinicial1[2] - $FIinicial1[1]  <br>\n";			
				$FI2 = mktime (0,0,0,$FIinicial2[1],$FIinicial2[2],$FIinicial2[0]); //echo "Fila[1]=$FIinicial2[0] - $FIinicial2[2] - $FIinicial2[1] <br>\n";
				
				$FFinal1=explode("-",$FecFinLiq2); 
				$FFinal2=explode("-",$fila[2]);
				
				$FF1 = mktime (0,0,0,$FFinal1[1],$FFinal1[2],$FFinal1[0]);			
				if($fila[2]){$FF2 = mktime (0,0,0,$FFinal2[1],$FFinal2[2],$FFinal2[0]);	}
				
				$FecIniEstancia="";
				$FecFinEstancia="";
				$DiasCobro="";
				//echo "FI1=$FI1 FI2=$FI2 FF1=$FF1 FF2=$FF2 <br> XXX";			
				if($FI2<=$FI1){ //Si la fecha Inicial del periodo de la estancia es menor a la fecha inicial Seleccionada					
					$FecIniEstancia=$FecIniLiq;
					if(empty($fila[2])){									
						$FecFinEstancia=$FecFinLiq2; //echo "caso 1 ";
					}					
					else{
						if($FF2>=$FF1){
							$FecFinEstancia=$FecFinLiq2;  //echo "caso 2 ";
						}
						else{
							if($FF2>=$FI1){
								$FecFinEstancia=$fila[2]; //echo "caso 3 ";
							}
							else{
								$FecIniEstancia="";
							}
						}
					}					
				}
				else{
					$FecIniEstancia=$fila[1];
					if($fila[2]==''){
						if($FI2<=$FF1){							
							$FecFinEstancia=$FecFinLiq2; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FecFinLiq2; //echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				//echo "<br>".$FecIniEstancia."|".$FecFinEstancia;
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				//$DiasCobro++;		
				//if($Num>0){$DiasCobro--;}		//Verificar con mas dias de estancia	
				//echo "$FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro<br>";
				if($fila3[3]==1)
				{
					$Facturable="";$Facturable1="";$Facturable2="";
				}
				else{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[2]="0";
				}
				if($fila3[0]==""){$fila3[2]="0";}
				$vT=$fila3[2]*$DiasCobro;
				if($fila3[2]==''){$fila3[2]="0";}
				if($fila3[1]==''){$fila3[1]="00001";}	
				
				if($FecIniEstancia==$FecFinEstancia){
					$DiasCobro=1;
				}	
				
				if($DiasCobro>0){
					if($FecIniEstancia<$FecFinEstancia){
						$DiasCobro=$DiasCobro+1;
					}
				//	echo "$DiasCobro FecIniEstancia=$FecIniEstancia FechaFinAnt=$FechaFinAnt<BR>";
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
					$cons98="Select ";
					if($DiasCobro==31 && $AjustarDias){$DiasCobro=30;}
				    if($DiasCobro>0){
					$PDiasCobro=0;
					$DiasCobro_[]=$DiasCobro;				
                    $resultado=0;
                    foreach($DiasCobro_ as $valor){
                           $resultado=$resultado+$valor;
						   if($resultado==31){$PDiasCobro=30;}
						   }
					$DiasCobro=$PDiasCobro;}				
					if($DiasCobro>0){
					//if($DiasCobro>1){$DiasCobro--;}					
					$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";			
					$res5=ExQuery($cons5); echo ExError();
					//echo $cons2;
					if(ExNumRows($res5)>0){ //echo "acutaliza";
						$fila5=ExFetch($res5); 						
						$Cantidad=$fila5[1]+$DiasCobro;
						$VrTot=$Cantidad*$fila5[4];
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito='$Ambito'
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";					
						//echo "<br>\n$cons4<br>\n";		
					}
					else{	
						$vT=$DiasCobro*$fila3[2];//echo "DiasCobro=$DiasCobro<BR>";
						$cons4="insert into facturacion.tmpcupsomeds 
						(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,finalidad,causaext,dxppal,tipodxppal,ambito $Facturable1) values 
						('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila[0]','$fila[5]',$DiasCobro,$fila3[2],$vT,'$FecFinLiq2','','','','','$Ambito' $Facturable2)"; 
						//echo "<br>\n$cons4<br>\n";		
						//si no tiene grupo lo inserta pero no lo lista
					}
					$res4=ExQuery($cons4); echo ExError();}//
				}
				$FechaIniAnt=$FecIniEstancia; $FechaFinAnt=$FecFinEstancia;
			}
			
			//En caso de no cobrar del primer dia
			if($PrimDia==0){
				$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
				
				$res3=ExQuery($cons3);
				if(ExNumRows($res3)>0){
					$fila3=ExFetch($res3);
					if($fila3[1]==1){
						$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
						and grupo='$fila5[2]' and tipo='$fila5[3]'";						
					}
					else{
						$Cantidad=$fila3[1]-1;
						$VrTot=$fila3[4]*$Cantidad;
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
					}					
					$res4=ExQuery($cons4);
				}
			}
			//En caso de no cobrar el ultimo dia
			if($NoEgr==0){
				if($UltDia==0){			
					$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
					
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0){
						$fila3=ExFetch($res3);
						if($fila3[1]==1){
							$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
							and grupo='$fila5[2]' and tipo='$fila5[3]'";						
						}
						else{
							$Cantidad=$fila3[1]-1;
							$VrTot=$fila3[4]*$Cantidad;
							$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
							where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
						}					
						$res4=ExQuery($cons4);
					}
				}				
			}
		}*/
	
/**************
//Estancia				
		$consAmb="select consultaextern,hospitalizacion,hospitaldia,pyp,urgencias from salud.servicios,salud.ambitos
		where servicios.compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and servicios.numservicio=$NumServ and ambitos.compania='$Compania[0]'
		and tiposervicio=ambito";
		//echo $consAmb;
		$resAmb=ExQuery($consAmb);
		$filaAmb=ExFetch($resAmb);
		if($filaAmb[1]==1||$filaAmb[2]==1){				
			$cons2="select planbeneficios,plantarifario,primdia,ultdia,ajustardias from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont' and compania='$Compania[0]'";	
			$res2=ExQuery($cons2); 
			$fila2=ExFetch($res2); 
			if($fila2[2]==1){$PrimDia=1;}
			if($fila2[3]==1){$UltDia=1;}
			$AjustarDias=$fila2[4];
			
			$cons="select fechaini,fechafin from salud.pagadorxservicios where compania='$Compania[0]' and numservicio=$NumServ
			and entidad='$Paga'and contrato='".trim(preg_replace('/[^a-zA-Z0-9\s]/',utf8_encode("Ñ"),str_replace("\'","",str_replace("Ã",utf8_encode(""),utf8_encode($PagaCont)))))."' and nocontrato='$PagaNocont'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$FecIniPag=$fila[0]; $FecFinPag=$fila[1];
			$cons="select cup,fechai,fechae,confestancia.pabellon,ambitos.ambito,nombre from salud.pacientesxpabellones,salud.confestancia,salud.ambitos,contratacionsalud.cups
			where pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.cedula='$Paciente[1]' 
			and pacientesxpabellones.numservicio=$NumServ and pacientesxpabellones.pabellon=confestancia.pabellon and confestancia.compania='$Compania[0]'
			and contrato='$PagaCont' and nocontrato='$PagaNocont' and entidad='$Paga' and ambitos.compania='$Compania[0]' and ambitos.hospitalizacion=1
			and ambitos.ambito=confestancia.ambito and cups.compania='$Compania[0]' and cups.codigo=confestancia.cup order by fechai";					
			//echo "<br>$cons<br>";		
			
			$res=ExQuery($cons); 
			$Num=ExNumRows($res);
			$Cont=1;
			while($fila=ExFetch($res))
			{$Cont++;
				$DiasCobro=0;
				$NoFecFin=0;
				if($fila[2]==''){$NoEgr=1;}else{$NoEgr=0;}			
				$cons3="select cups.grupo,cups.tipo,cupsxplanes.valor,cupsxplanservic.facturable
				from contratacionsalud.cupsxplanservic,contratacionsalud.cups,contratacionsalud.cupsxplanes 			
				where codigo=cupsxplanservic.cup and cupsxplanservic.cup=cupsxplanes.cup and codigo='$fila[0]' and cupsxplanes.compania='$Compania[0]' and cups.compania='$Compania[0]'
				and cupsxplanservic.compania='$Compania[0]' and cupsxplanservic.autoid=$fila2[0] and cupsxplanes.autoid=$fila2[1] and cupsxplanservic.clase='CUPS'";
				$res3=ExQuery($cons3); echo ExError(); 
				$fila3=ExFetch($res3);				
				
                $FIinicial1=explode("-",$FecIniLiq); //echo  "FecIniLiq=$FecIniLiq  ";
				$FIinicial2=explode("-",$fila[1]); //echo $fila[1]."<br>";
				
				$FI1 = mktime (0,0,0,$FIinicial1[1],$FIinicial1[2],$FIinicial1[0]); //echo "FIinicial1= $FIinicial1[0] - $FIinicial1[2] - $FIinicial1[1]  <br>\n";			
				$FI2 = mktime (0,0,0,$FIinicial2[1],$FIinicial2[2],$FIinicial2[0]); //echo "Fila[1]=$FIinicial2[0] - $FIinicial2[2] - $FIinicial2[1] <br>\n";
				
				$FFinal1=explode("-",$FecFinLiq2); 
				$FFinal2=explode("-",$fila[2]);
				
				$FF1 = mktime (0,0,0,$FFinal1[1],$FFinal1[2],$FFinal1[0]);	
				if($fila[2]){$FF2 = mktime (0,0,0,$FFinal2[1],$FFinal2[2],$FFinal2[0]);	}
				
				$FecIniEstancia="";
				$FecFinEstancia="";
				$DiasCobro="";		
				//echo "FI1=$FI1 FI2=$FI2 FF1=$FF1 FF2=$FF2<br> XXX";			
				if($FI2<=$FI1){ //echo "Si la fecha Inicial del periodo de la estancia es menor o igual a la fecha inicial Seleccionada echo $fila[2]";
					$FecIniEstancia=$FecIniLiq;
					if(empty($fila[2])){									
						$FecFinEstancia=$FecFinLiq2; //echo "caso 1 ";
					}					
					else{
						if($FF2>=$FF1){
							$FecFinEstancia=$FecFinLiq2;  //echo "caso 2 ";
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
							$FecFinEstancia=$FecFinLiq2; //echo "caso 4 ";
						}
					}		
					else{						
						if($FI2<=$FF1){						
							if($FF2>=$FF1){	
								$FecFinEstancia=$FecFinLiq2;//echo "caso 5 ";
							}
							else{
								$FecFinEstancia=$fila[2]; //echo "caso 6 ";
							}
						}
					}	
				}
				//echo "<br>".$FecIniEstancia."|".$FecFinEstancia;
				$DiasCobro=diferenciaDias($FecIniEstancia,$FecFinEstancia);					
				//$DiasCobro++;		
				//if($Num>0){$DiasCobro--;}		//Verificar con mas dias de estancia	
				//echo "$FecIniEstancia--$FecFinEstancia  DiasCobro=$DiasCobro<br>";
				if($fila3[3]==1)
				{
					$Facturable="";$Facturable1="";$Facturable2="";
				}
				else{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[2]="0";
				}
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
					$cons98="Select ";
					if($DiasCobro==31 && $AjustarDias){$DiasCobro=30;}
					if($DiasCobro>0){
					//if($DiasCobro>1){$DiasCobro--;}					
					$cons5="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds 
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";			
					$res5=ExQuery($cons5); echo ExError();
					//echo $cons2;
					if(ExNumRows($res5)>0){ //echo "acutaliza";
						$fila5=ExFetch($res5); 						
						$Cantidad=$fila5[1]+$DiasCobro;
						$VrTot=$Cantidad*$fila5[4];
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot,ambito='$Ambito'
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila[0]' and tmpcod='$TMPCOD2' and grupo='$fila3[0]' and tipo='$fila3[1]'";					
						//echo "<br>\n$cons4<br>\n";		
					}
					else{	
						$vT=$DiasCobro*$fila3[2];//echo "DiasCobro=$DiasCobro<BR>";
						$cons4="insert into facturacion.tmpcupsomeds 
						(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,fecha,finalidad,causaext,dxppal,tipodxppal,ambito $Facturable1) values 
						('$Compania[0]','$TMPCOD2','$Paciente[1]','$fila3[0]','$fila3[1]','$fila[0]','$fila[5]',$DiasCobro,$fila3[2],$vT,'$FecFinLiq2','','','','','$Ambito' $Facturable2)"; 
						//echo "<br>\n$cons4<br>\n";		
						//si no tiene grupo lo inserta pero no lo lista
					}
					$res4=ExQuery($cons4); echo ExError();}//
				}
				$FechaIniAnt=$FecIniEstancia; $FechaFinAnt=$FecFinEstancia;
			}
			
			//En caso de no cobrar del primer dia
			if($PrimDia==0){
				$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
				where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
				
				$res3=ExQuery($cons3);
				if(ExNumRows($res3)>0){
					$fila3=ExFetch($res3);
					if($fila3[1]==1){
						$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
						and grupo='$fila5[2]' and tipo='$fila5[3]'";						
					}
					else{
						$Cantidad=$fila3[1]-1;
						$VrTot=$fila3[4]*$Cantidad;
						$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
						where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
					}					
					$res4=ExQuery($cons4);
				}
			}
			//En caso de no cobrar el ultimo dia
			if($NoEgr==0){
				if($UltDia==0){			
					$cons3="select codigo,cantidad,grupo,tipo,vrund from facturacion.tmpcupsomeds
					where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";				
					
					$res3=ExQuery($cons3);
					if(ExNumRows($res3)>0){
						$fila3=ExFetch($res3);
						if($fila3[1]==1){
							$cons4="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' 
							and grupo='$fila5[2]' and tipo='$fila5[3]'";						
						}
						else{
							$Cantidad=$fila3[1]-1;
							$VrTot=$fila3[4]*$Cantidad;
							$cons4="update facturacion.tmpcupsomeds set cantidad=$Cantidad,vrtotal=$VrTot
							where compania='$Compania[0]' and cedula='$Paciente[1]' and codigo='$fila5[0]' and tmpcod='$TMPCOD2' and grupo='$fila5[2]' and tipo='$fila5[3]'";
						}					
						$res4=ExQuery($cons4);
					}
				}				
			}
		}**********/
	
	//CUPS	
	$cons5="select grupo::varchar,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]' group by grupo,codigo order by grupo ";
	$res5=ExQuery($cons5);
	?>
  <? $stn=false;
	while($fila5=ExFetch($res5)){
/*if(!$stn){
		$constn="update facturacion.tmpcupsomeds set tmpcod='$TMPCOD2'
			 where compania='$Compania[0]' and cedula='$Paciente[1]' and grupo='01'";					
	    $restn=ExQuery($constn);
		$stn=true;}*/
	    
		$cons4="select codigo,nombre,sum(cantidad),vrund,sum(vrtotal),sum(nofacturable),sum(labnointerp),cum,atc,presentacion from facturacion.tmpcupsomeds 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and tmpcod='$TMPCOD2' and grupo='$fila5[1]'
		group by codigo,nombre,vrund,cum,atc,presentacion order by codigo";			
		$res4=ExQuery($cons4);
		if(ExNumRows($res4)>0){
			$Sub=0;
			$banFac=0;
			$NumCoM=ExNumRows($res4);
  			while($fila4=ExFetch($res4)){
				//echo $cons4."<br>";	
				
				
				/*$consDev="select sum(cantidad) from consumo.movimiento,consumo.almacenesppales
			where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and tipocomprobante='Devoluciones' and noliquidacion is null 
			and almacenesppales.compania='$Compania[0]'	and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 
			and estado='AC' and movimiento.fecha>='$FecIniLiq' and movimiento.fecha<='$FecFinLiq2' and numservicio=$NumServ and autoid=".$ato[$fila4[0]]."
			group by autoid,cantidad,regmedicamento,movimiento.almacenppal,autoid,numservicio order by autoid";			
			//echo $consDev."<br>";
			$resDev=ExQuery($consDev);
			$filaDev=ExFetch($resDev);		
				while($filaDev=ExFetch($resDev))
					  $Cant=$fila4[2]-$filaDev[1];*/
				
				if($fila4[5]<=0)//Si esta como facturable
				{
					$banFac=1;
					if($fila5[0]<=0)//Si tiene grupo
					{
						if($RestricCobro&&$Rescric)
						{
							if($Rescric[$fila5[1]][0]=="Si")//Si esta configurado para ser mostrado
							{
								$BanRes=1;?>
								<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" 
								<? if($fila4[5]>0){?>style="color:#FF0000"<? }?>
                                <? if($fila4[6]>0){?> style="color:#FF0000"<? }?>
                                <? if($fila4[6]>0){?> title="Laboratorio sin interpretacion"<? }?>
                                >                                	
							<?		//echo "<td align='center'>$fila4[0]</td><td align='center'>$fila4[1]";
									echo "<td align='center'>";
							        if($fila5[1]==03||$fila5[1]==35)
								       echo $fila4[0];//$cums[$fila4[7]];
								       else echo $fila4[0];
									echo "</td><td align='center'>$fila4[1] $fila4[9], ";
								
									if($fila5[1]==03||$fila5[1]==35)//echo " - CUM ";
				                    //echo $cums[$fila4[7]]."</td>";	
									echo "CUM: ".$fila4[7]/*$cums[$fila4[7]]*/.", ATC: ".$fila4[8]/*$atc[$fila4[7]]*/;
									echo "</td>";
									
							
							//echo "<td align='center'>$fila4[0]</td><td align='center'>$fila4[1]</td>";
								if($Rescric[$fila5[1]][1]&&$Rescric[$fila5[1]][1]!="0")
								{?>									
								 <?	echo "<td align='center'>$fila4[2]</td>
									<td align='right'>".number_format("0",2)."</td>
									<td align='right'>".number_format("0",2)."</td>";
									$Sub=$Rescric[$fila5[1]][1];									
									//$Subtotal=$Subtotal+$Rescric[$fila5[1]][1];	
									$banCobGrup=$Rescric[$fila5[1]][1];										
								}
								else
								{ 
									if($Rescric[$fila5[1]][2]=="Si")
									{
										$Vrtot=number_format($fila4[4],2);//EST
										echo "<td align='center'>$fila4[2]</td><td align='right'>".number_format($fila4[3],2)."</td>
										<td align='right'>".number_format($fila4[4],2)."</td>";
										$Sub=$Sub+$fila4[4]; 
										//echo $Subtotal;
										$Subtotal=$Subtotal+$fila4[4];										
									}
									else
									{ 
									echo "<td align='center'>$fila4[2]</td><td align='right'>".number_format($fila4[3],2)."</td>
										<td align='right'>".number_format("0",2)."</td>";
										$ban3=1;
									}
								}
								?>
                                <td><img src="/Imgs/b_edit.png" style="cursor:hand" 
										<?php 
											$consCM="select * from consumo.codproductos where nombreprod1 = '$fila4[1]'";
											$resCM=ExQuery($consCM);
											if(ExNumRows($resCM)>0){
												$TipoCM="";
											}
											else{
												$TipoCM="Cup";
											}
										?>
                                        onClick="EditarCoM('<? echo $fila4[0]?>','<? echo $fila4[1]?>','<? echo $TipoCM; ?>')" title="Editar">
                                    </td>
                                    <td><img style="cursor:hand"  title="Eliminar" 
                                        onClick="if(confirm('Desea eliminar este registro?')){ElimarCupoMed('<? echo $fila4[0]?>','<? echo $fila4[1]?>')}" 
                                        src="/Imgs/b_drop.png"> 
                                    </td>
                                </tr>
					<?		}	
						}
						else
						{?>
							<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" 
							<? if($fila4[5]>0){?>style="color:#FF0000"<? }?>
							<? if($fila4[6]>0){?> style="color:#FF0000"<? }?>
							<? if($fila4[6]>0){?> title="Laboratorio sin interpretacion"<? }?>
							>
							 <?	echo "<td align='center'>";
							    if($fila5[1]==03||$fila5[1]==35)
								   echo $fila4[0];//$cums[$fila4[7]];
								else echo $fila4[0];
								echo "</td><td align='center'>$fila4[1] $fila4[9], ";
							 
									if($fila5[1]==03||$fila5[1]==35)
				                    echo "CUM: ".$fila4[7]/*$cums[$fila4[7]]*/.", ATC: ".$fila4[8]/*$atc[$fila4[7]]*/;	
							 
   							    echo "</td><td align='center'>";
								/*if($fila5[1]=='03'||$fila5[1]=='35')
								echo $fila4[9];//$Cant[$fila4[7]];
								   else*/          //KK
								   echo $fila4[2]."</td>";//uuuu$fila4[2] 
								echo "<td align='right'>".number_format($fila4[3],2)."</td>
								<td align='right'>".number_format($fila4[4],2)."</td>";
								$Sub=$Sub+$fila4[4];?>
								<td><img src="/Imgs/b_edit.png" style="cursor:hand" 
								<?php 
											 $consCM="select * from consumo.codproductos where nombreprod1 = '$fila4[1]'";
											$resCM=ExQuery($consCM);
											if(ExNumRows($resCM)>0){
												$TipoCM="";
											}
											else{
												$TipoCM="Cup";
											}
										?>
									
									onClick="EditarCoM('<? echo $fila4[0]?>','<? echo $fila4[1]?>','<? echo $TipoCM; ?>')" title="Editar">
								</td>
								<td><img style="cursor:hand"  title="Eliminar" 
									onClick="if(confirm('Desea eliminar este registro?')){ElimarCupoMed('<? echo $fila4[0]?>','<? echo $fila4[1]?>')}" 
									src="/Imgs/b_drop.png"> 
								</td>
							</tr>			  <?			
			  				$Subtotal=$Subtotal+$fila4[4];							
						}
						//echo "Subtotal=$Subtotal<br>";
					}
				}				
			}
			if($RestricCobro&&$Rescric)
			{
				if($BanRes==1)
				{
					if($banCobGrup){$Subtotal=$Subtotal+$banCobGrup; $banCobGrup="";}?>
					<tr bgcolor="#ECECEC">
						<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong><td  align="right"><? echo number_format($Sub,2)?></td>
						<td colspan="2">&nbsp;</td>
					</tr>
				<?	$BanRes="";
				}
				
			}
			else
			{
				if($banFac==1){
				?>	<tr bgcolor="#ECECEC">
						<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong><td  align="right"><? echo number_format($Sub,2)?></td>
						<td colspan="2">&nbsp;</td>
					</tr><?
				}
			}
		}
	}
	
	if($Subtotal!=''||($Subtotal==""&&$RestricCobro&&$Rescric)){?>
            <tr>
                <td colspan="4" align="right"><strong>SubTotal General:</strong></td>
                <td align="right"><? echo number_format($Subtotal,2)?></td>
                <td colspan="2">&nbsp;</td>
            </tr>
	  	<input type="hidden" name="Subtotal" value="<? echo $Subtotal?>">
<?	$Total=$Subtotal;
		$consul="select copago from contratacionsalud.contratos where entidad='$Paga' and contrato='$PagaCont' and numero='$PagaNocont'";
		$result=ExQuery($consul); 
		$row=ExFetch($result);
		$SiCopago=$row[0];
		if($SiCopago=='1'){
			$consul="select tipoasegurador from central.terceros where identificacion='$Paga' and compania='$Compania[0]' and Tipo='Asegurador'";
			//echo $consul."<br>";
			$result=ExQuery($consul);
			$row=ExFetch($result);		
			
			$consul2="select valor,clase,tipocopago,topeanual,topeevento from salud.topescopago 
			where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' and  nivelusu='$Nivelusu' and ambito='$Ambito'";				
			$result2=ExQuery($consul2); $fil=ExFetch($result2);
			$Tipocopago=$fil[2];
			$ClaseCopago=$fil[1];	
			if($fil[1]=='Fijo'){
				$Valorcopago=$fil[0]; $Porsentajecopago="0";			
			}
			else{			
				$Valorcopago=($fil[0]/100)*$Total; 
				
				$consul3="select sum(valorcopago) from facturacion.liquidacion where cedula='$Paciente[1]' and compania='$Compania[0]' 
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
					if($Valorcopago>$fil[4]){
						$Valorcopago=$fil[4];
						$BanRecal=1;
					}
					else{$BanRecal=0;}
				}
				else{
					/*if(($Valorcopago+$CopAcumulado)>$Tope){
						$Valorcopago=$Tope-$CopAcumulado;
						$BanRecal=1;
					}*/
					if($Valorcopago>$fil[4]){
						$Valorcopago=$fil[4];
						$BanRecal=1;
					}
					else{$BanRecal=0;}
				}
				
				$Porsentajecopago=$fil[0];	
			}	
			$Total=$Total-$Valorcopago;
		}?>
        <input type="hidden" name="Tipocopago" value="<? echo $Tipocopago?>">
        <input type="hidden" name="Valorcopago" value="<? echo $Valorcopago?>">
        <input type="hidden" name="Porsentajecopago" value="<? echo $Porsentajecopago?>">
        <input type="hidden" name="ClaseCopago" value="<? echo $ClaseCopago?>">
   	<?	if($RestricCobro&&$Rescric){?>
        	<input type="hidden" name="RestricCobroV" value="1">
  	<?	}?>
        <tr>
            <td colspan="2" align="right"><strong>Porcentaje Copago: </strong></td>
            <td align="center" <? if($BanRecal==1){ echo "Title='Valor del Copago Recalculado'";}?>><? if($BanRecal==1){ echo "<font color='#FF0000'>";} echo $Porsentajecopago?>% <? if($BanRecal==1){ echo "</font>";}?></td>
            <td align="right"><strong>Vr Copago</strong></td><td align="right"><? echo number_format($Valorcopago,2)?></td>
            <td colspan="2">&nbsp;</td>
        </tr>
    <?	if(!$NoLiquidacion){
			$consul3="select usuario from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion is null";
		}
		else{
			$consul3="select usuario from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion=$NoLiquidacion";
			$result3=ExQuery($consul3);
			if(ExNumRows($result3)<=0){
				$consul3="select usuario from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion is null";
				//$result3=ExQuery($consul3);
			}
		}
		//echo $consul3;
		$result3=ExQuery($consul3);
		if(ExNumRows($result3)>0){
			if($Porsentajedesc==''){$Porsentajedesc="0";}?>    
    	    <tr>
        		<td colspan="2" align="right"><strong>Porcentaje Descuento: </strong></td>
            	<td align="center">
            		<input type="text" onKeyDown="xNumero(this)" onKeyUp="xNumero(this);Calcular('<? echo $Total?>');" style="width:30px; text-align:center"
	                maxlength="2" name="Porsentajedesc" value="<? echo $Porsentajedesc?>">%</td>
    	 	<?		$Valordescuento=($Porsentajedesc/100)*$Total;
					$Total=$Total-$Valordescuento;?>
            	<td align="right"><strong>Vr Descuento</strong></td>
            	<td align="right"><input type="text" readonly name="Valordescuento"  style="text-align:right; width:100px" value="<? echo round($Valordescuento,2)?>"></td>
	            <td colspan="2">&nbsp;</td>
    	  	</tr>   
   	<?	}?>   
  			<tr>
    			<td colspan="4" align="right"><strong>Total:</strong></td>
	    		<td align="right"><input type="text" readonly name="Total"  style="text-align:right; width:100px" value="<? echo round($Total,2)?>"></td>
    			<td colspan="2">&nbsp;</td>
  			</tr><?		
	}?>
  <tr align="center">
    <td colspan="8"><input type="button" value="Guardar" onClick="PreGuarda()">
      <input type="button" value="Cancelar" onClick="salir()"></td>
  </tr>

  </table> 
  <input type="hidden" name="Guardar">
  <input type="hidden" name="FecIniLiq" value="<? echo $FecIniLiq?>">
  <input type="hidden" name="FecFinLiq" value="<? echo $FecFinLiq?>">
    <input type="hidden" name="FecFinLiq2" value="<? echo $FecFinLiq2?>">
  <input type="hidden" name="ban3" value="<? echo $ban3?>">
  <input type="hidden" name="CodCoM" value="">
  <input type="hidden" name="NomCoM" value="">
  <input type="hidden" name="PagaCoM" value="">
  <input type="hidden" name="PagaContCoM" value="">
  <input type="hidden" name="PagaNocontCoM" value="">
  <input type="hidden" name="VerPagador" value="<? echo $VerPagador?>">
  <input type="hidden" name="NumServ" value="<? echo $NumServ?>">
  <input type="hidden" name="TipoServcio" value="<? echo $TipoServcio?>">
  <input type="hidden" name="Insertar" value="">
  <input type="hidden" name="Guarda" value="">
  <input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
  <input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
  <input type="hidden" name="Eliminar" value="">
  <input type="hidden" name="EliminarCoM" value="">
  <input type="hidden" name="Ini" value="">
  <input type="hidden" name="EPS" value="">
  <input type="hidden" name="Contra" value="">
  <input type="hidden" name="NoContra" value="">
  <input type="hidden" name="TipoNuevo" value="">
  <input type="hidden" name="Cancelar" value="">
  <input type="hidden" name="NoLiquidacion" value="<? echo $NoLiquidacion?>">
  <input type="hidden" name="BanCargarHC" value="<? echo $BanCargarHC?>">
  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
  <input type="hidden" name="NoEnvia" value="">
</form>
</body>
</html>

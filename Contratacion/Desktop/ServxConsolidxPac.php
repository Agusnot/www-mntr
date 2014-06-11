<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
	if($CodElim)
	{
		$cons="delete from facturacion.tmpcupsomeds where compania='$Compania[0]'
		and tmpcod='$TMPCOD2' and cedula='$CedPac' and codigo='$CodElim'";
		//echo $cons;
		$res=ExQuery($cons);
	}
	if($Facturar)
	{
		$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $AutoId=$fila[0]+1;
		$cons="delete from facturacion.detalleliquidacion where compania='$Compania[0]' and noliquidacion=$NoLiq";
		$res=ExQuery($cons);
		//echo $cons."<br>";
		$cons="select grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,fecha
		,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal,ambito
		,formarealizacion,nofacturable	from facturacion.tmpcupsomeds where compania='$Compania[0]'
		and tmpcod='$TMPCOD2' and cedula='$CedPac'";
		//echo $cons."<br>";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){				
			while($fila=ExFetch($res)){
				if($fila[21]!=1){$fila[21]="0";}
				if(!$fila[19]){$fila[19]="$Amb";}
				if($fila[1]!="Medicamentos"){	
					if($fila[11]==''){$fila[11]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}								
					$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
					,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal,ambito,formarealizacion,nofacturable) 
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','$fila[1]',
					'$fila[2]','$fila[3]',$fila[4],$fila[5],$fila[6],$NoLiq,'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$fila[16]'
					,'$fila[17]','$fila[18]','$fila[19]','$fila[20]',$fila[21])";
					//echo $cons2."<br>";
				}
				else{						
					$cons2="insert into facturacion.detalleliquidacion 					(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) values 
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','Medicamentos','$fila[2]','$fila[3]',
					$fila[4],$fila[5],$fila[6],'$fila[7]','$fila[8]','$fila[9]','$fila[10]',$NoLiq,$fila[21])";						
				}					
				$SubTotal=$SubTotal+$fila[6];
				$res2=ExQuery($cons2); echo ExError();
				//echo "<br>$cons2";				
				$cons2="insert into facturacion.detallefactura 
				(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,nofactura,generico,presentacion,forma,almacenppal) values
				('$Compania[0]','$usuari[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','$fila[1]','$fila[2]','$fila[3]'
				,$fila[4],$fila[5],$fila[6],$AutoId,'$fila[7]','$fila[8]','$fila[9]','$fila[10]')";
				//echo "<br>$cons2";
				$res2=ExQuery($cons2);				
			}
		}
		if(!$Valordescuento){$Valordescuento="0";}if(!$VrCopato){$VrCopato="0";}
		$Total=$SubTotal-($Valordescuento+$VrCopato);

		$cons5="update facturacion.liquidacion set nofactura=$AutoId,subtotal=$SubTotal,total=$Total
		where compania='$Compania[0]' and noliquidacion=$NoLiq";
		//echo "<br>$cons5<br>";
		$res5=ExQuery($cons5);
		
		$cons5="insert into facturacion.facturascredito 			
		(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,estado,individual)				
		values('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]','$PagaM','$ContraM','$NoContraM','$Ambito',$SubTotal,$VrCopato
		,$Valordescuento,$Total,$AutoId,'AC',1)";
		//echo $cons5."<br>";
		$res5=ExQuery($cons5);
		$cons5="update salud.servicios set estado='AN' where compania='$Compania[0]' and numservicio=$NumServM";
		$res5=ExQuery($cons5);
		$cons="delete from facturacion.tmpcupsomeds where tmpcod='$TMPCOD2' and compania='$Compania[0]' and cedula='$CedPac'";			
		$res=ExQuery($cons);?>
  		<script language="javascript">			
			open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoId?>&Estado=<? echo 'AC'?>&Impresion=<? echo $Impresion?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1');
			location.href='PacientesxConsolid.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&CedPac=<? echo $CedPac?>';
		</script>
<?	}
	if($CargarEnLiq)
	{
		$cons="select usuario,fechacrea,noliquidacion,subtotal,total 
		from facturacion.liquidacion where compania='$Compania[0]' and numservicio=$NumServM and cedula='$CedPac'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $Mins=explode(" ",$fila[1]); 
		$SubT=0;
		$cons1 = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos 
		where Entidad='$PagaM' and contrato='$ContraM' and Compania='$Compania[0]' and numero='$NoContraM'";						
		$res1 = ExQuery($cons1); 
		$fila1=ExFetch($res1);
		//Medicamentos
		$cons5="select plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria
		from salud.plantillamedicamentos,consumo.codproductos
		where plantillamedicamentos.compania='$Compania[0]' and autoidprod=autoid
		and cedpaciente='$CedPac' and codproductos.compania='$Compania[0]' 
		and codproductos.almacenppal=plantillamedicamentos.almacenppal
		and numservicio=$NumServM
		and codigo1 not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion where 
		liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion)
		group by plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria
		order by detalle";
		$res5=ExQuery($cons5);
		//echo $cons5."<br>";
		while($fila5=ExFetch($res5))
		{
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1 
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila5[0]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila5[1]'
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";
			//echo $cons3."<br>";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$fila1[0]' and almacenppal='$fila5[0]' and codigo='$fila5[1]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);		
			$filaMedsxPlan=ExFetch($resMedsxPlan);
			//echo $consMedsxPlan."<br>";
			if($filaMedsxPlan[0]){
				if($filaMedsxPlan[1]==1)
				{
					$Facturable="";$Facturable1=",nofacturable";$Facturable2=",0";
				}
				else
				{ 
					$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $fila3[5]=0;
				}			
				$cons4="select grupofact from consumo.grupos where compania='$Compania[0]' and grupo='$fila3[0]' and almacenppal='$fila5[0]'
				and Anio=$ND[year]";
				$res4=ExQuery($cons4);
				$fila4=ExFetch($res4);	
				//echo $cons4."<br>";							
				//Cantidad
				$Cantidad=round($Cantidad);
				$VrTot=round($fila5[3]*$fila3[5]);
				 
				$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
				,noliquidacion,generico,presentacion,almacenppal,ambito $Facturable1) 
				values ('$Compania[0]','$FechaIni $Mins[1]','$fila[1]','$fila4[0]','Medicamentos'
				,'$fila5[1]','$fila3[2]',$fila5[3],$fila3[5],$VrTot,$fila[2],'$fila3[2]','$fila3[3]','$fila5[0]','$Ambito' $Facturable2)";	
				//echo $cons2."<br>";
				$res2=ExQuery($cons2);
				$SubTotal=$SubTotal+$VrTot;							
			}						
		}
		//Cups de los formatos de historia clinica
		$cons2="select planbeneficios,plantarifario from contratacionsalud.contratos 
		where entidad='$PagaM' and contrato='$ContraM' and numero='$NoContraM' and compania='$Compania[0]'";			
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);	$PlanB=$fila2[0]; $PlanT=$fila2[1];
		
		$cons2="select tblformat,formato,tipoformato,laboratorio from historiaclinica.formatos 
		where estado='AC' and compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			$cons4="select cup,nombre,$fila2[0].id_historia,fecha,dx1,dx2,dx3,dx4,dx5,tipodx,finalidadconsult,causaexterna,formarealizacion,hora 
			from histoclinicafrms.$fila2[0],histoclinicafrms.cupsxfrms,contratacionsalud.cups
			where $fila2[0].compania='$Compania[0]' 
			and $fila2[0].cedula='$CedPac' and $fila2[0].numservicio=$NumServM and $fila2[0].noliquidacion=0 
			and cups.compania='$Compania[0]' and cups.codigo=cup
			and cupsxfrms.compania='$Compania[0]' and cupsxfrms.id_historia=$fila2[0].id_historia and $fila2[0].formato='$fila2[1]' 
			and $fila2[0].tipoformato='$fila2[2]' and $fila2[0].formato=cupsxfrms.formato and $fila2[0].tipoformato=cupsxfrms.tipoformato
			and cupsxfrms.cedula='$CedPac' and cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
			where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
			and liquidacion.noliquidacion=detalleliquidacion.noliquidacion) order by cup";
			$res4=ExQuery($cons4);			
			while($fila4=ExFetch($res4))
			{
				$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila4[0]' and autoid=$PlanT";												
				$resVr=ExQuery($consVr);
				$filaVr=ExFetch($resVr);
				//echo $consVr."<br>";
				$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
				where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila4[0]' and cups.compania='$Compania[0]' 
				and cups.codigo=cup and autoid=$PlanB";
				$res3=ExQuery($cons3); 
				$fila3=ExFetch($res3);	
				if($fila3[3]==1)//Si es facturble
				{	$Facturable="";$Facturable1=",nofacturable";$Facturable2=",0";	}
				else
				{	$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $filaVr[0]="0";		}
															
				$vT=$filaVr[0];	if($vT==''){$vT="0";}	if($filaVr[0]==''){$filaVr[0]="0";}	if($fila3[1]==''){$fila3[1]="012";}
				
				if($fila[3]){//Si es de tipo laboratorio se verifica si ha sido interpretado o no
					$consADx="select interpretacion from histoclinicafrms.ayudaxformatos where compania='$Compania[0]' and formato='$fila2[1]' 
					and tipoformato='$fila2[2]'	and cedula='$CedPac' and numservicio=$NumServM and id_historia=$fila4[2]";
					$resADx=ExQuery($consADx);
					if(ExNumRows($resADx)<=0){		$IntLab=",labnointerp=1";	$IntLab1=",labnointerp";	$IntLab2=",1";	}
				}
				if($fila3[0]==''){$filaVr[0]="0";}
				
				$cons6="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
				,noliquidacion,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,tipodxppal,ambito,formarealizacion 
				$Facturable1) values ('$Compania[0]','$fila[0]','$FechaIni $Mins[1]','$fila3[0]','$fila3[1]','$fila4[0]','$fila4[1]',1
				,$filaVr[0],$filaVr[0],$fila[2],'$fila4[3] $fila4[13]','$fila4[10]','$fila4[11]','$fila4[4]','$fila4[5]','$fila4[6]'
				,'$fila4[7]','$fila4[8]','$fila4[9]','$Ambito','$fila4[12]' $Facturable2)";
				//echo $cons6."<br>";
				$res6=ExQuery($cons6);
				$SubTotal=$SubTotal+$filaVr[0];	
			}
		}
		//Cups de plantilla de Procedimientos
		$cons4="select cup,nombre,interpretacion,fechaini,fechafin,finproced,diagnostico,causaexterna,tipodx 
		from salud.plantillaprocedimientos,contratacionsalud.cups
		where plantillaprocedimientos.compania='$Compania[0]' and cups.compania='$Compania[0]' and cup=cups.codigo and numservicio=$NumServM
		and cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
		where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion)";
		$res4=ExQuery($cons4);
		//echo $cons4;
		while($fila4=ExFetch($res4))
		{
			$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila4[0]' and autoid=$PlanT";												
			$resVr=ExQuery($consVr);
			$filaVr=ExFetch($resVr);
			//echo $consVr."<br>";
			$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
			where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila4[0]' and cups.compania='$Compania[0]' 
			and cups.codigo=cup and autoid=$PlanB";
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);	
			if($fila3[3]==1)//Si es facturble
			{	$Facturable="";$Facturable1=",nofacturable";$Facturable2=",0";	}
			else
			{	$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $filaVr[0]="0";		}
														
			$vT=$filaVr[0];	if($vT==''){$vT="0";}	if($filaVr[0]==''){$filaVr[0]="0";}	if($fila3[1]==''){$fila3[1]="012";}
					
			if($fila3[0]==''){$filaVr[0]="0";}				
			
			$cons6="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
			,noliquidacion,causaext,dxppal,tipodxppal,ambito $Facturable1) values ('$Compania[0]','$fila[0]','$FechaIni $Mins[1]','$fila3[0]','$fila3[1]','$fila4[0]','$fila4[1]',1
			,$filaVr[0],$filaVr[0],$fila[2],'$fila4[7]','$fila4[6]','$fila4[8]','$Ambito' $Facturable2)";
			//echo $cons6."<br>";
			$res6=ExQuery($cons6);
			$SubTotal=$SubTotal+$filaVr[0];	
		}
		
		//Odontologia		
		$cons2="select odontogramaproc.cup,cups.nombre,fecha,diagnostico1,diagnostico2,diagnostico3,diagnostico4,diagnostico5,finalidadprocedimiento
		,formarealizacion from odontologia.odontogramaproc,odontologia.procedimientosimgs,contratacionsalud.cups
		where identificacion='$CedPac' and numservicio=$NumServM 
		and odontogramaproc.compania='$Compania[0]' and procedimientosimgs.cup=odontogramaproc.cup 
		and procedimientosimgs.compania='$Compania[0]' and diagnostico1 IS DISTINCT FROM ''
		and cups.compania='$Compania[0]'and cups.codigo=odontogramaproc.cup 
		and odontogramaproc.cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
		where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion)";
		//echo $cons2."<br>";
		$res2=ExQuery($cons2);
		while($fila2=ExFetch($res2))
		{
			$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila2[0]' and autoid=$PlanT";												
			$resVr=ExQuery($consVr);
			$filaVr=ExFetch($resVr);
			//echo $consVr."<br>";
			$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
			where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila2[0]' and cups.compania='$Compania[0]' 
			and cups.codigo=cup and autoid=$PlanB";	
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
			if($fila3[3]==1)//Si es facturble
			{	$Facturable="";$Facturable1=",nofacturable";$Facturable2=",0";	}
			else
			{	$Facturable=",nofacturable=1"; $Facturable1=",nofacturable"; $Facturable2=",1"; $filaVr[0]="0";		}
														
			$vT=$filaVr[0];	if($vT==''){$vT="0";}	if($filaVr[0]==''){$filaVr[0]="0";}	if($fila3[1]==''){$fila3[1]="012";}
			$cons6="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
			,noliquidacion,fechainterpret,finalidad,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,ambito,formarealizacion 
			$Facturable1) values ('$Compania[0]','$fila[0]','$FechaIni $Mins[1]','$fila3[0]','$fila3[1]','$fila2[0]','$fila2[1]',1
			,$filaVr[0],$filaVr[0],$fila[2],'$fila2[2] $Mins[1]','$fila2[8]','$fila2[3]','$fila2[4]','$fila2[5]','$fila2[6]'
			,'$fila2[7]','$Ambito','$fila2[8]' $Facturable2)";
			//echo $cons6."<br>";
			$res6=ExQuery($cons6);
			$SubTotal=$SubTotal+$filaVr[0];	
		}	
		$SubT=$fila[3]+$SubTotal;
		$Tot=$fila[4]+$SubTotal;
		$cons2="update facturacion.liquidacion set subtotal=$SubT,total=$Tot
		where compania='$Compania[0]' and numservicio=$NumServM and cedula='$CedPac'";
		//echo $cons2;
		$res2=ExQuery($cons2);
		$CargarEnLiq="";
	
		
		$cons="select pagador,contrato,nocontrato,valordescuento,porsentajedesc,fechaini,fechafin ,valorcopago
		from facturacion.liquidacion 
		where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion=$NoLiq";
		//echo $cons;
		$res=ExQuery($cons);
		$row=ExFetch($res);
		$Valordescuento=$row[3];$Porsentajedesc=$row[4];$FecIniLiq=$row[5];$FecFinLiq2=$row[6]; $VrCopato=$row[7];
		
		$cons="select grupo,tipo,codigo,nombre,sum(cantidad),vrunidad,vrunidad,generico,presentacion,forma,almacenppal from facturacion.detalleliquidacion 
		where compania='$Compania[0]' and noliquidacion=$NoLiq group by grupo,tipo,codigo,nombre,vrunidad,generico,presentacion,forma,almacenppal";
		$res=ExQuery($cons);
		//echo $cons;
		if(ExNumRows($res)>0){
			$TMPCOD2=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			while($fila=ExFetch($res)){
				if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];}
				$VrTot=$fila[4]*$fila[5];
				$cons2="insert into facturacion.tmpcupsomeds 
					(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenPpal) values			('$Compania[0]','$TMPCOD2','$CedPac','$fila[0]','$fila[1]','$fila[2]','$fila[3]',$fila[4],$fila[5],$VrTot,'$fila[7]','$fila[8]','$fila[9]','$fila[10]')";										
				//echo $cons2;
				$res2=ExQuery($cons2); 
			}
		}
	}
	$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$PagaM'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NomAseg=$fila[0]." ".$fila[1]." ".$fila[2]." ".$fila[2];
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr  bgcolor="#e5e5e5" align="center">
	<td colspan="11"><strong> <? echo "$NomPac - Identificacion: $CedPac"?></strong></td>
</tr>
<tr  bgcolor="#e5e5e5" align="center">
	<td colspan="11">
    	<strong>Asegurador: </strong><? echo $NomAseg?> <strong>Cotrato: </strong><? echo $ContraM?> <strong>No. Contrato: </strong><? echo $NoContraM?></td>
</tr>
<tr><td colspan="11" align="center">
		<input type="button" value="Agregar Medicamentos"
        onClick="location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&FecIniLiq=<? echo $FechaIni?>&FecFinLiq2=<? echo $FechaIni?>&TipoNuevo=Medicamento'">
        
        <input type="button" value="Agregar CUPS" 
        onClick="location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&FecIniLiq=<? echo $FechaIni?>&FecFinLiq2=<? echo $FechaIni?>&TipoNuevo=Cup'">
	</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
	<td>Codigo</td><td>Nombre</td><td>Cantidad</td><td>Vr Unidad</td><td>Vr Total</td><td colspan="2"></td>
</tr>
<?
	$cons="select codigo,nombre,cantidad,vrund,vrtotal from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD2'
	and cedula='$CedPac' order by nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
    	<tr>
        	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td align="right"><? echo $fila[2]?></td>
            <td align="right"><? echo number_format($fila[3],2)?></td><td align="right"><? echo number_format($fila[4],2)?></td>
            <td><img src="/Imgs/b_edit.png" title="Editar" style="cursor:hand"></td>
            <td>
            	<img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand"
                onClick="if(confirm('¿Desea eliminar este resgistro?')){location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD?>&TMPCOD2=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&ElimElto=1&CodElim=<? echo $fila[0]?>';}">
          	</td>
        </tr>
<?		$Total=$Total+$fila[4];
	}?>
<tr>
	<td align="right" colspan="4"><strong>Total</strong></td><td align="right"><? echo number_format($Total,2)?></td>
</tr>
<tr align="center">
    <td colspan="11">
        <input type="submit" name="Facturar" value="Facturar">
    </td>
</tr>
</table>

<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServM" value="<? echo $NumServM?>">
<input type="hidden" name="NoLiq" value="<? echo $NoLiq?>">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="PagaM" value="<? echo $PagaM?>">
<input type="hidden" name="ContraM" value="<? echo $ContraM?>">
<input type="hidden" name="NoContraM" value="<? echo $NoContraM?>">
<input name="CargarEnLiq" type="hidden" value="<? echo $CargarEnLiq?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
<input type="hidden" name="Valordescuento" value="<? echo $Valordescuento?>">
<input type="hidden" name="VrCopato" value="<? echo $VrCopato?>">
<input type="hidden" name="FechaIni" value="<? echo $FechaIni?>">

</form>
</body>
</html>
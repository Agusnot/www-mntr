<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Total=0;
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	$cons="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by grupo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsCUPs[$fila[1]]=array($fila[0],$fila[1]);
	}
	$cons="select almacenppal from consumo.almacenesppales where compania='$Compania[0]' and ssfarmaceutico=1";
	$res=ExQuery($cons);
	$banAlmacen=0;
	while($fila=ExFetch($res)){
		if($banAlmacen==0){	$Almacenes="and almacenppal in ('$fila[0]'";$banAlmacen=1;}
		else{$Almacenes=$Almacenes.",'$fila[0]'";}
	}
	$Almacenes=$Almacenes.")";
	$cons="select autoid,pos from consumo.codproductos where compania='$Compania[0]' $Almacenes";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$PosCodProds[$fila[0]]=$fila[1];		
	}
	if($CodElim)
	{
		$cons="delete from facturacion.tmpcupsomeds where compania='$Compania[0]'
		and tmpcod='$TMPCOD2' and cedula='$CedPac' and codigo='$CodElim'";
		//echo $cons;
		$res=ExQuery($cons); $res=ExQuery($cons);
		$PlanTarfCups=$fila[0]; $PlanServCups=$fila[1]; $PlanTarifMeds=$fila[2]; $PlanServMeds=$fila[3]; $CobraCuota=$fila[4];
	}
	if($GuardarLiq)
	{
		$cons="delete from facturacion.detalleliquidacion where compania='$Compania[0]' and noliquidacion=$NoLiq";
		$res=ExQuery($cons);
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
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];} 
					if($fila[11]==''){$fila[11]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}								
					$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
					,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal,ambito,formarealizacion,nofacturable) 
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','$fila[1]',
					'$fila[2]','$fila[3]',$fila[4],$fila[5],$fila[6],$NoLiq,'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$fila[16]'
					,'$fila[17]','$fila[18]','$fila[19]','$fila[20]',$fila[21])";
					
				}
				else{						
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];} 
					$cons2="insert into facturacion.detalleliquidacion 					(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) values 
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','Medicamentos','$fila[2]','$fila[3]',
					$fila[4],$fila[5],$fila[6],'$fila[7]','$fila[8]','$fila[9]','$fila[10]',$NoLiq,$fila[21])";						
					//echo $cons2."<br>";
				}					
				$SubTotal=$SubTotal+$fila[6];
				$res2=ExQuery($cons2); echo ExError();
				//echo "<br>$cons2";									
			}
		}
		if(!$Valordescuento){$Valordescuento="0";}
		if(!$VrCopato){$VrCopato="0";}
		if($Furips){$Furips="1";}else{$Furips="";}
		if($Parto){$Part=",parto='$Parto'";}else{$Part=",parto=NULL";}
		$cons5="update facturacion.liquidacion 
		set subtotal=$SubTotal,total=$Total,ambito='$Ambito',pagador='$PagaM',contrato='$ContraM',nocontrato='$NoContraM'
		,valordescuento='$Valordescuento',valorcopago='$VrCopato',tipofactura='$TipoFac',formatofurips='$Furips' $Part
		where compania='$Compania[0]' and noliquidacion=$NoLiq";
		//echo "<br>$cons5<br>";
		$res5=ExQuery($cons5);
		$cons5="update salud.servicios set tiposervicio='$Ambito' where compania='$Compania[0]' and numservicio=$NumServM";
		$res5=ExQuery($cons5);		
		$cons5="update salud.pagadorxservicios set entidad='$PagaM',contrato='$ContraM',nocontrato='$NoContraM' where numservicio=$NumServM
		and compania='$Compania[0]' and tipo=1";
		$res5=ExQuery($cons5);
		if(!$Parto){
			$cons="delete from salud.partos where compania='$Compania[0]' and idmadre='$CedPac' and noliq=$NoLiq";
			$res=ExQuery($cons);
		}
		?>
        	<script language="javascript">
				alert("Los cambios se han guardado en la liquidacion!!!");
			</script>
        <?
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
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];} 
					if($fila[11]==''){$fila[11]="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";}								
					$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion
					,fechainterpret,finalidad,causaext,dxppal,dxrel1,dxrel2,dxrel3,tipodxppal,ambito,formarealizacion,nofacturable) 
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','$fila[1]',
					'$fila[2]','$fila[3]',$fila[4],$fila[5],$fila[6],$NoLiq,'$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$fila[16]'
					,'$fila[17]','$fila[18]','$fila[19]','$fila[20]',$fila[21])";
					
				}
				else{						
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];} 
					$cons2="insert into facturacion.detalleliquidacion 					(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenPpal,noliquidacion,nofacturable) values 
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila[0]','Medicamentos','$fila[2]','$fila[3]',
					$fila[4],$fila[5],$fila[6],'$fila[7]','$fila[8]','$fila[9]','$fila[10]',$NoLiq,$fila[21])";						
					//echo $cons2."<br>";
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
  		if($Furips){$Furips="1";}else{$Furips="";}
		if($Parto){$Part=",parto='1'";}else{$Part=",parto=NULL";}
		$cons5="update facturacion.liquidacion 
		set nofactura=$AutoId,subtotal=$SubTotal,total=$Total,ambito='$Ambito',pagador='$PagaM',contrato='$ContraM',nocontrato='$NoContraM'
		,valordescuento='$Valordescuento',valorcopago='$VrCopato',tipofactura='$TipoFac',formatofurips='$Furips' $Part
		where compania='$Compania[0]' and noliquidacion=$NoLiq";
		//echo "<br>$cons5<br>";
		$res5=ExQuery($cons5);
		
		$cons5="insert into facturacion.facturascredito 			
		(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,estado,individual,tipofactura)				
		values('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday]','$ND[year]-$ND[mon]-$ND[mday]','$PagaM','$ContraM','$NoContraM','$Ambito',$SubTotal,$VrCopato
		,$Valordescuento,$Total,$AutoId,'AC',1,'$TipoFac')";
		//echo $cons5."<br>";
		$res5=ExQuery($cons5);
		$cons6="select consultaextern from salud.ambitos where compania='$Compania[0]' and ambito='$Ambito'";
		$res6=ExQuery($cons6); $fila6=ExFetch($res6);
		if($fila6[0]==1){$CambEst=",estado='AN'";}else{$CambEst="";}
		//$cons5="update salud.servicios set  where compania='$Compania[0]' and numservicio=$NumServM";
		$cons5="update salud.servicios set tiposervicio='$Ambito' $CambEst where compania='$Compania[0]' and numservicio=$NumServM";
		$res5=ExQuery($cons5);
		
		$cons="delete from facturacion.tmpcupsomeds where tmpcod='$TMPCOD2' and compania='$Compania[0]' and cedula='$CedPac'";			
		$res=ExQuery($cons);
		$cons5="update salud.pagadorxservicios set entidad='$PagaM',contrato='$ContraM',nocontrato='$NoContraM' where numservicio=$NumServM
		and compania='$Compania[0]' and tipo=1";
		$res5=ExQuery($cons5);
		if(!$Parto){
			$cons="delete from salud.partos where compania='$Compania[0]' and idmadre='$CedPac' and noliq=$NoLiq";
			$res=ExQuery($cons);
		}?>
  		<script language="javascript">			
			open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoId?>&Estado=<? echo 'AC'?>&Impresion=<? echo $Impresion?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1');
			location.href='PacientesxConsolid.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>&CedPac=<? echo $CedPac?>';
		</script>
<?		
	}	
	if($CargarEnLiq)
	{
		if($NoLiq=="DebeCrear")
		{
			$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";	
			$res=ExQuery($cons);
			$fila=ExFetch($res); $NoLiq=$fila[0]+1;
			 		 
			$cons="select tiposervicio,medicotte,fechaegr,tipousu,nivelusu,autorizac1 from salud.servicios where compania='$Compania[0]'  
			and numservicio=$NumServM"; $res=ExQuery($cons); $DatoServ=ExFetch($res);
			if(!$DatoServ[2]){$DatoServ[2]="$ND[year]-$ND[mon]-$ND[mday]";}
			
			$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechafin,fechaini,tipousu,nivelusu,autorizac1
			,pagador,contrato,nocontrato,noliquidacion,numservicio,cedula) values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$DatoServ[0]','$DatoServ[1]','$DatoServ[2]','$FechaIni','$DatoServ[3]','$DatoServ[4]','$DatoServ[5]'
			,'$PagaM','$ContraM','$NoContraM',$NoLiq,$NumServM,'$CedPac')";
			//echo "crea ".$cons;			
			$res=ExQuery($cons);
		}
		$cons="select usuario,fechacrea,noliquidacion,subtotal,total 
		from facturacion.liquidacion where compania='$Compania[0]' and numservicio=$NumServM and cedula='$CedPac'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $Mins=explode(" ",$fila[1]); 
		$SubT=0;
		$cons1 = "Select PlanServMeds,plantarifameds from ContratacionSalud.Contratos 
		where Entidad='$PagaM' and contrato='$ContraM' and Compania='$Compania[0]' and numero='$NoContraM'";
		//echo $cons1;
		$res1 = ExQuery($cons1); 
		$fila1=ExFetch($res1);

		//Medicamentos
		$cons5="select plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria,codproductos.codigo1
		from salud.plantillamedicamentos,consumo.codproductos
		where plantillamedicamentos.compania='$Compania[0]' and autoidprod=autoid
		and cedpaciente='$CedPac' and codproductos.compania='$Compania[0]' 
		and codproductos.almacenppal=plantillamedicamentos.almacenppal
		and numservicio=$NumServM
		and autoid not in (select cast(codigo as int) from facturacion.detalleliquidacion,facturacion.liquidacion where 
		liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion and liquidacion.noliquidacion=$NoLiq and tipo='Medicamentos' and estado='AC'
		and nofactura is null)
		group by plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria,codproductos.codigo1
		order by detalle";
		/*$cons5="select plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria,codproductos.codigo1
		from salud.plantillamedicamentos,consumo.codproductos
		where plantillamedicamentos.compania='$Compania[0]' and autoidprod=autoid
		and cedpaciente='$CedPac' and codproductos.compania='$Compania[0]' 
		and codproductos.almacenppal=plantillamedicamentos.almacenppal
		and numservicio=$NumServM		
		group by plantillamedicamentos.almacenppal,autoidprod,detalle,cantdiaria,codproductos.codigo1
		order by detalle";*/
		$res5=ExQuery($cons5);
		//echo $cons5."<br><br>";
		while($fila5=ExFetch($res5))
		{
			$cons3 = "Select grupo,CodProductos.tipoproducto,NombreProd1,UnidadMedida,Presentacion,valorventa,codigo1 
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$fila1[1]' 
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='$fila5[0]' and CodProductos.Anio=$ND[year] and CodProductos.autoid='$fila5[1]'
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";
			//echo $cons3."<br><br>";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);
			$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
			where compania='$Compania[0]' and autoid='$fila1[0]' and almacenppal='$fila5[0]' and codigo='$fila5[4]'";
			$resMedsxPlan=ExQuery($consMedsxPlan);		
			$filaMedsxPlan=ExFetch($resMedsxPlan);
			//echo $consMedsxPlan."<br><br>";
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
				//echo $cons4."<br><br>s";							
				//Cantidad
				$Cantidad=round($Cantidad);
				$VrTot=round($fila5[3]*$fila3[5]);
				$cons="select from facturacion.detalleliquidacion";
				$cons2="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
				,noliquidacion,generico,presentacion,almacenppal,ambito,forma $Facturable1) 
				values ('$Compania[0]','$FechaIni $Mins[1]','$fila[1]','$fila4[0]','Medicamentos'
				,'$fila5[1]','$fila3[2]',$fila5[3],$fila3[5],$VrTot,$NoLiq,'$fila3[2]','$fila3[4]','$fila5[0]','$Ambito','$fila3[3]' $Facturable2)";	
				//echo "<br>".$cons2."<br>";
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
			$cons4="select cup,nombre,$fila2[0].id_historia,fecha,dx1,dx2,dx3,dx4,dx5,tipodx,finalidadproced,causaexterna,formarealizacion,hora 
			from histoclinicafrms.$fila2[0],histoclinicafrms.cupsxfrms,contratacionsalud.cups
			where $fila2[0].compania='$Compania[0]' 
			and $fila2[0].cedula='$CedPac' and $fila2[0].numservicio=$NumServM and $fila2[0].noliquidacion=0 
			and cups.compania='$Compania[0]' and cups.codigo=cup
			and cupsxfrms.compania='$Compania[0]' and cupsxfrms.id_historia=$fila2[0].id_historia and $fila2[0].formato='$fila2[1]' 
			and $fila2[0].tipoformato='$fila2[2]' and $fila2[0].formato=cupsxfrms.formato and $fila2[0].tipoformato=cupsxfrms.tipoformato
			and cupsxfrms.cedula='$CedPac' and cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
			where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
			and liquidacion.noliquidacion=detalleliquidacion.noliquidacion and estado='AC'
			and nofactura is null) order by cup";
			$res4=ExQuery($cons4);			
			//echo $cons4;
//if($fila2[0]=='tbl00036'){echo "$cons4";}
			while($fila4=ExFetch($res4))
			{
				//echo $cons4."<br>";
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
				,$filaVr[0],$filaVr[0],$NoLiq,'$fila4[3] $fila4[13]','$fila4[10]','$fila4[11]','$fila4[4]','$fila4[5]','$fila4[6]'
				,'','$fila4[8]','$fila4[9]','$Ambito','$fila4[12]' $Facturable2)";
				//echo $cons6."<br>";
				$res6=ExQuery($cons6);
				$SubTotal=$SubTotal+$filaVr[0];	
			}
		}
		//Cups de plantilla de Procedimientos
		
		$cons4="select cup,nombre,interpretacion,fechaini,fechafin,finproced,diagnostico,causaexterna,tipodx,formaquirugica 
		from salud.plantillaprocedimientos,contratacionsalud.cups
		where plantillaprocedimientos.compania='$Compania[0]' and cups.compania='$Compania[0]' and cup=cups.codigo and numservicio=$NumServM
		and cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
		where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion and estado='AC'
		and nofactura is null)";
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
			$Cant="";
			$consRep="select cantidad,vrunidad from facturacion.detalleliquidacion where compania='$Compania[0]' and codigo='$fila4[0]' and noliquidacion=$NoLiq";
			$resRep=ExQuery($consRep);
			$filaRep=ExFetch($resRep);
			$Cant=$filaRep[0]; 
			if($Cant>0)
			{
				$Cant++;
				$VrTot=$Cant*$filaRep[1];
				$cons6="update facturacion.detalleliquidacion set cantidad='$Cant',vrtotal=$VrTot where compania='$Compania[0]' and codigo='$fila4[0]' and 
				noliquidacion=$NoLiq";	
			}
			else
			{
				$cons6="insert into facturacion.detalleliquidacion (compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal
				,noliquidacion,causaext,dxppal,tipodxppal,ambito,finalidad,formarealizacion $Facturable1) values ('$Compania[0]','$fila[0]','$FechaIni $Mins[1]','$fila3[0]','$fila3[1]','$fila4[0]','$fila4[1]',1
				,$filaVr[0],$filaVr[0],$NoLiq,'$fila4[7]','$fila4[6]','$fila4[8]','$Ambito','$fila4[5]','$fila4[9]' $Facturable2)";
			}
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
		and cups.compania='$Compania[0]'and cups.codigo=odontogramaproc.cup and odontogramaproc.tipoodonto!='Inicial'
		and odontogramaproc.cup not in (select codigo from facturacion.detalleliquidacion,facturacion.liquidacion 
		where liquidacion.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and numservicio=$NumServM
		and liquidacion.noliquidacion=detalleliquidacion.noliquidacion and estado='AC'
		and nofactura is null)";
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
			,$filaVr[0],$filaVr[0],$NoLiq,'$fila2[2] $Mins[1]','$fila2[8]','$fila2[3]','$fila2[4]','$fila2[5]','$fila2[6]'
			,'$fila2[7]','$Ambito','$fila2[8]' $Facturable2)";
			//echo $cons6."<br>";
			$res6=ExQuery($cons6);
			$SubTotal=$SubTotal+$filaVr[0];	
			$BanOdonto=1;
		}	
		$SubT=$fila[3]+$SubTotal;
		$Tot=$fila[4]+$SubTotal;
		$cons2="update facturacion.liquidacion set subtotal=$SubT,total=$Tot
		where compania='$Compania[0]' and numservicio=$NumServM and cedula='$CedPac'";
		//echo $cons2;
		$res2=ExQuery($cons2);
		$CargarEnLiq="";
	
				
		if(!$VrCopato){$VrCopato=$row[7];} 
		if(!$Valordescuento){$Valordescuento=$row[3];}
		if(!$Ambito){$Ambito=$row[8];}
		//echo $Ambito." nn ";
		$cons="select grupo,tipo,codigo,nombre,sum(cantidad),vrunidad,vrunidad,generico,presentacion,forma,almacenppal from facturacion.detalleliquidacion 
		where compania='$Compania[0]' and noliquidacion=$NoLiq group by grupo,tipo,codigo,nombre,vrunidad,generico,presentacion,forma,almacenppal";
		$res=ExQuery($cons);
		//echo $cons;
		if(ExNumRows($res)>0){			
			$TMPCOD2=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
			while($fila=ExFetch($res)){
				$cons2="select finalidad from facturacion.detalleliquidacion where compania='$Compania[0]' and codigo='$fila[2]' and noliquidacion=$NoLiq";
				$res2=ExQuery($cons2);
				$FinCup="";
				while($fila2=ExFetch($res2))
				{
					if($fila2[0]){$FinCup=$fila2[0];}
				}				
				if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];}
				$VrTot=$fila[4]*$fila[5];
				$cons2="insert into facturacion.tmpcupsomeds 
					(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenPpal,finalidad) values			('$Compania[0]','$TMPCOD2','$CedPac','$fila[0]','$fila[1]','$fila[2]','$fila[3]',$fila[4],$fila[5],$VrTot,'$fila[7]','$fila[8]','$fila[9]','$fila[10]','$FinCup')";										
				//echo $cons2;
				$res2=ExQuery($cons2); 
				if($fila[0]=="CURACIONES"||$fila[0]=="ODONTOLOGIA"){$BanOdonto=1;}
			}
		}
	}
	$cons="select pagador,contrato,nocontrato,valordescuento,porsentajedesc,fechaini,fechafin ,valorcopago,ambito,tipofactura,formatofurips
	from facturacion.liquidacion 
	where compania='$Compania[0]' and cedula='$CedPac' and noliquidacion=$NoLiq";
	$res=ExQuery($cons);
	$row=ExFetch($res);
	
	//$Porsentajedesc=$row[4];
	$FecIniLiq=$row[5];
	$FecFinLiq2=$row[6]; 
	if(!$TipoFac){$TipoFac=$row[9];}
	if(!$Furips){$Furips=$row[10];}
	if($Recalcular&&$NoContraM)
	{
		$cons="select plantarifario,planbeneficios,plantarifameds,planservmeds,cuotamod from contratacionsalud.contratos where compania='$Compania[0]'
		and entidad='$PagaM' and contrato='$ContraM' and numero='$NoContraM'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$PlanTarfCups=$fila[0]; $PlanServCups=$fila[1]; $PlanTarifMeds=$fila[2]; $PlanServMeds=$fila[3]; $CobraCuota=$fila[4];
		
		$consRES="select restriccioncobro from ContratacionSalud.Contratos 
		where compania='$Compania[0]' and entidad='$PagaM' and 	contrato='$ContraM' and numero='$NoContraM'";
		$resRES=ExQuery($consRES);
		$filaRES=ExFetch($resRES); $RestricCobro=$filaRES[0];
		if($RestricCobro==1)
		{
			$consRestric="select grupo,mostrar,montofijo,cobrar from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$PagaM' and contrato='$ContraM' and nocontrato='$NoContraM'";
			$resRestric=ExQuery($consRestric);			
			//echo $consRestric;
			while($filaRestric=ExFetch($resRestric))
			{
				$Rescric[$filaRestric[0]]=array($filaRestric[1],$filaRestric[2],$filaRestric[3]); //Rescric[grupo] = mostrar,montofijo,cobrar				
			}
		}
		
		$cons="select grupo,tipo,codigo,cantidad,vrund,vrtotal,nofacturable,almacenppal,nombre from facturacion.tmpcupsomeds where compania='$Compania[0]'
		and tmpcod='$TMPCOD2' and cedula='$CedPac'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{//echo "$fila[0] $fila[1] $fila[2] $fila[3]<br>";
			if($RestricCobro&&$Rescric)
			{				
				if($Rescric[$fila[0]])
				{
					if($Rescric[$fila[0]][0]=="Si"){
						$NoFacturable="0";
					}
					else{
						$NoFacturable="1";$filaVr[0]="0";
					}
					if($Rescric[$fila[0]][1]&&$Rescric[$fila[0]][1]!="0")
					{													
						if(!$BanRestric[$fila[0]]){
							//$fila[4]=$Rescric[$fila[0]][1];
							$fila[4]="0";
							$fila[5]=$Rescric[$fila[0]][1];
							$BanRestric[$fila[0]]=1;								
						}
						else{
							$fila[4]="0";
							$fila[5]="0";
						}
					}
					else
					{
						if($Rescric[$fila[0]][2]=="No")
						{
							$fila[5]="0";
						}													
					}	
					$cons4="update facturacion.tmpcupsomeds set vrund=$fila[4],vrtotal=$fila[5],nofacturable=$NoFacturable
					where compania='$Compania[0]' and tmpcod='$TMPCOD' and codigo='$fila[2]' and nombre='$fila[8]'";
					$res4=ExQuery($cons4);
					//echo "$cons4<br>";
				}
			}
			else
			{
				
				if($fila[1]!="Medicamentos")
				{
					$consVr="select valor from contratacionsalud.cupsxplanes where compania='$Compania[0]' and cup='$fila[2]' and autoid=$PlanTarfCups";						
					$resVr=ExQuery($consVr);
					$filaVr=ExFetch($resVr);
					//echo "$consVr<br>";
					$cons3="select grupo,tipo,nombre,facturable from contratacionsalud.cupsxplanservic,contratacionsalud.cups 
					where cupsxplanservic.compania='$Compania[0]' and clase='CUPS' and cup='$fila[2]' and cups.compania='$Compania[0]' 
					and cups.codigo=cup and autoid=$PlanServCups";
					$res3=ExQuery($cons3); 
					$fila3=ExFetch($res3);	
					//echo $cons3."<br>";
					if($fila3[3]==1){$NoFacturable="0";}else{$NoFacturable="1";$filaVr[0]="0";}
					if(!$filaVr[0]){$filaVr[0]="0";}
					$VrTotalAct=$filaVr[0]*$fila[3]; if(!$VrTotalAct){$VrTotalAct="0";}
					$cons4="update facturacion.tmpcupsomeds set vrund=$filaVr[0],vrtotal=$VrTotalAct,nofacturable=$NoFacturable
					where compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$CedPac' and codigo='$fila[2]' and nombre='$fila[8]'";
					$res4=ExQuery($cons4);
					//echo $cons4."<br>";
				}
				else
				{ 					
					$cons3 = "Select valorventa
					from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
					where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null and Tarifario='$PlanTarifMeds' 
					and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid
					and CodProductos.Compania='$Compania[0]' and TiposdeProdxFormulacion.AlmacenPpal='$fila[7]' and CodProductos.Anio=$ND[year] 
					and CodProductos.Codigo1='$fila[2]'
					group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa
					order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";
					$res3=ExQuery($cons3);
					//echo $cons3."<br>";
					$fila3=ExFetch($res3);
					$consMedsxPlan="select codigo,facturable from contratacionsalud.medsxplanservic 
					where compania='$Compania[0]' and autoid='$PlanServMeds' and almacenppal='$fila[7]' and codigo='$fila[2]'";
					$resMedsxPlan=ExQuery($consMedsxPlan);
					//echo $consMedsxPlan."<br>";
					$filaMedsxPlan=ExFetch($resMedsxPlan); if($filaMedsxPlan[1]==1){$NoFacturable="0";}else{$NoFacturable="1"; $fila3[0]="0";}
					$cons="select grupo,tipo,codigo,cantidad,vrund,vrtotal,nofacturable,almacenppal from facturacion.tmpcupsomeds 
					where compania='$Compania[0]'	and tmpcod='$TMPCOD'";
					if(!$fila3[0]){$fila3[0]="0";}
					$VrTotalAct=$fila3[0]*$fila[3]; if(!$VrTotalAct){$VrTotalAct="0";}
					$cons4="update facturacion.tmpcupsomeds set vrund=$fila3[0],vrtotal=$VrTotalAct,nofacturable=$NoFacturable
					where compania='$Compania[0]' and tmpcod='$TMPCOD2' and cedula='$CedPac' and codigo='$fila[2]' and nombre='$fila[8]'";
					$res4=ExQuery($cons4);
					//echo $cons4."<br>";
				}
			}
		}
		if($CobraCuota==1&&$Tipousu&&$Nivelusu&&$Ambito&&$Cedula)
		{/*
			$consul="select tipoasegurador from central.terceros where identificacion='$PagaM' and compania='$Compania[0]' and Tipo='Asegurador'";
			//echo $consul."<br>";
			$result=ExQuery($consul);
			$row=ExFetch($result);		
			
			$consul2="select valor,clase,tipocopago,topeanual from salud.topescopago 
			where anio='$ND[year]' and compania='$Compania[0]' and tipousuario='$Tipousu' and tipoasegurador='$row[0]' and  nivelusu='$Nivelusu' and ambito='$Ambito'";				
			$result2=ExQuery($consul2); $fil=ExFetch($result2);
			$Tipocopago=$fil[2];
			$ClaseCopago=$fil[1];	
			if($fil[1]=='Fijo'){
				$Copago=$fil[0]; $Porsentajecopago="0";			
			}
			$Copago=($fil[0]/100)*$Total; 
				
				$consul3="select sum(valorcopago) from facturacion.liquidacion where cedula='$Cedula' and compania='$Compania[0]' 
				and nofactura is not null and porsentajecopago is not null and porsentajecopago!=0 and estado='AC' and fechacrea>='$ND[year]-01-01 00:00:00' 
				and fechacrea<='$ND[year]-12-31 23:59:59' group by cedula";
				$result3=ExQuery($consul3);
				//echo "$consul3 <br>";
				$fil3=ExFetch($result3); $CopAcumulado=$fil3[0]; $Tope=$fil[3];
				//echo "Copago='$Valorcopago' Copago Acumulado='$CopAcumulado' Tope='$Tope'";
				if(!$CopAcumulado){
					if($Tope<$Copago){
						$Copago=$Tope;
						$BanRecal=1;
					}
					else{$BanRecal=0;}
				}
				else{
					if(($Copago+$CopAcumulado)>$Tope){
						$Copago=$Tope-$CopAcumulado;
						$BanRecal=1;
					}
					else{$BanRecal=0;}
				}
				
				$Porsentajecopago=$fil[0];	*/
		}
	}
	$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$PagaM'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NomAseg=$fila[0]." ".$fila[1]." ".$fila[2]." ".$fila[2];
	$consServAnt="select fechaing,fechaegr,tiposervicio from salud.servicios where compania='$Compania[0]' and numservicio=$NumServM";
	$resServAnt=ExQuery($consServAnt);
	$filaServAnt=ExFetch($resServAnt); $FIServAnt=explode(" ",$filaServAnt[0]); $FFServAnt=explode(" ",$filaServAnt[1]);
	$consServAnt="Select servicios.numservicio,tiposervicio from salud.servicios,facturacion.liquidacion
	where fechaing>='$FIServAnt[0] 00:00:00' and fechaing<='$FIServAnt[0] 23:59:59'
	and servicios.numservicio!='$NumServM' and servicios.cedula='$CedPac' and liquidacion.numservicio=servicios.numservicio and liquidacion.estado='AC' and nofactura is not null";
	$resServAnt=ExQuery($consServAnt);	
	//echo $consServAnt;
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function RecalcTot()
	{		
		if(document.FORMA.VrCopato.value>0){
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.VrCopato.value);
		}
		else{
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.Valordescuento.value);
		}
	}
	function RecalcTot2()
	{		
		if(document.FORMA.Valordescuento.value>0){
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.Valordescuento.value);
		}
		else{
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.VrCopato.value);
		}
	}
	function MoverFecha(e,NumServ,FecIni,FecFin)
	{
		x = e.clientX;
		y = e.clientY;
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/CambiaFechaServicio.php?DatNameSID=<? echo $DatNameSID?>&ConsolidFac=1&CedPac=<? echo $CedPac?>&NumServ=<? echo $NumServM?>&FecIng="+FecIni+"&FecEgr="+FecFin;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st-50;
		document.getElementById('FrameOpener').style.left=1;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='300px';
	}
	function DataPartos(e,NumServ,NoLiq,CedPac)
	{
		if(document.FORMA.Parto.checked==true)
		{
			x = e.clientX;
			y = e.clientY;
			st = document.body.scrollTop;
			frames.FrameOpener.location.href="/Facturacion/PartsFacs.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $CedPac?>&NumServ="+NumServ+"&NoLiq="+NoLiq+"&CedPac="+CedPac;
			document.getElementById('FrameOpener').style.position='absolute';
			//document.getElementById('FrameOpener').style.top=(y)+st-50;
			document.getElementById('FrameOpener').style.top=1;
			document.getElementById('FrameOpener').style.left=1;
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='100%';
			document.getElementById('FrameOpener').style.height='100%';
		}
	}
	function Validar()
	{
		if(document.FORMA.NoContraM.value==''){alert("Debe haber un numero de contrato!!!");return false;}
		if(document.FORMA.TipoFac.value==''){alert("Debe seleccionar el tipo de factura!!!");return false;}
	}
</script>

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  <?
if(ExNumRows($resServAnt)>0)
{?>
	<div align="center">
	<?	while($filaServAnt=ExFetch($resServAnt))
        {?>
            
            <font color="#FF0000" style="text-align:center;" size="+1" >
                ¡EXISTE UN SERVICIO DE  <? echo strtoupper($filaServAnt[1])?> QUE HA SIDO FACTURADO CON LA MISMA FECHA QUE ESTE SERVICIO!</font><br><br><?
        }?>
        <input type="button" value="Mover Fecha Servicio" onClick="MoverFecha(event,'<? echo $NumServM?>','<? echo $FIServAnt[0]?>','<? echo $FFServAnt[0]?>')">
    </div><?
}?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr  bgcolor="#e5e5e5" align="center">
	<td colspan="6"><strong> <? echo "$NomPac - Identificacion: $CedPac"?></strong></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" align="center"><strong>Asegurador:</strong><? //echo $NomAseg?></td>
    <td colspan="5">
    <? 	//if(!$EPS){$EPS=$PagaM;}
		$consEPS="select entidad,primape,segape,primnom,segnom from central.terceros,contratacionsalud.contratos 
		where terceros.compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion 
		group by entidad,primape,segape,primnom,segnom order by primape,segape,primnom,segnom";
		$resEPS=ExQuery($consEPS);?>
        <select name="PagaM" onChange="document.FORMA.submit()">
        	<option></option>
       	<?	while($filaEPS=ExFetch($resEPS))
            {
                if($filaEPS[0]==$PagaM){echo "<option value='$filaEPS[0]' selected>$filaEPS[1] $filaEPS[2] $filaEPS[3] $filaEPS[4]</option>";}
				else{echo "<option value='$filaEPS[0]'>$filaEPS[1] $filaEPS[2] $filaEPS[3] $filaEPS[4]</option>";}
            }?>
      	</select>
    </td>
</tr>
<tr>    
   	<td bgcolor="#e5e5e5" align="center"><strong>Cotrato: </strong><? //echo $ContraM?></td> 
    <td>
    <?	$consEPS="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$PagaM' group by contrato order by contrato";
		$resEPS=ExQuery($consEPS);?>
    	<select name="ContraM" onChange="document.FORMA.submit()">
        	<option></option>        
        <?	while($filaEPS=ExFetch($resEPS))
            {
                if($filaEPS[0]==$ContraM){echo "<option value='$filaEPS[0]' selected>$filaEPS[0]</option>";}
				else{echo "<option value='$filaEPS[0]'>$filaEPS[0]</option>";}
            }?>
        </select>
    </td>
    <td bgcolor="#e5e5e5" align="center"><strong>No. Contrato: </strong><? //echo $NoContraM?></td> 
    <td>
    <?	$consEPS="select numero from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$PagaM' and contrato='$ContraM'
		group by numero order by numero";
		$resEPS=ExQuery($consEPS);?>
    	<select name="NoContraM" onChange="document.FORMA.Recalcular.value=1;document.FORMA.CargarEnLiq.value=1;document.FORMA.submit()">
        	<option></option>        
        <?	while($filaEPS=ExFetch($resEPS))
            {
                if($filaEPS[0]==$NoContraM){echo "<option value='$filaEPS[0]' selected>$filaEPS[0]</option>";}
				else{echo "<option value='$filaEPS[0]'>$filaEPS[0]</option>";}
            }?>
        </select>
    </td>
    <td bgcolor="#e5e5e5" align="center"><strong>Proceso:</strong></td> 
    <td>
	<?	$consEPS="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
		$resEPS=ExQuery($consEPS);?>
        <select name="Ambito" onChange="document.FORMA.submit()">
        	<option></option>
		<?	while($filaEPS=ExFetch($resEPS))
			{
				if($filaEPS[0]==$Ambito){echo "<option value='$filaEPS[0]' selected>$filaEPS[0]</option>";}
				else{echo "<option value='$filaEPS[0]'>$filaEPS[0]</option>";}
			}?>    	
        </select>
    </td>
</tr>
<tr>
<?	$consTF="select tipofact from facturacion.tipofactura where compania='$Compania[0]' and ambitofac='$Ambito' order by tipofact";
	$resTF=ExQuery($consTF);?>	
     <td bgcolor="#e5e5e5" align="center"><strong>Tipo Factura</strong></td> 
    <td colspan="4">
    	<select name="TipoFac">
        	<option></option>
      	<?	while($filaTF=ExFetch($resTF))
			{
				if($filaTF[0]==$TipoFac){echo "<option value='$filaTF[0]' selected>$filaTF[0]</option>";}
				else{echo "<option value='$filaTF[0]'>$filaTF[0]</option>";}
			}?>
      	</select>      
    </td>
    <td>
    <?	if(!$Parto){
			$consParto="select * from salud.partos where compania='$Compania[0]' and idmadre='$CedPac' and noliq=$NoLiq";
			$resParto=ExQuery($consParto);
			if(ExNumRows($resParto)>0){$Parto="on";}
		}?>
    	<strong>PARTO</strong> <input type="checkbox" name="Parto" onClick="DataPartos(event,'<? echo $NumServM?>','<? echo $NoLiq?>','<? echo $CedPac?>')" 
		<? if($Parto){?> checked<? }?>> 
        <strong>FURIPS</strong> <input type="checkbox" name="Furips" <? if($Furips){?> checked<? }?>>
    </td>
</tr>
<tr  bgcolor="#e5e5e5" align="center" title="Ver Liquidacion" style="cursor:hand"
onClick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $NoLiq?>&Ced=<? echo $CedPac?>&Estado=<? echo "AC"?>','','left=10,top=10,width=900,height=700,menubar=yes,scrollbars=YES')">
	<td colspan="6">Liquidacion No.<strong><? echo $NoLiq?></strong></td>
</tr>
</table>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr><td colspan="12" align="center">
		<input type="button" value="Agregar Medicamentos"
        onClick="if(document.FORMA.NoContraM.value!=''){location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&NomPac=<? echo $NomPac?>&FecIniLiq=<? echo $FechaIni?>&FecFinLiq2=<? echo $FechaIni?>&TipoNuevo=Medicamento';}else{alert('Debe haber un numero de contrato!!!');}"
        <?	if(!$PagaM||!$ContraM||!$NoContraM){?> disabled<? }?>>
        
        <input type="button" value="Agregar CUPS" 
        onClick="if(document.FORMA.NoContraM.value!=''){location.href='NewLiquidacion.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&VrCopato=<? echo $VrCopato?>&NomPac=<? echo $NomPac?>&FecIniLiq=<? echo $FechaIni?>&FecFinLiq2=<? echo $FechaIni?>&TipoNuevo=Cup'}else{alert('Debe haber un numero de contrato!!!');}"
        <?	if(!$PagaM||!$ContraM||!$NoContraM){?> disabled<? }?>>
  	<?	//if($BanOdonto==1){?>
            <input type="button" value="Ver Odontograma"
            onClick="open('/HistoriaClinica/Odontologia/ImprimeOdontograma.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $CedPac?>&NumServ=<? echo $NumServM?>&TipoOdontograma=<? echo "Seguimiento";?>&Fecha=<? echo $FecIniLiq?>','','width=1180,height=700,scrollbars=yes');"
            <?	if(!$PagaM||!$ContraM||!$NoContraM){?> disabled<? }?>>
            <input type="button" value="Ver Registros Medicos"
            onClick="open('/HistoriaClinica/VerFormatosxPac.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $CedPac?>&NumServ=<? echo $NumServM?>&FechaIni=<? echo $FecIniLiq?>&FechaFin=<? echo $FecFinLiq2?>','','width=800,height=600,scrollbars=yes');"
            <?	if(!$PagaM||!$ContraM||!$NoContraM){?> disabled<? }?>>
   	<?	//}?>
	</td>
</tr>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
	<td>Codigo</td><td>Nombre</td><td>Finalidad<td>Cantidad</td><td>Vr Unidad</td><td>Vr Total</td><td colspan="3"></td>
</tr>
<?	foreach($GrupsCUPs as $GG){
		$cons="select codigo,nombre,cantidad,vrund,vrtotal,presentacion,forma,finalidad 
		from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD2'
		and cedula='$CedPac' and grupo='$GG[1]' order by nombre";
		$res=ExQuery($cons);
		//echo $cons;
		$SubTxG=0;
		$BanG=0;
		$Proceds="";
		while($fila=ExFetch($res))
		{
			$BanG=1;
			$consT="select tipo,almacenppal from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD2'
			and cedula='$CedPac' and grupo='$GG[1]' and codigo='$fila[0]'";
			$resT=ExQuery($consT);
			$filaT=ExFetch($resT); $Tip=$filaT[0]; $Almappal=$filaT[1];?>
			<tr <? 	if($fila[4]<=0){?> style="color:#F00" title='Valor Total Ceros'<? }
					elseif($PosCodProds[$fila[0]]!='1'&&$Tip=="Medicamentos"){?> style="color:#C0C" title="Medicamento no pos";<? }?>
          	>
				<td><? echo $fila[0]?></td><td><? echo "$fila[1] $fila[5] $fila[6]"?></td><td align="center"><? echo $fila[7]?>&nbsp;</td><td align="right"><? echo $fila[2]?></td>
				<td align="right"><? echo number_format($fila[3],2)?></td><td align="right"><? echo number_format($fila[4],2)?></td>
				<td><img src="/Imgs/b_edit.png" title="Editar" style="cursor:hand"></td>
				<td>
					<img src="/Imgs/b_drop.png" title="Eliminar" style="cursor:hand"
					onClick="if(confirm('Desea eliminar este resgistro?')){location.href='ServxConsolidxPac.php?DatNameSID=<? echo $DatNameSID?>&NumServM=<? echo $NumServM?>&NoLiq=<? echo $NoLiq?>&CedPac=<? echo $CedPac?>&Ambito=<? echo $Ambito?>&PagaM=<? echo $PagaM?>&ContraM=<? echo $ContraM?>&NoContraM=<? echo $NoContraM?>&TMPCOD=<? echo $TMPCOD?>&TMPCOD2=<? echo $TMPCOD2?>&Valordescuento=<? echo $Valordescuento?>&NomPac=<? echo $NomPac?>&VrCopato=<? echo $VrCopato?>&ElimElto=1&CodElim=<? echo $fila[0]?>';}">
				</td>
     	<?	?>
			</tr>
	<?		$SubTxG=$SubTxG+$fila[4];
			$Total=$Total+$fila[4];
			$Proceds=$Proceds."***$fila[0];;;$fila[1];;;$fila[2]";//codigo,detalle,cantidad
		}
		if($BanG==1){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="right">	
            	<td colspan="4"><? echo $GG[0];?></td><td><? echo number_format($SubTxG,2);?></td>
                <td colspan="3">
			<? 	if($Tip=="Medicamentos"){?>
            		<button title="Imprimir Orden Medicamentos"
                    onClick=" open('FormulaGenerica.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&AlmacenPpal=<?echo $Almappal?>&Urgente=1&Numero=<? echo $NumServM?>','','width=860','height=700','scrollbars=yes')">
            			<img src="/Imgs/b_print.png">
                 	</button>
            <?	}
				if($Tip=="00005")
				{?>					
                    <button title="Imprimir Orden" onClick=" open('OrdenProced.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&Numero=<? echo $NumServM?>&Proceds=<? echo $Proceds?>','','width=860','height=700','scrollbars=yes')">
                        <img src="/Imgs/b_print.png" >
                    </button>					
			<?	}?>
            	&nbsp;</td>
            </tr>
	<?	}
	}//Valordescuento VrCopato?>
<tr> 
	<td align="right" colspan="4"><strong>Descuento</strong></td><td align="right">
	<input type="text" name="Valordescuento" onKeyDown="xNumero(this)" onKeyUp="xNumero(this);RecalcTot2();"  onKeyPress="xNumero(this);"
    value="<? echo $Valordescuento?>" style="width:80; text-align:right"></td><td colspan="3">&nbsp;</td>
</tr>
<tr> 
	<td align="right" colspan="4"><strong>Copago</strong></td><td align="right">
	<input type="text" name="VrCopato" onKeyDown="xNumero(this)" onKeyUp="xNumero(this);RecalcTot();"  onKeyPress="xNumero(this);"
    value="<? echo $VrCopato?>" style="width:80; text-align:right"></td><td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td align="right" colspan="4"><strong>Total</strong></td>
    <td align="center">
    <?	$Total=$Total-($Copago+$Descuento);?>
    	<input type="hidden" name="AuxTotal" value="<? echo $Total?>">
        <input type="text" name="Total" value="<? echo $Total?>" style="width:80; text-align:right" readonly></td>    
    <td colspan="3">&nbsp;</td>
</tr>
<tr align="center">
    <td colspan="11">
    	<input type="button" onClick="if(document.FORMA.NoContraM.value!=''){document.FORMA.GuardarLiq.value=1;document.FORMA.submit()}
    	else{alert('Debe haber un numero de contrato!!!');}" 
        value="Guardar Liquidacion"<? if(!$PagaM||!$ContraM||!$NoContraM||!$Ambito){?> disabled<? }?> /> 
           	
        <input type="submit" name="Facturar" value="Facturar" <?	if(!$PagaM||!$ContraM||!$NoContraM||!$Ambito){?> disabled<? }?> />	
    </td>
</tr>
</table>

<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServM" value="<? echo $NumServM?>">
<input type="hidden" name="NoLiq" value="<? echo $NoLiq?>">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
<input type="hidden" name="Recalcular" value="">
<!--
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" name="PagaM" value="<? echo $PagaM?>">
<input type="hidden" name="ContraM" value="<? echo $ContraM?>">
<input type="hidden" name="NoContraM" value="<? echo $NoContraM?>">
<input type="hidden" name="Valordescuento" value="<? echo $Valordescuento?>">
<input type="hidden" name="VrCopato" value="<? echo $VrCopato?>">
-->
<input name="CargarEnLiq" type="hidden" value="<? echo $CargarEnLiq?>">
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
<input type="hidden" name="FechaIni" value="<? echo $FechaIni?>">
<input type="hidden" name="NomPac" value="<? echo $NomPac?>">
<input type="hidden" name="GuardarLiq" value="">

</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1"></iframe> 
</body>
</html>
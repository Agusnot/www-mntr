<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	//echo $Cedula;
	
	if($GuardarFac)
	{		
		if(!$Copago){$Copago="0";}if(!$Descuento){$Descuento="0";}
		if($Furips){$Furips="1";}else{$Furips="";}
		if($FacIndv){
			if($NumServ){$NS=",numservicio=$NumServ";}
			if($Parto){$Part=",parto='1'";}else{$Part=",parto=NULL";}
			$cons="update facturacion.liquidacion set nocarnet='$NoCarnet',tipousu='$Tipousu',nivelusu='$Nivelusu',autorizac1='$Autorizac1'
			,autorizac2='$Autorizac2',autorizac3='$Autorizac3',pagador='$Paga',contrato='$PagaCont',motivonocopago='$MsjCopago' $NS $Porc
			,nocontrato='$PagaNocont',valorcopago=$Copago,valordescuento=$Descuento,subtotal=$Subtotal,total=$Total,usumod='$usuario[1]'
			,fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',fechacrea='$FechaExp $ND[hours]:$ND[minutes]:$ND[seconds]'		
			,fechaini='$FechaIni',fechafin='$FechaFin',formatofurips='$Furips' $Part where compania='$Compania[0]' and nofactura=$NoFac and noliquidacion=$NumLiq";
			//echo $cons."<br>";
			$res=ExQuery($cons);
			$cons="Delete from facturacion.detalleliquidacion where noliquidacion=$NumLiq and compania='$Compania[0]'";
			//echo $cons."<br>";
			$res=ExQuery($cons);
			$cons="select 	
			fecha,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,dxppal,nofacturable,finalidad,causaext,tipodxppal,codproducto			
			from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD'";
			//echo $cons."<br>";		
			$res=ExQuery($cons);
			//echo "<br><br>";
			while($fila=ExFetch($res))
			{
				$cons2="insert into facturacion.detalleliquidacion 		
				(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,generico,presentacion
				,forma,almacenppal,finalidad,causaext,tipodxppal,nofacturable,dxppal,fechainterpret,codproducto) values 		
				('$Compania[0]','$usuario[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]',$fila[5],$fila[6],$fila[7],$NumLiq,'$fila[8]'
				,'$fila[9]','$fila[10]','$fila[11]','$fila[14]','$fila[15]','$fila[16]','$fila[13]','$fila[12]','$FechaExp','$fila[17]')";
				$res2=ExQuery($cons2);
				//echo $cons2."<br>";
			}			  
			$cons="update salud.pagadorxservicios set entidad='$Paga' ,contrato='$PagaCont' , nocontrato='$PagaNocont'
			where compania='$Compania[0]' and numservicio=$NumServ and entidad='$PagaAnt' and contrato='$PagaContAnt' 
			and nocontrato='$PagaNocontAnt'";
			$res=ExQuery($cons);
			//echo $cons."<br>";		
		}
		$cons="delete from facturacion.detallefactura where compania='$Compania[0]' and nofactura=$NoFac";
		$res=ExQuery($cons);
		if(!$Copago){$Copago="0";}if(!$Descuento){$Descuento="0";}
		$cons="update facturacion.facturascredito set fechaini='$FechaIni',fechafin='$FechaFin',entidad='$Paga',contrato='$PagaCont'
		,nocontrato='$PagaNocont',subtotal=$Subtotal,copago=$Copago,total=$Total,usumod='$usuario[1]',fechamod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',descuento=$Descuento,fechacrea='$FechaExp $ND[hours]:$ND[minutes]:$ND[seconds]'
		where compania='$Compania[0]' and nofactura=$NoFac";
		//echo $cons."<br>";  
		$res=ExQuery($cons);
		$cons="select 	
		fecha,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,dxppal,nofacturable,finalidad,causaext,tipodxppal				
		from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD'";
		//echo $cons."<br>";		
		$res=ExQuery($cons);
		//echo "<br><br>";
		while($fila=ExFetch($res))
		{
			$cons2="insert into facturacion.detallefactura 
			(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,nofactura,generico,presentacion,forma,almacenppal) values 	
			('$Compania[0]','$usuario[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]',$fila[6],$fila[7],$NoFac,'$fila[8]','$fila[9]'																																																																																
			,'$fila[10]','$fila[11]')";
			//echo $cons2."<br>";
			$res2=ExQuery($cons2);
		}?>            
            <script language="javascript">
				open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $NoFac?>&Estado=<? echo "AC"?>&Impresion=<? echo $Impresion?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')
			</script>
            <?
	}
	
	if($AgregarFac){	
		if(!$Copago){$Copago="0";}if(!$Descuento){$Descuento="0";}
		if($NoFac){
			if($Cedula){ $FacIndv="1";}else{$FacIndv="0";}
			
			$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc";
			$res=ExQuery($cons); $fila=ExFetch($res); $AutoNofac=$fila[0]+1;
			//echo $cons."<br>";
			
			if(!$Subtotal){$Subtotal="0";}if(!$Copago){$Copago="0";}if(!$Descuento){$Descuento="0";}if(!$Total){$Total="0";}
			$cons="insert into facturacion.facturascredito 
			(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,estado,individual)
			values ('$Compania[0]','$FechaExp $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$FechaIni','$FechaFin'
			,'$Paga','$PagaCont','$PagaNocont','$Ambito',$Subtotal,$Copago,$Descuento,$Total,$AutoNofac,'AC',$FacIndv)";
			//echo "$cons<br>";
			$res=ExQuery($cons);
			
			$cons="select 	
			fecha,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,dxppal,nofacturable,finalidad,causaext,tipodxppal,codproducto		
			from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD'";
			//echo $cons."<br>";		
			$res=ExQuery($cons);
			//echo "<br><br>";
			while($fila=ExFetch($res))
			{
				$cons2="insert into facturacion.detallefactura 
				(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,nofactura,generico,presentacion,forma,almacenppal) values 	
				('$Compania[0]','$usuario[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]','$fila[5]',$fila[6],$fila[7],$AutoNofac,'$fila[8]','$fila[9]','$fila[10]','$fila[11]')";
				//echo $cons2."<br>";
				$res2=ExQuery($cons2);
				
			}			
			if($FacIndv){
								
				if($BanLigaServ==1){
					$cons="select numservicio from salud.servicios where compania='$Compania[0]' and numservicio=$NumServ";	
					//echo $cons;
					$res=ExQuery($cons);
					if(ExNumRows($res)<=0)
					{$BanLigaServ=0;?><script language="javascript">alert("Este Numero de Servicio no ha sido creado aun, se creara uno nuevo!!!");</script><? }
				}
				//exit;
				if($BanLigaServ!=1){
					$cons="select numservicio from salud.servicios where compania='$Compania[0]' order by numservicio desc";
					$res=ExQuery($cons);$fila=ExFetch($res); $AutoNumServ=$fila[0]+1;
					
					$cons="insert into salud.servicios (cedula,numservicio,tiposervicio,fechaing,fechaegr,tipousu,nivelusu,autorizac1,autorizac2,autorizac3
					,estado,nocarnet,compania) values ('$Cedula',$AutoNumServ,'$Ambito','$FechaIni $ND[hours]:$ND[minutes]:$ND[seconds]'
					,'$FechaFin $ND[hours]:$ND[minutes]:$ND[seconds]','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3','AN','$NoCarnet','$Compania[0]')";
					$res=ExQuery($cons);				
					//echo $cons."<br>";
					
					$cons="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre,tipo)
					values ($AutoNumServ,'$Compania[0]','$Paga','$PagaCont','$PagaNocont','$FechaIni','$FechaFin','$usuario[1]'
					,'$FechaExp $ND[hours]:$ND[minutes]:$ND[seconds]',1)";
					//echo $cons."<br>";
					$res=ExQuery($cons);
				}
				else{
					$AutoNumServ=$NumServ;
				}
				$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";
				$res=ExQuery($cons);
				$fila=ExFetch($res); 
				$AutoNoLiq=$fila[0]+1;
				if($Furips){$Furips="1";}else{$Furips="";}
				//if($Parto){$Part1=",parto";$Part2=",1";}
				$cons="insert into facturacion.liquidacion 
				(compania,usuario,fechacrea,ambito,fechaini,fechafin,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,pagador,contrato,nocontrato
				 ,noliquidacion,numservicio,valorcopago,valordescuento,subtotal,total,cedula,estado,nofactura,motivonocopago,formatofurips $Part1) 
				values ('$Compania[0]','$usuario[1]'
				 ,'$FechaExp $ND[hours]:$ND[minutes]:$ND[seconds]','$Ambito','$FechaIni','$FechaFin','$NoCarnet','$Tipousu','$Nivelusu','$Autorizac1'
				 ,'$Autorizac2','$Autorizac3','$Paga','$PagaCont','$PagaNocont',$AutoNoLiq,$AutoNumServ,$Copago,$Descuento,$Subtotal,$Total,'$Cedula'
				 ,'AC',$AutoNofac,'$MsjCopago','$Furips' $Part2)";
				$res=ExQuery($cons);
				//echo $cons."<br>";
				$Parto="";
				$cons="select 					fecha,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenppal,dxppal,nofacturable,finalidad,causaext,tipodxppal,codproducto				
				from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD'";
				//echo $cons."<br>";		
				$res=ExQuery($cons);
				//echo "<br><br>";
				while($fila=ExFetch($res))
				{
					$cons2="insert into facturacion.detalleliquidacion 		
					(compania,usuario,fechacrea,grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,noliquidacion,generico,presentacion
					,forma,almacenppal,finalidad,causaext,tipodxppal,nofacturable,dxppal,fechainterpret,codproducto) values 		
					('$Compania[0]','$usuario[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]',$fila[5],$fila[6],$fila[7],$AutoNoLiq,'$fila[8]'
					,'$fila[9]','$fila[10]','$fila[11]','$fila[14]','$fila[15]','$fila[16]','$fila[13]','$fila[12]','$FechaExp','$fila[17]')";
					$res2=ExQuery($cons2);
					//echo $cons2."<br>";
				}
				$NoFac=$AutoNofac;
			}?>            
            <script language="javascript">
				open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoNofac?>&Estado=<? echo "AC"?>&Impresion=<? echo $Impresion?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')
			</script>
            <?
		}			
	}
	if($Ver&&!$CodElim&&!$CodElim&&!$CamPaga&&!$CambTipoUsu&&!$CambNivelUsu&&!$GuardarFac&&!$BanProd)
	{	
		
		$CambCed="";
		$cons="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD'";
		$res=ExQuery($cons);
		$TMPCOD=""; $Paga=""; $PagaCont=""; $PagaNocont="";
		if($NoFac){
			if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
						
			$cons="select fechacrea,individual,ambito from facturacion.facturascredito where compania='$Compania[0]' and nofactura=$NoFac";
			$res=ExQuery($cons);$fila=ExFetch($res); $FechaExpedicion=explode(" ",$fila[0]);$FechaExp=$FechaExpedicion[0]; $FacIndv=$fila[1]; $Ambito=$fila[2];
			if($FacIndv==1){
				$cons="select ambito,fechafin,fechaini,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,pagador,contrato,nocontrato,noliquidacion,cedula
				,valorcopago,valordescuento,motivonocopago,numservicio,formatofurips,parto
				from facturacion.liquidacion where compania='$Compania[0]' and nofactura=$NoFac";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Paga=$fila[9]; $PagaCont=$fila[10]; $PagaNocont=$fila[11]; $Ambito=$fila[0]; $FechaFin=$fila[1]; $FechaIni=$fila[2]; $NoCarnet=$fila[3];
				$Tipousu=$fila[4]; $Nivelusu=$fila[5]; $Autorizac1=$fila[6]; $Autorizac2=$fila[7]; $Autorizac3=$fila[8]; $NumLiq=$fila[12]; $Cedula=$fila[13];
				$Copago=$fila[14]; $Descuento=$fila[15]; $MsjCopago=$fila[16]; $PagaAnt=$fila[9]; $PagaContAnt=$fila[10]; $PagaNocontAnt=$fila[11]; 
				$NumServ=$fila[17];	$Furips=$fila[18]; $Parto=$fila[19];	
			}
			else{
				$cons="select entidad,contrato,nocontrato,fechafin,fechaini from facturacion.facturascredito 
				where compania='$Compania[0]' and nofactura=$NoFac";	
				$res=ExQuery($cons);
				$fila=Exfetch($res); $Paga=$fila[0]; $PagaCont=$fila[1]; $PagaNocont=$fila[2]; $FechaFin=$fila[3]; $FechaIni=$fila[4];
			}
			$cons="select grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,finalidad ,causaext,dxppal,dxrel1,dxrel2
			,dxrel3,dxrel4,nofacturable,liquidacion.noliquidacion,detalleliquidacion.fechacrea,detalleliquidacion.codproducto
			from facturacion.detalleliquidacion,facturacion.liquidacion where detalleliquidacion.compania='$Compania[0]' and nofactura=$NoFac
			and liquidacion.compania='$Compania[0]' and liquidacion.noliquidacion=detalleliquidacion.noliquidacion";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){	
				while($fila=ExFetch($res)){
					if($GruposMeds[$fila[0]]){$fila[0]=$GruposMeds[$fila[0]];}
					$FecC=explode(" ",$fila[20]);
					$cons2="insert into facturacion.tmpcupsomeds 							
					(compania,tmpcod,cedula,grupo,tipo,codigo,nombre,cantidad,vrund,vrtotal,generico,presentacion,forma,almacenPpal,finalidad
					,causaext,dxppal,dxrel1,dxrel2,dxrel3,dxrel4,nofacturable,fecha,codproducto) values 
					('$Compania[0]','$TMPCOD','$Paciente[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]',$fila[4],$fila[5],$fila[6],'$fila[7]','$fila[8]','$fila[9]','$fila[10]','$fila[11]','$fila[12]','$fila[13]','$fila[14]','$fila[15]','$fila[16]','$fila[17]',$fila[18],'$FecC[0]','$fila[21]')";
					//echo $cons2;
					$res2=ExQuery($cons2); 
					//tmp->0-compa,1-tmpcod,2-ced,3-gruupo,4-tipo,5-codigo,6-nom,7-cant,8-vrund,9-vrtot,10-generico,11-presentacion,12-forma,13-almappal
				}
			}	
		}
	}
	if($CambCed)
	{
		//echo "entra";
		$cons="select tipousu,nivelusu,nocarnet from central.terceros where compania='$Compania[0]' and identificacion='$CambCed'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $NoCarnet=$fila[2]; $Nivelusu=$fila[1]; $Tipousu=$fila[0];
		//echo $cons;
	}
	if($CamPaga){$PagaCont="";}
	if($CamPagaCont){$PagaNocont="";}
	if(($CamPagaNoCont&&$PagaNocont)||($PagaNocont&&$BanProd)){
		$cons="select plantarifario,planbeneficios,plantarifameds,planservmeds,cuotamod from contratacionsalud.contratos 
		where compania='$Compania[0]' and entidad='$Paga' and numero='$PagaNocont' and contrato='$PagaCont'";
		$res=ExQuery($cons); $fila=ExFetch($res);
		$PlanTarfCups=$fila[0]; $PlanServCups=$fila[1]; $PlanTarifMeds=$fila[2]; $PlanServMeds=$fila[3]; $CobraCuota=$fila[4];
		
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
		//$PlanTarfCups=$fila[0]; $PlanServCups=$fila[1]; $PlanTarifMeds=$fila[2]; $PlanServMeds=$fila[3];
		$cons="select grupo,tipo,codigo,cantidad,vrund,vrtotal,nofacturable,almacenppal,nombre from facturacion.tmpcupsomeds where compania='$Compania[0]'
		and tmpcod='$TMPCOD'";
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
					where compania='$Compania[0]' and tmpcod='$TMPCOD' and codigo='$fila[2]' and nombre='$fila[8]'";
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
					where compania='$Compania[0]' and tmpcod='$TMPCOD' and codigo='$fila[2]' and nombre='$fila[8]'";
					$res4=ExQuery($cons4);
					//echo $cons4."<br>";
				}
			}
		}
		if($CobraCuota==1&&$Tipousu&&$Nivelusu&&$Ambito&&$Cedula)
		{
			$consul="select tipoasegurador from central.terceros where identificacion='$Paga' and compania='$Compania[0]' and Tipo='Asegurador'";
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
				
				$Porsentajecopago=$fil[0];	
		}
	}
	if($CodElim)
	{
		$cons="delete from facturacion.tmpcupsomeds where compania='$Compania[0]' and tmpcod='$TMPCOD' and codigo='$CodElim' and tipo='$TipoElim'";	
		$res=ExQuery($cons);
		//echo $cons;
		$CodElim="";$TipoElim="";
	}
?>	
<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function ValidaDocumento(Objeto,NoFac,TMPCOD){
		frames.FrameOpener.location.href="ValidaDocumento.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value+"&NoFac="+NoFac+"&TMPCOD="+TMPCOD;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10px';
		document.getElementById('FrameOpener').style.left='50%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='390';
	}
	function Validar(Tip)
	{
		if(document.FORMA.PagaNocont.value==""){alert("Debe seleccionar una entidad responsable del pago!!!");}
		else{
			if(document.FORMA.FechaExp.value==""){alert("Debe selecionar la fecha de espedicion!!!");}
			else{
				if(document.FORMA.FechaIni.value==""){alert("Debe seleccionar la fecha incial del perido de facturacion!!!");}
				else{
					if(document.FORMA.FechaFin.value==""){alert("Debe seleccionar la fecha final del periodo de facturacion");}
					else{
						if(document.FORMA.FechaFin.value!=""&&document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
							alert("La fecha incial debe ser menor o igual a la fecha final!!!");}
						else{
							if(document.FORMA.Cedula.value!=""){
								if(document.FORMA.Tipousu.value==""){alert("Debe seleccionar el tipo de usuario!!!");}
								else{
									if(document.FORMA.Nivelusu.value==""){alert("Debe seleccionar el nivel del usuario!!!");}
									else{
										if(document.FORMA.FacIndv.value=="1"){
											if(document.FORMA.Ambito.value!=""){alert("Debe seleccionar el Proceso!!");}
											else{
												if(document.FORMA.NumServ.value!=""){
													if(confirm("¿Desea que esta factura se lige a este numero de servicio?")){
														document.FORMA.BanLigaServ.value=1;	
													}
													else{document.FORMA.BanLigaServ.value=0;	}
												}
												if(Tip==1){document.FORMA.GuardarFac.value=1;document.FORMA.submit();}
												if(Tip==2){document.FORMA.AgregarFac.value=1;document.FORMA.submit();}
											}
										}
										else{
											if(Tip==1){document.FORMA.GuardarFac.value=1;document.FORMA.submit();}
											if(Tip==2){document.FORMA.AgregarFac.value=1;document.FORMA.submit();}
										}
									}
								}
							}
							else
							{
								alert("La factura ser guardara como una factura grupal, los datos del paciente se omitiran!!!");
								if(Tip==1){document.FORMA.GuardarFac.value=1;document.FORMA.submit();}
								if(Tip==2){document.FORMA.AgregarFac.value=1;document.FORMA.submit();}
							}
						}
					}
				}
			}
		}
	}
	function RecalcTot()
	{		
		if(document.FORMA.Copago.value>0){
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.Copago.value);
		}
		else{
			document.FORMA.Total.value=parseFloat(document.FORMA.AuxTotal.value)-parseFloat(document.FORMA.Descuento.value);
		}
	}
	function NuevoCoM(e,TMPCOD,Entidad,Contrato,NoContrato,NoFac,FecIni,FecFin,Cedula)	
	{
		//alert(FecFin);
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="CupsoMeds2.php?DatNameSID=<? echo $DatNameSID?>&Entidad="+Entidad+"&Contrato="+Contrato+"&NoContrato="+NoContrato+"&TMPCOD="+TMPCOD+"&FechFin="+FecFin+"&FechIni="+FecIni+"&Cedula="+Cedula+"&NumServ=<? echo $NumServ?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='180px';
		document.getElementById('FrameOpener').style.height='100px';
	}//NumServ NumLiq Cedula
	function DataPartos(e,NumServ,NoLiq,CedPac)
	{
		
		if(document.FORMA.Parto.checked==true)
		{	
			x = e.clientX;
			y = e.clientY;
			st = document.body.scrollTop;
			frames.FrameOpener.location.href="/Facturacion/PartsFacs.php?DatNameSID=<? echo $DatNameSID?>&Fac=1&CedPac=<? echo $CedPac?>&NumServ="+NumServ+"&NoLiq="+NoLiq+"&CedPac="+CedPac;
			document.getElementById('FrameOpener').style.position='absolute';
			document.getElementById('FrameOpener').style.top=(y)+st-150;
			//document.getElementById('FrameOpener').style.top=;
			document.getElementById('FrameOpener').style.left=1;
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='800px';
			document.getElementById('FrameOpener').style.height='350px';
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">  
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    	<td colspan="11">Edicion de Facturas</td>
   	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No de Factura</td>
        <td>
        	<input type="text" name="NoFac" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $NoFac?>" style=" width:100"/>
        </td>
        <td><input type="submit" value="Ver" name="Ver" /></td>
    </tr>
</table>
<br>
<?
if($NoFac)
	{?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No Factura</td>
		<td><? echo "$NoFac"?></td>
		<td align="center" colspan="2"><? echo "<strong>De $FechaIni a $FechaFin</strong>"?></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad</td>
	<?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion
		group by identificacion,primape,segape,primnom,segnom order by primape";?>
		<td colspan="3">
		<select name="Paga" onChange="document.FORMA.CamPaga.value=1;document.FORMA.submit()"><option></option>
	<?	$res=ExQuery($cons);
		while($row = ExFetch($res))
		{
			if($Paga==$row[0])
			{ ?>				
				<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
		 <? }
			else
			{
			?>
				<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
		  <? }
		  }?>
		 </select>
	</tr>
	<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Contrato</td>
	<?	$cons="Select contrato from contratacionsalud.contratos
	where contratos.compania='$Compania[0]' and entidad='$Paga'";
	//echo $cons;?>
	<td>
		<select name="PagaCont" onChange="document.FORMA.CamPagaCont.value=1;document.FORMA.submit()"><option></option>
	 <?	$res=ExQuery($cons);
		while($row = ExFetch($res))
		{
			if($PagaCont==$row[0])
			{ ?>				
				<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
		 <? }
			else
			{
			?>
				<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
		  <? }
		  }?>
		</select>
	</td>
	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No Contrato</td>
	<?	$cons="Select numero from contratacionsalud.contratos 
	where contratos.compania='$Compania[0]' and entidad='$Paga' and contrato='$PagaCont'";
	//echo $cons;?>
	<td>
		<select name="PagaNocont" onChange="document.FORMA.CamPagaNoCont.value=1;document.FORMA.submit()"><option></option>
	 <?	$res=ExQuery($cons);
		while($row = ExFetch($res))
		{
			if($Compania[0]=='Hospital San Rafael de Pasto'&&$PagaCont=='VERBAL AGUDOS Y URGENCIAS'){ 
				if($row[0]==0){$row[0]="0";}				
				if($PagaNocont==0){$PagaNocont="0";}
			}
			if($PagaNocont==$row[0])
			{ ?>				
				<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
		 <? }
			else
			{
			?>
				<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
		  <? } 
		  }?>
		</select>
	</td>   
	</tr>
	<tr>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
   	<td>
    <?	$cons="select Ambito from salud.Ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by Ambito";
		$res=ExQuery($cons);?>
    	<select name="Ambito">
        	<option></option>
        <?	while($fila=ExFetch($res))
			{
				if($Ambito==$fila[0]){echo "<option values='$fila[0]' selected>$fila[0]</option>";}
				else{echo "<option values='$fila[0]'>$fila[0]</option>";}
			}?>
        </select>
    </td>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Expedicion</td>
	<td>
		<!-- <input type="text" readonly="readonly" name="FechaExp" onClick="popUpCalendar(this, FORMA.FechaExp, 'yyyy-mm-dd')" value="<? //echo $FechaExp ?>" style="width:80"/>-->
		<input type="text" name="FechaExp" onClick="popUpCalendar(this, FORMA.FechaExp, 'yyyy-mm-dd')" value="<? echo $FechaExp ?>" style="width:80"/>
	</td>
	</tr>
	<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Inicio</td>
	<td><!--<input type="text" readonly="readonly" name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? //echo $FechaIni?>" style="width:80"/> -->
	<input type="text" name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>" style="width:80"/>
	</td>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>
	<td><!--<input type="text" readonly="readonly" name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? //echo $FechaFin?>" style="width:80"/> -->
	<input type="text" name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>" style="width:80"/>
	</td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td>
		<td><input type="text" name="Cedula" value="<? echo $Cedula?>"  onFocus="ValidaDocumento(this,'<? echo $NoFac?>','<? echo $TMPCOD?>')"  onKeyUp="ValidaDocumento(this,'<? echo $NoFac?>','<? echo $TMPCOD?>');xLetra(this)" onKeyDown="xLetra(this)"></td>
	<?	$cons="select primape,segape,primnom,segnom from central.terceros where identificacion='$Cedula' and compania='$Compania[0]'";
		$res=ExQuery($cons); $fila=ExFetch($res);?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td><td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Carnet</td>
		<td><input type="text" name="NoCarnet" value="<? echo $NoCarnet?>" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:100">
		</td>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Usuario</td>
		<td><select name="Tipousu" onChange="document.FORMA.CambTipoUsu.value=1;document.FORMA.submit();">
		<?	$cons4="select * from salud.tiposusuarios"; 
			$res4=ExQuery($cons4);
			while($fila4=ExFetch($res4))
			{
				if($fila4[0]==$Tipousu){echo "<option selected value='$fila4[0]'>$fila4[0]</option>";}
				else{echo "<option value='$fila4[0]'>$fila4[0]</option>";}
			}
			?>
		</select></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Nivel Usuario</td>
		<td><select name="Nivelusu" onChange="document.FORMA.CambNivelUsu.value=1;document.FORMA.submit();"> 
		 <?	$cons4="select * from salud.nivelesusu"; 
			$res4=ExQuery($cons4);
			while($fila4=ExFetch($res4))
			{
				if($fila4[0]==$Nivelusu){echo "<option selected value='$fila4[0]'>$fila4[0]</option>";}
				else{echo "<option value='$fila4[0]'>$fila4[0]</option>";}
			}?>
			</select>
		</td>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 1</td> 
		<td><input type="text" name="Autorizac1" value="<? echo $Autorizac1?>" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:100"></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 2</td>
		<td><input type="text" name="Autorizac2" value="<? echo $Autorizac2?>" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:100"></td>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Autorizacion 3</td>
		<td><input type="text" name="Autorizac3" value="<? echo $Autorizac3?>" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" style="width:100"></td>
	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">No Servicio</td>
        <td>
        	<input type="text" name="NumServ" value="<? echo $NumServ?>" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" 
        	onKeyUp="xNumero(this)" style="width:100">
       	</td>
        <td>
        	<strong>FURIPS </strong>
            <input type="checkbox" name="Furips" <? if($Furips){?> checked<? }?>>
        </td>
        <td>
        <?	if($Cedula&&$NumLiq)
			{?>
        		<strong>PARTO</strong> <input type="checkbox" name="Parto" onClick="DataPartos(event,'<? echo $NumServ?>','<? echo $NumLiq?>','<? echo $Cedula?>')" 
		<? 		if($Parto){?> checked<? }?>> 
        <?	}?>
        </td>
    </tr>
	</table>
	<br>
	<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">
	   <td colspan="7">
	   <input type="button" value="Agregar CUPS o Medicamentos" 
	   onClick="NuevoCoM(event,<? echo $TMPCOD?>,Paga.value,PagaCont.value,PagaNocont.value,'<? echo $NoFac?>',FechaIni.value,FechaFin.value,Cedula.value)"></td>
	</tr>
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td>Codigo</td><td>Descripcion</td><td>Cantidad</td><td>Vr Unidad</td><td>Vr Total</td><td colspan="2" ></td>
	</tr><?
	$consRES="select restriccioncobro from ContratacionSalud.Contratos 
	where compania='$Compania[0]' and entidad='$Paga' and 		contrato='$PagaCont' and numero='$PagaNocont'";
	$resRES=ExQuery($consRES);
	$filaRES=ExFetch($resRES); //$RestricCobro=$filaRES[0];
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
	
	$cons5="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]' group by grupo,codigo order by grupo ";
	$res5=ExQuery($cons5);
	while($fila5=ExFetch($res5))
	{
		$cons="select  grupo,tipo,codigo,nombre,sum(cantidad),vrund,sum(vrtotal),generico,presentacion,forma,almacenppal from facturacion.tmpcupsomeds
		where tmpcod='$TMPCOD' and grupo='$fila5[1]' group by grupo,tipo,codigo,nombre,vrund,generico,presentacion,forma,almacenppal";
		$res=ExQuery($cons);
		$banFac=0;
		$Sub=0;
		if(ExNumRows($res)>0){		
			while($fila=ExFetch($res))
			{
				$banFac=1;?>
				<tr>        
			<? 	//tmp->0-compa,1-tmpcod,2-ced,3-gruupo,4-tipo,5-codigo,6-nom,7-cant,8-vrund,9-vrtot,10-generico,11-presentacion,12-forma,13-almappal
				echo "<td align='center'>$fila[2]</td><td>".strtoupper("$fila[3] $fila[8] $fila[9]")."</td><td align='right'>$fila[4]</td>
				<td align='right'>".number_format($fila[5],2)."</td><td align='right'>".number_format($fila[6],2)."</td>";?>
				<td><img style="cursor:hand"  title="Eliminar" 
					onClick="if(confirm('Desea eliminar este registro?')){
							document.FORMA.CodElim.value='<? echo $fila[2]?>';document.FORMA.TipoElim.value='<? echo trim($fila[1])?>';
                            document.FORMA.submit();
                        }" 
					src="/Imgs/b_drop.png"> 
				</td></tr>
			<?	$VrTot=$VrTot+$fila[6];
				$Sub=$Sub+$fila[6];
			}
			/*if($RestricCobro&&$Rescric)
			{echo "ENTRAAAA";
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
			{*/
				if($banFac==1){
				?>	<tr bgcolor="#ECECEC">
						<td colspan="4" align="right"><strong><? echo $fila5[0]?></strong><td  align="right"><? echo number_format($Sub,2)?></td>
						<td colspan="2">&nbsp;</td>
					</tr><?
				}
			//}
		}
	}?>
     <tr>
    	<td colspan="4"  style="font-weight:bold" align="right">SUBTOTAL</td>
        <?	$Subtotal=$VrTot;?>
        <td><input type="text" name="Subtotal" value="<? echo $Subtotal?>" style="width:80; text-align:right" readonly></td><td colspan="2">&nbsp;</td>
    </tr>  
    <tr>
    <?	if(!$Descuento){$Descuento="0";}?>
    	<td colspan="4"  style="font-weight:bold" align="right">Descuento</td>
        <td><input type="text" name="Descuento" value="<? echo $Descuento?>" style="width:80; text-align:right"></td><td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4"  style="font-weight:bold" align="right">Copago</td>
        <td><input type="text" name="Copago" value="<? echo $Copago?>" style="width:80; text-align:right" onKeyDown="xNumero(this)" 
        onKeyUp="xNumero(this);RecalcTot();"  onKeyPress="xNumero(this);"></td>
        <td colspan="2">&nbsp;</td>
    </tr> 
    <tr>
    	<td><strong>Motivo No Copago</strong></td>
        <td colspan="4">
        	<input type="text" name="MsjCopago" value="<? echo $MsjCopago?>" style="width:100%">
        </td>
    </tr>
    <tr>
    	<td colspan="4"  style="font-weight:bold" align="right">TOTAL</td>
 	<?	$Total=$VrTot-($Copago+$Descuento);?>
    	<input type="hidden" name="AuxTotal" value="<? echo $Total?>">
        <td><input type="text" name="Total" value="<? echo $Total?>" style="width:80; text-align:right" readonly></td><td colspan="2">&nbsp;</td>
    </tr>   
	<tr>
    	<td colspan="7" align="center">
        	<input type="button" value="Guardar" onClick="Validar(1)" >
            <input type="button" value="Agregar Como Nueva Factura" onClick="Validar(2)"> 
        </td>
	</tr><?
}?>

<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="CodElim" value="">
<input type="hidden" name="CambCed" value="">
<input type="hidden" name="CamPaga" value="">
<input type="hidden" name="CamPagaNoCont" value="">
<input type="hidden" name="CamPagaCont" value="">
<input type="hidden" name="TipoElim" value="">
<input type="hidden" name="GuardarFac" value="">
<input type="hidden" name="CambTipoUsu" value="">
<input type="hidden" name="CambNivelUsu" value="">
<input type="hidden" name="BanProd" value="">
<input type="hidden" name="AgregarFac" value="">
<input type="hidden" name="PagaAnt" value="<? echo $PagaAnt?>">
<input type="hidden" name="PagaContAnt" value="<? echo $PagaContAnt?>">
<input type="hidden" name="PagaNocontAnt" value="<? echo $PagaNocontAnt?>">
<input type="hidden" name="FacIndv" value="<? echo $FacIndv?>">
<input type="hidden" name="NumLiq" value="<? echo $NumLiq?>">
<!--<input type="hidden" name="NumServ" value="<? echo $NumServ?>">-->
<input type="hidden" name="BanLigaServ">

</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1"></iframe> 
</body>
<? if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	//require('LibPDF/fpdf.php');
	require('LibPDF/rotation.php');
	$d=date('w',mktime(0,0,0,$F[1],$F[2],$F[0]));	
	switch($d){
		case 1: $Diasem='Lun'; break;
		case 2: $Diasem='Mar'; break;
		case 3: $Diasem='Mie'; break;
		case 4: $Diasem='Juv'; break;
		case 5: $Diasem='Vie'; break;
		case 6: $Diasem='Sab'; break;
		case 0: $Diasem='Dom'; break;
	}
	global $Individual;
	$ND=getdate();
	$cons="select grupo,grupofact from consumo.grupos where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$GruposMeds[$fila[0]]=$fila[1];
	}
	
	$ND=getdate();
	if($ND[mon]<10){$cero1='0';}else{$cero1='';}
	if($ND[mday]<10){$cero2='0';}else{$cero2='';}
	if($ND[hours]<10){$cero3='0';}else{$cero3='';}
	if($ND[minutes]<10){$cero4='0';}else{$cero4='';}
	if($ND[seconds]<10){$cero5='0';}else{$cero5='';}	
	//echo "Desde:$$NoLiqConsecIni hasta:$NoLiqConsecFin<br>";
	//echo $NoLiqConsecIni." | ".$NoLiqConsecFin;
	if($NoLiqConsecIni){ 
		if($NoLiqConsecIni==$NoLiqConsecFin){
			$NoLiquidacion=$NoLiqConsecIni; 
			$Individual=1;
		}
		else{
			$NoLiquidacion="";		
			for($i=$NoLiqConsecIni;$i<=$NoLiqConsecFin;$i++){
				$NoLiquidacion=$i;	
						
				$cons="select ambito,medicotte,liquidacion.nocarnet,liquidacion.tipousu,liquidacion.nivelusu,autorizac1,autorizac2,autorizac3,
				(primnom || segnom || primape || segape) as eps,pagador,contrato,nocontrato,numservicio,valorcopago,direccion,telefono,tipoasegurador,codigosgsss,tipocopago,clasecopago,
				porsentajecopago,valordescuento,porsentajedesc,subtotal,total,estado,noliquidacion,cedula ,fechacrea
				from facturacion.liquidacion,central.terceros where terceros.compania='$Compania[0]' 
				and liquidacion.compania='$Compania[0]' and noliquidacion=$NoLiquidacion and identificacion=pagador";					
				$res=ExQuery($cons);
				$fila=ExFetch($res);				
				$Datos[$NoLiquidacion]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19],$fila[20],$fila[21],$fila[22],$fila[23],$fila[24],$fila[25],$fila[26],$fila[27],$fila[28]);	 	
				//echo "$cons<br>";			
				
	    $consRC="select restriccioncobro from ContratacionSalud.Contratos where compania='$Compania[0]' 
		         and entidad='$fila[9]' and contrato='$fila[10]' and numero='$fila[11]'";
		$resRC=ExQuery($consRC);
		$filaRC=ExFetch($resRC); 
		$RestricCobro=$filaRC[0];
        if($RestricCobro==1)
		{
			$consRestric="select grupo from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$fila[9]' and contrato='$fila[10]' and nocontrato='$fila[11]'";
			$resRestric=ExQuery($consRestric);			
			while($filaRestric=ExFetch($resRestric))
			{
				
				$Paciente[$fila[27]][1]=$fila[27];
				$consP="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[27]'";
				$resP=ExQuery($consP);
				$filaP=ExFetch($resP);				
				$Paciente[$fila[27]][2]=$filaP[0]; $Paciente[$fila[27]][3]=$filaP[1]; $Paciente[$fila[27]][4]=$filaP[3]; $Paciente[$fila[27]][5]=$filaP[2]; 
				//echo "<br>".$Paciente[$fila[27]][1]."<br>";
				$consP=""; $resP=""; $filaP="";
				
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo='$filaRestric[0]'
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip--,fechacrea order by fechacrea desc limit 1";	
				$res2=ExQuery($cons2);
				//echo "$cons2<br>";
				if(ExNumRows($res2)==0){
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip";	
				$res2=ExQuery($cons2);}
				
				$C=1;
				while($fila2=ExFetch($res2)){
				
				
				
				
				$cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
		and movimiento.fechadespacho<='$FechaFin' and cum='$fila2[11]' group by autoid
		order by autoid";
		$res4=ExQuery($cons4);		
		$fila4=ExFetch($res4);	
            if(!$fila4[0])$autoid='null';
               else $autoid=$fila4[0];			
			$cons3 = "Select codigo2,grupo,codigo1
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='FARMACIA' and CodProductos.Anio=$ND[year] and CodProductos.autoid=$autoid				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
				
				
				
					if($GruposMeds[$fila2[2]]){$fila2[2]=$GruposMeds[$fila2[2]];} 
					$Pac[$fila[27]]=1;
					//echo "<br>".$NoLiquidacion." | ".$fila[27]." | ".$fila2[0];
					if($fila3[1]=="Dispositivo Medico")
					   $Id="$NoLiquidacion$fila[27]|$C";
					   else 
					       $Id="$NoLiquidacion$fila[27]$fila2[0]";
						   
					//echo "NoLiquidacion=$NoLiquidacion fila[27]=$fila[27] fila2[0]=$fila2[0]<br>";
					//echo "<br>".$fila2[0]." | ".$fila2[1]." | ".$fila2[2]." | ".$fila2[3]." | ".$fila2[4]." | ".$fila2[5]." | ".$fila2[6]." | ".$fila2[7]." | ".$fila2[8]." | ".$fila2[9]." | ".$fila2[10]." | ".$fila2[11]." | ".$fila3[0];
					$CUPsMED[$Id][$NoLiquidacion]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10],$fila2[11],$fila3[0],$fila2[12]);
					//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
				$C++;}	}}
        else{
		
		$Paciente[$fila[27]][1]=$fila[27];
				$consP="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[27]'";
				$resP=ExQuery($consP);
				$filaP=ExFetch($resP);				
				$Paciente[$fila[27]][2]=$filaP[0]; $Paciente[$fila[27]][3]=$filaP[1]; $Paciente[$fila[27]][4]=$filaP[3]; $Paciente[$fila[27]][5]=$filaP[2]; 
				//echo "<br>".$Paciente[$fila[27]][1]."<br>";
				$consP=""; $resP=""; $filaP="";
				
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip";	
				$res2=ExQuery($cons2);
				//echo "$cons2<br>";
				
				$C=1;
				while($fila2=ExFetch($res2)){
				
				
				
				
				$cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
		and movimiento.fechadespacho<='$FechaFin' and cum='$fila2[11]' group by autoid
		order by autoid";
		$res4=ExQuery($cons4);		
		$fila4=ExFetch($res4);	
            if(!$fila4[0])$autoid='null';
               else $autoid=$fila4[0];			
			$cons3 = "Select codigo2,grupo,codigo1
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='FARMACIA' and CodProductos.Anio=$ND[year] and CodProductos.autoid=$autoid				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
				
				
				
					if($GruposMeds[$fila2[2]]){$fila2[2]=$GruposMeds[$fila2[2]];} 
					$Pac[$fila[27]]=1;
					//echo "<br>".$NoLiquidacion." | ".$fila[27]." | ".$fila2[0];
					if($fila3[1]=="Dispositivo Medico")
					   $Id="$NoLiquidacion$fila[27]|$C";
					   else 
					       $Id="$NoLiquidacion$fila[27]$fila2[0]";
						   
					//echo "NoLiquidacion=$NoLiquidacion fila[27]=$fila[27] fila2[0]=$fila2[0]<br>";
					//echo "<br>".$fila2[0]." | ".$fila2[1]." | ".$fila2[2]." | ".$fila2[3]." | ".$fila2[4]." | ".$fila2[5]." | ".$fila2[6]." | ".$fila2[7]." | ".$fila2[8]." | ".$fila2[9]." | ".$fila2[10]." | ".$fila2[11]." | ".$fila3[0];
					$CUPsMED[$Id][$NoLiquidacion]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10],$fila2[11],$fila3[0],$fila2[12]);
					//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
				$C++;}
				
		}				
				$cons=""; $res=""; $fila="";				
			}
		}			
	}
	if($NoLiqConsecIni==$NoLiqConsecFin){
		$cons="select ambito,medicotte,liquidacion.nocarnet,liquidacion.tipousu,liquidacion.nivelusu,autorizac1,autorizac2,autorizac3,
				(primnom || segnom || primape || segape) as eps,pagador,contrato,nocontrato,numservicio,valorcopago,direccion,telefono,tipoasegurador,codigosgsss,tipocopago,clasecopago,
				porsentajecopago,valordescuento,porsentajedesc,subtotal,total,estado,noliquidacion,cedula ,fechacrea
				from facturacion.liquidacion,central.terceros where terceros.compania='$Compania[0]' 
				and liquidacion.compania='$Compania[0]' and noliquidacion=$NoLiquidacion and identificacion=pagador";					
				$res=ExQuery($cons);
				$fila=ExFetch($res);				
				$Datos[$NoLiquidacion]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19],$fila[20],$fila[21],$fila[22],$fila[23],$fila[24],$fila[25],$fila[26],$fila[27],$fila[28]);	 	
				//echo "$cons<br>";			
				
	    $consRC="select restriccioncobro from ContratacionSalud.Contratos where compania='$Compania[0]' 
		         and entidad='$fila[9]' and contrato='$fila[10]' and numero='$fila[11]'";
		$resRC=ExQuery($consRC);
		$filaRC=ExFetch($resRC); 
		$RestricCobro=$filaRC[0];
        if($RestricCobro==1)
		{ 
		$consRestric="select grupo from contratacionsalud.restriccionescobro 
			where compania='$Compania[0]' and entidad='$fila[9]' and contrato='$fila[10]' and nocontrato='$fila[11]'";
			$resRestric=ExQuery($consRestric);			
			while($filaRestric=ExFetch($resRestric))
			{				
				$Paciente[$fila[27]][1]=$fila[27];
				$consP="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[27]'";
				$resP=ExQuery($consP);
				$filaP=ExFetch($resP);				
				$Paciente[$fila[27]][2]=$filaP[0]; $Paciente[$fila[27]][3]=$filaP[1]; $Paciente[$fila[27]][4]=$filaP[3]; $Paciente[$fila[27]][5]=$filaP[2]; 
				//echo "<br>".$Paciente[$fila[27]][1]."<br>";
				$consP=""; $resP=""; $filaP="";
				
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo='$filaRestric[0]'
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip--,fechacrea order by fechacrea desc limit 1";	
				$res2=ExQuery($cons2);
				//echo "$cons2<br>";
				if(ExNumRows($res2)==0){
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip";	
				$res2=ExQuery($cons2);}

				$C=1;
				while($fila2=ExFetch($res2)){
				
				
				
				if($ActCtAg)
			$cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$ND[year]-$ND[mon]-$ND[mday]' 
		and movimiento.fechadespacho<='$ND[year]-$ND[mon]-$ND[mday]' 
		and cum='$fila2[11]' group by autoid
		order by autoid";
		else
			    $cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
		and movimiento.fechadespacho<='$FechaFin' 
		and cum='$fila2[11]' group by autoid
		order by autoid";
		$res4=ExQuery($cons4);		
		$fila4=ExFetch($res4);	
            if(!$fila4[0])$autoid='null';
               else $autoid=$fila4[0];			
			$cons3 = "Select codigo2,grupo
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='FARMACIA' and CodProductos.Anio=$ND[year] and CodProductos.autoid=$autoid				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
				
				
				
					if($GruposMeds[$fila2[2]]){$fila2[2]=$GruposMeds[$fila2[2]];} 
					$Pac[$fila[27]]=1;
					//echo "<br>".$NoLiquidacion." | ".$fila[27]." | ".$fila2[0];
					if($fila3[1]=="Dispositivo Medico")
					   $Id="$NoLiquidacion$fila[27]|$C";
					   else 
					       $Id="$NoLiquidacion$fila[27]$fila2[0]";
					//echo "NoLiquidacion=$NoLiquidacion fila[27]=$fila[27] fila2[0]=$fila2[0]<br>";
					//echo "<br>"./*$fila2[0]." | ".$fila2[1]." | ".$fila2[2]." | ".$fila2[3]." | ".$fila2[4]." | ".$fila2[5]." | ".$fila2[6]." | ".$fila2[7]." | ".$fila2[8]." | ".$fila2[9]." | ".$fila2[10]." | ".$fila2[11]." | ".*/$fila3[0];
					$CUPsMED[$Id][$NoLiquidacion]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10],$fila2[11],$fila3[0],$fila2[12]);
					//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
				$C++;}	}}
		else{
		        $Paciente[$fila[27]][1]=$fila[27];
				$consP="select primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion='$fila[27]'";
				$resP=ExQuery($consP);
				$filaP=ExFetch($resP);				
				$Paciente[$fila[27]][2]=$filaP[0]; $Paciente[$fila[27]][3]=$filaP[1]; $Paciente[$fila[27]][4]=$filaP[3]; $Paciente[$fila[27]][5]=$filaP[2]; 
				//echo "<br>".$Paciente[$fila[27]][1]."<br>";
				$consP=""; $resP=""; $filaP="";
				
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal),cum,rip 
				from facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal,cum,rip";	
				$res2=ExQuery($cons2);
				//echo "$cons2<br>";
				$C=1;
				while($fila2=ExFetch($res2)){
				
				
				
				if($ActCtAg)
			$cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$ND[year]-$ND[mon]-$ND[mday]' 
		and movimiento.fechadespacho<='$ND[year]-$ND[mon]-$ND[mday]' 
		and cum='$fila2[11]' group by autoid
		order by autoid";
		else
			    $cons4="select autoid from consumo.movimiento,consumo.almacenesppales
		where movimiento.compania='$Compania[0]' and cedula='$fila[27]' and tipocomprobante='Salidas' and noliquidacion is null and almacenesppales.compania='$Compania[0]'	
		and almacenesppales.almacenppal=movimiento.almacenppal and ssfarmaceutico=1 and estado='AC' and movimiento.fechadespacho>='$FechaIni' 
		and movimiento.fechadespacho<='$FechaFin' 
		and cum='$fila2[11]' group by autoid
		order by autoid";
		$res4=ExQuery($cons4);		
		$fila4=ExFetch($res4);	
            if(!$fila4[0])$autoid='null';
               else $autoid=$fila4[0];			
			$cons3 = "Select codigo2,grupo
			from Consumo.CodProductos,ContratacionSalud.TiposdeProdxFormulacion,Consumo.TarifasxProducto
			where CodProductos.TipoProducto = TiposdeProdxFormulacion.TipoProducto and clasificacion is not null
			and TarifasxProducto.Compania='$Compania[0]' and TarifasxProducto.autoid=CodProductos.autoid and CodProductos.Compania='$Compania[0]' 
			and TiposdeProdxFormulacion.AlmacenPpal='FARMACIA' and CodProductos.Anio=$ND[year] and CodProductos.autoid=$autoid				
			group by Codigo1,NombreProd1,grupo,CodProductos.tipoproducto,UnidadMedida,Presentacion,CodProductos.TipoProducto,valorventa,pos,codigo2
			order by ( NombreProd1 || ' ' || Presentacion || ' ' || UnidadMedida) asc";						
			$res3=ExQuery($cons3); 
			$fila3=ExFetch($res3);
				
				
				
					if($GruposMeds[$fila2[2]]){$fila2[2]=$GruposMeds[$fila2[2]];} 
					$Pac[$fila[27]]=1;
					//echo "<br>".$NoLiquidacion." | ".$fila[27]." | ".$fila2[0];
					if($fila3[1]=="Dispositivo Medico")
					   $Id="$NoLiquidacion$fila[27]|$C";
					   else 
					       $Id="$NoLiquidacion$fila[27]$fila2[0]";
					//echo "NoLiquidacion=$NoLiquidacion fila[27]=$fila[27] fila2[0]=$fila2[0]<br>";
					//echo "<br>"./*$fila2[0]." | ".$fila2[1]." | ".$fila2[2]." | ".$fila2[3]." | ".$fila2[4]." | ".$fila2[5]." | ".$fila2[6]." | ".$fila2[7]." | ".$fila2[8]." | ".$fila2[9]." | ".$fila2[10]." | ".$fila2[11]." | ".*/$fila3[0];
					$CUPsMED[$Id][$NoLiquidacion]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10],$fila2[11],$fila3[0],$fila2[12]);
					//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
				$C++;}
		
		}
				$cons=""; $res=""; $fila="";		
	}	
	
	$cons="select grupo,almacenppal from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsMeds[$fila[0]]=array($fila[0],$fila[1]);
	}	
	$cons="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsCUPs[$fila[1]]=array($fila[0],$fila[1]);
	}	
	
	/*
	function DatosPrueba($data,$NoLiquidacion,$Paciente)
	{
		global $CUPsMED; global $GrupsMeds; global $GrupsCUPs; global $ND; global $Compania; 
		
		//MEDICAMENTOS
		foreach($GrupsMeds as $GM)
		{				
			foreach($CUPsMED as $Meds)
			{					
				if($Meds[$NoLiquidacion][2]==$GM[0]&&$Meds[$NoLiquidacion][3]=="Medicamentos"&&$Meds[$NoLiquidacion][9]==$GM[1])
				{	
					echo $Meds[$NoLiquidacion][1]."<br>";
				}
			}				
		}
			//CUPS
		foreach($GrupsCUPs as $GC)
		{	
			$ban=0;
			$SubTot=0;
			foreach($CUPsMED as $CUPs)
			{				
				if($CUPs[$NoLiquidacion][2]==$GC[1]&&$CUPs[$NoLiquidacion][3]!="Medicamentos")
				{	
					echo $CUPs[$NoLiquidacion][1]."<br>";
				}
			}				
		}
	}
	
	$Datos2=$Datos;
	foreach($Datos as $Info)
	{			
		echo $Datos2[$Info[26]][26].",$Info[26],".$Paciente[$Info[27]][1]."<br>";	
		DatosPrueba($Datos,$Info[26],$Paciente[$Info[27]][1]);
	}*/
class PDF extends PDF_Rotate
{
	function Header1($NoLiquidacion,$Ini)
	{
		$this->AddPage();		
		global $Compania; //global $Anio;global $MesIni;global $DiaIni;global $MesFin;global $DiaFin;global $ND;
		$Raiz = $_SERVER['DOCUMENT_ROOT'];
		$this->Image("$Raiz/Imgs/Logo.jpg",10,7,20,20);
		$this->SetFont('Arial','B',10);				
		$this->Cell(20,5,"",0,0,'L');
		$this->Cell(125,5,strtoupper($Compania[0]),0,0,'L');		
		$this->SetFont('Arial','B',10);						
		$this->Cell($this->GetStringWidth("LIQUIDACION No. $NoLiquidacion")+1,5,"LIQUIDACION No. $NoLiquidacion",0,0,'C');
		$this->SetFont('Arial','',8);
		$this->Ln(4);		
		$this->Cell(20,5,"",0,0,'L');
		$this->Cell(75,5,strtoupper($Compania[1]),0,0,'L');							
		$this->SetFont('Arial','',8);
		$this->Ln(4);
		$this->Cell(20,5,"",0,0,'L');
		$this->Cell(85,5,"CODIGO SGSSS ".strtoupper($Compania[17]),0,0,'L');	
		$this->Ln(4);
		$this->SetFont('Arial','',8);
		$this->Cell(20,5,"",0,0,'L');
		$this->Cell(0,5,$Compania[2]." - TELEFONOS: ".strtoupper($Compania[3]),0,0,'L');		
		if($Ini==1){
			$this->Ln(12);
		}
		else{
			$this->Ln(8);
		}
	}
	function Header2($data,$NoLiquidacion,$Paciente)
	{
		//$this->AddPage();
		//DATOS CLIENTE		
		$this->SetFont('Arial','B',8);				
		$this->Cell(10,5,"",0,0,'L');
		$this->Cell(18,5,"CLIENTE:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(0,5,strtoupper(utf8_decode($data[$NoLiquidacion][8])),0,0,'L');		
		$this->Ln(4);		
		$this->SetFont('Arial','B',8);				
		$this->Cell(10,5,"",0,0,'L');
		$this->Cell(18,5,"NIT:",0,0,'L');		
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,$data[$NoLiquidacion][9],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"CODIGO SGSSS:",0,0,'L');			
		$this->SetFont('Arial','',8);
		$this->Cell(27,5,$data[$NoLiquidacion][17],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(15,5,"REGIMEN:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(30,5,strtoupper($data[$NoLiquidacion][16]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(62,5,substr(strtoupper($data[$NoLiquidacion][10]),0,28),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"No CONTRATO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(74,5,strtoupper($data[$NoLiquidacion][11]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"DIRECCION:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(62,5,substr($data[$NoLiquidacion][14],0,38),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(24,5,"TELEFONO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(35,5,$data[$NoLiquidacion][15],0,0,'L');				
		//$this->Rect(20,35,182,17);	//RECTANGULO CLIENTE	
		$this->Ln(10);
		
		//DATOS PACIENTE
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"PACIENTE:",0,0,'L');
		$this->SetFont('Arial','',8);
		$NomPac=strtoupper(utf8_decode("$Paciente[2] $Paciente[3] $Paciente[5] $Paciente[4]"));		
		$this->Cell(101,5,substr($NomPac,0,52),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"IDENTIFICACION:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(26,5,$Paciente[1],0,0,'L');		
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(18,5,"No CARNET:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(40,5,$data[$NoLiquidacion][2],0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(27,5,"TIPO DE USUARIO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(34,5,strtoupper($data[$NoLiquidacion][3]),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"NIVEL DE USUARIO:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(20,5,strtoupper($data[$NoLiquidacion][4]),0,0,'L');
		$this->Ln(4);	
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(26,5,"AUTORIZACION 1:",0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(32,5,substr($data[$NoLiquidacion][5],0,14),0,0,'L');
		$this->SetFont('Arial','B',8);	
		$this->Cell(27,5,"AUTORIZACION 2:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(34,5,substr($data[$NoLiquidacion][6],0,14),0,0,'L');				
		$this->SetFont('Arial','B',8);	
		$this->Cell(29,5,"AUTORIZACION 3:",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(32,5,substr($data[$NoLiquidacion][7],0,14),0,0,'L');	
		$this->Ln(4);
		$this->Cell(10,5,"",0,0,'L');	
		$this->SetFont('Arial','B',8);	
		$this->Cell(32,5,"FECHA EXPEDICION: ",0,0,'L');
		$this->SetFont('Arial','',8);		
		$this->Cell(32,5,substr($data[$NoLiquidacion][28],0,11),0,0,'L');
	}
	function Titulos($Ini){
		//Titulos
		if($Ini==1){
			$this->Ln(12);		
		}
		$this->SetFillColor(228,228,228);
		$this->SetFont('Arial','B',8);	
		$this->Cell(20,5,"CODIGO/CUM",1,0,'C',1);
		$this->Cell(100,5,"DESCRIPCION",1,0,'C',1);
		$this->Cell(20,5,"CANTIDAD",1,0,'C',1);
		$this->Cell(25,5,"VR UNIDAD",1,0,'C',1);
		$this->Cell(29,5,"VR TOTAL",1,0,'C',1);
		$this->SetFont('Arial','',8);
	}
	function Datos($data,$NoLiquidacion,$Paciente)
	{
		global $CUPsMED; global $GrupsMeds; global $GrupsCUPs; global $ND; global $Compania; 
		
		$this->Titulos(1);
		
		//MEDICAMENTOS		
		if(!empty($GrupsMeds))
		{//echo $GrupsMeds;
			foreach($GrupsMeds as $GM)
			{	
				$ban=0;
				$SubTot=0;		
				if($CUPsMED){
					foreach($CUPsMED as $Meds)
						{	
								
						if($Meds[$NoLiquidacion][2]==$GM[0]&&$Meds[$NoLiquidacion][3]=="Medicamentos"&&$Meds[$NoLiquidacion][9]==$GM[1])
						{	
							//for($j=0;$j<20;$j++){						
							$ban=1;
							$this->SetFont('Arial','',8);
							$this->Ln(5);					
							$this->Cell(20,5,substr($Meds[$NoLiquidacion][0],0,12),"LR",0,'C');					
							$this->Cell(100,5,strtoupper(substr($Meds[$NoLiquidacion][1]/*." ".$Meds[$NoLiquidacion][7]." ".$Meds[$NoLiquidacion][6]*/,0,52)),"LR",0,'C');					
							$this->Cell(20,5,substr($Meds[$NoLiquidacion][8],0,12),"LR",0,'R');
							$this->Cell(25,5,number_format($Meds[$NoLiquidacion][4],2),"LR",0,'R');					
							$this->Cell(29,5,number_format(($Meds[$NoLiquidacion][10]),2),"LR",0,'R');
							$SubTot=$SubTot+($Meds[$NoLiquidacion][10]);					
							$GranSubTot=$SubTot+$GranSubTot;	
							//echo "Entraaaaaaaa";
							$POSY=$this->GetY();
							if($POSY>=250 && $POSY<255){		
								$this->Ln(5);										
								$this->Cell(194,1,"","T",0,'L');				
								$this->Header1($NoLiquidacion,0);
								$this->Titulos(0);						
							}				
							//}
						}
					}	
				}
				
				if($ban==1){
					$POSY=$this->GetY();
						if($POSY>=250 && $POSY<255){						
							$this->Header1($NoLiquidacion,0);
							$this->Titulos(0);
						}
					$this->Ln(5);
					$this->SetFillColor(240,240,240);	
					$this->SetFont('Arial','B',8);	
					$this->Cell(165,5,strtoupper($GM[0]),1,0,'R',1);
					$this->Cell(29,5,number_format($SubTot,2),1,0,'R',1);
						$POSY=$this->GetY();
						if($POSY>=250 && $POSY<255){						
							$this->Header1($NoLiquidacion,0);
							$this->Titulos(0);
						}
				}
			}
		}
		//CUPS
		//echo $GrupsCUPs;
		foreach($GrupsCUPs as $GC)
		{	
			$ban=0;
			
			if($CUPsMED){
				$SubTot=0;	
				foreach($CUPsMED as $CUPs)
				{				
					if($GC[1]==38)$DM='';else$DM=" -ATC ".$CUPs[$NoLiquidacion][12];
					if($GC[1]==38)$Code=substr($CUPs[$NoLiquidacion][13],0,12);else$Code=substr($CUPs[$NoLiquidacion][0],0,12);
					if($CUPs[$NoLiquidacion][12])$ATC=$DM;
					if($CUPs[$NoLiquidacion][2]==$GC[1])
					{	
						$POSY=$this->GetY();
						if($POSY>=250 && $POSY<255){
							$this->Header1($NoLiquidacion,0);
							$this->Titulos(0);
						}
						$ban=1;					
						$this->Ln(5);				
						$this->SetFont('Arial','',7);
						/*if($CUPs[$NoLiquidacion][12])
						   $this->Cell(20,5,substr($CUPs[$NoLiquidacion][12],0,12),"LR",0,'C');	
						   else*/
						    $this->Cell(20,5,/*substr($CUPs[$NoLiquidacion][0],0,12)*/$Code,"LR",0,'C');					
						//$this->Cell(100,5,strtoupper(substr($CUPs[$NoLiquidacion][1]." ".$CUPs[$NoLiquidacion][6]." ".$CUPs[$NoLiquidacion][7],0,52)),"LR",0,'C');					
						//echo "<br>".$GC[1];
						//if($GC[1]==38)$DM='';else$DM=" -ATC ".$CUPs[$NoLiquidacion][12];
						//if($CUPs[$NoLiquidacion][12])$ATC=$DM;
						$this->Cell(100,5,strtoupper(substr($CUPs[$NoLiquidacion][1]." ".$CUPs[$NoLiquidacion][6]." ".$CUPs[$NoLiquidacion][7],0,52)).$ATC,"LR",0,'C');					
						$this->Cell(20,5,substr(/*round*/($CUPs[$NoLiquidacion][8]/*,0*/),0,12),"LR",0,'R');
						$this->Cell(25,5,number_format(/*round*/($CUPs[$NoLiquidacion][4]/*,0*/),2),"LR",0,'R');
						//$this->Cell(29,5,substr("968588493493499349934141234134",0,17),"LRTB",0,'R');
						//$this->Cell(29,5,number_format(round($CUPs[$NoLiquidacion][10],0),2),"LR",0,'R');
						
	//echo $CUPs[$NoLiquidacion][1]." ".$CUPs[$NoLiquidacion][6]." ".$CUPs[$NoLiquidacion][7]." vrund=".round($CUPs[$NoLiquidacion][4],0)." ".round($CUPs[$NoLiquidacion][10],0)."<br>";
						if(round($CUPs[$NoLiquidacion][10],0)==0){
							$this->Cell(29,5,number_format(round($CUPs[$NoLiquidacion][10],0),2),"LR",0,'R');
							$SubTot=$SubTot+round($CUPs[$NoLiquidacion][10],0);
							$GranSubTot=$GranSubTot+round($CUPs[$NoLiquidacion][10],0);
							//echo "Entra";
						}
						elseif(round($CUPs[$NoLiquidacion][10],0)==round($CUPs[$NoLiquidacion][10],0))
						{
							$this->Cell(29,5,number_format(round($CUPs[$NoLiquidacion][10],0),2),"LR",0,'R');
							$SubTot=$SubTot+round($CUPs[$NoLiquidacion][10],0);
							$GranSubTot=$GranSubTot+round($CUPs[$NoLiquidacion][10],0);
						}
						else{							
							$this->Cell(29,5,number_format((round($CUPs[$NoLiquidacion][4],0)*round($CUPs[$NoLiquidacion][8],0)),2),"LR",0,'R');
							$SubTot=$SubTot+round(round($CUPs[$NoLiquidacion][4],0)*round($CUPs[$NoLiquidacion][8],0));
							$GranSubTot=$GranSubTot+round(round($CUPs[$NoLiquidacion][4],0)*round($CUPs[$NoLiquidacion][8],0));	
							//echo $CUPs[$NoLiquidacion][0]." SubTot=$SubTot ".round($CUPs[$NoLiquidacion][4],0)." ".round($CUPs[$NoLiquidacion][8],0)."<br>";
						}
						
	//echo $CUPs[$NoLiquidacion][0]." Cantidad=".round($CUPs[$NoLiquidacion][8],0)." vrUnidad=".round($CUPs[$NoLiquidacion][4],0)." vrtotal=".(round($CUPs[$NoLiquidacion][4],0)*round($CUPs[$NoLiquidacion][8],0))."SubTot=$SubTot GranSubTot=$GranSubTot<br>";
						$POSY=$this->GetY();
						if($POSY>=250 && $POSY<255){						
							$this->Ln(5);										
							$this->Cell(194,1,"","T",0,'L');
							$this->Header1($NoLiquidacion,0);
							$this->Titulos(0);
						}								
					}
				}	
			}
			if($ban==1){
				$POSY=$this->GetY();
				if($POSY>=250 && $POSY<255){						
					$this->Header1($NoLiquidacion,0);
					$this->Titulos(0);
				}
				$this->Ln(5);
				$this->SetFillColor(240,240,240);	
				$this->SetFont('Arial','B',8);	
				$this->Cell(165,5,strtoupper($GC[0]),1,0,'R',1);
				$this->Cell(29,5,number_format($SubTot,2),1,0,'R',1);
				$POSY=$this->GetY();
					if($POSY>=250 && $POSY<255){						
						$this->Header1($NoLiquidacion,0);
						$this->Titulos(0);
					}
			}
		}
		
		if($data[$NoLiquidacion][22]!=''&&$data[$NoLiquidacion][22]!="0"&&$data[$NoLiquidacion][23]!=''){
			$Limite=201.00125;
		}
		else{
			$Limite=206.00125;
		}
		$POSY=$this->GetY();	
		while($POSY<$Limite){			
			$this->Ln(5);
			$this->Cell(120,5,"","LR",0,'L');
			$this->Cell(20,5,"","LR",0,'L');
			$this->Cell(25,5,"","LR",0,'L');
			$this->Cell(29,5,"","LR",0,'L');
			$POSY=$this->GetY();
		}	
		//SUTOTALES,DESCUENTOS,COPAGO,TOTAL
		if($data[$NoLiquidacion][23]!=''){			
			$POSY=$this->GetY();
			if($POSY>=250 && $POSY<255){						
				$this->Header1($NoLiquidacion,0);				
			}
			$Total=$GranSubTot;
			$this->Ln(5);
			$this->SetFont('Arial','B',8);	
			$this->Cell(165,5,"SUBTOTAL GENERAL:",1,0,'R');
			$this->SetFont('Arial','',8);
            $this->Cell(29,5,number_format(round($data[$NoLiquidacion][23]),2),1,0,'R');
			
			$POSY=$this->GetY();
			if($POSY>=250 && $POSY<255){						
				$this->Header1($NoLiquidacion,0);				
			}
			$Tipocopago=$data[$NoLiquidacion][18];
			$ClaseCopago=$data[$NoLiquidacion][19];
			if($data[$NoLiquidacion][19]=='Fijo'){
				$Valorcopago=$data[$NoLiquidacion][13]; $Porsentajecopago="0";			
			}
			else{
				$Valorcopago=$data[$NoLiquidacion][13]; $Porsentajecopago=$data[$NoLiquidacion][20];	
			}		
			$Valorcopago=round($Valorcopago,0);
			$Total=$Total-$Valorcopago;
			$this->Ln(5);
			$this->SetFont('Arial','B',8);	
			$this->Cell(120,5,"PORCENTAJE COPAGO:",1,0,'R');
			$this->SetFont('Arial','',8);
			$this->Cell(20,5,$Porsentajecopago."%",1,0,'R');
			$this->SetFont('Arial','B',8);	
			$this->Cell(25,5,"VR COPAGO:",1,0,'R');
			$this->SetFont('Arial','',8);
			$this->Cell(29,5,number_format(round($Valorcopago),2),1,0,'R');	
			
			if($data[$NoLiquidacion][22]!=''&&$data[$NoLiquidacion][22]!="0"){
				$POSY=$this->GetY();
				if($POSY>=250 && $POSY<255){						
					$this->Header1($NoLiquidacion,0);				
				}
				$this->Ln(5);
				$this->SetFont('Arial','B',8);	
				$this->Cell(120,5,"PORCENTAJE DESCUENTO:",1,0,'R');
				$this->SetFont('Arial','',8);
				$this->Cell(20,5,$data[$NoLiquidacion][22]."%",1,0,'R');
				$this->SetFont('Arial','B',8);	
				$this->Cell(25,5,"VR DESCUENTO:",1,0,'R');
				$this->SetFont('Arial','',8);
				$this->Cell(29,5,number_format($data[$NoLiquidacion][21],2),1,0,'R');
				$Valordescuento=round($Valordescuento,0);
				$Total=$Total-$Valordescuento;    	            
   			}
			$POSY=$this->GetY();
			if($POSY>=250 && $POSY<255){						
				$this->Header1($NoLiquidacion,0);				
			}
			$this->Ln(5);
			$this->SetFont('Arial','B',8);	
			$this->Cell(165,5,"TOTAL:",1,0,'R');
			$this->SetFont('Arial','',8);
			$this->Cell(29,5,number_format(round($data[$NoLiquidacion][24]),2),1,0,'R');
			
			$cons="update facturacion.liquidacion set subtotal=$GranSubTot,total=$Total where compania='$Compania[0]' and noliquidacion=$NoLiquidacion";	
			//echo $cons;
			$res=Exquery($cons);
			$POSY=$this->GetY();
			if($POSY>=240 && $POSY<255){
				$this->Header1($NoLiquidacion,0);				
				$this->Ln(20);
			}
			else{
				$this->Ln(28);
			}
			$this->Cell(50,5,"",0,0,'D');
			$this->Cell(100,5,"FIRMA PACIENTE","T",0,'C');			
		}	
	}
	function BasicTable($data)
	{
		global $NoLiquidacion; global $Paciente; global $CUPsMED; global $GrupsMeds; global $GrupsCUPs; global $ND; 
		global $Compania; global $Individual; global $Estado; global $Pac;
		
		if($Individual==1){
			$this->Header1($NoLiquidacion,1);
			$this->Header2($data,$NoLiquidacion,$Paciente);	
			$this->Datos($data,$NoLiquidacion,$Paciente);
			if($Estado == "AN")
			{
				$this->SetFont('Arial','B',90);
				$this->SetTextColor(215,215,215);
				$this->Rotate(45,40,200);
				$this->Text(35,220,'ANULADO');
				$this->SetTextColor(0,0,0);
				$this->Rotate(0);			
			}
		}	
		else{
			foreach($data as $Info)
			{
				if($Pac[$Info[27]]==1){
					$DatosPaciente[1]=$Paciente[$Info[27]][1];
					$this->Header1($Info[26],1);
					$this->Header2($data,$Info[26],$Paciente[$Info[27]]);	
					$this->Datos($data,$Info[26],$Paciente[$Info[27]]);
					if($Info[25]=='AN')
					{
						$this->SetFont('Arial','B',90);
						$this->SetTextColor(215,215,215);
						$this->Rotate(45,40,200);
						$this->Text(35,220,'ANULADO');
						$this->SetTextColor(0,0,0);
						$this->Rotate(0);
					}
				}
			}
		}	
	}
	

	function Footer()
	{
		global $ND;
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln(3);
		$this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
	}
}

$pdf=new PDF('P','mm','Letter');
$pdf->AliasNbPages();
//$pdf->AddPage();//Agrega una paguina en blanco al pdf
$pdf->SetFont('Arial','',8);//Fuente documento,negrilla,tamaño letra
$pdf->BasicTable($Datos);
$pdf->Output();
?>
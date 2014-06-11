<? if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	//require('LibPDF/fpdf.php');
	require('LibPDF/rotation.php');
	
	if($Company)$Company=$Company;
	else if($Compania[0])$Company=$Compania[0];
	//else $Company="Clinica San Juan de Dios";
	
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
	
	$cons="select grupo,grupofact from consumo.grupos where compania='$Company'";
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
				from facturacion.liquidacion,central.terceros where terceros.compania='$Company' 
				and liquidacion.compania='$Company' and noliquidacion=$NoLiquidacion and identificacion=pagador";					
				$res=ExQuery($cons);
				$fila=ExFetch($res);				
				$Datos[$NoLiquidacion]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19],$fila[20],$fila[21],$fila[22],$fila[23],$fila[24],$fila[25],$fila[26],$fila[27],$fila[28]);	 	
				//echo "$cons<br>";			
				
				$Paciente[$fila[27]][1]=$fila[27];
				$consP="select primape,segape,primnom,segnom from central.terceros where compania='$Company' and identificacion='$fila[27]'";
				$resP=ExQuery($consP);
				$filaP=ExFetch($resP);				
				$Paciente[$fila[27]][2]=$filaP[0]; $Paciente[$fila[27]][3]=$filaP[1]; $Paciente[$fila[27]][4]=$filaP[3]; $Paciente[$fila[27]][5]=$filaP[2]; 
				//echo "<br>".$Paciente[$fila[27]][1]."<br>";
				$consP=""; $resP=""; $filaP="";
				
				$cons2="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal) 
				from facturacion.detalleliquidacion 
				where compania='$Company' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
				group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal";	
				$res2=ExQuery($cons2);
				//echo "$cons2<br>";
				while($fila2=ExFetch($res2)){
					if($GruposMeds[$fila2[2]]){$fila2[2]=$GruposMeds[$fila2[2]];} 
					$Pac[$fila[27]]=1;
					$Id="$NoLiquidacion$fila[27]$fila2[0]";
					//echo "NoLiquidacion=$NoLiquidacion fila[27]=$fila[27] fila2[0]=$fila2[0]<br>";
					$CUPsMED[$Id][$NoLiquidacion]=array($fila2[0],$fila2[1],$fila2[2],$fila2[3],$fila2[4],$fila2[5],$fila2[6],$fila2[7],$fila2[8],$fila2[9],$fila2[10],$fila2[11]);
					//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
				}	
				$cons=""; $res=""; $fila="";				
			}
		}			
	}
	if($NoLiquidacion!=''&!$NoLiqConsecFin){//echo "$NoLiquidacion ";
		$Individual=1;
		//if(!$Paciente[1]){$Paciente[1]=$Ced;}
					
		$cons="select ambito,medicotte,liquidacion.nocarnet,liquidacion.tipousu,liquidacion.nivelusu,autorizac1,autorizac2,autorizac3,
		(primnom || segnom || primape || segape) as eps,pagador,contrato,nocontrato,numservicio,valorcopago,direccion,telefono,tipoasegurador,codigosgsss,tipocopago,clasecopago,
		porsentajecopago,valordescuento,porsentajedesc,subtotal,total,estado,noliquidacion,cedula,fechacrea
		from facturacion.liquidacion,central.terceros where terceros.compania='$Company' 
		and liquidacion.compania='$Company' and noliquidacion=$NoLiquidacion and identificacion=pagador";	
		
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$IdDatos=$Paciente[1];
		$Datos[$NoLiquidacion]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19],$fila[20],$fila[21],$fila[22],$fila[23],$fila[24],$fila[25],$fila[26],$fila[27],$fila[28]);	 	
		
		$Paciente[1]=$Ced;
		$cons="select primape,segape,primnom,segnom from central.terceros where compania='$Company' and identificacion='$fila[27]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Paciente[2]=$fila[0]; $Paciente[3]=$fila[1]; $Paciente[4]=$fila[3]; $Paciente[5]=$fila[2];
		
		$cons="select codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,sum(cantidad),almacenppal,sum(vrtotal) from facturacion.detalleliquidacion 
		where compania='$Company' and noliquidacion=$NoLiquidacion and nofacturable!=1 and grupo!=''
		group by codigo,nombre,grupo,tipo,vrunidad,generico,presentacion,forma,almacenppal";	
		$res=ExQuery($cons);
		//echo $cons;
		while($fila=ExFetch($res)){
			if($GruposMeds[$fila[2]]){$fila[2]=$GruposMeds[$fila[2]]; } 
			$Id="$NoLiquidacion$Paciente[1]$fila[0]";			
			$Pac[$fila[27]]=1;
			$CUPsMED[$Id][$NoLiquidacion]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10]);
			//echo $CUPsMED[$Id][$NoLiquidacion][11]."<br>";
		}
	}	
	
	$cons="select grupo,almacenppal from consumo.grupos where compania='$Company' and anio='$ND[year]'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsMeds[$fila[0]]=array($fila[0],$fila[1]);
	}	
	$cons="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Company'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res)){
		$GrupsCUPs[$fila[1]]=array($fila[0],$fila[1]);
	}	
	
	/*
	function DatosPrueba($data,$NoLiquidacion,$Paciente)
	{
		global $CUPsMED; global $GrupsMeds; global $GrupsCUPs; global $ND; global $Company; 
		
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
	
	
	
?>















<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
//	include("Funciones.php");
	$ND=getdate();	
	if($Facturar){
		 if($Contrato){$Contra="and contrato='$Contrato'";} 
		 if($NoContrato){$NoContra="and numero='$NoContrato'";}
		//----------------------------------------------------------Seleccionamos el tipo de factura (Individual/Colectivo)-------------------------------------------------------------
		$cons="select tipofactura from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' $Contra $NoContra";		
		$res=ExQuery($cons); echo ExError(); $fila=ExFetch($res); 
		$TipoFac=$fila[0];//Tipo de Factura
		//----------------------------------------------------------Buscamos el numero de fatura consecutivo-----------------------------------------------------------------------------
		$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc";
		$res=ExQuery($cons); echo ExError(); $fila=ExFetch($res); 
		$AutoId=$fila[0]+1;//Numero de factura
		//----------------------------------------------------------Seleccionamos las liquidaciones q cumplan con los criterios de busqueda----------------------------------------------
		if($Ambito!=''){$Amb="and ambito='$Ambito'";}
		$cons="select noliquidacion,subtotal,valorcopago,valordescuento,total from facturacion.liquidacion where compania='$Compania[0]' and pagador='$Entidad' and contrato='$Contrato' 
		and nocontrato='$NoContrato' $Amb and estado='AC' and nofactura is null";
		//echo $cons;
		$res=ExQuery($cons);
		
		if($TipoFac=="Individual"){	
			$AutoIdIni=$AutoId;				
			while($fila=ExFetch($res)){				
				if($fila[3]==''){$fila[3]="0";}
				$consF="insert into facturacion.facturascredito (compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura)
				values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$FechaIni','$FechaFin','$Entidad','$Contrato','$NoContrato',
				'$Ambito',$fila[1],$fila[2],$fila[3],$fila[4],$AutoId)";
				//echo "$consF<br>";
				$resF=ExQuery($consF);
				$consUp="update facturacion.liquidacion set nofactura=$AutoId where compania='$Compania[0]' and noliquidacion=$fila[0]";
				$resUp=ExQuery($consUp);
				$cons2="select codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal from  facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion='$fila[0]'";
				//echo $cons2;
				$res2=ExQuery($cons2); echo ExError();
				while($fila2=ExFetch($res2)){
					$consDF="insert into facturacion.detallefactura 
					(compania,usuario,fechacrea,codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,nofactura)
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$fila2[1]','$fila2[2]','$fila2[3]',$fila2[4]
					,$fila2[5],$fila2[6],'$fila2[7]','$fila2[8]','$fila2[9]','$fila2[10]',$AutoId)";
					//echo $consDF;
					$resDF=ExQuery($consDF); echo ExError();			
				}
				$AutoId++;
			}	
			?><script language="javascript">
				//open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoIdIni?>&NoFacFin=<? echo $AutoId?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES');</script><?			
		}		
		else{			
			while($fila=ExFetch($res)){
				if($fila[3]==''){$fila[3]="0";}
				$STot=$STot+$fila[1];
				$Cop=$Cop+$fila[2];
				$Desc=$Desc+$fila[3];
				$Tot=$Tot+$fila[4];
				$consUp="update facturacion.liquidacion set nofactura=$AutoId where compania='$Compania[0]' and noliquidacion=$fila[0]";
				$resUp=ExQuery($consUp);
				$cons2="select codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal from  facturacion.detalleliquidacion 
				where compania='$Compania[0]' and noliquidacion='$fila[0]'";
				//echo $cons2;
				$res2=ExQuery($cons2); echo ExError();
				while($fila2=ExFetch($res2)){
					$consDF="insert into facturacion.detallefactura 
					(compania,usuario,fechacrea,codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,nofactura)
					values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$fila2[1]','$fila2[2]','$fila2[3]',$fila2[4]
					,$fila2[5],$fila2[6],'$fila2[7]','$fila2[8]','$fila2[9]','$fila2[10]',$AutoId)";
					//echo $consDF;
					$resDF=ExQuery($consDF); echo ExError();			
				}				
			}
			if($Desc==''){$Desc="0";}
			if($Cop==''){$Cop="0";}
			$consF="insert into facturacion.facturascredito (compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura)
			values ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$FechaIni','$FechaFin','$Entidad','$Contrato','$NoContrato',
			'$Ambito',$STot,$Cop,$Desc,$Tot,$AutoId)";
			//echo "$consF<br>";
			$resF=ExQuery($consF);
			?><script language="javascript">open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoId?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES');</script><?
		}		
		$Entidad=''; $FechaIni=''; $FechaFin=''; $Contra=''; $NoContra=''; $Ambito='';		
	}
?>
<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar2()
	{
		if(document.FORMA.FechaIni.value==""){ 
			alert("Debe seleccionar la fecha de inicio!!!"); return false; 
		}		
		if(document.FORMA.FechaFin.value==""){ 
			alert("Debe seleccionar la fecha final!!!");  return false;
		}		
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
			alert("La fecha inicial debe ser menor o igual a la fecha final!!!"); return false;
		}
		document.FORMA.Ver.value=1;
		document.FORMA.Simula.value=1;
	}
	function Validar()
	{
		if(document.FORMA.FechaIni.value==""){ 
			alert("Debe seleccionar la fecha de inicio!!!"); 
		}
		else{
			if(document.FORMA.FechaFin.value==""){ 
				alert("Debe seleccionar la fecha final!!!"); 
			}
			else{	
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
					alert("La fecha inicial debe ser menor o igual a la fecha final!!!");
				}					
				else{				
					document.FORMA.Ver.value=1;
					document.FORMA.submit();					
					
				}
			}
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar2()">   
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
	<tr align="center">
    	<td colspan="9" bgcolor="#e5e5e5" style="font-weight:bold">Liquidacion en Masa</td>        
  	</tr> 
	<tr>    	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
   	<?	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" ></td>    
      	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>    
		<td>
	         <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   			</select>
       	</td>
        <td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo</td>    
        <td>
        <?	$cons="select tipo from central.tiposaseguramiento";
			$res=ExQuery($cons);?>
        	<select name="TipoAseg" onChange="document.FORMA.submit()"><option></option>
            <?	while($fila=ExFetch($res)){
					if($fila[0]==$TipoAseg){ 
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
				}?>
            </select>
       	</td>    	
  	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
   	 <?	if($TipoAseg){$TA="and tipoasegurador='$TipoAseg'";}
	 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion $TA 
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="3">
        	<select name="Entidad" onChange="document.FORMA.submit()"><option></option>
      	<?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Entidad==$row[0])
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
        </td>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
  	<?	$cons="Select contrato from contratacionsalud.contratos where contratos.compania='$Compania[0]' and entidad='$Entidad'";
		//echo $cons;?>
        <td>
        	<select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Contrato==$row[0])
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
        <td bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>
  	<?	$cons="Select numero from contratacionsalud.contratos where contratos.compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'";
		//echo $cons;?>
        <td>
        	<select name="NoContrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($NoContrato==$row[0])
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
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Separar Medicamentos No POS</td>
        <td colspan="7"><input type="checkbox" name="SepMedNoPOS" <? if($SepMedNoPOS){?> checked<? }?>></td>
    </tr>
    <tr align="center">          		
    	<td colspan="9"><input type="submit" value="Simular"><input type="button" value="Liquidar" onClick="Validar();"></td>        
    </tr>
</table>
<input type="hidden" name="Facturar">
<input type="hidden" name="Ver" />
<input type="hidden" name="Simula">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</body>
<iframe frameborder="0" id="VerLiqEnMasa" src="VerLiqEnMasa.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ambito=<? echo $Ambito?>&Ver=<? echo $Ver?>&TipoAseg=<? echo $TipoAseg?>&Simula=<? echo $Simula?>&SepMedNoPOS=<? echo $SepMedNoPOS?>" width="100%" height="85%"></iframe>


</html>

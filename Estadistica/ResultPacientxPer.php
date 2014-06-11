<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	/*if($DatNameSID){
		$cons="select numservicio from salud.servicios where compania='$Compania[0]'  and fechaegr>='$FechaIni 00:00:00' and fechaegr<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{*/
			$consME="select detalle,numservicio from salud.ordenesmedicas where compania='$Compania[0]' and tipoorden='Orden Egreso'";
			$resME=ExQuery($consME);
			while($filaME=ExFetch($resME))
			{
				$CSalid=explode(":",$filaME[0]);
				$CS=explode("-",$CSalid[1]);
				$CausaSalida[$filaME[1]]=$CS[0];
				//echo $CausaSalida[$filaME[1]]." ".$filaME[1]."<br>";
			}
		/*}
	}
	$consME="select detalle,servicios.numservicio from salud.ordenesmedicas,salud.servicios where ordenesmedicas.compania='$Compania[0]' and tipoorden='Orden Egreso' 
	and servicios.compania='$Compania[0]' and ordenesmedicas.numservicio=servicios.numservicio and fechaegr>='$FechaIni 00:00:00' and fechaegr<='$FechaFin 23:59:59' 
	and fechaegr is not null";
	$resME=ExQuery($consME);	
	while($filaME=ExFetch($resME))
	{
		$CSalid=explode(":",$filaME[0]);
		$CS=explode("-",$CSalid[1]);
		$CausaSalida[$fila[1]]=$CS[0];
		//echo $CausaSalida[$fila[1]]."<br>";
	}	*/
	$cons="select codigo,diagnostico from salud.cie";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$CIE[$fila[0]]=$fila[1];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($AuxIncluir){
	
	$Incluir=$AuxIncluir;
	$AuxInc=$Incluir;
	$cons="select usuarios.usuario,nombre from central.usuarios,salud.medicos,salud.cargos where cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]' 
	and medicos.usuario=usuarios.usuario and medicos.cargo=cargos.cargos and cargos.tratante=1 ";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Meds[$fila[0]]=$fila[1];
	}
	if($Incluir)
	{
		$INEnt=" and identificacion in (";
		$AuxIncluir=explode(";",$Incluir);
		$ban=0;
		foreach($AuxIncluir as $Inclu)
		{
			$AuxIncuilr2=explode("*",$Inclu);
			if($ban==0){$INEnt=$INEnt."'".$AuxIncuilr2[0]."'"; $ban=1;}
			else{$INEnt=$INEnt.",'".$AuxIncuilr2[0]."'";}
		}
		$INEnt=$INEnt.") ";
		//echo $INEnt;
	}
	$Regimenes=$AuxRegimenes; 
	if($Regimenes)
	{		
		$Regimenes="'".$Regimenes;
		$Regimenes=str_replace(";","','",$Regimenes);
		$AuxRegs=$Regimenes;		
		$Regimenes=$Regimenes."'";
		$Reg=" and tipoasegurador in (".$Regimenes.") ";
		$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom, identificacion from central.terceros where compania='$Compania[0]' 
		and tipo='Asegurador' $Reg $INEnt";
		//echo $cons;
		$res=ExQuery($cons);
		$ban=0;
		$AsegContra=" and pagadorxservicios.entidad in (";
		while($fila=ExFetch($res))
		{
			$Aseguradoras[$fila[1]]=$fila[0];
			
			if($ban==0)
			{
				$ban=1;
				$AsegContra=$AsegContra."'".$fila[1]."'";
			}
			else{
				$AsegContra=$AsegContra.",'".$fila[1]."'";
			}	
		}
		$AsegContra=$AsegContra.")";
		//if($AsegContra==" and entidad in ()"){$AsegContra="";}
	}
	/*if(!$VercionRed){
		if($OpcVer=="Solo Ingresos"){$OV="and fechaing>='$FechaIni 00:00:00' and fechaing<='$FechaFin 23:59:59'";}
		elseif($OpcVer=="Solo Egresos"){$OV="and fechaegr>='$FechaIni 00:00:00' and fechaegr<='$FechaFin 23:59:59' and fechaegr is not null";}
		elseif($OpcVer=="Pacientes que vienen"){$OV=" and fechaing<'$FechaIni 00:00:00'  and (fechaegr>='$FechaIni 00:00:00' or fechaegr is not null)";}
		elseif($OpcVer=="Hospitalizados del periodo"){$OV="and servicios.estado='AC' and fechaegr is null";}
		else{$OV=" and (fechaegr>='$FechaIni 00:00:00' or fechaegr is null) and fechaing<='$FechaFin 23:59:59'";}
	/*}
	else{*/
		if($OpcVer=="Solo Ingresos"){$OV="and fechaini>='$FechaIni 00:00:00' and fechaini<='$FechaFin 23:59:59'";}
		elseif($OpcVer=="Solo Egresos"){$OV="and fechafin>='$FechaIni 00:00:00' and fechafin<='$FechaFin 23:59:59' and fechafin is not null";}
		elseif($OpcVer=="Pacientes que vienen"){$OV=" and fechaini<'$FechaIni 00:00:00'  and (fechafin>='$FechaIni 00:00:00' or fechafin is null)";}
		elseif($OpcVer=="Hospitalizados del periodo"){$OV="and servicios.estado='AC' and fechafin is null";}
		else{$OV=" and (fechafin>='$FechaIni 00:00:00' or fechafin is null) and fechaini<='$FechaFin 23:59:59'";}
	/*}*/
	if(!$OrdenarPor||$OrdenarPor=="Nombre"){$Order="order by nom,fechaini";}
	if($OrdenarPor=="Cedula"){$Order="order by identificacion";}
	if($OrdenarPor=="Ambito"){$Order="order by tiposervicio";}
	if($OrdenarPor=="FecIngreso"){$Order="order by fechaing desc";}
	if($OrdenarPor=="FecEgreso"){$Order="order by fechaegr desc";} 
	if($OrdenarPor=="FecIni"){$Order="order by fechaini desc";}
	if($OrdenarPor=="FecFin"){$Order="order by fechafin desc";}	
	
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as 
	nom,identificacion,fechaini,fechafin,tiposervicio,medicotte,pagadorxservicios.entidad
	,servicios.nivelusu,servicios.tipousu,autorizac1,dxserv,fecnac,fechaing,fechaegr,municipio,servicios.numservicio
	from central.terceros,salud.servicios,salud.pagadorxservicios,salud.ambitos
	where servicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]'
	and identificacion=cedula and pagadorxservicios.numservicio=servicios.numservicio and tiposervicio=ambito and ambitos.consultaextern=0 $OV $AsegContra
	$Order";
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$AuxRegs=str_replace("'","",$AuxRegs);
		$AuxRegs=str_replace(","," - ",$AuxRegs);
		$AuxInc2=explode(";",$AuxInc); $banAI=0;		
		foreach($AuxInc2 as $AuxI2)
		{
			$AI=explode("*",$AuxI2);
			if($banAI==0){$AI2=$Aseguradoras[$AI[0]];$banAI=1;}
			else{$AI2=$AI2." - ".$Aseguradoras[$AI[0]]; }
		}//echo $AI2;
		if(!$Paginacion){?>	
        	<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="0" style="font : normal normal small-caps 11px Tahoma">	
	        <tr><td colspan="17" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Periodo</td></tr>
    	    <tr><td colspan="17" align="center"><? echo "Desde $FechaIni hasta $FechaFin"?></td></tr>        
        	<tr><td colspan="17" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Regimenes</td></tr><tr><td colspan="17" align="center"><? echo $AuxRegs?></td></tr>        
		 <?	if(!$VercionRed){?>
                <tr><td colspan="17" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidades</td></tr><tr><td colspan="17" align="center"><? echo $AI2?></td></tr>
         <?	}?>
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
                <td></td>
                <td onClick="parent.document.FORMA.OrdenarPor.value='Nombre';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Nombre">Nombre</td>
                <td onClick="parent.document.FORMA.OrdenarPor.value='Cedula';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Identificacion">Identificacion</td>
                <td onClick="parent.document.FORMA.OrdenarPor.value='Ambito';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Proceso">Proceso</td>
            <?	if(!$VercionRed){?>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecIngreso';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha de Ingreso">
                        Fecha Ingreso Servicio</td>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecEgreso';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha de Egreso">
                        Fecha Fin Servicio</td>
                    <td>Causa Salida</td>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecIni';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Inicial">
                        Fecha Inicio Ent. Reponsable de Pago</td>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecFin';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Final">
                        Fecha Fin Ent. Responsable de Pago</td>
            <?	}
                else{?>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecIni';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Inicial">
                        Fecha Ingreso</td>
                    <td onClick="parent.document.FORMA.OrdenarPor.value='FecFin';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Final">
                        Fecha Egreso</td>
            <?	}
                if($VercionRed){?>
                    <td>Causa Salida</td>
            <?	}?>
                <td>Medico</td><td>Ent. Responsable de Pago</td><td>Procedencia</td>
            <?	if(!$VercionRed){?>
                    <td>Nivel</td><td>Tipo Usu</td>    	        
            <?	}?>
                <td>Autorizacion</td><td>Dx</td><td>Fecha Nac</td>            
            </tr>    
	<?	}
		$cont=1;
		$cont2=0;
		while($fila=ExFetch($res))
		{			
			if($Paginacion){
				if($cont2<10){
					$cont2++;                                      
				}
				else{
					$cont2=1;
				?>	</table>
                	<br>
                    <br>
                    <br>
					<?
				}
				if($cont2==1){?>	
                	<table border="0" cellpadding="0" style="font : normal normal small-caps 11px Tahoma" align="center">	
                    <tr><td colspan="17" style="font-weight:bold" align="center"><? echo $Compania[0]?></td></tr>
                    <tr><td colspan="17" align="center"><? echo "CODIGO SGSSS $Compania[17]"?></td></tr>
                    <tr><td colspan="17" align="center"><? echo $Compania[2]." - TELEFONOS: ".strtoupper($Compania[3])?></td></tr>
	     		   	<tr><td colspan="17" style="font-weight:bold" align="center">Periodo</td></tr>
    		   		<tr><td colspan="17" align="center"><? echo "Desde $FechaIni hasta $FechaFin"?></td></tr>        
		        	<tr><td colspan="17" style="font-weight:bold" align="center">Regimenes</td></tr><tr><td colspan="17" align="center"><? echo $AuxRegs?></td></tr>
                 <?	if(!$VercionRed){?>
                        <tr><td colspan="17" style="font-weight:bold" align="center">Entidades</td></tr><tr><td colspan="17" align="center"><? echo $AI2?></td></tr>
                 <?	}?>                 		
	             	</table>
                    <BR> 
                    <table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="0" style="font : normal normal small-caps 11px Tahoma">	
                    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">	
                        <td></td>
                        <td onClick="parent.document.FORMA.OrdenarPor.value='Nombre';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Nombre">Nombre</td>
                        <td onClick="parent.document.FORMA.OrdenarPor.value='Cedula';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Identificacion">Identificacion</td>
                        <td onClick="parent.document.FORMA.OrdenarPor.value='Ambito';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Ambito">Ambito</td>
                    <?	if(!$VercionRed){?>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecIngreso';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha de Ingreso">
                                Fecha Ingreso Servicio</td>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecEgreso';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha de Egreso">
                                Fecha Fin Servicio</td>
                            <td>Causa Salida</td>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecIni';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Inicial">
                                Fecha Inicio Ent. Reponsable de Pago</td>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecFin';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Final">
                                Fecha Fin Ent. Responsable de Pago</td>
                    <?	}
                        else{?>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecIni';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Inicial">
                                Fecha Ingreso</td>
                            <td onClick="parent.document.FORMA.OrdenarPor.value='FecFin';parent.document.FORMA.submit()" style="cursor:hand" title="Ordernar por Fecha Final">
                                Fecha Egreso</td>
                    <?	}
                        if($VercionRed){?>
                            <td>Causa Salida</td>
                    <?	}?>
                        <td>Medico</td><td>Ent. Responsable de Pago</td><td>Procedencia</td>
                    <?	if(!$VercionRed){?>
                            <td>Nivel</td><td>Tipo Usu</td>    	        
                    <?	}?>
                        <td>Autorizacion</td><td>Dx</td><td>Fecha Nac</td>            
                    </tr>                       
		     <?	}
			}
			
			
			$FIng=explode(" ",$fila[12]);
			$FEgr=explode(" ",$fila[13]);
			if(!$fila[3]&&$FEgr[0]){$fila[3]=$FEgr[0];}
			if($FEgr[0]){
			/*	$consME="select detalle from salud.ordenesmedicas where compania='$Compania[0]' and numservicio=$fila[15] and tipoorden='Orden Egreso'";
				$resME=ExQuery($consME);
				$filaME=ExFetch($resME);
				$CSalid=explode(":",$filaME[0]);
				$CS=explode("-",$CSalid[1]);*/
			}
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            	<td><? echo $cont;?></td><td><? echo strtoupper($fila[0])?></td><td align="center"><? echo $fila[1]?></td><td><? echo $fila[4]?></td>
             <?	if(!$VercionRed){?>
                	<td><? echo $FIng[0]?></td>
	        	<?	if($FEgr[0]){?>
    	            	<td><? echo $FEgr[0]?>&nbsp;</td>
                        <td><? echo $CausaSalida[$fila[15]]?>&nbsp;</td>
        	  	<?	}
					else{?>
    	            	<td colspan="2" align="center">Hospitalizado</td>
				<?	}?>
          	<?	}?>
	                <td><? echo $fila[2]?></td>
           	<?	if(!$VercionRed){?>
                    <td><? echo $fila[3]?>&nbsp;</td>
          	<?	}
				else{?>
				<?	if($fila[3]){?>
    	            	<td><? echo $fila[3]?>&nbsp;</td>
                        <td><? echo $CausaSalida[$fila[15]]?>&nbsp;</td>
        	  	<?	}
					else{?>
    	            	<td colspan="2" align="center">Hospitalizado</td>
				<?	}?>
			<?	}?>
    	           	<td><? echo $Meds[$fila[5]]?> &nbsp;</td><td><? echo $Aseguradoras[$fila[6]]?></td><td><? echo $fila[14]?>&nbsp;</td>
            <?	if(!$VercionRed){?>
        	        <td><? echo $fila[7]?>&nbsp;</td><td><? echo $fila[8]?>&nbsp;</td>
          	<?	}?>
                <td><? echo $fila[9]?>&nbsp;</td><td><? echo "$fila[10] - ".$CIE[$fila[10]];?>&nbsp;</td>           	
                <td><? echo $fila[11]?>&nbsp;</td>
			</tr>	
	<?		$cont++;
		}
	}
}?>
</table>
<?
if($OpcVer=="Todos"){
	if($AsegContra){
		$ASeg1=",salud.pagadorxservicios";
		$ASeg2="and pagadorxservicios.compania='$Compania[0]' and servicios.numservicio=pagadorxservicios.numservicio $AsegContra";
	}
	$cons="select count(cedula) from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]'	
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaing>='$FechaIni 00:00:00' and fechaing<='$FechaFin 23:59:59'
	$ASeg2";
	//echo $cons;
	$res=ExQuery($cons); $fila=ExFetch($res); $Ingresos=$fila[0];
	$cons="select count(cedula) from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito and ambitos.consultaextern=0 and fechaing>='$FechaIni 00:00:00' and fechaing<='$FechaFin 23:59:59' 
	and fechaegr is not null and estado='AN' $ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $IngresosCE=$fila[0];
	//echo $cons."<br>";
	$cons="select count(cedula) from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaing>='$FechaIni 00:00:00' and fechaing<='$FechaFin 23:59:59' and fechaegr is null and estado='AC'
	$ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $IngresosSE=$fila[0];	
	$cons="select servicios.numservicio from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaing<='$FechaIni 00:00:00' and ((fechaegr>='$FechaIni 00:00:00' and estado='AN') 
	or (fechaegr is null and estado='AC')) $ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $Vienen=ExNumRows($res);
	$cons="select count(cedula) from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaing<='$FechaIni 00:00:00' and fechaegr is null and estado='AC' $ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $VienenSE=$fila[0];
	$cons="select count(cedula) from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaing<'$FechaIni 00:00:00' and fechaegr is not null and fechaegr>='$FechaIni 00:00:00' 
	and estado='AN' $ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $VienenCE=$fila[0];
	//echo $cons."<br>";
	$cons="select servicios.numservicio from salud.servicios,salud.ambitos $ASeg1 where servicios.compania='$Compania[0]' and ambitos.compania='$Compania[0]' 
	and tiposervicio=ambito	and ambitos.consultaextern=0 and fechaegr>='$FechaIni 00:00:00' and fechaegr<='$FechaFin 23:59:59' 
	and fechaegr is not null and estado='AN' $ASeg2";
	$res=ExQuery($cons); $fila=ExFetch($res); $Egresos=ExNumRows($res); 
	//echo $cons?>
    
	<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="0" style="font : normal normal small-caps 13px Tahoma">
    	<tr><td bgcolor="#e5e5e5" style="font-weight:bold">Ingresos</td><td><? echo $Ingresos?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Ingresos con Egreso</td><td><? echo $IngresosCE?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Ingresos sin Egreso</td><td><? echo $IngresosSE?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Pacientes que Vienen</td><td><? echo $Vienen?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Pacientes que Vienen con Egreso</td><td><? echo $VienenCE?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Pacientes que Vienen sin Egreso</td><td><? echo $VienenSE?></td></tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Egresos del periodo</td><td><? echo $Egresos?></td></tr>        
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold">Pacientes a facturar</td><td><? echo $Ingresos+$Vienen?></td></tr>        
	</table><?
}?>
</form>    
</body>
</html>

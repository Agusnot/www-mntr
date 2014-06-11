<?php
include("../Funciones.php");

switch($Report){









    //Facturas Radicadas
    case 1:{
	   if($FacI){$FacIni="and nofactura>=$FacI";}
		else{$FacIni="";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
		else{$FacFin="";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
		else{$Ent="";}
	if($Contrato){$Contr="and contrato='$Contrato'"; }
		else{$Contr="";}
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
		else{$NoContr="";}
	switch($type)
			{case 0:
					$Fnb="fechacrea";
					$Rcnb="and fecharadic is NULL";
				break;
				case 1:
					$Fnb="fecharadic";
					$Rcnb="and fecharadic is not NULL";
				break;
			}
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa 
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa 
	where facturascredito.compania='$Compania' 
	and $Fnb>='$FechaIni 00:00:00' 
	and $Fnb<='$FechaFin 23:59:59' 	
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad
	$FacIni 
	$FacFin 
	$Ent 
	$Contr $NoContr 
	and facturascredito.estado='AC'
	$Rcnb
	group by nofactura,fechacrea,total,fecharadic,fechaglosa,vrglosa,motivoglosa, (primape || segape || primnom || segnom)
	order by nofactura";
	//echo"$cons";
	$res=ExQuery($cons);
	$tradicadas=0;
if(ExNumRows($res)>0){?>
	<table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
		
		<tr align="center">
		<td colspan="6" align="center" class="ui-state-default">
		INFORME DE FACTURAS RADICADAS    </td>
		</tr>
		
		<tr align="center" class="ui-state-default">
        
			
  <td>No. Factura</td><td>Fecha Factura</td><td>Fecha Radicaci&oacute;n</td><td>Entidad</td><td>Valor Factura</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr class="ui-widget-content">
       			  <td align="center"><div align="center"><? echo $fila[0]?></div></td>
       			  <td align="center"><div align="center"><? echo $Fec[0]?></div></td>
       			  <td align="center"><div align="center"><? echo $fila[4]?></div></td>
       			  <td align="center"><div align="center"><? echo $fila[2]?></div></td>
       			  <td align="right"><div align="right"><? $tradicada=$tradicada+$fila[3];echo number_format($fila[3],2)?></div></td>
       			</tr><? }?>
       			<tr>
       			  <td align="center">&nbsp;</td>
       			  <td align="center">&nbsp;</td>
       			  <td align="center">&nbsp;</td>
       			  <td align="center">&nbsp;</td>
       			  <td align="right">&nbsp;</td>
	  </tr>
       			<tr>
       			  <td align="center">&nbsp;</td>
       			  <td align="center">&nbsp;</td>
       			  <td align="center">&nbsp;</td>
       			  <td align="center" class="ui-state-default"><div align="right" >TOTAL:</div></td>
					  <td align="right" class="ui-widget-content"><div align="right"><? echo number_format($tradicada,2);?></div></td>
	  </tr> 	</table>
	<?	}else{?>
	     <table align="center" class="ui-state-default">  
				<tr align="center"><td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda..</td></tr>
	    </table><?	}
	   }break;
	   
	   
	   
	   
	   
	   
	   
	   
	   
	   //Respuestas Glosas
	   case 3:{
	if($FacI){$FacIni="and nofactura>=$FacI";}
		else{$FacIni="";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
		else{$FacFin="";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
		else{$Ent="";}
	if($Contrato){$Contr="and contrato='$Contrato'"; }
		else{$Contr="";}
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
		else{$NoContr="";}
    if($codResGlossEPS)$CRGEPS="and tipoglosa='$codResGlossEPS'";
	    else $CRGEPS="";
	if($codResGlossIPS)$CRGIPS="and codrespuestaglosa='$codResGlossIPS'";	
        else $CRGIPS="";	
	switch($type)
		{
			case '':
			$cons="select nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom) as noment,total,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa,codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	   	 		
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa,facturacion.informerespuesglosa,facturacion.motivoglosa 
	INNER JOIN facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo 
	INNER JOIN facturacion.codmotivoglosa on facturacion.codmotivoglosa.codigo=facturacion.motivoglosa.tipoglosa::varchar
	where facturascredito.compania='$Compania' 
	and fecharadic>='$FechaIni 00:00:00' 
	and fecharadic<='$FechaFin 23:59:59' 	
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad
	$FacIni 
	$FacFin 
	$Ent 
	$Contr $NoContr 
	and facturascredito.estado='AC'
	and fecharadic is not NULL
	and facturascredito.estado='AC'
	and nofactura=facturacion.informerespuesglosa.nufactura
	and facturacion.informerespuesglosa.fecharasis is not NULL $CRGEPS $CRGIPS
	group by nofactura,fechacrea,total,fecharadic,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa, (primape || segape || primnom || segnom),codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	   	 
	order by nofactura";
	        break;
			case 0:
			$cons="select nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom) as noment,total,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa,codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	   	 
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa, facturacion.informerespuesglosa, facturacion.motivoglosa 
	INNER JOIN facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo 
	INNER JOIN facturacion.codmotivoglosa on facturacion.codmotivoglosa.codigo=facturacion.motivoglosa.tipoglosa::varchar
	where facturascredito.compania='$Compania' 
	and fecharadic>='$FechaIni 00:00:00' 
	and fecharadic<='$FechaFin 23:59:59' 	
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad
	$FacIni 
	$FacFin 
	$Ent 
	$Contr $NoContr 
	and facturascredito.estado='AC'
	and fecharadic is not NULL
	
	and nofactura=facturacion.informerespuesglosa.nufactura
	and facturacion.informerespuesglosa.fecharasis is not NULL $CRGEPS $CRGIPS
	
	group by nofactura,fechacrea,total,fecharadic,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa, (primape || segape || primnom || segnom),codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	   	 
	order by nofactura";
				/*$consx="select nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom),total,numeroinforme,facturacion.informerespuesglosa.fecharasis,codrespuestaglosa.codigo,codrespuestaglosa.detalle 
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa,facturacion.informerespuesglosa,facturacion.motivoglosa
	INNER JOIN facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo
    INNER JOIN facturacion.codmotivoglosa on facturacion.codmotivoglosa.codigo=facturacion.motivoglosa.tipoglosa::varchar	
	where facturascredito.compania='$Compania'
	and fecharespuesta>='$FechaIni 00:00:00' 
	and fecharespuesta<='$FechaFin 23:59:59' 
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad 
	$FacIni 
	$FacFin 
	$Ent 
	$Contr 
	$NoContr 
	and facturascredito.estado='AC'
	and nofactura=facturacion.informerespuesglosa.nufactura
	and facturacion.informerespuesglosa.fecharasis is not NULL $CRGEPS $CRGIPS	
	group by nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom),total,numeroinforme,facturacion.informerespuesglosa.fecharasis,codrespuestaglosa.codigo,codrespuestaglosa.detalle 
	order by nofactura";*/
			break;
			case 1:
				$cons="select nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom),total,numeroinforme,facturacion.informerespuesglosa.fecharasis,codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	  
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa,facturacion.informerespuesglosa,facturacion.motivoglosa
	INNER JOIN facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo
	INNER JOIN facturacion.codmotivoglosa on facturacion.codmotivoglosa.codigo=facturacion.motivoglosa.tipoglosa::varchar
	where facturascredito.compania='$Compania'
	and fecharespuesta>='$FechaIni 00:00:00' 
	and fecharespuesta<='$FechaFin 23:59:59' 
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad 
	$FacIni 
	$FacFin 
	$Ent 
	$Contr 
	$NoContr 
	and facturascredito.estado='AC'
	and nofactura=facturacion.informerespuesglosa.nufactura
	and facturacion.informerespuesglosa.fecharasis is not NULL $CRGEPS $CRGIPS		
	group by nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom),total,numeroinforme,facturacion.informerespuesglosa.fecharasis,codrespuestaglosa.codigo,codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,codmotivoglosa.codigo,codmotivoglosa.detalle 	  
	order by nofactura";
			break;
		}
		//echo"$cons";
	$res=ExQuery($cons);
	$tradicadas=0;
if(ExNumRows($res)>0){?>
   <table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
    	<tr align="center">	
        <td colspan="13" align="center" class="ui-state-default">INFORME DE RESPUESTA GLOSAS</td>
        </tr>
        <tr align="center" class="ui-state-default">	  	
			
        <td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicaci&oacute;n</td>
        <td>Entidad</td><td>Cod. Respuesta EPS</td><td>Cod. Respuesta IPS</td><td>Valor Factura</td><td>Valor Glosa</td>
            <td>Valor Aceptado IPS</td> <td>Valor Objetado No aceptado IPS</td><td>Valor a Pagar EPS</td>
       	    <td>N&uacute;mero Informe de Radicado</td>
       	    <td>Fecha De Radicaci&oacute;n de Respuesta</td>
   	      <?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);
				$i=0;
				if($type==0)
					{
						$resx=ExQuery($consx);
						if(ExNumRows($resx)>0)
							{
								while($filax=ExFetch($resx))
									{
										$factx[$i]=$filax[0];
										$i=$i+1;
									}
							}
					}
				$cresg=0;
				for($j=0;$j<=$i;$j++)
					{
						if($fila[0]==$factx[$j])
							{
								 $cresg=1;
							}
					}
				if($cresg==0){
					?>	
       			<tr class="ui-widget-content">                
                	<td align="center">
					<div align="center"><? echo $fila[0]?>				        </div></td>
                    <td align="center"><div align="center"><? echo $Fec[0]?></div></td><td align="center"><div align="center"><? echo $fila[2]?></div></td>
                    <td align="center"><div align="center"><? echo $fila[3]?></div></td>
					<td><div align="center"><? echo "Cod. ".$fila[14]."<br>".$fila[15]?></div></td>
					<td><div align="center"><? echo "Cod. ".$fila[8]."<br>".$fila[9]?></div></td>
                    <td align="right"><div align="center"><? echo number_format($fila[4],2)?></div></td>
                    <td align="right"><div align="center"><?echo number_format($fila[10],2);?></div></td>
					<td align="right"><div align="center"><?echo number_format($fila[11],2);?></div></td>
					<td align="right"><div align="center"><?$VONAIPS=($fila[10]-$fila[11]);echo number_format($VONAIPS,2);?></div></td>
					<td align="right"><div align="center"><?$VPEPS=($fila[4]-$fila[11]);echo number_format($VPEPS,2);?></div></td>
					<td align="right"><div align="center"><?echo $fila[12];?></div></td>
					<td align="right"><div align="center"><?echo $fila[13];?></div></td>
                      <!--<div align="center"><font style="font-size:11px">-->
                        <? 
					 // if($estadonb==1)
					  	//{
							/*$cons2="select vrglosatotal,aceptaglosa from facturacion.respuestaglosa where nufactura='$fila[0]'";
					  		$res2=ExQuery($cons2);
					  		if(ExNumRows($res2)>0)
								{
					  				while($fila2=ExFetch($res2))
										{
											echo number_format($fila2[0],2); ?>
                      </font></div></td> <td><div align="center"><?  echo number_format($fila2[1],2);?></div></td>                   
                    <td><div align="center">nnnn
                      <? $vona=$fila2[0]-$fila2[1];if($estadonb==1){echo number_format($vona,2);} }}*/?>
                    <!--</div></td>
                    <td><div align="center"><? //$vapeps=$fila[4]-$vona;if($estadonb==1){ echo number_format($vapeps,2);} ?></div></td>                 
                    <td><div align="center"><? //if($estadonb==1){echo $fila[5]; }?></div></td>
                    <td><div align="center"><? //$fila[6]=substr($fila[6],-19,10);if($estadonb==1){echo $fila[6];}?></div></td>-->
       			</tr> <? //}
				}}?></table>
<?	} else{?>
				<table align="center" class="ui-state-default">  
					<tr align="center">
					  <td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda...</td>
					</tr>
				</table> <?	}
	   }break;









    //Recepciones de Respuesta
    case 4:{
	       if($FacI){$FacIni="and nofactura>=$FacI";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
	if($Contrato){$Contr="and contrato='$Contrato'"; }
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,
	motivoglosa ,vrglosatotal,pagaipsglosa,aceptaglosa,pagarips ,totalrecepcion,contrato,numradicacion
	from facturacion.facturascredito,central.terceros ,facturacion.respuestaglosa 
	where facturascredito.compania='$Compania' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania' and fecharadic is not null and respuestaglosa.nufactura=facturascredito.nofactura and respuestaglosa.vrglosatotal<respuestaglosa.vrtotal
	and terceros.identificacion=facturascredito.entidad $FacIni $FacFin $Ent $Contr $NoContr order by entidad ASC, nofactura ASC";
	//echo "$cons"; 
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?><div id="print">
				<table id="container" width="100%" align="center" class="ui-state-highlight ui-corner-all">  
				
				<tr align="center">
				<td colspan="15" align="center" class="ui-state-default">INFORME RECEPCION DE RESPUESTA</td>
				
				</tr>
				
				<tr align="center" class="ui-state-default">	
					
				<td>#</td>		
			  <td>No. Factura</td>
			  <td>Fecha Factura</td>
			  <td>Fecha Radicaci&oacute;n</td>
			  <td>Entidad</td>
			   <td>Contrato</td>
			   <td>N&uacute;mero Radicaci&oacute;n</td>
			  <td>Valor Factura</td>
				<td>Valor Glosa</td>
				  <td>Valor Aceptado IPS</td>
					<td>Valor Objetado No. aceptado IPS</td>
					  <td>Valor a Pagar EPS</td>
					  <td>Valor Reiterado</td>
					  <td>Estado</td>
			<?	while($fila=ExFetch($res)){
					$Fec=explode(" ",$fila[1]);?>	
		  <tr class="ui-widget-content">
	<td><div align="center">
	  <? $cont++; echo $cont?>
	</div></td>
	<td align="center"><div align="center"><? echo $fila[0]?></div></td>
	<td align="center"><div align="center"><? echo $Fec[0]?></div></td>
	<td align="center"><div align="center"><? echo $fila[4]?></div></td>
	<td align="center"><div align="center"><? echo $fila[2]?></div></td>
	<td align="center"><div align="center"><? echo $fila[13]?></div></td>
	<td align="center"><div align="center"><? echo $fila[14]?></div></td>
	<td align="right"><div align="center"><? echo number_format($fila[3],2)?></div></td>
	<td align="right"><div align="center"><? echo number_format($fila[8],2)?></div></td>
	<td align="right"><div align="center"><? echo number_format($fila[10],2)?></div></td>
	<td align="right"><div align="center"><? $vnaips=$fila[8]-$fila[10];echo number_format($vnaips,2)?></div></td>
	<td align="right"><div align="center"><? $vapeps=$fila[3]-$vnaips;echo number_format($vapeps,2)?></div></td>
	<td align="right"><div align="center"><? echo number_format($fila[12],2)?></div></td>
								  
	  <td align="right"><div align="center"><? $conse="select estadorecepcion from facturacion.motivoglosa  where nufactura = '$fila[0]' and valorrecepcion is not null limit 1 offset 0";
		//echo"\n\n $conse";
	  $rese=ExQuery($conse);
	  if(ExNumRows($rese)>0)
		{
			while($filae=ExFetch($rese))
				{
					echo $filae[0];
				}
		}
	   ?></div></td>
		  </tr> <? }?>
	  
	  <tr>
	  <td align="center" colspan="15" class="ui-state-default"><span style="cursor:pointer;" id="sendPrint"><img style="scroll: 10px center;" src="Imgs/b_print.png" title="Imprimir" alt="Imprimir" onClick="Text.print('print','sendPrint');"/></span></td></tr>
      </table></div>
		<?	} else{?>
			  <table align="center" class="ui-state-default">  
						<tr align="center"><td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda...</td></tr>
			  </table><?	}
		  }break;








    //Formato Informe Conciliacion
    case 5:{
		if($FacI){$FacIni="and nofactura>=$FacI";}
		if($FacF){$FacFin="and nofactura<=$FacF";}
		if($Entidad){$Ent="and entidad='$Entidad'";} 
		if($Contrato){$Contr="and contrato='$Contrato'"; }
		if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
		?> <div id="print">
			<table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
			
			<tr align="center"  >
			<td colspan="9" align="center" class="ui-state-default">FORMATO INFORME CONCILIACI&Oacute;N
			
			  <table width="100%" align="center" class="ui-state-default">
		<tr>
		<td align="right">Nombre IPS:</td>
		<td class="ui-widget-content"><? echo $Compania?></td>
		<td align="right">Consecutivo Interno:</td>
		<td class="ui-widget-content">&nbsp;</td>
		</tr>
		<tr>
		<td align="right">Tipo de Identificaci&oacute;n IPS:</td>
		<td class="ui-widget-content"><? echo "NIT"?></td>
		<td align="right">Fecha Conciliaci&oacute;n:</td>
		<td class="ui-widget-content">		  
		</td>
		</tr>
		<tr>
		<td align="right">Nit de la IPS:</td>
		<td class="ui-widget-content"><? echo $Nit?></td>
		<td align="right">Periodo Conciliado:</td>
		<td class="ui-widget-content"><? echo $FechaIni." - ".$FechaFin ?></td>
		</tr>
		</table>
		<p align="left"><? echo "<font size='2' >".$fila[2]."</font>"?></p></td>    
		</tr>    
		<tr align="center" class="ui-state-default">	
		<td>Fecha</td>
		<td>Valor Factura</td>
		<td>Valor Objeci&oacute;n</td>
		<td>% Objeci&oacute;n</td>
		<td>Valor Recuperado</td>
		<td>Valor Glosa Definitiva</td>
		<td>% Recuperado</td>
		<td>% Glosa Final</td>
		<td>Observaciones</td></tr>
		<?	



		$fecha1="$FechaIni"; 
		$fecha1=date("m-d-Y",strtotime($fecha1));
		$fechaInicio = $fecha1; 
		$mesInicio = substr($fechaInicio, 0, 2); 
		$diaInicio  = substr($fechaInicio, 3, 3); 
		$anioInicio= substr($fechaInicio, 6, 10);  
		// fecha fin
		$fecha2="$FechaFin"; 
		$fecha2=date("m-d-Y",strtotime($fecha2));
		$fechaFi = $fecha2; 
		$mesFin = substr($fechaFi, 0, 2); 
		$diaFin  = substr($fechaFi, 3, 3); 
		$anioFin= substr($fechaFi, 6, 10);  
		$mes=date("$mesInicio");
		$mes2=date("$mesFin");
		for($mes=$mes; $mes<=$mesFin;$mes++)
		{?>
		<tr class="ui-widget-content"> 	  
		<td align='center' style='cursor:hand'>
		<?
		if ($mes==01){
		echo "Enero";
		} else if ($mes==02){
		echo "Febrero";
		} else if ($mes==03){
		echo "Marzo";
		} else if ($mes==04){
		echo "Abril";
		} else if ($mes==05){
		echo "Mayo";
		} else if ($mes==06){
		echo "Junio";
		} else if ($mes==07){
		echo "Julio";
		}else if ($mes==08){
		echo "Junio";
		}  else if ($mes==10){
		echo "Octubre";
		} else if ($mes==11){
		echo "Noviembre";
		} else if ($mes==12){
		echo "Diciembre";  }
		echo  "&nbsp;&nbsp;<br>";
		?>
		</td>
		<td align="right">
		<?
		$cons="select fechaconciliacion,vrtotal FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		echo number_format($fila[1],2);
		 }
		} 
		?>  
		  </td>
		  <td align="right">
		 <?
		$cons="select fechaconciliacion, vrglosatotal FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		echo number_format($fila[1],2);
		 }
		} 
		?>   
		</td>
		<td align="right">
		 <?
		$cons="select fechaconciliacion, vrtotal,vrglosatotal FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){

		$promedio=round((($fila[2]/$fila[1])*100),0);
		echo "%".number_format($promedio,2);
		 }
		} 
		?>   
		  </td> <td align="right">
		 <?
		$cons="select fechaconciliacion, aceptaglosa FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		echo number_format($fila[1],2);
		 }
		} 
		?>  
		  </td>
			  <td align="right">
		 <?
		$cons="select fechaconciliacion, vrglosatotal,aceptaglosa FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		$promedio1=$fila[1]-$fila[2];
		echo number_format($promedio1,2);
		 }
		} 
		?>   
		</td>
		<td align="right">
		 <?
		$cons="select fechaconciliacion, vrglosatotal,aceptaglosa FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		$promedio2=round((($fila[2]/$fila[1])*100),0);
		if($promedio>=0){
		echo "%".number_format($promedio2,2);
		 }}
		} 
		?>   
		</td>
		<td align="right">
		 <?
		$cons="select fechaconciliacion, vrtotal,aceptaglosa FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		$promedio2=round((($promedio1/$fila[1])*100),0);
		echo "%".number_format($promedio2,2);
		 }
		} 
		?>   
		</td>
		<td align="right"s>
		 <?
		$cons="select fechaconciliacion, vrtotal,aceptaglosa FROM facturacion.respuestaglosa 
		where compania='$Compania' and fechaconciliacion>='$FechaIni 00:00:00' and  fechaconciliacion<='$FechaFin 23:59:59'";
		$res=ExQuery($cons);	
		while($fila=ExFetch($res)){	
		$vfactura= "$fila[0]";
		$vfactura=date("m-d-Y",strtotime($vfactura));
		$vfactura1 = $vfactura; 
		$mesf = substr($vfactura1, 0, 2); 
		$diaf  = substr($vfactura1, 3, 3); 
		$aniof= substr($vfactura1, 6, 10);  
		if($mes==$mesf){
		echo "-";
		 }
		} 
		?>   
		</td>
		</tr> 
		 <? }  ?>
		  </table>
		 <table width="100%" align="center" class="ui-state-highlight ui-corner-all">
			<tr>
			<td align="center" class="ui-widget-content">
		<? 
		  $conexion="SELECT nombre,cedula,usuario FROM central.usuarios where usuario='$Usuario' ";
		  $respuesta= ExQuery($conexion);
		  $fiz=ExFetch($respuesta); 
		  $nombre=$fiz[0];	 
		  $user=$fiz[2]; 
		  $cons="select rm,cargo from salud.medicos where   usuario='$user'";
						$res=ExQuery($cons);
						$fila=ExFetch($res);
						$RM=$fila[0];
						$Cargo=$fila[1]; 
		echo "".$nombre.""; 

		?>
			 </td>
			</tr>
			<tr>
			  <td class="ui-widget-content">
		   <? 
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/Firmas/$fiz[1].GIF")){?>      	
			<img src="/Firmas/<? echo $fiz[1]?>.GIF" width="158" height="63"><?
		}  ?> 	  
			  </td>  </tr>
			  <tr>
			  <td align="center" class="ui-widget-content"><? echo "<b>".$Cargo."</b>" ?> </td>
			  </tr>
			<tr>	
			<!--<td align="center" class="ui-state-default">INFORME CONCILIACI&Oacute;N: <? //echo $RM ?></td>-->
			 </tr>
			</table><div>
			
			<div align="center" class="ui-state-default"><span style="cursor:pointer;" id="sendPrint"><img style="scroll: 10px center;" src="Imgs/b_print.png" title="Imprimir" alt="Imprimir" onClick="Text.print('print','sendPrint');"/></span></div>
         <?php }break;








    //Trazabilidad
    case 6:{
	        if($FacI){$FacIni="and nofactura>=$FacI";}
		else{$FacIni="";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
		else{$FacFin="";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
		else{$Ent="";}
	if($Contrato){$Contr="and contrato='$Contrato'"; }
		else{$Contr="";}
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
		else{$NoContr="";}
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa 
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa 
	where facturascredito.compania='$Compania' 
	and fechacrea>='$FechaIni 00:00:00' 
	and fechacrea<='$FechaFin 23:59:59' 	
	and terceros.compania='$Compania' 
	and terceros.identificacion=facturascredito.entidad
	$FacIni 
	$FacFin 
	$Ent 
	$Contr $NoContr 
	and facturascredito.estado='AC'
	group by nofactura,fechacrea, total,fecharadic,fechaglosa,vrglosa,motivoglosa, (primape || segape || primnom || segnom)
	order by nofactura";
	//echo"$cons";
	$res=ExQuery($cons);
if(ExNumRows($res)>0){?>
	<table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
				<tr align="center">	
				<td colspan="26" align="center" class="ui-state-default">INFORME TRAZABILIDAD</td>
				
				</tr> 
        <tr class="ui-state-default">				
        <td>Asegurador</td>
		<td>No. Factura</td>
		<td>Fecha Generaci&oacute;n Factura</td>
        <td>Valor Bruto Factura</td>
        <td>Valor Copago </td>
        <td>Descuento</td>
        <td>Total Factura </td>
        <td>Fecha Radicaci&oacute;n Factura</td>
        <td>Valor sin Radicar</td>
        <td>Valor Devuelta </td>
        <td>Valor Glosa </td>
        <td>Fecha Radicaci&oacute;n Glosa</td>
        <td>Valor Aceptado Glosa</td>
        <td>Saldo Factura</td>
        <td>Fecha Pago Realizado</td>
            <td>Valor Pago</td> 
            <td>Valor Pendiente</td>
            <td>No Vencidad</td>
            <td>De 1 a 30 D&iacute;s</td>
            <td>De 31 a 60 D&iacute;s</td>
            <td>De 61 a 90 D&iacute;s</td>
            <td>De 91 a 120 D&iacute;s</td>
            <td>De 121 a <!--180-->150 D&iacute;s</td>
            <td>De 151 a 180 D&iacute;s</td>
            <td>De 181 a 360 D&iacute;s</td>
            <td>M&aacute;s de 360 D&iacute;s</td></tr>
          <?php
		$tfactura=0;
		$tcopago=0;
		$tdescuento=0;
		$ttfactura=0;
		$tvsradicar=0;
		$tvdevuelta=0;
		$tvglosa=0;
		$tvaglosa=0;
		$tsfactura=0;
		$tpago=0;
		$tvpendiente=0;
		$tnovencidas=0;
		$tvencidas30=0;
		$Var->row=array();
			while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);
				?>					
	  <tr class="ui-widget-content">                
         <td align="center" ><div align="left"><?php $Var->row[]=$fila[2];echo $fila[2]?>   	</div></td>
         <td align="center"><div align="center"><?php $Var->row[]=$fila[0]; echo $fila[0]?></div></td>   
         <td align="center"><div align="center"><?php $Var->row[]=$Fec[0]; echo $Fec[0]?></div></td>
         <td align="center"><div align="center"><?php $tfactura=$tfactura+$fila[3];echo number_format($fila[3],2)?></div></td>
		<?php 	$cons2="SELECT sum(valorcopago) AS VALOR FROM facturacion.liquidacion WHERE nofactura='".$fila[0]."'";
		//echo "$cons2";
	$res2=ExQuery($cons2);
	$res2=ExFetch($res2);
			if($res2[0]<1){$res2[0]=0;}
	?>
         <td align="right"><div align="center"><?php $tcopago=$tcopago+$res2[0];echo number_format($res2[0],2) ?></div></td>
		<?php 	$cons3="SELECT sum(valordescuento) AS VALOR FROM facturacion.liquidacion WHERE nofactura='".$fila[0]."'";
		//echo "$cons3";
	$res3=ExQuery($cons3);
	$res3=ExFetch($res3);
	if($res3[0]<1){$res3[0]=0;}
	?>
         <td align="right"><div align="center"><?php $tdescuento=$tdescuento+$res3[0];echo number_format($res3[0],2) ?></div></td>
		 <?php $res44=$fila[3]+$res2[0]-$res3[0]; ?>
         <td align="right"><div align="center"><?php $ttfactura=$ttfactura+$res44;echo number_format($res44,2) ?></div></td>
         <td align="right"><div align="center"><?php echo $fila[4] ?></div></td>
        <td align="right"><div align="center">
          <?php	$numf= $fila[0];				     
$con4= "SELECT vrglosatotal,fecharasis,aceptaglosa,pagarips FROM facturacion.respuestaglosa where nufactura='".$fila[0]."'";	
//echo "consulta $con4";			
$res4= ExQuery($con4);
$res4=ExFetch($res4);
if($fila[4])
	{
		echo number_format(0,2);
	}
if($fila[4]==NULL)
	{
		$tvsradicar=$tvsradicar+$res44;
		echo number_format($res44,2);
	}
  ?>
        </div>        </td>
         <td align="right"><div align="center">
           <?php 
					$cons5="SELECT devolucion FROM facturacion.facturascredito WHERE nofactura='".$fila[0]."'";
	//	echo "$cons5";
	$res5=ExQuery($cons5);
	$res5=ExFetch($res5);
switch($res5[0])
	{
		case NULL: echo number_format(0,2);
		break;
		default: $tdevuelta=$tdevuelta+$res44;
		echo number_format($res44,2);
		break;
	}

	?>
         </div></td>
         <td align="right"><div align="center"><?php $tvglosa=$tvglosa+$res4[0];echo number_format($res4[0],2)?></div></td>
         <td align="right"><div align="center"><?php

		 
		  echo $res4[1] ?></div></td>
         <td align="right"><div align="center"><?php
		 
	 
		  $tvaglosa=$tvaglosa+$res4[2]; echo number_format($res4[2],2)?></div></td>
         <td align="right"><div align="center"><?php
		 $saldo=$fila[3]-$res4[2];$tsfactura=$tsfactura+$saldo;echo number_format($saldo,2);//echo $saldo;?></div></td>
         
 <td align="right">
                    <div align="center"><?php 
					
					$cons6="select comprobante from contabilidad.movimiento where docsoporte ='".$fila[0]."' and numero!='".$fila[0]."'";
					  //echo "$cons6";
	$res6=ExQuery($cons6);
	$res6=ExFetch($res6);
	//echo "$res6[0], ";
					
					$cons9="select fecha,haber from contabilidad.movimiento where docsoporte ='".$fila[0]."' and comprobante='".$res6[0]."' and haber>0";
			//echo"$cons9";	
			$res9=ExQuery($cons9);
			while($fres9=ExFetch($res9))
			{
			echo number_format($fres9[1],2);
			echo" el día $fres9[0]</br></br>";
			}
			
			?></div></td> <td><div align="center">
                      <?php   

					  
	$cons7="select tipocomprobant from contabilidad.comprobantes where comprobante='".$res6[0]."'";
	//echo"$cons7";	
	$res7=ExQuery($cons7);
	$res7=ExFetch($res7);		  
	//echo"$res7[0]";
	switch($res7[0])
		{
			
			case "Ingreso":
			//echo"Entrooo";
			$cons8="select sum(haber) from contabilidad.movimiento where docsoporte ='".$fila[0]."' and comprobante='".$res6[0]."'";
			//echo"$cons8";	
			$res8=ExQuery($cons8);
			$res8=ExFetch($res8);
			$tpago=$tpago+$res8[0];		  
			echo number_format($res8[0],2);
			break;
			default:
			echo number_format(0,2);
			$res8=0;
			break;
				
		}

					?>
                    </div></td>                   
                    <td><div align="center"><?php 
					//echo"$fila[3] - $res8[0] - $res4[2]";
					$vpendiente=$fila[3]-$res8[0]-$res4[2]; $tvpendiente=$tvpendiente+$vpendiente;echo number_format($vpendiente,2); ?></div></td>
                    <td><div align="center">
					<?php 
					$aaaa=substr("$Fec[0]", -10,4);
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					$f_act=getdate();
					//echo"$aaaa,$mm,$dd";
					//echo "la fecha es $Fec[0]";
					?>
					
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=0)&&($dxmm<=1))
										{
											if(($dxmm>0)&&($dd>$f_act[mday]))
												{
													$tnovencidas=$tnovencidas+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=0)&&($mm<=1))
										{
											if(($dxmm>0)&&($dd>$f_act[mday]))
												{
													$tnovencidas=$tnovencidas+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>
					
					</div></td>
					<td><div align="center">
					
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=1)&&($dxmm<=2))
										{
											if(($dxmm>1)&&($dd>$f_act[mday]))
												{
													$tvencidas30=$tvencidas30+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=1)&&($mm<=2))
										{
											if(($mm>1)&&($dd>$f_act[mday]))
												{
													$tvencidas30=$tvencidas30+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>	
									
					</div></td>
                    <td><div align="center">
					
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=2)&&($dxmm<=3))
										{
											if(($dxmm>2)&&($dd>$f_act[mday]))
												{
													$tvencidas60=$tvencidas60+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=2)&&($mm<=3))
										{
											if(($mm>2)&&($dd>$f_act[mday]))
												{
													$tvencidas60=$tvencidas60+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>	
									
					</div></td>
                    <td><div align="center">
					
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=3)&&($dxmm<=4))
										{
											if(($dxmm>3)&&($dd>$f_act[mday]))
												{
													$tvencidas90=$tvencidas90+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=3)&&($mm<=4))
										{
											if(($mm>3)&&($dd>$f_act[mday]))
												{
													$tvencidas90=$tvencidas90+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>
										
					</div></td>
                    <td><div align="center">
              
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=4)&&($dxmm<=5))
										{
											if(($dxmm>4)&&($dd>$f_act[mday]))
												{
													$tvencidas120=$tvencidas120+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=4)&&($mm<=5))
										{
											if(($mm>4)&&($dd>$f_act[mday]))
												{
													$tvencidas120=$tvencidas120+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>
			  
                    </div></td>
                    <td><div align="center">
                    
					<?php 
					/*if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=5)&&($dxmm<=7))
										{
											if(($dxmm>5)&&($dd>$f_act[mday]))
												{
													$tvencidas180=$tvencidas180+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=5)&&($mm<=7))
										{
											if(($mm>5)&&($dd>$f_act[mday]))
												{
													$tvencidas180=$tvencidas180+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}*/
						if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=5)&&($dxmm<=6))
										{
											if(($dxmm>5)&&($dd>$f_act[mday]))
												{
													$tvencidas150=$tvencidas150+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=5)&&($mm<=6))
										{
											if(($mm>5)&&($dd>$f_act[mday]))
												{
													$tvencidas180=$tvencidas180+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
						$mm=substr("$Fec[0]", -5,2);
						if($mm<10){$mm=str_replace("0","","$mm");}					
						$dd=substr("$Fec[0]", -2);
						if($dd<10){$dd=str_replace("0","","$dd");}
						?>
				  
						</div></td>
						<td><div align="center">
						
						<?php 
						if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=6)&&($dxmm<=7))
										{
											if(($dxmm>6)&&($dd>$f_act[mday]))
												{
													$tvencidas150=$tvencidas150+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=6)&&($mm<=7))
										{
											if(($mm>6)&&($dd>$f_act[mday]))
												{
													$tvencidas180=$tvencidas180+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>
					
                    </div></td>
                  <td><div align="center">
        
					<?php 
					if($saldo>0)
						{		
							$mm=12-$mm+$f_act[mon];
							if($mm>=12)
								{
									$mm=substr("$Fec[0]", -5,2);
									if($mm<10){$mm=str_replace("0","","$mm");}					
									$dd=substr("$Fec[0]", -2);
									if($dd<10){$dd=str_replace("0","","$dd");}
									$dxmm=$f_act[mon]-$mm;
									if(($dxmm>=7)&&($dxmm<=12))
										{
											if(($dxmm>7)&&($dd>$f_act[mday]))
												{
													$tvencidas360=$tvencidas360+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
							$mm=substr("$Fec[0]", -5,2);
							if($mm<10){$mm=str_replace("0","","$mm");}					
							$dd=substr("$Fec[0]", -2);
							if($dd<10){$dd=str_replace("0","","$dd");}
							$mm=12-$mm+$f_act[mon];
							if($mm<12)
								{
									if(($mm>=7)&&($mm<=12))
										{
											if(($mm>7)&&($dd>$f_act[mday]))
												{
													$tvencidas360=$tvencidas360+$vpendiente;
													echo number_format($vpendiente,2);
												}else{echo number_format(0,2);}
										}else{echo number_format(0,2);}
								}
						}else{echo number_format(0,2);}
					$mm=substr("$Fec[0]", -5,2);
					if($mm<10){$mm=str_replace("0","","$mm");}					
					$dd=substr("$Fec[0]", -2);
					if($dd<10){$dd=str_replace("0","","$dd");}
					?>
		
                  </div></td>
                    <td><div align="center">
                    <?php 
					if($saldo>0)
						{
							if(($f_act[year]>$aaaa)&&($f_act[mon]>$mm))
								{
								//echo"entro 7";
									$tvencidasm360=$tvencidasm360+$vpendiente;
									echo number_format($vpendiente,2);									
								}
							else{echo number_format(0,2);}	
						}
					else{echo number_format(0,2);}	
					?>
                    </div></td>
                  <?php ?>                 
      </tr> <?php   
	  } ?>
               
				<tr>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
				  <td height="10">&nbsp;</td>
	  </tr>
				<tr class="ui-widget-content">
				   <td><div align="right" class="ui-state-default"><strong>TOTAL:</strong></div></td>
				   <td><div align="center"></div></td>
				   <td><div align="center"></div></td>
				   <td><div align="center"><strong><?php echo number_format($tfactura,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tcopago,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tdescuento,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($ttfactura,2); ?></strong></div></td>
				   <td><div align="center"></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvsradicar,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvdevuelta,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvglosa,2); ?></strong></div></td>
				   <td><div align="center"></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvaglosa,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tsfactura,2); ?></strong></div></td>
				   <td><div align="center"></div></td>
				   <td><div align="center"><strong><?php echo number_format($tpago,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvpendiente,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tnovencidas,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas30,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas60,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas90,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas120,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format(/*$tvencidas180*/$tvencidas150,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas180,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidas360,2); ?></strong></div></td>
				   <td><div align="center"><strong><?php echo number_format($tvencidasm360,2); ?></strong></div></td>
      </tr>
  </table>
<?php	
}else{ ?>
		        <table align="center" class="ui-state-default">  
					<tr align="center"><td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda...</td></tr>
				</table> <?	}
				}break;	








    //Consolidado
    case 7:{
			if($FacI){$FacIni="and facturacion.facturascredito.nofactura>=$FacI";}
			if($FacF){$FacFin="and facturacion.facturascredito.nofactura<=$FacF";}
			if($Entidad){$Ent="and entidad='$Entidad'";} 
			if($Contrato){$Contr="and facturacion.facturascredito.contrato='$Contrato'"; }
			if($NoContrato){$NoContr="and facturacion.facturascredito.nocontrato='$NoContrato'"; }
		$cons="select motivoglosa.nufactura,primape,segape,primnom,
		segnom,identificacion,vrtotal,tipoglosa,
		motivoglosa.vrglosa ,observacionglosa,aceptaglosa,obseraceptado
		from facturacion.facturascredito,central.terceros,facturacion.motivoglosa,facturacion.liquidacion 	
		where facturascredito.compania='$Compania' and facturascredito.fechacrea>='$FechaIni 00:00:00' and facturascredito.fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania' and fecharadic is not null 
		and motivoglosa.nufactura=facturascredito.nofactura and motivoglosa.nufactura=liquidacion.nofactura
		and terceros.identificacion=liquidacion.cedula $FacIni $FacFin $Ent $Contr $NoContr order by motivoglosa.nufactura ASC";
		$res=ExQuery($cons);
			if(ExNumRows($res)>0){?><div id="print">
			<table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
				<tr align="center">	
				<td colspan="16" align="center" class="ui-state-default">INFORME CONSOLIDADO</td>        
				</tr>       
				<tr align="center" class="ui-state-default">	 
		<td>#</td>		
		<td>No. Factura</td>
		<td>Primer Apellido del Paciente</td>
		<td>Segundo Apellido Del paciente</td>
		<td>Primer Nombre del Paciente</td>
		<td>Segundo nombre Del paciente</td>
		<td>Numero de identifacion del paciente</td>
		<td>Valor Total factura</td>
		<td>Codigo Glosa</td>
		<td>Valor Objetado</td>
		<td>Observaciones</td>
		<td>Valor aceptado por IPS</td>
		<td>Valor de la objecion No aceptado por IPS</td>
		<td>Respuesta IPS</td>
		<?	while($fila=ExFetch($res)){
		$Fec=explode(" ",$fila[1]);?>	
			  <tr class="ui-widget-content">         
				 <td align="center" >  <? $cont++; echo $cont ?> 	</td>		 
				 <td><? echo $fila[0]?></td>
				 <td align="center"><? echo $fila[1]?></td>   
				 <td align="center"><? echo $fila[2]?></td>
				 <td align="center"><? echo $fila[3]?></td>
				 <td align="center"><? echo $fila[4]?></td>
				 <td align="center"><? echo $fila[5]?></td>		
				 <td align="right"><? echo number_format($fila[6],2)?></td>       
				 <td align="center"><? echo $fila[7]?></td>                   
				 <td align="right"><? echo number_format($fila[8],2)?></td>
				 <td align="justify"><? echo $fila[9]?></td>
				 <td align="right"><? echo number_format ($fila[10],2)?></td>
				 <td align="right">
				 <?
				 $saldo=0;
				 $saldo= $fila[8]-$fila[10];
				 echo number_format ($saldo,2);
				 ?>		 
				 </td>
				 <td align="justify"><? echo $fila[11]?></td>					               
				 </tr>			
		<? 

		$totalfac=$totalfac+$fila[6];
		$vobjetado=$vobjetado+$fila[8];
		$vaceptado=$vaceptado+$fila[10];
		$noaceptado=$noaceptado+$saldo;
		} ?>
		 <tr>
				 <td colspan="7" align="right" class="ui-state-default">TOTALIZADO:</td>
				 <td align="right" class="ui-widget-content"><? echo number_format ($totalfac,2)?></td>
				 <td align="center"></td>
				 <td align="right" class="ui-widget-content"><? echo number_format ($vobjetado,2)?></td>
				 <td></td>
				 <td align="right" class="ui-widget-content"><? echo number_format ($vaceptado,2)?></td>
				 <td align="right" class="ui-widget-content"><? echo number_format ($noaceptado,2)?></td>
				 </tr>
				 <tr>
				 <td align="center" colspan="16" class="ui-state-default"><span style="cursor:pointer;" id="sendPrint"><img style="scroll: 10px center;" src="Imgs/b_print.png" title="Imprimir" alt="Imprimir" onClick="Text.print('print','sendPrint');"/></span></td>
				 </tr>
		  </table></div>
		<? 	} 
		else{ ?>
		        <table align="center" class="ui-state-default">  
					<tr align="center"><td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda...</td></tr>
				</table> <?	}
				}break;	
				
				
				
				
				
				
				
				
		//Respuestas Glosas
	   case 9:{
	   if($insurer)
	   $consTA="SELECT tipo,codigo FROM central.tiposaseguramiento where codigo='$insurer'";
	   $resTA=ExQuery($consTA);
	   $filaTA=ExFetch($resTA);	

	if($FacI){$FacIni="and nofactura>=$FacI";}
		else{$FacIni="";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
		else{$FacFin="";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
		else{$Ent="";}
	if($Contrato){$Contr="and contrato='$Contrato'"; }
		else{$Contr="";}
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
		else{$NoContr="";}	
    if($insurer)$TI="and tipoasegurador='$filaTA[0]'";
	    else $TI="";
			$cons="select nofactura,fechacrea,fecharadic,(primape || segape || primnom || segnom) as noment,total,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa,facturacion.codrespuestaglosa.codigo,facturacion.codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,tipoasegurador,codmotivoglosa.codigo,codmotivoglosa.detalle 	   	 		
	from facturacion.facturascredito,central.terceros,facturacion.respuestaglosa,facturacion.informerespuesglosa,facturacion.motivoglosa 
	INNER JOIN facturacion.codrespuestaglosa on motivoglosa.codrespuestaglosa=codrespuestaglosa.codigo
	INNER JOIN facturacion.codmotivoglosa on facturacion.codmotivoglosa.codigo=facturacion.motivoglosa.tipoglosa::varchar
	where facturascredito.compania='$Compania' 
	and fecharadic>='$FechaIni 00:00:00' 
	and fecharadic<='$FechaFin 23:59:59' 	
	and terceros.compania='$Compania' 
	and facturacion.motivoglosa.nufactura=facturacion.facturascredito.nofactura
	and terceros.identificacion=facturascredito.entidad
	$FacIni 
	$FacFin 
	$Ent 
	$Contr $NoContr 
	and facturascredito.estado='AC'
	and fecharadic is not NULL
	and facturascredito.estado='AC'
	and nofactura=facturacion.informerespuesglosa.nufactura
	and facturacion.informerespuesglosa.fecharasis is not NULL $TI
	group by nofactura,fechacrea,total,fecharadic,facturascredito.fechaglosa,facturascredito.vrglosa,motivoglosa, (primape || segape || primnom || segnom),facturacion.codrespuestaglosa.codigo,facturacion.codrespuestaglosa.detalle,motivoglosa.vrglosa,motivoglosa.aceptaglosa,facturacion.respuestaglosa.numrecepcionrespuesta,facturacion.respuestaglosa.fecharespuesta,tipoasegurador,codmotivoglosa.codigo,codmotivoglosa.detalle 	  
	order by nofactura";
	$res=ExQuery($cons);
	$tradicadas=0;
if(ExNumRows($res)>0){?>
   <table width="100%" align="center" class="ui-state-highlight ui-corner-all">  
    	<tr align="center">	
        <td colspan="14" align="center" class="ui-state-default">INFORME DE RESPUESTA GLOSAS</td>
        </tr>
        <tr align="center" class="ui-state-default">	  	
			
        <td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicaci&oacute;n</td>
        <td>Entidad</td><td>Tipo Asegurador</td><td>Cod. Respuesta EPS</td><td>Cod. Respuesta IPS</td><td>Valor Factura</td><td>Valor Glosa</td>
            <td>Valor Aceptado IPS</td> <td>Valor Objetado No aceptado IPS</td><td>Valor a Pagar EPS</td>
       	    <td>N&uacute;mero Informe de Radicado</td>
       	    <td>Fecha De Radicaci&oacute;n de Respuesta</td>
   	      <?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);
				$i=0;
				if($type==0)
					{
						$resx=ExQuery($consx);
						if(ExNumRows($resx)>0)
							{
								while($filax=ExFetch($resx))
									{
										$factx[$i]=$filax[0];
										$i=$i+1;
									}
							}
					}
				$cresg=0;
				for($j=0;$j<=$i;$j++)
					{
						if($fila[0]==$factx[$j])
							{
								 $cresg=1;
							}
					}
				if($cresg==0){
					?>	
       			<tr class="ui-widget-content">                
                	<td align="center">
					<div align="center"><? echo $fila[0]?>				        </div></td>
                    <td align="center"><div align="center"><? echo $Fec[0]?></div></td><td align="center"><div align="center"><? echo $fila[2]?></div></td>
                    <td align="center"><div align="center"><? echo $fila[3]?></div></td>
					<td align="center"><div align="center"><? echo $fila[14]?></div></td>
					<td><div align="center"><? echo "Cod. ".$fila[15]."<br>".$fila[16]?></div></td>
					<td><div align="center"><? echo "Cod. ".$fila[8]."<br>".$fila[9]?></div></td>
                    <td align="right"><div align="center"><? echo number_format($fila[4],2)?></div></td>
                    <td align="right"><div align="center"><?echo number_format($fila[10],2);?></div></td>
					<td align="right"><div align="center"><?echo number_format($fila[11],2);?></div></td>
					<td align="right"><div align="center"><?$VONAIPS=($fila[10]-$fila[11]);echo number_format($VONAIPS,2);?></div></td>
					<td align="right"><div align="center"><?$VPEPS=($fila[4]-$fila[11]);echo number_format($VPEPS,2);?></div></td>
					<td align="right"><div align="center"><?echo $fila[12];?></div></td>
					<td align="right"><div align="center"><?echo $fila[13];?></div></td>
                      <!--<div align="center"><font style="font-size:11px">-->
                        <? 
					 // if($estadonb==1)
					  	//{
							/*$cons2="select vrglosatotal,aceptaglosa from facturacion.respuestaglosa where nufactura='$fila[0]'";
					  		$res2=ExQuery($cons2);
					  		if(ExNumRows($res2)>0)
								{
					  				while($fila2=ExFetch($res2))
										{
											echo number_format($fila2[0],2); ?>
                      </font></div></td> <td><div align="center"><?  echo number_format($fila2[1],2);?></div></td>                   
                    <td><div align="center">nnnn
                      <? $vona=$fila2[0]-$fila2[1];if($estadonb==1){echo number_format($vona,2);} }}*/?>
                    <!--</div></td>
                    <td><div align="center"><? //$vapeps=$fila[4]-$vona;if($estadonb==1){ echo number_format($vapeps,2);} ?></div></td>                 
                    <td><div align="center"><? //if($estadonb==1){echo $fila[5]; }?></div></td>
                    <td><div align="center"><? //$fila[6]=substr($fila[6],-19,10);if($estadonb==1){echo $fila[6];}?></div></td>-->
       			</tr> <? //}
				}}?></table>
<?	} else{?>
				<table align="center" class="ui-state-default">  
					<tr align="center">
					  <td>No hay Glosas que cumplan con los par&aacute;metros de la b&uacute;squeda...</td>
					</tr>
				</table> <?	}
	   }break;		
}//End switch
?>

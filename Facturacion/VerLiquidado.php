<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	 
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
	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradoras[$fila[0]]="$fila[1] $fila[2] $fila[3] $fila[4]";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<input ="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="0" align="center">
<?	if($FechaIni&&$FechaFin){
		if($Entidad){$Ent="and pagador='$Entidad'";}
		if($Contrato){$Contra="and contrato='$Contrato'";}
		if($NoContrato){$NoContra="and nocontrato='$NoContrato'";}
		if($Ambito){
			if($Ambito=='Consulta Externa'){
				$Amb="and (ambito='$Ambito' or ambito='1')";
			}
			else
			{
				$Amb="and ambito='$Ambito'";
			}
		}
		if($Tipo=="Facturadas"){$TipoFac="and nofactura is not null";}	
		elseif($Tipo=="Sin Facturar"){$TipoFac="and nofactura is null";}
		elseif($Tipo=="Anuladas"){$TipoFac="and estado='AN'";}
		elseif($Tipo=="Activas"){$TipoFac="and estado='AC'";}
		if($OrdenarPor=="NoLiq"){$OrdBy=" order by noliquidacion";}
		if($OrdenarPor=="NoFac"){$OrdBy=" order by nofactura";}
		if($OrdenarPor=="IdPac"){$OrdBy=" order by cedula";}
		if($OrdenarPor=="NomPac"){$OrdBy=" order by primape,segape,primnom,segnom";}
		if($OrdenarPor=="Entidad"){$OrdBy=" order by pagador,primape,segape,primnom,segnom";}
		if($Desde){$Ini=" and noliquidacion>=$Desde";}
		if($Hasta){$Fin=" and noliquidacion<=$Hasta";}
		
		$cons="select noliquidacion,subtotal,valorcopago,valordescuento,total,nofactura,pagador,cedula,primape,segape,primnom,segnom,estado,nofactura,contrato,nocontrato
		,fechaini,fechafin,liquidacion.tipousu,liquidacion.nivelusu
		from facturacion.liquidacion,central.terceros
		where liquidacion.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and identificacion=cedula
		$Ent $Contra $NoContra $Amb $TipoFac $Ini $Fin $OrdBy";
		$res=ExQuery($cons);		
		//echo $cons;
		if($Desde&&$Hasta){?>
			<tr align="center">
            	<td colspan="10">
                	<input type="button" value="Imprimir Bloque" onClick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&Masa=1&NoLiqConsecIni=<? echo $Desde?>&NoLiqConsecFin=<? echo $Hasta?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','width=800,height=600,scrollbars=YES')">
                </td>
            </tr>	
	<?	}
		if(ExNumRows($res)>0){
			if(!$VerDet){?>
                <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
                    <td>No. Liquidacion</td><td>Usuario</td><td>Identificaion</td><td>Entidad</td><td>SubTotal</td><td>Copago</td><td>Descuento</td><td>Total</td><td>No Factura</td><td></td>
                </tr>
    <?			while($fila=ExFetch($res))
                {	
                    $TotSubT=$TotSubT+$fila[1];
                    $TotCop=$TotCop+$fila[2];
                    $TotDesc=$TotDes+$fila[3];	
                    $T=$fila[4]+$T;
                    if($fila[2]==''){$fila[2]="0";}
                    if($fila[3]==''){$fila[3]="0";}?>
                    <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" <? if($fila[12]=="AN"){?> style="color:#FF0000; text-decoration:underline" title="Anulada"<? }?>>
                        <td align="center" onClick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila[0]?>&Ced=<? echo $fila[7]?>&Estado=<? echo $fila[12]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=900,height=700,menubar=yes,scrollbars=YES')"style="cursor:hand" title="Ver">
                            <? echo $fila[0];?>
                        </td>               
                        <td align="center"><? echo strtoupper("$fila[8] $fila[9] $fila[10] $fila[11]");?></td>
                        <td align="center"><? echo $fila[7];?></td>
                        <td align="center"><? echo $Aseguradoras[$fila[6]];?></td>
                        <td align="right"><? echo number_format($fila[1],2)?></td><td align="right"><? echo number_format($fila[2],2)?></td>
                        <td align="right"><? echo number_format($fila[3],2)?></td><td align="right"><? echo number_format($fila[4],2)?></td>
                        <td style="cursor:hand" align="center" <? if($fila[13]){?> title="Ver Factura"
                            onClick="open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[13]?>&Estado=AC&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')"
                        <? }?>>
                        <? if($fila[13]){ echo $fila[13];}else{ echo "Sin Facturar";}?></td>
                    <? 	if($fila[5]==''){?>
                            <td><img style="cursor:hand"  title="Anular" 
                                onClick="if(confirm('Desea anular este registro?')){parent.document.FORMA.NoLiq.value=<? echo $fila[0]?>;parent.document.FORMA.submit();}" 
                                src="/Imgs/b_drop.png"> 
                            </td>
                   <? 	}
                        else{?>
                             <td><img style="cursor:hand"  title="Anular" 
                                onClick="alert('Esta liquidacion no se puede anular debido a que ya ha sido facturada!!!')" src="/Imgs/b_drop.png"> 
                            </td>
                    <?	}?>
                    </tr>                
            <?	}?>
                <tr align="right">    	
                    <td colspan="4" style="font-weight:bold" >Totales</td><td><? echo number_format(round($TotSubT),2)?></td><td><? echo number_format(round($TotCop),2)?></td>
                    <td><? echo number_format(round($TotDesc),2)?></td><td><? echo number_format(round($T),2)?></td><td>&nbsp;</td>
                </tr>
<?			}
			else
			{				
				while($fila=ExFetch($res))
                {?>
                	<tr>
                    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Factura</td><td><? echo $fila[0]?></td>
                        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad Responsable de Pago</td><td><? echo $Aseguradoras[$fila[6]];?></td>
                    </tr>
                    <tr>
                    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Contrato</td><td><? echo $fila[14]?></td>
                        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Contrato</td><td><? echo $fila[15]?></td>
                    </tr>
                    <tr>
                    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Paciente</td><td><? echo strtoupper("$fila[8] $fila[9] $fila[10] $fila[11]");?></td>
                        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Identificacion</td><td><? echo $fila[7]?></td>                        
                    </tr>
                    <tr>
                    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Usuario</td><td><? echo $fila[18]?></td>
                        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Niverlusuario</td><td><? echo $fila[19]?></td>
                    </tr>
                    <tr>
                    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Inicial</td><td><? echo $fila[16]?></td>
                        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Final</td><td><? echo $fila[17]?></td>
                    </tr>                    
            	<?	
				/*	$cons="select noliquidacion,subtotal,valorcopago,valordescuento,total,nofactura,pagador,cedula,primape,segape,primnom,segnom,estado,nofactura,contrato,nocontrato,fechaini,fechafin
		from facturacion.liquidacion,central.terceros
		where liquidacion.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and identificacion=cedula
		$Ent $Contra $NoContra $Amb $TipoFac $Ini $Fin $OrdBy";*/
					$cons2="select codigo,nombre,vrunidad,grupo,tipo,generico,presentacion,forma,sum(cantidad),almacenppal from facturacion.detalleliquidacion
					where compania='$Compania[0]' and noliquidacion=$fila[0] group by tipo,grupo,codigo,nombre,vrunidad,generico,presentacion,forma,almacenppal
					order by tipo,grupo,codigo";
					$res2=ExQuery($cons2);
					$Subtotal=0;?>
                    <tr>
                    	<td colspan="4">
                        	<table BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="0" align="center">
                            	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                                	<td>Tipo</td><td>Codigo</td><td>Nombre</td><td>Vr Und</td><td>Cant</td><td>VrTotal</td>                                    
                                </tr>
                         	<?	while($fila2=ExFetch($res2)){									?>
									<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                                    	<td><? if($fila2[4]=="Medicamentos"){ echo "Medicamento";}else{echo "CUP";}?></td><td><? echo $fila2[0]?></td>
                                        <td><? echo "$fila2[1]"?></td>
                                        <td align="right"><? echo number_format($fila2[2],2);?></td>
                                        <td align="right"><? echo $fila2[8]?></td>
                                        <td align="right"><? 
											if(($fila[6]=='800198972-6'||$fila[6]=='890399029-5'||$fila[6]=='891580016-8'||$fila[6]=='I891280001-0'||$fila[6]=='800103913-4')&&$fila2[3]=="Medicamentos"){echo "0.00";}else{echo number_format(($fila2[2]*$fila2[8]),2);}?></td>
                                    </tr>
							<?		
									if(($fila[6]=='800198972-6'||$fila[6]=='890399029-5'||$fila[6]=='891580016-8'||$fila[6]=='I891280001-0'||$fila[6]=='800103913-4')&&$fila2[3]=="Medicamentos"){
										$fila2[2]=0;
									}
									if($fila2[0]==0&&$fila2[3]=="Medicamentos"){}
									else{
										$Subtotal=$Subtotal+($fila2[2]*$fila2[8]);
									}
								}			?>
                                <tr>                                
                                	<td colspan="5" align="right"><strong>Subtotal </strong></td><td align="right"><? echo number_format($Subtotal,2)?></td>
                                </tr>
                                <tr>                                
                                	<td colspan="5" align="right"><strong>Copago</strong></td><td align="right"><? echo number_format($fila[2],2)?></td>
                                </tr>
                                <tr>
                                	<td colspan="5" align="right"><strong>Descuento</strong></td><td align="right"><? echo number_format($fila[3],2)?></td>
                                </tr>
                          	<?	$Total=$Subtotal-$fila[2]-$fila[3]?>
	                            <tr>
                                	<td colspan="5" align="right"><strong>Total </strong></td><td align="right"><? echo number_format($Total,2)?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>  
                    <tr>
                    	<td colspan="6">&nbsp;</td>
                    </tr> 
			<?	}
			}
		}
		else{?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
		    	<td colspan="7">No se encuentran registros que coincidan con los criterios de busqueda</td>
			</tr>
	<?	}
	}
	?>       
</table>
</form>    
</body>
</html>

<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons="select nombre,usuario from central.usuarios";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]=$fila[0];
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
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<input type="hidden" name="Impresion" value="<? echo $Impresion?>">
<?
if($Desde&&$Hasta)
{?>
<center><input type="button" name="Imprimir Bloque" value="Imprimir Bloque" onClick="open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $Desde?>&NoFacFin=<? echo $Hasta?>&Impresion=<? echo $Impresion?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')"></center>		
<?
}?>
<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="0" align="center">
<?	if($FechaIni&&$FechaFin){
		if($Entidad){$Ent="and entidad='$Entidad'";}
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
		if($Tipo=="Todas"){$OpcTipo="";}
		elseif($Tipo=="Activas"){$OpcTipo="and estado='AC'";}
		elseif($Tipo=="Anuladas"){$OpcTipo="and estado='AN'";}
		if($Desde){$Ini=" and nofactura>=$Desde";}
		if($Hasta){$Fin=" and nofactura<=$Hasta";}
		if($Usucrea&&$Usucrea!="Todos"){$UsC=" and usucrea='$Usucrea'";}
		$cons="select nofactura,subtotal,copago,descuento,total,fechaglosa,entidad,estado,individual,usucrea,fechacrea from facturacion.facturascredito 
		where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' $Ent $Contra $NoContra $Amb $Ini $Fin $OpcTipo $UsC
		order by nofactura";		
		$res=ExQuery($cons);		
		//echo $cons;
		if(ExNumRows($res)>0){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    			<td>No. Factura</td><td>Entidad</td><td>Creada Por</td><td>Fecha Crea</td><td>SubTotal</td><td>Copago</td><td>Descuento</td><td>Total</td><td></td>
			</tr>
<?			while($fila=ExFetch($res))
			{	
				if($fila[8]==1){
					$cons2="select fechaini,fechafin,cedula,numservicio from facturacion.liquidacion where compania='$Compania[0]' and nofactura=$fila[0]";	
					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);
				}
				$TotSubT=$TotSubT+$fila[1];
				$TotCop=$TotCop+$fila[2];
				$TotDesc=$TotDes+$fila[3];				
				$T=$fila[4]+$T;
				if($fila[2]==''){$fila[2]="0";}
				if($fila[3]==''){$fila[3]="0";}?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"  <? if($fila[7]=="AN"){?> style="color:#FF0000; text-decoration:underline" title="Anulada"<? }?>>
                	<td align="center" style="cursor:hand" title="Ver"
                    onClick="open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo $fila[7]?>&Impresion=<? echo $Impresion?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')">
						<? echo $fila[0]?>
                    </td>
                    <td><? echo $Aseguradoras[$fila[6]];?></td><td align="center"><? echo strtoupper($Usus[$fila[9]])?></td><td><? echo $fila[10]?></td>
                    <td align="right"><? echo number_format($fila[1],2)?></td><td align="right"><? echo number_format($fila[2],2)?></td>
                    <td align="right"><? echo number_format($fila[3],2)?></td><td align="right"><? echo number_format($fila[4],2)?></td>
                <? 	if($fila[5]==''){?>
	                    <td><img style="cursor:hand"  title="Eliminar" 
    	                	onClick="if(confirm('Desea anular este registro?')){parent.document.FORMA.NoFac.value=<? echo $fila[0]?>;parent.document.FORMA.submit();}" 
    						src="/Imgs/b_drop.png"> 		    			
               <? 	}
                   	else{?>
                    	 <td><img style="cursor:hand"  title="Eliminar" 
    	                	onClick="alert('Esta factura no se puede anular debido a que ha sido glosada!!!')" src="/Imgs/b_drop.png"> 		    			
				<?	}
					if($fila[8]==1&&$fila2[2]){?>
                    	<img style="cursor:hand" title="Ver Regitros Formatos" src="/Imgs/b_print.png"
                        onClick="open('/HistoriaClinica/VerFormatosxPac.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $fila2[2]?>&NumServ=<? echo $fila2[3]?>&FechaIni=<? echo $fila2[0]?>&FechaFin=<? echo $fila2[1]?>&NoFac=<? echo $fila[0]?>','','width=800,height=600,scrollbars=yes');"> 
               	<?	}?>
                	</td>
                </tr>                
		<?	}?>
			<tr align="right">    	
		    	<td colspan="2" style="font-weight:bold" >Totales</td><td><? echo number_format($TotSubT,2)?></td><td><? echo number_format($TotCop,2)?></td>
                <td><? echo number_format($TotDesc,2)?></td><td><? echo number_format($T,2)?></td><td>&nbsp;</td>
			</tr>
<?		}
		else{?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
		    	<td colspan="4">No se encuentran registros que coincidan con los criterios de busqueda</td>
			</tr>
	<?	}
	}
	?>       
</table> 
</form>    
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">  
</iframe>
</body>
</html>

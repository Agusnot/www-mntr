<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($NoLiq){		
		$cons="update facturacion.liquidacion set estado='AN' where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion=$NoLiq";
		//echo $cons;
		$res=ExQuery($cons);		
		/*$cons="update salud.plantillaprocedimientos set noliquidacion=NULL
		where plantillaprocedimientos.compania='$Compania[0]' and cedula='$Paciente[1]' and plantillaprocedimientos.estado='AC' and noliquidacion=$NoLiq";
		$res=ExQuery($cons);
		$cons="select tblformat from historiaclinica.formatos where estado='AC' and compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res)){
			$cons5=$cons5." update  histoclinicafrms.$fila[0] set noliquidacion=0
			where $fila[0].compania='$Compania[0]' and $fila[0].cedula='$Paciente[1]' and $fila[0].cup is not null  and noliquidacion=$NoLiq ";					
			$res=ExQuery($cons5); echo ExError();
		}	
		$cons="update consumo.movimiento set noliquidacion=NULL where movimiento.compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
		and noliquidacion=$NoLiq and tipocomprobante='Salidas' and almacenppal in(select almacenppal from consumo.almacenesppales where compania='$Compania[0]'
		and ssfarmaceutico=1)";
		$res=ExQuery($cons);*/
	}
	$cons="select noliquidacion,ambito,numservicio,(primnom  || segnom || primape || segape) as pagador,total,estado,nofactura,fechacrea,fechaini,fechafin
	from facturacion.liquidacion,central.terceros 
	where liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]' and cedula='$Paciente[1]' and terceros.identificacion=pagador
	order by noliquidacion,fechacrea";		
	$res=ExQuery($cons);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<?	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">
    	<td colspan="15"><input type="button" value="Nuevo" onClick="location.href='SelectPeriodoLiq.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>No Liquidacion</td><td>Fecha</td><td>Periodo</td><td>Proceso</td><td>No Servicio</td><td>Pagador</td><td>Valor</td><td colspan="2"></td>
    </tr>
<?	while($fila=ExFetch($res)){
		if($fila[5]=='AC'){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        		<td align="center" title="Ver" style="cursor:hand"
                	onclick="open('/Facturacion/VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila[0]?>&Ced=<? echo $Paciente[1]?>&FechaIni=<? echo $fila[8]?>&FechaFin=<? echo $fila[9]?>','','width=800,height=600,scrollbars=YES')"
                ><? echo $fila[0]?></td><td><? echo $fila[7]?></td><td><? echo "<strong>Desde:</strong> $fila[8] <strong>Hasta: $fila[9]</strong>";?></td>
                <td align="center"><? echo $fila[1]?></td><td align="center"><? echo $fila[2]?></td><td align="center"><? echo $fila[3]?></td>
            	<td align="right"><? echo number_format($fila[4],2)?></td>
           <? 	if($fila[6]==''){?>
		            <td>
    	            	<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='Liquidacion.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila[0]?>&NumServ=<? echo $fila[2]?>'" title="Editar">
        	        </td>	
	    			<td><img style="cursor:hand"  title="Eliminar" onClick="if(confirm('Desea eliminar este registro?')){location.href='VerLiquidaciones.php?DatNameSID=<? echo $DatNameSID?>&NoLiq=<? echo $fila[0]?>';}" 
    					src="/Imgs/b_drop.png"> 
		    		</td>
        	<?	}
				else{?>
                	<td>
    	            	<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="alert('Este registro no se puede editar debido a que ya ha sido facturado')" title="Editar">
        	        </td>	
	    			<td><img style="cursor:hand"  title="Eliminar" onClick="alert('Este registro no se puede editar debido a que ya ha sido facturado')" src="/Imgs/b_drop.png"> 
		    		</td>
         	<?	}?>
    	    </tr>
     <?	}
	 	else{?>
     		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="color:#FF0000; text-decoration:underline">
        		<td align="center"style="cursor:hand"
                	onclick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila[0]?>&Ced=<? echo $Paciente[1]?>&FechaIni=<? echo $fila[8]?>&FechaFin=<? echo $fila[9]?>','','width=1000,height=800,scrollbars=YES')"
                ><? echo $fila[0]?></td><td><? echo $fila[7]?></td><td><? echo "<strong>Desde:</strong> $fila[8] <strong>Hasta: $fila[9]</strong>";?></td>
                <td align="center"><? echo $fila[1]?></td><td align="center"><? echo $fila[2]?></td><td align="center"><? echo $fila[3]?></td>
            	<td align="right"><? echo number_format($fila[4],2)?></td>
	            <td>
                	<img src="/Imgs/b_edit_gray.png" style="cursor:hand">
                </td>	
	    		<td><img style="cursor:hand" src="/Imgs/b_drop_gray.png"> 
	    		</td>
    	    </tr>
<?		}
	}?>    
    
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>    
</body>
</html>

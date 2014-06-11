<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
?>
<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<? 
if($FechaIni&&$FechaFin){
	if($Entidad){$Ent="and pagador='$Entidad'"; }	
	if($Contrato){$Contra="and contrato='$Contrato'";}
	if($NoContrato){$NoContra="and nocontrato='$NoContrato'";}
	if($Ambito){$Amb="and liquidacion.ambito='$Ambito'";}	
	if($CedPac){$CedP="and cedula='$CedPac'";}
	//echo $cons;				
	$cons5="select grupo,almacenppal from consumo.grupos where compania='$Compania[0]' and anio='$ND[year]'";
	$res5=ExQuery($cons5);
	//echo $cons5;        
    if(ExNumRows($res5)>0){?>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
   			<td>Grupo</td><td>Valor</td>
		</tr><?
		while($fila5=ExFetch($res5))
		{						
	    	$cons="select grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,liquidacion.noliquidacion,valordescuento,valorcopago,subtotal,total,almacenppal 
			from facturacion.detalleliquidacion,facturacion.liquidacion 
			where detalleliquidacion.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and liquidacion.noliquidacion=detalleliquidacion.noliquidacion 
			and liquidacion.fechacrea>='$FechaIni 00:00:00' and liquidacion.fechacrea<='$FechaFin 23:59:59' and nofactura is null and estado='AC' $Ent
			$Contra $NoContra $Amb $CedP and tipo='Medicamentos' and grupo='$fila5[0]' and almacenppal='$fila5[1]'";			
			$res=ExQuery($cons); //echo $cons."<br>"; 
			$totalxgrupo=0;
			while($fila=ExFetch($res)){		
				$totalxgrupo=$totalxgrupo+$fila[6];
								
			}
			if($totalxgrupo>0){
				echo "<tr><td>$fila5[0]</td><td align='right'>".number_format($totalxgrupo,2)."</td></tr>";
				$total=$total+$totalxgrupo; 
			}			
		}		
		//cups
		
		$cons5="select grupo,codigo from contratacionsalud.gruposservicio where compania='$Compania[0]'";
		$res5=ExQuery($cons5);
		
		while($fila5=ExFetch($res5))
		{			
			$cons="select grupo,tipo,codigo,nombre,cantidad,vrunidad,vrtotal,liquidacion.noliquidacion,valordescuento,valorcopago,subtotal,total,almacenppal 
			from facturacion.detalleliquidacion,facturacion.liquidacion 
			where detalleliquidacion.compania='$Compania[0]' and liquidacion.compania='$Compania[0]' and liquidacion.noliquidacion=detalleliquidacion.noliquidacion 
			and liquidacion.fechacrea>='$FechaIni 00:00:00' and liquidacion.fechacrea<='$FechaFin 23:59:59' and nofactura is null and estado='AC' $Ent
			$Contra $NoContra $Amb $CedP and grupo='$fila5[1]'";
			$res=ExQuery($cons);
			$totalxgrupo=0;
			while($fila=ExFetch($res))
			{
				$totalxgrupo=$totalxgrupo+$fila[6];
			}			
			if($totalxgrupo>0){
				echo "<tr><td>$fila5[0]</td><td align='right'>".number_format($totalxgrupo,2)."</td></tr>";
				$total=$total+$totalxgrupo;					
			}			
		}			
		$cons="select sum(valordescuento),sum(valorcopago),sum(subtotal),sum(total)
		from facturacion.liquidacion where liquidacion.compania='$Compania[0]' and liquidacion.fechacrea>='$FechaIni 00:00:00' 
		and liquidacion.fechacrea<='$FechaFin 23:59:59' and nofactura is null and estado='AC' $Ent $Contra $NoContra $Amb $CedP";
		//echo $cons;
		$res=ExQuery($cons);	$fila=ExFetch($res);
		if($total!='')	{			
			echo "<tr><td><strong>SubTotal</strong></td><td align='right'>".number_format($fila[2],2)."</td></tr>";
			if($fila[1]!=''&&$fila[1]!="0"){
				echo "<tr><td><strong>Copago</strong></td><td align='right'>".number_format($fila[1],2)."</td></tr>";
			}
			if($fila[0]!=''&&$fila[0]!="0"){
				echo "<tr><td><strong>Descuento</strong></td><td align='right'>".number_format($fila[0],2)."</td></tr>";
			}				
			echo "<tr><td><strong>Total</strong></td><td align='right'>".number_format($fila[3],2)."</td></tr>";			
		}
		 $cons="select noliquidacion,cedula,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),subtotal,valordescuento,valorcopago,total 
		from facturacion.liquidacion,central.terceros
		where liquidacion.compania='$Compania[0]' and terceros.compania='$Compania[0]' and identificacion=cedula
		and liquidacion.fechacrea>='$FechaIni 00:00:00' and liquidacion.fechacrea<='$FechaFin 23:59:59' and nofactura is null and estado='AC' 
		$Ent $Contra $NoContra $Amb $CedP order by noliquidacion";
		//echo $cons;
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{?>
        	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan="11">LIQUIDACIONES</td></tr>
			<td colspan="11">		
				<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
                	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
                    	<TD>Liquidacion</TD><td>Paciente</td><td>Cedula</td><td>Subtotal</td><td>Vr Descuento</td><td>Vr Copago</td><td>Total</td>
                    </tr>
			<?	while($fila=Exfetch($res))
                {?>               
                   <tr>
                   		<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td>
                        <td><? echo $fila[4]?>&nbsp;</td><td><? echo $fila[5]?>&nbsp;</td><td><? echo $fila[6]?></td>
                   </tr>    
            <?	}?>
        		</table>
			</td>
	<?	}
	}
	else{?>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">   
    		<td>No hay registros que coincidad con los criterios de busqueda</td>
	    </tr>
<?	} 
}?>
	 
	
</table>   
</form>    
</body>
</html>

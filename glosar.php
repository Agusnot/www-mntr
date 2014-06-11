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
	if($Radicar){
		if($registra){
			/*while (list($nofac,$nocompc) = each ($Registra)) 
			{
				//echo "factura: ".$nofac." y nombre comprobante contable: ".$nocompc."</BR>";
				$cons="UPDATE facturacion.facturascredito SET fecharadic='$fecharadicacion',usuarioradic='$usuario[1]',
				numradicacion='$noradicacion',fecharasis='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where nofactura='$nofac' and compania='$Compania[0]' ";
				$res=ExQuery($cons);
				
				$cons2="SELECT compcontable,nocompcontable, fecharadic FROM facturacion.facturascredito WHERE nofactura='$nofac'";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				
				$cons3="INSERT INTO facturacion.historialcuenta (nofactura,cuenta,fechainicial,fechafinal) VALUES ('$nofac',(SELECT cuentarad FROM contratacionsalud.contratos,contabilidad.movimiento WHERE entidad='$Entidad' and contrato='$Contrato' and contratos.numero='$NoContrato' and cuentacont=cuenta AND cuentarad!='' AND cuentarad between '1306000000' AND '1306999999' LIMIT 1),'$fila2[2]',null)";
				$res3=ExQuery($cons3);
				
				$cons4="UPDATE contabilidad.movimiento SET cuenta=(
							SELECT cuentarad FROM facturacion.facturascredito
							INNER JOIN contabilidad.movimiento ON contabilidad.movimiento.numero::bigint=facturacion.facturascredito.nofactura
							INNER JOIN contratacionsalud.contratos ON contratacionsalud.contratos.entidad=facturacion.facturascredito.entidad 
							WHERE cuentarad!='' AND facturacion.facturascredito.nofactura='$nofac'  LIMIT 1) 
						WHERE cuenta=(
								SELECT cuenta FROM facturacion.facturascredito
								INNER JOIN contabilidad.movimiento ON contabilidad.movimiento.numero::bigint=facturacion.facturascredito.nofactura
								INNER JOIN contratacionsalud.contratos ON contratacionsalud.contratos.entidad=facturacion.facturascredito.entidad
								AND contratacionsalud.contratos.cuentacont=contabilidad.movimiento.cuenta  
								WHERE cuentarad!='' AND facturacion.facturascredito.nofactura='$nofac' LIMIT 1) and
						numero='$nofac' and comprobante='Venta de servicios'";
				$res4=ExQuery($cons4);
																
				
			}*/
		}
		else{ ?><font color="#FF0000"><em>No ha seleccionado ninguna solicitud</em></font><? }	
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.fecharadicacion.value==""){alert("Debe seleccionar la fecha de Radicacion!!");return false}
		if(document.FORMA.noradicacion.value==""){alert("Debe digitar el numero de Radicacion!!");return false}
		if(document.FORMA.registra.checked==true){alert("ooooooo");return false}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<input type = "hidden" name = 'formularioRad' value = "1"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>


<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="0" align="center">
<?	if($FechaIni&&$FechaFin){

		if($Desde){$Ini=" and nofactura>=$Desde";}
		if($Hasta){$Fin=" and nofactura<=$Hasta";}
		//echo $usuario[1];
		/*echo $cons="select nofactura,subtotal,copago,descuento,total,fechaglosa,entidad,estado,individual,usucrea,fechacrea,fecharadic,nocompcontable from facturacion.facturascredito
		where compania='$Compania[0]'  and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and estado='AC'
		and fecharadic is not null and compcontable='Venta de servicios' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato' $Ini $Fin order by nofactura";*/
		$cons="SELECT nofactura,fechacrea,(primape || segape || primnom || segnom) AS noment,total,fecharadic,SUM(facturacion.motivoglosa.vrglosa) AS GLOSS_VAL,
		facturacion.facturascredito.fechaglosa,facturacion.facturascredito.vrglosa,facturacion.facturascredito.motivoglosa, facturacion.respuestaglosa.numero,facturacion.respuestaglosa.vrglosatotal,facturacion.respuestaglosa.fecharasis,facturacion.respuestaglosa.fechanotificacion 
		FROM central.terceros, facturacion.facturascredito
		LEFT JOIN facturacion.respuestaglosa ON nofactura=nufactura
		LEFT JOIN facturacion.motivoglosa ON nofactura=facturacion.motivoglosa.nufactura
		WHERE facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin  23:59:59' AND terceros.compania='$Compania[0]' and fecharadic IS NOT NULL AND terceros.identificacion=facturascredito.entidad  and compcontable='Venta de servicios' and entidad='$Entidad' and contrato='$Contrato' $Ini $Fin GROUP BY nofactura,fechacrea,noment,total,fecharadic,
		facturacion.facturascredito.fechaglosa,facturacion.facturascredito.vrglosa,facturacion.facturascredito.motivoglosa, 		facturacion.respuestaglosa.numero,facturacion.respuestaglosa.vrglosatotal,facturacion.respuestaglosa.fecharasis,facturacion.respuestaglosa.fechanotificacion 
		ORDER BY nofactura";
		$res=ExQuery($cons);
		
		
		
		//echo $cons;
		if(!$fecharadicacion){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$fecharadicacion="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		if(ExNumRows($res)>0){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    			
				
				 <? //if($Tipo=="Sin Radicar"){?>
	                    <td align="center"><input type="submit" value="glosar" name="glosar"></td>
	    			
				<? //	}
                
					?>
				<td></td>
				<td>No Radicación<input type="text" value="<? echo $noradicacion?>" name="noradicacion"></td>
				<td>Fecha Radicación
				<input type="text" readonly name="fecharadicacion" onClick="popUpCalendar(this, FORMA.fecharadicacion, 'yyyy-mm-dd')" style="width:90px" value="<?echo $fecharadicacion?>"></td>  
			</tr>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    			<td>#</td><td>No. Factura</td><td>Entidad</td><td>Fecha Crea</td><td>Fecha Radicación</td><td>Valor Factura</td><td>Valor Glosa</td><td>Fecha</td><td>Número de Documento</td><td>Fecha Notificación Glosa</td><td>GLOSAR</td>
			</tr>
			
			
<?			$contador=0;
			while($fila=ExFetch($res))
			{	
				$contador=$contador+1;
				$cons2="SELECT numero,fecharasis,fechanotificacion FROM facturacion.respuestaglosa WHERE compania='$Compania[0]' AND nufactura='$fila[0]'";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				?>
					<td align="center"><? echo $contador?></td>
					<td align="center"><? echo $fila[0];  ?></td>					
                    <td align="center"><? echo $fila[2];//$Aseguradoras[$fila[6]]; ?></td>
					<td align="center"><? echo $fila[1]?></td>
					<td align="center"><? echo $fila[4]?></td>
                    <td align="right" width="15%"><? echo number_format($fila[3],2)?></td>
					<td align="right" width="15%"><? echo number_format($fila[5],2)?></td>
					<td align="center"><? echo $fila2[1]?></td>
					<td align="center"><? echo $fila2[0]?></td>
					<td align="center"><? echo $fila2[2]?></td>
                <? 	if($fila[6]!='' || $fila[4]!=''){?>
	                    <td align="center">
							<input type="radio" name="registra" value="<? echo $fila[0]?>" />
	    			
               <? 	}                   	
					?>
						</td>
                </tr>                
		<?	}?>

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





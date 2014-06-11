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
		if($Registra){
			while (list($nofac,$nocompc) = each ($Registra)) 
			{
				//echo "factura: ".$nofac." y nombre comprobante contable: ".$nocompc."</BR>";
				$cons="UPDATE facturacion.facturascredito SET fecharadic='$fecradicacion',usuarioradic='$usuario[1]',
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
																
				
			}
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
		if(document.FORMA.FechaRad.value==""){alert("Debe seleccionar la fecha de Radicacion!!");return false}
		if(document.FORMA.NoRadicacion.value==""){alert("Debe digitar el numero de Radicacion!!");return false}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<input type = "hidden" name = 'formularioRad' value = "1"/>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>


<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="0" align="center">
<?	if($FechaIni&&$FechaFin){
		//if($Entidad){$Ent="and entidad='$Entidad'";}
		//if($Contrato){$Contra="and contrato='$Contrato'";}
		//if($NoContrato){$NoContra="and nocontrato='$NoContrato'";}
		//if($NoContrato==0){$NoContra="and nocontrato=''";}
		if($Tipo=="Sin Radicar"){$OpcTipo="and fecharadic IS NULL";}
		elseif($Tipo=="Radicado"){$OpcTipo="and fecharadic IS NOT NULL";}
		//echo $usuario[1];
		echo $cons="select nofactura,subtotal,copago,descuento,total,fechaglosa,entidad,estado,individual,usucrea,fechacrea,fecharadic,nocompcontable from facturacion.facturascredito 
		where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and estado='AC'
		and compcontable='Venta de servicios' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$NoContrato' $OpcTipo order by nofactura";		
		$res=ExQuery($cons);		
		//echo $cons;
		if(ExNumRows($res)>0){?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    			
				
				 <? if($Tipo=="Sin Radicar"){?>
	                    <td align="center"><input type="submit" value="Radicar" name="Radicar"></td>
	    			
				<? 	}
                   	
					?>
				<td></td>
				<td>No Radicación<input type="text" value="<? echo $noradicacion?>" name="NoRadicacion"></td>
				<td>Fecha Radicación
				<input type="text" readonly name="FechaRad" onClick="popUpCalendar(this, FORMA.FechaRad, 'yyyy-mm-dd')" style="width:90px" value="<? echo $fecradicacion?>"></td>  
			</tr>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">    	
    			<td>#</td><td>No. Factura</td><td>Entidad</td><td>Fecha Crea</td><td>Total</td><td>RADICAR</td>
			</tr>
			
			
<?			$contador=0;
			while($fila=ExFetch($res))
			{	
				$contador=$contador+1;
				if($fila[2]==''){$fila[2]="0";}
				if($fila[3]==''){$fila[3]="0";}?>
					<td align="center"><? echo $contador?></td>
					<td align="center"><? echo $fila[0]; $vector["factura"][$contador] = $fila[0]; ?></td>					
                    <td align="center"><? echo $Aseguradoras[$fila[6]]; $vector["aseguradora"][$contador] = $Aseguradoras[$fila[6]]; ?></td>
					<td align="center"><? echo $fila[10]?></td>
                    <td align="right" width="15%"><? echo number_format($fila[4],2)?></td>
                <? 	if($fila[5]=='' && $fila[11]==''){?>
	                    <td align="center">
							<input type="checkbox" name="Registra[<? echo $fila[0]?>]" value="<? echo $fila[12]?>" />
	    			
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





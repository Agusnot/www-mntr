<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function ChequearTodos(chkbox) 
	{ 
		for (var i=0;i < document.forms["FORMA"].elements.length;i++) 
		{ 
			var elemento = document.forms[0].elements[i]; 
			if (elemento.type == "checkbox") 
			{ 
				elemento.checked = chkbox.checked 
				elemento.checked.disabled = false;
			} 
		} 
	}
	
</script>	
<?
if($Guardar){
$cons="Update facturacion.respuestaglosa 
Set fecharasis='$FechaGlosa',usuarioglosa='$usuario[1]',nufactura='$NoFac',
pagaipsglosa=$faltante where nufactura='$NoFac'";
		$res=ExQuery($cons);
		echo ExError($res);
		}
?>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<?
if($Ver){
	if($FacI){$FacIni="and nofactura>=$FacI";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
	if($Entidad){$Ent="and entidad='$Entidad'";} 
	if($Contrato){$Contr="and contrato='$Contrato'"; }
	if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa 
	from facturacion.facturascredito,central.terceros 
	where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and fecharadic is not null 
	and terceros.identificacion=facturascredito.entidad $FacIni $FacFin $Ent $Contr $NoContr order by nofactura";
	$res=ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0){?>
	<table   style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">    	
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">			
        	<td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicacion</td><td>Entidad</td><td>Vr Factura</td><td>Vr Glosado</td>
            <td>valor paga IPS</td> <td>Valor aceptado</td><td>vr pagar ep Segun Ips</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                	            	<td align="center" style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
						<? echo $fila[0]?>
                  	</td>
                    <td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
                    <td align="right"><? echo number_format($fila[3],2)?></td>
                    <td align="right">
                    <font style="font-size:11px">
                    <?
					$numf= $fila[0];				     
					$con4= "SELECT vrglosatotal,pagaipsglosa,aceptaglosa,pagarips FROM facturacion.respuestaglosa where nufactura='$numf'";					
					$res4= ExQuery($con4);
					while($fil=ExFetch($res4))
					{ echo number_format($fil[0],2); ?>	                     
                    </font>
                    </td>
                    <td>
                  <? echo number_format($fil[1],2)?></td>                    
                    <td><? echo number_format($fil[2],2)?></td>
                    <td><? echo number_format($fil[3],2)?></td>                   
                  <? } ?> </tr>
        <?	}?>
      	</tr>
	</table>
    <br></br>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
    <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    <td width="74">Tipo Glosa</td>
    <td width="84">Clase Glosa</td>
    <td width="180">Observacion</td>
    <td width="104">Valor Glosa</td>
   <td width="90">Fecha Glosa</td>
    <td width="151">valor A pagar IPS</td>
    <td width="113">Valor Aceptado</td>
    <td width="20"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
    </tr>
    <?
	$conexion="SELECT tipoglosa,claseglosa,observacionglosa,vrglosa,fecharasis,pagaipsglosa,aceptaglosa FROM facturacion.motivoglosa WHERE compania='$Compania[0]' ";
	$respuesta=ExQuery($conexion);
	while($fila=ExFetch($respuesta))
	{?>
     <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
     <td><? echo $fila[0]?></td>
     <td><? echo $fila[1]?></td>
     <td><? echo $fila[2]?></td>
     <td><? echo $fila[3]?></td>
     <td><? echo $fila[4]?></td>
     <td><? echo $fila[5]?></td>
      <td><? echo $fila[6]?></td>
     <td><input type="checkbox" name="Glosar[<? echo $fila[0]?>]" id="Fac<? echo $fila[0]?>" ></td>
     </tr>    
<?	}?> 
 <tr align="center">
     <td colspan="8"> <button type="submit" name="Guardar">Guardar</button></td>
     </tr>   
    </table>    
<? } else{?>
    	<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table>
<?	} } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>

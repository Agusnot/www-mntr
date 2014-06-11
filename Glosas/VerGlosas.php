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
			} 
		} 
	}
	function RealizarGlosa(e,NoFac,VrFac)
	{	
		y = e.clientY; 
		x = e.clientX;
		st = document.body.scrollTop;		
		if(document.getElementById('Fac'+NoFac).checked==true){
			for (i=0;i<document.FORMA.elements.length;i++){
				if(document.FORMA.elements[i].type == "checkbox"){				
					document.FORMA.elements[i].disabled = true;  } 
			                                                   }
			frames.FrameOpener.location.href="Glosar.php?DatNameSID=<? echo $DatNameSID?>&NoFac="+NoFac+"&VrFac="+VrFac;
			document.getElementById('FrameOpener').style.position='absolute';			
			document.getElementById('FrameOpener').style.top=(y)+st;			
			document.getElementById('FrameOpener').style.left=x;
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='990px';
			document.getElementById('FrameOpener').style.height='400px';		
		}
	}
</script>	

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
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa ,contrato
	from facturacion.facturascredito,central.terceros 
	where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59'
    and terceros.compania='$Compania[0]'  and fecharadic is not null 
	and terceros.identificacion=facturascredito.entidad $FacIni $FacFin $Ent $Contr $NoContr order by nofactura";
	$res=ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0){?>
	<table width="1020"  border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"   style='font : normal normal small-caps 10px Tahoma;'>  
    <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        	<td width="20"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
			
  <td width="75">No Factura</td><td width="96">Fecha Factura</td><td width="119">Fecha Radicacion</td><td width="66">Entidad</td>
  <td>Contrato</td><td width="114">Valor Factura</td><td width="85">Valor Glosa</td><td width="94">Fecha </td><td width="128">Numero De Documento</td><td width="139">Fecha Notificacion Glosa</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                	<td>   					              
                    <input type="checkbox" name="Glosar[<? echo $fila[0]?>]" id="Fac<? echo $fila[0]?>"  
				<?	
					
					$cons55="select * FROM facturacion.respuestaglosa where estado='AN' AND nufactura='$fila[0]' ";
					$res55=ExQuery($cons55);
			 while($ro=ExFetch($res55))
			 {?> disabled  <? }				
			 $consll="select nufactura FROM facturacion.respuestaglosa
			  where compania='$Compania[0]' AND nufactura='$fila[0]' ";
			 $respp=ExQuery($consll);
			 while($roll=ExFetch($respp))
			 {?> checked  <? }					                       
						 if($fila[6]){
						?> checked title="Eliminar Glosa" disabled<? }
						else{?>title="Glosar"<? }?> value="<? echo $fila[0]?>"
                        onClick="RealizarGlosa(event,'<? echo $fila[0]?>','<? echo $fila[3]?>') ">                     
                  	</td>
 <td align="center" style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')">
						<? echo $fila[0]?>
                  	</td>
                    <td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
					<td align="center"><? echo $fila[8]?></td>
                    <td align="right"><? echo number_format($fila[3],2)?></td>
                    <td align="center">
                    <font style="font-size:12px">
 <?					
$numf= $fila[0];				     
$con4= "SELECT numero,vrglosatotal,fecharasis,fechanotificacion  FROM facturacion.respuestaglosa where nufactura='$numf'";					
$res4= ExQuery($con4);
while($fil=ExFetch($res4)){				
echo number_format($fil[1],2); 	?>      
</font>                    
                    </td>  
                    <td align="center"> <font style="font-size:12px"><? echo $fil[2] ?></b></font> </td>
                    <td align="center"><? echo $fil[0] ?></td>
                    <td align="center"><? echo $fil[3];}?></td>              
                </tr> <? }?></tr></table><?	} else{?>
  <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table><? }} ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe> 
</body>
</html>

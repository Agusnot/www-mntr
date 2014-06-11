<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($FechaRad==''){
		if($ND[mon]<10){$Cero1="0";}else{$Cero1="";}
		if($ND[mday]<10){$Cero2="0";}else{$Cero2="";}
		$FechaRad="$ND[year]-$Cero1$ND[mon]-$Cero2$ND[mday]";
	}	
	if($Guardar==1){
		if($SinRad!=NULL){
			while( list($cad,$val) = each($SinRad))
			{
				if($cad && $val)
				{	
				$tiempo = strftime("%Y-%m-%d %H:%M:%S",time());				
					$cons="update facturacion.facturascredito set fecharadic='$FechaRad',usuarioradic='$usuario[1]', numradicacion='$Numradi',fecharasis='$tiempo' where nofactura=$cad and compania='$Compania[0]' ";			
					$res = ExQuery($cons);	echo ExError();							
					//echo $cons;
				}
			}
		}	
		if($MostrarB=="Radicado"){
			if($Rad!=NULL){
				$Lista="";
				$Ban=0;
				while( list($cad,$val) = each($Rad))
				{
					if($cad && $val)
					{	
						if($Ban==1){
							$Lista=$Lista.",";
						}								
						
						else{$Ban++;}
						$Lista=$Lista."$cad";									
					}
				}
				$CondicionList="and nofactura in (".$Lista.")";
			}	
			$cons="update facturacion.facturascredito set fecharadic=null,usuarioradic='$usuario[1]' 
			where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' 
			$CondicionList ";
			//echo $cons;
			$res=ExQuery($cons);
			
			if($Modifica){	
		$cons="update facturacion.facturascredito set fecharadic=null,usuarioradic='$usuario[1]' 
			where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' 
			 $CondicionList AND nofactura='$NocFact' ";	
			echo $cons;		
			$res=ExQuery($cons);
	}
		}	
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
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
	function GuardarRad(){
		if(document.FORMA.FechaRad!=null){
			if(document.FORMA.FechaRad.value>document.FORMA.FechaAtc.value){
				alert("La fecha de Radicacion no puede ser mayor a la actual!!!");
			}
			else{
				document.FORMA.Guardar.value=1;
				document.FORMA.submit();
			}
		}
		else{
			document.FORMA.Guardar.value=1;
			document.FORMA.submit();
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<?
if($Ver){	
if($Mostrar=="Radicado"){$Rad="and fecharadic is not null";}
else{$Rad="and fecharadic is null";}
if($Entidad){$Ent="and entidad='$Entidad'";} 
if($Contrato){$Contr="and contrato='$Contrato'"; }
if($NoContrato){$NoContr="and nocontrato='$NoContrato'"; }
$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa ,numradicacion,fecharadic,contrato
from facturacion.facturascredito,central.terceros 
where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59'  and terceros.compania='$Compania[0]' and 		
terceros.identificacion=facturascredito.entidad and  facturascredito.estado='AC' $Rad $Ent $Contr $NoContr 
order by entidad ASC, nofactura ASC";
$res=ExQuery($cons); echo ExError();
	//echo $cons;
	if(ExNumRows($res)>0){?>
    <input type="hidden" name="EntidadB" value="<? echo $Entidad?>">
    <input type="hidden" name="ContratoB" value="<? echo $Contrato?>">
    <input type="hidden" name="NoContratoB" value="<? echo $NoContrato?>">
    <input type="hidden" name="MostrarB" value="<? echo $Mostrar?>">
	<? 	if($ND[mon]<10){$Cero1="0";}else{$Cero1="";}
		if($ND[mday]<10){$Cero2="0";}else{$Cero2="";}?>
    <input type="hidden" name="FechaAtc" value="<? echo "$ND[year]-$Cero1$ND[mon]-$Cero2$ND[mday]";?>">
	<table style='font : normal normal small-caps 11px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<?	if($Mostrar!="Radicado"){?>
		<tr align="center">	
		<td colspan="13">
			<strong>N° Radicacion </strong>
	
<?	
$consul1= "select numradicacion from facturacion.facturascredito where Compania='$Compania[0]' and numradicacion is not null Order By numradicacion DESC limit 1";	
$res1=ExQuery($consul1);
$dato=ExFetch($res1);
$result[0]=$dato[0]+1;		
echo"<input type='text'  name='Numradi'  style='width:65px' value='$result[0]'>";	
?>   
<strong>Fecha Radicacion  </strong>
<input type="text" readonly name="FechaRad" onClick="popUpCalendar(this, FORMA.FechaRad, 'yyyy-mm-dd')" style="width:90px" value="<? echo $FechaRad?>"></td> 
	</tr>   
<?	}?>        
        <tr align="center">
        	<td colspan="10"><input type="button" value="Guardar" onClick="GuardarRad()"></td>
        </tr> 
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
            <td><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
            <td>Fecha Factura</td>
			<td >No Factura</td>
			<td>Entidad</td>
			<td>Contracto</td>
			<td>Vr Factura</td>
			
			
			
			<?	if($Mostrar=="Radicado"){?><td>Numero Radicacion</td><td>Fecha Radicado</td><td>Diferecias de Dias</td><? }?>
        </tr>    
   	<?	while($fila=ExFetch($res)){?>
    		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            <?	if($fila[5]!=''){
					echo "<td align='center'>GL</td>";
				}
				else{				
					if($Mostrar=="Radicado"){?>
        	    		<td><input type="checkbox" name="Rad[<? echo $fila[0]?>]" title="Elimanar Radiacacion" checked value="<? echo $fila[0]?>"></td>
				<?	}
					else{?>
	            		<td lign='center'><input type="checkbox" name="SinRad[<? echo $fila[0]?>]" title="Radiacar"></td>
            <? 		}
				}
				$FecFac=explode(" ",$fila[1]);
				echo "<td align='center'>$FecFac[0]</td>";?>
				<td align='center' style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')"><? echo $fila[0]?>                </td>
            <?	echo "<td align='center'>$fila[2]</td>
			<td lign='center'> $fila[8]</td>
			
<td  align='right'>".number_format($fila[3],2); 
if($Mostrar=="Radicado"){
echo "</td>
<td lign='center'>$fila[6]</td>
<td align='center'>$fila[4]</td>";					   
$inicio = strtotime($FecFac[0]);    
$fin = strtotime($fila[4]);    
$dif = $fin - $inicio;    
$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);   

echo "<td align='center'>$diasFalt";	
?>
<td>


</td>
<?														
}echo "</td>";?>
</tr>
<?	} if(ExNumRows($res)>9){?>
<tr align="center">
<td colspan="8"></td>
</tr> 
<?	}?>
</table>
<?	}
else{?>
<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr align="center">	
<td colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">No Hay Facturas  <?	if($Mostrar=="Radicado"){?>Radicadas<? }else{?>Sin Radicar<? }?> Durante Este Periodo</td>
</tr>
</table>
<?	}
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Guardar" value="">
</form>    
</body>
</html>

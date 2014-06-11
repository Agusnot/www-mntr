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
							
$cons="update facturacion.respuestaglosa set fecharespuesta='$FechaRad',usuariorecepcion ='$usuario[1]',
 numrecepcionrespuesta='$Numradi' where  nufactura=$cad and compania='$Compania[0]' ";			
					$res = ExQuery($cons);	
					?>
						<script language="JavaScript">
						 alert ("informacion Registrada correptamente!!");
					   </script>  
					<?
					echo ExError();							
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
			$cons="update facturacion.respuestaglosa set fecharespuesta=null,usuariorecepcion ='$usuario[1]' 
			where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' 
			$CondicionList ";
			
					?>
						<script language="JavaScript">
						 alert ("informacion Registrada correptamente!!");
					   </script>  
					<?
			$res=ExQuery($cons);
		}	
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="30">
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Validar()
{ 

if(document.FORMA.Numradi.value=="")
              {
			  alert("Debe Ingresar el Numero de las glosas");
			  document.FORMA.Numradi.focus(); return false;
			  } 

}



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

$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa , numrecepcionrespuesta,contrato
from facturacion.facturascredito,central.terceros ,facturacion.respuestaglosa 
where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59'  and terceros.compania='$Compania[0]' and  respuestaglosa.nufactura=facturascredito.nofactura and		
	terceros.identificacion=facturascredito.entidad and  facturascredito.estado='AC' order by entidad ASC, nofactura ASC";
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
	<table style='font : normal normal small-caps 10px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<?	if($Mostrar!="Radicado"){?>
		<tr align="center">	
		<td colspan="2"></td>
		<td colspan="2"  bgcolor="#e5e5e5" style="font-weight:bold">
			<strong>Numero de Radicado </strong>	
	      </td> 
		  <td><strong>		
		<input type='text'  name='Numradi'  style='width:85px' value="" >
	</strong></td>
        	<td colspan="2"  bgcolor="#e5e5e5" style="font-weight:bold"><strong>Fecha de Radicado </strong>        	
            </td> 
			<td colspan="5" align="left">
		<input type="text" readonly name="FechaRad" onClick="popUpCalendar(this, FORMA.FechaRad, 'yyyy-mm-dd')" style="width:150px">			
			</td>        
		</tr>   
<?	}?>        
        <tr align="center">
        	<td colspan="13"><input type="button" value="Guardar" onClick="GuardarRad()"></td>
        </tr> 
        <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
          <td width="20"><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
            <td width="89">Fecha Factura</td>
            <td width="63" >No Factura</td>
            <td width="54">Entidad</td>
			<td>Contrato</td>
            <td width="69">Vr Factura</td>
            <?	if($Mostrar=="Radicado"){?><td width="94">Fecha Radicado</td>
            <td width="88">Diferecia Dias</td>
            <? }?><td width="71">Valor Glosa</td>
            <td width="112">Valor Aceptado IPS</td> 
            <td width="168">Valor Objetado No aceptado IPS</td>
            <td width="102">Valor a Pagar EPS</td>
			<td>Numero de Radicado</td>
			<td>Fecha de Radicado</td>
        </tr>    
   	<?	while($fila=ExFetch($res)){?>
	
    		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
            <?	if($fila[5]!=''){
					echo "<td align='center'>GL</td>";
					
				}
				else{				
					if($fila[6]!=''){?>
        	    		<td>
						
<input type="checkbox" name="Rad[<? echo $fila[0]?>]" title="Elimanar Radiacacion" checked value="<? echo $fila[0]?>"

<?
$cons55="select * FROM facturacion.respuestaglosa where estado='AN' AND nufactura='$fila[0]' ";
$res55=ExQuery($cons55);
while($ro=ExFetch($res55))
{?> disabled  <? }	?>
></td>
<?	}
else{?>
<td><input type="checkbox" name="SinRad[<? echo $fila[0]?>]" title="Radiacar">
</td>
<? 		}
}
$FecFac=explode(" ",$fila[1]);
echo "<td align='center'>$FecFac[0]</td>";?>
<td align='center' style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')"><? echo $fila[0]?>

</td>
<?	echo "<td align='center'>$fila[2]</td>
<td>$fila[7]</td>
<td  align='right'>".number_format($fila[3],2); 
if($Mostrar=="Radicado"){
echo "</td><td align='center'>$fila[4]</td>";
$inicio = strtotime($FecFac[0]);    
$fin = strtotime($fila[4]);    
$dif = $fin - $inicio;    
$diasFalt = (( ( $dif / 60 ) / 60 ) / 24);    
echo "<td align='center'>$diasFalt";										
}echo "</td>";?>
<td align="right">
<font style="font-size:11px">
<?	$numf= $fila[0];				     
$con4= "SELECT vrglosatotal,pagaipsglosa,aceptaglosa,pagarips,numrecepcionrespuesta,fecharespuesta FROM facturacion.respuestaglosa where nufactura='$numf'";					
$res4= ExQuery($con4);
while($fil=ExFetch($res4))
{ echo number_format($fil[0],2); ?>                    
</font>
</td> <td align="right"><? echo number_format($fil[2],2)?></td>                   
<td align="right"><? echo number_format($fil[1],2)?></td>
<td align="right"><? echo number_format($fil[3],2)?></td>
<td align="right"><? echo $fil[4]?></td>
<td align="center"><? echo $fil[5]?></td>
<? }?>  
</tr>
<?	} if(ExNumRows($res)>9){?>
<tr align="center">
<td colspan="8"><input type="button" value="Guardar" onClick="GuardarRad()"></td>
</tr> 
<?	}?>
</table>
<?	}
else{?>
<table  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">  
<tr align="center">	
<td colspan="4" bgcolor="#e5e5e5" style="font-weight:bold">No Hay Facturas  <?	if($Mostrar=="Radicado"){?>Radicadas<? }else{?>Sin Radicar<? }?> Durante Este Periodo</td>
</tr>
</table>
<? } }?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Guardar" value="">
</form>    
</body>
</html>

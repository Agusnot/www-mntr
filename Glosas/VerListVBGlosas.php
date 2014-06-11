<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		if($VoBoGlod!=NULL){
			while( list($cad,$val) = each($VoBoGlod))
			{
				if($cad && $val)
				{				
					$cons="update facturacion.facturascredito set fechavoboglosa='$ND[year]-$ND[mon]-$ND[mday]',usuariovoboglosa='$usuario[1]' 
					where nofactura=$cad and compania='$Compania[0]'";			
					$res = ExQuery($cons);	echo ExError();							
					//echo $cons;
				}
			}
		}	
	}
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
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($Ver){
	if($Entidad){$Ent="and entidad='$Entidad'";}
	if($Contrato){$Cont="and contrato='$Contrato'";}
	if($NoContrato){$NoCont="and nocontrato='$NoContrato' ";}
	if($FacI){$FacIni="and nofactura>=$FacI";}
	if($FacF){$FacFin="and nofactura<=$FacF";}
	
	$cons="select nofactura,fechacrea,(primape || segape || primnom || segnom) as noment,total,fecharadic,fechaglosa,vrglosa,motivoglosa 
	from facturacion.facturascredito,central.terceros 
	where facturascredito.compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' and terceros.compania='$Compania[0]' and terceros.identificacion=facturascredito.entidad and fechaglosa is not null and fechavoboglosa is null $Ent $Cont $NoCont $FacIni $FacFin order by nofactura";
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
    	<tr align="center">
        	<td colspan="8"><input type="submit" value="Guardar" name="Guardar"></td>
        </tr> 
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        	<td>No Factura</td><td>Fecha Factura</td><td>Fecha Radicacion</td><td>Entidad</td><td>Vr Factura</td><td>Vr Glosa</td><td>Nota Glosa</td>
            <td><input type="checkbox" name="Todos" onClick="ChequearTodos(this);" title="Seleccionar Todos"></td>
		</tr>
    <?	while($fila=ExFetch($res)){
			$Fec=explode(" ",$fila[1]);?>
           	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> 
				<td align="center" style="cursor:hand" title="Ver" onClick="open('/Facturacion/IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $fila[0]?>&Estado=<? echo "AC"?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES')"><? echo $fila[0]?></td>
                <td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
    	        <td align="right"><? echo number_format($fila[3],2)?></td><td align="right">&nbsp;<? if($fila[6]) {echo number_format($fila[6],2);}?></td>
        	    <td>&nbsp;<font style="font-size:9px"><? echo "$fila[5]"?></font> <? echo "<br>$fila[7]"?></td>
                <td><input type="checkbox" title="Dar VoBo" name="VoBoGlod[<? echo $fila[0]?>]" value="<? echo $fila[0]?>"/></td>
         	</tr>
	<?	}
		if(ExNumRows($res)>9){?>
           	<tr align="center">
             	<td colspan="8"><input type="submit" value="Guardar" name="Guardar"></td>
          	</tr> 
     <?	}?>
	</table><?
	}
	else{?>
		<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Facturas que cumpan con los parametros de la busqueda</td></tr>
		</table><?
	}
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

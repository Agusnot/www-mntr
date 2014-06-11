<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
	if($Facturar){
			
		//----------------------------------------------------------Buscamos el numero de fatura consecutivo-----------------------------------------------------------------------------
		$cons="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc";
		$res=ExQuery($cons); $fila=ExFetch($res); 
		$AutoId=$fila[0]+1;//Numero de factura
		$AutoIdIni=$AutoId;	
		//----------------------------------------------------------Seleccionamos las liquidaciones q cumplan con los criterios de busqueda----------------------------------------------
		
		if($Ambito!=''){$Amb="and liquidacion.ambito='$Ambito'";}
		if($Entidad!=''){$Pag="and pagador='$Entidad'";}
		if($Contrato){$Contra="and contrato='$Contrato'";} 
		if($NoContrato){$NoContra="and nocontrato='$NoContrato'";}
		if($CedPac){$CedP="and cedula='$CedPac'";}
		
		
		$cons3="select pagador,contrato,nocontrato
		from facturacion.liquidacion where compania='$Compania[0]' $Pag  $Contra $NoContra $Amb $CedP and estado='AC' and nofactura is null
		and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' group by  pagador,contrato,nocontrato";			
		$res3=ExQuery($cons3); 
		
		while($fila3=ExFetch($res3)){	
			$cons="select tipofactura from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$fila3[0]' and  contrato='$fila3[1]' and  numero='$fila3[2]'";		
			//echo "$cons<br>\n";
			$res=ExQuery($cons); $fila=ExFetch($res); 
			$TipoFac=$fila[0];//Tipo de Factura					
			if($Unificar&&$CedPac){$TipoFac="Colectiva";}

			$cons="select noliquidacion,subtotal,valorcopago,valordescuento,total,ambito
			from facturacion.liquidacion where compania='$Compania[0]' and fechacrea>='$FechaIni 00:00:00' and fechacrea<='$FechaFin 23:59:59' 
			and pagador='$fila3[0]'  and contrato='$fila3[1]' and nocontrato='$fila3[2]'  $Amb $CedP and estado='AC' and nofactura is null";	
			$res=ExQuery($cons); 
			if($TipoFac=="Individual"){	
				while($fila=ExFetch($res)){
					if(!$fila[2]){$fila[2]="0";}	
					if($fila[3]==''){$fila[3]="0";}			
					$consF="insert into facturacion.facturascredito 
					(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,individual)
					values ('$Compania[0]','$FechaFin $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$FechaIni','$FechaFin','$fila3[0]','$fila3[1]','$fila3[2]',
					'$fila[5]',$fila[1],$fila[2],$fila[3],$fila[4],$AutoId,1)";
					//echo "<br>Individual $consF<br>\n";		
					$resF=ExQuery($consF);
					$consUp="update facturacion.liquidacion set nofactura=$AutoId where compania='$Compania[0]' and noliquidacion=$fila[0]";
					$resUp=ExQuery($consUp);
					//echo $consUp."<br>";
					$cons2="select codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,cum from  facturacion.detalleliquidacion 
					where compania='$Compania[0]' and noliquidacion='$fila[0]'";
					//echo $cons2."<br>";
					$res2=ExQuery($cons2);
					while($fila2=ExFetch($res2)){
						$consDF="insert into facturacion.detallefactura 
						(compania,usuario,fechacrea,codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,cum,nofactura)
						values ('$Compania[0]','$usuario[1]','$FechaFin
						$ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$fila2[1]','$fila2[2]','$fila2[3]',$fila2[4]
						,$fila2[5],$fila2[6],'$fila2[7]','$fila2[8]','$fila2[9]','$fila2[10]','$fila2[11]',$AutoId)";
						//echo "$consDF<br>\n";
						$resDF=ExQuery($consDF);
					}
					$AutoId++;
				}						
			}
			else{ 
				while($fila=ExFetch($res)){
					if($fila[3]==''){$fila[3]="0";}
					$STot=$STot+$fila[1];
					$Cop=$Cop+$fila[2];
					$Desc=$Desc+$fila[3];
					$Tot=$Tot+$fila[4];
					$consUp="update facturacion.liquidacion set nofactura=$AutoId where compania='$Compania[0]' and noliquidacion=$fila[0]";
					$resUp=ExQuery($consUp);
					$cons2="select codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,cum from  facturacion.detalleliquidacion 
					where compania='$Compania[0]' and noliquidacion='$fila[0]'";
					//echo $cons2;
					$res2=ExQuery($cons2);
					while($fila2=ExFetch($res2)){
						$consDF="insert into facturacion.detallefactura 
						(compania,usuario,fechacrea,codigo,grupo,tipo,nombre,cantidad,vrunidad,vrtotal,generico,presentacion,forma,almacenppal,cum,nofactura)
						values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$fila2[1]','$fila2[2]','$fila2[3]',$fila2[4]
						,$fila2[5],$fila2[6],'$fila2[7]','$fila2[8]','$fila2[9]','$fila2[10]','$fila2[11]',$AutoId)";
						//echo "<br>\n$consDF<br>\n";
						$resDF=ExQuery($consDF);
					}						
				}
				if($Desc==''){$Desc="0";}
				if($Cop==''){$Cop="0";}
				if($Unificar&&$CedPac){$Ind="1";}else{$Ind="0";}
				
				$consF="insert into facturacion.facturascredito 
				(compania,fechacrea,usucrea,fechaini,fechafin,entidad,contrato,nocontrato,ambito,subtotal,copago,descuento,total,nofactura,individual)
				values ('$Compania[0]','$FechaFin $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$FechaIni','$FechaFin','$fila3[0]','$fila3[1]','$fila3[2]',
				'$fila[5]',$STot,$Cop,$Desc,$Tot,$AutoId,$Ind)";
				//secho "Grupal $consF<br>\n";
				$resF=ExQuery($consF);
				$AutoId++;
			}
		}
		$AutoId--;
		if($AutoIdIni>$AutoId){$AutoId=$AutoIdIni+1;}
		//echo "AutoIdIni=$AutoIdIni AutoId=$AutoId";		
		?><script language="javascript">
		open('IntermedioFactura.php?DatNameSID=<? echo $DatNameSID?>&NoFac=<? echo $AutoIdIni?>&NoFacFin=<? echo $AutoId?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES');</script><?	
		
		//$Entidad=''; $FechaIni=''; $FechaFin=''; $Contra=''; $NoContra=''; $Ambito='';		
	}
?>
<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function Validar2()
	{
		if(document.FORMA.FechaIni.value==""){ 
			alert("Debe seleccionar la fecha de inicio!!!"); return false; 
		}		
		if(document.FORMA.FechaFin.value==""){ 
			alert("Debe seleccionar la fecha final!!!");  return false;
		}		
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
			alert("La fecha inicial debe ser menor o igual a la fecha final!!!");
		}
	}
	function Validar()
	{
		if(document.FORMA.FechaIni.value==""){ 
			alert("Debe seleccionar la fecha de inicio!!!"); 
		}
		else{
			if(document.FORMA.FechaFin.value==""){ 
				alert("Debe seleccionar la fecha final!!!"); 
			}
			else{	
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
					alert("La fecha inicial debe ser menor o igual a la fecha final!!!");
				}					
				else{				
					document.FORMA.Facturar.value=1;
					document.FORMA.submit();					
					
				}
			}
		}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar2()">   
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
	<tr align="center">
    	<td colspan="9" bgcolor="#e5e5e5" style="font-weight:bold">Facturar</td>        
  	</tr> 
	<tr>    
    	<?	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" ></td>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
   	 <?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion 
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="3">
        	<select name="Entidad" onChange="document.FORMA.submit()"><option></option>
      	<?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Entidad==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[1]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[1]?></option>
              <? }
			  }?>
             </select>
        </td>
         <td align="center" rowspan="2">
        	<input type="submit" value="Ver" name="Ver">
        </td>
  	</tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
  	<?	$cons="Select contrato from contratacionsalud.contratos where contratos.compania='$Compania[0]' and entidad='$Entidad'";
		//echo $cons;?>
        <td>
        	<select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($Contrato==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
              <? }
			  }?>
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">No Contrato</td>
  	<?	$cons="Select numero from contratacionsalud.contratos where contratos.compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato'";
		//echo $cons;?>
        <td>
        	<select name="NoContrato" onChange="document.FORMA.submit()"><option></option>
         <?	$res=ExQuery($cons);
			while($row = ExFetch($res))
			{
				if($NoContrato==$row[0])
				{ ?>				
                	<option value="<? echo $row[0]?>" selected><? echo $row[0]?></option>
             <? }
			 	else
				{
				?>
                	<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
              <? }
			  }?>
            </select>
        </td>   
    	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Ambito</td>    
		<td>
	         <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?	$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";	
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}?>
   			</select>
       	</td>
       	<td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Cedula</td>    
		<td>
        	<input type="text" name="CedPac" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)" value="<? echo $CedPac?>" style="width:90">
        </td>
	</tr>
    
    <tr><td colspan="11">Unifcar Liquidaciones <input type="checkbox" name="Unificar" <? if($Unificar){?> checked<? }?>></td></tr>
    <tr align="center">
    	<td colspan="9"><input type="button" value="Facturar" onClick="Validar();"></td>        
    </tr>
</table>
<input type="hidden" name="Facturar">
</form>    
</body>
<?
if($Ver){?>
<iframe frameborder="0" id="VerxGrupos" src="VerxGrupos.php?DatNameSID=<? echo $DatNameSID?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ambito=<? echo $Ambito?>&CedPac=<? echo $CedPac?>" width="100%" height="85%"></iframe>
<? 
}?>

</html>

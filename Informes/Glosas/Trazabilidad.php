<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Valida(){
		
	///-------------------validacion campos de entidad y contrato
	
	if(document.FORMA.Entidad.value==0){

                 alert("Por favor seleccion un tipo de Entidad");
                    return false;}
					
					
			if(document.FORMA.Contrato.value==0){
			alert("Por favor Seleccione un tipo de Contrato");
			return false;
			}		
			
			
	///fin de la validacion entidad y contracto-----------FacI
	
	
		if(document.FORMA.FechaIni.value==""){
			alert("Debe selecionar la fecha inicial!!!");
		}
		else{
			if(document.FORMA.FechaFin.value==""){
				alert("Debe selecionar la fecha final!!!");
			}
			else{
				if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
					alert("La fecha incial debe ser menor a la fecha final!!!");
				}
				else{
					
					if(document.FORMA.FacF.value!=""){
						if(document.FORMA.FacI.value==""){
							alert("Debe ditar el numero de factura inicial!!!");
						}
						else{//alert(document.FORMA.FacI.value);alert(document.FORMA.FacF.value);										
							if(parseInt(document.FORMA.FacI.value)>parseInt(document.FORMA.FacF.value)){
								alert("La factura incial debe ser menor a la factura final!!!");
							}
							else{ 								
								frames.ResTrazabilidad.location.href='ResTrazabilidad.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ver=1&FacI='+document.FORMA.FacI.value+'&FacF='+document.FORMA.FacF.value;
							}
						}
					}
					else{									
						if(document.FORMA.FacI.value!=""){
							if(document.FORMA.FacF.value!=""){																					
								if(document.FORMA.FacI.value>document.FORMA.FacF.value){	
									alert("La factura incial debe ser menor a la factura final!!!");
								}
								else{		
									frames.ResTrazabilidad.location.href='ResTrazabilidad.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ver=1&FacI='+document.FORMA.FacI.value+'&FacF='+document.FORMA.FacF.value;																					
								}
							}
							else{
								frames.ResTrazabilidad.location.href='ResTrazabilidad.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ver=1&FacI='+document.FORMA.FacI.value+'&FacF='+document.FORMA.FacF.value;																					
							}
						}
						else{
							frames.ResTrazabilidad.location.href='ResTrazabilidad.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ver=1&FacI='+document.FORMA.FacI.value+'&FacF='+document.FORMA.FacF.value;
						}										
					}
				}
			}
		}
	}
	
	function CopiarFac(){
		document.FORMA.FacF.value=document.FORMA.FacI.value;
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">	
        <td colspan="8" bgcolor="#e5e5e5" style="font-weight:bold">ESTADO DE CARTERA X EDADES INICIAL!!!</td>        
	</tr>
    <tr>	
    	<?
        if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}else{$C1="";}			
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}else{$C1="";}
			if($ND[mday]<10){$C2="0";}else{$C2="";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}
		?>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Inicio</td>
    	<td><input type="Text" name="FechaIni"  readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>" style="width:80px"></td>       
        
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>
        <td><input type="Text" name="FechaFin"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>" style="width:80px"></td>        
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad</td>
   	 <?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="3">
        	<select name="Entidad" onChange="document.FORMA.submit()" onKeyUp="Validar(this.value)"><option></option>
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
   	</tr> 
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
  	<?	$cons="Select contrato from contratacionsalud.contratos where contratos.compania='$Compania[0]' and entidad='$Entidad'";
		//echo $cons;?>
        <td>
        	<select name="Contrato" onChange="document.FORMA.submit()" onKeyUp="Validar(this.value)"><option></option>
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
        <td bgcolor="#e5e5e5" style="font-weight:bold">Facturas</td>
        <td><input type="text" name="FacI" style="width:80px" onKeyDown="xNumero(this)" onKeyUp="xNumero(this);CopiarFac()" onBlur="CopiarFac()" value="<? echo $FacI?>" ></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">A</td>
        <td><input type="text" name="FacF" style="width:80px" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $FacF?>"></td>
	</tr>        
    <tr><td align="center" colspan="9"><input type="button" value="Ver" onClick="Valida()"></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"> 
</form>
<iframe frameborder="0" id="ResTrazabilidad" src="ResTrazabilidad.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>" width="100%" height="85%"></iframe>    
</body>
</html>

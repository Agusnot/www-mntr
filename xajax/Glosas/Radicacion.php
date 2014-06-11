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
<script language="javascript">
function Validar(){

                 	if(document.FORMA.Entidad.value==0){

                 alert("Debe seleccionar una entidad!!!");
                    return false;}
					
					if(document.FORMA.Contrato.value==0){
					alert("Por favor debe seleccionar un contrato");
					return false;				}



	if(document.FORMA.FechaIni.value==""){
		alert("Debe seleccionar la fecha de inicio!!!");
	}
	else{
		if(document.FORMA.FechaFin.value==""){
			alert("Debe seleccionar la fecha de finalizacion!!!");
		}
		else{
			if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){
				alert("La fecha final debe ser mayor o igual a la incial!!!");
			}
			else{
				frames.VerRadicacion.location.href='VerRadicacion.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni='+document.FORMA.FechaIni.value+'&FechaFin='+document.FORMA.FechaFin.value+'&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Ver=1&Mostrar='+document.FORMA.Mostrar.value;			
			}
		}
	}	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
	<tr align="center">	
        <td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold">Radicacion</td>
        
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
    	<td><input type="Text" name="FechaIni"  onKeyUp="Validar(this.value)" readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>" style="width:80px" ></td>       
        
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>
        <td><input type="Text" name="FechaFin" onKeyUp="Validar(this.value)"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>" style="width:80px"></td>        
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad</td>
   	 <?	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom)  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td>
        	<select name="Entidad"  name="Entidad" id="Entidad"  onChange="document.FORMA.submit()" onKeyUp="Validar(this.value)"><option></option>
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
        	<select name="Contrato" id="Contrato" onChange="document.FORMA.submit()" onKeyUp="Validar(this.value)"><option></option>
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
        <td bgcolor="#e5e5e5" style="font-weight:bold">Mostrar</td>    	
        <td>
        	<select name="Mostrar">
        		<option value="SinRadicar">Sin Radicar</option>
           	<?	if($Mostrar=="Radicado"){?>
            		<option value="Radicado" selected>Radicado</option>
          	<?	}
				else{?>
                	<option value="Radicado">Radicado</option>
          	<?	}?>
        	</select>
        </td>
    </tr>   	    
    <tr align="center">
	    <td colspan="6"><input type="button" value="Ver" onClick="Validar()"/></td> 
 	</tr>
</table>    
</form>

<iframe frameborder="0" id="VerRadicacion" src="VerRadicacion.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&Mostrar=<? echo $Mostrar?>" width="100%" height="85%"></iframe>
</body>
</html>

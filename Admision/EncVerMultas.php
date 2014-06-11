<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();	
?>	

<html>
<head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function ValidaDocumento(Objeto)
	{
		frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocumentoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Multa=1&Cedula="+Objeto.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='90px';
		document.getElementById('FrameOpener').style.left='325px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='390';
	}
	function Validar()
	{
		if(document.FORMA.FechaIni.value>document.FORMA.FechaFin.value){alert("La fecha final debe ser mayor a la fecha inicial!!!");return false;}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">   
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">	
	<tr align="center">
    	<td colspan="10" bgcolor="#e5e5e5" style="font-weight:bold">Multas</td>        
  	</tr> 
    <tr>    	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Desde</td>
   	<?	if(!$FechaIni){
			if($ND[mon]<10){$C1="0";}
			$FechaIni="$ND[year]-$C1$ND[mon]-01";
		}
		if(!$FechaFin){
			if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
			$FechaFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
		}?>
        <td ><input type="text" readonly name="FechaIni" onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaIni?>"
        	onChange="document.FORMA.submit()">
       	</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Hasta</td>
        <td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" style="width:70px" value="<? echo $FechaFin?>"
        	onChange="document.FORMA.submit()" >
      	</td> 
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Estado</td>
        <td>
        	<select name="Estado">
            <?	if($Estado==="AC"){?>
            		<option value="AC" selected>Activas</option>
            <? 	}
				else{?>
                	<option value="AC" >Activas</option>
         	<? 	}?>
              <?	if($Estado=="AN"){?>
            		<option value="AN" selected>Inactivas</option>
            <? 	}
				else{?>
                	<option value="AN" >Inactivas</option>
         	<? 	}?>
             <?	if($Estado=="PG"){?>
            		<option value="PG" selected>Pagadas</option>
            <? 	}
				else{?>
                	<option value="PG" >Pagadas</option>
         	<? 	}?>  
            <?	if($Estado=="Todas"){?>
            		<option value="Todas" selected>Todas</option>
            <? 	}
				else{?>
                	<option value="Todas" >Todas</option>
         	<? 	}?>               
            </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td>
        <td><input type="text" name="Cedula" value="<? echo $Cedula?>"  onFocus="ValidaDocumento(this)"  onKeyUp="ValidaDocumento(this);xLetra(this)" 
        	onKeyDown="xLetra(this)" value="<? echo $Cedula?>" style="width:100"> 
      	</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">PDF</td>        
        <td>
        	<input type="checkbox" name="PDF">
        </td>
  	</tr>
    <tr>  
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad</td>
   	 <?	if($TipoAseg){$TA="and tipoasegurador='$TipoAseg'";}
	 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as nom  from Central.Terceros,contratacionsalud.contratos
		where Tipo='Asegurador' and Terceros.Compania='$Compania[0]' and contratos.compania='$Compania[0]' and entidad=identificacion $TA 
		group by identificacion,primape,segape,primnom,segnom order by primape";
		//echo $cons;?>
        <td colspan="7" align="center">
        	<select name="Entidad"><option></option>
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
        <td align="center" colspan="2">
        	<input type="submit" value="Ver" name="Ver">
        </td>
  	</tr>          
</table>
</form>    
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
<? 
if($Ver){?>
	<iframe frameborder="0" id="Multas" src="Multas.php?DatNameSID=<? echo $DatNameSID?>&Estado=<? echo $Estado?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&Cedula=<? echo $Cedula?>&Entidad=<? echo $Entidad?>&Ver=<? echo $Ver?>" width="100%" height="85%"><?
}
?>
</body>
</html>

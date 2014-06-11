<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.FechaFin.value==''){alert("Debe seleccionar la fecha final!!!"); return false;}
		if(document.FORMA.NoContrato.value==''){alert("Debe seleccionar un Numero de Contrao!!!"); return false;}
		if(document.FORMA.LiqExist.value=='1'){
			if(!confirm("Este servicio ya ha sido liquidado, aun asi desea realizar una nueva liquidacion?")){return false;}
		}
	}
</script>	
</head>
<?
if($Generar)
{
	$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' order by noliquidacion desc";	
	$res=ExQuery($cons);
	$fila=ExFetch($res); $NoLiq=$fila[0]+1;
			 
	
	$cons="select tiposervicio,medicotte,tipousu,nivelusu,autorizac1 from salud.servicios where compania='$Compania[0]' and numservicio=$NumServ";
	$res=ExQuery($cons); $DatServ=ExFetch($res);
	
	$cons="insert into facturacion.liquidacion (compania,usuario,fechacrea,ambito,medicotte,fechafin,fechaini,tipousu,nivelusu,autorizac1
	,pagador,contrato,nocontrato,noliquidacion,numservicio,cedula) values ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] 
	$ND[hours]:$ND[minutes]:$ND[seconds]','$DatServ[0]','$DatServ[1]','$FechaFin','$FechaIni','$DatServ[2]','$DatServ[3]','$DatServ[4]'
	,'$Entidad','$Contrato','$NoContrato',$NoLiq,$NumServ,'$Paciente[1]')";
	//echo $cons;			
	$res=ExQuery($cons);	?>
    <script language="javascript">
		alert("El servicio ha sido enviado a facturacion!!!");
		CerrarThis();
	</script>
	<?
}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr>
    <?	if(!$FechaIni){$FechaIni=$FecIng;}?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Inicial</td>
        <td>
        	<input type="Text" name="FechaIni"  readonly onClick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')" value="<? echo $FechaIni?>" style="width:90">
       	</td>
  	<?	if(!$FechaFin){$FechaFin=$FecEgr;}
		if(!$FechaFin){$FechaFin="$ND[year]-$ND[mon]-$ND[mday]";}?>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Final</td>
        <td>        
        	<input type="Text" name="FechaFin"  readonly onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')" value="<? echo $FechaFin?>"style="width:90">
       	</td>
    </tr>	
<?	$cons="select primape,entidad,contrato,nocontrato from salud.pagadorxservicios,central.terceros
	where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and entidad=identificacion and numservicio=$NumServ 
	and pagadorxservicios.tipo=1 order by pagadorxservicios.tipo,fechaini desc,primape";
	$res=ExQuery($cons);
	if(ExNumRows($res)==1){
		$fila=ExFetch($res);
		if(!$Entidad){$Entidad=$fila[1];} if(!$Contrato){$Contrato=$fila[2];} if(!$NoContrato){$NoContrato=$fila[3];}
	}
	$cons="select primape,entidad from salud.pagadorxservicios,central.terceros
	where pagadorxservicios.compania='$Compania[0]' and terceros.compania='$Compania[0]' and entidad=identificacion and numservicio=$NumServ 
	and pagadorxservicios.tipo=1 order by pagadorxservicios.tipo,fechaini desc,primape";
	$res=ExQuery($cons);?>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Entidad</td>
    	<td colspan="3">        
            <select name="Entidad" onChange="document.FORMA.submit()">
            	<option></option>
            <?	while($fila=Exfetch($res))
                {
                    if($Entidad==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";}	
                    else{echo "<option value='$fila[1]'>$fila[0]</option>";}
                }?>      
            </select>
      	</td>
    </tr>
    <tr>
    <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' AND estado='AC'
		group by contrato order by contrato";
		$res=ExQuery($cons);?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Contrato </td>
        <td>
            <select name="Contrato" onChange="document.FORMA.submit()">
            	<option></option>
                <?	while($fila=Exfetch($res))
                {
                    if($Contrato==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?> 
            </select>
        </td>
     <?	$cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' 
		and estado='AC' group by numero order by numero";
		$res=ExQuery($cons);?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No Contrato</td>
        <td>
            <select name="NoContrato" onChange="document.FORMA.submit()">
            	<option></option>
                <?	while($fila=Exfetch($res))
                {
                    if($NoContrato==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?> 
            </select>
        </td>
    </tr>
    <tr align="center">
    	<td colspan="4"><input type="submit" value="Generar Liquidacion" name="Generar"></td>
    </tr>
<?	$cons="select noliquidacion from facturacion.liquidacion where compania='$Compania[0]' and estado='AC' and numservicio=$NumServ";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{?>
		<input type="hidden" name="LiqExist" value="1">
<?	}else{?>
		<input type="hidden" name="LiqExist" value="0">
<?	}?>        
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
</form>    
</body>
</html>
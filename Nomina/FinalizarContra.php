<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
//	echo $TipoContrato;
//	echo $Numero."<--".$Identificacion."----".$FecFin."----".$FecIni." -- ".$Compania[0];
	$ND=getdate();
	$Year="$ND[year]";
	if("$ND[mon]"<10)
	{
		$Mes="0$ND[mon]";
	}
	if("$ND[mday]"<10)
	{
		$Dia="0$ND[mday]";
	}
	$Fecha="$ND[year]-$Mes-$Dia";
	//echo $Year;
	if($Guardar)
	{
		if($TipoContrato=="Indefinido"&&$FecFin=='')
		{
			$cons="update nomina.contratos set fecfin='$FechaFin',motivo='$Motivo' where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and fecinicio='$FecIni' and fecfin is Null";
		}
		else
		{
			$cons="update nomina.contratos set fecfin='$FechaFin',motivo='$Motivo' where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and fecinicio='$FecIni' and fecfin='$FecFin'";
		}
//		echo $cons;
		$res=ExQuery($cons);
		$AnioTr=substr($FecFin,0,4);
		$MesTr=substr($FecFin,5,2);
		$DiaTr=substr($FecFin,8,2);
		$AnioTrN=substr($FechaFin,0,4);
		$MesTrN=substr($FechaFin,5,2);
		$DiaTrN=substr($FechaFin,8,2);
		$cons0="select diastr from nomina.diastrab where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero'";
		$res0=ExQuery($cons0);
		$ContF=ExNumRows($res0);
//		echo $ContF;
		if($ContF==2)
		{
			$cons1="update nomina.diastrab set anio='$AnioTrN', mestr='$MesTrN', diastr='$DiaTrN' where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero' and anio='$AnioTr' and mestr='$MesTr' and diastr='$DiaTr'";
			$res1=ExQuery($cons1);
			//		echo $cons1;
		}
		elseif($ContF==1)
		{
			$cons2="select concepto from nomina.conceptosliquidacion,nomina.tiposvinculacion where conceptosliquidacion.compania='$Compania[0]' and 
					tiposvinculacion.compania=conceptosliquidacion.compania and diastr='1' and tiposvinculacion.codigo='$TipoVinculacion' and 
					conceptosliquidacion.tipovinculacion=tiposvinculacion.tipovinculacion";
			$res2=ExQuery($cons2);
			$fila=ExFetch($res2);
			$cons3="insert into nomina.diastrab values ('$Compania[0]','$Identificacion','$DiaTrN','$AnioTrN',$MesTrN,'$fila[0]','$TipoVinculacion','$Numero')";
			$res3=ExQuery($cons3);
//			echo $cons3;
		}
//		echo $Numero."<--".$Identificacion."----".$FecFin."----".$FecIni." -- ".$Compania[0]." -- ".$FechaFin;
		?>
		 <script language="javascript">
		 parent.location.href="Contrato.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Numero=<? echo $Numero?>&Editar=1";
		 </script>
	<?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type"/>
<script language="javascript" src="/Funciones.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-es.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-setup.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
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
	function Asignar(V)
	{
		//alert("entro");
		//parent.frames.FrameOpener.document.FORMA.CC.value=V;
		parent.frames.Info.document.FORMA.CC.value=V;
		CerrarThis();
	}
	function ConfigCal(Campo)
	{
		//alert(Campo.name)
		Calendar.setup({
		inputField     :    Campo.name, 	      
		ifFormat       :    "%Y-%m-%d",       
		showsTime      :    true,            
		//button         :    "calendario",   
		singleClick    :    false,           
		step           :    1                
		});	
	}
</script>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
<table border="0" bordercolor="#e5e5e5"  style='font : normal normal small-caps 13px Tahoma;' width="100%">
<br />
<tr>
<td colspan="2" bgcolor="#666699" style="color:white" align="center">Fecha de Finalizacion</td>
</tr>
<tr>
<td colspan="2" align="center"><input type="text" name="FechaFin" value="<? echo "$Fecha";?>" style="text-align:center" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" readonly/></td>
</tr>
<tr>
<td colspan="2" bgcolor="#666699" style="color:white" align="center">Motivo de Finalizacion</td>
</tr>
<tr><td colspan="2"><textarea name="Motivo" rows="5" cols="50" wrap="hard" onClick="this.value=''">motivo de finalizacion del contrato</textarea>
</tr>
<tr align="center"><td>
<input type="submit" name="Guardar" value="Guardar"  /></td><td><input type="button" value="Cancelar" onClick="CerrarThis()"/></td>
</tr>
</table>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
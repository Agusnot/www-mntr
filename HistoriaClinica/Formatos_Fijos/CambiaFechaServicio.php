<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$Paciente[1]){$Paciente[1]=$CedPac;}
	if($CambiarFec)
	{		
		$cons="select numservicio from salud.servicios where fechaing>='$FechaIniCamb 00:00:00' and fechaing<='$FechaIniCamb 23:59:59' 
		and compania='$Compania[0]' and cedula='$Paciente[1]'";
		//echo $cons;
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{?>
			<script language="javascript">
				alert("Hay un servicio registrado para esta fecha inicial!!!");
			</script>
	<?	}
		else{			
			if($FecFinCamb){$FF=",fechafin='$FecFinCamb'";}
			$cons="update facturacion.liquidacion set fechaini='$FechaIniCamb',fechacrea='$FechaIniCamb $FecCrea[1]' $FF
			where numservicio='$NumServ' and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			$res=pg_query($cons);
			$cons="select nofactura from facturacion.liquidacion where compania='$Compania[0]' and numservicio=$NumServ";
			$res=pg_query($cons); $NumFac=ExFetch($res);
			if($NoFac[0]){
				$cons="select fechacrea from facturacion.facturascredito where nofactura=$NoFac[0] and compania='$Compania[0]'";
				$res=pg_query($cons); $fila=pg_fetch_row($res); $FecCrea=explode(" ",$fila[0]);
				if($FecFinCamb){$FF=",fechafin='$FecFinCamb'";}
				$cons="update facturacion.facturascredito set fechaini='$FechaIniCamb',fechacrea='$FechaIniCamb $FecCrea[1]' $FF
				where nofactura=$NoFac[0] and compania='$Compania[0]'";
				//echo $cons."<br><br>";
				$res=pg_query($cons);
			}
			$cons="select TipoFormato,Formato,tblformat from historiaclinica.formatos where compania='$Compania[0]' order by TipoFormato,formato";
			$res=pg_query($cons);
			while($fila=pg_fetch_row($res))
			{
				$cons3="select table_name from information_schema.tables where table_name='".$fila[2]."' ";
				$res3=pg_query($cons3);
				if(pg_num_rows($res3)>0)
				{
					if($FechaIni){$FI="and fecha>='$FechaIni'";}
					if($FechaFin){$FF="and fecha<='$FechaFin'";}
					$cons2="select cedula from histoclinicafrms.".$fila[2]." where numservicio=$NumServ $FI and compania='$Compania[0]' 	group by cedula";
					$res2=pg_query($cons2);
					if(pg_num_rows($res2)>0)
					{
						$cons4="update histoclinicafrms.".$fila[2]." set fecha='$FechaIniCamb' where numservicio=$NumServ
						and compania='$Compania[0]'";
						//echo $cons4."<br><br>";
						$res4=pg_query($cons4);
					}
				}
			}
			if($FecFinCamb){$FF=",fechafin='$FecFinCamb'";}
			$cons="update salud.plantillaprocedimientos set fechaini='$FechaIniCamb' $FF where numservicio=$NumServ
			and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			$res=pg_query($cons);
				
			$cons="update odontologia.odontogramaproc set fecha='$FechaIniCamb' where tipoodonto='Seguimiento' and numservicio='$NumServ' 
			and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			$res=pg_query($cons);
			if($FecFinCamb){$FF=",fechafin='$FecFinCamb'";}
			$cons="update salud.plantillamedicamentos SET fechaformula='$FechaIniCamb $FecCrea[1]',fechaini='$FechaIniCamb' $FF
			where numservicio=$NumServ and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			$res=pg_query($cons);	
			
			$cons="update salud.agenda set fecha='$FechaIniCamb' where compania='$Compania[0]' and numservicio=$NumServ";
			//echo $cons."<br><br>";
			$res=pg_query($cons);	
			
			$cons="update salud.ordenesmedicas set fecha='$FechaIniCamb $FecCrea[1]' where numservicio='$NumServ' and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			//$res=pg_query($cons);	
			
			if($FecFinCamb){$FF=",fechafin='$FecFinCamb'";}
			$cons="update salud.pagadorxservicios set fechaini='$FechaIniCamb' $FF where compania='$Compania[0]' and numservicio=$NumServ";
			$res=pg_query($cons);
			
			if($FecFinCamb){$FF=",fechaegr='$FecFinCamb'";}
			$cons="update salud.servicios set fechaing='$FechaIniCamb' $FF where numservicio='$NumServ' and compania='$Compania[0]'";
			//echo $cons."<br><br>";
			$res=pg_query($cons);	
			if(!$ConsolidFac)
			{?>
            	<script language="javascript">
					alert("Cambio realizado exitosamente!!!");
					parent.location.href='NewAutorizarServicio.php?Numservicio=<? echo $NumServ?>&DatNameSID=<? echo $DatNameSID?>&Edit=1';
				</script>
       	<?	}
			else
			{?>
            	<script language="javascript">
					alert("Cambio realizado exitosamente!!!");
					parent.document.FORMA.submit();
				</script>
      	<?	}
		}
	}
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
		if(document.FORMA.FechaIniCamb.value==''){alert("Debe seleccionar la fecha Inicial!!!"); return false;}
		//if(document.FORMA.FechaFinal.value==''){alert("Debe seleccionar la fecha final!!!"); return false;}
		if(document.FORMA.FecFinCamb.value==''){
			if(!confirm("Esta seguro de dejar el servicio sin fecha final?"))
			{
				return false;	
			}
		}
		if(document.FORMA.FecFinCamb.value!=''&&document.FORMA.FecFinCamb.value<document.FORMA.FechaIniCamb.value){
			alert("La fecha final debe ser mayor o igual a la fecha inicial!!!");return false;
		}
		if(document.FORMA.FechaActual.value<document.FORMA.FechaIniCamb.value||document.FORMA.FechaActual.value<document.FORMA.FecFinCamb.value)
		{
			alert("La fecha del servicio debe ser menor a la fecha actual!!!"); return false;
		}
	}
</script>	
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<DIV align="center">
<font color="#FF0000" style="font : normal normal small-caps 24px Tahoma; text-align:center">
	¡AL CAMBIAR DE FECHA EL SERVICIO TODOS LOS REGISTROS DEL PACIENTE RELACIONADOS A ESTE SERAN CAMBIADOS DE FECHA!
</font>
</DIV>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr>
    <?	if(!$FechaIniCamb){$FechaIniCamb=$FecIng;}?>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Inicial</td>
        <td>
        	<input type="text" name="FechaIniCamb" readonly onClick="popUpCalendar(this,FORMA.FechaIniCamb,'yyyy-mm-dd')"
            style="width:90" value="<? echo $FechaIniCamb?>">
       	</td>
        <?	if(!$FecFinCamb){$FecFinCamb=$FecEgr;}
		//if(!$FecFinCamb){$FecFinCamb="$ND[year]-$ND[mon]-$ND[mday]";}?>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Final</td>
        <td>        
        	<input type="text" name="FecFinCamb"  readonly onClick="popUpCalendar(this,FORMA.FecFinCamb,'yyyy-mm-dd')" style="width:90" 
            value="<? echo $FecFinCamb?>">
       	</td>
  	</tr>
    <TR align="center">	
    	<td colspan="10"><input type="submit" value="Cambiar de Fecha" name="CambiarFec"></td>
    </TR>
</table>
<br>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td colspan="11">SERVICIOS ANTERIORES</td>
	</tr>    
<?	$consAnt="select tiposervicio,fechaing,fechaegr from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' 
	and numservicio!=$NumServ order by fechaing desc";
	$resAnt=ExQuery($consAnt);//echo $consAnt;
	while($fila=ExFetch($resAnt))
	{
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]&nbsp;</tr>";	
	}?>    
</table>
<input type="hidden" name="FechaActual" value="<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="ConsolidFac" value="<? echo $ConsolidFac?>">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
</form>
</body>
</html>    
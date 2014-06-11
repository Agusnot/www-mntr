<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	//if($NUMREG){$NUMREG=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	$cons="select identificacion,primape,segape,primnom,segnom,tipoasegurador from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradoras[$fila[0]]=array("$fila[1] $fila[2] $fila[3] $fila[4]",$fila[5]);
		//echo $Aseguradoras[$fila[0]][0]."<br>";
	}
	$cons="select compcont from salud.afectacioncontable where compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$CompCont=$fila[0];	
	if($Pagar){
		
		$cons2="select compfacturacion from contratacionsalud.contratos 
		where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and numero='$Nocontrato'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$CompFac=$fila2[0];
		
		$cons="select autoid from  contabilidad.movimiento where compania='$Compania[0]' order by autoid desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$AutoId=$fila[0]+1;
		
		$cons="insert into contabilidad.movimiento (autoid,fecha,comprobante,numero,identificacion,detalle,cuenta,debe,haber,cc,docsoporte,basegravable,compania)
		values ($AutoId,'$ND[year]-$ND[mon]-$ND[mday]',,$Ced,'Consulta Externa',)";
		//Pendietne de terminar con jaime
		
		//$cons="update salud.copagos set compcontable='$CompCont',nocompcont='12345' where compania='$Compania[0]' and numserv=$NumServ and tipocopago='$TipoCopago'";
		$cons="update facturacion.liquidacion set recaudo=1,compcontablerecaudo='12345' where compania='$Compania[0]' and noliquidacion=$NumLiq and estado='AC'";
		$res=ExQuery($cons);
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<?	if($CompCont){

		$cons="select liquidacion.cedula,primape,segape,primnom,segnom,pagador,liquidacion.contrato,liquidacion.nocontrato,liquidacion.total,liquidacion.numservicio,clasecopago
		,noliquidacion,valorcopago,tiposervicio
		from salud.servicios,central.terceros,facturacion.liquidacion
		where liquidacion.compania='$Compania[0]' and liquidacion.estado='AC' and servicios.compania='$Compania[0]' and servicios.numservicio=liquidacion.numservicio 
		and servicios.estado='AC' and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and compcontablerecaudo is NULL and recaudo=0";
		//echo $cons;
		/*$cons="select servicios.cedula,primape,segape,primnom,segnom,tiposervicio,entidad,contrato,nocontrato,copagos.tipocopago,copagos.valor,copagos.numserv
		from salud.copagos,salud.servicios,central.terceros,salud.agenda
		where copagos.compania='$Compania[0]' and servicios.compania='$Compania[0]' and servicios.numservicio=copagos.numserv and servicios.estado='AC'
		and terceros.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and agenda.compania='$Compania[0]' and agenda.estado='Activa'
		and agenda.numservicio=servicios.numservicio and CompContable is NULL";*/
		//echo $cons;
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{?>
			<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" >
				<td>Identificacion</td><td>Nombre</td><td>Proceso</td><td>Entidad</td><td>Contrato</td><td>No Contrato</td><td>No. Liquidacion</td><td>Tipo Copago<td>Valor Recaudo</td>
			</tr>
	<?		while($fila=ExFetch($res))
			{
				if($Aseguradoras[$fila[5]][1]=="Particular"){?>
                	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" title="Registrar Recuado"
                    onClick="location.href='Copagos.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[0]?>&NumLiq=<? echo $fila[11]?>&VrRecaudo=<? echo $fila[8]?>&Pagar=1'">
                    <?	echo "<td alig='center'>$fila[0]</td><td>$fila[1] $fila[2] $fila[3] $fila[4]</td><td>$fila[13]</td><td>".$Aseguradoras[$fila[5]][0]."</td>
						<td align='center'>$fila[6]</td><td align='center'>$fila[7]</td><td align='center'>$fila[11]</td><td align='center'>No Aplica</td>
						<td align='right'>".number_format($fila[8],2)."</td>";?>	
                    </tr>
			<?	}
				else{?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" title="Registrar Recuado"
                    onClick="location.href='Copagos.php?DatNameSID=<? echo $DatNameSID?>&Ced=<? echo $fila[0]?>&NumLiq=<? echo $fila[11]?>&VrRecaudo=<? echo $fila[12]?>&Pagar=1'">	<?		
					echo "<td alig='center'>$fila[0]</td><td>$fila[1] $fila[2] $fila[3] $fila[4]</td><td>$fila[13]</td><td>".$Aseguradoras[$fila[5]][0]."</td>
					<td align='center'>$fila[6]</td><td align='center'>$fila[7]</td><td align='center'>$fila[11]</td><td align='center'>$fila[10]</td>
					<td align='right'>".number_format($fila[12],2)."</td>";
		   ?>   </tr>
		<?		}
			}		
		}
		else
		{?>
			<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No hay recaudos pendientes</td></tr>
	<?	}
	}
	else{?>
		<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No se han configurado el comprobante contable</td></tr>
<?	}
?>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>

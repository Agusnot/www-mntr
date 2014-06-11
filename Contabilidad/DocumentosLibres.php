<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	if($Comprobante)
	{
		$Anio=substr($Fecha,0,4);
		$Numero=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
		$NoActual=$Numero-1;
	}

	if(!$Anio){$Anio=$ND[year];}

	$cons2="Select * from Central.CentrosCosto where Compania='$Compania[0]' and Codigo='000' and Anio=$Anio";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==0)
	{
		$cons3="Insert into Central.CentrosCosto(Codigo,CentroCostos,Compania,Anio,Tipo)
		values('000','Sin Centro','$Compania[0]',$Anio,'Detalle')";
		$res3=ExQuery($cons3);
	}

	$cons2="Select Cuenta from Contabilidad.Plancuentas where Anio=$Anio and Compania='$Compania[0]' and Cuenta='1'";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==0)
	{
		echo "<br><em>No hay plan de cuentas para la vigencia seleccionada!!!</em>";exit;
	}

	$cons="Select * from Central.Terceros where Identificacion = '99999999999-0' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons="Insert into Central.Terceros (PrimApe,SegApe,PrimNom,SegNom,Identificacion,Compania) values ('VARIOS','','','','99999999999-0','$Compania[0]')";
		$res=ExQuery($cons);
		echo ExError($res);
	}


	if($Ejecutar)
	{
		$Anio=substr($Fecha,0,4);
		$Mes=substr($Fecha,5,2);
		$cons2="Select * from Central.CierrexPeriodos where Compania='$Compania[0]' and Mes=$Mes and Anio=$Anio";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>=1)
		{?>
		<script language="JavaScript">
			alert("Periodo Cerrado, no es posible asignar documentos libres");
		</script>
<?		}
		else{
		$cons="Select TipoComprobant from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Tipo=$fila[0];

		for($i=1;$i<=$NoReservar;$i++)
		{
			$NumComprobante=$NoActual+$i;
			if($Tipo=="Cuentas x Pagar" || $Tipo=="Facturas")
			{
				$DocSoporte=$NumComprobante;
			}
			else{$DocSoporte=0;}
			$cons="Insert into Contabilidad.Movimiento (AutoId,Fecha,Comprobante,Numero,Detalle,Compania,UsuarioCre,FechaCre,Estado,Identificacion,DocSoporte,BaseGravable,ConceptoRte,Anio,CC,Cuenta,modificadox) 
			values($i,'$Fecha','$Comprobante','$NumComprobante','Documento Reservado','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday]','AC','99999999999-0','$DocSoporte',NULL,NULL,$Anio,'000','1','Eliminar')";
			$res=ExQuery($cons);echo ExError($res);
			$Mes=substr($Fecha,5,2);
		}?>
		<script language="JavaScript">
			alert("Proceso finalizado exitosamente");
			location.href='/Contabilidad/Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Comprobante=<?echo $Comprobante?>'
		</script>
		<?
		}
	}
?>
<body background="/Imgs/Fondo.jpg">
<script language='javascript' src="/calendario/popcalendar.js"></script> 
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.Comprobante.value==""){alert("Seleccione un comprobante");return false;}
		if(document.FORMA.NoReservar.value==""){alert("Seleccione la cantidad de documentos a reservar");return false;}
		if(document.FORMA.Fecha.value.length!=10){alert("Fecha Invalida");return false;}
	}
</script>
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" rules="groups" bordercolor="#e5e5e5" cellspacing="3" cellpadding="2" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Comprobante</td><td>Fecha</td><td>No. Actual</td><td>Cantidad</td><td>No. Final</td><td></td></tr>
<tr><td><select name="Comprobante" onChange="FORMA.submit()">
<option>
<?
	$cons="SELECT Comprobante FROM Contabilidad.Comprobantes where Compania='$Compania[0]'  Order By Comprobante";
	$res=ExQuery($cons,$conex);echo ExError($res);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Comprobante){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
	
	$cons="Select Fecha from Contabilidad.Movimiento where Comprobante='$Comprobante' and Compania='$Compania[0]'
	Order By Fecha Desc";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$fila=ExFetch($res);
		$Anio=substr($fila[0],0,4);
		$Mes=substr($fila[0],5,2);
		$Dia=substr($fila[0],8,2);
	}
	else
	{
		$Anio=$ND[year];
		$Mes=$ND[mon];if($Mes<10){$Mes="0".$Mes;}
		$Dia=$ND[mday];if($Dia<10){$Dia="0".$Dia;}
	}
?>
</select>
</td>
<td><input type="Text" name="Fecha" maxlength="10" onkeypress="return false;" onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy/mm/dd');" size="10" type="text" style="width:80px;" value="<? echo "$Anio-$Mes-$Dia" ?>"></td>
<td><input type="Text" name="NoActual" style="width:80px;" readonly="yes" value="<?echo $NoActual?>"></td>
<td>
<select name="NoReservar" style="width:80px;" onchange="NoFinal.value=NoActual.value-(this.value)*-1">
<option>
<?
	for($i=1;$i<=99;$i++)
	{
		echo "<option value='$i'>$i</option>";
	}
?>
<td><input type="Text" name="NoFinal" style="width:80px;" readonly="yes" value="<?echo $NoActual?>"></td>
<td><input type="Submit" name="Ejecutar" value="Ejecutar"></td>
</tr>
</table>
</form>
</body>
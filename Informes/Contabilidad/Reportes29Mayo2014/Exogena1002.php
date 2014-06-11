<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$ND=getdate();
	if(!$CuentaIni){$CuentaIni=2;}
	if(!$CuentaFin){$CuentaFin=29999999999;}
?>
		<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:10;font-style:<?echo $Estilo[10]?>">
		<tr><td colspan="14"><center><strong><?echo strtoupper($Compania[0])?><br>
		<?echo $Compania[1]?><br>INFORMACION EXOGENA FORMATO 1002<br>Vigencia: <?echo $Anio?></td></tr>
		<tr><td colspan="14" align="right">Fecha de Impresi&oacute;n <?echo "$ND[year]-$ND[mon]-$ND[mday]"?></td>
		</tr>
		<tr style="font-weight:bold" bgcolor="#e5e5e5" style="text-align:center;">
		<td>Concepto</td><td>Tipo Documento</td><td>No Identificacion</td><td>Dig Ver</td><td>Prim Apellido</td><td>Seg Apellido</td><td>Prim Nombre</td><td>Otros Nombres</td><td>Raz&oacute;n Social</td><td>Direccion</td><td>Depto</td><td>Mpo</td><td>Pais</td><td>Vr Pago</td><td>Vr Retencion</td>
</tr>
<?
	$cons="Select Cod1001,sum(BaseGravable),Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Direccion,Departamento,Municipio,Pais,
	sum(Haber),Movimiento.Cuenta 
	from Contabilidad.Movimiento,Central.Terceros,Contabilidad.PlanCuentas 
	where Terceros.Compania='$Compania[0]' and Movimiento.Compania='$Compania[0]'
	and Movimiento.Cuenta=PlanCuentas.Cuenta and PlanCuentas.Anio=$Anio and PlanCuentas.Compania='$Compania[0]'
	and Movimiento.Identificacion=Terceros.Identificacion and BaseGravable>0   
	and Fecha>='$Anio-$MesIni-$DiaIni' and Fecha<='$Anio-$MesFin-$DiaFin' and Movimiento.Cuenta>='$CuentaIni' and Movimiento.Cuenta<='$CuentaFin'
	and Estado='AC'
	Group By Cod1001,Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom,Direccion,Departamento,Municipio,Pais,Movimiento.Cuenta
	Order By PrimApe,SegApe,PrimNom,SegNom";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if(!$fila[4] && !$fila[5] && !$fila[6])
		{
			$RazonSoc=$fila[3];
			$PrimApe="";$TipoDoc="NIT";		
		}
		else{$PrimApe=$fila[3];$RazonSoc="";$TipoDoc="CC";}
		$cons2="Select Codigo from Central.Departamentos where Departamento='$fila[8]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$CodDepto=$fila2[0];
		
		$cons2="Select CodMpo from Central.Municipios where Departamento='$CodDepto' and Municipio='$fila[9]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$CodMpo=$fila2[0];

		$NoIdent=explode("-",$fila[2]);
		echo "<tr><td>$fila[0]</td><td>$TipoDoc</td><td>$NoIdent[0]</td><td>$NoIdent[1]</td><td>$PrimApe</td><td>$fila[4]</td><td>$fila[5]</td><td>$fila[6]</td><td>$RazonSoc</td><td>$fila[7]</td><td>$CodDepto</td><td>$CodMpo</td><td>0057</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[11],2)."</td></tr>";	
	}
?>

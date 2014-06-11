<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}

	$consT="Select Identificacion from Central.Terceros where PrimApe ilike 'Varios' and Terceros.Compania='$Compania[0]'";
	$resT=ExQuery($consT);
	$filaT=ExFetch($resT);
	$Tercero=$filaT[0];

?>
<body background="/Imgs/Fondo.jpg">
	<form name="FORMA">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">

	<tr><td style="color:white" bgcolor="<?echo $Estilo[1]?>">Seleccione Periodo</td>
	
	<td><select name="Anio">
	<?
		$AnioInc=$Anio-10;
		$AnioAf=$Anio+10;
		for($i=$AnioInc;$i<$AnioAf;$i++)
		if($i==$Anio){echo "<option selected value=$i>$i</option>";}
		else{echo "<option value=$i>$i</option>";}
	?>
	</select></td>
	<td><select name="Mes">
	<?
	$cons="Select Mes,Numero,NumDias from Central.Meses Order By Numero";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetch($res))
	{
		if($Mes==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";$NumDias=$fila[2];$MesLet=$fila[0];}
		else{echo "<option value='$fila[1]'>$fila[0]</option>";}
	}
?>

	</select></td>
<td style="color:white" bgcolor="<?echo $Estilo[1]?>">Vigencia</td><td>
<select name="Vigencia">
<?
	if(!$Vigencia){$Vigencia="Actual";}
	$cons1="Select Vigencia from Presupuesto.Vigencias";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($Vigencia==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
		else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
	}
?>
</select></td>
<td style="color:white" bgcolor="<?echo $Estilo[1]?>">Clase Vigencia</td><td>
<select name="ClaseVigencia">
<option>
<?
	$cons1="Select TiposVigencia from Presupuesto.TiposVigencia";
	$res1=ExQuery($cons1);
	while($fila1=ExFetch($res1))
	{
		if($ClaseVigencia==$fila1[0]){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
		else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
	}
?>
</select></td>

<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
	<td>	<input type="Submit" name="Iniciar" value="Iniciar"></td>
	</tr>
	</table>
</form>

<?	if($Iniciar)
	{

	if($ClaseVigencia=="")
	{
		$Comprobante="Cuentas cero";
	}
	if($ClaseVigencia=="CxP")
	{
		$Comprobante="Cuentas cero cxp";
	}
	if($ClaseVigencia=="Reservas")
	{
		$Comprobante="Cuentas cero reservas";
	}

	$cons="Select * from Central.CierrexPeriodos where Mes=$Mes and Anio=$Anio and Compania='$Compania[0]'";


	$res=ExQuery($cons);
	if(ExNumRows($res)==1)
	{?>
		<script language="JavaScript">
			alert("El periodo seleccionado se encuentra cerrado!!!");
		</script>
<?	
		exit;
	}

	$cons="Select * from Contabilidad.Movimiento where Comprobante='$Comprobante' and date_part('month',Fecha)=$Mes and date_part('year',Fecha)=$Anio and Movimiento.Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{?>
	<script language="JavaScript">
		if(confirm("Existe un cruce de cuentas cero para este mes, desea sobreescribirlo?")==false)
		{
			location.href="nada.html";
		}
		else
		{<?
			$cons="Delete from Contabilidad.Movimiento where Comprobante='$Comprobante' and date_part('month',Fecha)=$Mes and date_part('year',Fecha)=$Anio and Movimiento.Compania='$Compania[0]'";
			$res=ExQuery($cons);
?>		}
	</script>
<?	}

	$FechaContable="$Anio-$Mes-$NumDias";
	
	$Numero=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");


	if($Mes==1)
	{
		
		$cons="Select * from Presupuesto.CruceCuentasCero where Anio=$Anio and TipoCompPresupuestal='Apropiacion inicial' and Compania='$Compania[0]' 
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";

		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$cons1="Select sum(Apropiacion) from Presupuesto.PlanCuentas where Cuenta='$fila[6]' and Anio=$Anio and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			$Valor=$fila1[0];
			if($Valor){
			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'".$Tercero."','Apropiacion Inicial','".$fila[4]."','".$Valor."','0','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','Apropiacion inicial')";
			$res10=ExQuery($cons10);
			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'".$Tercero."','Apropiacion Inicial','".$fila[5]."','0','".$Valor."','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','Apropiacion inicial')";
			$res10=ExQuery($cons10);}
		}
	$Numero++;
	}

	$cons="Select * from Presupuesto.CruceCuentasCero where Anio=$Anio and Vigencia='$Vigencia' and Compania='$Compania[0]' and (Referencia IS NULL Or Referencia='0')";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{

		$cons1="Select * from Presupuesto.Movimiento,Presupuesto.Comprobantes 
		where Movimiento.Comprobante=Comprobantes.Comprobante and date_part('year',Fecha)=$Anio and TipoComprobant='$fila[3]' and Cuenta='$fila[6]' and date_part('month',Fecha)=$Mes
		and date_part('year',Fecha)=$Anio  and Estado='AC' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' 
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res1=ExQuery($cons1);

		while($fila1=ExFetchArray($res1))
		{
			$Valor=$fila1['credito']+$fila1['contracredito'];
			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[4]."','".$Valor."','0','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);

			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[5]."','0','".$Valor."','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);


		}
	}
	
	$cons="Select * from Presupuesto.CruceCuentasCero where Anio=$Anio and Vigencia='$Vigencia' and Compania='$Compania[0]' and (Referencia ='Credito') ";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="Select * from Presupuesto.Movimiento,Presupuesto.Comprobantes 
		where Movimiento.Comprobante=Comprobantes.Comprobante and date_part('year',Fecha)=$Anio and TipoComprobant='$fila[3]' and Cuenta='$fila[6]' 
		and date_part('month',Fecha)=$Mes
		and date_part('year',Fecha)=$Anio  and Estado='AC' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' 
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Credito>0";
		$res1=ExQuery($cons1);

		while($fila1=ExFetchArray($res1))
		{
			$Valor=$fila1['credito']+$fila1['contracredito'];
			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[4]."','".$Valor."','0','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);

			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[5]."','0','".$Valor."','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);


		}
	}


	$cons="Select * from Presupuesto.CruceCuentasCero where Anio=$Anio and Vigencia='$Vigencia' and Compania='$Compania[0]' and (Referencia ='Contra Credito') ";

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons1="Select * from Presupuesto.Movimiento,Presupuesto.Comprobantes 
		where Movimiento.Comprobante=Comprobantes.Comprobante and date_part('year',Fecha)=$Anio and TipoComprobant='$fila[3]' and Cuenta='$fila[6]' 
		and date_part('month',Fecha)=$Mes
		and date_part('year',Fecha)=$Anio  and Estado='AC' and Movimiento.Compania='$Compania[0]' and Comprobantes.Compania='$Compania[0]' 
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and ContraCredito>0";
		$res1=ExQuery($cons1);

		while($fila1=ExFetchArray($res1))
		{
			$Valor=$fila1['credito']+$fila1['contracredito'];
			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[4]."','".$Valor."','0','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);

			$AutoId++;
			$cons10="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,DocDestino)
			values($AutoId,'$FechaContable','$Comprobante',$Numero,'$Tercero','Interface Cuenta Cero Mes $MesLet','".$fila[5]."','0','".$Valor."','0','0','0','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','".$fila1['comprobante']."-".$fila1['numero']."')";
			$res10=ExQuery($cons10);


		}

	}	
	?>
	
	<script language="JavaScript">
		alert("Proceso Finalizado exitosamente!!!");
	</script>
	
<?	}
?>


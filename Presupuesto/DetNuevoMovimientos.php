<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include("Funciones.php");
	$Bloquear="";
	if(!$DocSoporte){$DocSoporte="0";}
	if(!$ValidarSaldo){$ValidarSaldo=0;}
	if(!$Credito){$Credito=0;}
	if(!$ContraCredito){$ContraCredito=0;}
	$cons="Select TipoComprobant from Presupuesto.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TipoComp=$fila[0];
	$ND=getdate();

	$ValorDim=0;

	if($Modificar)
	{
		$cons="Select sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento 
		where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]'
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'
		and Cuenta='$Cuenta' and Estado='AC'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if($fila[0]>0){$ValorDim=$fila[0];}
		if($fila[1]>0){$ValorDim=$fila[1];}

		$cons="Select * from Presupuesto.Movimiento where Cuenta='$Cuenta' and CompAfectado='$Comprobante' and DocSoporte='$Numero'
		and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Estado='AC'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){$NoModCta=" readonly ";}else{$NoModCta="";}
		
		$cons="Select NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia from Presupuesto.TmpMovimiento
		where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		$Cuenta=$fila['cuenta'];$Credito=$fila['credito'];
		$ContraCredito=$fila['contracredito'];$Tercero=$fila['identificacion'];
		$DocSoporte=$fila['docsoporte'];
		$Detalle=$fila['detalle'];$Comprobante=$fila['comprobante'];$Vigencia=$fila['vigencia'];$ClaseVigencia=$fila['clasevigencia'];

		$cons="Delete from Presupuesto.TmpMovimiento where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$ValMovimiento=1;
		$BON=" onload='document.FORMA.Cuenta.focus();' ";
	}
	if($Eliminar)
	{
		$cons="Delete from Presupuesto.TmpMovimiento where NumReg='$NUMREG' and Cuenta='$Cuenta' and AutoId=$AutoId";
		$res=ExQuery($cons);
		$Guardar=1;$NoInsert=1;
	}
	
	if($Guardar)
	{
		if($TipoComp=="Disponibilidad")
		{
			$cons="Select * from Presupuesto.TmpMovimiento where NumReg='$NUMREG' and Compania='$Compania[0]' and Cuenta='$Cuenta'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){echo "<em>No puede duplicar registros en Disponibilidades!!!</em>";$NoInsert=1;}
		}
	
		if($TipoComp=="Compromiso presupuestal" || $TipoComp=="Egreso presupuestal" || $TipoComp=="Obligacion presupuestal" 
		|| $TipoComp=="Disminucion a obligacion presupuestal" || $TipoComp=="Disminucion a compromiso" || $TipoComp=="Disminucion a disponibilidad" || $TipoComp=="Ingreso presupuestal" || $TipoComp=="Disminucion a ingreso presupuestal"){

		if($TipoComp=="Compromiso presupuestal" || $TipoComp=="Egreso presupuestal" || $TipoComp=="Obligacion presupuestal"){$Valor=$Credito;}
		if($TipoComp=="Disminucion a obligacion presupuestal" || $TipoComp=="Disminucion a compromiso" || $TipoComp=="Disminucion a disponibilidad" || $TipoComp=="Ingreso presupuestal" || $TipoComp=="Disminucion a ingreso presupuestal"){$Valor=$ContraCredito;}
		include("CalcularSaldos.php");

		$cons1="Select CompDestino from Presupuesto.CruceComprobantes where CompOrigen='$Comprobante'";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$cons2="Select Comprobante from Presupuesto.Comprobantes where TipoComprobant='$fila1[0]'";

		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$DocAnterior=$fila2[0];

		if(!$DocSoporte){$DocSoporte="99999999999";}
		ObtieneValoresxDocxCuenta("$Anio-01-01","$Anio-$Mes-$Dia",$DocAnterior);
		$SaldoDoc=CalcularSaldoxDocxCuenta($Cuenta,$DocSoporte,$DocAnterior,"$Anio-01-01","$Anio-$Mes-$Dia",$Vigencia,$ClaseVigencia);
		}
		else{$SaldoDoc=99999999999999999;}
		if($TipoComp=="Disponibilidad"){$Valor=$Credito;}
		if($TipoComp=="Ingreso presupuestal" || $TipoComp=="Reconocimiento presupuestal"){$Valor=$ContraCredito;}

		$cons7="Select sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento 
		where DocSoporte='$DocSoporte' and CompAfectado='$DocAnterior' and Estado='AC'
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'
		and Comprobante='$Comprobante' and Numero='$Numero' and Cuenta='$Cuenta' and Movimiento.Compania='$Compania[0]'";

		$res7=ExQuery($cons7);
		if(ExNumRows($res7)>0)
		{
			$fila7=ExFetch($res7);
			if($fila7[0]>0){$VrDesc=$fila7[0];}
			if($fila7[1]>0){$VrDesc=$fila7[1];}
			$SaldoDoc=$SaldoDoc+$VrDesc;
		}


		$cons="Select sum(Credito),sum(ContraCredito) from Presupuesto.Movimiento 
		where Cuenta='$Cuenta' and DocSoporte='$Numero' and CompAfectado='$Comprobante' and Compania='$Compania[0]' and Estado='AC'
		and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if($fila[0]>0){$VrAfectado=$fila[0];}if($fila[1]>0){$VrAfectado=$fila[1];}
		if($Valor<$VrAfectado)
		{
		?>
			<script language="JavaScript">
				alert("El valor no puede estar por debajo de la afectacion --><? echo number_format($VrAfectado,2)?>");
			</script>		
<?		$Modificar=1;}
		else
		{
		if($Valor>$SaldoDoc)
		{
		?>
			<script language="JavaScript">
				alert("Valor sobrepasa el saldo del documento --> <?echo number_format($SaldoDoc,2)?>");
			</script>		
<?		$Modificar=1;}
		else{
		if(!$NoInsert)
		{
			if(!$AutoId){
			$cons="Select AutoId from Presupuesto.TmpMovimiento where NumReg='$NUMREG' Order By AutoId Desc";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$AutoId=$fila[0]+1;}

			$cons="Select * from Central.Terceros where Identificacion='$Tercero' and Terceros.Compania='$Compania[0]'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==0)
			{
				echo "<em>Tercero NO existe. Registro Abortado</em><br>";
			}
			else
			{

				$cons="Insert into Presupuesto.TmpMovimiento (NumReg,AutoId,Comprobante,Identificacion,Cuenta,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia)
				values('$NUMREG',$AutoId,'$Comprobante','$Tercero','$Cuenta',$Credito,$ContraCredito,'$DocSoporte','$Compania[0]','$Detalle','$Vigencia','$ClaseVigencia')";
				$res=ExQuery($cons);
				if(mysql_errno()==1062){echo "<font size='-1'><em>No puede duplicar el rubro</em></font>";}
			}

		}
		$DocSoporte="0";
		$AutoId="";
		$Cuenta="";$Credito="0";
		$ContraCredito="0";
		$ValMovimiento=0;
		$CC="000";
		}
		}
		$BON=" onload='document.FORMA.Cuenta.focus();' ";
	}

		if($TipoComp=="Disponibilidad"){$ValidarSaldo=1;}
		if($TipoComp=="Reduccion" || $TipoComp=="Traslado"){$ValidarSaldo=2;}
		$cons="Select Descuadre from Presupuesto.TiposComprobante where Tipo='$TipoComp'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Descuadre=$fila[0];

		$cons="Select sum(Credito),sum(ContraCredito) from Presupuesto.TmpMovimiento where NumReg='$NUMREG'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$TotCredito=$fila[0];$TotContraCredito=$fila[1];$Dif=$TotCredito-$TotContraCredito;
		if(($Dif==0 || $Descuadre=="Si")&&($TotContraCredito || $TotCredito))
		{?>
			<script language="JavaScript">
				parent.document.FORMA.Guardar.disabled=false;
			</script>
<?		}
		else
		{?>
			<script language="JavaScript">
				parent.document.FORMA.Guardar.disabled=true;
			</script>
<?		}
		?>
		<script language="JavaScript">
			parent.frames.TotMovimientos.document.FORMA.TotDebitos.value="<?echo number_format($TotCredito,2)?>";
			parent.frames.TotMovimientos.document.FORMA.TotCreditos.value="<?echo number_format($TotContraCredito,2)?>";
			parent.frames.TotMovimientos.document.FORMA.Diferencia.value="<?echo number_format($Dif,2)?>";
		</script>
<?		

?>
<body onFocus="Ocultar()" <? echo "$BON";?>>
<script language="javascript" src="/Funciones.js"></script>

<script language="JavaScript">
	function Validar(ValorDim)
	{
		if(document.FORMA.ValidarSaldo.value==1){
		Saldo=(parent.frames.TotMovimientos.document.FORMA.Saldo.value*1);
		Valor=(document.FORMA.Credito.value)*1;
		if(Valor>Saldo+ValorDim){alert("Valor sobrepasa el saldo de la apropiacion->" + Saldo);document.FORMA.Credito.focus();return false;}
		}

		if(document.FORMA.ValidarSaldo.value==2){
		Saldo=(parent.frames.TotMovimientos.document.FORMA.Saldo.value*1);
		Valor=(document.FORMA.ContraCredito.value)*1;
		if(Valor>Saldo+ValorDim){alert("Valor sobrepasa el saldo de la apropiacion->" + Saldo);document.FORMA.Credito.focus();return false;}
		}
		

		if(document.FORMA.Credito.value!=0 && document.FORMA.ContraCredito.value!=0){alert("No puede registrar movimiento en ambas partidas");return false;}
		if(document.FORMA.Movimiento.value!="Detalle"){alert("Cuenta no permite movimiento");document.FORMA.Cuenta.focus();return false;}
		if(document.FORMA.Credito.value==0 && document.FORMA.ContraCredito.value==0){alert("Credito ingresar el valor del movimiento");document.FORMA.Credito.focus();return false;}
		if(document.FORMA.Tercero.value==""){alert("Se requiere tercero");document.FORMA.Tercero.focus();return false;}

	}
</script>
<form name="FORMA" onSubmit="return Validar(<?echo $ValorDim?>)">

<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td>Id</td><td>Codigo</td><td>Cr&eacute;dito</td><td>Contra Cr&eacute;dito</td><td>Tercero</td><td>Doc</td></tr>
<?
	if(!$Vigencia){$Vigencia="Actual";}

	$cons="Select Cuenta,Credito,ContraCredito,Identificacion,DocSoporte,Detalle,AutoId from Presupuesto.TmpMovimiento 
	where NumReg='$NUMREG' and Comprobante='$Comprobante' Order By AutoId";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="";$Fondo=1;}
		
		$cons2="Select Nombre from Presupuesto.PlanCuentas where Cuenta='$fila[0]' and Compania='$Compania[0]'
		and Vigencia='$Vigencia' and Anio='$Anio'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NomCuenta=substr($fila2[0],0,40-strlen($fila[0]));
		
		$cons3="Select * from Presupuesto.Movimiento where Cuenta='$fila[0]' and CompAfectado='$Comprobante' and DocSoporte='$Numero'
		and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' and Estado='AC'";
		$res3=ExQuery($cons3);

		echo "<tr bgcolor='$BG'><td align='right'>$fila[6]</td><td>$fila[0] $NomCuenta</td><td align='right'>".number_format($fila[1],2)."</td><td align='right'>".number_format($fila[2],2)."</td><td align='right'>$fila[3]</td><td>$fila[4]</td>";
		if(!$Modificar)
		{
			echo "<td>";
			
			if(!$Bloquear){echo "<a href='DetNuevoMovimientos.php?DatNameSID=$DatNameSID&Modificar=1&Cuenta=$fila[0]&NUMREG=$NUMREG&AutoId=$fila[6]&Comprobante=$Comprobante&Tercero=$Tercero&DocSoporte=$DocSoporte&Detalle=$Detalle&Anio=$Anio&Mes=$Mes&Dia=$Dia&Numero=$Numero&Vigencia=$Vigencia&ClaseVigencia=$ClaseVigencia&MovHabil=$MovHabil'>";}echo "<img src='/Imgs/b_edit.png' border=0></a></td>";
	
			if(ExNumRows($res3)==0)
			{
				echo "<td>";
				if(!$Bloquear){echo "<a href='DetNuevoMovimientos.php?DatNameSID=$DatNameSID&Eliminar=1&Cuenta=$fila[0]&NUMREG=$NUMREG&AutoId=$fila[6]&Comprobante=$Comprobante&Tercero=$Tercero&DocSoporte=$DocSoporte&Detalle=$Detalle&Anio=$Anio&Mes=$Mes&Dia=$Dia&Numero=$Numero&Vigencia=$Vigencia&ClaseVigencia=$ClaseVigencia&MovHabil=$MovHabil'>";}echo "<img src='/Imgs/b_drop.png' border=0></a></td>";
			}
		}
		echo "</tr>";
	}


	if($MovHabil=="Credito"){$CredHabil="";$CCredHabil="disabled";}
	if($MovHabil=="Contra Credito"){$CredHabil="disabled";$CCredHabil="";}

?>
<tr>
<script language="JavaScript">
	function Mostrar()
	{
		parent.document.getElementById('Busquedas').style.position='absolute';
		parent.document.getElementById('Busquedas').style.top='50px';
		parent.document.getElementById('Busquedas').style.right='10px';
		parent.document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		parent.document.getElementById('Busquedas').style.display='none';
	}
</script>

<input type="Hidden" name="ValidarSaldo" value="<?echo $ValidarSaldo?>">  
<td colspan="2"><input type="Text" name="Cuenta" value="<? echo $Cuenta?>"  <? echo $NoModCta?> style="width:110px;" onBlur="campoNumero(this)" onKeyDown="xNumero(this)" onFocus="Mostrar();Tercero.value=parent.document.FORMA.Identificacion.value;parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Mes='+parent.document.FORMA.Mes.value+'&Anio='+parent.document.FORMA.Anio.value+'&TipoCom=<? echo $TipoComp?>'+'&Vigencia='+Vigencia.value+'&ClaseVigencia='+ClaseVigencia.value" onKeyUp="xNumero(this);parent.frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentas&Cuenta='+this.value+'&Mes='+parent.document.FORMA.Mes.value+'&Anio='+parent.document.FORMA.Anio.value+'&TipoCom=<?echo $TipoComp?>'+'&Vigencia='+document.FORMA.Vigencia.value+'&ClaseVigencia='+document.FORMA.ClaseVigencia.value"></td>
<td><input type="text" onFocus="Ocultar()" name="Credito" <? echo $CredHabil?> style="width:80px;" value="<? echo $Credito?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
<td><input type="text" onFocus="Ocultar()" name="ContraCredito" <? echo $CCredHabil?> style="width:80px;" value="<? echo $ContraCredito?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
<td><input type="text" onFocus="Ocultar()" name="Tercero" style="width:85px;" readonly value="<? echo $Tercero?>">
</td>
</td>
<td><input type="text" onFocus="Ocultar()" name="DocSoporte" readonly style="width:80px;" value="<?echo $DocSoporte?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>

<input type="Hidden" onFocus="Ocultar()" name="Detalle" style="width:90px;" value="<?echo $Detalle?>">
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Movimiento" value="<?echo $ValMovimiento?>">
<input type="Hidden" name="AutoId" value="<?echo $AutoId?>">
<input type="Hidden" name="MovHabil" value="<?echo $MovHabil?>">
<input type="Hidden" name="Anio" value="<?echo $Anio?>">
<input type="Hidden" name="Mes" value="<?echo $Mes?>">
<input type="Hidden" name="Dia" value="<?echo $Dia?>">
<input type="Hidden" name="Numero" value="<?echo $Numero?>">
<input type="Hidden" name="Vigencia" value="<?echo $Vigencia?>">
<input type="Hidden" name="ClaseVigencia" value="<?echo $ClaseVigencia?>">
<?
	if($Bloquear){$Disabled=" disabled ";}
	else{$Disabled="";}
	
	if($Modificar)
	{
	?>
	<script language="JavaScript">
		parent.document.FORMA.Guardar.disabled=true;
	</script>
<?
	}
	
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<td><input type="Submit" <? echo $Disabled?> name="Guardar" value="G"></td>
</tr>
</table>
</form>
</body>
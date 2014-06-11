<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	
	$phpMovimiento=str_replace("_","/",$phpMovimiento);
	$ParamsAdc=str_replace("_","=",$ParamsAdc);
	$ParamsAdc=str_replace("*","&",$ParamsAdc);
	
	if(!$phpMovimiento){$phpMovimiento="Movimiento.php";}

	$cons="Select Formato from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Archivo=$fila[0];

	$cons="Select TipoComprobant,CompPresupuesto from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$TipoComp=$fila[0];
	$DocPresupuestal=$fila[1];

	$cons="Select CompPresupuesto,Archivo,VerificaCruce from Contabilidad.TiposComprobante where Tipo='$TipoComp'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$ArcEjecutar=$fila[1];
	$VerificaCruce=$fila[2];

	if(!$NUMREG){$NUMREG=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);;}
		if(!$Edit){
			$Numero=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
			if($_GET['Comprobante']=="Venta de servicios"&&$_GET['Tipo']=="Facturas"){
				$NumeroA=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
				$consB="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc limit 1";
				$resB=ExQuery($consB);
				while($NumeroB=ExFetch($resB)){
					if($NumeroA>$NumeroB[0]){$Numero=$NumeroA;}
					if($NumeroB[0]>$NumeroA){$Numero=$NumeroB[0]+1;}
					if($NumeroB[0]==$NumeroA){$Numero=$NumeroB[0]+1;}
				}								
			}
		}

	if($Cancelar)
	{
		$cons="Delete from Contabilidad.TmpMovimiento where NumReg='$NUMREG'";
		$res=ExQuery($cons);
		?>
		<script language="JavaScript">
			parent(2).location.href='<? echo $phpMovimiento?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Numero=<?echo $Numero?>&<? echo $ParamsAdc?>'
		</script>
		<?
	}

	if($Guardar || $Acarrear)
	{
		$MesTrabajo=$Mes;$AnioTrabajo=$Anio;$DiaTrabajo=$Dia;
		$cons="Select NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido 
		from Contabilidad.TmpMovimiento where NumReg='$NUMREG'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$cons1="Select Naturaleza,Tercero,Documentos from Contabilidad.PlanCuentas where Cuenta='$fila[3]' and Anio=$Anio and Compania='$Compania[0]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			
			if($fila1[1]==1){$CondAdc1=" and Identificacion='$Identificacion'";}

			$cons2="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where date_part('year',Fecha)<$Anio $CondAdc1 and Cuenta='$fila[3]' and Compania='$Compania[0]' and Estado='AC'";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);echo ExError($res2);
			$Debitos=$fila2[0];$Creditos=$fila2[1];
			if($fila1[0]=="Debito"){$SaldoI=$SaldoI+$Debitos-$Creditos;}
			elseif($fila1[0]=="Credito"){$SaldoI=$SaldoI+$Creditos-$Debitos;}

			$cons3="Select sum(Debe),sum(Haber) from Contabilidad.Movimiento where Cuenta ='$fila[3]' $CondAdc1 and Fecha>='$Anio-01-01' and Fecha<='$Anio-$Mes-$Dia' and Compania='$Compania[0]' and Estado='AC'";
			$res3=ExQuery($cons3);
			$fila3=ExFetch($res3);echo ExError($res3);
			$Debitos=$fila3[0];$Creditos=$fila3[1];
			if(!$Debitos){$Debitos=0;}if(!$Creditos){$Creditos=0;}

			if($fila1[0]=="Debito"){$SaldoF=$SaldoI-$Creditos+$Debitos+$fila[5]-$fila[6];}
			elseif($fila1[0]=="Credito"){$SaldoF=$SaldoI+$Creditos-$Debitos-$fila[5]+$fila[6];}

			if($SaldoF<0)
			{?>
				<script language="JavaScript">
					if(confirm("Al realizar este comprobante, la cuenta <?echo $fila[3]?> quedaria con saldo <?echo number_format($SaldoF,0)?>. Desea continuar?")==false)
					{
						location.href="NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $Numero?>&Tipo=<?echo $Tipo?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Tercero?>&Identificacion=<?echo $Identificacion?>&Banco=<?echo $Banco?>&NumCheque=<?echo $NumCheque?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Edit=1";
					}
				</script>
<?			}
		}

		if($Edit && !$Acarrear)
		{
			$cons="Delete from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]'";
			$res=ExQuery($cons);echo ExError($res);
		}

		$cons="Select NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido,FechaConciliado from Contabilidad.TmpMovimiento 
		where NumReg='$NUMREG'";

		$res=ExQuery($cons);

		if($PagoEgreso=="" || $PagoEgreso=='NULL'){$PagoEgreso='NULL';}else{$PagoEgreso="'$PagoEgreso'";}
		if($TipoPago=="" || $TipoPago=='NULL'){$TipoPago='NULL';}else{$TipoPago="'$TipoPago'";}
		if($ClasePago=="" || $ClasePago='NULL'){$ClasePago='NULL';}else{$ClasePago="'$ClasePago'";}
		if(!$Edit){$Numero=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");}
		if($_GET['Comprobante']=="Venta de servicios"&&$_GET['Tipo']=="Facturas"){
			$NumeroA=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
			$consB="select nofactura from facturacion.facturascredito where compania='$Compania[0]' order by nofactura desc limit 1";
			$resB=ExQuery($consB);
			while($NumeroB=ExFetch($resB)){
				if($NumeroA>$NumeroB[0]){$Numero=$NumeroA;}
				if($NumeroB[0]>$NumeroA){$Numero=$NumeroB[0]+1;}
				if($NumeroB[0]==$NumeroA){$Numero=$NumeroB[0]+1;}
			}								
		}
		if($Acarrear){$Numero=$ObjAcarreo;$cons29="Delete from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$ObjAcarreo' and Compania='$Compania[0]' and Detalle='Documento Reservado' and Identificacion='99999999999-0'";$res29=ExQuery($cons29);}
		while($fila=ExFetch($res))
		{
			if($Acarrear && $fila[8]){$fila[8]=$ObjAcarreo;}
			if(substr($fila[3],0,2)=="14"){$ValidacionCruce=1;}
			if(!$BaseGravable){$BaseGravable=0;}if(!$fila[14]){$fila[14]="NULL";}else{$fila[14]="'$fila[14]'";}if(!$fila[13]){$fila[13]=0;}
			if(!$BancoRecRec){$BancoRecRec=0;}if(!$NumCheque){$NumCheque=0;}if(!$Banco){$Banco=0;}
			if(!$fila[10]){$fila[10]=0;}if(!$fila[11]){$fila[11]=0;}if(!$DiasVencimiento){$DiasVencimiento=0;}

			if($fila[12]=="NULL" || $fila[12]==""){$fila[12]='NULL';}else{$fila[12]="'$fila[12]'";}

			$cons1="Insert into Contabilidad.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,UsuarioCre,FechaCre,FormaPago,NoCheque,Banco,DiasVencimiento,TipoPago,ClasePago,BancoRecRec,ConceptoRte,PorcRetenido,FechaConciliado,FechaDocumento,Anio)
			values($fila[1],'$Anio-$Mes-$Dia','$Comprobante','$Numero','$fila[4]','$Detalle','$fila[3]','$fila[5]','$fila[6]','$fila[7]','$fila[8]','$fila[11]','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$PagoEgreso,'$NumCheque','$Banco','$DiasVencimiento',$TipoPago,$ClasePago,'$BancoRecRec',$fila[12],'$fila[13]',$fila[14],'$FechaDocumento',$Anio)";
			$res1=ExQuery($cons1,$conex);
			if(ExError($res1))
			{
                            echo "<br>ATENCION: COPIE EL MENSAJE DE ERROR (COLOR ROJO) Y ENVIELO X CORREO A SU ADMINISTRADOR DE SISTEMAS:<BR>";
                               exit;
                        }
		}

		$cons92="Delete from Contabilidad.TmpMovimiento where NumReg='$NUMREG'";
		$res92=ExQuery($cons92);
		$Edit=NULL;
		if($DocGen)
		{
			$cons45="Update consumo.movimiento set CompContable='$Comprobante',NumCompCont='$Numero' where Compania='$Compania[0]' and Comprobante='$DocConsumo' and Numero='$NumDocConsumo' and AlmacenPpal='$AlmacenPpal'
			and Compania='$Compania[0]'";
			$res45=ExQuery($cons45);echo ExError();
		}



		$cons="Select Comprobante,Numero,Vigencia,ClaseVigencia from Presupuesto.Movimiento where DocOrigen='$Comprobante' and NoDocOrigen='$Numero' and Estado='AC' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>=1)
		{
			$fila=ExFetch($res);?>
			<script language="JavaScript">
				location.href="/Presupuesto/NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $fila[0]?>&Numero=<?echo $fila[1]?>&Edit=1&DocOrigen=<?echo $Comprobante?>&NoDocOrigen=<?echo $Numero?>&Tipo=<?echo $Tipo?>&Vigencia=<?echo $fila[2]?>&ClaseVigencia=<?echo $fila[3]?>";
			</script>		
<?		}
		else
		{
			if($ValidacionCruce=="0" && $VerificaCruce=="Si")
			{
			?>
			<script language="JavaScript">
				parent(2).location.href='<? echo $phpMovimiento?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Numero=<?echo $Numero?>&<? echo $ParamsAdc?>';
				<?if($DocPresupuestal){?>
				SelAfectacion=confirm("Desea realizar el comprobante presupuestal?");
				if(SelAfectacion==true)
				{
					open('AfectacionContable.php?DatNameSID=<? echo $DatNameSID?>&AfectaReconocimiento=1&Registrar=1&NombreTercero=<?echo $Tercero?>&DocDestino=<?echo $DocDestino?>&Tercero=<?echo $Identificacion?>&DocPresupuestal=<?echo $DocPresupuestal?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $Numero?>&Detalle=<?echo $Detalle?>&DocOrigen=<?echo $Comprobante?>&NoDocOrigen=<?echo $Numero?>&Tipo=<?echo $Tipo?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>','','width=1,height=1,top=2000,left=2000,scrollbars=yes');
				}
				else
				{
					open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>","","width=650,height=500,scrollbars=yes");
				}<?}
				else{?>
					open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>","","width=650,height=500,scrollbars=yes");
				<?}?>
			</script>
	<?		}
			else
			{
			?>
			<script language="JavaScript">
				parent(2).location.href='<? echo $phpMovimiento?>?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Numero=<?echo $Numero?>&<? echo $ParamsAdc?>'
				<?if($DocPresupuestal){?>
					SelAfectacion=confirm("Desea realizar el comprobante presupuestal?");
					if(SelAfectacion==true)
					{
						open('<?echo $ArcEjecutar?>?DatNameSID=<? echo $DatNameSID?>&Generar=1&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&Anio=<?echo $Anio?>&Tercero=<?echo $Identificacion?>&DocPresupuestal=<?echo $DocPresupuestal?>&Comprobante=<?echo $Comprobante?>&Numero=<?echo $Numero?>&Detalle=<?echo $Detalle?>&Tipo=<?echo $Tipo?>','','width=700,height=500,scrollbars=yes');
					}
					else
					{
						open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>","","width=650,height=500,scrollbars=yes");
					}<?}
					else
					{?>
						open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>","","width=650,height=500,scrollbars=yes");
	<?				}?>
			</script>
			<? }
		}
	}

	if($Edit)
	{
		if(!$NoCargue)
		{
			$cons="Select AutoId,Identificacion,Cuenta,Debe,Haber,CC,DocSoporte,BaseGravable,Compania,'',Detalle,Fecha,Banco,NoCheque,FormaPago,
			TipoPago,ClasePago,BancoRecRec,ConceptoRte,PorcRetenido,FechaConciliado,FechaDocumento,ModificadoX
			from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if(!$fila[3]){$fila[3]=0;}
				if(!$fila[4]){$fila[4]=0;}
				if(!$fila[0]){$fila[0]=0;}
				if(!$fila[19]){$fila[19]=0;}
				if(!$fila[20]){$fila[20]="NULL";}else{$fila[20]="'$fila[20]'";}
				if(!$fila[18]){$fila[18]="NULL";}else{$fila[18]="'$fila[18]'";}
				if(!$fila[7]){$fila[7]="NULL";}else{$fila[7]="'$fila[7]'";}
				$DSoporte=$fila[6];
				if($fila[22]!="Eliminar"){
				$cons2="Insert into Contabilidad.TmpMovimiento (NumReg,AutoId,Comprobante,Cuenta,Identificacion,Debe,Haber,CC,DocSoporte,Compania,Detalle,Base,ConceptoRte,PorcRetenido,FechaConciliado)
				values('$NUMREG',$fila[0],'$Comprobante','$fila[2]','$fila[1]',$fila[3],$fila[4],'$fila[5]','$fila[6]','$fila[8]','$fila[9]',$fila[7],$fila[18],$fila[19],$fila[20])";
				$res2=ExQuery($cons2);echo ExError($res2);
				$Identificacion=$fila[1];$Detalle=$fila[10];$Banco=$fila[12];$NumCheque=$fila[13];$FormaPago=$fila[14];

			}
				$Anio=substr($fila[11],0,4);$Mes=substr($fila[11],5,2);$Dia=substr($fila[11],8,2);
				$TipoPago=$fila[15];$ClasePago=$fila[16];$BancoRecRec=$fila[17];$FechaDocumento=$fila[21];
			}
		}
		$cons3="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$Identificacion' and Terceros.Compania='$Compania[0]'";
		$res3=ExQuery($cons3);
		$fila3=ExFetch($res3);
		$Tercero="$fila3[0] $fila3[1] $fila3[2] $fila3[3]";
	}

	$cons="Select NumDias from Central.Meses where Numero=$Mes";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NoMaxDias=$fila[0];

	$cons2="Select * from Central.CentrosCosto where Compania='$Compania[0]' and Codigo='000' and Anio=$Anio";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==0)
	{
		$cons3="Insert into Central.CentrosCosto(Codigo,CentroCostos,Compania,Anio,Tipo)
		values('000','Sin Centro','$Compania[0]',$Anio,'Detalle')";
		$res3=ExQuery($cons3);
	}


?>
<title>Registro de Movimiento</title>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Tercero.focus();" onFocus="Ocultar()">
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.MesInvalido.value==1){alert("Periodo cerrado, no se puede asignar documento a este mes!!!");return false;}
		if(document.FORMA.Dia.value>document.FORMA.NoMaxDias.value || document.FORMA.Dia.value<1){alert("Fecha Invalida");return false;}
		if(document.FORMA.Identificacion.value=="0" || document.FORMA.Identificacion.value==""){alert("Tercero Invalido");return false;}
		if(document.FORMA.Detalle.value==""){alert("Debe registrar un Detalle");return false;}
	}
</script>
<script language='javascript' src="/calendario/popcalendar.js"></script> 
<script language="javascript" src="/Funciones.js"></script>
<form name="FORMA" onSubmit="return Validar()">
<table border="0">
<tr><td>
<table border="1" width="700" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td colspan="4"><?echo strtoupper($Comprobante)?></td></tr>
<tr><td>Fecha</td>
<td>
<input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly="yes" value="<?echo $Anio?>">
<?
	$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==1)
	{
?>
<select name="Mes" style="width:40px" onFocus="Oculta()" onChange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Modulo=Contabilidad&Tipo=RevisaCierre&Anio='+document.FORMA.Anio.value+'&Mes='+this.value;frames.Busquedas.location.href='Busquedas.php?Tipo=MaxDias&Mes='+this.value">
<?
	for($i=1;$i<=12;$i++)
	{
		if($i==$Mes){echo "<option selected value='$i'>$i</option>";}
		else{echo "<option value='$i'>$i</option>";}
	}
?>
</select>
<?
	}
	else
	{
?>
<input type="Text" name="Mes" readonly="yes" style="width:20px" maxlength="2" onFocus="Ocultar()" value="<?echo $Mes?>">
<?
	}
	if(!$Dia){$Dia=$DiaTrabajo;if(strlen($Dia)<2 && !$Edit){$Dia="0".$Dia;}}
	if(!$FechaDocumento){$FechaDocumento="$Anio-$Mes-$Dia";}
?>
<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<? echo $Dia?>">
Fecha doc <input type="Text" name="FechaDocumento" value="<? echo "$FechaDocumento"?>" style="width:70px;" maxlength="10" onKeyPress="return false;" onClick="popUpCalendar(this, FORMA.FechaDocumento, 'yyyy-mm-dd');">
</td>
<td>Numero</td>
<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly="yes" style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<?echo $Numero?>"></td>
<tr>
<td>Tercero</td>
<td><input type="Text" name="Tercero" value="<? echo $Tercero?>" onKeyPress="return evitarSubmit(event)" style="width:250px;" onKeyUp="Mostrar();Identificacion.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;Pasar(event,'Detalle')"></td>
<td>Identificacion</td>
<td><input type="Text" value="<? echo $Identificacion?>" style="width:200px;" name="Identificacion" onKeyPress="return evitarSubmit(event)" onKeyUp="frames.NuevoMovimiento.document.FORMA.Tercero.value=this.value;Pasar(event,'Detalle')"  onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion='+this.value">
<img src="/Imgs/webdown.png" title="Cambiar este tercero para todos los asientos" style="cursor:hand" onClick="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CambiaTerceroComp&NUMREG=<? echo $NUMREG?>&Cedula='+document.FORMA.Identificacion.value+'&Comprobante=<? echo $Comprobante?>'">

</td>

<tr>
<td>Detalle</td>
<td colspan="3"><input type="Text" id="Detalle" onKeyPress="return evitarSubmit(event)"  onKeyUp="xLetra(this);" onKeyDown="xLetra(this)" value="<?echo $Detalle?>" name="Detalle" onfocus="Ocultar()" style="width:580px;" onblur="frames.NuevoMovimiento.document.FORMA.Detalle.value=this.value" onfocus="frames.NuevoMovimiento.document.FORMA.Tercero.value=Identificacion.value"></td>
</tr>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td colspan="2">EGRESO</td><td colspan="2">INGRESO</td></tr>
<tr>
<td>Banco</td><td>

<?
	$cons="Select TipoComprobant from Contabilidad.Comprobantes where Comprobante='$Comprobante'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if($fila[0]=="Egreso"){$DisIngreso="";$DisEgreso="Disabled";}
	elseif($fila[0]=="Ingreso"){$DisIngreso="Disabled";$DisEgreso="";}
	else{$DisIngreso="Disabled";$DisEgreso="Disabled";}
?>
<input type="Text" value="<?echo $Banco?>" name="Banco" <? echo $DisIngreso ?> style="width:85px;" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bancos&Banco='+this.value+'&Anio='+document.FORMA.Anio.value" onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bancos&Banco='+this.value+'&Anio='+document.FORMA.Anio.value">
<select name="PagoEgreso" onFocus="Ocultar()" onChange="if(this.value=='Cheque'){frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cheque&Banco='+Banco.value+'&Anio='+document.FORMA.Anio.value}" <? echo $DisIngreso?>>
<option>
<?
	$cons="Select Forma from Contabilidad.FormasPago where Egreso=1";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$FormaPago){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
<input type="Text" value="<?echo $NumCheque?>" <? echo $DisIngreso?> name="NumCheque" onFocus="Mostrar()" style="width:55px;">
</td>
<td>Pago en</td>
<td><select name="PagoIngreso" <?echo $DisEgreso?> onFocus="Ocultar()">
<?
	$cons="Select Forma from Contabilidad.FormasPago where Ingreso=1";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>
</select>
<script language="JavaScript">
	function AfectaDocumentos()
	{
		if(document.FORMA.Identificacion.value==""){alert("Debe seleccionar un tercero");document.FORMA.Identificacion.focus();return false;}
		if(document.FORMA.ReqBanco.value=="1" && document.FORMA.Banco.value==""){alert("Debe seleccionar un Banco para afectar documentos");document.FORMA.Banco.focus();return false;}

		frames.FrameOpener.location.href='AfectarDocumentos.php?DatNameSID=<? echo $DatNameSID?>&Detalle='+document.FORMA.Detalle.value+'&NUMREG=<?echo $NUMREG?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>&Tercero='+document.FORMA.Identificacion.value+'&Banco='+document.FORMA.Banco.value+'&Anio='+document.FORMA.Anio.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}

	function AplicaRetencion()
	{
		if(document.FORMA.Identificacion.value==""){alert("Debe seleccionar un tercero");document.FORMA.Identificacion.focus();return false;}
		if(document.FORMA.Detalle.value==""){alert("Ingrese el detalle");document.FORMA.Detalle.focus();return false;}
		frames.FrameOpener.location.href='ReteFuente.php?DatNameSID=<? echo $DatNameSID?>&DSoporte=<? echo $DSoporte?> &Detalle='+document.FORMA.Detalle.value+'&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Tercero='+document.FORMA.Identificacion.value+'&Banco='+document.FORMA.Banco.value+'&Anio='+document.FORMA.Anio.value+'&DocSoporte='+frames.NuevoMovimiento.document.FORMA.DocSoporte.value;

		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='480';
	}
	function ConceptosPago()
	{
		if(document.FORMA.Identificacion.value==""){alert("Debe seleccionar un tercero");document.FORMA.Identificacion.focus();return false;}
		if(document.FORMA.Detalle.value==""){alert("Ingrese el detalle");document.FORMA.Detalle.focus();return false;}

		frames.FrameOpener.location.href='ConceptosPago.php?DatNameSID=<? echo $DatNameSID?>&Detalle='+document.FORMA.Detalle.value+'&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Tercero='+document.FORMA.Identificacion.value+'&Banco='+document.FORMA.Banco.value+'&Anio='+document.FORMA.Anio.value+'&Numero='+document.FORMA.Numero.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
		
	}
</script>
<input type="Text" onFocus="Ocultar()" name="NoConsignacion" style="width:30px;" <?echo $DisEgreso?>>
Banco
<input type="Text" onFocus="Ocultar()" name="IngBanco" style="width:70px;" <?echo $DisEgreso?>></td>
</tr>
<tr><td colspan="2">Tipo de Pago 
<select name="TipoPago" style="width:90px;" <?echo $DisIngreso?>>
<option>
<?
	$cons="Select Tipo from Contabilidad.TiposPago Order By Codigo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($TipoPago==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
Clase de Pago
<select name="ClasePago" style="width:90px;" <?echo $DisIngreso?>>
<option>
<?
	$cons="Select Clase from Contabilidad.ClasesPago Order By Codigo";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($ClasePago==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
</td>
<td colspan="2">Recursos Recibidos en
<input <? echo $DisEgreso?> type="Text" name="BancoRecRec" value="<? echo $BancoRecRec?>" style="width:200px;" onKeyUp="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bancos1&Banco='+this.value" onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bancos1&Banco='+this.value+'&Anio='+document.FORMA.Anio.value"></td>
</tr>

<tr><td colspan="4"><center>
<?
	$cons="Select * from Contabilidad.CruzarComprobantes where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
	$fila=ExFetch($res);
	if($fila[4]=="Bancos"){$ReqBanco=1;}
?>
<input type="Hidden" name="ReqBanco" value="<?echo $ReqBanco?>">
<input type="Button" value="Afectar Documentos" onClick="AfectaDocumentos()" onFocus="Ocultar()" style="width:150px;">
<?}?>

<?
	$cons="Select * from Contabilidad.ConceptosPago where Comprobante='$Comprobante' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){$Disabled="";}
	else{$Disabled=" disabled ";}
?>

	
<input type="Button" <? echo $Disabled ?> accesskey="C" value="Conceptos de pago" onFocus="Ocultar()" onClick="ConceptosPago()" style="width:150px;">


<?
	$cons="Select * from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Retencion=1 and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
?>
<input type="Button" onFocus="Ocultar()" value="Retenciones" onClick="AplicaRetencion()" style="width:150px;">
<?	}
	else
	{?>
<input type="Button" onFocus="Ocultar()" disabled value="Retenciones" onClick="AplicaRetencion()" style="width:150px;">
	<? } ?>


</td></tr>
</tr>
</table>
<?
	if($TipoComp=="Cuentas x Pagar" || $TipoComp=="Facturas"){$DS=$Numero;}else{$DS=0;}
?>
<iframe id="NuevoMovimiento" height="200" src="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&DocSoporte=<? echo $DS?>&Comprobante=<?echo $Comprobante?>&NUMREG=<?echo $NUMREG?>" frameborder="0" width="700"></iframe><br>
<iframe id="TotMovimientos" src="TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" width="700" height="80"></iframe><br>

<?
	if($Edit)
	{?>
		<script language="JavaScript">
			frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&DocSoporte=<? echo $DS?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&Comprobante=<? echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<? echo $Identificacion?>';
		</script>
<?	}
?>
<center>
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Tipo" value="<?echo $Tipo?>">
<input type="Hidden" name="Edit" value="<?echo $Edit?>">
<input type="Hidden" name="ValidacionCruce" value="0">
<input type="Submit" name="Guardar" value="Guardar Registro" style="width:150px;" disabled>
<input type="Submit" name="Cancelar" value="Cancelar" style="width:150px;">
<br>
<?
	$cons="Select * from Contabilidad.Comprobantes where Comprobante='$Comprobante' and Acarreo=1 and Compania='$Compania[0]'"; 
	$res=ExQuery($cons);
	if(ExNumRows($res)>0 && $Edit)
	{
		$NumeroAcarreo=ConsecutivoComp($Comprobante,$Anio,"Contabilidad");
?>
<input type="submit" onFocus="Ocultar()" value="Insertar registro en:" title="<? echo $NumeroAcarreo?>" name="Acarrear" style="width:150px;" disabled>
<select name='ObjAcarreo' style="width:150px;">
<option value="<? echo $NumeroAcarreo?>"><? echo $NumeroAcarreo?>(Nuevo)</option>
<?
	$cons="Select Numero,Fecha from Contabilidad.Movimiento where Detalle='Documento Reservado' and Comprobante='$Comprobante' 
	and Identificacion='99999999999-0'
	and Compania='$Compania[0]' and Estado='AC'
	and date_part('year',Fecha)=$Anio and Cuenta='1' Order By Numero";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0] ($fila[1])</option>";
	}
?>
</select>
<?	} 
	else
	{?>
	<input type="hidden" name="Acarrear">
<?	}
?>

<input type="Hidden" name="NoMaxDias" value="<?echo $NoMaxDias?>">
<input type="Hidden" name="MesInvalido" value="0">
<input type="hidden" name="phpMovimiento" value="<? echo $phpMovimiento?>">
<input type="hidden" name="ParamsAdc" value="<? echo $ParamsAdc?>">
<input type="Hidden" name="DocConsumo" value="<? echo $DocConsumo?>">
<input type="hidden" name="NumDocConsumo" value="<? echo $NumDocConsumo?>">
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>">
<input type="Hidden" name="DocGen" value="<? echo $DocGen?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>


</td>
<script language="JavaScript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
</tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>

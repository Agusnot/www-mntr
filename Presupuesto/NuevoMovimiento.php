<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();

	$cons="Select TipoComprobant,Archivo from Presupuesto.Comprobantes where Comprobante='$Comprobante'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	
	$TipoComp=$fila[0];

	$ArchivoImp=$fila[1];
	
	$cons="Select GeneraNuevo,Movimiento from Presupuesto.TiposComprobante where Tipo='$TipoComp'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$GeneraNuevo=strtoupper(substr($fila[0],0,1));
	$GeneraNuevo=$GeneraNuevo.strtolower(substr($fila[0],1,strlen($fila[0])));
	
	$MovHabil=$fila[1];
	
	if(!$Numero){$Numero=ConsecutivoComp($Comprobante,$Anio,"Presupuesto");}
	if(!$NUMREG){$NUMREG=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]");}

	if($Cancelar)
	{
		$cons="Delete from Presupuesto.TmpMovimiento where NumReg='$NUMREG'";
		$res=ExQuery($cons);

		if($DocOrigen)
		{
			$cons="Select Formato,TipoComprobant from Contabilidad.Comprobantes where Comprobante='$DocOrigen' and Compania='$Compania[0]'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);

		?>
		<script language="JavaScript">
			parent(2).location.href='/Contabilidad/Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $DocOrigen?>&Mes=<?echo $Mes?>&Tipo=<?echo $fila[1]?>&Numero=<?echo $NoDocOrigen?>'
		</script>
		<?}
		else
		{
			if($CompAnterior){$Comprobante=$CompAnterior;}
		?>
		<script language="JavaScript">
			parent(2).location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Numero=<?echo $Numero?>'
		</script>
		<? }
	}

	if($Guardar)
	{
		$MesTrabajo=$Mes;$AnioTrabajo=$Anio;$DiaTrabajo=$Dia;

		if($Edit)
		{
			$cons="Delete from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia'
			and ClaseVigencia='$ClaseVigencia'";
			$res=ExQuery($cons);
		}

		$cons="Select NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia from Presupuesto.TmpMovimiento 
		where NumReg='$NUMREG' and Comprobante='$Comprobante'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$Vigencia=$fila[10];$ClaseVigencia=$fila[11];
			if(!$Vigencia){$Vigencia='Actual';}
			$cons1="Insert into Presupuesto.Movimiento(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,Compania,UsuarioCre,FechaCre,DiasVencimiento,DocOrigen,NoDocOrigen,CompAfectado,Vigencia,ClaseVigencia,Anio)
			values($fila[1],'$Anio-$Mes-$Dia','$Comprobante','$Numero','$fila[4]','$Detalle','$fila[3]','$fila[5]','$fila[6]','$fila[7]','$Compania[0]','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','0','$DocOrigen','$NoDocOrigen','$CompAfectado','$Vigencia','$fila[11]',$Anio)";
			$res1=ExQuery($cons1,$conex);
		}

		if($GeneraNuevo && !$DocOrigen)
		{
			$cons="Select Comprobante,Numero,Vigencia,ClaseVigencia from Presupuesto.Movimiento where CompAfectado='$Comprobante' and DocSoporte='$Numero' 
			and Estado='AC' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
			$res=ExQuery($cons);
			if(ExNumRows($res)>=1)
			{
				$fila=ExFetch($res);

				$cons2="Select TipoComprobant,Archivo from Presupuesto.Comprobantes where Comprobante='$fila[0]'";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				$TipoComp=$fila2[0];

				$cons1="Select TipoGr from Presupuesto.TiposComprobante where Tipo='$TipoComp'";
				$res1=ExQuery($cons1);
				$fila1=ExFetch($res1);
				$Tipo=$fila1[0];
		?>
				<script language="JavaScript">
					location.href="NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $fila[0]?>&Numero=<?echo $fila[1]?>&Edit=1&Tipo=<?echo $Tipo?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>";
					open("/Informes/Presupuesto/<?echo $ArchivoImp?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>","","width=650,height=500,scrollbars=yes")
				</script>
<?			}
			elseif(ExNumRows($res)==0)
			{

				$NUMREGANT=$NUMREG;
				$NUMREG=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]");
				$cons99="Update Presupuesto.TmpMovimiento set Comprobante='$GeneraNuevo',NUMREG='$NUMREG',DocSoporte='$Numero' 
				where NUMREG='$NUMREGANT' and Comprobante='$Comprobante'";
				$res99=ExQuery($cons99);
				
				$cons87="Select * from Presupuesto.Movimiento where Numero='$Numero' and Comprobante='$GeneraNuevo' and Movimiento.Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
				$res87=ExQuery($cons87);
				if(ExNumRows($res87)==0)
				{
					$NumDoc=$Numero;
				}
				?>
				<script language="JavaScript">
					location.href="NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&NUMREG=<?echo $NUMREG?>&CompAnterior=<?echo $Comprobante?>&Comprobante=<?echo $GeneraNuevo?>&Tercero=<?echo $Tercero?>&Identificacion=<?echo $Identificacion?>&Detalle=<?echo $Detalle?>&Mes=<?echo $Mes?>&Anio=<?echo $Anio?>&Dia=<?echo $Dia?>&CompAfectado=<?echo $Comprobante?>&Tipo=<?echo $Tipo?>&Numero=<?echo $NumDoc?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>";
					open("/Informes/Presupuesto/<?echo $ArchivoImp?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>","","width=650,height=500,scrollbars=yes")
				</script>
<?		}}
		else{
			$cons="Delete from Presupuesto.TmpMovimiento where NumReg='$NUMREG'";
			$res=ExQuery($cons);
		}
		if($DocOrigen)
		{
		$cons="Select Formato,TipoComprobant from Contabilidad.Comprobantes where Comprobante='$DocOrigen' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Archivo=$fila[0];

		?>
		<script language="JavaScript">
			open("/Informes/Contabilidad/<?echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $NoDocOrigen?>&Comprobante=<?echo $DocOrigen?>","","width=650,height=500,scrollbars=yes")
			parent(2).location.href='/Contabilidad/Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $DocOrigen?>&Mes=<?echo $Mes?>&Tipo=<?echo $fila[1]?>&Numero=<?echo $NoDocOrigen?>'
		</script>
		<?
		}
		else
		{
		?>
		<script language="JavaScript">
			open("/Informes/Presupuesto/<?echo $ArchivoImp?>?DatNameSID=<? echo $DatNameSID?>&Numero=<?echo $Numero?>&Comprobante=<?echo $Comprobante?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>","","width=650,height=500,scrollbars=yes")
<?			if($CompAnterior){$Comprobante=$CompAnterior;}?>
			parent(2).location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&Mes=<?echo $Mes?>&Tipo=<?echo $Tipo?>&Numero=<?echo $Numero?>'
		</script>
		<?
		}
	}

	if($Edit)
	{
		$cons="Select AutoId,Identificacion,Cuenta,Credito,ContraCredito,DocSoporte,Compania,'',Detalle,Fecha,DocOrigen,NoDocOrigen,CompAfectado,Vigencia,ClaseVigencia,ModificadoX 
		from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[15]!='Eliminar')
			{
				$cons2="Insert into Presupuesto.TmpMovimiento (NumReg,AutoId,Comprobante,Cuenta,Identificacion,Credito,ContraCredito,DocSoporte,Compania,Detalle,Vigencia,ClaseVigencia)
				values('$NUMREG',$fila[0],'$Comprobante','$fila[2]','$fila[1]','$fila[3]','$fila[4]','$fila[5]','$fila[6]','$fila[7]','$fila[13]','$fila[14]')";
				$res2=ExQuery($cons2);
				$Identificacion=$fila[1];$Detalle=$fila[8];$CompAfectado=$fila[12];$DocOrigen=$fila[10];$NoDocOrigen=$fila[11];
			}
			$Anio=substr($fila[9],0,4);$Mes=substr($fila[9],5,2);$Dia=substr($fila[9],8,2);
			$Vigencia=$fila[13];$ClaseVigencia=$fila[14];
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

?>
<title>Registro de Movimiento</title>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Tercero.focus();" onFocus="Ocultar()">
<script language="JavaScript">
	function AfectaDocumentos()
	{
		if(document.FORMA.Identificacion.value==""){alert("Debe seleccionar un tercero");document.FORMA.Identificacion.focus();return false;}
		if(document.FORMA.Detalle.value==""){alert("Ingrese el detalle");document.FORMA.Detalle.focus();return false;}
		
		frames.FrameOpener.location.href='AfectacionDocumentos.php?DatNameSID=<? echo $DatNameSID?>&Anio='+document.FORMA.Anio.value+'&Mes='+document.FORMA.Mes.value+'&Dia='+document.FORMA.Dia.value+'&Detalle='+document.FORMA.Detalle.value+'&Numero=<?echo $Numero?>&NUMREG=<?echo $NUMREG?>&Comprobante=<?echo $Comprobante?>&Tercero='+document.FORMA.Identificacion.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
</script>

<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.MesInvalido.value==1){alert("Periodo cerrado, no se puede asignar documento a este mes!!!");return false;}
		if(document.FORMA.Dia.value>document.FORMA.NoMaxDias.value || document.FORMA.Dia.value<1){alert("Fecha Invalida");return false;}
		if(document.FORMA.Identificacion.value=="0" || document.FORMA.Identificacion.value==""){alert("Tercero Invalido");return false;}
		if(document.FORMA.Detalle.value==""){alert("Debe registrar un Detalle");return false;}
	}
</script>
<script language="javascript" src="/Funciones.js"></script>
<form name="FORMA" onSubmit="return Validar()">
<table border="0">
<tr><td>
<table border="1" width="700" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td colspan="4"><?echo strtoupper($Comprobante)?></td></tr>
<tr><td>Fecha</td>
<td>
<input type="Text" name="Anio" onFocus="Ocultar()" style="width:40px;" readonly="yes" value="<?echo $Anio?>">
<?
	$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==1)
	{
?>
<select name="Mes" style="width:40px" onFocus="Ocultar()" onChange="frames.Busquedas.location.href='/Contabilidad/Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=RevisaCierre&Anio='+document.FORMA.Anio.value+'&Mes='+this.value;frames.Busquedas.location.href='/Contabilidad/Busquedas.php?Tipo=MaxDias&Mes='+this.value">
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
	if(!$Dia){$Dia=$DiaTrabajo;}
	if(strlen($Dia)<2 && !$Edit){$Dia="0".$Dia;}
?>
<input type="Text" name="Dia" onFocus="Ocultar()" style="width:20px;" maxlength="2" value="<?echo $Dia?>">

</td>
<td>Numero</td>
<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly="yes" style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<?echo $Numero?>"></td>
<tr>
<td>Nombre Tercero</td>
<td><input type="Text" name="Tercero" onFocus="Mostrar()" value="<?echo $Tercero?>" style="width:250px;" onKeyPress="return evitarSubmit(event)" onKeyUp="Identificacion.value='';frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value+'&NumReg=<?echo $NUMREG?>'"></td>
<td>Identificacion</td>
<td><input type="Text" value="<?echo $Identificacion?>" style="width:230px;" onFocus="Ocultar()" name="Identificacion" onKeyPress="return evitarSubmit(event)" onKeyUp="frames.NuevoMovimiento.document.FORMA.Tercero.value=this.value"  onchange="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Identificacion&Identificacion='+this.value+'&NumReg=<?echo $NUMREG?>'"></td>

<tr>
<td>Detalle</td>
<td colspan="3"><input type="Text" onFocus="Ocultar()" value="<?echo $Detalle?>" name="Detalle" style="width:580px;" onKeyPress="return evitarSubmit(event)" onKeyUp="xLetra(this);" onKeyDown="xLetra(this)" onBlur="frames.NuevoMovimiento.document.FORMA.Detalle.value=this.value" ></td>
</tr>
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td colspan="4">AFECTACION DE DOCUMENTOS</td></tr>
<tr><td colspan="4">
<center>
<input type="Button" value="Comprobante a afectar" onFocus="Ocultar()" onClick="AfectaDocumentos()"></td></tr>
</tr>
</table>
<iframe id="NuevoMovimiento" height="200" src="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Comprobante=<?echo $Comprobante?>&MovHabil=<?echo $MovHabil?>&NUMREG=<?echo $NUMREG?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Identificacion?>&ValidarSaldo=<?echo $ValidarSaldo?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&Numero=<?echo $Numero?>&Bloquear=<?echo $Bloquear?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>" frameborder="0" width="700"></iframe><br>
<iframe id="TotMovimientos" src="TotMovimientos.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" width="700" height="80"></iframe><br>

<?
	if($Edit)
	{?>
		<script language="JavaScript">
			frames.NuevoMovimiento.location.href='DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Guardar=1&NoInsert=1&NUMREG=<?echo $NUMREG?>&MovHabil=<?echo $MovHabil?>&Comprobante=<?echo $Comprobante?>&Detalle=<?echo $Detalle?>&Tercero=<?echo $Identificacion?>&ValidarSaldo=<?echo $ValidarSaldo?>&Anio=<?echo $Anio?>&Mes=<?echo $Mes?>&Dia=<?echo $Dia?>&DocSoporte=<?echo $Numero?>&Numero=<?echo $Numero?>&Bloquear=<?echo $Bloquear?>&Vigencia=<?echo $Vigencia?>&ClaseVigencia=<?echo $ClaseVigencia?>';
		</script>
<?	}
?>
<center>
<input type="Hidden" name="NUMREG" value="<?echo $NUMREG?>">
<input type="Hidden" name="Comprobante" value="<?echo $Comprobante?>">
<input type="Hidden" name="Edit" value="<?echo $Edit?>">
<input type="Hidden" name="DocOrigen" value="<?echo $DocOrigen?>">
<input type="Hidden" name="NoDocOrigen" value="<?echo $NoDocOrigen?>">
<input type="Hidden" name="CompAnterior" value="<?echo $CompAnterior?>">
<input type="Hidden" name="CompAfectado" value="<?echo $CompAfectado?>">
<input type="Hidden" name="Tipo" value="<?echo $Tipo?>">
<input type="Hidden" name="NoMaxDias" value="<?echo $NoMaxDias?>">

<input type="Hidden" name="Vigencia" value="<?echo $Vigencia?>">
<input type="Hidden" name="ClaseVigencia" value="<?echo $ClaseVigencia?>">


<input type="Hidden" name="MesInvalido" value="0">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="Submit" name="Guardar" value="Guardar Registro" style="width:150px;" disabled>
<input type="Submit" name="Cancelar" value="Cancelar" style="width:150px;">
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
<td valign="top"><iframe id="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
</td>
</tr>
</table>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>

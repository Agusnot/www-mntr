<?
	if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND = getdate();
if($Guardar)
{
	if($ConceptoDif && $FechaIni && $Tercero && $Total && $CtaCred)
	{
		$cons="Select Id from Contabilidad.ProgDiferidos Where Compania='$Compania[0]' Order By Id Desc";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Id=$fila[0]+1;
		$cons = "Select Identificacion from Central.Terceros 
		Where (PrimApe || ' ' || SegApe || ' '  || PrimNom  || ' ' || SegNom) like '$Tercero' and Compania = '$Compania[0]'";
		$res = ExQUery($cons);
		$fila = ExFetch($res);
		$IdTercero = $fila[0];
		$cons="Insert into Contabilidad.ProgDiferidos(Compania,Id,Usuario,Fecha,Concepto,Tercero,SaldoIni,NoCuotas,VrDiferidoMensual,Comprobante,CtaCredito,Anio)
		values('$Compania[0]',$Id,'$usuario[0]','$FechaIni','$ConceptoDif','$IdTercero','$Total','$CuotasxDif','$VrCuota','$Comprobante','$CtaCred',$ND[year])";
		$res=ExQuery($cons);echo ExError();	
	}
	else
	{
		?><script language="javascript">alert("Los Datos se encuentran incompletos");</script><?	
	}
}
if($Eliminar)
{
	while(list($cad,$val) = each($Eliminar))
	{
		while(list($cad1,$val1) = each($Eliminar[$cad]))
		{
			$cons = "Delete from Contabilidad.ProgDiferidosxCC 
			Where Compania='$Compania[0]' and ID=$cad and Anio=$cad1";
			$res = ExQuery($cons);
			
			$cons = "Delete from Contabilidad.ProgDiferidos where Compania='$Compania[0]' and Anio = $cad1
			and ID=$cad";
			$res = ExQuery($cons);
		}
		
	}
}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
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
	function AbrirDebito(Id,Concepto,Tercero,SaldoIni,Fecha)
	{
		frames.FrameOpener.location.href="DetProgDiferidos.php?DatNameSID=<? echo $DatNameSID?>&Id="+Id+"&Concepto="+Concepto+"&Tercero="+Tercero+"&SaldoIni="+SaldoIni+"&Fecha="+Fecha;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
	function Validar()
	{
		if(document.FORMA.ConceptoDif.value == ""){alert("Debe Llenar el Campo Concepto");return false;}
		if(document.FORMA.FechaIni.value == ""){alert("Debe Llenar el Campo Fecha");return false;}	
		if(document.FORMA.Tercero.value == ""){alert("Debe Llenar el Campo Tercero");return false;}	
		if(document.FORMA.CtaCred.value == ""){alert("Debe Llenar el Campo Cuenta Credito");return false;}	
		if(document.FORMA.Total.value == "" || document.FORMA.Total.value == "0"){alert("Debe Llenar el Campo Total");return false;}	
	}
</script>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table border="0" cellpadding="3" style="font-family:<? echo $Estilo[8]?>;font-size:11px;font-style:<? echo $Estilo[10]?>">
<tr style="color:<?echo $Estilo[6]?>;font-weight:bold" bgcolor="<?echo $Estilo[1]?>" align="center">
<td>Concepto Diferido</td><td>Fecha Inicial</td><td>Tercero</td><td>Cta Cred</td>
<td>Total</td><td>Cuotas x Dif</td><td>Vr x Cuota</td><td>Cuotas Dif</td><td>Saldo Actual</td><td>Comprobante</td><td>Cta Deb</td></tr>
<?
	$cons="Select Concepto,Tercero,CtaCredito,SaldoIni,NoCuotas,VrDiferidoMensual,Id,Fecha,Anio,Comprobante from Contabilidad.ProgDiferidos where Compania='$Compania[0]' Order By Id";
	$res=ExQuery($cons);
	$X = ExNumRows($res);
	while($fila=ExFetch($res))
	{
		$cons2="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila[1]'";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		$NomTerc="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";

		$cons1="Select sum(VrDiferido),count(Numero) from Contabilidad.ProgDiferidosEjec where Compania='$Compania[0]' and Id=$fila[6]";
		$res1=ExQuery($cons1);
		$fila1=ExFetch($res1);
		$NoCuotasDif=$fila1[1];$VrDif=$fila1[0];
		$SaldoAct=$fila[3]-$VrDif;
		
		echo "<tr><td>$fila[0]</td><td>$fila[7]</td><td>$fila[1] - $NomTerc</td><td>$fila[2]</td>
		<td align='right'>".number_format($fila[3],2)."</td><td align='right'>$fila[4]</td><td align='right'>".number_format($fila[5],2)."</td><td align='right'>";?>
		<a href="#" onclick="open('DetMovDiferidos.php?DatNameSID=<? echo $DatNameSID?>&Id=<?echo $fila[6]?>','','width=600,height=400,scrollbars=yes')">
		<? echo "$NoCuotasDif</a></td><td align='right'>".number_format($SaldoAct,2)."</td>";?>
		<td><? echo $fila[9];?></td>
        <td align="center"><button type="button" onClick="AbrirDebito('<? echo $fila[6]?>','<? echo $fila[0]?>','<? echo $fila[1]?>','<? echo $fila[5]?>','<? echo $fila[7]?>')">
        <img src="/Imgs/b_tblops.png" title="Cargar Cuentas Debito" />
        </button>
        <button type="submit" name="Eliminar[<? echo $fila[6]?>][<? echo $fila[8]?>]" id="Eliminar[<? echo $fila[6]?>][<? echo $fila[8]?>]" 
        title="Eliminar"><img src="/Imgs/b_drop.png"></button></td>
		<?
		echo "</tr>";
	}
?>
<tr>
<td><input type="Text" name="ConceptoDif" onFocus="Ocultar()"></td>
<td><input type="text" name="FechaIni" size="8" onFocus="Ocultar();popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd');" 
        onclick="popUpCalendar(this, FORMA.FechaIni, 'yyyy-mm-dd')"  readonly /></td>
<td><input type="Text" name="Tercero" style="width:160px; font-size:9px;" onKeyDown="ExLetra(this)"
	onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;"
    onKeyUp="ExLetra(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value;"  >
</td>
<td><input type="Text" name="CtaCred" style="width:60px;" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"
	onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CtaCred&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>';"
    onKeyUp="xNumero(this);frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&NoMovimiento=1&ObjCuenta=CtaCred&Tipo=PlanCuentas&Cuenta='+this.value+'&Anio=<? echo $ND[year]?>';" />
</td>
<td><input type="Text" name="Total" style="width:70px;text-align:right;" onChange="VrCuota.value=Total.value/CuotasxDif.value" value="0" 
	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" onFocus="Ocultar()" /></td>
<td><input type="Text" name="CuotasxDif" style="width:70px;text-align:right;" onChange="VrCuota.value=Total.value/CuotasxDif.value" value="1"
	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" onFocus="Ocultar()"></td>
<td><input type="Text" name="VrCuota" style="width:70px;" readonly="yes" onFocus="Ocultar()"></td>
<td colspan="2">
<select name="Comprobante" style="width:160px;" onFocus="Ocultar()" title="Comprobante">
<?
	$cons="Select Comprobante from Contabilidad.Comprobantes Where Compania = '$Compania[0]' order by Comprobante";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>
</select>
</td>
<td align="left"><button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar" /></button></td>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none;border-color:#e5e5e5;" frameborder="1"></iframe>
</body>
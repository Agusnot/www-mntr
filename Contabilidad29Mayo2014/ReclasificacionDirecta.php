<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Reclasificar)
	{
		if($ComprobanteSel){$condAdc1=" and Comprobante='$ComprobanteSel'";}
		if($IdAnt){$condAdc2=" and Identificacion='$IdAnt'";}
		//if($IdAnt){$condAdc2=" and Identificacion='$IdAnt'";}
		if($IdNueva){$condAdc3=", Identificacion='$IdNueva'";}
		$cons="Update Contabilidad.Movimiento set Cuenta='$CuentaNueva' $condAdc3 where Fecha>='$PerIni' and Fecha<='$PerFin' and Cuenta='$CuentaAnt' $condAdc1 $condAdc2";

		$res=ExQuery($cons);echo ExError($res);
		$NumerRows=ExAfectedRows($res);

		?>
		<script language="JavaScript">
			alert("Se afectaron un total de <? echo $NumerRows?> Registros");
		</script>
<?
	}
?>
<script language="javascript">
	function SelCuenta(Anio,SelCuenta)
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Cuentas&Campo='+SelCuenta+'&Formulario=FORMA&Anio='+Anio;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	}
	function SelTercero(Campo)
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo='+Campo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	
	}
	function Validar()
	{
		if(document.FORMA.PerIni.value==""){alert("Debe seleccionar el Periodo Inicial"); return false;}
		if(document.FORMA.PerFin.value==""){alert("Debe seleccionar el Periodo Final"); return false;}
		if(document.FORMA.CuentaAnt.value==""){alert("Debe seleccionar la cuenta a cambiar"); return false;}
		if(document.FORMA.CuentaNueva.value==""){alert("Debe seleccionar nueva la cuenta"); return false;}
	}
</script>
<script language='javascript' src="/calendario/popcalendar.js"></script> 
<body background="/Imgs/Fondo.jpg">
<center><br /><br /><br />
<form name="FORMA" method="post" onSubmit="return Validar()">
<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5"><td colspan="4" align="center"><strong>Reclasificacion de Cuentas Directa</td></tr>
<tr><td>Fecha</td><td><input type="text" name="PerIni" value="<? echo $PerIni?>" readonly style="width:70px;" onClick="popUpCalendar(this, FORMA.PerIni, 'yyyy-mm-dd')" />
<img src="/Imgs/flecha_der.gif" />
<input type="text" name="PerFin" value="<? echo $PerFin?>" style="width:70px;" readonly onClick="popUpCalendar(this, FORMA.PerFin, 'yyyy-mm-dd')" />
<td>Comprobante</td><td><select name="ComprobanteSel">
<option value="">Todos</option>
<?
	$cons="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' Order By Comprobante";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($ComprobanteSel==$fila[0])
		{
			echo "<option selected value='$fila[0]'>$fila[0]</option>";
		}
		else
		{
			echo "<option value='$fila[0]'>$fila[0]</option>";
		}
	}
?>
</select>
</td>
</tr>
<tr><td>Cuenta Anterior</td><td><input type="text" name="CuentaAnt" readonly value="<? echo $CuentaAnt?>" onFocus="SelCuenta('<? echo $Anio?>','CuentaAnt')" /></td><td>ID Anterior</td><td><input readonly type="text" name="IdAnt"  value="<? echo $IdAnt?>" onClick="SelTercero('IdAnt')" /></td></tr>
<tr><td>Cuenta Nueva</td><td><input type="text" name="CuentaNueva" readonly value="<? echo $CuentaNueva?>" onFocus="SelCuenta('<? echo $Anio?>','CuentaNueva')" /></td><td>ID Nueva</td><td><input readonly type="text" name="IdNueva" value="<? echo $IdNueva?>" onClick="SelTercero('IdNueva')" /></td></tr>
<tr><td colspan="4" align="center"><input type="submit" name="Reclasificar" value="Reclasificar"/></td></tr>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

</table>
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>
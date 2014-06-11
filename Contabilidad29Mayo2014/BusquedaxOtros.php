<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
?>
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
</script>
<title>Compuconta Software</title>
<?
	if($Tipo=="Tercero")
	{?>
	<form name="FORMA">
	<table border="1" width="100%" bordercolor="#ffffff" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="<?echo $Estilo[1]?>" style="color:<?echo $Estilo[6]?>;text-align:center;font-weight:bold"><td>Nombre</td><td>Identificacion</td></tr>
	<tr><td><input type="Text" name="Tercero" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Nombre&Nombre='+this.value"></td>
	<td><input type="Text" name="Identificacion"></td></tr>
	<input type="Hidden" name="Detalle">
	</table>
<?	if(!$NuevoMovimiento)
	{?>
	<input type="Button" value="Regresar" onclick="parent.frames.FORMA.<? echo $Campo?>.value=Identificacion.value;CerrarThis();">
<?	}
	else
	{
?>
	<input type="Button" value="Regresar" onclick="parent.frames.NuevoMovimiento.document.FORMA.<? echo $Campo?>.value=Identificacion.value;CerrarThis();">
<?	}?>
	</form>
	<?
	}
	if($Tipo=="Cuentas")
	{?>
	<form name="FORMA">
	<table border="1" width="100%" bordercolor="#ffffff" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="<?echo $Estilo[1]?>" style="color:<?echo $Estilo[6]?>;text-align:center;font-weight:bold"><td>Cuenta</td></tr>
	<tr><td><input type="Text" name="Cuenta" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasGr&Cuenta='+this.value+'&Anio=<?echo $Anio?>'"></td>
	</table>
	<?
	if(!$Formulario){$Formulario="FORMA";}
	?>
	<input type="Button" value="Regresar" onclick="parent.document.<?echo $Formulario?>.<?echo $Campo?>.value=Cuenta.value;CerrarThis();">
	</form>
<?	}
	if($Tipo=="CuentasDetalle")
	{?>
	<form name="FORMA">
	<table border="1" width="100%" bordercolor="#ffffff" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="<?echo $Estilo[1]?>" style="color:<?echo $Estilo[6]?>;text-align:center;font-weight:bold"><td>Cuenta</td></tr>
	<tr><td><input type="Text" name="Cuenta" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanCuentasGr&Cuenta='+this.value+'&Anio=<?echo $Anio?>'"></td>
	</table>
	<?
	if(!$Formulario){$Formulario="FORMA";}
	?>
	<input type="Button" value="Regresar" onclick="parent.document.<?echo $Formulario?>.<?echo $Campo?>.value=Cuenta.value;CerrarThis();">
	</form>
<?	}
	if($Tipo=="Comprobante")
	{?>
	<form name="FORMA">
	<table border="1" width="100%" bordercolor="#ffffff" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="<?echo $Estilo[1]?>" style="color:<?echo $Estilo[6]?>;text-align:center;font-weight:bold"><td>Cuenta</td></tr>
	<tr><td><input type="Text" name="Comprobante" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Comprobante&Comprobante='+this.value"></td>
	</table>
	<input type="Button" value="Regresar" onclick="opener.document.FORMA.<?echo $Campo?>.value=Comprobante.value;window.close();">
	</form>
<?	}
	if($Tipo=="CodigoExogena")
	{?>
	<form name="FORMA">
	<table border="1" width="100%" bordercolor="#ffffff" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
	<tr bgcolor="<?echo $Estilo[1]?>" style="color:<?echo $Estilo[6]?>;text-align:center;font-weight:bold"><td>Cuenta</td></tr>
	<tr><td><input type="Text" name="Codigo" style="width:420px;" onkeyup="frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CodigoExogena&Codigo='+this.value"></td>
	</table>
	<input type="Button" value="Regresar" onclick="parent.document.FORMA.<?echo $Campo?>.value=Codigo.value;CerrarThis();">
	</form>
<?	}?>

<iframe width="100%" style="height:200px" name="Busquedas" id="Busquedas" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&" frameborder="0"></iframe> 

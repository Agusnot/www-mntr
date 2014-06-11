<?
		if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$ND=getdate();
?>
<script language="javascript">
	function SelTercero()
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo=Tercero';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	
	}

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

</script>
<center>
<form name="FORMA" method="post">
<table border="1" bordercolor="#666699" cellpadding="6"  bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr><td>Todos los Terceros</td><td><select name="Todos">
<option value="No">No</option>
<option value="Si">Si</option>
</select></td>
<td>Seleccione Tercero</td><td><input type="Text" name="Tercero" onclick="SelTercero()"></td></tr>
<tr><td>Mensaje</td><td colspan="3"><textarea style="width:450px;height:200px;" name="Mensaje">
La Divisi&oacute;n Administrativa y Financiera con el fin de dar cumplimiento a las disposiciones vigentes sobre la retencion en la fuente, certifica que durante el periodo 
comprendido entre: Enero 01 y Diciembre 31 del a&ntilde;o gravable de Anio
</textarea></td></tr>
<tr><td>Cuenta Inicial</td><td><input type="Text" name="CtaInicial" onclick="SelCuenta('<? echo $Anio?>','CtaInicial')"></td>
<td>Cuenta Final</td><td><input type="Text" name="CtaFinal" onclick="SelCuenta('<? echo $Anio?>','CtaFinal')"></td>
</tr>
</table>
<br><input type="Button" name="Generar" value="Generar Certificado" onclick="open('ImpCertificadoReteFte.php?DatNameSID=<? echo $DatNameSID?>&Tercero='+Tercero.value+'&CtaInicial='+CtaInicial.value+'&CtaFinal='+CtaFinal.value+'&Mensaje='+Mensaje.value+'&Anio=<?echo $Anio?>&Todos='+Todos.value,'','width=800,height=600,scrollbars=yes')">
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>

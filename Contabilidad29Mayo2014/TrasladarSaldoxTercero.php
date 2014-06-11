<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Iniciar)
	{
		$cons="Update $BaseDatos.Movimiento set Identificacion='$IdNueva' where Identificacion='$IdAnterior'";
		$res=ExQuery($cons);echo ExError($res);
		$NumerRows=ExAfectedRows($res);
		?>
		<script language="JavaScript">
			alert("Se afectaron un total de <?echo $NumerRows?> Registros");
			location.href='/ModOpciones.php?DatNameSID=<? echo $DatNameSID?>';
		</script>
<?
	}
?>
<script language="javascript">
	function SelTercero(Campo)
	{
		frames.FrameOpener.location.href='/Contabilidad/BusquedaxOtros.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Tercero&Campo='+Campo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='5px';
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='390';
	
	}
</script>

<title>Mentor Software</title>
<body background="/Imgs/Fondo.jpg">
<br><br><br>
<center>
<form name="FORMA" onSubmit="if(confirm('Desea trasladar saldos entre los terceros seleccionados?')==true)">
<table cellpadding="8"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr><td>Identificacion Anterior</td><td><input readonly="yes" type="Text" name="IdAnterior" onClick="SelTercero(this.name)"></td></tr>
<tr><td>Identificacion Nueva</td><td><input readonly="yes" type="Text" name="IdNueva" onClick="SelTercero(this.name)"></td></tr>
<tr><td colspan="2" align="center"><input type="Submit" name="Iniciar" value="Trasladar Saldos"></td></tr>
<input type="Hidden" name="BaseDatos" value="<?echo $BaseDatos?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</table>
</form></center>
<iframe id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1"></iframe>
</body>

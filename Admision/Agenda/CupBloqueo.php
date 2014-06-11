<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Agregar)
	{
		$cons="update salud.dispoconsexterna set cuppermitido='$Agregar' where compania='$Compania[0]' and horaini='$HoraIni' and minsinicio='$MinIni'
		and usuario='$Profecional' and fecha='$Fecha'";
		$res=ExQuery($cons);
		?>
        <script language="javascript">
			parent.location.href='BoqxCup.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&IdHorario=<? echo $IdHorario?>&Ver=1';
		</script>
        <?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener2').style.position='absolute';
		parent.document.getElementById('FrameOpener2').style.top='1px';
		parent.document.getElementById('FrameOpener2').style.left='1px';
		parent.document.getElementById('FrameOpener2').style.width='1';
		parent.document.getElementById('FrameOpener2').style.height='1';
		parent.document.getElementById('FrameOpener2').style.display='none';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' cellpadding="4">
	<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Codigo</td>
   		<td><input type="text" name="Codigo" style="width:70" value="<? echo $Codigo?>"
        onKeyUp="xLetra(this);frames.NewCupBloq.location.href='NewCupBloq.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HoraIni=<? echo $HoraIni?>&MinIni=<? echo $MinIni?>&Nombre='+Nombre.value"
        onKeyDown="xLetra(this)"/></td>
   		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Nombre</td>		
       <td><input type="text" name="Nombre" style="width:630" value="<? echo $Nombre?>"
        onkeyup="xLetra(this);frames.NewCupBloq.location.href='NewCupBloq.php?DatNameSID=<? echo $DatNameSID?>&Nit='+Codigo.value+'&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HoraIni=<? echo $HoraIni?>&MinIni=<? echo $MinIni?>&Nombre='+this.value" onKeyDown="xLetra(this)" /></td>               
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe frameborder="0" id="NewCupBloq" src="NewCupBloq.php?DatNameSID=<? echo $DatNameSID?>" width="100%" height="85%"></iframe>
<?
	if($Cargo && $Nombre)
	{
		?><script language="javascript">
        	frames.NewCupBloq.location.href="NewCupBloq.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HoraIni=<? echo $HoraIni?>&MinIni=<? echo $MinIni?>";
        </script><?
	}
?>
</table>
</form>    
</body>
</html>
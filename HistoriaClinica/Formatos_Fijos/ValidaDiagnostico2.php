<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
?>
<script language="javascript" src="/Funciones.js"></script>
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
	function ValidaDiagnostico(Objeto1,Objeto2,NameCod,NameNom)
	{		
		frames.FrameOpener3.location.href="ValidaDiagnostico.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD2=<? echo $TMPCOD2?>&CodCup=<? echo $CodCup?>&Codigo="+Objeto1.value+"&Nombre="+Objeto2.value+"&NameCod="+NameCod+"&NameNom="+NameNom;
		document.getElementById('FrameOpener3').style.position='absolute';
		document.getElementById('FrameOpener3').style.top='80px';
		document.getElementById('FrameOpener3').style.left='0px';
		document.getElementById('FrameOpener3').style.display='';
		document.getElementById('FrameOpener3').style.width='700px';
		document.getElementById('FrameOpener3').style.height='300px';
	}
</script>	
<?
if($CodDx){?>
	<script language="javascript">		
			parent.document.getElementById('<? echo $NameCod ?>').value="<? echo $CodDx?>";
			parent.document.getElementById('<? echo $NameNom ?>').value="<? echo $NomDx?>";
			CerrarThis();
	</script>
<?
}?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.CodDiagnostico.focus();">
<form name="FORMA" method="post">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td colspan="2" align="right"><input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana"></td></tr>
<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Codigo</td><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Nombre</td></tr>
<tr>
	<td><input style="width:80" type="text" name="CodDiagnostico" onFocus="ValidaDiagnostico(this,NomDiagnostico,'<? echo $NameCod?>','<? echo $NameNom?>')"  onKeyUp="ValidaDiagnostico(this,NomDiagnostico,'<? echo $NameCod?>','<? echo $NameNom?>');xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $CodDiagnostico?>"></td>
	<td><input type="text" style="width:600" name="NomDiagnostico" onFocus="ValidaDiagnostico(CodDiagnostico,this,'<? echo $NameCod?>','<? echo $NameNom?>')"  onKeyUp="ValidaDiagnostico(CodDiagnostico,this,'<? echo $NameCod?>','<? echo $NameNom?>');xLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $NomDiagnostico?>"></td>
</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="TMPCOD2" value="<? echo $TMPCOD2?>">
</form>
<iframe scrolling="yes" id="FrameOpener3" name="FrameOpener3" style="display:none" frameborder="0" height="1" ></iframe>    
</body>
</html>

<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	function Cups()
	{
		st = parent.document.body.scrollTop;
		parent.frames.FrameOpener.location.href="NewFactura.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Tipo=Cup&CedPac=<? echo $CedPac?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&NumServ=<? echo $NumServ?>&NoFac=<? echo $NoFac?>&FechFin=<? echo $FechFin?>";
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=30+st;
		parent.document.getElementById('FrameOpener').style.left=10;
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='1000';
		parent.document.getElementById('FrameOpener').style.height='500px';
	}
	function Meds()
	{
		st = parent.document.body.scrollTop;
		parent.frames.FrameOpener.location.href="NewFactura.php?DatNameSID=<? echo $DatNameSID?>&TMPCOD=<? echo $TMPCOD?>&Tipo=Medicamentos&CedPac=<? echo $CedPac?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&NoContrato=<? echo $NoContrato?>&NumServ=<? echo $NumServ?>&NoFac=<? echo $NoFac?>&FechFin=<? echo $FechFin?>";
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top=30+st;
		parent.document.getElementById('FrameOpener').style.left=10;
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='1000';
		parent.document.getElementById('FrameOpener').style.height='500px';
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;' align="center"> 
	<tr>
    	<td><input type="radio" name="Cup" onClick="Cups()">CUPS</td>
    </tr>
    <tr>
    	<td><input type="radio" name="Medicamentos" onClick="Meds()">Medicamentos</td>
    </tr>
</table>
</form>
</body>
</html>
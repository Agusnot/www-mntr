<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
$Year="$ND[year]";
//--------------------------------------
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function AsistBusqueda(Valor)
	{
		parent(1).frames.FrameOpener.location.href="AsistenteCC.php?DatNameSID=<? echo $DatNameSID?>&Valor="+Valor.value+;
		parent(1).document.getElementById('FrameOpener').style.position='absolute';
		parent(1).document.getElementById('FrameOpener').style.top='10px';
		parent(1).document.getElementById('FrameOpener').style.right='10px';
		parent(1).document.getElementById('FrameOpener').style.display='';
		parent(1).document.getElementById('FrameOpener').style.width='300px';
		parent(1).document.getElementById('FrameOpener').style.height='450px';
	}
    function Ocultar()
	{
		parent(1).document.getElementById('FrameOpener').style.display='none';
		parent(1).document.getElementById('FrameOpener').style.width='0';
		parent(1).document.getElementById('FrameOpener').style.height='0';
	}
</script>	
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" target="Abajo" action="ResultBusHojaVida.php" >
<table border="1" bordercolor="white" bgcolor="#e5e5e5"  style="font-family:Tahoma;font-size:13" align="center">
	<tr  bgcolor="#666699"style="color:white" align="center"><td colspan="2">Distribucion</td></tr>
	<tr style="text-align:center;">
    <td >Centros de Costos</td>
    <td >Porcentaje</td>
    </tr>
    <tr>
    <td><input type="Text" name="CC" style="width:90px;" onFocus="AsistBusqueda(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this)"></td>
    <td><input type="Text" name="Porcentaje" style="width:90px;" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" onFocus="Ocultar()"></td>
    </tr>
    <tr><td style="font-size:10" colspan="2">NOTA: la suma de los porcentajes<br />no debe exceder 100%</td>
    </tr>
    <tr align="center"><td colspan="2"><input type="submit" name="Guardar" value="Guardar"/></td></tr>
</table>
<table>
	<?
	$cons="select cc,porcentaje from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and anio='$Year'";
	echo $cons;
	?>
</table>
</form>
</body>
</html>
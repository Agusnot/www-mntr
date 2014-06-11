<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Year="$ND[year]";
	//echo $Year;
?>
<html>
<head>
<meta http-equiv="Content-Type"/>
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
	function Asignar(V)
	{
		//alert("entro");
		//parent.frames.FrameOpener.document.FORMA.CC.value=V;
		parent.frames.Info.document.FORMA.CC.value=V;
		CerrarThis();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
<br>
<table border="0" bordercolor="#e5e5e5"  style='font : normal normal small-caps 13px Tahoma;' width="100%">
<?
if(1==1)
{
	$Valor=trim($Valor);
	
	$cons="select centrocostos from central.centroscosto where compania='$Compania[0]' and tipo='Detalle' and anio=$Year and centrocostos ilike '$Valor%' order by centrocostos";
	//echo $cons;
?>
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td>CENTROS DE COSTOS</td>
</tr>
<?	
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="Asignar('<? echo $fila[0]?>')">
            	<input type="hidden" name="V" value="<? echo $fila[1]?>">
        		<td><? echo $fila[0]?></td>
	        </tr>
<?		}
	}
	else
	{?>
		<tr><td bgcolor="#e5e5e5" align="center" style="font-weight:bold">No Hay Registros Coincidentes</td></tr>
<?	}
}
?>
</table>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
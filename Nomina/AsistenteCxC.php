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
	function Asignar(V,T)
	{
		//alert("entro");
		//parent.frames.FrameOpener.document.FORMA.CC.value=V;
		switch(T)
		{
			case 'Debito'	:	parent.document.FORMA.Debito.value=V; break;
			case 'Credito'	:	parent.document.FORMA.Credito.value=V; break;
			case 'CCDebito'	:	parent.document.FORMA.CCDebito.value=V; break;
								break;
		}
//		parent.document.FORMA.Debito.value=V;
		CerrarThis();
	}
</script>
</head>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post">
<br>
<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de B&uacute;squeda</center></td></tr>
<tr><td <? if($Reporteador){?> valign="top"<? }?>><?
if(1==1)
{
	$Valor=trim($Valor);
	//echo $Cuenta;
	switch($Cuenta)
	{
		case "Debito"	:	$cons="select cuenta,nombre from contabilidad.plancuentas where compania='$Compania[0]' and anio=$Year and naturaleza='Debito' and tipo='Detalle' and cuenta ilike '$Valor%' order by cuenta"; break;
		case 'Credito'	:	$cons="select cuenta,nombre from contabilidad.plancuentas where compania='$Compania[0]' and anio=$Year and naturaleza='Credito' and tipo='Detalle' and cuenta ilike '$Valor%' order by cuenta"; break;
	}
?>
<!--<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
<td>CUENTAS <? echo strtoupper($Cuenta);?></td>
</tr>-->
<?
	$res=ExQuery($cons);
//	echo ExError();
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="Asignar('<? echo $fila[0]?>','<? echo $Cuenta?>')">
            	<input type="hidden" name="V" value="<? echo $fila[0]?>">
        		<td><? echo $fila[0]." - ".$fila[1]?></td>
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
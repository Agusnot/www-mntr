<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
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
	function Asignar(V,T,C)
	{
		switch(T)
		{
			case 'Identificacion'	:	parent.parent(0).document.FORMA.Identificacion.value=V; break;
			case 'Cargo'		:	parent.parent(0).document.FORMA.Cargo.value=C; parent.parent(0).document.FORMA.Codigo.value=V; break;
								break;
		}
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
	switch($Tipo)
	{
		case 'Identificacion'	:	$cons="select identificacion,identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and identificacion!='Sin Identificacion' and (tipo='Empleado' or regimen='Empleado') and identificacion ilike '$Valor%'"; break;
		case 'Cargo'	 	:	$cons="select codigo,cargo from nomina.cargos where compania='$Compania[0]' and cargo ilike '$Valor%'"; break;

	}
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" onClick="Asignar('<? echo $fila[0]?>','<? echo $Tipo?>','<? echo $fila[1]?>')">
            	<input type="hidden" name="Med" value="<? echo $fila[1]?>">
        		<td><? echo $fila[1]; if($Tipo=='Identificacion'){echo " - $fila[2] $fila[3] $fila[4] $fila[5]";}?></td>
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
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$Year="$ND[year]";
	if($Guardar)
	{
		//echo $Concepto;
		$cons="Delete from Nomina.CuentasCentcostos where Compania='$Compania[0]' and Concepto='$Concepto' and Clase='$Movimiento' and Naturaleza='$Naturaleza'";
		$res=ExQuery($cons);
		while( list($cad,$val) = each($Cuenta))
		{
//			echo $cad." -- > ".$val."<br>";
			if($val)
			{
				$cons="INSERT INTO nomina.cuentascentcostos(
				compania, naturaleza, concepto, clase, anio, centrocosto, cuenta)
				VALUES ('$Compania[0]', '$Naturaleza', '$Concepto', '$Movimiento', $Year, '$cad', '$val');";
				//echo $cons;
				$res=ExQuery($cons);
			}
		}
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type"/>
<script language="javascript">
	function AsistBusqueda(Campo)
	{
		parent.document.FrameOpener.location.href="Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=CuentasFrame&Cuenta="+Campo.value+"&Campo="+Campo.name;
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='10px';
		parent.document.getElementById('FrameOpener').style.right='10px';
		parent.document.getElementById('FrameOpener').style.display='';
		parent.document.getElementById('FrameOpener').style.width='300px';
		parent.document.getElementById('FrameOpener').style.height='450px';
	}
function Ocultar()
	{
//		parent.frames.FrameOpener.style.display='';
		parent.document.getElementById('FrameOpener').style.display='none';
		parent.document.getElementById('FrameOpener').style.width='0';
		parent.document.getElementById('FrameOpener').style.height='0';
	}
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
								break;
		}
//		parent.document.FORMA.Debito.value=V;
		CerrarThis();
	}
</script>
<body background="/Imgs/Fondo.jpg" onFocus="Ocultar();">
<!--<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">-->
<form name="FORMA" method="post">
<br>

<table border="0" bordercolor="#e5e5e5"  style='font : normal normal small-caps 13px Tahoma;' width="100%">
<?
if(1==1)
{
	$Valor=trim($Valor);
	//echo $Cuenta;
?>
	<tr align="center" style="font:bold"><td colspan="2">CENTRO DE COSTOS</td>
    </tr>
<?	
	$cons="select centrocostos,codigo from central.centroscosto where compania='$Compania[0]' and tipo='Detalle' and anio=$Year ";
	$res=ExQuery($cons);
//	echo ExError();
	if(ExNumRows($res)>0)
		{
		while($fila=ExFetch($res)){
			
			$cons2="Select Cuenta from nomina.cuentascentcostos where Compania='$Compania[0]' and Naturaleza='$Naturaleza' and Concepto='$Concepto'
			and Clase='$Movimiento' and CentroCosto='$fila[1]'";
			$res2=ExQuery($cons2);
			$fila2=ExFetch($res2);
			$i++;
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand">
        		<td><? echo $fila[0]?></td>
                <td><input type="text" id="Cuenta_<? echo $i?>" value="<? echo $fila2[0]?>" name="Cuenta[<? echo $fila[1]?>]" onFocus="AsistBusqueda(this)" onKeyDown="AsistBusqueda(this)" onKeyUp="AsistBusqueda(this)"/></td>
	        </tr>
<?		}
	}
	else
	{?>
		<tr><td bgcolor="#e5e5e5" align="center" style="font-weight:bold">No Hay Registros Coincidentes</td></tr>
<?	}
}
//echo $Movimiento;
?>
</table>
<input type="hidden" name="Naturaleza" value="<? echo $Naturaleza?>">
<input type="hidden" name="Concepto" value="<? echo $Concepto?>">
<input type="hidden" name="Movimiento" value="<? echo $Movimiento?>">

<center><input type="submit" name="Guardar" value="Guardar"/></center>
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
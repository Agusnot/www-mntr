<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
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
</script>
<?
	if($Registrar)
	{
		if($Registra)
		{
			while (list($val,$cad) = each ($Registra)) 
			{
				$cons = "Update Infraestructura.CodElementos set TMPCOD = '$TMPCOD' Where Compania='$Compania[0]'
				and Tipo='Compras' and EstadoOrdenCompra='Aprobado' and NumeroOrdenCompra='$val'";
				$res = ExQuery($cons);
				?>
				<script language="javascript">
					parent.document.FORMA.Detalle.value = "<? echo $Detalle[$cad]?>";
					parent.frames.NuevoMovimiento.location.href="DetNuevoMovimientos.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase;?>&Tipo=Compras&TMPCOD=<? echo $TMPCOD?>&Anio=<? echo $Anio?>
					&Mes="+parent.document.FORMA.Mes.value+"&Dia="+parent.document.FORMA.Dia.value;
					CerrarThis();
				</script>
				<?
			}
		}
		else{ ?><font color="#FF0000"><em>No ha seleccionado ninguna solicitud</em></font><? }	
	}
	
	$cons = "Select NumeroOrdenCompra,Tipo,FechaOrdenCompra,sum(CostoInicial)+sum(VrIVA),Detalle from InfraEstructura.CodElementos where Compania='$Compania[0]' and 
	EstadoOrdenCompra='Aprobado' and Clase='$Clase' and Cedula='$Identificacion' and Tipo='Compras' and NumeroCompra IS NULL
        Group By NumeroOrdenCompra,Tipo,FechaOrdenCompra,Detalle";
	$res = ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	
?>
<script language="javascript">
	function Mostrar(num,Anio)
	{
		open("/Informes/Almacen/Formatos/OrdenCompra.php?Comprobante=Orden de Compra&AlmacenPpal=<? echo $AlmacenPpal?>&Anio="+Anio+"&Numero="+num,'','width=600,height=400,scrollbars=yes');
	}
</script>

<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" width="100%" bordercolor="#e5e5e5">

	<tr>
    	<td colspan="6" align="center" bgcolor="#e5e5e5" style="font-weight:bold; font-size:12px">Ordenes de Compra Aprobadas</td>
    </tr>
    <tr bgcolor="#e5e5e5">
    	<td>&nbsp;</td><td>Numero</td><td>Comprobante</td><td>Fecha</td><td>Total</td>
    </tr>
<?

	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$xn++;
		?>
		<tr><td>
        	<input type="checkbox" name="Registra[<? echo $fila[0]?>]" value="<? echo $fila[0]?>" title="A&ntilde;adir Esta Orden al Registro" />
        	<input type="hidden" name="Detalle[<? echo $fila[0]?>]" value="<? echo $fila[4];?>" />
            </td>
        	<td style="cursor:hand" 
        	onMouseOver="this.bgColor='#AAD4FF'" 
            onmouseout="this.bgColor='#FFFFFF'" 
            onclick="Mostrar('<? echo $fila[0]?>','<? echo $Anio?>')" 
            align="center" title="Ver Esta Orden">
        <? echo "$fila[0]</td><td>$fila[1]</td><td align='center'>".$fila[2]."</td><td align='right'>".number_format($fila[3],2)."</td>";?>
		</td>
        </tr>
        <tr><td colspan="6" align="right">
        <table border="1" align="right" style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="white" width="90%">
        <tr bgcolor="#e5e5e5"><td width="5%">AutoId</td><td>Nombre</td><td>Caracteristicas</td><td>Marca</td><td>Modelo</td><td width="20%">Valor</td></tr>
        <?
        	$cons9 = "Select AutoId,Nombre,CostoInicial+VrIva,Marca,Modelo,Caracteristicas 
			from InfraEstructura.CodElementos Where Compania='$Compania[0]' and NumeroOrdenCompra='$fila[0]'
			and Tipo = 'Compras' and EstadoOrdenCompra='Aprobado'";
			$res9 = ExQuery($cons9);
			while($fila9 = ExFetch($res9))
			{
				echo "<tr><td>$fila9[0]</td><td>$fila9[1]</td><td>$fila9[5]</td><td>$fila9[3]</td><td>$fila9[4]</td><td align='right'>".number_format($fila9[2],2)."</td></tr>";	
			}
		?>
        </table>
        </td></tr>
<?	}
?>
</table>
<input type="submit" name="Registrar" value="Registrar" />
<input type="button" value="Cancelar" onClick="CerrarThis()">
</form>


<body background="/Imgs/Fondo.jpg">
</body>
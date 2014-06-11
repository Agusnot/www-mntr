<?php	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		while(list($cad,$val)=each($AprobarOC))
		{
			if($val!=""){
			if($val=="Aprobar"){$T = " ,Tipo = 'Compras' ";$ET = "Aprobado";}
			elseif($val=="Rechazar"){;$ET="Rechazado";}
			$cons="Update InfraEstructura.CodElementos set EstadoOrdenCompra='$ET', FechaMod='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]',
			EstadoOrdenCompraX = '$usuario[0]' $T
			where Compania='$Compania[0]' and Tipo='Orden Compra' and NumeroOrdenCompra='$cad'";
			$res=ExQuery($cons);}
		}
	}
?>
<script language="javascript">
	function Mostrar(x)
	{open("/Informes/Infraestructura/Formatos/OrdenCompra.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Orden Compra&Numero="+x,'','width=600,height=600,scrollbars=yes');}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Fecha</td><td>Numero</td><td>Comprobante</td><td>Detalle</td><td colspan>Tercero</td><td>Valor</td><td>Aprobar</td>
    </tr>
<?
	$cons="Select FechaOrdenCompra,NumeroOrdenCompra,CodElementos.Tipo,Detalle,PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,
	Sum(CostoInicial)+sum(VrIVA),EstadoOrdenCompra
	from InfraEstructura.CodElementos,Central.Terceros where CodElementos.Cedula=Terceros.Identificacion and Terceros.Compania='$Compania[0]' 
	and CodElementos.Compania='$Compania[0]' and
	CodElementos.Tipo='Orden Compra' and EstadoOrdenCompra = 'Solicitado' Group by NumeroOrdenCompra,FechaOrdenCompra,CodElementos.Tipo,Detalle,
	PrimApe,SegApe,PrimNom,SegNom,CodElementos.Cedula,EstadoOrdenCompra Order By NumeroOrdenCompra";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		while($fila=ExFetch($res))
		{
			$AnioOrden = substr($fila[0],0,4);
			if($fila[10]=="Solicitado")
			{
				echo "<tr><td align='center'>$fila[0]</td>";
				?><td style="cursor:hand" title="Ver Orden de Compra" 
        		onMouseOver="this.bgColor='#AAD4FF'" 
            	onmouseout="this.bgColor='#FFFFFF'" 
            	onclick="Mostrar('<? echo $fila[1]?>')" 
            	align="center">
            	<?
            	echo "$fila[1]</td>
				<td>$fila[2]</td><td>$fila[3]</td><td title='C.C.$fila[8]'>$fila[4] $fila[5] $fila[6] $fila[7]</td>";
				?>
                <td align='right'><? echo number_format($fila[9],2) ?></td>
				<td align="center"><select name="AprobarOC[<? echo $fila[1]?>]">
                <option></option>
            		<option value="Rechazar">Rechazar</option>
                	<option value="Aprobar">Aprobar</option>
            	</select></td>
				</tr>		
				<?
			}
		}
		?>
		</table>
		<input type="submit" name="Guardar" value="Guardar" />
		<?	
	}
	else
	{
		echo "<font color='red'><em>No hay Solicitudes Pendientes de Aprobacion</em></font>";	
	}
	?>
</form>
</body>
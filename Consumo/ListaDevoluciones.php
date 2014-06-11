<? 
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
$ND=getdate();
if(!$AnioI){$AnioI=$ND[year];}
if(!$MesI){$MesI = $ND[mon];}
$cons = "Select NumDias from Central.Meses Where Numero = $MesI";
$res = ExQuery($cons);
$fila = ExFetch($res);
$NumDias = $fila[0];

$cons = "Select Mes From Central.CierreXPeriodos Where Compania='$Compania[0]' and Modulo='Consumo' and Anio=$AnioI and Mes=$MesI";
	$res = ExQuery($cons);
	if(ExNumRows($res)==1)
	{	
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.title="PERIODO CERRADO, No se pueden Ingresar Nuevos Registros";
		parent(0).document.FORMA.Nuevo.disabled=true;
		</script>
		<?
	}
	else
	{
		?><script language="javascript">
		parent(0).document.FORMA.Nuevo.title="";
		parent(0).document.FORMA.Nuevo.disabled=false;
		</script>
		<?
	}

if($Eliminar)
{
	$Valores = explode("|",$ValoresE);
	$cons = "Update Consumo.Movimiento set Estado = 'AN' Where Compania='$Compania[0]' and Fecha='$Valores[0]'
	and Numero='$Valores[1]' and Cedula = '$Valores[2]' and Comprobante = 'Devoluciones'";
	$res = ExQuery($cons);
}$Comprobante = "Devoluciones";
$cons = "Select Fecha,Numero,Cedula,PrimApe,SegApe,PrimNom,SegNom,Estado from Consumo.Movimiento,Central.Terceros 
Where Movimiento.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
and Movimiento.Cedula = Terceros.Identificacion and Comprobante = 'Devoluciones'
and Fecha>='$AnioI-$MesI-01' and Fecha<='$AnioI-$MesI-$NumDias' group by Fecha,Numero,Cedula,PrimApe,SegApe,PrimNom,SegNom,Estado order by Fecha DESC";
$res = ExQuery($cons);			
if(ExNumRows($res)>0)
{
	?>
	<html><head><title></title>
	<script language="javascript">
	function VerImprimible(Numero,Comprobante,AlmacenPpal,NoFactura)
	{
		<? 
			$cons000 = "Select Formato from Consumo.Comprobantes where Compania='$Compania[0]' and AlmacenPpal = '$AlmacenPpal' and Comprobante = '$Comprobante'";
			$res000 = ExQuery($cons000);
			$fila000 = ExFetch($res000);
			$Archivo = $fila000[0];
		?>
		open("/Informes/Almacen/<? echo $Archivo?>?DatNameSID=<? echo $DatNameSID?>&NoFactura="+NoFactura+"&Numero="+Numero+"&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&Anio=<? echo $AnioI?>","","width=700,height=500,scrollbars=yes")
	}</script>
	</head><body>
    <form name="FORMA" method="post">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
    <input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
    <input type="hidden" name="Anio" value="<? echo $AnioI?>" />
    <input type="hidden" name="Mes" value="<? echo $MesI?>" />
    <input type="hidden" name="AnioI" value="<? echo $AnioI?>" />
    <input type="hidden" name="MesI" value="<? echo $MesI?>" />
    <input type="hidden" name="ValoresE" />
	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' width="100%">
    	<tr bgcolor="<? echo $Estilo[1]?>" style="color:#FFFFFF; font-weight:bold" align="center">
        	<td>N&uacute;mero</td><td>Fecha</td><td>Tercero</td><td colspan="2">Buscar</td>
        </tr>
        <tr align="center">
        	<td><? echo "$AnioI-$MesI-"?><input type="text" name="DiaDev" size="2" /></td>
            <td><input type="text" name="NumeroDev" /></td>
            <td><input type="text" name="TerceroDev" /></td>
            <td colspan="2"><button type="submit" name="Buscar" title="Buscar">
            	<img src="/Imgs/b_search.png" />
            </button></td>
        </tr>
        <?
        while($fila = ExFetch($res))
		{
			if($fila[7]=="AN"){$Est="text-decoration:underline; color:red";}else{$Est="";}
			?><tr align="center" style="<? echo $Est?>">
            	<td><? echo $fila[1]?></td><td><? echo $fila[0]?></td><td><? echo "$fila[3] $fila[4] $fila[5] $fila[6] - $fila[2]"?></td>
                <? if($fila[7]!="AN")
				{
				?>
				<td width="18px">
            <img style="cursor:hand;" border="0" onClick="VerImprimible('<? echo $fila[1]?>','<? echo $Comprobante ?>','<? echo $AlmacenPpal?>','<? echo $fila[1]?>')" title="Ver la Versión imprimible" src="/Imgs/b_print.png" /></td>
				<td width="16px"><button type="submit" name="Editar" title="Editar" style="border:0; cursor:hand" 
                onclick="ValoresE.value='<? echo "$fila[0]|$fila[1]|$fila[2]|$fila[3] $fila[4] $fila[5] $fila[6]"?>';
                document.FORMA.action='NuevaDevolucion.php'">
                	<img src="/Imgs/b_edit.png" />
                </button></td>
                <td width="16px">
                <button type="submit" name="Eliminar" title="Eliminar" style="border:0; cursor:hand"
                onclick="ValoresE.value='<? echo "$fila[0]|$fila[1]|$fila[2]|$fila[3] $fila[4] $fila[5] $fila[6]"?>';">
                	<img src="/Imgs/b_drop.png" />
                </button></td>
				<?
				}?>
            </tr><?	
		}
		?>
    </table>
    </form></body></html>
	<?
}
?>
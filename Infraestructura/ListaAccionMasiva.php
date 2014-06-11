<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Tipo=="Bajas"){$Tabla = "Bajas";}
	if($Tipo=="Traslados"){$Tabla = "Traslados";}
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.$Tabla Where Compania='$Compania[0]' and TMPCOD='$TMPCOD' and AutoId=$AutoId";
		$res = ExQuery($cons);	
	}
	$cons = "Select $Tabla.AutoId,CodElementos.Codigo,Nombre,Caracteristicas,Modelo,Serie,Marca,Grupo 
	from Infraestructura.$Tabla,Infraestructura.CodElementos Where $Tabla.AutoId=CodElementos.AutoId and $Tabla.Compania='$Compania[0]' 
	and CodElementos.Compania='$Compania[0]' and $Tabla.TMPCOD='$TMPCOD' order by Grupo";
	$res = ExQuery($cons);
	?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<div align="right">
	<input type="button" name="Agregar" value="Agregar" 
    onclick="location.href='DetAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&TMPCOD=<? echo $TMPCOD?>&Existe=1';
    		parent.document.FORMA.Guardar.disabled = true" />
</div>
<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<?
while($fila = ExFetch($res))
{
    $cons1 = "Select distinct Ubicaciones.CentroCostos, PrimNom, SegNom, PrimApe, SegApe, FechaIni, FechaFin, CentrosCosto.CentroCostos,Responsable 
    From Central.Terceros,Infraestructura.Ubicaciones,Central.CentrosCosto
    Where Ubicaciones.Compania='$Compania[0]' and Terceros.Compania='$Compania[0]' and AutoId=$fila[0] and Terceros.Identificacion = Ubicaciones.Responsable
    and CentrosCosto.Codigo = Ubicaciones.CentroCostos and CentrosCosto.Compania = '$Compania[0]' and CentrosCosto.Anio = $ND[year]";
    $res1 = ExQuery($cons1);
    $fila1 = ExFetch($res1);
    if($fila[7] != $GrupAnt)
    {
        ?>
        <tr bgcolor='<? echo $Estilo[1]?>'  style='color:white;font-weight:bold;'><td colspan='15' align='center'>
        <? echo $fila[7] ?></td></tr><?
        
    }
    ?>
    <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
    <?
    echo "<td>$fila[1]</td><td>$fila[2] $fila[3] $fila[6] $fila[4] $fila[5]</td>
    <td>$fila1[3] $fila1[4] $fila1[1] $fila1[2]</td>
    <td>$fila1[0] - $fila1[7]</td>";
    ?><td><img src="/Imgs/b_drop.png" style="cursor:hand" 
    onclick="if(confirm('Desea Eliminar el Registro?')){location.href='ListaAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Tipo=<? echo $Tipo?>&AutoId=<? echo $fila[0]?>&TMPCOD=<? echo $TMPCOD?>';}" /></td><?
    echo "</tr>";
    $GrupAnt = $fila[7];
}
?>
</table>
</form>
</body>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Delete from Central.CargosxCompania where Compania='$Entidad' and Categoria='$Categoria' and Cargo='$Cargo' and Identificacion='$Identificacion'";
		$res=ExQuery($cons);
	}
	if($Entidad)
	{
		$cons="Select Categoria, Cargo, Identificacion, Nombre, FechaIni, FechaFin from Central.CargosxCompania where Compania='$Entidad' 
		group by Categoria,Cargo,Identificacion,Nombre,FechaIni,FechaFin order by Categoria, Cargo, Identificacion";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$MatCargos[$fila[0]][$fila[1]][$fila[2]]=array($fila[0],$fila[1],$fila[2],$fila[3],$fila[4],$fila[5]);	
		}		
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($MatCargos)
{?>
	<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' width="100%">    
    <?
	foreach($MatCargos as $Categoria)
	{
		foreach($Categoria as $Cargo)
		{
			foreach($Cargo as $Identificacion)
			{	if($Categorias!=$Identificacion[0])
				{
					$Categorias=$Identificacion[0];
				?>			
				
                <td colspan="7" bgcolor="#e5e5e5" style="font-weight:bold" align="center"><? echo $Identificacion[0]?></td></tr>
                <tr bgcolor="#e5e5e5" style="font-weight:bold">
                    <td>Nombre</td>
                    <td>Identificacion</td>
                    <td>Cargo</td>
                    <td>Fecha Inicio</td>
                    <td>Fecha Fin</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <?
				}?>                				
                <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
                	<td><? echo $Identificacion[3]?></td>
                    <td><? echo $Identificacion[2]?></td>
                    <td><? echo $Identificacion[1]?></td>
                    <td><? echo $Identificacion[4]?></td>
                    <td><? echo $Identificacion[5]?></td>
                    <td width="16px">
				   	<a href="NuevoCargo.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Entidad=<? echo $Entidad?>&Categoria=<? echo $Identificacion[0]?>&Cargo=<? echo $Identificacion[1]?>&Identificacion=<? echo $Identificacion[2]?>">
                    <img border=0 src="/Imgs/b_edit.png" title="Editar"></a></td><?                   
					?><td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
					{location.href='ListadoCargos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Entidad=<? echo $Entidad?>&Categoria=<? echo $Identificacion[0]?>&Cargo=<? echo $Identificacion[1]?>&Identificacion=<? echo $Identificacion[2]?>';}">
					<img border="0" src="/Imgs/b_drop.png" title="Eliminar"/></a></td>			
                </tr>
                
				<? 
			}   
		}
	}
	?>
    </table>
    <?	
}?>
<input type="button" name="Nuevo" value="Nuevo" onClick="location.href='NuevoCargo.php?DatNameSID=<? echo $DatNameSID?>&Nuevo=1&Entidad=<? echo $Entidad?>'"/>
</form>
</body>
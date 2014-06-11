<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Anio)
	{
		$ND = getdate();
		$Anio = $ND[year];	
	}
	if(!$Clase)
	{
		$Clase = "Devolutivos";	
	}
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.CuentasDepxCC Where Compania='$Compania[0]' and Anio=$Anio
		and Grupo='$Grupo'";
		$res = ExQuery($cons);
		
		$cons = "Delete from Infraestructura.GruposDeElementos Where Grupo='$Grupo' and Anio=$Anio and Compania='$Compania[0]' and Clase='$Clase'";
		$res = ExQuery($cons);	
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
	<tr>
    	<td style="font-weight:bold" bgcolor="#e5e5e5">A&ntilde;o</td>
        <td><select name="Anio" onChange="FORMA.submit()">
        <?
			$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
        </select></td>
        <td>
        <select name="Clase" onChange="FORMA.submit()">
        	<option value="Devolutivos" <? if($Clase=="Devolutivos"){ echo " selected ";}?> >Devolutivos</option>
            <option value="Activos Fijos" <? if($Clase=="Activos Fijos"){ echo " selected ";}?> >Activos Fijos</option>
        </select>
        </td>
        <?
        if($Anio)
		{
			$cons = "Select Grupo,Clase,ModoDeprecia,ValorDeprecia, CtaGrupo,CtaProveedor,DepreciAcumulada, CodGrupo
			From Infraestructura.GruposDeElementos 
			Where Compania='$Compania[0]' and Clase = '$Clase' and Anio=$Anio Order by Grupo";
			?>
			<table width="90%" style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
            	<tr style="font-weight:bold" align="center" bgcolor="#e5e5e5"> 
                	<td>Codigo</td><td>Grupo</td><td>Clase</td><td>Cuenta Grupo</td><td>Cuenta Proveedor</td>
                    <td>Depreciacion Acumulada</td><td colspan="3">&nbsp;</td>
                </tr>
                <?
                $res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					?>
                        <tr onMouseOver="this.bgColor='#AAD4FF'" 
                        onmouseout="this.bgColor=''">
                    <?
					echo "<td>$fila[7]</td><td>$fila[0]</td><td>$fila[1]</td><td align = 'right'>$fila[4]</td>
					<td align = 'right'>$fila[5]</td><td align = 'right'>$fila[6]</td>";
					?>
                    <td align="center"><a href="ConfNewGdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&Grupo=<? echo $fila[0];?>">
                    <img title="Editar" border="0" src="/Imgs/b_edit.png" /></a></td>
                    <td align="center"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
                    {location.href='ConfGruposdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Eliminar=1&Grupo=<? echo $fila[0];?>&Clase=<? echo $Clase?>';}">
                    <img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a></td></tr>
                    <?	
				}?>
            </table>
			<?	
		}?>
    </tr>		
</table>
<input type="button" name="Nuevo" value="Nuevo" onClick="location.href='ConfNewGdeElementos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Clase=<? echo $Clase?>'" />
</form>
</body>
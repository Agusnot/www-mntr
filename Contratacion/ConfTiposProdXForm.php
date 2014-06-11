<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons = "Delete from ContratacionSalud.TiposdeProdXFormulacion 
		where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Formulacion='$Formulacion'";
		$res = ExQuery($cons);
		if($TipoPro)
		{	
			while( list($cad,$val) = each ($TipoPro))
			{
				$cons = "Insert into ContratacionSalud.TiposdeProdXFormulacion (Compania,AlmacenPpal,Formulacion,TipoProducto)
				values ('$Compania[0]','$AlmacenPpal','$Formulacion','$cad')";
				$res = ExQuery($cons);
			}
		}
	}
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania = '$Compania[0]' and SSFarmaceutico = 1";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal=$fila[0];
	}
	if(!$Formulacion)
	{
		$cons = "Select Formulacion from Salud.Formulaciones where Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Formulacion = $fila[0];
	}
	$cons = "Select TipoProducto from ContratacionSalud.TiposdeProdXFormulacion where Compania='$Compania[0]' and 
	AlmacenPpal='$AlmacenPpal' and Formulacion='$Formulacion'";
	$res = ExQuery($cons);
	while($fila = ExFetch($res)){ $CHK[$fila[0]]= " checked ";}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="<? echo $Estilo[1]?>">
    	<tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Almacen Principal</td>
            <td><select name="AlmacenPpal" onChange="FORMA.submit()" style="width:100%">
            <?
            	$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania = '$Compania[0]' and SSFarmaceutico = 1";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else {echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>	
            </select></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold">Formulaci&oacute;n</td>
            <td><select name="Formulacion" onChange="FORMA.submit()">
            	<?
                	$cons = "Select Formulacion from Salud.Formulaciones where Compania = '$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($Formulacion == $fila[0]){echo "<option selected value='$fila[0]'>Medicamentos $fila[0]</option>";}
						else{ echo "<option value='$fila[0]'>Medicamentos $fila[0]</option>";}
					}
				?>
            </select></td>
        </tr>
        <tr>
        	<td colspan="2">
            	<table width="100%" style='font : normal normal small-caps 12px Tahoma;' border="0">
                <?
                	$cons = "Select TipoProducto from Consumo.TiposProducto where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' order by TipoProducto asc";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $fila[0]?></td>
                        <td><input type="checkbox" name="TipoPro[<? echo $fila[0]?>]" value="<? echo $fila[0]?>"
                        <? echo $CHK[$fila[0]];?> /><?
					}
				?>
                </table>
            </td>
        </tr>
    </table>
<input type="submit" name="Guardar" value="Guardar" />  
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">  
</form>
</body>
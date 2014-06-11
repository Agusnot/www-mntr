<?
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if(!$AutoId)
		{
			$cons = "Select AutoId from ContratacionSalud.PlaneServicios where Clase = '$Clase' and Compania = '$Compania[0]' order by AutoId desc";
			$res = ExQuery($cons);
			$fila = ExFetch($res);
			$AutoId = $fila[0] +1;
		}
		$cons = "Insert into ContratacionSalud.PlaneServicios (AutoId,NombrePlan,Ambito,Clase,Compania) 
		values ('$AutoId','$NombrePlan','$Ambito','$Clase','$Compania[0]')";
		$res = ExQuery($cons);
		?><script language="javascript">
        	location.href = "PlanesServicio.php?Clase=<? echo $Clase?>&Plan=<? echo $AutoId?>";
        </script><?
	}
?>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.NombrePlan.value == ""){alert ("Debe Ingresar el Nombre del Plan");return false}
	}
</script>

<form name="FORMA" method="post" onsubmit="return Validar();">
<table cellpadding="4"  border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr>
    	<td>Nombre:</td><td><input type="text" name="NombrePlan" value="<? echo $NombrePlan?>" /></td>
    </tr>
    <tr>
    	<td>Proceso:</td>
        <td><select name="Ambito">
        <?
        	$cons = "Select Ambito from Salud.Ambitos where Compania = '$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Ambito == $fila[0]){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else {echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
		?>
        </select></td>
    </tr>
    <tr>
    	<td>Traer De:</td>
        <td><select name="TraerDe" onChange="FORMA.submit()" style="width:100%">
        <option value=""></option>
        <?
        	$cons = "Select NombrePlan,AutoId from ContratacionSalud.PlaneServicios where Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($Plan==$fila[1]){echo "<option selected value='$fila[1]'>$fila[0]</option>";}
				else {echo "<option value='$fila[1]'>$fila[0]</option>";}
			}
		?>
    	</select></td>
    </tr>
    <tr>
    	<td align="center" colspan="2">
        	<input type="submit" name="Guardar" value="Guardar" />
			<input type="button" name="Cancelar" value="Cancelar" onclick="location.href='PlanesServicio.php?Clase=<? echo $Clase?>'" />
        </td>
    </tr>
</table>

</form>
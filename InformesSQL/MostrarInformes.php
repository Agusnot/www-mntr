<?
	session_start();
	include("Funciones.php");
?>
<form name="FORMA" method="post">
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5">
	<tr>
    	<td><select name="Modulo" onchange="document.FORMA.submit()">
    		<option value="">--Seleccione Un Modulo--</option>
            <?
            	$cons = "Select Perfil,UsuariosxModulos.Madre from Central.AccesoxModulos,Central.UsuariosxModulos 
						where Perfil = Modulo and Nivel > 0 and Ruta <> '' and Usuario = '$usuario[1]'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Modulo == "$fila[0] - $fila[1]"){ echo "<option selected value='$fila[0] - $fila[1]'>$fila[0] - $fila[1]</option>";}
					else {echo "<option value='$fila[0] - $fila[1]'>$fila[0] - $fila[1]</option>";}
				}
			?>
    	</select></td>
        <td><select name="Informe" onchange="document.FORMA.submit()">
        	<option value="">--Seleccione un Informe--</option>
            <?
            	$cons = "Select Nombre from Informes.InformesCreados where Compania = '$Compania[0]' and Modulo = '$Modulo'";
				$res = ExQuery($cons);
				while($fila = ExFetch($res))
				{
					if($Informe == "$fila[0]"){ echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
				}
			?>
        </select></td>
    </tr>
</table>
<?
	if($Modulo && $Informe)
	{
		$cons = "Select InstruccionSQL,AutoId from Informes.InformesCreados where Nombre = '$Informe' and Modulo = '$Modulo' and Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		echo ExError();
		$fila = ExFetch($res);
		$cons = $fila[0];
		$res = ExQuery($cons);
		if (ExErrorNo() == "1064" || ExErrorNo() == "1054")
		{ 
			?>
            <br><em>LA CONSULTA SE ENCUENTRA MAL DEFINIDA</em><br />
			<a href="NuevoInforme.php?Editar=1&AutoId=<? echo $fila[1]?>">MODIFICAR CONSULTA</a><?
		}
		else
		{
			echo "<table style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5' width='100%'>";
			$NumCampos=mysql_num_fields($res);
			echo "<tr bgcolor='#e5e5e5' style='font-weight:bold' align='center'>";
			for($i=0; $i<$NumCampos; $i++)
			{
				echo "<td>".mysql_field_name($res,$i)."</td>";
			}
			echo "</tr>";
			while($fila = ExFetch($res))
			{
				echo "<tr>";
				for($i=0; $i<$NumCampos; $i++)
				{
					echo "<td>".$fila[$i]."</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}
	}
	
?>
</form>
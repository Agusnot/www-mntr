<?
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Informes.InformesCreados where AutoId = '$AutoId' and Compania = '$Compania[0]'";
		$res = ExQuery($cons); echo ExError();
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5">
	<tr>
    	<td style="font-weight:bold" bgcolor="#e5e5e5">Filtrar Modulos</td>
        <td><select name="Modulo" onchange="document.FORMA.submit()">
    		<option value="">No Filtrar Modulos</option>
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
    </tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Modulo</td><td>Nombre</td><td>InstruccionSQL</td><td>Parametros </td>
    </tr>
	<?
    	$cons = "Select Modulo,Nombre,InstruccionSQL,Parametros,AutoId from Informes.InformesCreados where Compania = '$Compania[0]' and Modulo LIKE '%$Modulo%'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";
			?>
			<td width="20px"><a href="NuevoInforme.php?Editar=1&AutoId=<? echo $fila[4]; ?>">
			<img title="Editar" border="0" src="/Imgs/b_edit.png" />
			</a></td>
			<td width="20px"><a href="#" onclick="if(confirm('Desea eliminar el registro?'))
            {location.href='Informes.php?Eliminar=1&AutoId=<? echo $fila[4];?>'}">
			<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a></td></tr>
			<?
		}
	?>
</table>
<input type="button" name="Nuevo" value="Nuevo" onclick="location.href='NuevoInforme.php'"; />
</form>
</body>
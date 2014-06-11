<?
	$conex = mysql_connect('localhost','root','Server*1492')
?>
	<script language="javascript">
    	function Validar()
		{
			if(document.forma.Identificacion.value=="" && document.forma.PApellido.value=="" && document.forma.SApellido.value=="" && document.forma.PNombre.value=="" && document.forma.SNombre.value=="")
			{
				alert("Por favor llene al menos un campo para realizar una busqueda");
				return false;
			}
		}
    </script>
<?	
	if(!$Buscar)
	{
?>
<form name="forma" method="post" onsubmit="return Validar()">
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
    	<tr>
        	<td>Identificaci&oacute;n: </td>
            <td><input type="text" name="Identificacion" maxlength="10" size="10" /></td>
        </tr>
        <tr>
        	<td>Apellidos: </td>
            <td><input type="text" name="PApellido" maxlength="30" size="30" /></td>
            <td><input type="text" name="SApellido" maxlength="30" size="30" /></td>
         </tr>
         <tr>
         	<td>Nombres: </td>
            <td><input type="text" name="PNombre" maxlength="30" size="30" /></td>
            <td><input type="text" name="SNombre" maxlength="30" size="30" /></td>
         </tr>
         <tr>
         	<td colspan="3" align="center"><input type="submit" name="Buscar" value="Buscar"></td>
         </tr>
    </table>
</form>
<?	}
else{
	echo "<font style='font : normal normal small-caps 12px Tahoma;'><strong>HOSPITAL SAN RAFAEL DE PASTO<br>";
	echo "HOJA DE VERIFICACION DE DERECHOS<br><br>";
	if($Codigo)
	{
		$cons="Select * from BDAseguradoras.Aseguradores where Codigo_Declaracion='$Codigo' and Identificacion='$Identificacion'";
	}
	elseif($Identificacion)
	{
		$cons="Select * from BDAseguradoras.Aseguradores where Identificacion='$Identificacion'";
	}
	else
	{
		$cons="Select * from BDAseguradoras.Aseguradores where Primer_Apellido like '%$PApellido%' and Segundo_Apellido like '%$SApellido%' and Primer_Nombre like '%$PNombre%' and Segundo_Nombre like '%$SNombre%'";
	}
	$res=mysql_query($cons);

	if(mysql_num_rows($res)==1)
	{
		//$fila=mysql_fetch_array($res);
		$fila=mysql_fetch_row($res);
		echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;'>";
		$NumCampos=mysql_num_fields($res);
		for($n=0;$n<$NumCampos;$n++)
		{
			$i++;
			echo "<td bgcolor='#e5e5e5'>".str_replace("_"," ",mysql_field_name($res,$n))."</td>";
			echo "<td>".$fila[$n]."</td>";	
			if($i>=3){echo "<tr>";$i=0;}
		}
		?>
        </table>
		<input type="button" name="Volver" value="Volver" onclick="location.href='BusquedaAseg.php'" />
		<?
	}
	else
	{
		echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;'>";
		echo "<tr bgcolor='#e5e5e5'><td>Codigo</td><td>Identificacion</td><td>Nombres</td></tr>";					
		while($fila=mysql_fetch_array($res))
		{?>
			<tr style="cursor:hand" onclick="location.href='BusquedaAseg.php?Buscar=1&Codigo=<? echo $fila['Codigo_declaracion']?>&Identificacion=<?echo $fila['Identificacion']?>'"><td><?echo $fila['Codigo_declaracion']?></td><td><?echo $fila['Identificacion']?></td><td><?echo $fila['Primer_Apellido']." ".$fila['Segundo_Apellido']." ".$fila['Primer_Nombre']." ".$fila['Segundo_Nombre']?></td></tr>
		<?}				
	}
}
?>
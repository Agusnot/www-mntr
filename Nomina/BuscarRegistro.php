<?
	$conex = mysql_connect("localhost", "root", "");
	mysql_select_db("Nomina", $conex);
	if($Buscar)
	{
		$cons="Select Cedula,PrimApe,SegApe,PrimNom,SegNom from Personal where PrimApe Like '$PrimApe%'
		and SegApe Like '$SegApe%' and PrimNom Like '$PrimNom%' and SegNom Like '$SegNom%'";
		$res=mysql_query($cons);
		if(mysql_num_rows($res)==1)
		{
			$fila=mysql_fetch_row($res);
		?>
			<script language="JavaScript">
				opener.parent.location.href='ListaFuncionarios.php?Redirect=<?echo $fila[0]?>&Emp=10';
				window.close();
			</script>
<?		}
		elseif(mysql_num_rows($res)>1)
		{
			echo "<table>";
			while($fila=mysql_fetch_row($res))
			{
			?>
				<tr><td><a href="#" onclick="opener.parent.location.href='ListaFuncionarios.php?Redirect=<?echo $fila[0]?>&Emp=10';window.close();"><?echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></a></td></tr>
<?			}
			echo "</table>";
			exit;
		}
	}
?>

<form name="FORMA">
<table border="0" cellpadding="4">
<tr><td>Primer Apellido</td><td><input type="Text" name="PrimApe"></td>
<td>Segundo Apellido</td><td><input type="Text" name="SegApe"></td>
<tr><td>Primer Nombre</td><td><input type="Text" name="PrimNom"></td>
<td>Segundo Nombre</td><td><input type="Text" name="SegNom"></td>
</tr>
</tr>

</table>
<input type="Submit" name="Buscar" value="Buscar">
</form>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($Eliminar==1)
	{
		$cons = "Delete from Consumo.".$Tabla." where Usuario='$Usuario' and AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$Eliminar=0;
	}
?>
<script language="javascript">
	function Validar()
	{
		document.FORMA.action="AgregarUsuariosx.php";
		document.FORMA.submit();
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<select name="AlmacenPpal" onChange="document.FORMA.submit();">
<?
			$cons = "Select AlmacenPpal from Consumo.AlmacenesPpales where Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
</select>
<?
	if($AlmacenPpal)
	{
		?>
		<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="600px">
        <tr bgcolor="#e5e5e5" style="font-weight=bold"><td>Usuario</td></tr>
		<?
		$cons="Select Usuario from Consumo.".$Tabla." where AlmacenPpal='$AlmacenPpal' and Compania='$Compania[0]'  order by Usuario asc";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td>";
			?>
			<td width="20px"><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
            {location.href='Usuariox.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Tabla=<? echo $Tabla?>&Usuario=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal;?>';}">
			<img border="0" title="Eliminar Registro" src="/Imgs/b_drop.png"/></a></td></tr>
			<?
		}
?>
	</table>
    <input type="button" name="Agregar" value="Agregar" onClick="Validar()" />
<?		
	}
?>
<input type="Hidden" name="Eliminar" value="<? echo $Eliminar?>" />
<input type="Hidden" name="Tabla" value="<? echo $Tabla?>" >
</form>
</body>
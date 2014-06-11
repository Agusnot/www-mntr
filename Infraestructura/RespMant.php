<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.ResponsablesMantenimiento Where Compania='$Compania[0]' and Usuario='$Usuario'";
		$res = ExQuery($cons);	
	}
	$cons = "Select Nombre,Cedula from Infraestructura.ResponsablesMantenimiento,Central.Usuarios
	Where ResponsablesMantenimiento.Usuario = Usuarios.Cedula and Compania='$Compania[0]' Group by Nombre,Cedula order by Nombre";
	$res = ExQuery($cons);
?>
<script language="javascript">
	function AbrirGrupos(Usuario)
	{
		St = document.body.scrollTop;
		frames.FrameOpener.location.href="GruposUsuario.php?DatNameSID=<? echo $DatNameSID?>&Usuario="+Usuario;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=St + 20;
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='400';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Usuario</td><td colspan="2">&nbsp;</td></tr>
<?
	while($fila = ExFetch($res))
	{
	?>
	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'">
    	<td><? echo $fila[0]?></td>
        <td><img src="/Imgs/s_process.png" style="cursor:hand" onClick="AbrirGrupos('<? echo $fila[1];?>')" title="Configurar Grupos" /></td>
        <td><img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar"
        onclick="if(confirm('Desea Eliminar este registro?')){location.href='RespMant.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Usuario=<? echo $fila[1]?>'}" /></td>
    </tr>
	<?	
	}
?>
</table>
<input type="button" name="Agregar" value="Agregar" onClick="location.href='NewRespMant.php?DatNameSID=<? echo $DatNameSID?>'" />
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
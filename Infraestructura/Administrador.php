<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons = "Delete from Infraestructura.Administrador Where Compania='$Compania[0]' and Cedula='$Cedula'";
		$res = ExQuery($cons);	
	}
?>
<script language="javascript">
    function AbrirGrupos(Usuario)
    {
            St = document.body.scrollTop;
            frames.FrameOpener.location.href="GruposUsuario.php?DatNameSID=<? echo $DatNameSID?>&AdminAlmacen=1&Usuario="+Usuario;
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
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Administradores de Infraestructura</td></tr>
<?
	$cons = "Select Usuario,Cedula From InfraEstructura.Administrador Where Compania = '$Compania[0]' order by Usuario";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		echo "<tr><td>$fila[0]</td>";
		?>
                <td><img src="/Imgs/s_process.png" style="cursor:hand" onClick="AbrirGrupos('<? echo $fila[1];?>')" title="Configurar Grupos" /></td>
		<td><img src="/Imgs/b_drop.png" style="cursor:hand" onClick="location.href='Administrador.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cedula=<? echo $fila[1];?>'"  /></td></tr>
		<?	
	}
?>
</table>
<input type="button" name="Agregar" value="Agregar" onClick="location.href='NuevoAdministrador.php?DatNameSID=<? echo $DatNameSID?>'" />
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
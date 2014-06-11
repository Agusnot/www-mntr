<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if(!$Anio){$Anio = $ND[year];}
	if($Editar)
	{
		$cons="Update Central.Usuarios set Nombre='$Nombre',Entidad='$SelEntidad' where Cedula='$Identificacion'";

		$res=ExQuery($cons);
		echo ExError($res);
		if($res==1){echo "<span class='mensaje1'>Cambio realizado!</span>";}
	}
	if($Elimina)
	{
		$cons="Delete from Central.Usuarios where Usuario='$Usuario'";
		$res=ExQuery($cons);

		$cons="Delete from Central.UsuariosxModulos where Usuario='$Usuario'";
		$res=ExQuery($cons);

		echo ExError($res);
		if($res==1){echo "<span class='mensaje1'>Registro Eliminado!</span>";}
	}
	if($Nuevo)
	{
		$Clave=md5("userdef");
		$cons="Insert into Central.Usuarios (Usuario,Nombre,Cedula,Clave) values('$NewUsuario','$NewNombre','$NewCedula','$Clave')";
		$res=ExQuery($cons);
		echo ExError($res);
		if($res==1){echo "<span class='mensaje1'>Registro insertado!</span>";}
	}
?>
<script language="javascript">
	function AbrirUsuariosxCC(Usuario)
	{
		St = document.body.scrollTop;
		frames.FrameOpener.location.href="AutUsuariosxCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Usuario="+Usuario;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=St + 20;
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='550';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
A&ntilde;o:
<select name="Anio" onChange="FORMA.submit()">
<?
	$cons = "Select Anio from Central.Anios where Compania='$Compania[0]'";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		if($Anio == $fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{ echo "<option value='$fila[0]'>$fila[0]</option>";}
	}
?>
</select>
<?
	if($Anio)
	{ ?>
		<table border="1" bordercolor="#e5e5e5" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
		<tr style="font-weight:bold" align="center" bgcolor="#e5e5e5"><td>Nombre Usuario</td></tr>
		<?
			$cons="Select Usuario,Cedula,Nombre from Central.Usuarios Order By Usuario ASC, Nombre ASC ";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{?>
				<tr><td><? echo $fila[2] ?></td>
				<td><button onClick="AbrirUsuariosxCC('<? echo $fila[0]?>')"><img title="Autorizar Centros de Costo" src="/Imgs/s_process.png"></button></td>
            	</tr>
			<? } ?>
		</table>
	<? }
?>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</form>
</body>
<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		$cons = "Delete from Consumo.UsuariosxCC where Compania='$Compania[0]' and Usuario='$Usuario' and Anio='$Anio'";
		$res = ExQuery($cons);
		if($Check)
		{
			while( list($cad,$val) = each($Check))
			{
				$cons = "Insert into Consumo.UsuariosxCC (Compania,Usuario,CC,Anio) values ('$Compania[0]','$Usuario','$cad','$Anio')";
				$res = ExQuery($cons);
			}
		}
	}
	$cons = "Select CC from Consumo.UsuariosxCC where Compania='$Compania[0]' and Usuario='$Usuario' and Anio='$Anio'";
	$res = ExQuery($cons);
	while($fila = ExFetch($res)){
		$Checked[$fila[0]] = " checked ";
	}
?>
<script language="JavaScript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Marcar()
	{
		if(document.FORMA.Habilitar.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
<table border="0" width="100%">
<tr><td align="right">
	<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
	<button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button>
	<input type="checkbox" name="Habilitar" title="Habilitar/Deshabilitar Todo" onClick="Marcar()" />
</td></tr>
</table>
<table width="100%">
	<?
    	$cons = "Select Codigo,CentroCostos,Tipo from Central.CentrosCosto where Anio = '$Anio' and Compania = '$Compania[0]' and Codigo<>'000' Order by Codigo";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			$Tabs = strlen($fila[0]);
			if($fila[2]=='Titulo'){$BgColor = "#e5e5e5";$Check="&nbsp;";}
			else{$BgColor = "";$Check="<input type='checkbox' name='Check[$fila[0]]' ".$Checked[$fila[0]]." />";}
			echo "<tr bgcolor='$BgColor'><td>";
			for($i=0;$i<$Tabs;$i++){echo "&nbsp;";}
			echo "$fila[0] - $fila[1]</td><td width='10px' align='right'>$Check</td></tr>";
		}
		
	?>
</table>
</form>
</body>
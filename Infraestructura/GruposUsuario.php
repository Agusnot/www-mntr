<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Guardar)
	{
		$cons = "Delete from InfraEstructura.ResponsablesMantenimiento where Compania='$Compania[0]' and Usuario='$Usuario'";
		$res = ExQuery($cons);
		if($Check)
		{
			if($NoCodi){$Adcons = ",NoCodificados";$AdValues=",1";}
                        while( list($cad,$val) = each($Check))
			{
				$cons = "Insert into InfraEstructura.ResponsablesMantenimiento (Compania,Usuario,GrupoElementos$Adcons)
				values ('$Compania[0]','$Usuario','$cad'$AdValues)";
				$res = ExQuery($cons);
			}
		}
	}
	$cons = "Select GrupoElementos,NoCodificados from InfraEstructura.ResponsablesMantenimiento where Compania='$Compania[0]' and Usuario='$Usuario'";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		$Checked[$fila[0]] = " checked ";
                if($fila[1]){ $ChkNC = " checked ";}
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
<input type="hidden" name="Usuario" value="<? echo $Usuario?>" />
<table border="0" width="100%">
<tr><td align="right">
	<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
	<button type="button" name="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" title="Cerrar"></button>
	<input type="checkbox" name="Habilitar" title="Habilitar/Deshabilitar Todo" onClick="Marcar()" />
</td></tr>
</table>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>No codificados</td><td><input type="checkbox" name="NoCodi" <? echo $ChkNC?>></td></tr>
    <tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Grupo de Elementos</td><td>&nbsp;</td></tr>
	<?
                $cons = "Select Grupo From InfraEstructura.GruposdeElementos Where Compania='$Compania[0]' and Anio=$ND[year] order by Clase desc,Grupo asc";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor='#FFFFFF'"><? 
			echo"<td>$fila[0]</td><td width='10px' align='right'>
			<input type='checkbox' name='Check[$fila[0]]' ".$Checked[$fila[0]]." /></td></tr>";
                }
		
	?>
</table>
</form>
</body>
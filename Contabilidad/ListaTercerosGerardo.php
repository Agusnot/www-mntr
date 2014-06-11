<?
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		$cons="Select * from Contabilidad.Movimiento where Cedula='$IdeEliminar' and Movimiento.Compania='$Compania[0]'";
		$res=mysql_query($cons);
		if(mysql_num_rows($res)==0)
		{
			$cons="Delete from Central.Terceros where Cedula='$IdeEliminar' and Terceros.Compania='$Compania[0]'";
			$res=mysql_query($cons);
		}
		else
		{
			echo "Tercero tiene movimiento. No es posible eliminar!!!";
		}
	}
	if(!$Cedula){$Cedula="";}
	$cons="Select Cedula,PrimApe,SegApe,PrimNom,SegNom,Direccion,Regimen from Central.Terceros 
	where Cedula like '$Cedula%' and PrimApe like '%$PrimApe%' and SegApe like '%$SegApe%' and PrimNom like '%$PrimNom%' and SegNom like '%$SegNom%' and Terceros.Compania='$Compania[0]'
	Order By PrimApe,SegApe,PrimNom,SegNom";

	$res=mysql_query($cons);echo mysql_error();
?>
<script language="javascript">
function AbrirCriterios(Tipo,Cedula)
	{
		<? 	$cons00 = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res00 = ExQuery($cons00);
			$fila00 = ExFetch($res00)
		?>
		frames.FrameOpener.location.href='/Consumo/EvaluacionCriterios.php?Tipo='+Tipo+'&Cedula='+Cedula+'&AlmacenPpal=<? echo $fila00[0]?>';
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450';
		document.getElementById('FrameOpener').style.height='470';
	}
</script>
<body background="/Imgs/Fondo.jpg">
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
</style>
<table border="1" rules="groups" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>" style="text-transform:uppercase">
<tr style="font-weight:bold;color:<?echo $Estilo[6]?>;text-align:center" bgcolor="<?echo $Estilo[1]?>"><td></td><td></td><td>Cedula</td><td>Nombre</td><td>Direccion</td><td>Regimen</td></tr>
<?
	while($fila=mysql_fetch_row($res))
	{
		if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
		else{$BG="white";$Fondo=1;}
		echo "<tr bgcolor='$BG'>";?>
		<? if($ModOrigen!='Consumo') { ?>
		<td><center>
			<a href="ListaTerceros.php?IdeEliminar=<?echo $fila[0]?>&Eliminar=1&TipoTercero=<?echo $TipoTercero?>&PrimApe=<?echo $PrimApe?>&SegApe=<?echo $SegApe?>&PrimNom=<?echo $PrimNom?>&SegNom=<?echo $SegNom?>">
			<img border="0" src="/Imgs/b_drop.png"></a></td>	
		<? } 
		else{ ?> <td width="20px"><button type="button" name="Seleccion" onClick="AbrirCriterios('Seleccion','<? echo $fila[0]?>')">
				<img src="/Imgs/b_ftext.png" title="Seleccion"></button></td> <? }
		if($ModOrigen=="Contabilidad"){?>
        <td><center><a href="#" onclick="open('MovimientoxCuenta.php?TerceroSel=<?echo $fila[0]?>','','width=800,height=300,scrollbars=yes')"><img border="0" src="/Imgs/b_tblexport.png"></td>	<? }?>
		<? if($ModOrigen=="Presupuesto"){?>
        <td><center><a href="#" onclick="open('/Presupuesto/MovimientoxCuenta.php?TerceroSel=<?echo $fila[0]?>','','width=800,height=300,scrollbars=yes')"><img border="0" src="/Imgs/b_tblexport.png"></td>	<? }
        else { ?> <td width="20px"><button type="button" name="Desempeno" onClick="AbrirCriterios('Desempeno','<? echo $fila[0]?>')">
        			<img src="/Imgs/b_tblexport.png" title="Desempeño"></button></td> <? }
		echo "<td>$fila[0]</td><td><a href='NuevoTercero.php?Cedula=$fila[0]&Edit=1'>$fila[1] $fila[2] $fila[3] $fila[4]</a></td><td>$fila[5]</td><td>$fila[6]</td>";
		echo "</tr>";
	}
?>
</table>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
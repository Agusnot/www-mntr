<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$Anio){$ND = getdate();$Anio = $ND[year];}
	if(!$AlmacenPpal)
	{
		$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[1]' and Compania='$Compania[0]'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$AlmacenPpal = $fila[0];		
	}
	if($Eliminar)
	{
		$cons1 = "Select Criterio from Consumo.CriteriosxGrupo Where Compania='$Compania[0]' and Anio = $Anio and AlmacenPpal='$AlmacenPpal'";
		$res1 = ExQuery($cons1);
		if(ExNumRows($res1)==0)
		{
			$cons = "Delete from Consumo.Grupos where Grupo='$Grupo' and AlmacenPpal = '$AlmacenPpal' and Compania = '$Compania[0]' and Anio = $Anio";
			$res = ExQuery($cons);
			$MostrarMensaje = 0;
		}
		else
		{
			$MostrarMensaje = 1;
		}
	}
?>
<script language="javascript">
	function Abrir(Grupo,AlmacenPpal,TipoCriterio,Anio)
	{
		frames.FrameOpener.location.href='CriteriosXGrupo.php?DatNameSID<? echo $DatNameSID?>&Compania=<? echo $Compania[0]?>&AlmacenPpal='+AlmacenPpal+'&Grupo='+Grupo+'&TipoCriterio='+TipoCriterio+'&Anio='+Anio;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='80px';
		document.getElementById('FrameOpener').style.left='8px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='380';
		document.getElementById('FrameOpener').style.height='400';
	}
	function Validar()
	{
		if(document.FORMA.AlmacenPpal.value!="")
		{
			document.FORMA.action = "NewConfGrupos.php";
			document.FORMA.submit();	
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form method="post" name="FORMA" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
A&ntilde;o: 
<select name="Anio" onChange="FORMA.submit()" />
    <?
    	$cons = "Select Anio from Central.Anios where Compania = '$Compania[0]'";
		$res = ExQuery($cons);
		while($fila = ExFetch($res))
		{
			if($Anio==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else {echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
	?>
</select>
<?
	if($Anio)
	{ ?><select name="AlmacenPpal" onChange="document.FORMA.submit()">
        	<?
            $cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			echo ExError();
			while($fila=ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
			?>
</select>
<?
	if($AlmacenPpal)
	{
		$cons="Select  Grupo,CtaContable,CtaProveedor,ReteFte,CtaReteFteE,CtaReteFteS,ReteICA,CtaReteICAE,CtaReteICAS,CtaIVAE,CtaIVAS,CtaGasto 
		       from Consumo.Grupos where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $Anio order by Grupo";
		$res = ExQuery($cons);
		echo ExError();
		?>
        <table width='800px' style='font : normal normal small-caps 12px Tahoma;' border='1' bordercolor='#e5e5e5'>
			<tr style='font-weight:bold' width='280px' bgcolor='#e5e5e5' align='center'>
				<td rowspan="2">Nombre Grupo</td><td rowspan="2">Cuenta Contable</td><td rowspan="2">Cuenta Proveedor</td><td rowspan="2">ReteFuente</td>
				<td colspan="2">Cuenta ReteFte</td><td rowspan="2">ICA</td><td colspan="2">Cuenta ICA</td><td colspan="2">Cuenta IVA</td>
                <td rowspan="2">Cuenta Gasto</td>
            </tr>
            <tr  style='font-weight:bold' width='280px' bgcolor='#e5e5e5' align='center'>
               	<td>Entrada</td><td>Salida</td><td>Entrada</td><td>Salida</td><td>Entrada</td><td>Salida</td>
            </tr>
        <?
		while($fila=ExFetch($res))
		{
			?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" 
            	onmouseout="this.bgColor=''">
            <?
			echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td>
			<td>$fila[6]</td><td>$fila[7]</td><td>$fila[8]</td><td>$fila[9]</td><td>$fila[10]</td><td>$fila[11]</td>";
			?>
            <td>
            <a href="NewConfGrupos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Editar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Grupo=<? echo $fila[0];?>">
            <img title="Editar" border="0" src="/Imgs/b_edit.png" /></a>
            </td>
			<td><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
            {location.href='ConfigGrupos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Eliminar=1&AlmacenPpal=<? echo $AlmacenPpal;?>&Grupo=<? echo $fila[0];?>';}">
			<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a></td>
            <td><a href="#" onClick="Abrir('<? echo $fila[0]?>','<? echo $AlmacenPpal?>','Seleccion','<? echo $Anio?>')">
            	<img title="Lista de Criterios de Seleccion" border="0" src="/Imgs/b_ftext.png" /></a></td>
            <td><a href="#" onClick="Abrir('<? echo $fila[0]?>','<? echo $AlmacenPpal?>','Desempeno','<? echo $Anio?>')">
            	<img title="Lista de Criterios de Desempeño" border="0" src="/Imgs/b_tblexport.png" /></a></td>
			
			<?	} ?>
		</table>
		<input type="button" name="Nuevo" value="Nuevo" onClick="Validar()"  />
		</form>
	<? } ?>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>				
	<? } ?>
</body>
<?
	if($MostrarMensaje)
	{
	?>
	<script language="javascript">
		alert("Aun hay criterios por eliminar en el grupo");
	</script>
	<?
	}
?>
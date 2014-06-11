<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Operar(Campo,Evento,Objeto)
	{
		var c = Objeto;
		if(Campo.value!="")
		{	if(Evento=='onfocus'){ document.getElementById(c).value = parseInt(document.getElementById(c).value) - parseInt(Campo.value);}
			if(Evento=='onBlur'){document.getElementById(c).value = parseInt(document.getElementById(c).value) + parseInt(Campo.value);}
		}
		if(document.getElementById(c).value > 100){document.FORMA.Aceptar.disabled = true;}
		else{document.FORMA.Aceptar.disabled = false;}	
	}
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
	if($Aceptar)
	{
		?><script language="javascript">CerrarThis()</script><?	
	}
	if($Cancelar)
	{
		?><script language="javascript">CerrarThis()</script><?	
	}
	if($Eliminar)
	{
		$cons = "Delete from Consumo.CriteriosXGrupo where
		Compania = '$Compania' and AlmacenPpal = '$AlmacenPpal' and Grupo='$Grupo' and Criterio='$Criterio'";
		$res = ExQuery($cons); echo ExError();
		$Eliminar = 0;
		$cons = "Update Consumo.CriteriosXGrupo set Completo = 'NO' 
				where Compania = '".utf8_decode($Compania)."' and AlmacenPpal = '$AlmacenPpal' and Grupo = '$Grupo' and Tipo = '$TipoCriterio'";
		$res = ExQuery($cons);
	}
	if($Guardar)
	{
		if ($TotalPeso == 100){$Completo = 'SI';}
		else { $Completo = 'NO';}
		if($Criterio && $Minimo && $Peso)
		{
			if($Minimo > $Peso)
			{
				?><script language="javascript">alert("El Valor Minimo no puede ser mayor que el Peso")</script><?
				$TotalPeso = $TotalPeso - $Peso;
				$TotalMinimo = $TotalMinimo - $Minimo;
			}
			else
			{
				if($TotalPeso>100 || $TotalMinimo>100)
				{
					?><script language="javascript">alert("Se ha superado los Valores Limites")</script><?
					$TotalPeso = $TotalPeso - $Peso;
					$TotalMinimo = $TotalMinimo - $Minimo;
				}
				else
				{
					$cons = "Insert Into Consumo.CriteriosXGrupo (Compania,AlmacenPpal,Grupo,Criterio,Peso,Minimo,Tipo,Anio)
							values ('$Compania','$AlmacenPpal','$Grupo','$Criterio','$Peso','$Minimo','$TipoCriterio','$Anio')";
					$res = ExQuery($cons); echo ExError();
					$cons = "Update Consumo.CriteriosXGrupo set Completo = '$Completo' 
					where Compania = '$Compania' and AlmacenPpal = '$AlmacenPpal' and Grupo='$Grupo' and Tipo = '$TipoCriterio' and Anio = '$Anio'";
					$res = ExQuery($cons);
				}
			}	
		}
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Eliminar" value="<? echo $Eliminar?>" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5">
    	<tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4">Almacen Principal: <? echo $AlmacenPpal?></td></tr>
        <tr bgcolor="#e5e5e5" style="font-weight:bold"><td colspan="4">Grupo: <? echo $Grupo?></td></tr>
        <tr bgcolor="#e5e5e5" align="center">
        	<td>Criterio de <? echo $TipoCriterio?></td><td>Peso</td><td>Minimo</td><td>&nbsp;</td>
        </tr>
        <? 
			$cons = "Select Criterio,Peso,Minimo from Consumo.CriteriosXGrupo 
			where Compania='$Compania' and AlmacenPpal='$AlmacenPpal' and Grupo='$Grupo' and Tipo='$TipoCriterio' and Anio = '$Anio'";
			$TotalPeso = 0;
			$TotalMinimo = 0;
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{	$TotalPeso = $TotalPeso + $fila[1];
				$TotalMinimo = $TotalMinimo + $fila[2];
				echo "<tr><td>$fila[0]</td><td align='right'>$fila[1]</td><td align='right'>$fila[2]</td>";
			
		?>
        	<td><a href="#" onClick="if(confirm('Desea eliminar el registro?'))
        	{location.href='CriteriosXGrupo.php?Anio=<? echo $Anio?>&DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Criterio=<? echo $fila[0]?>&AlmacenPpal=<? echo $AlmacenPpal?>&Grupo=<? echo $Grupo?>&TipoCriterio=<? echo $TipoCriterio?>'}">
					<img border="0" src="/Imgs/b_drop.png"/></a></td></tr>
            <? } ?>
        <tr>
            <td><input type="text" name="Criterio" /></td>
            <td align="center"><input type="text" name="Peso" size="3" maxlength="3" style="text-align:right"
                onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);Operar(this,'onBlur','TotalPeso');"
                onFocus="Operar(this,'onfocus','TotalPeso')"  /></td>
            <td align="center"><input type="text" name="Minimo" size="3" maxlength="3" style="text-align:right"
            	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this);Operar(this,'onBlur','TotalMinimo');"
                onFocus="Operar(this,'onfocus','TotalMinimo')" /></td>
            <td><button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button></td>
        </tr>
        <tr>
        	<td align="right">Total:</td>
            <td><input type="text" name="TotalPeso" id="TotalPeso" value="<? echo $TotalPeso?>" readonly size="3" maxlength="3" style="text-align:right" /></td>
            <td><input type="text" name="TotalMinimo" id="TotalMinimo" value="<? echo $TotalMinimo?>" readonly size="3" maxlength="3" style="text-align:right" /></td>
        </tr>
    </table>
<input type="submit" name="Aceptar" value="Aceptar" <? if ($TotalPeso!=100){ echo "disabled";}?> />
<input type="submit" name="Cancelar" value="Cancelar" />
</form>
</body>
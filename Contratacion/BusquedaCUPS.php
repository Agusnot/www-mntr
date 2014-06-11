<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		while( list($cad,$val) = each($Valor))
		{
			if($cad && $val)
			{
				$cons = "Insert into ContratacionSalud.CupsXPlanes (AutoId,CUP,Valor,Compania) values ('$VrElemento','$cad','".round($val,0)."','$Compania[0]')";
				$res = ExQuery($cons);
			}
		}
		
	}
	if($Eliminar)
	{
		$cons = "Delete from ContratacionSalud.Cups where Codigo = '$Codigoe' and Compania = '$Compania[0]'";
		$res = ExQuery($cons);
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
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
<?
	if(!$Grupo){$CompG = " Grupo is NULL or Grupo like '%%' ";}
	else{$CompG = " Grupo = '$Grupo' ";}
	if(!$Tipo){$CompT = " Tipo is NULL or Tipo like '%%' ";}
	else{$CompT = " Tipo = '$Tipo' ";}
	if($Clasificacion==1){$Clasf="nopos=1";} else {$Clasf="nopos is null or nopos =0";}
	if($Codigo || $Nombre || $Grupo || $Tipo || $Clasificacion)
	{
		$cons = "Select Codigo,Nombre,Grupo,Tipo,SOAT,DetalleSOAT,Notas,nopos from ContratacionSalud.Cups where Compania = '$Compania[0]' and
		Codigo ilike '$Codigo%' and Nombre ilike '%$Nombre%' and ($CompG) and ($CompT) and ($Clasf)";
		if($Elemento)
		{
			$cons = $cons . " and Codigo not in(Select CUP from ContratacionSalud.CupsXPlanes where AutoId = '$VrElemento' and Compania='$Compania[0]')";
		}
		$cons=$cons." order by codigo,nombre";
		//echo $cons;
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
?>		<table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>" width="100%">
		<? if($Elemento)
		{
			?><tr><td align="right" colspan="10">
			<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
			</td></tr><?
		}?>
            <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    		<td>Codigo</td><td>Nombre</td><? if(!$Elemento) { echo "<td>Grupo</td><td>Tipo</td><td>SOAT</td><td>Detalle SOAT</td><td>&nbsp;</td><td>&nbsp;</td>";}
			else{echo "<td>$Texto</td>";}?>
    	</tr>
    	<?  while($fila = ExFetch($res))
			{
				if($fila[7]==1){$NP="No POS";}else{$NP="POS";}
				if(!$fila[2]){$fila[2] = " &nbsp;";}
				else
				{
					$cons0="Select Grupo from ContratacionSalud.GruposServicio where Codigo = '$fila[2]' and Compania='$Compania[0]'";
					$res0=ExQuery($cons0);
					$fila0 = ExFetch($res0);
					$fila[2] = $fila0[0];
				}
				if(!$fila[3]){$fila[3] = " &nbsp;";}
				else
				{
					$cons0="Select Tipo from ContratacionSalud.TiposServicio where Codigo = '$fila[3]' and Compania='$Compania[0]'";
					$res0=ExQuery($cons0);
					$fila0 = ExFetch($res0);
					$fila[3] = $fila0[0];
				}
				if(!$fila[4]){$fila[4] = " &nbsp;";}if(!$fila[5]){$fila[5] = " &nbsp;";}
				?><tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><?
				echo "<td>$fila[0]</td><td  title='$fila[6]'>$fila[1]</td>";
				if(!$Elemento)
				{	echo "<td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>$NP</td>";
				?>	<td width="16px">
                		<a href='NuevoCUP.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&Codigo=<? echo $fila[0]?>&AntNombre=<? echo $Nombre?>&AntCodigo=<? echo $Codigo?>' target="_parent">
                    	<img title="Editar" border=0 src='/Imgs/b_edit.png'></a></td>
					<td width="16px"><a href="#"
                	onclick="if(confirm('Desea eliminar el registro?'))
                	{location.href='BusquedaCUPS.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Codigoe=<? echo $fila[0]?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&Grupo=<? echo $Grupo?>&Tipo=<? echo $Tipo?>';}">
					<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></a>
					</td>
				<? }
				else
				{?><!-- <td width="16px" align="center"><input type="checkbox" name="CUP[<? echo $fila[0]?>]"> -->
                <td align="center"><input type="text" name="Valor[<? echo $fila[0]?>]" size="6" maxlength="6"
                onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td><? }
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	} ?>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>

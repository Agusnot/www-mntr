<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$MesI){$MesI=$MesTrabajo;}
	if(!$DiaI){$DiaI=$ND[mday];}
	if(!$AnioI){$AnioI=$ND[year];}
	if(!$Clase)
	{
		$Clase = "Devolutivos";
		$HacerSubmit = 1;
	}
	else
	{
		unset ($HacerSubmit);
	}
	if(!$Comprobante)
	{
		$cons="SELECT Comprobante FROM Consumo.Comprobantes WHERE Tipo='$Tipo' and Compania='$Compania[0]'
		and AlmacenPpal='$AlmacenPpal'
		ORDER BY Comprobante";
		$res=ExQuery($cons);
		$fila = ExFetch($res);
		$Comprobante = $fila[0];
	}
	if($Tipo=="Traslados")
	{
		$cons = "Select Usuario,Cedula,PrimApe,SegApe,PrimNom,SegNom From Infraestructura.Administrador, Central.Terceros
		Where Administrador.Cedula = Terceros.Identificacion and Administrador.Compania = '$Compania[0]' and Terceros.Compania='$Compania[0]' and Usuario = '$usuario[0]'";
		//echo $cons;
		$res = ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			$IRO = " disabled ";	
		}	
	}
	
?>
<script language="javascript">
	function verificarMes(objeto)
	{
		if(objeto.value!=<? echo $ND[mon]?>)
		{
			document.FORMA.Nuevo.disabled = true;	
		}
		else
		{
			document.FORMA.Nuevo.disabled = false;
		}
	}
</script>
<html>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" target="Abajo" <? if($Tipo!="Traslados" && $Tipo!="Bajas"){?>action="ListaMovimiento.php"<? }?>
<? if($Tipo=="Traslados"){?>action="ListaTraslados.php"<? }
   if($Tipo=="Bajas"){?>action="ListaBajas.php"<? }
?> method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<center>
<table border="1" bordercolor="#e5e5e5" cellpadding="4" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td><center>Visualizar Periodo</td><td>Clase</td>
<td colspan="2"></td></tr>
<tr>
<td>

<select name="AnioI" onChange="document.FORMA.submit();" <? echo $IRO?>  />
<?
	$cons="Select Anio from Central.Anios where Compania = '$Compania[0]' order by Anio desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($AnioI==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}		
	}
	
?>
</select>

<select name="MesI" onChange="<? if($IRO){ ?>verificarMes(this);<? }?>document.FORMA.submit();"> 
<?
	$cons="Select Mes,Numero,NumDias from Central.Meses Order By Numero";
	$res=ExQuery($cons,$conex);
	while($fila=ExFetch($res))
	{
		if($MesI==$fila[1]){echo "<option value='$fila[1]' selected>$fila[0]</option>";$NumDias=$fila[2];}
		else{echo "<option value='$fila[1]'>$fila[0]</option>";}
	}
?>
</select>
</td>

<td>
<select name="Clase" <? echo $IRO;?> 
onChange="parent.location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo?>&Clase='+FORMA.Clase.value+'&AnioI='+FORMA.AnioI.value+'&MesI='+FORMA.MesI.value;">
	<option <? if($Clase == "Devolutivos"){ echo " selected ";}?> value="Devolutivos">Devolutivos</option>
    <option <? if($Clase == "Activos Fijos"){ echo " selected ";}?> value="Activos Fijos">Activos Fijos</option>
</select>
</td>
<td><input type="button" value="Nuevo" name="Nuevo" 
<? if($Tipo != "Traslados" && $Tipo != "Bajas")
{?>onClick="parent.location.href='NuevoMovimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Clase='+document.FORMA.Clase.value" <? }
   else
	{
	if($Tipo=="Traslados")
		{
			if($Origen)
			{
				?>onclick="parent.location.href='NewAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>&Tabla=Traslados'"<?
			}
			else
			{
				?>onClick="parent.location.href='Traslados.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Clase='+document.FORMA.Clase.value" <? 	
			}
		}
	if($Tipo=="Bajas")
		{
			if($Origen)
			{
				?>onclick="parent.location.href='NewAccionMasiva.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>&Tabla=Bajas'"<? 		
			}
			else
			{
				?>onClick="parent.location.href='NewBajas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>&Anio='+AnioI.value+'&Mes='+MesI.value+'&Clase='+document.FORMA.Clase.value" <? 	
			}
		}
	}?> />	
</table>
<input type="Hidden" name="Tipo" value="<? echo $Tipo?>">
<? if($HacerSubmit){?><script language="javascript">document.FORMA.submit();</script><?	}?>

</form>
</body>
</html>


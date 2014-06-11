<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
include ("Funciones.php");
	if(!$Nivel)
	{
		$cons = "Select Nivel from Presupuesto.EstructuraPuc where compania='$Compania[0]' and Anio='$Anio' order by Nivel desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		echo ExError();
		$Nivel = $fila[0] + 1;
	}
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons="Insert into Presupuesto.EstructuraPuc(NoCaracteres,Detalle,Nivel,Compania,Anio) 
			values ('$NoCaracteres','$Detalle','$Nivel','$Compania[0]','$Anio')";		   
		}
		else
		{
			$cons = "Update Presupuesto.EstructuraPuc set NoCaracteres = '$NoCaracteres', Detalle = '$Detalle' where
			Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
		}
		$res=ExQuery($cons);
		echo ExError($res);
		?><script language="javascript">location.href='ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';</script><?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if (document.FORMA.NoCaracteres.value == ""){alert("Ingrese un Numero de Caracteres");return false;}
		else{if (document.FORMA.Detalle.value == ""){alert("Ingrese el Detalle");return false;}
		     else{return true}}	
	}
</script>
<? 
	if($Editar)
	{
		$cons = "Select NoCaracteres,Detalle,Nivel from Presupuesto.EstructuraPuc where compania='$Compania[0]' and Anio='$Anio' and Nivel = '$Nivel'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$NoCaracteres = $fila[0]; $Detalle = $fila[1]; $Nivel = $fila[2];
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
   <table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold" align="center">A&ntilde;o <? echo $Anio?></td></tr>
    <tr>
		<td bgcolor="#e5e5e5">No de Caracteres</td>
        <td><input type="text" name="NoCaracteres" value="<? echo $NoCaracteres?>" maxlength="2" size="2"
        onkeyup="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" /></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5">Detalle</td>
        <td><input type="text" name="Detalle" value="<? echo $Detalle?>" 
        onkeyup="xLetra(this)" onKeyDown="xLetra(this)"/></td>
	</tr>
	<tr>
		<td bgcolor="#e5e5e5">Nivel</td>
        <td><input type="text" name="Nivel" value="<? echo $Nivel?>" readonly maxlength="3" size="3" /></td>
	</tr>
	</table>
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="submit" name="Guardar" value="Guardar" />
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfEstructuraPUC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"  />
</form>
</body>
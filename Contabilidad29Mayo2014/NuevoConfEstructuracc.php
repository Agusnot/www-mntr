<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	if(!$Nivel)
	{
		$cons = "Select Nivel from Central.EstructuraxCC where compania='$Compania[0]' and Anio='$Anio' order by Nivel desc";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		echo ExError();
		$Nivel = $fila[0] + 1;
	}
	if($Guardar)
	{
		if(!$Editar)
		{
			$cons="Insert into Central.EstructuraxCC(Compania,Anio,Nivel,Digitos) 
			values ('$Compania[0]','$Anio','$Nivel','$Digitos')";		   
		}
		else
		{
			$cons = "Update central.EstructuraxCC set Digitos = '$Digitos' where
			Compania = '$Compania[0]' and Anio = '$Anio' and Nivel = '$Nivel'";
		}
		$res=ExQuery($cons);
		echo ExError($res);
		?><script language="javascript">location.href='ConfEstructuraCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';</script><?
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Digitos.value == ""){alert("Ingrese un Numero de Digitos");return false;}
	}
</script>
<? 
	if($Editar)
	{
		$cons = "Select Nivel,Digitos from Central.EstructuraxCC where compania='$Compania[0]' and Anio='$Anio' and Nivel = '$Nivel'";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Nivel = $fila[0]; $Digitos=$fila[1];
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="Anio" value="<? echo $Anio?>" />
<input type="hidden" name="Editar" value="<? echo $Editar?>" />
   <table cellpadding="4"  border="1" bordercolor="<?echo $Estilo[1]?>" style="font-family:<?echo $Estilo[8]?>;font-size:12px;font-style:<?echo $Estilo[10]?>">
	<tr><td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Estructura Centro de Costos A&ntilde;o <? echo $Anio?></td></tr>
    	<td bgcolor="#e5e5e5">Nivel</td>
        <td><input type="text" name="Nivel" value="<? echo $Nivel?>" readonly maxlength="3" size="3" /></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5">Digitos</td>
        <td><input type="text" name="Digitos" value="<? echo $Digitos?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)" 
        maxlength="3" size="3" /></td>
    </tr>
	</table>
<input type="submit" name="Guardar" value="Guardar" />
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="button" name="Cancelar" value="Cancelar" onClick="location.href='ConfEstructuraCC.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"  />
</form>
</body>